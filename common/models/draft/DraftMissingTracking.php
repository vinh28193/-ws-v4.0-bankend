<?php


namespace common\models\draft;


class DraftMissingTracking extends \common\models\db\DraftMissingTracking
{
    const MERGE_CALLBACK = 'MERGE_CALLBACK';
    const SPILT_MANUAL = 'SPILT_MANUAL';
    const MERGE_MANUAL = 'MERGE_MANUAL';
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftMissingTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftMissingTrackingQuery(get_called_class());
    }

    public function createOrUpdate($validate = true){
        $draft_data = self::find()->where([
            'tracking_code' => $this->tracking_code,
            'product_id' => $this->product_id,
            'order_id' => $this->order_id,
        ])->one();
        if(!$draft_data){
            $draft_data = self::find()->where([
                'tracking_code' => $this->tracking_code,
                'product_id' => null,
                'order_id' => null,
            ])->one();
            if(!$draft_data){
                $draft_data = new self();
            }
        }
        $draft_data->setAttributes($this->getAttributes());
        return $draft_data->save($validate);
    }
}