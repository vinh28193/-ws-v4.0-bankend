<?php


namespace frontend\modules\account\views\widgets;


use common\models\Address;
use yii\base\Widget;

class FormAddressWidget extends Widget
{
    public $address;
    public function run()
    {
        $user = \Yii::$app->user->getIdentity();
        if(!$user || \Yii::$app->user->isGuest){
            return "";
        }
        if(!$this->address){
            $this->address = new Address();
        }
        return $this->render('form-address',[
            'address' => $this->address,
            'user' => $user
        ]);
    }
}