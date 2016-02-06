Philippine Stock Exchange Calculator
===================

This is a small Calculator tool help Investors in the Philippines plan their Stock Trades and Investments.

----------


Sample Usage
------------

#### <i class="icon-file"></i> To Get Buy and Sell Estimates By **Percentage**
```
<?php
require 'vendor/autoload.php';
use PseCalc\PseCalc;

$pseCalc         = new PseCalc();
$budget          = 10000;  // Your Budget Money 
$buyPrice        = 20;     // Stock Buy Price
$percentage      = 100;    // Target Percentage when to Sell
$data_percentage = $pseCalc->getEstimateByPercentage($budget, $buyPrice, $percentage);

print_r($data_percentage);
```
  **Data on Buy/Sell by Percentage**
  
  *It would return something similar to this:*
```
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

```
#### <i class="icon-file"></i> To Get Buy and Sell Estimates By **Sell Price**
```
$budget         = 10000; // Your Budget Money 
$buyPrice       = 20;    // Stock Buy Price
$sellPrice      = 30;    // Target Price when to Sell
$data_sellprice = $pseCalc->getEstimateBySellPrice($budget, $buyPrice, $sellPrice);

print_r($data_sellprice);
```
  **Data on Buy/Sell by Sell Price**
  
  *It would return something similar to this:*
```
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
```

## Version
1.0.0

## Author
Jesus B. Nana (@kurapikats)

support@taxicomplaints.net

## License
TaxiComplaints (C) 2015-2016 is available under MIT license.

The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

