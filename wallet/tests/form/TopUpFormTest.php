<?php
namespace wallet\tests\form;
use common\components\TextUtility;
use wallet\modules\v1\models\form\ResponePaymentForm;
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 28/05/2018
 * Time: 10:44
 */
class TopUpFormTest extends TestCase
{
    public $test;
    public $price;

    public function setUp()
    {
        $this->test = new ResponePaymentForm($error_code='0000',$wallet_transaction_code='',$payment_transaction='',$total_amount=0,$request_content='',$response_content='');
    }

    public function testStatusCode()
    {
        $error_code = $this->test->status_code;
        $this->assertTrue($error_code == '25');
    }

    public function testTrueIsTrue()
    {
        $foo = true;
        $this->assertTrue($foo=true);
    }
}
