<?php

use yii\db\Migration;

/**
 * Class m190401_045331_Update_decimal_tables_tracking_code
 */
class m190401_045331_Update_decimal_tables_tracking_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tracking_code','weight' , $this->decimal(10)->defaultValue(0.00)->comment('seller Weight (kg)'));
        $this->alterColumn('tracking_code','quantity' , $this->decimal(2)->defaultValue(0.00)->comment('seller quantity'));
        $this->alterColumn('tracking_code','dimension_width' ,$this->decimal(10)->defaultValue(0.00)->comment('Width (cm)'));
        $this->alterColumn('tracking_code','dimension_length' , $this->decimal(10)->defaultValue(0.00)->comment('Length (cm)'));
        $this->alterColumn('tracking_code','dimension_height' , $this->decimal(10)->defaultValue(0.00)->comment('Height (cm)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190401_045331_Update_decimal_tables_tracking_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190401_045331_Update_decimal_tables_tracking_code cannot be reverted.\n";

        return false;
    }
    */
}
