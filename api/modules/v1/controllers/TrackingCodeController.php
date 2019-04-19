<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-21
 * Time: 08:52
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\components\db\ActiveQuery;
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
//        $queryParams = Yii::$app->request->getQueryParams();
//        $query = TrackingCode::find();
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSizeParam' => 'ps',
//                'pageParam' => 'p',
//                'params' => $queryParams,
//            ],
//            'sort' => [
//                'params' => $queryParams,
//            ],
//        ]);
//
//        return $this->response(true, 'Ok', $dataProvider);
        $page_m = \Yii::$app->request->get('p_m',1);
        $limit_m = \Yii::$app->request->get('ps_m',20);
        $manifest_Code = \Yii::$app->request->get('manifest_code');
        $trackingC = \Yii::$app->request->get('trackingC');
        $trackingU = \Yii::$app->request->get('trackingU');
        $trackingW = \Yii::$app->request->get('trackingW');
        $trackingM = \Yii::$app->request->get('trackingM');

        $page = \Yii::$app->request->get('p',1);
        $limit = \Yii::$app->request->get('ps',20);
//        $model = DraftDataTracking::find()->with([
//            'draftExtensionTrackingMap',
//            'trackingCode',
//            'draftBoxmeTracking',
//            'draftMissingTracking',
//            'draftWastingTracking',
//            'draftPackageItem'])->where(['is not', 'product_id', null]);
//        $countD = clone $model;
//        $data['_items'] = $model->limit($limit)->offset($page*$limit - $limit)->asArray()->orderBy('id desc')->all();
//        $data['_total'] = $countD->count();
        $model = Manifest::find()->where(['active' => 1]);
        //#Todo filter

        $manifests = clone $model;
        if($manifest_Code){
            $model->andWhere(['manifest_code'=>$manifest_Code]);
            $manifests->andWhere(['manifest_code'=>$manifest_Code]);
        }
        if(!$trackingC && !$trackingM && !$trackingU && !$trackingW){
            $data['_total_manifest'] = $manifests->count();
            $data['_manifest'] = $manifests->with(['receiveWarehouse','sendWarehouse'])->orderBy('id desc')->limit($limit_m)->offset($limit_m*$page_m - $limit_m)->asArray()->all();
        }
        $model->with([
            'draftWastingTrackings' => function($q){
                /** @var ActiveQuery $q */
                $tracking = \Yii::$app->request->get('trackingW');
                if($tracking){
                    $q->andWhere(['like','tracking_code',$tracking]);
                }
//                $q->orderBy('id desc')->limit($this->get['l'])->offset($this->get['l']*$this->get['p'] - $this->get['l']);
            }
            ,'draftMissingTrackings' => function($q){
                /** @var ActiveQuery $q */
                $tracking = \Yii::$app->request->get('trackingM');
                if($tracking){
                    $q->andWhere(['like','tracking_code',$tracking]);
                }
//                $q->orderBy('id desc')->limit($this->get['l'])->offset($this->get['l']*$this->get['p'] - $this->get['l']);
            }
            ,'draftPackageItems' => function($q){
                /** @var ActiveQuery $q */
                $tracking = \Yii::$app->request->get('trackingC');
                if($tracking){
                    $q->andWhere(['like','tracking_code',$tracking]);
                }
//                $q->orderBy('id desc')->limit($this->get['l'])->offset($this->get['l']*$this->get['p'] - $this->get['l']);
            }
            ,'unknownTrackings' => function($q){
                /** @var ActiveQuery $q */
                $tracking = \Yii::$app->request->get('trackingU');
                if($tracking){
                    $q->andWhere(['like','tracking_code',$tracking]);
                }
//                $q->orderBy('id desc')->limit($this->get['l'])->offset($this->get['l']*$this->get['p'] - $this->get['l']);
            }
        ]);
        $data['_items'] = $model->limit($limit)->offset($page*$limit - $limit)->orderBy('id desc')->asArray()->one();
        return $this->response(true, "Success", $data);
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
        $message = ["Sending `$manifest->manifest_code` success"];
        foreach ($tokens as $name => $token){
            $error = isset($token['error']) ? count($token['error']) : 0;
            $message[] = "from `$name` {$token['total']} executed $error error/{$token['success']} success";
        }
        $message = implode(", ",$message);
//        ChatHelper::push($message,$model->ordercode,'WS_CUSTOMER', 'SYSTEM');
//        Yii::$app->wsLog->order->push('Us Sending', null, [
//            'id' => $model->order->ordercode,
//            'request' => $this->post,
//        ]);
        return $this->response(true, $message);
    }

    function getList(){

    }
}