<?php

use yii\db\Migration;

/**
 * Class m190619_070537_insert_district
 */
class m190619_070537_insert_district extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('{{%system_district}}');
        $data = \common\helpers\ExcelHelper::read(\Yii::getAlias('@console').'/location/district.xls');
        if(is_array($data)){
            $k = 0;
            foreach ($data as $datum){
                if ($k == 0){
                    $k = 1;
                    foreach ($datum as $item){
                        try{
                            if(\yii\helpers\ArrayHelper::getValue($item,'ID')){
                                $cache = Yii::$app->cache->get('getCountry_'.\yii\helpers\ArrayHelper::getValue($item,'COUNTRY_CODE'));
                                if(!$cache){
                                    $cache = \common\models\db\SystemCountry::findOne(['country_code' => \yii\helpers\ArrayHelper::getValue($item,'COUNTRY_CODE')]);
                                    Yii::$app->cache->set('getCountry_'.\yii\helpers\ArrayHelper::getValue($item,'COUNTRY_CODE'),$cache);
                                }
                                echo \yii\helpers\ArrayHelper::getValue($item,'ID');
                                $this->insert('{{%system_district}}',[
                                    'id' => \yii\helpers\ArrayHelper::getValue($item,'ID'),
                                    'name' => (\yii\helpers\ArrayHelper::getValue($item,'DISTRICT_NAME')),
                                    'name_local' => (\yii\helpers\ArrayHelper::getValue($item,'DISTRICT_NAME_LOCAL')),
                                    'name_alias' => (\yii\helpers\ArrayHelper::getValue($item,'DISTRICT_NAME_CONVERT')),
                                    'display_order' => 0,
                                    'province_id' => (\yii\helpers\ArrayHelper::getValue($item,'PROVINCE_ID')),
                                    'country_id' => $cache->id,
                                    'created_at' => time(),
                                    'remove' => (\yii\helpers\ArrayHelper::getValue($item,'ACTIVE') == 1) ? 0 : 1,
                                    'version' => '4.0',
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
        echo "m190619_070537_insert_district cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190619_070537_insert_district cannot be reverted.\n";

        return false;
    }
    */
}
