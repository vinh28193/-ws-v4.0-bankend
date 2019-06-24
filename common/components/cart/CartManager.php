<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:36
 */

namespace common\components\cart;


use common\components\cart\storage\MongodbCartStorage;
use common\components\GetUserIdentityTrait;
use common\components\StoreManager;
use common\helpers\WeshopHelper;
use common\models\User;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Yii;
use Exception;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\components\cart\storage\CartStorageInterface;
use common\components\cart\item\OrderCartItem;
use yii\helpers\ArrayHelper;

/**
 * Class CartManager
 * @package common\components\cart
 *
 * [
 *      1 => [
 *         ebay:fun_shop => [
 *              sku => [
 *                   // item data
 *              ],
 *              sku:parentSku => [
 *                  // item data
 *             ]
 *          ],
 *          ebay:shop2 => [
 *
 *          ]
 *
 * ]
 */
class CartManager extends Component
{

    use GetUserIdentityTrait;
    const TYPE_SHOPPING = 'shopping';
    const TYPE_BUY_NOW = 'buynow';
    const TYPE_SHIPPING = 'shipping';
    const TYPE_REQUEST = 'request';

    /**
     * @var string | \common\components\cart\storage\CartStorageInterface
     */
    public $storage = 'common\components\cart\storage\MongodbCartStorage';

    /**
     * @return MongodbCartStorage|string
     * @throws InvalidConfigException
     */
    public function getStorage()
    {
        if (!is_object($this->storage)) {
            $this->storage = Yii::createObject($this->storage);
        }
        return $this->storage;
    }


    /**
     * @param string $type
     * @param string $key
     * @param string|null $uuid
     * @return mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function hasItem($type, $key, $uuid = null)
    {
        return $this->getStorage()->hasItem($type, $key, $uuid);
    }

    /**
     * @param string $type
     * @param string $id
     * @param string|null $uuid
     * @return bool|mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function getItem($type, $id, $uuid = null)
    {
        return $this->getStorage()->getItem($type, $id, $uuid);
    }


    public function setMeOwnerItem($id)
    {
        return $this->getStorage()->setMeOwnerItem($id);

    }

    public function filterItem($filter, $type = null, $uuid = null)
    {
        return $this->getStorage()->filterItem($filter, $type, $uuid);
    }

    /**
     * @param $type :shopping buynow ...
     * @param $params
     *      -type: ebay/amazon
     *      -seller: seller name
     *      -id: primary id of item
     *      -sku: sku of item (default null)
     *      -quantity: quantity
     *      -image: image
     * @param string|null $uuid
     * @return array
     * @throws \Throwable
     */
    public function addItem($type, $params, $uuid = null)
    {
        $key = $this->createKeyFormParams($params);
        $cartItem = new OrderCartItem($this);
        try {
            if ($type !== CartSelection::TYPE_SHOPPING) {
                list($ok, $value) = $cartItem->createOrderFormKey($key, false);
                if (!$ok) {
                    return [false, $value];
                }
                $success = $this->getStorage()->addItem($type, $key, $value, $uuid);
                return [$success, Yii::t('common', $success ? 'Add to {type} cart success' : 'Add to {type} cart failed', [
                    'type' => $type
                ])];
            }
            $item = $this->filterItem($this->normalKeyFilter($key), $type, $uuid);
            if (!empty($item)) {
                return [true, Yii::t('common', 'This item already added in to {type} cart', ['type' => $type])];
            } else {
                $filter = $key;
                unset($filter['products']);
                $parents = $this->filterItem($this->normalKeyFilter($filter), $type, $uuid);
                if (count($parents) > 0) {
                    foreach ($parents as $parent) {
                        $parentProducts = $parent['key']['products'];
                        if (count($parentProducts) < 3) {
                            $parentProducts[] = $key['products'][0];
                            $parent['key']['products'] = $parentProducts;
                            list($ok, $value) = $cartItem->createOrderFormKey($parent['key'], true);
                            if (!$ok) {
                                return [false, $value];
                            }
                            $success = $this->getStorage()->setItem($parent['_id'], $parent['key'], $value, $uuid);
                            return [$success, Yii::t('common', $success ? 'Add to {type} cart success' : 'Add to {type} cart failed', [
                                'type' => $type
                            ])];
                        }
                    }
                }
                list($ok, $value) = $cartItem->createOrderFormKey($key, false);
                if (!$ok) {
                    return [false, $value];
                }
                $success = $this->getStorage()->addItem($type, $key, $value, $uuid);
                return [$success, Yii::t('common', $success ? 'Add to {type} cart success' : 'Add to {type} cart failed', [
                    'type' => $type
                ])];
            }
        } catch (Exception $exception) {
            Yii::info($exception);
            return [false, $exception->getMessage()];
        }
    }

