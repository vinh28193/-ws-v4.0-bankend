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
        $form->type = 'ebay';
        Yii::info($form->getAttributes(), __METHOD__);
        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
            return $this->render('@frontend/views/common/no_search_results');
        }
        $data['ebay'] = $results;
        $form->type = 'amazon';
        Yii::info($form->getAttributes(), __METHOD__);
        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
            return $this->render('@frontend/views/common/no_search_results');
        }
        $data['amazon'] = $results;
        $form->type = 'amazon-jp';
        Yii::info($form->getAttributes(), __METHOD__);
        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
            return $this->render('@frontend/views/common/no_search_results');
        }
        $data['amazon-jp'] = $results;
        return $this->render('index',['data' => $data]);
    }
}