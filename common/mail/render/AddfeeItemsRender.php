<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-03
 * Time: 17:25
 */

namespace common\mail\render;


use common\mail\BaseMailRender;

class AddfeeItemsRender extends BaseMailRender
{

    public function getView()
    {
        return 'addfee_info';
    }
}