    /**
     * @param $type
     * @param $id
     * @param $key
     * @param array $params
     * @param $uuid
     * @return array
     * @throws \Throwable
     */
    public function updateItem($type, $id, $key, $params = [], $uuid = null)
    {
        $cartItem = new OrderCartItem($this);
        try {
            if (($item = $this->getItem($type, $id, $uuid)) === false) {
                return [false, Yii::t('common', 'This item not exist in {type} cart', [
                    'type' => $type
                ])];
            }
            $activeKey = $item['key'];
            $products = [];
            foreach (ArrayHelper::remove($activeKey, 'products', []) as $value) {
                if ($this->isDetectedProduct($value, $key)) {
                    $value = ArrayHelper::merge($value, $params);
                }
                $products[] = $value;
            }
            $activeKey['products'] = $products;
            list($ok, $value) = $cartItem->createOrderFormKey($activeKey, true);
            if (!$ok) {
                return [false, $value];
            }
            $success = $this->getStorage()->setItem($item['_id'], $activeKey, $value, $uuid);
            return [$success, $success ? Yii::t('common', 'This item already up to date') : Yii::t('common', 'Can not update this item')];
        } catch (Exception $exception) {
            Yii::info($exception);
            return [false, $exception->getMessage()];
        }
    }

    public function removeItem($type, $id, $key = null, $uuid = null)
    {
        $cartItem = new OrderCartItem($this);
        if ($key === null) {
            $success = $this->getStorage()->removeItem($id);
            return [$success, $success ? Yii::t('common', 'Item has been deleted') : 'Item can not deleted'];
        } else {
            if (($item = $this->getItem($type, $id, $uuid)) === false) {
                return [false, Yii::t('common', 'This item not exist in {type} cart', [
                    'type' => $type
                ])];
            }
            $activeKey = $item['key'];
            $products = [];
            foreach (ArrayHelper::remove($activeKey, 'products', []) as $value) {
                if ($this->isDetectedProduct($value, $key)) {
                    continue;
                }
                $products[] = $value;
            }
            if (empty($products)) {
                $success = $this->getStorage()->removeItem($id);
                $success = $success === false ? false : true;
                return [$success, $success ? Yii::t('common', 'Item has been deleted') : 'Item can not deleted'];
            }
            $activeKey['products'] = $products;
            list($ok, $value) = $cartItem->createOrderFormKey($activeKey, true);
            if (!$ok) {
                return [false, $value];
            }
            $success = $this->getStorage()->setItem($item['_id'], $activeKey, $value);
            return [$success, Yii::t('common', $success ? 'Your {type} cart already up to date' : 'Can not update {type} now, try again', [
                'type' => $type
            ])];
        }
    }

    public function createKeyFormParams($params = [])
    {
        $pKeys = ['source', 'sellerId'];
        $p = [];
        foreach ($pKeys as $k) {
            if (($v = ArrayHelper::getValue($params, $k)) === null || $v === '') {
                continue;
            }
            $p[$k] = $v;
        }
        $c = [];
        foreach ($params as $g => $v) {
            if (ArrayHelper::isIn($g, $pKeys) || ($v === null || $v === '')) {
                continue;
            }
            $c[$g] = $v;
        }

        return ArrayHelper::merge($this->getDefaultKey(), $p, ['products' => [$c]]);
    }

