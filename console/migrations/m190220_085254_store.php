<?php

use yii\db\Migration;

/**
 * Class m190220_085254_store
 */
class m190220_085254_store extends Migration
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
        $this->createTable('store',[
            'id' => $this->primaryKey()->comment("ID"),
            'country_id' => $this->integer(11)->comment(""),
            'locale' => $this->string(255)->comment(""),
            'name' => $this->string(255)->comment(""),
            'country_name' => $this->string(255)->comment(""),
            'address' => $this->text()->comment(""),
            'url' => $this->string(255)->comment(""),
            'currency' => $this->string(255)->comment(""),
            'currency_id' => $this->integer(11)->comment(""),
            'status' => $this->integer(11)->comment(""),
            'env' => $this->integer(11)->comment("PROD or UAT or BETA ..."),
            ],$tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190220_085254_store cannot be reverted.\n";

//        foreach ($this->list as $data){
//            $this->dropIndex('idx-store-'.$data['column'], 'store');
//            $this->dropForeignKey('fk-store-'.$data['column'], 'store');
//        }
//        $this->dropTable('store');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_085254_store cannot be reverted.\n";

        return false;
    }
    */
}
