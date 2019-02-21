<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 21/02/2019
 * Time: 17:11
 */

namespace console\controllers;


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
}