    public function getDefaultKey()
    {

        $store = Yii::$app->storeManager;
        /** @var $store StoreManager */
        $user = $this->getUser();
        if ($user) {
            if ($user->primaryAddress && isset($user->primaryAddress[0])) {
                $buyer = [];
                $primaryAddress = $user->primaryAddress[0];
                $buyer['buyer_email'] = $primaryAddress ? $primaryAddress->email : null;
                $buyer['buyer_name'] = $primaryAddress ? implode(' ', [$primaryAddress->first_name, $primaryAddress->last_name]) : null;
                $buyer['buyer_address'] = $primaryAddress ? $primaryAddress->address : null;
                $buyer['buyer_country_id'] = $primaryAddress ? $primaryAddress->country_id : null;
                $buyer['buyer_country_name'] = $primaryAddress ? $primaryAddress->country_name : null;
                $buyer['buyer_province_id'] = $primaryAddress ? $primaryAddress->province_id : null;
                $buyer['buyer_province_name'] = $primaryAddress ? $primaryAddress->province_name : null;
                $buyer['buyer_district_id'] = $primaryAddress ? $primaryAddress->district_id : null;
                $buyer['buyer_district_name'] = $primaryAddress ? $primaryAddress->district_name : null;
                $buyer['buyer_post_code'] = $primaryAddress ? $primaryAddress->post_code : null;
            } else if ($user->primaryAddress == null) {
                $primaryAddress = $user;
                $buyer = [];
                $buyer['buyer_email'] = $primaryAddress->email;
                $buyer['buyer_name'] = implode(' ', [$primaryAddress->first_name, $primaryAddress->last_name]);
                $buyer['buyer_address'] = $primaryAddress->address;
                $buyer['buyer_phone'] = $primaryAddress->phone;
                $buyer['buyer_country_id'] = null;
                $buyer['buyer_country_name'] = null;
                $buyer['buyer_province_id'] = null;
                $buyer['buyer_province_name'] = null;
                $buyer['buyer_district_id'] = null;
                $buyer['buyer_district_name'] = null;
                $buyer['buyer_post_code'] = null;
            }
        }
        if (!$user) {
            $buyer = [];
            $buyer['buyer_email'] = null;
            $buyer['buyer_name'] = null;
            $buyer['buyer_address'] = null;
            $buyer['buyer_phone'] = null;
            $buyer['buyer_country_id'] = null;
            $buyer['buyer_country_name'] = null;
            $buyer['buyer_province_id'] = null;
            $buyer['buyer_province_name'] = null;
            $buyer['buyer_district_id'] = null;
            $buyer['buyer_district_name'] = null;
            $buyer['buyer_post_code'] = null;
        }
        $receiver = [];
        $defaultShippingAddress = $user ? ($user->defaultShippingAddress !== null ? $user->defaultShippingAddress : null) : null;
        if ($defaultShippingAddress) {
            $receiver['receiver_email'] = $defaultShippingAddress ? $defaultShippingAddress->email : null;
            $receiver['receiver_name'] = $defaultShippingAddress ? implode(' ', [$defaultShippingAddress->first_name, $defaultShippingAddress->last_name]) : null;
            $receiver['receiver_phone'] = $defaultShippingAddress ? $defaultShippingAddress->phone : null;
            $receiver['receiver_address'] = $defaultShippingAddress ? $defaultShippingAddress->address : null;
            $receiver['receiver_country_id'] = $defaultShippingAddress ? $defaultShippingAddress->country_id : null;
            $receiver['receiver_country_name'] = $defaultShippingAddress ? $defaultShippingAddress->country_name : null;
            $receiver['receiver_province_id'] = $defaultShippingAddress ? $defaultShippingAddress->province_id : null;
            $receiver['receiver_province_name'] = $defaultShippingAddress ? $defaultShippingAddress->province_name : null;
            $receiver['receiver_district_id'] = $defaultShippingAddress ? $defaultShippingAddress->district_id : null;
            $receiver['receiver_district_name'] = $defaultShippingAddress ? $defaultShippingAddress->district_name : null;
            $receiver['receiver_post_code'] = $defaultShippingAddress ? $defaultShippingAddress->post_code : null;
            $receiver['receiver_address_id'] = $defaultShippingAddress ? $defaultShippingAddress->id : null;
        }

        return [
            'source' => '',
            'sellerId' => '',
            'seller' => [],
            'current_status' => 'NEW',
            'times' => [
                'new' => Yii::$app->getFormatter()->asTimestamp('now')
            ],
            'supportId' => '',
            'supportAssign' => [],
            'store_id' => $store->id,
            'currency' => $store->store->currency,
            'orderCode' => WeshopHelper::generateTag(Yii::$app->formatter->asTimestamp('now'), 'WSC'),
            'products' => [],
            'buyer' => $buyer,
            'receiver' => $receiver,
        ];
    }

