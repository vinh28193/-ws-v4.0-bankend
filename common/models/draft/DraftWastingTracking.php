<?php


namespace common\models\draft;


class DraftWastingTracking extends \common\models\db\DraftWastingTracking
{
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftWastingTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftWastingTrackingQuery(get_called_class());
    }

    public function createOrUpdate($validate = true){
        $draft_data = DraftDataTracking::find()->where([
            'tracking_code' => $this->tracking_code,
            'product_id' => $this->product_id,
        ])->one();
        if(!$draft_data){
            $draft_data = DraftDataTracking::find()->where([
                'tracking_code' => $this->tracking_code,
                'product_id' => null,
            ])->one();
            if(!$draft_data){
                $draft_data = new DraftDataTracking();
            }
        }
        $draft_data->setAttributes($this->getAttributes());
        return $draft_data->save($validate);
    }
}