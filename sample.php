<?php
require 'vendor/autoload.php';

use PseCalc\PseCalc;

$pseCalc = new PseCalc();

// To Get Buy and Sell Estimates By Percentage
$budget     = 10000;  // Your Budget Money 
$buyPrice   = 20;     // Stock Buy Price
$percentage = 100;    // Target Percentage when to Sell

$data_percentage = $pseCalc->getEstimateByPercentage($budget, $buyPrice, $percentage);

print_r($data_percentage);
/*
    Data on Buy/Sell by Percentage

    [calculatorType] => Percentage
    [budget] => 10000
    [buyPrice] => 20
    [boardLotSize] => 100
    [sharesPerLot] => 2000
    [totalShares] => 400
    [buyTotal] => 10000
    [buyTotalWithFees] => 8023.6
    [sellTotalWithFees] => 15872.8
    [percent] => 100
    [sellPrice] => 40
    [netEarnings] => 7849.2

*/

// To Get Buy and Sell Estimates By Sell Price
$budget    = 10000; // Your Budget Money 
$buyPrice  = 20;    // Stock Buy Price
$sellPrice = 30;    // Target Price when to Sell

$data_sellprice = $pseCalc->getEstimateBySellPrice($budget, $buyPrice, $sellPrice);

print_r($data_sellprice);
/* 
    Data on Buy/Sell by Sell Price 

    [calculatorType] => Sell Price
    [budget] => 10000
    [buyPrice] => 20
    [boardLotSize] => 100
    [sharesPerLot] => 2000
    [totalShares] => 400
    [buyTotal] => 10000
    [buyTotalWithFees] => 8023.6
    [sellTotalWithFees] => 11904.6
    [percent] => 50
    [sellPrice] => 30
    [netEarnings] => 3881
*/
