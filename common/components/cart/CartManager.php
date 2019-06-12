<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:36
 */

namespace common\components\cart;


use common\components\cart\storage\MongodbCartStorage;
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
//            if (!$this->storage instanceof CartStorageInterface) {
//                throw new InvalidConfigException(get_class($this->storage) . " not instanceof common\components\cart\CartStorageInterface");
//            }
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
                return [true, Yii::t('common', 'Add cart success')];
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

        return ArrayHelper::merge(OrderCartItem::defaultKey(), $p, ['products' => [$c]]);
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
     * @param bool $force
     * @return int|string|null
     * @throws \Throwable
     */
    public function getIsSafe($force = false)
    {
        if (($user = $this->getUser()) !== null && $force) {
            return $user->ui;
        }
        return null;
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
            if ($param['typeUpdate'] == 'cancelCart') {
                $key['current_status'] = 'CANCELLED';
                $value['cancelled']['new'] = $now;
            }
            if ($param['typeUpdate'] == 'confirmOrderCart') {
                $key['current_status'] = 'PURCHASED';
                $value['purchased']['new'] = $now;
            }
            if ($param['typeUpdate'] == 'assignSaleCart') {
                $value['sale_support_id'] = $param['idSale'];
            }
            if ($param['typeUpdate'] == 'markAsJunk') {
                $key['current_status'] = 'JUNK';
            }
            if ($param['type_chat'] === 'WS_CUSTOMER') {
                var_dump($key['current_status']);
                die();
                $key['current_status'] = 'SUPPORTING';
                $value['supporting'] = $now;
            }
            var_dump('asad');
            die();
            if ($param['type_chat'] == 'GROUP_WS') {
                $key['current_status'] = 'SUPPORTED';
                $value['supported'] = $now;
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
        $supporters = User::find()->indexBy('id')->select(['id', 'email'])
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
        return ['id' => $assigner->id, 'email' => $assigner->email];
    }
}
