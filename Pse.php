<?php namespace PseCalc;

/**
 * Philippine Stock Exchange common calculators
 *
 * @author   Jesus B. Nana
 * @version  1.0
 */
class Pse
{
    /**
     * Get percentage difference between two float values
     *
     * Used when a user wants to get the target sell percentage
     * using the desired sell price
     *
     * @param  float  $start  Start buy price
     * @param  float  $end    Target sell price
     *
     * @return  float  Target Percentage
     */
    protected function getPercentageDiff($start, $end)
    {
        return (($end - $start) / $start) * 100;
    }

    /**
     * Increase or Decrease by Percentage
     *
     * Used when a user wants to get the target sell price
     * using the desired percentage
     *
     * @param  float  $start       Start buy price
     * @param  float  $percentage  Target percentage, can use +/- values
     *
     * @return  float  Target Sell Amount
     */
    protected function getSellPriceByPercentage($start, $percentage)
    {
        return (($start * $percentage) / 100) + $start;
    }

    /**
     * Get the PSE Board Lot Size based on stock price
     *
     * @link   http://www.pseacademy.com.ph/LM/investors~details/id-1317973500501/The_Board_Lot_Table.html
     * @param  float  $price  Buy price
     *
     * @return  int  PSE Board Lot Size
     */
    protected function getBoardLotSize($price)
    {
        switch ($price) {
            case $price >= 1000.0000:
                $size = 5;
                break;
            case $price >= 50.0000:
                $size = 10;
                break;
            case $price >= 5.0000:
                $size = 100;
                break;
            case $price >= 0.5000:
                $size = 1000;
                break;
            case $price >= 0.0500:
                $size = 10000;
                break;
            case $price >= 0.0100:
                $size = 100000;
                break;
            case $price >= 0.0001:
                $size = 1000000;
                break;
            default:
                throw new Error("Invalid Board Lot Size");
                break;
        }

        return $size;
    }
}
