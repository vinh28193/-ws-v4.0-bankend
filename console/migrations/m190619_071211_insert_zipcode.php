<?php

use yii\db\Migration;

/**
 * Class m190619_071211_insert_zipcode
 */
class m190619_071211_insert_zipcode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('{{%system_zipcode}}');
        $data = \common\helpers\ExcelHelper::read(\Yii::getAlias('@console').'/location/zipcode.xls');
        if(is_array($data)){
            $k = 0;
            foreach ($data as $datum){
                if ($k == 0){
                    $k = 1;
                    foreach ($datum as $item){
                        try{
                            if(\yii\helpers\ArrayHelper::getValue($item,'PROVINCE_ID')){
                                $cache = Yii::$app->cache->get('getProvince_'.\yii\helpers\ArrayHelper::getValue($item,'PROVINCE_ID'));
                                if(!$cache){
                                    $cache = \common\models\db\SystemStateProvince::findOne(['id' => \yii\helpers\ArrayHelper::getValue($item,'PROVINCE_ID')]);
                                    Yii::$app->cache->set('getProvince_'.\yii\helpers\ArrayHelper::getValue($item,'PROVINCE_ID'),$cache);
                                }
                                echo \yii\helpers\ArrayHelper::getValue($item,'ID');
                                $this->insert('{{%system_zipcode}}',[
                                    'id' => \yii\helpers\ArrayHelper::getValue($item,'ID'),
                                    'system_country_id' => $cache->country_id,
                                    'system_state_province_id' => (\yii\helpers\ArrayHelper::getValue($item,'PROVINCE_ID')),
                                    'system_district_id' => (\yii\helpers\ArrayHelper::getValue($item,'DISTRICT_ID')),
                                    'boxme_country_id' => $cache->country_id,
                                    'boxme_state_province_id' => (\yii\helpers\ArrayHelper::getValue($item,'PROVINCE_ID')),
                                    'boxme_district_id' =>  (\yii\helpers\ArrayHelper::getValue($item,'DISTRICT_ID')),
                                    'zip_code' => (\yii\helpers\ArrayHelper::getValue($item,'ZIPCODE')),
                                ]);
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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190619_071211_insert_zipcode cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190619_071211_insert_zipcode cannot be reverted.\n";

        return false;
    }
    */
}
