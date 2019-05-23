<?php
/**
 * Created by PhpStorm.
 * User: ducquan
 * Date: 23/9/2015
 * Time: 14:06 PM
 */

namespace weshop\views\weshop\widgets\analytics;

use weshop\views\weshop\widgets\BaseWidget;
use Yii;

class AnalyticWidget extends BaseWidget
{
  public $isPortal = 0;

  public function run()
  {
      $typeModule =  substr( Yii::$app->request->url,  1, 9);

    return $this->render('analyticCode', [
        'website' => $this->getWebsite(),
        'typeModule' => $typeModule
    ]);

  }

}