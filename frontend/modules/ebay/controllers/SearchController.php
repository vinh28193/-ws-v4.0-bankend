<?php


namespace frontend\modules\ebay\controllers;

use Yii;
use common\products\forms\ProductSearchForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

class SearchController extends EbayController
{

    public function actionIndex()
    {
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $form = new ProductSearchForm();
        $form->load($queryParams);
        $form->type = 'ebay';
        $this->site_title = Yii::t('frontend','{keyword} - Shopping US Amazon, eBay - {web_name}',['keyword' => $form->keyword, 'web_name' =>$this->storeManager->store->name]);
        $this->site_description = Yii::t('frontend','{keyword} - Shopping US Amazon, eBay  easily immediately via {web_name}  to get the product within 7-15 days with many attractive offers, support goods inspection, direct consultation before purchase!',[
            'keyword' => $form->keyword,
            'web_name' => $this->storeManager->store->name
        ]);
        Yii::info($form->getAttributes(), __METHOD__);
        if (!($results = $form->search()) || (isset($results['products']) && $results['products'] === 0)) {
//            return $this->renderPartial('@frontend/views/common/no_search_results',['form' => $form]);
        }
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}
