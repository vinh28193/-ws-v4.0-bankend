<?php


namespace frontend\modules\cms\controllers;

use Yii;
use common\models\cms\PageForm;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class HomeController extends CmsController
{
    /*
     public $_uuid ;
     public function actionU()
     {
         $fingerprint = null;
         $post = $this->request->post();
         if (isset($post['fingerprint'])) {
             $fingerprint = $post['fingerprint'];
             Yii::info("fingerprint : ".$fingerprint);
         }
         if (!Yii::$app->getRequest()->validateCsrfToken() || $fingerprint === null ) {
             return ['success' => false,'message' => 'Form Security Alert', 'data' => ['content' => ''] ];
         }
         $this->_uuid = $fingerprint;
         Yii::info("_uuid : ".$this->_uuid);

         if (!Yii::$app->user->isGuest) {
             $this->_uuid = Yii::$app->user->identity->getId().'WS'.Yii::$app->user->identity->email;
         }else {
             $this->_uuid = isset($this->_uuid) ? $this->_uuid : 99999;
         }

         if($this->_uuid){
             if((YII_ENV == 'dev' and YII_DEBUG == true) || (Yii::$app->params['ENV'] == true) ){
                 // ENV DEV /  TEST
                 $this->gaWs();
             }else if((YII_ENV == 'prod' and YII_DEBUG == false) || (Yii::$app->params['ENV'] == false) ) {
                 // ENV PROD
             }
         }else {
             return ['success' => false,'message' => 'fingerprint null', 'data' => ['content' => ''] ];
          }
         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         return 'Ok ! '.$fingerprint;
     }

     public $setDocumentPath = '/HomeWs';
     public function gaWs()
     {
         Yii::info("HOME Pages GA WS");
         Yii::$app->ga->request()
             ->setClientId($this->_uuid)
             ->setDocumentPath($this->setDocumentPath)
             ->setAsyncRequest(true)
             ->sendPageview();
     }
     */

    public function actionIndex()
    {
        if (($data = $this->renderBlock(1, 6)) === false) {
            return $this->redirect('@frontend/views/common/404');
        }
        $this->isShow = true;
//        $data = [];
        return $this->render('index', ['data' => $data]);
    }

    public function actionVoucher()
    {
        $data = file_get_contents(\Yii::getAlias('@webroot') . '/data/data_voucher.json');
        $data = json_decode($data, true);
        return $this->render('voucher', ['web' => $this->storeManager, 'data' => $data]);
    }

    public function actionVoucherDetail($id = null)
    {
        $data = \Yii::$app->cache->get($id);
        return $this->render('detailVoucher', ['data' => $data, 'web' => $this->storeManager]);
    }

}
