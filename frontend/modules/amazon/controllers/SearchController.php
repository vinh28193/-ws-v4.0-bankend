<?php


namespace frontend\modules\amazon\controllers;


use common\products\forms\ProductSearchForm;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class SearchController extends AmazonController
{


    public function actionIndex()
    {
        $queryParams = $this->request->getQueryParams();
        Yii::info([" queryParams search "=>$queryParams],__CLASS__);
        $form = new ProductSearchForm();
        $form->load($queryParams);
        $form->keyword = str_replace("'s","",$form->keyword); // Key Word : men's watch
        $form->type = 'amazon';

        $this->site_title = Yii::t('frontend','{keyword} - Shopping US Amazon, eBay - {web_name}',['keyword' => $form->keyword, 'web_name' =>$this->storeManager->store->name]);
        $this->site_description = Yii::t('frontend','{keyword} - Shopping US Amazon, eBay  easily immediately via {web_name}  to get the product within 7-15 days with many attractive offers, support goods inspection, direct consultation before purchase!',[
            'keyword' => $form->keyword,
            'web_name' => $this->storeManager->store->name
        ]);


        Yii::info($form->getAttributes(), __METHOD__);
        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
//            return $this->render('@frontend/views/common/no_search_results');
        }
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}
