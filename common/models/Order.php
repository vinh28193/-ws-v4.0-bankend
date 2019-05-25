<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 11:21
 */

namespace common\models;

use common\helpers\WeshopHelper;
use common\models\db\Coupon;
use common\models\db\DraftExtensionTrackingMap;
use common\models\db\Order as DbOrder;
use common\models\db\Promotion;
use common\models\queries\OrderQuery;
use common\rbac\rules\RuleOwnerAccessInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\db\Query;
use yii\db\Expression;

/**
 * @property  Product[] $products
 * @property  Package[] $packages
 */
class Order extends DbOrder implements RuleOwnerAccessInterface
{

    const SCENARIO_UPDATE_RECEIVER = 'updateReceiver';
    const SCENARIO_UPDATE_STATUS = 'updateStatus';
    const SCENARIO_SALE_ASSIGN = 'saleAssign';
    const SCENARIO_REQUEST = 'request';
    const SCENARIO_CONFIRM_PURCHASE = 'confirmPurchase';
    const SCENARIO_UPDATE_ADJUST_PAYMENT = 'editAdjustPayment';
    const SCENARIO_UPDATE_COUPON = 'updateCouponId';
    const SCENARIO_UPDATE_PAY_BACK = 'updatePayBack';
    const SCENARIO_UPDATE_SELLER_REFUND = 'updateSellerRefund';
    const SCENARIO_UPDATE_PROMOTION = 'updatePromotionId';
    const SCENARIO_UPDATE_MARK_SUPPORTING = 'updateMarkSupporting';
    const SCENARIO_UPDATE_ORDER_STATUS = 'updateOrderStatus';
    const SCENARIO_UPDATE_ORDER_TIME = 'updateTimeNull';
    const SCENARIO_UPDATE_READY2PURCHASE = 'updateReady2Purchase';

    /**
     * order type
     */
    const TYPE_SHOP = 'SHOP';
    const ORDER_REQUEST = 'REQUEST';
    const ORDER_POS = 'POS';
    const ORDER_SHIP = 'SHIP';
    /**
     * @since ver 4.0
     * current_status const value
     */
    const STATUS_NEW = 'NEW'; // Lv 1; đơn mới được tạo, next status SUPPORTING
    const STATUS_JUNK = 'JUNK'; // end of status
    const STATUS_SUPPORTING = 'SUPPORTING'; // Lv 2; đơn đang được chăm sóc, next status SUPPORTED
    const STATUS_SUPPORTED = 'SUPPORTED'; // Lv 3; đơn đã được chăm sóc, next status READY_PURCHASE
    const STATUS_READY2PURCHASE = 'READY2PURCHASE'; // Lv 4; đơn đã sẵn sàng mua hàng, next status PURCHASING, PURCHASE_PART or REFUNDING
    const STATUS_PURCHASING = 'PURCHASING'; // Lv 5; đơn đang trong quá trình mua hàng, next status PURCHASED
    const STATUS_PURCHASE_PART = 'PURCHASE_PART'; // Lv 6; đơn đang trong quá trình mua hàng nhưng mới mua được 1 phần, next status PURCHASED
    const STATUS_PURCHASED = 'PURCHASED'; // Lv7; đơn đã mua, next status REFUNDING
    const STATUS_REFUNDING = 'REFUNDING'; //Lv8; đơn đang chuyển hoàn, next status REFUNDED
    const STATUS_REFUNDED = 'REFUNDED'; //Lv8; đơn đã chuyển hoàn, end of status
    const STATUS_CANCEL = 'CANCELLED';
    const STATUS_SELLER_SHIPPED = 'SELLER_SHIPPED';
    const STATUS_STOCK_IN_US = 'STOCK_IN_US';
    const STATUS_STOCK_OUT_US = 'STOCK_OUT_US';
    const STATUS_STOCK_IN_LOCAL = 'STOCK_IN_LOCAL';
    const STATUS_STOCK_OUT_LOCAL = 'STOCK_OUT_LOCAL';
    const STATUS_AT_CUSTOMER = 'AT_CUSTOMER';
    const STATUS_RETURNED = 'RETURNED';

