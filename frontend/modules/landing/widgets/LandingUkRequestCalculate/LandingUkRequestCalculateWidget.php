<?php
namespace weshop\modules\landing\views\widgets\LandingUkRequestCalculate;

use weshop\views\weshop\widgets\BaseWidget;
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 04/09/2018
 * Time: 09:53
 */
class LandingUkRequestCalculateWidget extends BaseWidget
{
    public $block = [];
    public function run(){
        return $this->render("LandingUkRequestCalculateView", [
        ]);
    }
}