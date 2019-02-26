<?php
use yii\helpers\Url as Url;

class LoginTestCest
{
    public function ensureThatLoginWorks(old $I)
    {
        $I->amOnPage('/site/login');
        $I->see('Login', 'h1');

        $I->amGoingTo('try to login with correct credentials');
        $I->fillField('input[name="LoginForm[username]"]', 'weshop2019');
        $I->fillField('input[name="LoginForm[password]"]', 'weshop@123');
        $I->click('login-button');
        $I->wait(2); // wait for button to be clicked

        $I->expectTo('see user info');
        $I->see('Logout');
    }
}
