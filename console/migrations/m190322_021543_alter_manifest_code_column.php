<?php

use yii\db\Migration;

/**
 * Class m190322_021543_alter_manifest_code_column
 */
class m190322_021543_alter_manifest_code_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('manifest','manifest_code', $this->string(32)->notNull()->comment('Mã kiện về (từ us/jp ..)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('manifest','manifest_code', $this->string()->notNull()->comment("Mã lô"));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190322_021543_alter_manifest_code_column cannot be reverted.\n";

        return false;
    }
    */
}
