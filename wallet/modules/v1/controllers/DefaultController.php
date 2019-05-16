<?php

namespace wallet\modules\v1\controllers;

use common\components\Language;
use yii\web\Controller;

/**
 * Default controller for the `wallet` module
 */
class DefaultController extends WalletServiceController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $testMsg = Language::t('test-23', 'ddaay la test 23');
        $testMsg .= Language::t('test-33', 'This is test 33');
        $testMsg .= Language::t('test-3', 'This is test 3');
        $testMsg .= Language::t('test-35', 'This is test 34');
        $testMsg .= Language::t('test-4', 'ddaay la test 4');
        $testMsg .= Language::t('test-5', 'ddaay la test 5');
        return $this->response(true, 'Wellcome back ' . $testMsg, \Yii::$app->wallet->identity);
    }
}
