<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-03
 * Time: 11:07
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\draft\DraftDataTracking;
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
        $page = \Yii::$app->request->get('p',1);
        $limit = \Yii::$app->request->get('l',20);
//        $modelSearch = new DraftPackageItemSearch();
//        $dataProvider = $modelSearch->search($this->get);
//        return $this->response(true, $modelSearch->createResponseMessage(), $dataProvider);
        $model = DraftDataTracking::find()->with([
            'draftExtensionTrackingMap',
            'trackingCode',
            'draftBoxmeTracking',
            'draftMissingTracking',
            'draftWastingTracking',
            'draftPackageItem'])->where(['is not', 'product_id', null]);
        $countD = clone $model;
        $data['_items'] = $model->limit($limit)->offset($page*$limit - $limit)->asArray()->orderBy('id desc')->all();
        $data['_total'] = $countD->count();
//        $data['current'] = $countD->count();
//        $data['_links']['last'] = $model->count();
//        $data['_links']['next'] = "";
//        $data['_links']['self'] = "";
//        $data['_meta']['currentPage'] = "";
//        $data['_meta']['pageCount'] = "";
//        $data['_meta']['perPage'] = "";
//        $data['_meta']['totalCount'] = "";
        return $this->response(true, "Success", $data);
    }
}