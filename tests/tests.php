<?php
require '../Calc.php';

use kurapikats\psecalc\Calc;

// $math = new ColaPse\Math();

// echo $math->getPercentageDiff(85.95, 91.20);
// echo "\n";
// echo $math->changeByPercentage(85.95, -6.11);
// echo "\n";
// $calc = new ColaPse\Col();
// echo "\n";
// echo $calc->sell(89.65, 870);

// // $pse = new Pse();
// // var_dump($pse->getBoardLotSize($argv[1]));


$calc = new Calc();
// $x = $calc->getSellPriceByPercentage(10000, 20);

$x = $calc->getEstimateByPercentage(10000, 20, 20.93);

//$x = $calc->sell(9800, 100);

print_r($x);
