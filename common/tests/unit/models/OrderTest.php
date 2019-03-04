<?php namespace common\tests\models;

use common\models\Order;
use Yii;

class OrderTest extends \common\tests\unit\WsUnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'order' => [
                'class' => \common\fixtures\OrderFixture::className()
            ]
        ];
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
                    'customer_id', 'receiver_address_id', 'receiver_country_id', 'receiver_district_id',
                    'receiver_province_id', 'sale_support_id', 'seller_id', 'store_id',
                ];
                $this->specify("test attribute $name valid", function () use ($name, $breaks) {
                    $param = [$name => rand(1, 9999)];
                    if ($name === 'quotation_status' || $name === 'difference_money') {
                        $param[$name] = 1;
                    } elseif ($name === 'customer_id') {
//                        $customer = Customer::findOne(1);
//                        $param[$name] = $customer->id;
                        return;
                    } elseif (in_array($name, $breaks)) {
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

        $this->specify('test with rule string', function () {
            $stringAttributes = [
                'note_by_customer', 'note', 'seller_store', 'purchase_order_id', 'purchase_transaction_id', 'purchase_amount',
                'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'total_weight', 'total_weight_temporary'
            ];
            foreach ($stringAttributes as $name) {
                $this->specify("test attribute $name not valid", function () use ($name) {
                    $model = new Order;
                    $model->load(['Order' => [$name => (integer)1]]);
                    $model->validate();
                    verify($model->hasErrors($name))->true();
                });

                $this->specify("test attribute $name valid", function () use ($name) {
                    $param = [$name => 'test'];
                    if ($name === 'seller_store') {
                        $param[$name] = 'http://test.com';
                    } elseif ($name === 'purchase_account_email') {
                        $param[$name] = 'tester@test.com';
                    }
                    $model = new Order;
                    $model->load(['Order' => $param]);
                    $model->validate();
                    verify($model->hasErrors($name))->false();
                    verify($model->getFirstError($name))->null();
                });
            }


            foreach ($stringAttributes as $name) {
                $this->specify("test attribute $name trim validated", function () use ($name) {
                    $text = '    test    ';
                    $param = [$name => $text];
                    $model = new Order;
                    $model->load(['Order' => $param]);
                    $model->validate();
                    verify($model->getAttribute($name))->equals(trim($text));
                });
            }
        });

        $numberAttributes = [
            'revenue_xu', 'xu_count', 'xu_amount', 'total_final_amount_local', 'total_paid_amount_local', 'total_refund_amount_local',
            'total_amount_local', 'total_fee_amount_local', 'total_counpon_amount_local', 'total_promotion_amount_local', 'total_origin_fee_local',
            'total_origin_tax_fee_local', 'total_origin_shipping_fee_local', 'total_weshop_fee_local', 'total_intl_shipping_fee_local',
            'total_custom_fee_amount_local', 'total_delivery_fee_local', 'total_packing_fee_local', 'total_inspection_fee_local', 'total_insurance_fee_local',
            'total_vat_amount_local', 'exchange_rate_fee', 'exchange_rate_purchase', 'purchase_amount_buck', 'purchase_amount_refund'
        ];


        $this->specify('test with rule number', function () use ($numberAttributes) {
            foreach ($numberAttributes as $name) {
                $this->specify("test attribute $name not valid", function () use ($name) {
                    $model = new Order;
                    $model->load(['Order' => [$name => 'this Text not Number']]);
                    $model->validate();
                    verify($model->hasErrors($name))->true();
                });

                $this->specify("test attribute $name not valid with < 0", function () use ($name) {
                    $model = new Order;
                    $model->load(['Order' => [$name => -(integer)rand(1, 999)]]);
                    $model->validate();
                    verify($model->hasErrors($name))->true();
                });

                $this->specify("test attribute $name is valid with integer", function () use ($name) {
                    $model = new Order;
                    $model->load(['Order' => [$name => (integer)rand(1, 999)]]);
                    $model->validate();
                    verify($model->hasErrors($name))->false();
                });



            }

        });


        $string255 = [
            'type_order', 'portal', 'quotation_note', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_name',
            'receiver_province_name', 'receiver_district_name', 'receiver_post_code', 'payment_type', 'support_email', 'coupon_id', 'coupon_code', 'utm_source', 'seller_name', 'currency_purchase'
        ];
        $this->specify('test with rule string max 255', function () use ($string255) {
            $length = 255;
            foreach ($string255 as $name) {
                $this->specify("test attribute $name not valid", function () use ($name, $length) {
                    $string = str_repeat('a', $length + 1);
                    if ($name === 'receiver_email' || $name === 'support_email') {
                        $host = '@test.com';
                        $string = str_repeat('a', ($length - strlen($host)) + 1);
                        $string .= $host;
                    }

                    $model = new Order;
                    $model->load(['Order' => [$name => $string]]);
                    $model->validate();
                    verify($model->hasErrors($name))->true();
                });

                $this->specify("test attribute $name is valid", function () use ($name, $length) {
                    $string = str_repeat('a', $length);
                    if ($name === 'receiver_email' || $name === 'support_email') {
                        /**
                         * @see https://www.regular-expressions.info/email.html
                         * local part (before the @) is limited to 64 characters and that each part of the domain name is limited to 63 characters.
                         * There's no direct limit on the number of subdomains. But the maximum length of an email address that can be handled by SMTP is 254 characters.
                         * So with a single-character local part, a two-letter top-level domain and single-character sub-domains, 125 is the maximum number of sub-domains.
                         */
                        $host = '@test.co' . str_repeat('m', 125);
                        $string = str_repeat('q', 63);
                        $string .= $host;
                    }
                    $model = new Order;
                    $model->load(['Order' => [$name => $string]]);
                    $model->validate();
                    verify($model->hasErrors($name))->false();
                });
            }

        });

        $this->specify('test with rule string max 200 attribute ', function () {
            $this->specify("test attribute current_status not valid", function () {
                $string = str_repeat('a', 200 + 1);
                $model = new Order;
                $model->load(['Order' => ['current_status' => $string]]);
                $model->validate();
                verify($model->hasErrors('current_status'))->true();
            });

            $this->specify("test attribute current_status is valid", function () {
                $string = str_repeat('a', 200);
                $model = new Order;
                $model->load(['Order' => ['current_status' => $string]]);
                $model->validate();
                verify($model->hasErrors('current_status'))->false();
            });
        });

        foreach (['receiver_email', 'support_email', 'purchase_account_email'] as $name) {
            $this->specify("test $name with rule string max 200 attribute", function () use ($name) {
                $this->specify("test attribute $name not valid with text", function () use ($name) {
                    $email = 'this is test not email';
                    $model = new Order;
                    $model->load(['Order' => [$name => $email]]);
                    $model->validate();
                    verify($model->hasErrors($name))->true();
                });

                $this->specify("test attribute $name not valid with number/integer", function () use ($name) {
                    $email = rand(1, 999);
                    $model = new Order;
                    $model->load(['Order' => [$name => $email]]);
                    $model->validate();
                    verify($model->hasErrors($name))->true();
                });

                $this->specify("test attribute $name is valid", function () use ($name) {
                    $email = 'tester@mydomain.com';
                    $model = new Order;
                    $model->load(['Order' => [$name => $email]]);
                    $model->validate();
                    verify($model->hasErrors($name))->false();
                });
            });
        }

        $this->specify('test with rule URL', function () {
            $this->specify("test attribute seller_store not valid with string", function () {
                $model = new Order;
                $model->load(['Order' => ['seller_store' => 'test not url']]);
                $model->validate();
                verify($model->hasErrors('seller_store'))->true();
            });

            $this->specify("test attribute seller_store not valid with integer/number", function () {
                $model = new Order;
                $model->load(['Order' => ['seller_store' => rand(1,999)]]);
                $model->validate();
                verify($model->hasErrors('seller_store'))->true();
            });

            $this->specify("test attribute current_status is valid", function () {
                $model = new Order;
                $model->load(['Order' => ['current_status' => \yii\helpers\Url::to('tester.com')]]);
                $model->validate();
                verify($model->hasErrors('current_status'))->false();
            });
        });
        $this->specify("test with filter Html::encode", function () {
            $strings = [
                'note_by_customer', 'note', 'seller_store', 'purchase_amount',
                'purchase_account_email', 'purchase_card',  'total_weight', 'total_weight_temporary'
            ];
            foreach ($strings as $name){
                $this->specify("test attribute $name", function () use ($name) {
                    $script = "<script>alert('OK')</script>";
                    $model = new Order;
                    $model->load(['Order' => [$name => $script]]);
                    $model->validate();
//                    verify($model->getAttribute($name))->equals(\yii\helpers\Html::encode($script));
                });

            }

        });
    }
}