    public function normalKeyFilter($key)
    {
        $useKey = [
            'sellerId', 'source', 'products'
        ];
        $n = [];
        foreach ($key as $k => $v) {
            if (!ArrayHelper::isIn($k, $useKey)) {
                continue;
            }
            if ($k === 'products') {
                $v = array_shift($v);
                $nv = [];
                foreach ($v as $i => $j) {
                    if ($i === 'id' || $i === 'sku') {
                        $nv[$i] = $j;
                    };
                }
                $v = $nv;
            }
            $n[$k] = $v;
        }
        return ['key' => $n];
    }

    /**
     * @param null $type
     * @param null $ids
     * @param null $uuid
     * @return array
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function getItems($type = null, $ids = null, $uuid = null)
    {

        return $this->getStorage()->getItems($uuid, $type, $ids);

    }


    /**
     * @param null $type
     * @param null $uuid
     * @return int
     * @throws InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    public function countItems($type = null, $uuid = null)
    {
        return $this->getStorage()->countItems($uuid, $type);
    }

    /**
     * @param null $uuid
     * @return bool|int
     * @throws InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    public function removeItems($uuid = null)
    {
        return $this->getStorage()->removeItems($uuid);
    }

    /**
     * @param $params
     * @return mixed
     * @throws InvalidConfigException
     */
    public function filterShoppingCarts($params)
    {
        return $this->getStorage()->filterShoppingCarts($params);
    }

    /**
     * @param $type
     * @param $id
     * @param $param
     * @return array
     * @throws \Throwable
     */
    public function updateSafeItem($type, $id, $param)
    {
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        try {
            if (($item = $this->getItem($type, $id)) === false) {
                Yii::info($item);
                return [false, 'Sản phẩm này không có trong giỏ hàng'];
            }
            $value = $item['value'];
            $key = $item['key'];
            if (isset($param['typeUpdate'])) {
                if ($param['typeUpdate'] == 'updateCartInCheckout') {
                    $key['buyer']['buyer_phone'] = $param['phone'];
                    $key['buyer']['buyer_name'] = $param['fullName'];
                    $key['buyer']['email'] = $param['email'];
                }
            }
            if (isset($param['typeUpdate'])) {
                if ($param['typeUpdate'] == 'cancelCart') {
                    $value['current_status'] = 'CANCELLED';
                    $key['current_status'] = 'CANCELLED';
                    $value['cancelled'] = $now;
                }
            }
            if (isset($param['typeUpdate'])) {
                if ($param['typeUpdate'] == 'confirmOrderCart') {
                    $value['current_status'] = 'SUPPORTED';
                    $key['current_status'] = 'SUPPORTED';
                    $value['supported'] = $now;
                }
            }
            if (isset($param['typeUpdate'])) {
                if ($param['typeUpdate'] == 'assignSaleCart') {
                    $value['sale_support_id'] = $param['idSale'];
                }
            }
            if (isset($param['typeUpdate'])) {
                if ($param['typeUpdate'] == 'markAsJunk') {
                    $value['current_status'] = 'JUNK';
                    $key['current_status'] = 'JUNK';
                }
            }
            if (isset($param['type_chat'])) {
                if ($param['type_chat'] === 'WS_CUSTOMER') {
                    $value['current_status'] = 'SUPPORTING';
                    $key['current_status'] = 'SUPPORTING';
                    $value['supporting'] = $now;
                }
            }
           if (isset($param['type_chat'])) {
               if ($param['type_chat'] == 'GROUP_WS') {
                   $value['current_status'] = 'SUPPORTED';
                   $key['current_status'] = 'SUPPORTED';
                   $value['supported'] = $now;
               }
           }
            // todo : thay đổi giá trị của $item['key']

            $success = $this->getStorage()->setItem($id, $key, $value);
        } catch (Exception $exception) {
            return [false, $exception->getMessage()];
        }
    }