    const STATUS_RE_APPRAISE = 'RE_APPRAISE';  //  re-appraise : Đơn đang càn thậm định lái về giá + log + những vấn đề khác  --> Màu vàng và dừng lại ko cho gửi shipment

    /**
     * request status
     */
    const QUOTATION_STATUS_PENDING = 0;
    const QUOTATION_STATUS_APPROVE = 1;
    const QUOTATION_STATUS_DECLINE = 2;

    /**
     * difference_money
     * 0: mac dinh, 1: lech, 2:ẩn thông báo bằng quyền của Admin
     * @return array
     */
    const DIFF_MONEY_DEFAULT = 0;
    const DIFF_MONEY_LECH = 1;
    const DIFF_MONEY_HIDE = 2;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'insertAttributes' => [
                'class' => \yii\behaviors\AttributesBehavior::className(),
                'attributes' => [
//                    'receiver_country_name' => [
//                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateSystemLocation']
//                    ],
//                    'receiver_province_name' => [
//                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateSystemLocation']
//                    ],
//                    'receiver_district_name' => [
//                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateSystemLocation']
//                    ],
                    'customer_id' => [
                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateCustomer']
                    ],
                    'current_status' => [
                        self::EVENT_BEFORE_INSERT => self::STATUS_NEW
                    ],
                    'store_id' => [
                        self::EVENT_BEFORE_INSERT => function ($event, $attribute) {
                            return Yii::$app->storeManager->getId();
                        },
                    ],
                    'exchange_rate_fee' =>  [
                        self::EVENT_BEFORE_INSERT => function ($event, $attribute) {
                            return Yii::$app->storeManager->getExchangeRate();
                        },
                    ],

                ]
            ],
        ]);
    }

    /**
     * @return array
     */
    public function timestampFields()
    {
        return ArrayHelper::merge(parent::timestampFields(), [
            'new', 'purchase_start', 'purchased', 'seller_shipped', 'stockin_us',
            'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'lost', 'cancelled', 'supporting', 'supported', 'ready_purchase'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->detachBehavior('blameable');
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_UPDATE_STATUS => [
                'current_status', 'supported', 'ready_purchase'
            ],
            self::SCENARIO_CONFIRM_PURCHASE => [
                'current_status'
            ],
            self::SCENARIO_UPDATE_READY2PURCHASE => [
                'current_status', 'ready_purchase'
            ],
            self::SCENARIO_SALE_ASSIGN => [
                'sale_support_id', 'support_email'
            ],
            self::SCENARIO_UPDATE_ADJUST_PAYMENT => [
                'total_paid_amount_local', 'check_update_payment'
            ],
            self::SCENARIO_UPDATE_COUPON => [
                'coupon_id'
            ],
            self::SCENARIO_UPDATE_PROMOTION => [
                'promotion_id'
            ],
            self::SCENARIO_UPDATE_PAY_BACK => [
                'total_refund_amount_local'
            ],
            self::SCENARIO_UPDATE_SELLER_REFUND => [
                'purchase_refund_transaction_id', 'purchase_amount_refund'
            ],
            self::SCENARIO_UPDATE_MARK_SUPPORTING => [
                'current_status', 'mark_supporting'
            ],
            self::SCENARIO_UPDATE_ORDER_STATUS => [
                'current_status', 'new', 'supporting', 'supported', 'ready_purchase', 'purchase_start', 'purchased', 'seller_shipped', 'stockin_us', 'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'cancelled', 'lost'
            ],
            self::SCENARIO_UPDATE_ORDER_TIME => [
                'current_status', 'new', 'supporting', 'supported', 'ready_purchase', 'purchase_start', 'purchased', 'seller_shipped', 'stockin_us', 'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'cancelled', 'lost'
            ],
        ]);
    }

    public function rules()
    {
        return [
            [
                [
                    'type_order', 'customer_id', 'portal', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'receiver_post_code', 'receiver_address_id', 'payment_type'
                ], 'required', 'on' => self::SCENARIO_DEFAULT
            ],
            [
                [
                    'receiver_post_code'
                ], 'required', 'on' => self::SCENARIO_DEFAULT, 'when' => function ($model) {
                return $model->receiver_country_id = 2;
            }
            ],
            [
                [
                    'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address',
                    'receiver_country_id', 'receiver_country_name',
                    'receiver_province_id', 'receiver_province_name',
                    'receiver_district_id', 'receiver_district_name'
                ], 'required', 'on' => self::SCENARIO_UPDATE_RECEIVER
            ],
            [[
                'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address'
            ], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_UPDATE_RECEIVER],
            [[
                'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address',
                'receiver_country_name', 'receiver_province_name', 'receiver_district_name'
            ], 'filter', 'filter' => '\yii\helpers\Html::encode', 'on' => self::SCENARIO_UPDATE_RECEIVER],

            [['receiver_address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['receiver_address_id' => 'id'], 'on' => [self::SCENARIO_UPDATE_RECEIVER, self::SCENARIO_DEFAULT]],
            [['receiver_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['receiver_country_id' => 'id'], 'on' => [self::SCENARIO_UPDATE_RECEIVER, self::SCENARIO_DEFAULT]],
            [['receiver_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['receiver_district_id' => 'id'], 'on' => [self::SCENARIO_UPDATE_RECEIVER, self::SCENARIO_DEFAULT]],
            [['receiver_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['receiver_province_id' => 'id'], 'on' => [self::SCENARIO_UPDATE_RECEIVER, self::SCENARIO_DEFAULT]],

            [['current_status'], 'required', 'on' => self::SCENARIO_UPDATE_STATUS],
//            [['current_status'], 'validateCurrentStatus', 'on' => self::SCENARIO_UPDATE_STATUS],

            [['sale_support_id'], 'required', 'on' => self::SCENARIO_SALE_ASSIGN],
            [['sale_support_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sale_support_id' => 'id'], 'on' => self::SCENARIO_SALE_ASSIGN],
            [['support_email'], 'safe', 'on' => self::SCENARIO_SALE_ASSIGN],
            [
                [
                    'store_id', 'customer_id', 'new', 'supported', 'supporting', 'ready_purchase',
                    'purchased', 'seller_shipped', 'stockin_us', 'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'cancelled', 'lost',
                    'is_quotation', 'quotation_status', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'receiver_address_id', 'seller_id', 'sale_support_id', 'is_email_sent', 'is_sms_sent', 'difference_money', 'coupon_id', 'xu_time', 'promotion_id', 'remove'
                ], 'integer'
            ],

            [
                [
                    'total_final_amount_local', 'total_amount_local', 'total_origin_fee_local', 'total_price_amount_origin', 'total_paid_amount_local', 'total_refund_amount_local', 'total_counpon_amount_local', 'total_promotion_amount_local', 'total_fee_amount_local', 'total_origin_tax_fee_local', 'total_origin_shipping_fee_local', 'total_weshop_fee_local', 'total_intl_shipping_fee_local', 'total_custom_fee_amount_local', 'total_delivery_fee_local', 'total_packing_fee_local', 'total_inspection_fee_local', 'total_insurance_fee_local', 'total_vat_amount_local', 'total_weight', 'total_weight_temporary',
                    'total_quantity', 'total_purchase_quantity'
                ], 'number'
            ],
            [

                [
                    'note_by_customer', 'note',
                    'seller_store',
                    'purchase_order_id', 'purchase_transaction_id', 'purchase_account_id', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id'
                ], 'string'
            ],
            [
                [
                    'exchange_rate_fee', 'exchange_rate_purchase',
                    'revenue_xu', 'xu_count', 'xu_amount',
                    'purchase_amount', 'purchase_amount_buck', 'purchase_amount_refund', 'check_update_payment'
                ], 'number'
            ],
            [
                [
                    'receiver_email', 'type_order', 'portal', 'utm_source', 'quotation_note',
                    'receiver_name', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code',
                    'seller_name', 'currency_purchase', 'payment_type', 'support_email', 'xu_log'
                ], 'string', 'max' => 255
            ],
//             [
//                 [
//                     'receiver_email',
//                 ],'string', 'min' => 5, 'math' , 'pattern' => "/^[A-Za-z0-9_\.]{6,32}@([a-zA-Z0-9]{2,12})(\.[a-zA-Z]{2,12})+$/"
//             ],
            [
                [
                    'receiver_phone',
                ], 'string', 'min' => 9
            ],
            [['customer_type'], 'string', 'max' => 11],
            [['current_status'], 'string', 'max' => 200],
//            [
//                [
//                    'note_by_customer', 'note',
//                    'seller_store',
//                    'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id',
//                    'total_weight',
//                ], 'filter' , 'filter' => 'trim'
//            ],
            [['current_status'], 'string', 'max' => 200],
            [['quotation_status'], 'in', 'range' => [null, self::QUOTATION_STATUS_PENDING, self::QUOTATION_STATUS_APPROVE, self::QUOTATION_STATUS_DECLINE]],
            [['difference_money'], 'in', 'range' => [self::DIFF_MONEY_DEFAULT, self::DIFF_MONEY_LECH, self::DIFF_MONEY_HIDE]],
            [
                [
                    'receiver_email', 'support_email', 'purchase_account_email'
                ], 'email'
            ],
//            [
//                [
//                    'note_by_customer', 'note',
//                    'seller_store',
//                    'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'total_weight',
//                ], 'filter', 'filter' => '\yii\helpers\Html::encode'
//            ],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::className(), 'targetAttribute' => ['seller_id' => 'id']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    protected function validateCurrentStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $value = $this->$attribute;
        }
    }

    /**
     * Todo lưu giá trị tên quận huyện tình thành theo các id đã select
     * @param string $attribute target attribute name
     * @param \yii\base\Event $event the event that triggers the current attribute updating.
     */
    public function evaluateSystemLocation($event, $attribute)
    {
        $sender = $event->sender;
        if ($attribute === 'receiver_country_name') {
            return $sender->receiverCountry->name;
        } elseif ($attribute === 'receiver_province_name') {
            return $sender->receiverProvince->name;
        } elseif ($attribute === 'receiver_district_name') {
            return $sender->receiverDistrict->name;
        }
        return $sender->$attribute;
    }

    /**
     * Todo lưu giá trị customer id đã login
     * @param string $attribute target attribute name
     * @param \yii\base\Event $event the event that triggers the current attribute updating.
     */
    public function evaluateCustomer($event, $attribute)
    {
        $sender = $event->sender;
        if ($sender->$attribute === null) {
            return Yii::$app->getUser()->id;
        }
        return $sender->$attribute;
    }

    public function getRuleParams($permissionName)
    {
        return $this->sale_support_id;
    }

    /**
     * @inheritdoc
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(OrderQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'receiver_address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverCountry()
    {
        return $this->hasOne(SystemCountry::className(), ['id' => 'receiver_country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'receiver_district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverProvince()
    {
        return $this->hasOne(SystemStateProvince::className(), ['id' => 'receiver_province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleSupport()
    {
        return $this->hasOne(User::className(), ['id' => 'sale_support_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFees()
    {
        return $this->hasMany(ProductFee::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getPackages()
//    {
//        return $this->hasMany(Package::className(), ['order_id' => 'id']);
//    }
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['order_id' => 'id']);
    }

    public function getDraftExtensionTrackingMap()
    {
        return $this->hasMany(DraftExtensionTrackingMap::className(), ['order_id' => 'id']);
    }

    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTransactions()
    {
        return $this->hasMany(PaymentTransaction::className(), ['order_code' => 'ordercode'])
            ->where([
                'payment_type' => [PaymentTransaction::PAYMENT_TYPE_REFUND,PaymentTransaction::PAYMENT_TYPE_ADDFEE,PaymentTransaction::PAYMENT_TYPE_BUY_NOW,PaymentTransaction::PAYMENT_TYPE_SHOPPING],
            ])
            ->andWhere(['<>','transaction_status',PaymentTransaction::TRANSACTION_STATUS_REPLACED])
            ->orderBy('id desc');
    }


    public function fields()
    {
        $fields = parent::fields();
        return array_merge($fields, [
            'store' => function ($model) {
                return $model->store_id === 1 ? 'Viet Nam' : 'Indo';
            }
        ]);
    }


    public static function updateOrderStatus($condition, $next, $note = null)
    {
        if (($model = self::findOne($condition)) === null) {
            $condition = is_array($condition) ? reset($condition) : $condition;
            throw new NotFoundHttpException("not found order $condition");
        }
        $model->setScenario(self::SCENARIO_UPDATE_STATUS);
        $model->current_status = $next;
        if ($model->current_status === self::STATUS_PURCHASED) {
        }
        if (!$model->save()) {
            throw new \InvalidArgumentException($model->getFirstErrors());
        }
        return true;
    }

    // Optional sort/filter params: page,limit,order,search[name],search[email],search[id]... etc

    static public function search($params)
    {

        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;
        $query = Order::find()
            ->withFullRelations()
            ->andWhere(['is not', 'product.id', null])// ToDo Test/Check Code Fee
            ->filter($params)
            ->limit($limit)
            ->offset($offset);
        if (isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }
        if (isset($params['store'])) {
            $query->andFilterWhere(['order.store_id' => $params['store']]);
        }
        if (isset($params['location'])) {
            $query->andFilterWhere(['order.receiver_province_id' => $params['location']]);
        }
        if (isset($params['paymentStatus'])) {
            if ($params['paymentStatus'] === 'PAID') {
                $query->andFilterWhere(['>', 'order.total_paid_amount_local', 0]);
            } elseif ($params['paymentStatus'] === 'UNPAID') {
                $query->andFilterWhere(['=', 'order.total_paid_amount_local', 0]);
            } elseif ($params['paymentStatus'] === 'REFUND_PARTIAL') {
                $query->andFilterWhere(['>', 'order.total_paid_amount_local', new Expression('[[order.total_refund_amount_local]]')]);
            } elseif ($params['paymentStatus'] === 'REFUND_FULL') {
                $query->andFilterWhere(['=', 'order.total_paid_amount_local', new Expression('[[order.total_refund_amount_local]]')]);
            }
        }
        if (isset($params['type'])) {
            $query->andFilterWhere(['order.type_order' => $params['type']]);
        }
        if (isset($params['searchKeyword']) && isset($params['keyWord'])) {
            if ($params['searchKeyword'] == 'ALL') {
                $query->andFilterWhere(['or',
                    ['order.ordercode' => $params['keyWord']],
                    ['product.id' => $params['keyWord']],
                    ['order.ordercode' => $params['keyWord']],
                    ['product.sku' => $params['keyWord']],
                    ['coupon.code' => $params['keyWord']],
                    ['product.category_id' => $params['keyWord']],
                    ['product.product_name' => $params['keyWord']],
                    ['order.payment_type' => $params['keyWord']],
                    ['user.email' => $params['keyWord']],
                    ['order.receiver_email' => $params['keyWord']],
                    ['order.receiver_phone' => $params['keyWord']],
                    ['user.phone' => $params['keyWord']],
                ]);
            } elseif ($params['searchKeyword'] != 'ALL') {
                if ($params['searchKeyword'] == 'email') {
                    $query->andFilterWhere(['or',
                        ['like', 'order.receiver_email', $params['keyWord']],
                        ['like', 'user.email', $params['keyWord']],
                    ]);
                } elseif ($params['searchKeyword'] == 'phone') {
                    $query->andFilterWhere(['or',
                        ['like', 'order.receiver_phone', $params['keyWord']],
                        ['like', 'user.phone', $params['keyWord']],
                    ]);
                }
                $query->andFilterWhere([$params['searchKeyword'] => $params['keyWord']]);
            }

        }

        if (isset($params['orderStatus'])) {
            $query->andFilterWhere(['order.current_status' => $params['orderStatus']]);
        }

        if (isset($params['portal'])) {
            $query->andFilterWhere(['order.portal' => $params['portal']]);
        }
        if (isset($params['sale'])) {
            $query->andFilterWhere(['order.sale_support_id' => $params['sale']]);
        }

        if (isset($params['timeKey']) && isset($params['startTime']) && isset($params['endTime'])) {
            $start = (int)(Yii::$app->formatter->asTimestamp($params['startTime']));
            $end = (int)(Yii::$app->formatter->asTimestamp($params['endTime']));
            if ($params['timeKey'] == 'ALL') {
                $query->andFilterWhere(['or',
                    ['between', 'order.created_at', $start, $end],
                    ['between', 'order.updated_at', $start, $end],
                    ['between', 'order.new', $start, $end],
                    ['between', 'order.purchased', $start, $end],
                    ['between', 'order.seller_shipped', $start, $end],
                    ['between', 'order.stockin_us', $start, $end],
                    ['between', 'order.stockout_us', $start, $end],
                    ['between', 'order.stockin_local', $start, $end],
                    ['between', 'order.stockout_local', $start, $end],
                    ['between', 'order.at_customer', $start, $end],
                    ['between', 'order.returned', $start, $end],
                    ['between', 'order.cancelled', $start, $end],
                    ['between', 'order.supporting', $start, $end],
                    ['between', 'order.supported', $start, $end],
                    ['between', 'order.ready_purchase', $start, $end],
                ]);
            } elseif ($params['timeKey'] != 'ALL') {
                $query->andFilterWhere(['between', $params['timeKey'], $start, $end]);
            }
        }

//        if (isset($params['timeKeyCreate']) && isset($params['startDate']) && isset($params['endDate'])) {
//            $query->andFilterWhere(['between', $params['timeKeyCreate'], $params['startDate'], $params['endDate']]);
//        }

//        if (isset($params['receiver_email'])) {
//            $query->andFilterWhere(['or',
//                ['like', 'order.receiver_email', $params['receiver_email']],
//                ['like', 'customer.email', $params['receiver_email']],
//            ]
//            );
//        }

        /*

        if(isset($params['typeSearch']) and isset($params['keyword']) ){
            $query->andFilterWhere(['like',$params['typeSearch'],$params['keyword']]);
        }else{
            $query->andWhere(['or',
                ['like', 'id', $params['keyword']],
                ['like', 'seller_name', $params['keyword']],
                ['like', 'seller_store', $params['keyword']],
                ['like', 'portal', $params['keyword']],
            ]);
        }
        */

        if (isset($params['type_order'])) {
            $query->andFilterWhere(['type_order' => $params['type_order']]);
        }
        if (isset($params['current_status'])) {
            $query->andFilterWhere(['current_status' => $params['current_status']]);
        }
        if (isset($params['time_start']) and isset($params['time_end'])) {
            $query->andFilterWhere(['or',
                ['>=', 'created_at', $params['time_start']],
                ['<=', 'updated_at', $params['time_end']]
            ]);
        }

//        if (isset($order)) {
//            $query->orderBy($order);
//        }
//
//
//        if (isset($order)) {
//            $query->orderBy($order);
//        }
        $subDraftExtensionTrackingMapQuery = new Query();
        $subDraftExtensionTrackingMapQuery->select([new Expression('1')]);
        $subDraftExtensionTrackingMapQuery->from(['draftTracking' => DraftExtensionTrackingMap::tableName()]);
        $subDraftExtensionTrackingMapQuery->where(['draftTracking.order_id' => new Expression('[[id]]')]);
        $countOrder = Order::find()->select(['purchased', 'stockin_us'])->asArray()->all();
        if (isset($params['noTracking'])) {
            if ($params['noTracking'] === 'NO_TRACKING') {
                $query->andWhere(['NOT EXISTS', $subDraftExtensionTrackingMapQuery]);
            }
            if ($params['noTracking'] === '10STOCKOUT_US') {
                $query->andFilterWhere([
                    'and',
                    ['IS NOT', 'stockin_us', new Expression('null')],
                    ['<', 'stockin_us', (Yii::$app->getFormatter()->asTimestamp('now - 10 days'))],
                    ['IS', 'stockout_us', new Expression('null')],
                ]);
            }
            if ($params['noTracking'] === 'SHIPPED5') {
                $query->andFilterWhere([
                    'AND',
                    ['<', 'seller_shipped', (int)(Yii::$app->getFormatter()->asTimestamp('now - 5 days'))],
                    ['is', 'stockin_us', new Expression('null')]
                ]);
            }
            if ($params['noTracking'] === 'PURCHASED2DAY') {
                for ($i = 0; $i < count($countOrder); $i++) {
                    if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['purchased'], 'l') == 'Friday') {
                        $query->where([
                            'and',
                            ['<', 'purchased', (int)Yii::$app->getFormatter()->asTimestamp('now - 5 days')],
                            ['is', 'stockin_us', new Expression('null')],
                        ]);
                    }
                    if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['purchased'], 'l') == 'Saturday') {
                        $query->where([
                            'and',
                            ['<', 'purchased', (int)Yii::$app->getFormatter()->asTimestamp('now - 4 days')],
                            ['is', 'stockin_us', new Expression('null')],
                        ]);
                    } else {
                        $query->where([
                            'and',
                            ['<', 'purchased', (int)Yii::$app->getFormatter()->asTimestamp('now - 2 days')],
                            ['is', 'stockin_us', new Expression('null')],
                        ]);
                    }
                }
            }
            if ($params['noTracking'] == 'STOCKIN_US2DAY') {
                for ($i = 0; $i < count($countOrder); $i++) {
                    if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['stockin_us'], 'l') == 'Friday') {
                        var_dump(Yii::$app->getFormatter()->asDatetime($countOrder[$i]['stockin_us'], 'l'));
                        die();
                        $query->andFilterWhere([
                            'and',
                            ['IS NOT', 'stockin_us', new Expression('null')],
                            ['<', 'stockin_us', (int)(Yii::$app->getFormatter()->asTimestamp('now - 4 days'))],
                            ['is', 'stockout_us', new Expression('null')],
                        ]);
                    }
                    if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['stockin_us'], 'l') == 'Saturday') {
                        $query->andFilterWhere([
                            'and',
                            ['IS NOT', 'stockin_us', new Expression('null')],
                            ['<', 'stockin_us', (int)(Yii::$app->getFormatter()->asTimestamp('now - 3 days'))],
                            ['is', 'stockout_us', new Expression('null')],
                        ]);
                    } else {
                        $query->andFilterWhere([
                            'and',
                            ['IS NOT', 'stockin_us', new Expression('null')],
                            ['<', 'stockin_us', (int)(Yii::$app->getFormatter()->asTimestamp('now - 2 days'))],
                            ['is', 'stockout_us', new Expression('null')],
                        ]);
                    }
                }
            }
        }

        $cloneQuery = clone $query;
        $cloneQuery->with = null;
        $cloneQuery->limit(-1);
        $cloneQuery->offset(-1);
        $cloneQuery->orderBy([]);
        $countPC = 0;
        $pc = 0;
        $pc1 = 0;
        $pc2 = 0;
        $countStockin = 0;
        $sk = 0;
        $sk1 = 0;
        $sk2 = 0;
        for ($i = 0; $i < count($countOrder); $i++) {
            if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['purchased'], 'l') == 'Friday') {
                $pc = (new Query())->from(['p' => $cloneQuery])->where([
                    'and',
                    ['IS NOT', 'p.purchased', new Expression('null')],
                    ['<', 'p.purchased', (int)Yii::$app->getFormatter()->asTimestamp('now - 5 days')],
                    ['is', 'p.seller_shipped', new Expression('null')],
                ])->count('p.id');
            }
            if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['purchased'], 'l') == 'Saturday') {
                $pc1 = (new Query())->from(['p' => $cloneQuery])->where([
                    'and',
                    ['IS NOT', 'p.purchased', new Expression('null')],
                    ['<', 'p.purchased', (int)Yii::$app->getFormatter()->asTimestamp('now - 4 days')],
                    ['is', 'p.seller_shipped', new Expression('null')],
                ])->count('p.id');
            }
            if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['stockin_us'], 'l') == 'Friday') {
                $sk = (new Query())->from(['p' => $cloneQuery])->where([
                    'and',
                    ['IS NOT', 'p.stockin_us', new Expression('null')],
                    ['<', 'p.stockin_us', (int)(Yii::$app->getFormatter()->asTimestamp('now - 4 days'))],
                    ['is', 'p.stockout_us', new Expression('null')],
                ])->count('p.id');
            }
            if (Yii::$app->getFormatter()->asDatetime($countOrder[$i]['stockin_us'], 'l') == 'Saturday') {
                $sk1 = (new Query())->from(['p' => $cloneQuery])->where([
                    'and',
                    ['IS NOT', 'p.stockin_us', new Expression('null')],
                    ['<', 'p.stockin_us', (int)(Yii::$app->getFormatter()->asTimestamp('now - 3 days'))],
                    ['is', 'p.stockout_us', new Expression('null')],
                ])->count('p.id');
            } else {
                $sk1 = (new Query())->from(['p' => $cloneQuery])->where([
                    'and',
                    ['IS NOT', 'p.stockin_us', new Expression('null')],
                    ['<', 'p.stockin_us', (int)(Yii::$app->getFormatter()->asTimestamp('now - 2 days'))],
                    ['is', 'p.stockout_us', new Expression('null')],
                ])->count('p.id');
                $pc2 = (new Query())->from(['p' => $cloneQuery])->where([
                    'and',
                    ['IS NOT', 'p.purchased', new Expression('null')],
                    ['<', 'p.purchased', (int)(Yii::$app->getFormatter()->asTimestamp('now - 2 days'))],
                    ['is', 'p.seller_shipped', new Expression('null')],
                ])->count('p.id');
            }
        }
        $countPC += $pc1 + $pc2 + $pc;
        $countStockin = $sk + $sk1 + $sk2;
        $summary = [
            'totalUnPaid' => (new Query())->from(['cc' => $cloneQuery])->where(['=', 'total_paid_amount_local', 0])->count('cc.id'),
            'countPurchase' => (new Query())->from(['cp' => $cloneQuery])->where([
                'AND',
                ['IS NOT', 'cp.seller_shipped', new Expression('null')],
                ['<', 'cp.seller_shipped', (int)(Yii::$app->getFormatter()->asTimestamp('now - 5 days'))],
                ['is', 'cp.stockin_us', new Expression('null')]
            ])->count('cp.id'),
            'countPC' => $countPC,
            'countStockin' => $countStockin,
            'countUS' => (new Query())->from(['cp' => $cloneQuery])->where([
                'and',
                ['IS NOT', 'cp.stockin_us', new Expression('null')],
                ['<', 'cp.stockin_us', (int)Yii::$app->getFormatter()->asTimestamp('now - 10 days')],
                ['is', 'cp.stockout_us', new Expression('null')],
            ])->count('cp.id'),
            'noTracking' => (new Query())->from(['cc' => $cloneQuery])->where(['NOT EXISTS', $subDraftExtensionTrackingMapQuery])->count('cc.id'),
        ];
        $additional_info = [
            'currentPage' => $page,
            'pageCount' => $page,
            'perPage' => $limit,
            'totalCount' => (int)(clone $query)->limit(-1)->offset(-1)->orderBy([])->count('order.id')
        ];
        $data = new \stdClass();
        $data->_items = $query->orderBy('id desc')->all();
        $data->_links = '';
        $data->_meta = $additional_info;
        $data->_summary = $summary;
        $data->total = count($data->_items);
        return $data;

    }
    public function updateSellerShipped($time = null , $updateNew = false){
        $this->current_status = $this->seller_shipped ? $this->current_status : self::STATUS_SELLER_SHIPPED;
        if ($updateNew || !$this->seller_shipped){
            $this->seller_shipped = $time ? $time : time();
        }
    }
    public function updateStockinUs($time = null , $updateNew = false)
    {
        $this->current_status = $this->stockin_us ? $this->current_status : self::STATUS_STOCK_IN_US;
        if ($updateNew || !$this->stockin_us) {
            $this->stockin_us = $time ? $time : time();
        }
    }
    public function updateStockoutUs($time = null , $updateNew = false){
        $this->current_status = $this->stockout_us ? $this->current_status : self::STATUS_STOCK_OUT_US;
        if ($updateNew || !$this->stockout_us){
            $this->stockout_us = $time ? $time : time();
        }
    }
    public function updateStockinLocal($time = null , $updateNew = false)
    {
        $this->current_status = $this->stockin_local ? $this->current_status : self::STATUS_STOCK_IN_LOCAL;
        if ($updateNew || !$this->stockin_local) {
            $this->stockin_local = $time ? $time : time();
        }
    }
    public function updateStockoutLocal($time = null , $updateNew = false){
        $this->current_status = $this->stockout_local ? $this->current_status : self::STATUS_STOCK_OUT_LOCAL;
        if ($updateNew || !$this->stockout_local){
            $this->stockout_local = $time ? $time : time();
        }
    }


}
