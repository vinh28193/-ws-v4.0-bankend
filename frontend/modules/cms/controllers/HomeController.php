<?php


namespace frontend\modules\cms\controllers;

use Yii;
use common\models\cms\PageForm;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class HomeController extends CmsController
{
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
        if($this->_uuid){
            if(YII_ENV == 'dev' and YII_DEBUG == true){
                // ENV DEV /  TEST
                $this->gaHomeWs($this->Uuid());
            }else {
                // ENV PROD
            }
        }else {
            return ['success' => false,'message' => 'fingerprint null', 'data' => ['content' => ''] ];
         }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return 'Ok ! '.$fingerprint;
    }

    public function Uuid()
    {
        $uid = 0;
        if (!Yii::$app->user->isGuest) {
            $uid = Yii::$app->user->identity->getId().'WS'.Yii::$app->user->identity->email;
        }else {
            $uid = isset($this->_uuid) ? $this->_uuid : 99999;
        }
        return $uid;
    }

    public function gaHomeWs($UUID)
    {
        Yii::info("HOME GA WS");
        Yii::$app->ga->request()
            ->setClientId($UUID)
            ->setDocumentPath($this->Dopath)
            ->setAsyncRequest(true)
            ->sendPageview();
    }

    public $setDocumentPath = '/HomeWs';

    public function actionIndex()
    {
        if (($data = $this->renderBlock(1,6)) === false) {
            return $this->redirect('@frontend/views/common/404');
        }
        return $this->render('index', ['data' => $data]);
    }


}
