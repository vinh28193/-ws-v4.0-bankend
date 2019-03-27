<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 27/03/2019
 * Time: 6:32 CH
 */

namespace common\models\weshop;


use yii\base\Model;

class FormRequestPurchase extends Model
{
    public $PPTranId;
    public $emailFragile;
    public $warehouse;
    public $emailPrice;
    public $accountPurchase;
    public $card_payment;
    public $buckAmount;
    public $orderIdPurchase;
    public $note;
    /** @var FormPurchaseItem[] $products */
    public $products;

    public function rules()
    {
        return [
            [['warehouse', 'accountPurchase', 'card_payment', 'orderIdPurchase','products'], 'required'],
        ];
    }
}
