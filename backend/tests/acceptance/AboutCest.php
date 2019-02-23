<?php
use yii\helpers\Url as Url;

class AboutTestCest
{
    public function ensureThatAboutWorks(old $I)
    {
        $I->amOnPage(Url::toRoute('/site/about'));
        $I->see('About', 'h1');
    }
}
