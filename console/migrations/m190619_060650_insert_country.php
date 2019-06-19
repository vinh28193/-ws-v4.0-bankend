<?php

use yii\db\Migration;

/**
 * Class m190619_060650_insert_country
 */
class m190619_060650_insert_country extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        $this->delete('{{%system_country}}');
        $data = \common\helpers\ExcelHelper::read(\Yii::getAlias('@console').'/location/country.xls');
        if(is_array($data)){
            $k = 0;
            foreach ($data as $datum){
                if ($k == 0){
                    $k = 1;
                    foreach ($datum as $item){
                        try{
                            if(\yii\helpers\ArrayHelper::getValue($item,'ID')){
                                $this->insert('{{%system_country}}',[
                                    'id' => \yii\helpers\ArrayHelper::getValue($item,'ID'),
                                    'name' => \yii\helpers\ArrayHelper::getValue($item,'COUNTRY_NAME'),
                                    'country_code' => strtoupper(\yii\helpers\ArrayHelper::getValue($item,'COUNTRY_CODE')),
                                    'country_code_2' => strtolower(\yii\helpers\ArrayHelper::getValue($item,'COUNTRY_CODE')),
                                    'language' => strtolower(\yii\helpers\ArrayHelper::getValue($item,'COUNTRY_CODE')),
                                    'status' => strtolower(\yii\helpers\ArrayHelper::getValue($item,'ACTIVE')),
                                    'version' => '4.0',
                                ]);
                                echo \yii\helpers\ArrayHelper::getValue($item,'ID');
                            }
                        }catch (Exception $e){
                            echo $e->getMessage();
                        }
                    }
                }else{
                    break;
                }
            }
        }
        $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%system_country}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190619_060650_insert_country cannot be reverted.\n";

        return false;
    }
    */
}
