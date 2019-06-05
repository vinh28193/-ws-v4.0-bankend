<?php

use yii\db\Migration;

/**
 * Class m190223_081430_add_column_type_authorzationCode
 */
class m190223_081430_add_column_type_authorzationCode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('authorization_codes','type',$this->string(50)->null()->defaultValue('user')->after('user_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('authorization_codes','type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_081430_add_column_type_authorzationCode cannot be reverted.\n";

        return false;
    }
    */
}
