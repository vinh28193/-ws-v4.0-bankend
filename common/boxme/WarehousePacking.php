<?php


namespace common\boxme;

use common\models\Manifest;
use common\models\Package;
use common\models\Product;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;

class WarehousePacking extends BaseObject
{
    const TOKEN = 'Q9v5AX0JsM5nLWUs3zDt8YQN3z9a55qP';
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    public $env = self::ENV_DEV;

    /**
     * @param Manifest $manifest
     * @return array|mixed
     */
    public function create($manifest)
    {
        $param = [];
        $items = $manifest->packages;
        if(($count = count($items)) === 0){
            return ['success' => false, 'message' => 'no item to create'];
        }
        $packing = implode('-', [$manifest->manifest_code, $manifest->id]);
        $param['hawb'] = $packing;
        $param['hawb_quantity'] = $count;
        $param['hawb_email'] = 'weshoptracking@gmail.com';
        $param['hawb_type'] = 0;
        $param['hawb_warehouse'] = $manifest->receive_warehouse_id;
        $param['parcels'] = $this->createParcels($items);
        $response = $this->createHttpRequest($param);

        $logParams = ArrayHelper::merge(['config' => implode('|', [$this->env, self::TOKEN, $this->getFullUrl()])], $param);

        if ($response['success'] === false) {
            return $response;
        }

        if ($this->env === self::ENV_PROD) {
            $manifest->status = Manifest::STATUS_PACKING_CREATED;
            $manifest->save();

            $itemIds = ArrayHelper::getColumn($items, 'id', false);

//            Package::updateAll([
//                'status' => Package::STATUS_LOCAL_REQUEST_INSPECT
//            ], ['id' => $itemIds]);
        }

        $response['message'] = $packing . ' created success with ' . $count . ' items';
        return $response;

    }


    /**
     * @param $items array
     * @return array
     */
    public function createParcels($items)
    {
        $parcels = [];
        foreach ($items as $item) {
            /** @var $item Package */
            $parcels[] = $this->createParcel($item);
        }
        return $parcels;
    }

    /**
     * @param $item Package
     * @return  array;
     */
    public function createParcel($item)
    {
        $parcel = [];
        $images = $this->getImages($item);
        $parcel['item_weight'] = $this->toString($item->weight);
        $parcel['tracking_code'] = $item->tracking_code;
        $parcel['tracking_soi'] = $item->ws_tracking_code;
        $parcel['tracking_type'] = $item->type_tracking;
        $parcel['item_quantity'] = ($quantity = $item->quantity) ==! null ? $this->toString($quantity) : '';
        $parcel['item_content'] = ($name = $item->item_name) ==! null ? '' :  $name;
        $parcel['item_note'] = $item->note_boxme;
        $parcel['item_volume'] = '';
        $isInspect = (count($images) === 0 || $this->isInspect($item)) ? '1' : '0';
        $parcel['item_inspect'] = $isInspect;
        $parcel['arr_images'] = $images;
        return $parcel;
    }

    /**
     * @param $item Package
     * @return bool
     */
    protected function isInspect($item)
    {
        return ArrayHelper::isIn($item->type_tracking,['UNKNOWN','ERROR']);
    }

    /**

    /**
     * @param $package Package
     * @return array
     */
    protected function getImages($package)
    {
        if ($package === null || ($image = $package->image) === null) {
            return [];
        }

        return [
            ['urls' => $image]
        ];
    }

    private function toString($value){
        return (string)$value;
    }

    protected function getFullUrl()
    {
        $baseUrl = 'https://s.boxme.asia/v1/packing';
        if ($this->env === self::ENV_DEV) {
            $baseUrl = 'http://wmsv2.boxme.vn/v1/packing';
        }
        return $baseUrl . '/packing/create';
    }

    /**
     * @param $data
     * @return array|mixed
     */
    private function createHttpRequest($data)
    {
        try {
            $client = new Client();
            $client->setTransport(['class' => CurlTransport::className()]);
            $request = $client->createRequest();
            $request->addHeaders(['Authorization' => self::TOKEN]);
            $request->setFormat(Client::FORMAT_JSON);
            $request->setMethod('POST');
            $request->setFullUrl($this->getFullUrl());
            $request->setData($data);
            $response = $client->send($request);
            $content = $response->getData();
            if (!$response->isOk || (isset($content['success']) && $content['success'] === false)) {
                return ['success' => false, 'message' => isset($content['message']) ? $content['message'] : 'can not connect'];
            }
            return $content;
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        } catch (\yii\base\InvalidConfigException $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }


    }
}