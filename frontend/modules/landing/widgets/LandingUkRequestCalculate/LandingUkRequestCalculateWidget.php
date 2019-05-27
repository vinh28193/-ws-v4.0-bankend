<?php
namespace landing\widgets\LandingUkRequestCalculate;

use landing\LandingWidget;
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 04/09/2018
 * Time: 09:53
 */
class LandingUkRequestCalculateWidget extends LandingWidget
{
    public $block = [];
    public function run(){
        return $this->render("LandingUkRequestCalculateView", [
        ]);
    }
}