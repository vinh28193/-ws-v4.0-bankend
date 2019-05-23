<?php
/**
 * Created by PhpStorm.
 * User: ducquan
 * Date: 23/9/2015
 * Time: 14:06 PM
 */

namespace weshop\views\weshop\widgets\analytics;

use weshop\views\weshop\widgets\BaseWidget;

class MetaWidget extends BaseWidget
{
    public $isPortal = 0;

    public function run()
    {
        return $this->render('metaCode', [
            'storeData' => $this->getWebsite()->getStoreId(),
            'website' => $this->getWebsite()
        ]);
    }

}