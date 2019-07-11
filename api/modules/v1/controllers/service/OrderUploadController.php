<?php


namespace api\modules\v1\controllers\service;

use Yii;
use Exception;
use common\helpers\ExcelHelper;
use yii\helpers\ArrayHelper;

class OrderUploadController extends OrderController
{

    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['upload'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'upload' => ['POST'],
        ];
    }

    public function actionUpload()
    {
        $bodyParams = Yii::$app->request->bodyParams;
        if (($typeUpload = ArrayHelper::getValue($bodyParams, 'type')) === null) {
            $this->response(false, 'Invalid parameter `type`');
        }
        return $this->response(true, 'This action not support now');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $sheets = ExcelHelper::readFromFile('file');

            if (empty($sheets)) {
                $this->response(false, 'File empty');
            }
            if ($typeUpload === 'orderEbay') {

            } elseif ($typeUpload === 'orderAmazon') {

            }else {
                return $this->response(false, "No handle for type $typeUpload");
            }
        }catch (Exception $exception){
            $transaction->rollBack();
            Yii::error($exception);
            return $this->response(false,'Upload failed');
        }

    }
}