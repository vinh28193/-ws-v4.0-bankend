<?php


namespace frontend\modules\checkout\controllers;


use common\components\cart\CartManager;
use common\components\cart\storage\MongodbCartStorage;
use common\components\UserCookies;
use common\models\SystemCountry;
use common\models\SystemDistrict;
use common\models\SystemZipcode;
use common\models\User;
use frontend\modules\payment\providers\wallet\WalletService;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use Yii;
use common\components\cart\CartHelper;
use frontend\modules\payment\models\ShippingForm;
use frontend\modules\payment\Payment;
use common\models\SystemStateProvince;
use common\components\cart\CartSelection;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use common\products\BaseProduct;
use yii\helpers\Inflector;

class ShippingController extends CheckoutController
{

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    public function gaCheckout()
    {
        Yii::info("GA CHECKOUT");
        $request = Yii::$app->ga->request();

        // Build the order data programmatically, each product of the order included in the payload
        // First, general and required hit data
        if (!Yii::$app->user->isGuest) {
            $request->setClientId(Yii::$app->user->identity->getId() . 'WS' . Yii::$app->user->identity->email);
            $request->setUserId(Yii::$app->user->identity->getId());
        }

        // Then, include the transaction data
        $request->setTransactionId('7778922')
            ->setAffiliation('THE ICONIC')
            ->setRevenue(250.0)
            ->setTax(25.0)
            ->setShipping(15.0)
            ->setCouponCode('MY_COUPON');

        // Include a product, the only required fields are SKU and Name
        $productData1 = [
            'sku' => 'AAAA-6666',
            'name' => 'Test Product 2',
            'brand' => 'Test Brand 2',
            'category' => 'Test Category 3/Test Category 4',
            'variant' => 'yellow',
            'price' => 50.00,
            'quantity' => 1,
            'coupon_code' => 'TEST 2',
            'position' => 2,
            'type' => 'eBay'
        ];

        $request->addProduct($productData1);

        // You can include as many products as you need, this way
        $productData2 = [
            'sku' => 'AAAA-5555',
            'name' => 'Test Product',
            'brand' => 'Test Brand',
            'category' => 'Test Category 1/Test Category 2',
            'variant' => 'blue',
            'price' => 85.00,
            'quantity' => 2,
            'coupon_code' => 'TEST',
            'position' => 4
        ];

        $request->addProduct($productData2);

        // Don't forget to set the product action, which is PURCHASE in the example below
        $request->setProductActionToPurchase();

        // Finally, you need to send a hit; in this example, we are sending an Event
        $request->setEventCategory('Checkout')
            ->setEventAction('Purchase')
            ->setAsyncRequest(true)//  'asyncMode' => true,
            ->sendEvent();

    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'sub-district' => [
                'class' => 'common\actions\DepDropAction',
                'useAction' => 'common\models\SystemDistrict::select2Data'
            ]
        ]);
    }

    public function init()
    {
        parent::init();
        $this->site_name = 'Checkout page';
        if (($type = Yii::$app->request->get('type')) !== null) {
            $this->site_title = Yii::t('frontend', 'Product in {type} cart', [
                'type' => $type
            ]);
        }

    }

    public function actionIndex($page, $type)
    {
        if (($keys = CartSelection::getSelectedItems($type)) === null) {
            return $this->goBack();
        }
        $uuid = $this->filterUuid();
        $shippingForm = new ShippingForm();
        $shippingForm->setDefaultValues();
        /** @var User $user */
        $keys = array_map(function ($e) use ($uuid, $type) {
            return [
                'cartId' => $e,
                'uuid' => $uuid,
                'checkoutType' => $type,
            ];
        }, $keys);
        $payment = new Payment([
            'page' => strtoupper($page),
            'uuid' => $this->filterUuid(),
            'type' => $type,
            'orders' => $keys,
        ]);
        $payment->initDefaultMethod();
        if (count($payment->getOrders()) === 0) {
            return $this->goBack();
        }
        $siteName = Yii::t('frontend', 'Checkout');
        $titleCollection = [];
        if ($payment->type === CartSelection::TYPE_BUY_NOW) {
            $siteName = Yii::t('frontend', 'Buy now');

        } elseif ($payment->type === CartSelection::TYPE_SHOPPING) {
            $siteName = Yii::t('frontend', 'Shopping');
        }
        $titleCollection[] = $siteName;
        $titleCollection[] = $shippingForm->getStoreManager()->store->name;

        foreach ($payment->getOrders() as $order) {

            $titleCollection[] = Yii::t('frontend', 'Seller:{portal} {seller}', [
                'portal' => strtoupper($order->seller->portal) === 'EBAY' ? 'eBay' : 'Amazon',
                'seller' => Inflector::camelize($order->seller->seller_name)
            ]);
            foreach ($order->products as $product) {
                $titleCollection[] = $product->product_name;
            }
        }
        $this->site_name = $siteName;
        $this->site_title = implode(' | ', $titleCollection);
        $this->site_description = 'checkout | products | payment | visa | master |bank transfer';
        return $this->render('index', [
            'shippingForm' => $shippingForm,
            'payment' => $payment,
        ]);
    }

    public function actionValidate()
    {
        $model = new ShippingForm();
        $model->on(ShippingForm::EVENT_AFTER_VALIDATE, function ($event) {
            /** @var  $event yii\base\Event */
            $sender = $event->sender;
        });
        $request = $this->request;
        Yii::info($request->post(), __METHOD__);
        // Todo save info of guest user when whole typing on form
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($request->isAjax && $request->isPost && $model->load($request->post())) {
            $attributes = [];
            $results = \yii\bootstrap4\ActiveForm::validate($model);
            $clone = clone $model;
            $clone->ensureReceiver();

            foreach ($clone->getAttributes() as $name => $value) {
                if ($name === 'customer_id' || $name === 'cartIds' || $name === 'checkoutType') {
                    continue;
                }
                if (!$model->hasErrors($name)) {
                    $attributes[$name] = $value;
                }
            }
            $keys = explode(',', $model->cartIds);
            $attributes['buyer_province_name'] = $clone->getBuyerProvinceName();
            $attributes['receiver_province_name'] = $clone->getReceiverProvinceName();
            $attributes['buyer_district_name'] = $clone->getBuyerDistrictName();
            $attributes['receiver_district_name'] = $clone->getReceiverDistrictName();
            $attributes['receiver_country_name'] = $this->storeManager->store->country_name;
            $attributes['receiver_country_id'] = $this->storeManager->store->country_id;
            $attributes['buyer_country_name'] = $this->storeManager->store->country_name;
            $attributes['buyer_country_id'] = $this->storeManager->store->country_id;
            foreach ($keys as $key) {
                $rs = $this->module->cartManager->updateShippingAddress($model->checkoutType, $key, $attributes, $this->filterUuid());
                Yii::info($rs);
            }
            return $results;
        }
        return [];
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            return ['success' => true];
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $key = CartSelection::getSelectedItems(CartSelection::TYPE_BUY_NOW);
            if ($key) {
                $this->module->cartManager->setMeOwnerItem($key[0]);
            }
            $wallet = new WalletService();
            $wallet->login($model->password);
            return ['success' => true, 'message' => Yii::t('frontend', 'Login success')];
        } else {
            return ['success' => false, 'message' => Yii::t('frontend', 'Login fail'), 'data' => $model->errors];
        }
    }

    public function actionSignup()
    {
        Yii::$app->response->format = Yii\web\Response::FORMAT_JSON;
        Yii::info('register new');
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::info('register new 002');
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Check your email for further instructions.'));
                $model->sendEmail();
                Yii::info('register new 003');
                if (Yii::$app->getUser()->login($user)) {
                    $key = CartSelection::getSelectedItems(CartSelection::TYPE_BUY_NOW);
                    if ($key) {
                        $this->module->cartManager->setMeOwnerItem($key[0]);
                    }
                    $wallet = new WalletService();
                    $wallet->login($model->password);
                    return ['success' => true, 'message' => Yii::t('frontend', 'Sign up success')];
                }
            }
        }
        return ['success' => false, 'message' => 'Sign up fail', 'data' => $model->errors];
    }

    public function actionAddCartCheckout()
    {
        $post = Yii::$app->request->post();
        $CartId = CartSelection::getSelectedItems();
        return $this->getCart()->updateSafeItem('buynow', $CartId['buynow'], $post);

    }

    /**
     * @return \common\components\cart\CartManager
     */
    protected function getCart()
    {
        return Yii::$app->cart;
    }

    public function actionGetAddressAjax()
    {
        $store = $this->storeManager->store;
        $queryParams = $this->request->getQueryParams();
        $message = ["load address for country `{$store->country_code}`"];
        if (($zip = ArrayHelper::getValue($queryParams, 'zipcode', null)) !== null) {
            $message[] = "for zip code `{$zip}`";
        }
        $results = SystemZipcode::loadAddress(101, $zip);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true, "message" => implode(', ', $message), 'data' => $results, 'count' => count($results)];
    }

    public function actionGetZipAjax()
    {
        $store = $this->storeManager->store;
        $queryParams = $this->request->getQueryParams();
        $message = ["load zipcode for country `{$store->country_code}`"];

        if (($province = ArrayHelper::getValue($queryParams, 'province', null)) !== null) {
            $message[] = "for province `{$province}`";
        }
        if (($district = ArrayHelper::getValue($queryParams, 'district', null)) !== null) {
            $message[] = "for district `{$district}`";
        }
        if (($zip = ArrayHelper::getValue($queryParams, 'zipcode', null)) !== null) {
            $message[] = "search zipcode `{$zip}`";
        }
        $refresh = isset($queryParams['refresh']) && $queryParams['refresh'] === 'yess';
        $refresh = true;
        if ($refresh === true) {
            $message[] = "invalid on cache";
        }
        $results = SystemZipcode::loadZipCode(101, $zip, $province, $district, $refresh);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true, "message" => implode(', ', $message), 'data' => $results, 'count' => count($results)];
    }
}
