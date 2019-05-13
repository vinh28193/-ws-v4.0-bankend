<?php


namespace frontend\modules\checkout\models;

use Yii;
use yii\base\Model;

class SecureForm extends Model
{

    public $loginId;
    public $password;
    public $isNew = false;
    public $rememberMe = true;


}