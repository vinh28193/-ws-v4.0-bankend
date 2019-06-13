<?php


namespace common\calculators;

use common\models\User;
use Yii;
use Exception;

class Condition extends Resolver
{
    const TYPE_INTEGER = 'int';
    const TYPE_STRING = 'string';

    const OPERATOR_GREATER_EQUAL = '>=';
    const OPERATOR_GREATER = '>';
    const OPERATOR_EQUAL = '==';
    const OPERATOR_LESS_EQUAL = '<=';
    const OPERATOR_LESS = '<';
    const OPERATOR_DIFFERENT = '!=';

    /**
     * @var string type of value calculator (string|integer)
     */
    public $type = self::TYPE_INTEGER;

    /**
     * @var string key
     */
    public $key;
    /**
     * @var string (==|<|<=|>|>=|!=)
     */
    public $operator;
    /**
     * @var string|integer
     */
    public $value;

    /**
     * @param $target
     * @return bool
     */
    public function pass($target)
    {
        try {
            $key = $this->resolveKey($this->key);
            $value = $this->resolve($target, $key);
            if ($key === 'getIsNew') {
                $value = $value ? 'new' : 'used';
            } elseif ($key === 'getUserLevel') {
                /** @var $value null|User */
                return $value === null ? User::LEVEL_NORMAL : $value->userLever;
            }
            return $this->check($value);
        } catch (Exception $exception) {
            Yii::info($exception, __METHOD__);
            return false;
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
                return $data >= $this->value;
                break;
            case self::OPERATOR_GREATER:
                return $data > $this->value;
                break;
            case self::OPERATOR_EQUAL:
                return $data == $this->value;
                break;
            case self::OPERATOR_LESS_EQUAL:
                return $data <= $this->value;
                break;
            case self::OPERATOR_LESS:
                return $data < $this->value;

                break;
            case self::OPERATOR_DIFFERENT:
                return $data != $this->value;
                break;
            default:
                return false;
        }
    }

    public function toArray()
    {
        return [
            'key' => $this->key,
            'operator' => $this->operator,
            'value' => $this->value,
            'type' => $this->type,
        ];
    }

    public function deception()
    {
        $decs = [
            self::OPERATOR_GREATER => 'greater',
            self::OPERATOR_GREATER_EQUAL => 'greater and equal',
            self::OPERATOR_EQUAL => 'equal',
            self::OPERATOR_LESS => 'less',
            self::OPERATOR_LESS_EQUAL => 'less and equal',
            self::OPERATOR_DIFFERENT => 'different'
        ];
        return "{$this->key} {$decs[$this->operator]} ({$this->type}){$this->value}";
    }
}