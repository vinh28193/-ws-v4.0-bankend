<?php


namespace console\controllers;


use common\models\db_old\Address;
use common\models\db_old\Customer;
use common\models\User;
use Yii;
use yii\console\Controller;

class UserController extends Controller
{
    const commentClone = 'clonedToV3';
    public function actionCloneUserOld() {
        $this->stdout('Bắt đầu chạy...'.PHP_EOL);
        /** @var Customer[] $usersOld */
        $usersOld = Customer::find()
            ->where(['or',['<>','AdminComment' ,self::commentClone],['AdminComment' => ''],['is','AdminComment' ,null]])->andWhere(['storeId' => [1,7]])->andWhere(['<>','phone','0912345678'])
            ->orderBy('id desc')->limit(500)->all();
        $this->stdout('Có '.count($usersOld).' items'.PHP_EOL);
        foreach ($usersOld as $k => $userOld){
            $this->stdout(($k + 1).'. '.PHP_EOL);
            $this->stdout('Kiểm tra user '.$userOld->id.'-'.$userOld->username.'-'.$userOld->email.'-'.$userOld->phone.'....'.PHP_EOL);
            if(!$userOld->email || !$userOld->phone){
                $this->stdout('Email hoặc phone đang trống. Không thể cập nhật.'.PHP_EOL);
            }else{
                $checkIs = User::find()
                    ->where(['or',['username' => $userOld->username], ['email' => $userOld->email], ['phone' => $userOld->phone],['id' => $userOld->id]])->orderBy('id desc')
                    ->limit(1)->select('id')->one();
                if(!$checkIs){
                    $this->stdout('Tạo mới User ...'.PHP_EOL);
                    $pass = Yii::$app->security->generateRandomString(32);
                    $customer = new User([
                        'id' => $userOld->id,
                        'phone' => $userOld->phone,
                        'last_name' => $userOld->lastName,
                        'first_name' => $userOld->firstName,
                        'username' => $userOld->username ? $userOld->username : $userOld->email,
                        'email' => $userOld->email,
                        'password' => $pass,
                        'updated_at' => time(),
                        'store_id' => $userOld->storeId,
                        'locale' => $userOld->storeId == 7 ? 'id-ID' : ($userOld->storeId == 1 ? 'vi-VN' : 'en-US'),
                        'active_shipping' => 0,
                        'total_xu' => 0,
                        'usable_xu' => 0,
                        'last_revenue_xu' => 0,
                        'email_verified' => 1,  // Nếu là Google Email lấy được rồi nên coi như là dã xác nhận
                        'phone_verified' => 1, // đăng kí trực tiếp nên xác nhận phone là đúng luôn chỉ còn vấn đề @ToDo Phone đúng đinh dạng Việt Nam
                        'gender' => 0,
                        'version' => 'v3',
                        'type_customer' => $userOld->customerGroupId == 2 ? 2 : 1, // set 1 là Khách Lẻ và 2 là Khách buôn - WholeSale Customer
                        'avatar' => $userOld->avatar,
                        'note_by_employee' => 'Khách hàng cũ từ weshop ver3',
                        'employee' => 0, //  1 Là Nhân viên , 0 là khách hàng
                        'vip' => 0, //  Mức độ Vip Của Khách Hàng không ap dụng cho nhân viên , theo thang điểm 0-5 số
                    ]);
                    $customer->setPassword($pass);
                    $customer->generateAuthKey();
                    if($customer->save(false)){
                        $this->stdout('Tạo mới Thành công. Id: '.$customer->id.PHP_EOL);
                        $userOld->AdminComment = self::commentClone;
                        $userOld->save(false);
                        /** @var Address[] $addresses */
                        $addresses = Address::find()->with(['systemDistrictMapping'])->where(['CustomerId' => $userOld->id,'isDeleted' => 0])->all();
                        $this->stdout('Kiểm tra địa chỉ ...'.PHP_EOL);
                        $c = 0 ;
                        foreach ($addresses as $address){
                            $this->stdout('Đồng bộ địa chỉ'.PHP_EOL);
                            if($address->systemDistrictMapping && $address->systemDistrictMapping->systemDistrict && $address->systemDistrictMapping->systemDistrict->province){
                                $c ++;
                                $newAddress = new \common\models\db\Address();
                                $this->stdout($c.PHP_EOL);
                                $newAddress->first_name = $address->FirstName;
                                $newAddress->last_name = $address->LastName;
                                $newAddress->email = $address->Email;
                                $newAddress->phone = $address->PhoneNumber;
                                $newAddress->country_id = $address->systemDistrictMapping->systemDistrict->country_id;
                                $newAddress->district_id = $address->systemDistrictMapping->systemDistrict->id;
                                $newAddress->province_id = $address->systemDistrictMapping->systemDistrict->province_id;
                                $newAddress->address = $address->Address1;
                                $newAddress->post_code = $address->ZipPostalCode;
                                $newAddress->store_id = $customer->store_id;
                                $newAddress->type = $address->Type == 'shipping' ? 2 : 1;
                                $newAddress->is_default = $address->IsDefault;
                                $newAddress->customer_id = $customer->id;
                                $newAddress->remove = 0;
                                $newAddress->version = '3.0';
                                $newAddress->created_at = strtotime($address->CreatedTime);
                                $newAddress->updated_at = time();
                                $newAddress->save(0);
                            }
                        }
                        /** @var \common\models\db\Address $adddef */
                        $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 1,'is_default' => 1])->orderBy('id desc')->limit(1)->one();
                        if(!$adddef){
                            $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 1])->orderBy('id desc')->limit(1)->one();
                            if($adddef){
                                $adddef->is_default = 1;
                                $adddef->save(0);
                            }
                        }
                        $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 2,'is_default' => 1])->orderBy('id desc')->limit(1)->one();
                        if(!$adddef){
                            $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 2])->orderBy('id desc')->limit(1)->one();
                            if($adddef){
                                $adddef->is_default = 1;
                                $adddef->save(0);
                            }
                        }
