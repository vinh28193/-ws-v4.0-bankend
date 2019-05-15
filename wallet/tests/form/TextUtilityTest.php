<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 12/06/2018
 * Time: 14:45
 */

namespace wallet\tests\form;

use common\components\TextUtility;
use PHPUnit\Framework\TestCase;


class TextUtilityTest extends TestCase
{
    public $test;



    public function setUp()
    {
        $this->test = new TextUtility();
    }

    public function testValuereturn()
    {
        $error_code = $this->test->GetMoneyHieghtValueAmz(4500);
        $this->assertTrue($error_code == '25');
    }

}