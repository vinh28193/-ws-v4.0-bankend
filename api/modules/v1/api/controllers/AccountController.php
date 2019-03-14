<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 25/02/2019
 * Time: 10:13
 */

namespace api\modules\v1\api\controllers;


use common\models\db\Customer;
use common\models\Address;
use common\models\db\SystemCountry;
use common\models\db\SystemDistrict;
use common\models\db\SystemStateProvince;

class AccountController extends AuthController
{
    public function actionAddAddress(){
        try{
            $mess = "Add new address success!";
            $address = new Address();
            $update = $this->setData($address);
            $mess = $update[0] ? $mess . $update[1] : $update[1] ;
            return $this->response($update[0],$mess);
        }catch (\Exception $e){
            $data = [];
            if(!YII_DEBUG){
                $data = [
                    'message' => $e->getMessage(),
                ];
            }else{
                $data = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'Previous' => $e->getPrevious(),
                    'TraceAsString' => $e->getTraceAsString(),
                ];
            }
            return $this->response(false,"Your request is invalid!",$data);
        }
    }

    /**
     * @param Address $address
     * @return array|mixed
     */
    public function setData($address){
        if(!$address){
            return [false,'Address not found!'];
        }
        if(!isset($this->post['country_id']) || empty($this->post['country_id']) || !($country = SystemCountry::findOne($this->post['country_id']))){
            return [false,"Your country is invalid!"];
        }

        if(!isset($this->post['province_id']) || empty($this->post['province_id']) || !($province = SystemStateProvince::findOne($this->post['province_id']))){
            return [false,"Your province is invalid!"];
        }

        if(!isset($this->post['district_id']) || empty($this->post['district_id']) || !($district = SystemDistrict::findOne($this->post['district_id']))){
            return [false,"Your district is invalid!"];
        }
        $address->setAttributes($this->post);
        $address->country_name = $country->name;
        $address->province_name = $province->name;
        $address->district_name = $district->name;
        if(!$address->validate()){
            $mess = "";
            foreach ($address->errors as $error){
                $mess .= $error[0];
            }
            return [false,$mess];
        }
        $mess ="";
        if(isset($this->post['is_default']) && $this->post['is_default']){
            $count = Address::updateAll(['is_default' => 0],['customer_id'=>$this->user->id,'is_default' => 1]);
            $mess = " And update default address success!";
            $address->is_default = 1;
        }else{
            $address->is_default = 0;
        }

        $address->store_id = $this->user->store_id;
        $address->customer_id = $this->user->id;
        $address->remove = isset($this->post['remove']) ? $this->post['remove'] : 0;
        $address->save(0);
        return [true,$mess];
    }

    public function actionUpdateAddress(){
        try{
            $mess = "Update address success!";
            $address = Address::findOne($this->post['id']);
            $update = $this->setData($address);
            $mess = $update[0] ? $mess . $update[1] : $update[1] ;
            return $this->response($update[0],$mess);
        }catch (\Exception $e){
            $data = [];
            if(!YII_DEBUG){
                $data = [
                    'message' => $e->getMessage(),
                ];
            }else{
                $data = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'Previous' => $e->getPrevious(),
                    'TraceAsString' => $e->getTraceAsString(),
                ];
            }
            return $this->response(false,"Your request is invalid!",$data);
        }
    }

    public function actionGetAllAddress(){
        $data = Address::find()
            ->where(['remove'=>0,'customer_id' => $this->user->id])->asArray()->all();
        return $this->response(true,"success!",$data);
    }

    public function actionUpdateProfile(){

    }

}