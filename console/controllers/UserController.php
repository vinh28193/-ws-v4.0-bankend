<?php


namespace console\controllers;


use common\models\User;
use common\models\db_old\Customer;
use Yii;
use yii\console\Controller;

class UserController extends Controller
{
    const commentClone = 'clonedToV3';
    public function actionCloneUserOld() {
        /** @var Customer[] $usersOld */
        $usersOld = Customer::find()
            ->where(['AdminComment' => self::commentClone,'store_id' => [1,7]])
            ->limit(500)->all();
        foreach ($usersOld as $userOld){
            $checkIs = User::find()
                ->where(['or',['username' => $userOld->username], ['email' => $userOld->email], ['phone' => $userOld->phone],['id' => $userOld->id]])->orderBy('id desc')
                ->limit(1)->select('id')->one();
            if(!$checkIs){
                $pass = Yii::$app->security->generateRandomString(32);
                $customer = new User([
                    'id' => $userOld->id,
                    'phone' => $userOld->phone,
                    'last_name' => $userOld->lastName,
                    'first_name' => $userOld->firstName,
                    'username' => $userOld->username,
                    'email' => $userOld->email,
                    'password' => $pass,
                    'updated_at' => time(),
                    'store_id' => $userOld->storeId == 7 ? 2 : 1,
                    'locale' => $userOld->storeId == 7 ? 'id-ID' : 'vi-VN',
                    'active_shipping' => 0,
                    'total_xu' => 0,
                    'usable_xu' => 0,
                    'last_revenue_xu' => 0,
                    'email_verified' => 1,  // Nếu là Google Email lấy được rồi nên coi như là dã xác nhận
                    'phone_verified' => 1, // đăng kí trực tiếp nên xác nhận phone là đúng luôn chỉ còn vấn đề @ToDo Phone đúng đinh dạng Việt Nam
                    'gender' => 0,
                    'type_customer' => 1, // set 1 là Khách Lẻ và 2 là Khách buôn - WholeSale Customer
                    'avatar' => $userOld->avatar,
                    'note_by_employee' => 'Khách hàng cũ từ weshop ver3',
                    'employee' => 0, //  1 Là Nhân viên , 0 là khách hàng
                    'vip' => 0, //  Mức độ Vip Của Khách Hàng không ap dụng cho nhân viên , theo thang điểm 0-5 số
                ]);
                $customer->setPassword($pass);
                $customer->generateAuthKey();
                if($customer->save()){
                    $userOld->AdminComment = self::commentClone;
                    $userOld->save(false);

                }

            }
        }
    }
}