<?php


namespace frontend\modules\landing\controllers;



use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class LandingController extends CmsController
{

    public $type = WsPage::TYPE_LANDING;

    public $layout = '@frontend/views/layouts/landing';



}