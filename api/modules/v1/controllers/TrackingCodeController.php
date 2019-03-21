<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-21
 * Time: 08:52
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\TrackingCode;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class TrackingCodeController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['operation', 'master_operation']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @return array list of tracking code
     */
    public function actionIndex()
    {

        $start = microtime(true);
        $queryParams = Yii::$app->request->getQueryParams();
        $query = TrackingCode::find();
        $query->filter($queryParams);
        $cloneQuery = (clone $query)->limit(-1)->offset(-1)->orderBy([]);
        $totalCount = $cloneQuery->select([new Expression('`tracking_code`.``id`')])->count(new Expression('`tracking_code`.`id`'));
        $page = ArrayHelper::getValue($queryParams, 'p', 1);
        $pageSize = ArrayHelper::getValue($queryParams, 'ps', 20);
        $totalPage = (int)(($totalCount + $pageSize - 1) / $pageSize);
        $offset = ($page - 1) * $pageSize;

        $summaryQuery = new Query();
        $summaryQuery->from(['c' => $cloneQuery->select(['seller_weight', 'seller_quantity', 'local_warehouse_weight', 'local_warehouse_quantity'])]);
        $summaryQuery->select([
            'totalSellerWeight' => new Expression('SUM(`c`.`seller_weight`)'),
            'totalSellerQuantity' => new Expression('SUM(`c`.`seller_quantity`)'),
            'totalWarehouseWeight' => new Expression('SUM(`c`.`local_warehouse_weight`)'),
            'totalWarehouseQuantity' => new Expression('SUM(`c`.`local_warehouse_quantity`)')
        ]);
        $summary = $summaryQuery->all(TrackingCode::getDb());
        $query->limit($pageSize)->offset($offset);
        $data = [
            '_items' => $query->all(),
            '_meta' => [
                'totalCount' => $totalCount,
                'pageCount' => $totalPage,
                'currentPage' => $page,
                'perPage' => $pageSize
            ],
            '_summary' => $summary
        ];
        $time = microtime(true) - $start;
        Yii::info("action execute time: " . sprintf('%.3f', $time), __METHOD__);
        return $this->response(true, 'Ok', $data);

    }
}