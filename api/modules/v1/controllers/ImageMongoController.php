<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/23/2019
 * Time: 11:09 AM
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\helpers\ChatHelper;
use common\models\db\Image;
use common\models\PackageItem;
use Yii;

class ImageMongoController extends BaseApiController
{
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true)
            ],
        ];
    }

    public function actionCreate() {
        $post = Yii::$app->request->post();
        $request = new \common\modelsMongo\Image();
        $request->link_img = $post['link_image'];
        $request->order_path = $post['Order_path'];
        if ($request->save()) {
            return $this->response(false, 'create link image error', $request);
        }
        return $this->response(true, 'create link image success', $request);
    }
}