<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-21
 * Time: 08:52
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\helpers\ExcelHelper;
use common\models\Manifest;
use common\models\TrackingCode;
use Yii;
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
            'index' => ['GET'],
            'create' => ['POST']
        ];
    }

    /**
     * @return array list of tracking code
     */
    public function actionIndex()
    {
        $queryParams = Yii::$app->request->getQueryParams();
        $query = TrackingCode::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'ps',
                'pageParam' => 'p',
                'params' => $queryParams,
            ],
            'sort' => [
                'params' => $queryParams,
            ],
        ]);

        return $this->response(true, 'Ok', $dataProvider);
    }

    public function actionCreate()
    {
        $start = microtime(true);
        $post = $this->post;
        $tokens = [];
        if (($store = ArrayHelper::getValue($post, 'store')) === null) {
            return $this->response(false, "create form undefined store !.");
        }
        if (($warehouse = ArrayHelper::getValue($post, 'warehouse')) === null) {
            return $this->response(false, "create form undefined warehouse !.");
        }
        if (($manifest = ArrayHelper::getValue($post, 'manifest')) === null) {
            return $this->response(false, "create form undefined manifest !.");
        }
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $manifest = Manifest::createSafe($manifest, 1, 1);

            foreach (ExcelHelper::readFromFile('file') as $name => $sheet) {
                $count = 0;
                $tokens[$name]['total'] = count($sheet);
                foreach ($sheet as $row) {
                    if (($trackingCode = ArrayHelper::getValue($row, 'TrackingCode')) === null) {
                        $tokens[$name]['error'][] = $row;
                        continue;
                    }
                    $model = new TrackingCode([
                        'tracking_code' => $trackingCode,
                        'store_id' => $manifest->store_id,
                        'manifest_code' => $manifest->manifest_code,
                        'manifest_id' => $manifest->id
                    ]);
                    if (!$model->save(false)) {
                        $tokens[$name]['error'][] = $row;
                    }
                    $count++;
                }
                $tokens[$name]['success'] = $count;
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e);
            return $this->response(false, $e->getMessage());
        }
        $time = microtime(true) - $start;
        $message = ["Sending $manifest->manifest_code success"];
        foreach ($tokens as $name => $token){
            $error = isset($token['error']) ? count($token['error']) : 0;
            $message[] = " from $name {$token['total']} executed $error error/{$token['success']} success";
        }
        $message = implode(", ",$message);
        return $this->response(true, $message);
    }
}