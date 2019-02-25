<?php
namespace console\controllers;

use Faker\Factory;
use yii\console\Controller;
use common\models\db\Customer;

class CustomerController extends Controller
{

    public function actionIndex(){
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create('vi_VN');

// generate data by accessing properties
        echo $faker->name;
        // 'Vương Thủy';
        echo $faker->address;
        // "57, Thôn Sáng Đào, Thôn Lạc Thịnh, Huyện Học Hoàng , Cà Mau"
        echo $faker->text;
        //  it sunt sed placeat. Ipsum quas expedita molestiae voluptas quo. Repudiandae labore itaque eaque quis.
    }

    public function actionSeedCutomer()
    {
         $_customer = new Customer();
         $faker = Factory::create('vi_VN');
         //$_customer->access_token = $faker->

        php -r "print_r(get_loaded_extensions())"
    }
}