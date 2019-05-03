<?php


namespace frontend\widgets;

use yii\helpers\Html;
use yii\bootstrap4\Widget;

class WsLazyCMSWidget extends Widget
{

    public $totalPage = 0;

    public $ajaxUrl;

    public $method = 'GET';

    public $async = true;

    public $lazyClientOptions = [];
}