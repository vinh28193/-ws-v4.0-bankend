<?php

use yii\db\Migration;

/**
 * Class m190220_090928_system_currency
 */
class m190220_090928_system_currency extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('system_currency',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'currency_code' => $this->string(255)->comment(""),
            'currency_symbol' => $this->string(255)->comment(""),
            'status' => $this->string(255)->comment(""),
        ],$tableOptions);

        // Insert Data
        $sql="INSERT INTO `system_currency` VALUES (1, 'VNĐ', 'vnđ', 'đ', '1');
                INSERT INTO `system_currency` VALUES (2, 'RMB', 'rmb', 'rmb', '1');
                INSERT INTO `system_currency` VALUES (3, 'USD', 'usd', '$', '1');
                INSERT INTO `system_currency` VALUES (4, 'JPY', 'JPY', '￥', '1');
                INSERT INTO `system_currency` VALUES (5, 'DOP', 'BTN', 'RON', '0');
                INSERT INTO `system_currency` VALUES (6, 'RON', 'CZK', 'ZMW', '0');
                INSERT INTO `system_currency` VALUES (7, 'EUR', 'MDL', 'CRC', '0');
                INSERT INTO `system_currency` VALUES (8, 'KYD', 'PAB', 'XCD', '0');
                INSERT INTO `system_currency` VALUES (9, 'BRL', 'ZMW', 'LKR', '1');
                INSERT INTO `system_currency` VALUES (10, 'KGS', 'MXN', 'COP', '1');";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_090928_system_currency cannot be reverted.\n";

        return false;
    }
    */
}
