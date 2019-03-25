<?php

use yii\db\Migration;

/**
 * Class m170916_100717_adding_employee_table
 */
class m170916_100717_adding_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $sql="CREATE TABLE `employee` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(200) NOT NULL,
              `email` varchar(100) NOT NULL,
              `created_at` timestamp default '0000-00-00 00:00:00', 
              `updated_at` timestamp null on update current_timestamp,
              PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        Yii::$app->db->createCommand($sql)->execute();


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170916_100717_adding_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
