<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 06/03/2019
 * Time: 15:57
 */

namespace api\modules\v1\api\controllers;


use api\modules\v1\controllers\AuthController;
use common\models\db\Manifest;

class ManifestController extends AuthController
{
    public function actionIndex(){
        return ['data' => [] , 'mess' => "index"];
    }

    public function actionUpdate($id){
        $model = Manifest::find();
    }

    public function actionView(){

    }

    public function actionDelete(){

    }

    public function actionCreate(){
        $model = new Manifest();
        $model->setAttributes($this->post);
        $model->created_at = time();
        $model->updated_at = time();
        $model->created_by = $this->user->id;
        $model->updated_by = $this->user->id;
        $model->save(0);
        return $model->toArray();
    }
}