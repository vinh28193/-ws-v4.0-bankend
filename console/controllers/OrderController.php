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

class OrderController extends Controller
{
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