//                    Yii::$app->mailer
//                        ->compose(
//                            ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
//                            ['user' => $userOld]
//                        )
//                        //->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
//                        ->setFrom([Yii::$app->params['supportEmail'] => 'Weshop Việt Nam robot'])
//                        ->setTo($userOld->email)
//                        ->setSubject('Password reset for ' . Yii::$app->name)
//                        ->send();
                    }
                }else{
                    $this->stdout('Đã có user '.$checkIs->id.PHP_EOL);
                }
            }
            $userOld->AdminComment = self::commentClone;
            $userOld->save(0);
        }
    }

    public function actionCloneUserOldByEmail($email)
    {
        $this->stdout('Bắt đầu chạy... ' . $email . PHP_EOL);
        /** @var Customer $userOld */
        $userOld = Customer::find()
            ->where(['email' => $email])->andWhere(['or', ['<>', 'Deleted', 0], ['is', 'Deleted', null]])
            ->orderBy('id desc')->one();
        $this->stdout('Kiểm tra user ' . $userOld->username . '-' . $userOld->email . '-' . $userOld->phone . '....' . PHP_EOL);
        if (!$userOld->email || !$userOld->phone) {
            $this->stdout('Email hoặc phone đang trống. Không thể cập nhật.' . PHP_EOL);
        } else {
            $checkIs = User::find()
                ->where(['or', ['username' => $userOld->username], ['email' => $userOld->email], ['phone' => $userOld->phone], ['id' => $userOld->id]])->orderBy('id desc')
                ->limit(1)->select('id')->one();
            if (!$checkIs) {
                $this->stdout('Tạo mới User ...' . PHP_EOL);
                $pass = Yii::$app->security->generateRandomString(32);
                $customer = new User([
                    'id' => $userOld->id,
                    'phone' => $userOld->phone,
                    'last_name' => $userOld->lastName,
                    'first_name' => $userOld->firstName,
                    'username' => $userOld->username ? $userOld->username : $userOld->email,
                    'email' => $userOld->email,
                    'password' => $pass,
                    'updated_at' => time(),
                    'store_id' => $userOld->storeId,
                    'locale' => $userOld->storeId == 7 ? 'id-ID' : ($userOld->storeId == 1 ? 'vi-VN' : 'en-US'),
                    'active_shipping' => 0,
                    'total_xu' => 0,
                    'usable_xu' => 0,
                    'last_revenue_xu' => 0,
                    'email_verified' => 1,  // Nếu là Google Email lấy được rồi nên coi như là dã xác nhận
                    'phone_verified' => 1, // đăng kí trực tiếp nên xác nhận phone là đúng luôn chỉ còn vấn đề @ToDo Phone đúng đinh dạng Việt Nam
                    'gender' => 0,
                    'version' => 'v3',
                    'type_customer' => $userOld->customerGroupId == 2 ? 2 : 1, // set 1 là Khách Lẻ và 2 là Khách buôn - WholeSale Customer
                    'avatar' => $userOld->avatar,
                    'note_by_employee' => 'Khách hàng cũ từ weshop ver3',
                    'employee' => 0, //  1 Là Nhân viên , 0 là khách hàng
                    'vip' => 0, //  Mức độ Vip Của Khách Hàng không ap dụng cho nhân viên , theo thang điểm 0-5 số
                ]);
                $customer->setPassword($pass);
                $customer->generateAuthKey();
                if ($customer->save(false)) {
                    $this->stdout('Tạo mới Thành công. Id: '.$customer->id.PHP_EOL);
                    $userOld->AdminComment = self::commentClone;
                    $userOld->save(false);
                    /** @var Address[] $addresses */
                    $addresses = Address::find()->with(['systemDistrictMapping'])->where(['CustomerId' => $userOld->id, 'isDeleted' => 0])->all();
                    $this->stdout('Kiểm tra địa chỉ ...' . PHP_EOL);
                    $c = 0;
                    foreach ($addresses as $address) {
                        $this->stdout('Đồng bộ địa chỉ' . PHP_EOL);
                        if ($address->systemDistrictMapping && $address->systemDistrictMapping->systemDistrict && $address->systemDistrictMapping->systemDistrict->province) {
                            $c++;
                            $newAddress = new \common\models\db\Address();
                            $this->stdout($c . PHP_EOL);
                            $newAddress->first_name = $address->FirstName;
                            $newAddress->last_name = $address->LastName;
                            $newAddress->email = $address->Email;
                            $newAddress->phone = $address->PhoneNumber;
                            $newAddress->country_id = $address->systemDistrictMapping->systemDistrict->country_id;
                            $newAddress->district_id = $address->systemDistrictMapping->systemDistrict->id;
                            $newAddress->province_id = $address->systemDistrictMapping->systemDistrict->province_id;
                            $newAddress->address = $address->Address1;
                            $newAddress->post_code = $address->ZipPostalCode;
                            $newAddress->store_id = $customer->store_id;
                            $newAddress->type = $address->Type == 'shipping' ? 2 : 1;
                            $newAddress->is_default = $address->IsDefault;
                            $newAddress->customer_id = $customer->id;
                            $newAddress->remove = 0;
                            $newAddress->version = '3.0';
                            $newAddress->created_at = strtotime($address->CreatedTime);
                            $newAddress->updated_at = time();
                            $newAddress->save(0);
                        }
                    }
                    /** @var \common\models\db\Address $adddef */
                    $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 1, 'is_default' => 1])->orderBy('id desc')->limit(1)->one();
                    if (!$adddef) {
                        $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 1])->orderBy('id desc')->limit(1)->one();
                        if ($adddef) {
                            $adddef->is_default = 1;
                            $adddef->save(0);
                        }
                    }
                    $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 2, 'is_default' => 1])->orderBy('id desc')->limit(1)->one();
                    if (!$adddef) {
                        $adddef = \common\models\db\Address::find()->where(['customer_id' => $userOld->id, 'type' => 2])->orderBy('id desc')->limit(1)->one();
                        if ($adddef) {
                            $adddef->is_default = 1;
                            $adddef->save(0);
                        }
                    }
                }
            } else {
                $this->stdout('Đã có user ' . $checkIs->id . PHP_EOL);
            }
        }
        $userOld->AdminComment = self::commentClone;
        $userOld->save(0);
    }
}