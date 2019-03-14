<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 26/02/2019
 * Time: 15:51
 */

namespace common\fixtures;


use common\fixtures\components\FixtureUtility;
use common\models\db\PackageItem;
use Faker\Generator;
use yii\test\ActiveFixture;

class PackageItemFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\PackageItem';

    public function load()
    {
        $dataPackages = FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\package.php',null);
        foreach ($dataPackages as $package){
            $orders = explode(',',$package['order_ids']);
            foreach ($orders as $order){
                $products = (FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\product.php',null,['order_id' => $order]));
                if(count($products) == 0){
                    echo 12;
                    continue;
                }
                $product = $products[rand(0,count($products) - 1)];
                $data = [
                    'package_id' => $package['id'],
                    'package_code' => $package['package_code'],
                    'box_me_warehouse_tag' => $package['current_status'] == 'STOCK_IN_LOCAL' ? 'CB_TAG-'.FixtureUtility::getRandomCode(16) : null,
                    'order_id' => $order,
                    'sku' => $product['sku'],
                    'quantity' => $quantity = rand(1,$product['quantity_customer']),
                    'weight' => $product['total_weight_temporary'] / $product['quantity_customer'] * $quantity,
                    'dimension_l' => $dl = rand(1,$package['package_dimension_l']),
                    'dimension_w' => $dw = rand(1,$package['package_dimension_w']),
                    'dimension_h' => $dh = rand(1,$package['package_dimension_h']),
                    'change_weight' => round(($dl * $dw * $dh)/5,2),
                    'stock_in_local' => $package['stock_in_local'],
                    'stock_out_local' => $stock_out_local = null,//$package['stock_in_local'] ? rand(0,1) ? rand($package['stock_in_local'],time()) : null : null,
                    'at_customer' => $atcustomer = null,//$stock_out_local ? rand(0,1) ?  rand($stock_out_local,time()) : null : null,
                    'returned' => $returned = null,//$stock_out_local ? rand(0,1) ?  rand($stock_out_local,time()) : null : null,
                    'lost' => $package['lost'],
                    'current_status' => $package['lost'] ? "LOST" : $package['stock_in_local'] ? "STOCK_IN_LOCAL" : $stock_out_local ? "STOCK_OUT_LOCAL" : $atcustomer ? "AT_CUSTOMER" : $returned ? "RETURNED" : $package['current_status'],
                    'shipment_id' => null,
                    'created_at' => $created_at = rand($package['seller_shipped'] - 60*60*24*15,$package['seller_shipped']),
                    'updated_at' => rand($created_at,time()),
                    'remove' => 0,
                ];
                $this->data = [];
                $table = $this->getTableSchema();
                $this->db->schema->insert($table->fullName, $data);
            }
        }
    }
}