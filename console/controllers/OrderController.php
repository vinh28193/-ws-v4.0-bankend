<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 21/02/2019
 * Time: 17:11
 */

namespace console\controllers;


use common\models\db\Category;
use common\models\db\Product;
use common\models\PackageItem;
use Faker\Factory;
use yii\console\Controller;
use Yii;

class OrderController extends Controller
{
    public function actionCachesClear()
    {
        // Load all tables of the application in the schema
        //Yii::$app->db->schema->getTables();
        // clear the cache of all loaded tables
        Yii::$app->db->schema->refresh();
    }

    public function actionTestFackeData()
    {
        $faker = \Faker\Factory::create('vi_VN');

        var_dump($faker->citySuffix);   // 'Ville'
        var_dump($faker->streetSuffix);   // string(5) "Street"
        var_dump($faker->buildingNumber); // string(1) "4"
        var_dump($faker->city);           // string(12) "Hải Phòng"
        var_dump($faker->streetName);     // string(17) "167 Phố Đổng"
        var_dump($faker->streetAddress);  // string(25) "Phố Hàng Châu Khuyên"
        var_dump($faker->postcode);   // string(10) "36730-2184"
        var_dump($faker->address);   // string(78) "3068 Phố Lân, Xã Tiêu Chính Đức, Quận Vi Trầm Lễ Hồ Chí Minh"
        var_dump($faker->country);    // 'Falkland Islands (Malvinas)'
        var_dump($faker->latitude($min = -90, $max = 90));
        var_dump($faker->longitude($min = -180, $max = 180));  // 86.211205
    }

    public function actionIndex(){
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create();

       // generate data by accessing properties
        echo $faker->name;
        // 'Lucy Cechtelar';
        echo $faker->address;
        // "426 Jordy Lodge
        // Cartwrightshire, SC 88120-6700"
        echo $faker->text;
        // Sint velit eveniet. Rerum atque repellat voluptatem quia rerum. Numquam excepturi
        // beatae sint laudantium consequatur. Magni occaecati itaque sint et sit tempore. Nesciunt
        // amet quidem. Iusto deleniti cum autem ad quia aperiam.
        // A consectetur quos aliquam. In iste aliquid et aut similique suscipit. Consequatur qui
        // quaerat iste minus hic expedita. Consequuntur error magni et laboriosam. Aut aspernatur
        // voluptatem sit aliquam. Dolores voluptatum est.
        // Aut molestias et maxime. Fugit autem facilis quos vero. Eius quibusdam possimus est.
        // Ea quaerat et quisquam. Deleniti sunt quam. Adipisci consequatur id in occaecati.
        // Et sint et. Ut ducimus quod nemo ab voluptatum.
    }
    public function actionUpdateCategory(){
        /** @var Category[] $categories */
        $categories = Category::find()->all();
        foreach ($categories as $category){
            $category->name = $category->origin_name;
            echo $category->name.PHP_EOL;
            $category->save(0);
        }
    }

    public function actionFakePackageItem(){
        /** @var PackageItem[] $packageItems */
        $packageItems = PackageItem::find()->all();
        foreach ($packageItems as $packageItem){
            echo $packageItem->sku.PHP_EOL;
            /** @var Product $product */
            $product = Product::find()->where(['sku' => $packageItem->sku,'order_id' => $packageItem->order_id])->limit(1)->one();
            $packageItem->price = $product->price_amount_local;
            $packageItem->cod = rand(0,10) * 23500;
            echo "Ok ".PHP_EOL;
            $packageItem->save(0);
        }
    }
}