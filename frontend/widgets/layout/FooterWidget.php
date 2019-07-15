<?php
namespace frontend\widgets\layout;

use common\components\UserCookies;
use frontend\modules\payment\models\ShippingForm;
use Yii;

class FooterWidget extends \yii\base\Widget
{
    public function run()
    {

        $shippingForm = new ShippingForm();
        $shippingForm->setDefaultValues();
        $js = <<<JS
            $(document).ready(function() {
              $('#modal-address').modal({backdrop: 'static', keyboard: false});
            });
JS;
        $userCook = new UserCookies();
        $userCook->setUser();
        if(!$shippingForm->receiver_province_id || !$shippingForm->receiver_district_id){
            $view = $this->getView();
            $view->registerJs($js);
        }elseif (!$shippingForm->receiver_post_code && Yii::$app->storeManager->getId() == 7){
            $view = $this->getView();
            $view->registerJs($js);
        }

        return $this->render('footer',[
            'shippingForm' => $shippingForm
        ]);
    }
}