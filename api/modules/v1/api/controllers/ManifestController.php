<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 06/03/2019
 * Time: 15:57
 */

namespace api\modules\v1\api\controllers;


use api\modules\v1\controllers\AuthController;
use common\models\db\Package;
use common\models\db\PackageItem;
use common\models\Manifest;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ManifestController extends AuthController
{
    public function actionIndex()
    {
        $limit = ArrayHelper::getValue($this->get, 'limit', 20);
        $page = ArrayHelper::getValue($this->get, 'page', 1);
        $model = Manifest::find()->where(['active' => 1])->limit($limit)->offset($page - 1 * $limit)->asArray()->all();
        return $model;
    }

    public function actionUpdate($id)
    {
        $model = Manifest::findIdentity($id);
        $model->setAttributes($this->post);
        $model->save();
        return $model->toArray();
    }

    public function actionView($id)
    {
        $model = Manifest::find()
            ->where(
                [
                    'active' => Manifest::STATUS_ACTIVE,
                    'id' => $id,
                ]
            )->with([
                'packages' => function ($q) {
                    /** @var ActiveQuery $q */
                    $q->with([
                        'packageItems' => function ($qr) {
                            /** @var ActiveQuery $qr */
                            $qr->with([
                                'order' => function ($qr) {
                                    /** @var ActiveQuery $qr */
                                    $qr->where(['remove' => 0]);
                                },
                            ])->where(['remove' => 0]);
                        },
                    ])->where(['remove' => 0]);
                }
            ])->asArray()->limit(1)->one();
        return $model;
    }

    public function actionDelete($id, $is_remove_package = false)
    {
        if (!$id) {
            return [];
        }
        $countPk = 0;
        $countPki = 0;
        if ($is_remove_package && $is_remove_package == 'remove_package') {
            /** @var Manifest $model */
            $model = Manifest::find()->where(['id' => $id, 'active' => 1])->with(['packages'])->limit(1)->one();
            if ($model) {
                $list_id = [];
                foreach ($model->packages as $package) {
                    $list_id[] = $package->package_code;
                }
                $countPk = Package::updateAll(['remove' => 1, 'updated_at' => time()], ['remove' => 0, 'package_code' => $list_id]);
                $countPki = PackageItem::updateAll(['remove' => 1, 'updated_at' => time()], ['remove' => 0, 'package_code' => $list_id]);
            }
        }
        $rows = Manifest::updateAll(['active' => 0, 'updated_at' => time()], ['active' => 1, 'id' => $id]);
        return ['message' => "remove success", 'total' => $rows, 'total_package' => $countPk, 'total_package_item' => $countPki];
    }

    public function actionCreate()
    {
        $model = new Manifest();
        $model->setAttributes($this->post);
        $model->save(0);
        return $model->toArray();
    }
}