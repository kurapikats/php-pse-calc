<?php namespace kurapikats\psecalc;

require 'Pse.php';

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

    public function __construct() {
        $this->minCommission = 20;
        $this->commission    = 0.25;
        $this->commissionVat = 12;
        $this->transFee      = 0.005;
        $this->sccp          = 0.01;
        $this->salesTax      = 0.5;
    }

    /**
     * Get Buy Estimates
     *
     * @param  float  $price   Buy Price
     * @param  int    $shares  Total number of shares to buy
     *
     * @return  mixed  Buy Estimates
     */
    public function buy($price, $shares) {
        $price  = (float) $price;
        $shares = (int) $shares;

        $gross          = $price * $shares;
        $commission     = $this->getCommission($gross);
        $commissionVat  = $this->getCommissionVat($commission);
        $transFee       = $this->getPseTransFee($gross);
        $sccp           = $this->getSccp($gross);
        $buyFees        = $this->getBuyFees($commission, $commissionVat,
                            $transFee, $sccp);
        $totalAmount    = $this->getBuyNetAmount($gross, $buyFees);

        $results['price']           = $price;
        $results['shares']          = $shares;
        $results['gross']           = $gross;
        $results['commission']      = $commission;
        $results['commissionVat']   = $commissionVat;
        $results['transFee']        = $transFee;
        $results['sccp']            = $sccp;
        $results['buyFees']         = $buyFees;
        $results['totalAmount']     = $totalAmount;

        return $results;
    }

    /**
     * Get Sell Estimates
     *
     * @param  float  $price   Sell Price
     * @param  int    $shares  Total number of shares to sell
     *
     * @return  mixed  Sell Estimates
     */
    public function sell($price, $shares) {
        $price  = (float) $price;
        $shares = (int) $shares;

        $gross          = $price * $shares;
        $commission     = $this->getCommission($gross);
        $commissionVat  = $this->getCommissionVat($commission);
        $transFee       = $this->getPseTransFee($gross);
        $sccp           = $this->getSccp($gross);
        $salesTax       = $this->getSalesTax($gross);
        $sellFees       = $this->getSellFees($commission, $commissionVat,
                            $transFee, $sccp, $salesTax);
        $totalAmount    = $this->getSellNetAmount($gross, $sellFees);

        $results['price']           = $price;
        $results['shares']          = $shares;
        $results['gross']           = $gross;
        $results['commission']      = $commission;
        $results['commissionVat']   = $commissionVat;
        $results['transFee']        = $transFee;
        $results['sccp']            = $sccp;
        $results['salesTax']        = $salesTax;
        $results['sellFees']        = $sellFees;
        $results['totalAmount']     = $totalAmount;

        return $results;
    }

    /**
     * Get Commission (VAT)
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  Commission
     */
    private function getCommission($gross) {
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
    private function getCommissionVat($commission) {
        return ($commission * $this->commissionVat) / 100;
    }

    /**
     * Get Philippine Stock Exchange Transaction Fee
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  PSE Transfer Fee
     */
    private function getPseTransFee($gross) {
        return ($gross * $this->transFee) / 100;
    }

    /**
     * Get Securities Clearing Corporation of The Philippines Fee (SCCP)
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  SCCP
     */
    private function getSccp($gross) {
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
    private function getBuyFees($commission, $commissionVat, $transFee, $sccp) {
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
    private function getSellFees($commission, $commissionVat, $transFee, $sccp,
        $salesTax) {

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
    private function getBuyNetAmount($gross, $buyFees) {
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
    private function getSellNetAmount($gross, $sellFees) {
        return $gross - $sellFees;
    }

    /**
     * Get Sales Tax
     *
     * @param  float  $gross  Gross Amount
     *
     * @return  float  Sales Tax
     */
    private function getSalesTax($gross) {
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
    private function recomputeBudget($budget, $buyPrice, $totalShares, 
        $boardLotSize) {
        
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
    public function getEstimateBy($type, $budget, $buyPrice, $value) {

        $budget     = (float) $budget;
        $buyPrice   = (float) $buyPrice;
        $value      = (float) $value;

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
        $totalShares    = floor($budget / $sharesPerLot) * $boardLotSize;

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

    /**
     * Get Buy and Sell Estimates by Percentage
     *
     * @param  float   $budget    Budget
     * @param  float   $buyPrice  Stock Buy Price
     * @param  float   $value     Desired Percentage
     *
     * @return  mixed  Buy and Sell Estimates
     */
    public function getEstimateByPercentage($budget, $buyPrice, $value) {
        return $this->getEstimateBy('percentage', $budget, $buyPrice, $value);
    }

    /**
     * Get Buy and Sell Estimates by Sell Price
     *
     * @param  float   $budget    Budget
     * @param  float   $buyPrice  Stock Buy Price
     * @param  float   $value     Desired Sell Price
     *
     * @return  mixed  Buy and Sell Estimates
     */
    public function getEstimateBySellPrice($budget, $buyPrice, $value) {
        return $this->getEstimateBy('sellprice', $budget, $buyPrice, $value);
    }

}
