<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 18:13
 */

namespace common\filters;


class ApiUrlRule extends \yii\rest\UrlRule
{

    public $sharePrefix = true;

    /**
     * {@inheritdoc}
     */
    protected function createRules()
    {
        $only = array_flip($this->only);
        $except = array_flip($this->except);
        $patterns = $this->extraPatterns + $this->patterns;
        $rules = [];
        foreach ($this->controller as $urlName => $controller) {
            $prefix = trim($this->prefix . '/' . $urlName, '/');
            \Yii::info($prefix);
            foreach ($patterns as $pattern => $action) {
                if (!isset($except[$action]) && (empty($only) || isset($only[$action]))) {
                    $action = $controller . '/' . $action;
                    $action = $this->sharePrefix ? trim($this->prefix . '/' . $action, '/') : $action;
                    $rules[$urlName][] = $this->createRule($pattern, $prefix, $action);
                }
            }
        }

        return $rules;
    }
}