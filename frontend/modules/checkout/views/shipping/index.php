<?php

use yii\helpers\Html;
use frontend\modules\checkout\WizardWidget;

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var frontend\modules\checkout\Payment $payment */
/* @var frontend\modules\checkout\models\ShippingForm $shippingForm */

$showStep = true;

echo $this->render('step/step1');