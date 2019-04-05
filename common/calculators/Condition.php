<?php


namespace common\calculators;

use Yii;
use ReflectionClass;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;

use yii\base\BaseObject;

class Condition extends BaseObject
{
    const TYPE_INTEGER = 'int';
    const TYPE_STRING = 'string';

    const OPERATOR_GREATER_EQUAL = '>=';
    const OPERATOR_GREATER = '>';
    const OPERATOR_EQUAL = '==';
    const OPERATOR_LESS_EQUAL = '<=';
    const OPERATOR_LESS = '<';
    const OPERATOR_DIFFERENT = '!=';

    public $type = self::TYPE_INTEGER;

    public $key;
    public $operator;
    public $value;

    /**
     * @param $target
     * @return bool
     */
    public function pass($target)
    {
        try {
            $data = (new ResolveTarget())->resolve($target, $this->key);
            return $this->check($data);
        } catch (\Exception $exception) {
            Yii::info($exception, __METHOD__);
            return false;
        }
    }

    /**
     * @param  $target
     * @return mixed
     * @throws InvalidConfigException
     */
    public function resolve($target)
    {
        $reflection = ReflectionClass($target);
        $getter = "get" . Inflector::camelize($this->key);
        if ($reflection->hasProperty($this->key) || $reflection->hasMethod($this->key)) {
            return $target->{$this->key};
        } elseif ($reflection->hasMethod($getter)) {
            return $target->$getter;
        } else {
            throw new InvalidConfigException("can not resolve property or method {$this->key} in " . get_class($target));
        }
    }

    /**
     * @param $data
     * @return bool
     */
    protected function check($data)
    {
        switch ($this->operator) {
            case self::OPERATOR_GREATER_EQUAL:
                return $data > $this->value;
                break;
            case self::OPERATOR_GREATER:
                return $data < $this->value;
                break;
            case self::OPERATOR_EQUAL:
                return $data >= $this->value;
                break;
            case self::OPERATOR_LESS_EQUAL:
                return $data <= $this->value;
                break;
            case self::OPERATOR_LESS:
                return $data == $this->value;
                break;
            case self::OPERATOR_DIFFERENT:
                return $data != $this->value;
                break;
            default:
                return false;
        }
    }

    public function deception(){
        $decs = [
            self::OPERATOR_GREATER => 'greater',
            self::OPERATOR_GREATER_EQUAL => 'greater and equal',
            self::OPERATOR_EQUAL => 'equal',
            self::OPERATOR_LESS => 'less',
            self::OPERATOR_LESS_EQUAL => 'less and equal',
            self::OPERATOR_DIFFERENT => 'different'
        ];
        return [
            'array' => [
                'key' => $this->key,
                'operator' => $this->operator,
                'value' => $this->value,
                'type' => $this->type,
            ],
            'string' => "{$this->key} {$decs[$this->operator]}  ({$this->type}){$this->value}"
        ];
    }
}