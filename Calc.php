<?php namespace PseCalc;

require 'vendor/autoload.php';

/**
 * Philippine Stock Exchange Calculator Library
 *
 * @author   Jesus B. Nana
 * @version  1.0
 */
class Calc extends Pse
{
    private $minCommission;
    private $commission;
    private $commissionVat;
    private $transFee;
    private $sccp;
    private $salesTax;

    public function __construct()
    {
        $this->minCommission = 20;
        $this->commission    = 0.25;
        $this->commissionVat = 12;
        $this->transFee      = 0.005;
        $this->sccp          = 0.01;
        $this->salesTax      = 0.5;
    }

    /**
     * Get Commission (VAT)
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  Commission
     */
    protected function getCommission($gross)
    {
        $commission = ($gross * $this->commission) / 100;

        if ($commission < $this->minCommission) {
            $commission = $this->minCommission;
        }

        return $commission;
    }

    /**
     * Get Commission Value Added Tax (VAT)
     *
     * @param  float  $gross  Commission
     *
     * @return  float  Commission VAT
     */
    protected function getCommissionVat($commission)
    {
        return ($commission * $this->commissionVat) / 100;
    }

    /**
     * Get Philippine Stock Exchange Transaction Fee
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  PSE Transfer Fee
     */
    protected function getPseTransFee($gross)
    {
        return ($gross * $this->transFee) / 100;
    }

    /**
     * Get Securities Clearing Corporation of The Philippines Fee (SCCP)
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  SCCP
     */
    protected function getSccp($gross)
    {
        return ($gross * $this->sccp) / 100;
    }


    /**
     * Get Total Buy Fees
     *
     * @param  float  $commission     Commission Fee
     * @param  float  $commissionVat  Commission VAT Fee
     * @param  float  $transFee       PSE Transfer Fee
     * @param  float  $sccp           SCCP Fee
     *
     * @return  float  Total Buy Fees
     */
    protected function getBuyFees($commission, $commissionVat, $transFee, $sccp)
    {
        return $commission + $commissionVat + $transFee + $sccp;
    }

    /**
     * Get Total Sell Fees
     *
     * @param  float  $commission     Commission Fee
     * @param  float  $commissionVat  Commission VAT Fee
     * @param  float  $transFee       PSE Transfer Fee
     * @param  float  $sccp           SCCP Fee
     * @param  float  $salexTax       Sales Tax Fee
     *
     * @return  float  Total Sell Fees
     */
    protected function getSellFees($commission, $commissionVat, $transFee, $sccp,
        $salesTax)
    {
        $buyFees = $this->getBuyFees($commission, $commissionVat, $transFee,
            $sccp);

        return $buyFees + $salesTax;
    }

    /**
     * Get Buy Net Total
     *
     * @param  float  $gross    Gross Amount
     * @param  float  $buyFees  Total Buy Fees
     *
     * @return  float  Buy Net Total
     */
    protected function getBuyNetAmount($gross, $buyFees)
    {
        return $gross + $buyFees;
    }

    /**
     * Get Sell Net Total
     *
     * @param  float  $gross  Gross Amount
     * @param  float  $sellFees  Total Sell Fees
     *
     * @return  float  Sell Net Total
     */
    protected function getSellNetAmount($gross, $sellFees)
    {
        return $gross - $sellFees;
    }

    /**
     * Get Sales Tax
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  Sales Tax
     */
    protected function getSalesTax($gross)
    {
        return ($gross * $this->salesTax) / 100;
    }

    /**
     * Recompute Budget 
     * 
     * @param  int    $budget        Budget
     * @param  float  $buyPrice      Buy Price
     * @param  int    $totalShares   Total Number of Shares
     * @param  int    $boardLotSize  Board Lot Size
     * 
     * @return  mixed  Buy Total with Fees and Total number of Shares 
     */
    protected function recomputeBudget($budget, $buyPrice, $totalShares,
        $boardLotSize)
    {
        recomputeBudget:

        $buyTotalWithFees = $this->buy($buyPrice, $totalShares)['totalAmount'];

        if ($buyTotalWithFees > $budget) {
            $totalShares = $totalShares - $boardLotSize;
            goto recomputeBudget;
        }

        $data = ["buyTotalWithFees" => $buyTotalWithFees,
                 "totalShares"      => $totalShares];

        return $data;
    }

    /**
     * Get Buy and Sell Estimates
     *
     * @param  string  $type      Can be "percentage" or "sellprice"
     * @param  float   $budget    Budget
     * @param  float   $buyPrice  Stock Buy Price
     * @param  float   $value     Desired Percentage or Sell Price
     *
     * @return  mixed  Buy and Sell Estimates
     */
    protected function getEstimateBy($type, $budget, $buyPrice, $value)
    {
        switch ($type) {
            case 'percentage':
                // the $value here is the target percentage
                $sellPrice      = $this->getSellPriceByPercentage($buyPrice, $value);
                $percent        = $value;
                $calculatorType = "Percentage";
                break;
            case 'sellprice':
                // the $value here is the target sell price
                $percent        = $this->getPercentageDiff($buyPrice, $value);
                $sellPrice      = $value;
                $calculatorType = "Sell Price";
                break;
        }

        $boardLotSize   = $this->getBoardLotSize($buyPrice);
        $sharesPerLot   = $buyPrice * $boardLotSize;
        $totalShares    = (int) floor($budget / $sharesPerLot) * $boardLotSize;

        if ($totalShares < $boardLotSize) {
            $results['error'] = "Not enough Budget";
            return $results;
        }

        $buyTotal           = $buyPrice * $totalShares;
        $computedBudget     = $this->recomputeBudget($budget, $buyPrice,
                              $totalShares, $boardLotSize);
        $totalShares        = $computedBudget['totalShares'];
        $buyTotalWithFees   = $computedBudget['buyTotalWithFees'];
        $sellTotalWithFees  = $this->sell($sellPrice, $totalShares)['totalAmount'];
        $netEarnings        = ($sellTotalWithFees - $buyTotalWithFees);

        $results['calculatorType']      = $calculatorType;
        $results['budget']              = $budget;
        $results['buyPrice']            = $buyPrice;
        $results['boardLotSize']        = $boardLotSize;
        $results['sharesPerLot']        = $sharesPerLot;
        $results['totalShares']         = $totalShares;
        $results['buyTotal']            = $buyTotal;
        $results['buyTotalWithFees']    = $buyTotalWithFees;
        $results['sellTotalWithFees']   = $sellTotalWithFees;
        $results['percent']             = $percent;
        $results['sellPrice']           = $sellPrice;
        $results['netEarnings']         = $netEarnings;

        return $results;
    }
}
