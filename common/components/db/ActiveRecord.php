<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 13:27
 */

namespace common\components\db;

use Yii;

/**
 * Class ActiveRecord
 * @package common\components\db
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $reflection = new \ReflectionClass($this);
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

        $behaviors = !empty($timestamp) ? array_merge($behaviors, [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
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

        $behaviors = !empty($blameable) ? array_merge($behaviors, [
            [
                'class' => \yii\behaviors\BlameableBehavior::className(),
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
    public static function getFormatter()
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
        $formatter = self::getFormatter();
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
            if (empty($fields) || in_array($field, $fields, true)) {
                if (in_array($field, $this->confidentialFields())) {
                    continue;
                } elseif (in_array($field, $this->timestampFields())) {
                    $result[$field] = $this->resolveTimestampField($definition);
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
            if (empty($expand) || in_array($field, $expand, true)) {
//                if (is_array($definition)) {
//                    $results = [];
//                    foreach ($definition as $fieldDefinition => $extraDefinition) {
//                        if (in_array($field, $this->confidentialFields())) {
//                            continue;
//                        } elseif (in_array($field, $this->timestampFields())) {
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
}