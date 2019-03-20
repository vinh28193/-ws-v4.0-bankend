<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 13:27
 */

namespace common\components\db;

use ReflectionClass;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveRecord
 * @package common\components\db
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    const EVENT_BEFORE_RESOLVE_FIELD = 'beforeResolveField';
    const EVENT_AFTER_RESOLVE_FIELD = 'afterResolveField';

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return parent::scenarios();
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $reflection = new ReflectionClass($this);
        if ($reflection->getShortName() === 'ActiveRecord') {
            return $behaviors;
        }

        $timestamp = [];
        if ($this->hasAttribute('created_at')) {
            $timestamp[self::EVENT_BEFORE_INSERT][] = 'created_at';
        }
        if ($this->hasAttribute('updated_at')) {
//            $timestamp[self::EVENT_BEFORE_INSERT][] = 'updated_at';
            $timestamp[self::EVENT_BEFORE_UPDATE][] = 'updated_at';
        }

        $behaviors = !empty($timestamp) ? ArrayHelper::merge($behaviors, [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => $timestamp,
            ],
        ]) : $behaviors;

        $blameable = [];
        if ($this->hasAttribute('created_by')) {
            $blameable[self::EVENT_BEFORE_INSERT][] = 'created_by';
        }
        if ($this->hasAttribute('updated_by')) {
//            $blameable[self::EVENT_BEFORE_INSERT][] = 'updated_by';
            $blameable[self::EVENT_BEFORE_UPDATE][] = 'updated_by';
        }

        $behaviors = !empty($blameable) ? ArrayHelper::merge($behaviors, [
            [
                'class' => BlameableBehavior::className(),
                'attributes' => $blameable,
            ],
        ]) : $behaviors;

        return $behaviors;
    }


    /**
     * @inheritdoc
     * @return ActiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\i18n\Formatter
     */
    public function getFormatter()
    {
        return Yii::$app->getFormatter();
    }

    /**
     * @return string
     */
    public function getFirstErrors()
    {
        $errors = parent::getFirstErrors();
        $errors = reset($errors);
        return $errors;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return parent::formName();
    }

    /**
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        return parent::toArray($fields, $expand, $recursive);
    }

    /**
     * @return array
     */
    public function timestampFields()
    {
        return [
            'created_at',
            'updated_at'
        ];
    }

    /**
     * @return array
     */
    public function confidentialFields()
    {
        return [
            'created_by',
            'updated_by',
            'password_hash',
            'access_token',
            'auth_key'
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return parent::fields();
    }


    /**
     * @param $field
     * @return \Closure
     */
    protected function resolveTimestampField($field)
    {
        $formatter = $this->getFormatter();
        $formatter->nullDisplay = '';
        return function ($self) use ($field, $formatter) {
            return $formatter->asDatetime($self->$field);
        };

    }

    /**
     * Determines which fields can be returned by [[toArray()]].
     * This method will first extract the root fields from the given fields.
     * Then it will check the requested root fields against those declared in [[fields()]] and [[extraFields()]]
     * to determine which fields can be returned.
     * @param array $fields the fields being requested for exporting
     * @param array $expand the additional fields being requested for exporting
     * @return array the list of fields to be exported. The array keys are the field names, and the array values
     * are the corresponding object property names or PHP callables returning the field values.
     */
    protected function resolveFields(array $fields, array $expand)
    {
        $fields = $this->extractRootFields($fields);
        $expand = $this->extractRootFields($expand);

        $result = [];
        foreach ($this->fields() as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }
            if (empty($fields) || ArrayHelper::isIn($field, $fields, true)) {
                if (ArrayHelper::isIn($field, $this->confidentialFields())) {
                    continue;
                } elseif (ArrayHelper::isIn($field, $this->timestampFields())) {
                    $result[$field] = $this->resolveTimestampField($field);
                } else {
                    $result[$field] = $definition;
                }
            }
        }
//        if (empty($expand)) {
//            return $result;
//        }

        foreach ($this->extraFields() as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }
            if (empty($expand) || ArrayHelper::isIn($field, $expand, true)) {
//                if (ArrayHelper::isIn($definition)) {
//                    $results = [];
//                    foreach ($definition as $fieldDefinition => $extraDefinition) {
//                        if (ArrayHelper::isIn($fieldDefinition, $this->confidentialFields())) {
//                            continue;
//                        } elseif (ArrayHelper::isIn($fieldDefinition, $this->timestampFields())) {
//                            $results[$fieldDefinition] = $this->resolveTimestampField($extraDefinition);
//                        } else {
//                            $results[$fieldDefinition] = $extraDefinition;
//                        }
//                    }
//                    $definition = $result;
//                }
                $result[$field] = $definition;
            }
        }

        return $result;
    }

    /**
     * @param $fields
     * @param $expand
     * @return ResolveFieldEvent
     */
    public function beforeResolveField($fields, $expand)
    {
        $event = new ResolveFieldEvent();
        $event->fields = $fields;
        $event->expand = $expand;
        $this->trigger(self::EVENT_BEFORE_RESOLVE_FIELD,$event);
        return $event;
    }
}