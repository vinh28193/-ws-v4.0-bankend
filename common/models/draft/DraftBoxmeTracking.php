<?php


namespace common\models\draft;


class DraftBoxmeTracking extends \common\models\db\DraftBoxmeTracking
{
    const STATUS_CALLBACK_SUCCESS = "CALLBACK";
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftBoxmeTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftBoxmeTrackingQuery(get_called_class());
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
        return $draft_data->save($validate);
    }
}