    /**
     * @param $target
     * @param null $source
     * @param string $convertType
     * @return bool|mixed
     */
    public function isDetectedProduct($target, $source)
    {
        $lv1 = trim($target['id']) === ($source['id']);
        $lv2 = isset($source['sku']);
        $lv2 = $lv2 ? (!isset($target['sku']) ? false : $source['sku'] === $target['sku']) : true;
        return $lv1 && $lv2;
    }

    public function getSupportAssign()
    {
        $authManager = Yii::$app->authManager;
        $saleIds = $authManager->getUserIdsByRole('sale');
        $masterSaleIds = $authManager->getUserIdsByRole('master_sale');
        $arraySales = array_merge($saleIds, $masterSaleIds);

        if (!$arraySales) {
            return false;
        }
        $supporters = User::find()->indexBy('id')->select(['id', 'email', 'username'])
            ->where(['id' => $arraySales])->all();
        if (!$supporters) {
            return false;
        }
        $ids = array_keys($supporters);
        $calculateToday = ArrayHelper::map($this->getStorage()->calculateSupported($ids), '_id', function ($elem) {
            return ['count' => $elem['count'], 'price' => $elem['price']];
        });

        $countData = [];
        foreach ($ids as $id) {
            $c = 0;
            if (isset($calculateToday[$id]) && ($forSupport = $calculateToday[$id]) !== null && !empty($forSupport) && isset($forSupport['count'])) {
                $c = $forSupport['count'];
            }
            $countData[$id] = $c;
        }
        asort($countData);

        $sQMin = WeshopHelper::sortMinValueArray($countData);

        $priceResult = [];

        foreach ($sQMin as $id => $val) {
            $p = 0;
            if (isset($calculateToday[$id]) && ($forSupport = $calculateToday[$id]) !== null && !empty($forSupport) && isset($forSupport['price'])) {
                $p = $forSupport['price'];
            }
            $priceResult[$id] = $p;
        }
        $priceResult = array_keys($priceResult);
        $id = array_shift($priceResult);
        if (($assigner = ArrayHelper::getValue($supporters, $id)) === null) {
            $assigner = array_shift($supporters);
        }
        return ['id' => $assigner->id, 'email' => $assigner->email, 'username' => $assigner->username];
    }

    public function updateShippingAddress($type, $id, $params, $uuid)
    {
        try {
            if (($item = $this->getItem($type, $id, $uuid)) === false) {
                return [false, 'Sản phẩm này không có trong giỏ hàng'];
            }
            Yii::info($params, $id);
            $value = $item['value'];
            $key = $item['key'];
            $buyer = $key['buyer'];
            $receiver = $key['receiver'];
            $newBuyer = [];
            $newReceiver = [];
            foreach (['buyer_email', 'buyer_name', 'buyer_address', 'buyer_country_id', 'buyer_country_name', 'buyer_province_id', 'buyer_province_name', 'buyer_district_id', 'buyer_district_name', 'buyer_post_code'] as $name) {
                if (($val = ArrayHelper::getValue($params, $name)) !== null && $val !== '') {
                    $newBuyer[$name] = $val;
                }
            }

            foreach (['receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_id', 'receiver_country_name', 'receiver_province_id', 'receiver_province_name', 'receiver_district_id', 'receiver_district_name', 'receiver_post_code', 'receiver_address_id'] as $name) {
                if (($val = ArrayHelper::getValue($params, $name)) !== null && $val !== '') {
                    $newReceiver[$name] = $val;
                }
            }
            $buyer = ArrayHelper::merge($buyer, $newBuyer);
            $receiver = ArrayHelper::merge($receiver, $newReceiver);
            $key['buyer'] = $buyer;
            $key['receiver'] = $receiver;
            $value = ArrayHelper::merge($value, $buyer, $receiver);
            $success = $this->getStorage()->setItem($id, $key, $value);
            $success = $success === false ? false : true;
            return [$success, Yii::t('common', $success ? 'Success' : 'Failed')];
        } catch (Exception $exception) {
            return [false, $exception->getMessage()];
        }
    }
}
