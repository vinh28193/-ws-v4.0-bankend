<?php


namespace common\promotion;

use yii\base\BaseObject;

class PromotionResponse extends BaseObject
{

    public $success = false;
    public $message = 'Không có trương trình nào phù hợp';
    public $errors = [];
    public $details = [];
    public $discount = 0;

}