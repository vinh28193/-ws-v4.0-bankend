<?php namespace common\tests\models;

use common\models\Customer;
use common\models\Order;
use Yii;

class OrderTest extends \common\tests\_support\WeshopTestUnit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testRules()
    {
        $this->specify('test with rule required', function () {
            $param = [];
            $model = new Order;
            $model->load(['Order' => $param]);
            $model->validate();
            $requiredMessage = '{attribute} cannot be blank.';
            verify($model->hasErrors('store_id'))->true();
            verify($model->getFirstError('store_id'))->equals(Yii::t('yii', $requiredMessage, ['attribute' => $model->getAttributeLabel('store_id')]));
            verify($model->hasErrors('type_order'))->true();
            verify($model->getFirstError('type_order'))->equals(Yii::t('yii', $requiredMessage, ['attribute' => $model->getAttributeLabel('type_order')]));
            verify($model->hasErrors('portal'))->true();
            verify($model->hasErrors('quotation_status'))->true();
            verify($model->hasErrors('is_quotation'))->true();
            verify($model->hasErrors('customer_id'))->true();
            verify($model->hasErrors('receiver_email'))->true();
            verify($model->hasErrors('receiver_name'))->true();
            verify($model->hasErrors('receiver_phone'))->true();
            verify($model->hasErrors('receiver_address'))->true();
            verify($model->hasErrors('receiver_country_id'))->true();
            verify($model->hasErrors('receiver_country_name'))->true();
            verify($model->hasErrors('receiver_province_id'))->true();
            verify($model->hasErrors('receiver_province_name'))->true();
            verify($model->hasErrors('receiver_district_id'))->true();
            verify($model->hasErrors('receiver_district_name'))->true();
            verify($model->hasErrors('receiver_post_code'))->true();
            verify($model->hasErrors('receiver_address_id'))->true();
            verify($model->hasErrors('payment_type'))->true();
            verify($model->hasErrors('sale_support_id'))->true();
            verify($model->hasErrors('support_email'))->true();
            verify($model->hasErrors('total_quantity'))->true();
            verify($model->hasErrors('seller_id'))->true();
            verify($model->hasErrors('seller_name'))->true();
            verify($model->hasErrors('seller_store'))->true();
            verify($model->hasErrors('total_final_amount_local'))->true();
            verify($model->hasErrors('total_promotion_amount_local'))->true();
            verify($model->hasErrors('current_status'))->true();
        });

        $this->specify('test with rule integer', function () {
            $integerAttributes = [
                'store_id', 'is_quotation', 'quotation_status', 'customer_id', 'receiver_country_id',
                'receiver_province_id', 'receiver_district_id', 'receiver_address_id', 'sale_support_id', 'coupon_time', 'is_email_sent', 'is_sms_sent',
                'total_quantity', 'promotion_id', 'difference_money', 'seller_id', 'purchase_account_id', 'new', 'purchased', 'seller_shipped',
                'stockin_us', 'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'cancelled', 'lost', 'created_at', 'updated_at', 'remove',
            ];
            foreach ($integerAttributes as $name) {
                $this->specify("test attribute $name not valid", function () use ($name) {
                    $model = new Order;
                    $model->load(['Order' => [$name => 'this is Text not Integer']]);
                    $model->validate();
                    verify($model->hasErrors($name))->true();
                    verify($model->getFirstError($name))->equals(Yii::t('yii', '{attribute} must be an integer.', ['attribute' => $model->getAttributeLabel($name)]));
                });
            }

            foreach ($integerAttributes as $name) {
                 /**
                  * nhũng attribute này còn được validate bởi các rule khác
                  */
                $breaks = [
                    'customer_id','receiver_address_id', 'receiver_country_id', 'receiver_district_id',
                    'receiver_province_id', 'sale_support_id', 'seller_id', 'store_id',
                ];
                $this->specify("test attribute $name valid", function () use ($name,$breaks) {
                    $param = [$name => rand(1, 9999)];
                    if($name === 'quotation_status' || $name === 'difference_money'){
                        $param[$name] = 1;
                    }
                    elseif ($name === 'customer_id') {
//                        $customer = Customer::findOne(1);
//                        $param[$name] = $customer->id;
                        return;
                    }elseif (in_array($name,$breaks)){
                        return;
                    }
                    $model = new Order;
                    $model->load(['Order' => $param]);
                    $model->validate();
                    verify($model->hasErrors($name))->false();
                    verify($model->getFirstError($name))->null();
                });
            }
        });
    }
}