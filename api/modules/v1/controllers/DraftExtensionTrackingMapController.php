<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/13/2019
 * Time: 9:34 AM
 */

namespace api\modules\v1\controllers;
use common\models\db\Order;
use Yii;
use api\controllers\BaseApiController;

class DraftExtensionTrackingMapController extends BaseApiController
{
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
}