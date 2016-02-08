<?php 

use PseCalc\PseCalc;

class PseTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->pseCalc = new PseCalc;
    }

    public function testBuyReturnsArray()
    {
        $price  = 5.1;
        $shares = 10;
        $this->assertTrue(is_array($this->pseCalc->buy($price, $shares)));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionIfNonNumericIsPassed()
    {
        $this->pseCalc->buy('1', '1');
    }
}
