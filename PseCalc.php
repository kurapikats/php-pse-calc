<?php namespace PseCalc;

require 'vendor/autoload.php';

/**
 * Philippine Stock Exchange Calculators
 *
 * @author   Jesus B. Nana
 * @version  1.0
 */
class PseCalc extends Calc
{
    /**
     * Get Buy Estimates
     *
     * @param  float  $price   Buy Price
     * @param  int    $shares  Total number of shares to buy
     *
     * @return  mixed  Buy Estimates
     */
    public function buy($price, $shares) {        

        if (!is_numeric($price) or !is_int($shares)) {
            throw new \InvalidArgumentException;
        }

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

        if (!is_numeric($price) or !is_int($shares)) {
            throw new \InvalidArgumentException;
        }

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
     * Get Buy and Sell Estimates by Percentage
     *
     * @param  float   $budget    Budget
     * @param  float   $buyPrice  Stock Buy Price
     * @param  float   $value     Desired Percentage
     *
     * @return  mixed  Buy and Sell Estimates
     */
    public function getEstimateByPercentage($budget, $buyPrice, $value) {

        foreach (func_get_args() as $v) {            
            if (!is_numeric($v)) {
                throw new \InvalidArgumentException;
            }
        }

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

        foreach (func_get_args() as $v) {            
            if (!is_numeric($v)) {
                throw new \InvalidArgumentException;
            }
        }

        return $this->getEstimateBy('sellprice', $budget, $buyPrice, $value);
    }        
}