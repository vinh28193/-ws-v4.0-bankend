<?php
namespace frontend\widgets\layout;

use common\components\UserCookies;
use frontend\modules\payment\models\ShippingForm;

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
        if(!$userCook->province_id || !$userCook->district_id){
            $view = $this->getView();
            $view->registerJs($js);
        }

        return $this->render('footer',[
            'shippingForm' => $shippingForm
        ]);
    }
}