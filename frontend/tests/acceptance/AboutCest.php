<?php
use yii\helpers\Url as Url;

class AboutCest
{
    public function ensureThatAboutWorks(old $I)
    {
        $I->amOnPage(Url::toRoute('/site/about'));
        $I->see('About', 'h1');
    }
}
