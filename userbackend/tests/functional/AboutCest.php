<?php
namespace userbackend\tests\functional;

use userbackend\tests\FunctionalTester;

class AboutCest
{
    public function checkAbout(FunctionalTester $I)
    {
        $I->amOnRoute('site/about');
        $I->see('About', 'h1');
    }
}
