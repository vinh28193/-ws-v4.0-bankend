<?php

namespace common\modelsMongo;

use common\components\db\ActiveRecord;
use common\helpers\ChatHelper;
use ReflectionClass;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for collection "order_update_log".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $period
 * @property mixed $action
 * @property mixed $object_class;
 * @property mixed $object_identity;
 * @property mixed $order_code
 * @property mixed $old_attribute
 * @property mixed $dirty_attribute
 * @property mixed $diff_value
 * @property mixed $type
 * @property mixed $create_by
 * @property mixed $create_at
 * @property mixed $status
 */
class ActiveRecordUpdateLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'active_record_update_log'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'period',
            'action',
            'type',
            'object_class',
            'object_identity',
            'old_attribute',
            'dirty_attribute',
            'diff_value',
            'create_by',
            'create_at',
            'status'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['period', 'action', 'type', 'object_class', 'object_identity', 'old_attribute', 'dirty_attribute', 'diff_value', 'action', 'create_by', 'create_at','status'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'period' => 'Period',
            'action' => 'Action',
            'type' => 'Type',
            'object_class' => 'Object Class',
            'object_identity' => 'Object Identity',
            'old_attribute' => 'Old Attribute',
            'dirty_attribute' => 'Dirty Attribute',
            'diff_value' => 'Diff Value',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
        ];
    }

    public static function register($action, ActiveRecord $model)
    {
        $model->on(ActiveRecord::EVENT_AFTER_UPDATE, function ($event) use ($action) {
            /** @var $event  \yii\db\AfterSaveEvent */
            /** @var $sender ActiveRecord */
            $sender = $event->sender;
            $diffValue = [];
            $dirtyAttribute = [];
            $oldAttribute = [];
            $formatter = Yii::$app->formatter;

            foreach ($event->changedAttributes as $attribute => $value) {
                $newValue = $sender->getAttribute($attribute);
                if (!in_array($attribute, $sender->timestampFields())) {
                    $value = floatval($value);
                    $diffV = floatval($newValue) - $value;
                } else {
                    $newValue = $formatter->asDatetime($newValue);
                    $diffV = $newValue;
                    $value = $formatter->asDatetime($value);
                }
                $oldAttribute[$attribute] = $value;
                $dirtyAttribute[$attribute] = $newValue;
                $diffValue[$attribute] = $diffV;
            }


            if (!empty($dirtyAttribute)) {

                $reflection = new ReflectionClass($sender);
                $pk = (($p = $sender->primaryKey) && is_array($p)) ? (count($p) === 1 ? $p[0] : implode(',', $p)) : $p;
                if ($reflection->getShortName() === 'Order') {
                    $pk = $sender->ordercode;
                }
                /** @var  $original ActiveRecordUpdateLog */
                $original = self::find()->where(['and',['status' => 'active'] ,['type' => 'original'], ['object_class' => $reflection->getShortName()], ['object_identity' => $pk]])->one();


                $type = 'changed';
                Yii::info($original !== null, $type);
                if ($original !== null) {
                    $originalOldAttribute = $original->old_attribute;
                    $existOldAttribute = array_keys($originalOldAttribute);
                    Yii::info($original->old_attribute, '$existOldAttribute');
                    foreach ($oldAttribute as $name => $value) {
                        if (!ArrayHelper::isIn($name, $existOldAttribute)) {
                            $originalOldAttribute[$name] = $value;
                        }
                    }
                    $originalDirtyAttribute = ArrayHelper::merge($original->dirty_attribute, $dirtyAttribute);

                    $originalDiffValue = [];

                    foreach ($originalOldAttribute as $attribute => $value) {
                        $newValue = $originalDirtyAttribute[$attribute];
                        if (in_array($attribute, $sender->timestampFields())) {
                            continue;
                        }
                        $value = floatval($value);
                        $diffOV = floatval($newValue) - $value;
                        $originalDiffValue[$attribute] = $diffOV;
                    }
                    $original->old_attribute = $originalOldAttribute;
                    $original->dirty_attribute = $originalDirtyAttribute;
                    $original->diff_value = $originalDiffValue;
                    $original->save(false);

                } else {
                    $type = 'original';
                }

                $newLog = new self();
                $newLog->period = $formatter->asTime('now');
                $newLog->type = $type;
                $newLog->action = $action;
                $newLog->object_class = $reflection->getShortName();
                $newLog->object_identity = $pk;
                $newLog->old_attribute = $oldAttribute;
                $newLog->dirty_attribute = $dirtyAttribute;
                $newLog->diff_value = $diffValue;
                $newLog->create_by = Yii::$app->getUser()->getId();
                $newLog->create_at = $formatter->asDatetime('now');
                $newLog->status = 'active';
                $newLog->save(false);

                if ($reflection->getShortName() === 'Order') {
                    $info = [];
                    foreach ($dirtyAttribute as $name => $val) {
                        if (in_array($name, $sender->timestampFields())) {
                            continue;
                        }
                        $info[] = "<span class='font-weight-bold'>- {$sender->getAttributeLabel($name)} :</span> <br> Changed from `{$oldAttribute[$name]}` to `$val` diff value `{$diffValue[$name]}`";
                    }
                    $info = implode('<br> ', $info);
                    $messages = "<span class='text-danger font-weight-bold'>Order {$sender->ordercode}</span> <br> - $action <br> $info";
                    Yii::info($messages);
                    ChatHelper::push($messages, $sender->ordercode, 'GROUP_WS', 'SYSTEM', null);
                }
            }

        });
    }
}
