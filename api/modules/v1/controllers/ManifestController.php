<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-22
 * Time: 13:25
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;

class ManifestController extends BaseApiController
{

    protected function verbs()
    {
        return [
            'create' => ['POST']
        ];
    }

    public function actionCreate()
    {

    }
}