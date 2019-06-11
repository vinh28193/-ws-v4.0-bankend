<?php


namespace frontend\modules\cms\controllers;


use common\products\forms\ProductSearchForm;
use frontend\controllers\CmsController;
use Yii;

class SearchController extends CmsController
{

    public function actionIndex(){
        $this->isShow = false;
        $keyword = Yii::$app->request->get('keyword');
        $form = new ProductSearchForm();
        $form->load(['keyword' => $keyword]);
//        $form->type = 'ebay';
//        Yii::info($form->getAttributes(), __METHOD__);
//        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
//            return $this->render('@frontend/views/common/no_search_results');
//        }
//        $data['ebay'] = $results;
        $form->type = Yii::$app->request->cookies->getValue('user_setting_default_search','amazon');
        Yii::info($form->getAttributes(), __METHOD__);
        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
            return $this->render('@frontend/views/common/no_search_results');
        }
        $data['amazon'] = $results;
        return $this->render('index',['data' => $data,'form' => $form]);
    }
    public function actionSetDefault(){
        Yii::$app->response->format = 'json';
        Yii::$app->response->data = ['success' => false,'message'=>'Cài đặt tìm kiếm mặc định thất bại.<br>Vui lòng thử lại sau. Xin cảm ơn.'];
        $post = Yii::$app->request->post('portal');
        if($post){
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'user_setting_default_search',
                'value' => $post,
            ]));

            Yii::$app->response->data = ['success' => true,'message'=>'Cài đặt tìm kiếm mặc định thành công.<br>Nếu muốn thay đổi vui lòng vào phần cài đặt tài khoản.<br>Nếu chưa đăng nhập tài khoản vui lòng đăng nhập ,đăng ký để thực hiện thay đổi tìm kiếm mặc định.<br>Xin cảm ơn.'];
        }
        Yii::$app->response->send();
    }
}
