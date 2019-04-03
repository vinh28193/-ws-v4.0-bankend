<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-03
 * Time: 11:07
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\draft\DraftPackageItemSearch;

/**
 * Class DraftPackageItemController
 * @package api\modules\v1\controllers
 */
class DraftPackageItemController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'roles' => ['operation','master_operation']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @return array list draft package item
     */
    public function actionIndex()
    {
        $modelSearch = new DraftPackageItemSearch;
        $dataProvider = $modelSearch->search($this->get);
        return $this->response(true, $modelSearch->createResponseMessage(), $dataProvider);
    }
}