<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\DeliveryNote;
use common\models\Package;

class PackageController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'merge'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET'],
            'merge' => ['POST'],

        ];
    }

    public function actionMerge()
    {
        $list_id = \Yii::$app->request->post('list_id');
        $manifest_id = \Yii::$app->request->post('manifest_id');
        /** @var $packages Package[] */
        if ($list_id) {
            $packages = Package::find()->with(['order','manifest'])->where(['id' => $list_id])->active()->all();
        } else if ($manifest_id) {
            $packages = Package::find()->with(['order','manifest'])->where(['manifest_id' => $manifest_id])->active()->all();
        } else {
            $packages = null;
        }
        if (!$packages) {
            return $this->response(false, 'Cannot find package!');
        }
        /** @var DeliveryNote[] $dataGroup */
        $dataGroup = [];
        foreach ($packages as $package) {
            if ($dataGroup) {
                $isGroup = false;
                foreach ($dataGroup as $group) {
                    if (
                    $group->warehouse_id == $package->manifest->receive_warehouse_id &&
                    ($group->receiver_address_id == $package->order->receiver_address_id ||
                        ($group->customer_id == $package->order->customer_id &&
                            $this->getCharacter($group->receiver_address) == $this->getCharacter($package->order->receiver_address) &&
                            $this->getCharacter($group->receiver_email) == $this->getCharacter($package->order->receiver_email) &&
                            $this->getCharacter($group->receiver_phone) == $this->getCharacter($package->order->receiver_phone) &&
                            substr(str_replace(' ', '', $this->getCharacter($group->receiver_phone)), -9) == substr(str_replace(' ', '', $this->getCharacter($package->order->receiver_phone)), -9) &&
                            $group->receiver_country_id == $package->order->receiver_country_id &&
                            $group->receiver_district_id == $package->order->receiver_district_id &&
                            $group->receiver_province_id == $package->order->receiver_province_id
                        )
                    )
                    ) {
                        $group->packages[] = $package;
                        $order_ids = explode(',',$group->order_ids);
                        if(!$group->order_ids || !in_array($package->order_id,$order_ids)){
                            $order_ids[] = $package->order_id;
                            $group->order_ids = implode(',',$order_ids);
                        }
                        $tracking_refs = explode(',',$group->tracking_reference_2);
                        if(!$group->tracking_reference_2 || !in_array($package->tracking_code,$tracking_refs)){
                            $tracking_refs[] = $package->tracking_code;
                            $group->tracking_reference_2 = implode(',',$tracking_refs);
                        }
                        $manifest_codes = explode(',',$group->manifest_code);
                        if(!$group->manifest_code || !in_array($package->manifest_code,$manifest_codes)){
                            $manifest_codes[] = $package->manifest_code;
                            $group->manifest_code = implode(',',$manifest_codes);
                        }
                        $group->delivery_note_weight = floatval($group->delivery_note_weight) + floatval($package->weight?$package->weight:0);
                        $group->delivery_note_dimension_w = $group->delivery_note_dimension_w + $package->dimension_w;
                        $group->delivery_note_dimension_l = $group->delivery_note_dimension_l + $package->dimension_l;
                        $group->delivery_note_dimension_h = $group->delivery_note_dimension_h + $package->dimension_h;
                        $group->order_ids = $package->order->id;
                        $group->delivery_note_change_weight = ($group->delivery_note_dimension_w * $group->delivery_note_dimension_l * $group->delivery_note_dimension_h) / 5;

                        $isGroup = true;
                        break;
                    }
                }
                if(!$isGroup){
                    $deliveryTemp = new DeliveryNote();
                    $deliveryTemp->customer_id = $package->order->customer_id;
                    $deliveryTemp->receiver_address_id = $package->order->receiver_address_id;
                    $deliveryTemp->receiver_email = $package->order->receiver_email;
                    $deliveryTemp->receiver_name = $package->order->receiver_name;
                    $deliveryTemp->receiver_phone = $package->order->receiver_phone;
                    $deliveryTemp->receiver_address = $package->order->receiver_address;
                    $deliveryTemp->receiver_province_id = $package->order->receiver_province_id;
                    $deliveryTemp->receiver_province_name = $package->order->receiver_province_name;
                    $deliveryTemp->receiver_district_id = $package->order->receiver_district_id;
                    $deliveryTemp->receiver_district_name = $package->order->receiver_district_name;
                    $deliveryTemp->receiver_country_id = $package->order->receiver_country_id;
                    $deliveryTemp->receiver_post_code = $package->order->receiver_post_code;
                    $deliveryTemp->receiver_country_name = $package->order->receiver_country_name;
                    $deliveryTemp->tracking_seller = $deliveryTemp->tracking_reference_1 =  $package->tracking_code;
                    $deliveryTemp->order_ids = $package->order->id;
                    $deliveryTemp->manifest_code = $package->manifest_code;
                    $deliveryTemp->delivery_note_dimension_w = $package->dimension_w;
                    $deliveryTemp->delivery_note_dimension_l = $package->dimension_l;
                    $deliveryTemp->delivery_note_dimension_h = $package->dimension_h;
                    $deliveryTemp->order_ids = $package->order->id;
                    $deliveryTemp->delivery_note_weight = $package->weight;
                    $deliveryTemp->delivery_note_change_weight = ($deliveryTemp->delivery_note_dimension_w * $deliveryTemp->delivery_note_dimension_l * $deliveryTemp->delivery_note_dimension_h) / 5;
                    $deliveryTemp->warehouse_id = $package->manifest->receive_warehouse_id;
                    $deliveryTemp->packages[] = $package;
                    $dataGroup[] = $deliveryTemp;
//                    $deliveryTemp->delivery_note_change_weight = $package->weight;
                }
            }else{
                $deliveryTemp = new DeliveryNote();
                $deliveryTemp->customer_id = $package->order->customer_id;
                $deliveryTemp->receiver_address_id = $package->order->receiver_address_id;
                $deliveryTemp->receiver_email = $package->order->receiver_email;
                $deliveryTemp->receiver_name = $package->order->receiver_name;
                $deliveryTemp->receiver_phone = $package->order->receiver_phone;
                $deliveryTemp->receiver_address = $package->order->receiver_address;
                $deliveryTemp->receiver_province_id = $package->order->receiver_province_id;
                $deliveryTemp->receiver_province_name = $package->order->receiver_province_name;
                $deliveryTemp->receiver_district_id = $package->order->receiver_district_id;
                $deliveryTemp->receiver_district_name = $package->order->receiver_district_name;
                $deliveryTemp->receiver_country_id = $package->order->receiver_country_id;
                $deliveryTemp->receiver_post_code = $package->order->receiver_post_code;
                $deliveryTemp->receiver_country_name = $package->order->receiver_country_name;
                $deliveryTemp->tracking_seller = $deliveryTemp->tracking_reference_1 =  $package->tracking_code;
                $deliveryTemp->order_ids = $package->order->id;
                $deliveryTemp->manifest_code = $package->manifest_code;
                $deliveryTemp->delivery_note_dimension_w = $package->dimension_w;
                $deliveryTemp->delivery_note_dimension_l = $package->dimension_l;
                $deliveryTemp->delivery_note_dimension_h = $package->dimension_h;
                $deliveryTemp->order_ids = $package->order->id;
                $deliveryTemp->delivery_note_weight = $package->weight;
                $deliveryTemp->delivery_note_change_weight = ($deliveryTemp->delivery_note_dimension_w * $deliveryTemp->delivery_note_dimension_l * $deliveryTemp->delivery_note_dimension_h) / 5;
                $deliveryTemp->warehouse_id = $package->manifest->receive_warehouse_id;
                $deliveryTemp->packages[] = $package;
                $dataGroup[] = $deliveryTemp;
            }
        }
        return $this->response(true,'Ok',$dataGroup);
    }

    function getCharacter($str)
    {
        $rs = str_replace(" ", "", strtolower($str));
        $rs = str_replace("-", "", strtolower($rs));
        $rs = str_replace(",", "", strtolower($rs));
        $rs = str_replace(".", "", strtolower($rs));
        $rs = str_replace("&", "", strtolower($rs));
        $rs = str_replace("*", "", strtolower($rs));
        $rs = str_replace("(", "", strtolower($rs));
        $rs = str_replace(")", "", strtolower($rs));
        return $rs;
    }
}