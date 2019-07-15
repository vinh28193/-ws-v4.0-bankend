<?php


namespace frontend\modules\amazon\controllers;


use common\products\forms\ProductSearchForm;
use Yii;
use yii\helpers\ArrayHelper;

class CategoryController extends AmazonController
{

    public function actionIndex($node)
    {

        $queryParams = $this->request->getQueryParams();
        $name = ArrayHelper::remove($queryParams, 'name');
        if (isset($queryParams['node'])) {
            unset($queryParams['node']);
        }
        $form = new ProductSearchForm();
        $form->load($queryParams);
        $form->type = 'amazon';
        $form->category = $node;

        Yii::info($form->getAttributes(), __METHOD__);
        if (!($results = $form->search()) || (isset($results['products']) && $results['products'] === 0)) {
//            return $this->renderPartial('@frontend/views/common/no_search_results',['form' => $form]);
        }
        var_dump($results);
        die;
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}