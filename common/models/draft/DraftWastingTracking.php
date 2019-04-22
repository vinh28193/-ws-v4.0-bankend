<?php


namespace common\models\draft;


class DraftWastingTracking extends \common\models\db\DraftWastingTracking
{
    const MERGE_CALLBACK = 'MERGE_CALLBACK';
    const MERGE_MANUAL = 'MERGE_MANUAL';
    const MERGE_BYHAND = 'MERGE_BYHAND'; // ToDo merge tracking Code by Employe supper or Support
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftWastingTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftWastingTrackingQuery(get_called_class());
    }

    public function createOrUpdate($validate = true){
        $draft_data = self::find()->where([
            'tracking_code' => $this->tracking_code,
            'product_id' => $this->product_id,
        ])->one();
        if(!$draft_data){
            $draft_data = self::find()->where([
                'tracking_code' => $this->tracking_code,
                'product_id' => null,
            ])->one();
            if(!$draft_data){
                $draft_data = new self();
            }
        }
        $draft_data->setAttributes($this->getAttributes());
        if($draft_data->save($validate)){
            return true;
        }else{
            print_r($draft_data->errors);
            die;
        }
    }
}
