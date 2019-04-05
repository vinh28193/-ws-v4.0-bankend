<?php
namespace common\models\draft;

class DraftDataTracking extends \common\models\db\DraftDataTracking
{
    const STATUS_EXTENSION = "EXTENSION";
    const STATUS_MAKE_US_SENDING = "MAKE_US_SENDING";
    const STATUS_CHECK_DONE = "CHECK_DONE";
    const STATUS_CHECK_DETAIL = "CHECK_DETAIL";

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
        $status = $draft_data->status != null && $draft_data->status != $this->status ? self::STATUS_CHECK_DONE : $this->status;
        $draft_data->setAttributes($this->getAttributes());
        $draft_data->status = $status;
        return $draft_data->save($validate);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftDataTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftDataTrackingQuery(get_called_class());
    }
}