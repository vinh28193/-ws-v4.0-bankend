<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-03
 * Time: 13:14
 */

namespace common\mail\render;


use common\mail\BaseMailRender;

class OrderInfoRender extends BaseMailRender
{

    public function getView()
    {
        return 'order_info';
    }
}