<?php


namespace frontend\modules\cms\controllers;


use common\components\Cache;
use common\products\forms\ProductSearchForm;
use frontend\controllers\CmsController;
use Yii;
use linslin\yii2\curl;

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
    public function actionSearchAutoComplete(){
        $key = Yii::$app->request->post('k');
        $content = Cache::get('search_auto_complete_key_'.base64_encode($key)) ? Cache::get('search_auto_complete_key_'.base64_encode($key)) : '';
        if(!$content){
            $url = "https://completion.amazon.com/search/complete?method=completion&mkt=1&r=QHW0T16FVMD8GWM2WWM4&s=161-1591289-5903765&c=AWJECJG5N87M8&p=Detail&l=en_US&sv=desktop&client=amazon-search-ui&search-alias=aps&qs=&cf=1&fb=1&sc=1&q=".urlencode($key);
            $curl = new curl\Curl();
            $response = $curl->get($url);
            $response = is_array($response) ? $response : json_decode($response,true);
            if(isset($response[1]) && is_array($response[1]) && count($response[1]) > 0){
                foreach ($response[1] as $item){
                    $content .= '<option>'.$item.'</option>';
                }
            }
            if($content && $curl->responseCode == 200){
                Cache::set('search_auto_complete_key_'.base64_encode($key),$content,60*60*24*365);
            }
        }
        Yii::$app->response->format = 'json';
        return ['success' => true, 'content' => $content];
    }
}
