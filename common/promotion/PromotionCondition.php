<?php


namespace common\promotion;

use Yii;
use Exception;
use common\helpers\ObjectHelper;
use common\models\db\PromotionCondition as DbPromotionCondition;
use yii\helpers\StringHelper;

/**
 * Class PromotionCondition
 * @package common\promotion
 *
 * @property PromotionConditionConfig $promotionConditionConfig
 */
class PromotionCondition extends DbPromotionCondition
{
    /**
     * php type cast
     */
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_ARRAY = 'array';
    const TYPE_DATETIME = 'datetime';

    public $operatorMapping = [
        'lt' => '<',
        'gt' => '>',
        'lte' => '<=',
        'gte' => '>=',
        'eq' => '=',
        'neq' => '!=',
        'in' => 'IN',
        'nin' => 'NOT IN',
        'like' => 'LIKE',
    ];

    /**
     * @param PromotionRequest $request
     * @return boolean
     */
    public function checkConditionRecursive(PromotionRequest $request)
    {
        if (($config = $this->promotionConditionConfig) === null) {
            return false;
        }
        $value = $this->resolveRequest($request);
        $valueType = gettype($value);
        if ($config->type_cast !== null) {
            if ($config->type_cast !== self::TYPE_BOOLEAN && $valueType === self::TYPE_BOOLEAN) {
                return false;
            }

            $this->value = $this->normalizeTypeCast($config->type_cast, $this->value);

            if (($config->type_cast === self::TYPE_INTEGER || $config->type_cast === self::TYPE_STRING) && $valueType !== $config->type_cast) {
                $value = $this->normalizeTypeCast($config->type_cast, $value);
            }
        }
        /**
         * Todo depend condition here
         * ví dụ điều kiện là hàng ebay nhưng mà phải trong danh mục đồng hồ
         * khi check điều kiện hàng ebay thì phải check xem có nằm trong danh mục đồng hồ không
         * hoặc ngược lại
         *
         * NOTE: vì hàm đệ quy nên phải tìm điểm dừng, tránh đệ quy liên tục
         * 1, created from config
         * 2, call function recursive
         */

        if ($config->operator === null) {
            return false;
        }
        $operatorsExecutor = [
            '<' => 'executeNormalCondition',
            '>' => 'executeNormalCondition',
            '<=' => 'executeNormalCondition',
            '>=' => 'executeNormalCondition',
            '=' => 'executeNormalCondition',
            '!=' => 'executeNormalCondition',
            'IN' => 'executeInCondition',
            'NOT IN' => 'executeInCondition',
            'LIKE' => 'executeLikeCondition',
        ];
        $operator = isset($this->operatorMapping[$config->operator]) ? $this->operatorMapping[$config->operator] : $config->operator;
        if (isset($operatorsExecutor[$operator]) && ($method = $operatorsExecutor[$operator]) !== null) {
            return call_user_func_array([$this, $method], $method === 'executeNormalCondition' ? [$operator, $value] : [$value]);
        }
        return $this->executeNormalCondition($operator, $value);
    }

    /**
     * @param $operator
     * @param $value
     * @return boolean
     */
    public function executeNormalCondition($operator, $value)
    {
        return $this->evalCondition("$value $operator {$this->value}");
    }

    public function executeInCondition($value)
    {
        $conditionValue = $this->value;
        if (!is_array($conditionValue)) {
            $conditionValue = [$conditionValue];
        }
        return in_array($value, $conditionValue);
    }

    public function executeNotInCondition($value)
    {
        return !$this->executeInCondition($value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function executeLikeCondition($value)
    {
        return strpos($this->value, $value) !== false;
    }

    /**
     * @param PromotionRequest $request
     * @return bool|mixed
     */
    protected function resolveRequest(PromotionRequest $request)
    {
        try {
            return ObjectHelper::resolve($request, $this->name);
        } catch (Exception $exception) {
            Yii::error($exception, __METHOD__);
            return false;
        }
    }

    /**
     * @param $type
     * @param $value
     * @return array|bool|int|mixed
     */
    protected function normalizeTypeCast($type, $value)
    {
        if ($type === self::TYPE_ARRAY) {
            $value = StringHelper::explode($value, ';');
        } elseif ($type === self::TYPE_INTEGER) {
            $value = (integer)$value;
        } elseif ($type === self::TYPE_BOOLEAN) {
            $value = strtolower($value) === 'true';
        }
        return $value;
    }

    /**
     * @param $condition
     * @return boolean
     */
    protected function evalCondition($condition)
    {
        return eval("return $condition;");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionConditionConfig()
    {
        return $this->hasOne(PromotionConditionConfig::className(), ['name' => 'name']);
    }
}