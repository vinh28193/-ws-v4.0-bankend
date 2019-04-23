<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\draft\DraftDataTracking;
use common\models\Manifest;

class UsSendingController extends BaseApiController
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
            'create' => ['POST'],
            'update' => ['PUT'],
        ];
    }
    public function actionIndex(){
        $manifest_id = \Yii::$app->request->get('id');
        $limit = \Yii::$app->request->get('ps');
        $page = \Yii::$app->request->get('p');
        $limit_t = \Yii::$app->request->get('ps_t');
        $page_t = \Yii::$app->request->get('p_t');
        $limit_e = \Yii::$app->request->get('ps_e');
        $page_e = \Yii::$app->request->get('p_e');
        $tracking_t = \Yii::$app->request->get('t_t');
        $tracking_e = \Yii::$app->request->get('t_e');
        $manifest = Manifest::find()->with(['receiveWarehouse']);
        if($manifest_id){
            $manifest->andWhere(['manifest_id'=>$manifest_id]);
        }
        $tracking = DraftDataTracking::find()->with(['order','product']);
        if($tracking_t){
            $tracking->andWhere(['tracking_code'=>$tracking_t]);
        }
        $ext = DraftDataTracking::find()->with(['order','product']);
        if($tracking_e){
            $ext->andWhere(['tracking_code'=>$tracking_e]);
        }


        $data['_manifest_total'] = $manifest->count();
        $data['_manifest'] = $manifest->limit($limit)->offset($limit*$page - $limit)->orderBy('id desc')->asArray()->all();
        $tracking->andWhere(['manifest_id' => $data['_manifest'][0]['id']]);
        $ext->andWhere(['manifest_id' => $data['_manifest'][0]['id']]);
        $data['_tracking_total'] = $tracking->count();
        $data['_tracking'] = $tracking->limit($limit_t)->offset($limit_t*$page_t - $limit_t)->orderBy('id desc')->asArray()->all();
        $data['_ext_total'] = $ext->count();
        $data['_ext'] = $ext->limit($limit_t)->offset($limit_t*$page_t - $limit_t)->orderBy('id desc')->asArray()->all();
        return $this->response(true, "Success", $data);
    }
}