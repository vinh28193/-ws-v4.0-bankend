<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 11:21
 */

namespace common\models;

use common\models\db\Coupon;
use common\models\db\Order as DbOrder;
use common\models\db\Promotion;
use common\models\queries\OrderQuery;
use common\rbac\rules\RuleOwnerAccessInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * @property  Product[] $products
 */
class Order extends DbOrder implements RuleOwnerAccessInterface
{

    const SCENARIO_UPDATE_RECEIVER = 'updateReceiver';
    const SCENARIO_UPDATE_STATUS = 'updateStatus';
    const SCENARIO_SALE_ASSIGN = 'SALE_ASSIGN';
    const SCENARIO_REQUEST = 'request';

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
    const STATUS_READY_PURCHASE = 'READY_PURCHASE'; // Lv 4; đơn đã sẵn sàng mua hàng, next status PURCHASING, PURCHASE_PART or REFUNDING
    const STATUS_PURCHASING = 'PURCHASING'; // Lv 5; đơn đang trong quá trình mua hàng, next status PURCHASED
    const STATUS_PURCHASE_PART = 'PURCHASE_PART'; // Lv 6; đơn đang trong quá trình mua hàng nhưng mới mua được 1 phần, next status PURCHASED
    const STATUS_PURCHASED = 'PURCHASED'; // Lv7; đơn đã mua, next status REFUNDING
    const STATUS_REFUNDING = 'REFUNDING'; //Lv8; đơn đang chuyển hoàn, next status REFUNDED
    const STATUS_REFUNDED = 'REFUNDED'; //Lv8; đơn đã chuyển hoàn, end of status

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
                    'receiver_country_name' => [
                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateSystemLocation']
                    ],
                    'receiver_province_name' => [
                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateSystemLocation']
                    ],
                    'receiver_district_name' => [
                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateSystemLocation']
                    ],
                    'customer_id' => [
                        self::EVENT_BEFORE_INSERT => [$this, 'evaluateCustomer']
                    ],
                    'current_status' => [
                        self::EVENT_BEFORE_INSERT => self::STATUS_NEW
                    ],
                    'store_id' => [
                        self::EVENT_BEFORE_INSERT => function ($event, $attribute) {
                            return YII_ENV_DEV ? 1 : Yii::$app->storeManager->getId();
                        },
                    ]
                ]
            ],
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
                '!current_status'
            ],
            self::SCENARIO_SALE_ASSIGN => [
                '!sale_support_id', '!support_email'
            ]
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
            [['current_status'], 'validateCurrentStatus', 'on' => self::SCENARIO_UPDATE_STATUS],

            [['sale_support_id', 'support_email'], 'required', 'on' => self::SCENARIO_SALE_ASSIGN],
            [['sale_support_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sale_support_id' => 'id'], 'on' => self::SCENARIO_SALE_ASSIGN],

            [
                [
                    'store_id', 'customer_id', 'new',
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
                    'purchase_amount', 'purchase_amount_buck', 'purchase_amount_refund'
                ], 'number'
            ],
            [
                [
                    'type_order', 'portal', 'utm_source', 'quotation_note',
                    'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code',
                    'seller_name', 'currency_purchase', 'payment_type', 'support_email', 'xu_log'
                ], 'string', 'max' => 255
            ],
            [['customer_type'], 'string', 'max' => 11],
            [['current_status'], 'string', 'max' => 200],
            [
                [
                    'note_by_customer', 'note',
                    'seller_store',
                    'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id',
                    'total_weight',
                ], 'trim'
            ],
            [['current_status'], 'string', 'max' => 200],
            [['quotation_status'], 'in', 'range' => [null, self::QUOTATION_STATUS_PENDING, self::QUOTATION_STATUS_APPROVE, self::QUOTATION_STATUS_DECLINE]],
            [['difference_money'], 'in', 'range' => [self::DIFF_MONEY_DEFAULT, self::DIFF_MONEY_LECH, self::DIFF_MONEY_HIDE]],
            [
                [
                    'receiver_email', 'support_email', 'purchase_account_email'
                ], 'email'
            ],
            [
                [
                    'note_by_customer', 'note',
                    'seller_store',
                    'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'total_weight',
                ], 'filter', 'filter' => '\yii\helpers\Html::encode'
            ],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
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
    protected function evaluateSystemLocation($event, $attribute)
    {
        Yii::info($event, $attribute);
    }

    /**
     * Todo lưu giá trị customer id đã login
     * @param string $attribute target attribute name
     * @param \yii\base\Event $event the event that triggers the current attribute updating.
     */
    protected function evaluateCustomer($event, $attribute)
    {

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
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
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
    public function getOrderFees()
    {
        return $this->hasMany(OrderFee::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageItems()
    {
        return $this->hasMany(PackageItem::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['order_id' => 'id']);
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
        return $this->hasMany(WalletTransaction::className(), ['order_id' => 'id']);
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
        if(($model = self::findOne($condition)) === null){
            $condition = is_array($condition) ? reset($condition) : $condition;
            throw new NotFoundHttpException("not found order $condition");
        }
        $model->setScenario(self::SCENARIO_UPDATE_STATUS);
        $model->current_status = $next;
        if($model->current_status === self::STATUS_PURCHASED){
        }
        if(!$model->save()){
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
            ->andWhere(['is not', 'product.id', null])
            ->filter($params)
            ->limit($limit)
            ->offset($offset);

        if (isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }
        if (isset($params['store'])) {
            $query->andFilterWhere(['order.store_id' => $params['store']]);
        }
        if (isset($params['type'])) {
            $query->andFilterWhere(['order.type_order' => $params['type']]);
        }
        if (isset($params['searchKeyword']) && isset($params['keyWord'])) {
            $query->andFilterWhere([$params['searchKeyword'] => $params['keyWord']]);
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
            $query->andFilterWhere(['between', $params['timeKey'], $params['startTime'], $params['endTime']]);
        }

        if (isset($params['receiver_email'])) {
            $query->andFilterWhere(['like', 'receiver_email', $params['receiver_email']]);
        }

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

        if (isset($order)) {
            $query->orderBy($order);
        }


        if (isset($order)) {
            $query->orderBy($order);
        }

        $additional_info = [
            'currentPage' => $page,
            'pageCount' => $page,
            'perPage' => $limit,
            'totalCount' => (int)$query->count()
        ];

        $data = new \stdClass();
        $data->_items = $query->all();
        $data->_links = '';
        $data->_meta = $additional_info;
        return $data;

    }
}
