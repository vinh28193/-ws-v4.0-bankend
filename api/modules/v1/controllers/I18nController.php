<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\i18n\Message;
use common\i18n\SourceMessage;
use Yii;
use yii\helpers\ArrayHelper;

class I18nController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','get-lang','update','create'],
                'roles' => ['operation', 'master_operation','marketing','master_marketing','master_accountant','marketing_intent']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'get-lang' => ['GET'],
        ];
    }
    public function actionIndex(){
        $get = Yii::$app->request->get();
        $category = ArrayHelper::getValue($get,'category');
        $language = ArrayHelper::getValue($get,'language');
        $translation = ArrayHelper::getValue($get,'translation');
        $query = SourceMessage::find()->joinWith('messages');
        if (isset($category) && $category !== '') {
            $query->andWhere(['category' => $category]);
        }
        if (isset($language) && $language !== '') {
            $query->andWhere(['language' => $language]);
        }
        if (isset($translation) && $translation !== '') {
            $query->andWhere([
                'OR',
                ['like', 'message', $translation],
                ['like', 'translation', $translation],
            ]);
        }
        $total = $query->count();
        if (isset($get['limit'])) {
            $query->limit($get['limit'])->offset($get['offset']);
        }
        //$data = $query->createCommand()->getRawSql();
        $data = $query->asArray()->all();
        return $this->response(true, 'get sources message success', $data, $total);
    }
    public function actionGetLang()
    {
        $languages = require Yii::getAlias('@common/i18n/languages.php');
        return $this->response(true, 'get languages success', $languages, 1);
    }
    public function actionUpdate($id){
        $messages = ArrayHelper::getValue($this->post,'message', []);
        $count = 0;
        foreach ($messages as $message){
            $newMss = new Message();
            $newMss->setAttributes($message);
            if($newMss->createOrUpdate(false)){
                $count ++;
            }
        }
        return $this->response(true, 'update success '.$count.' !');
    }
    public function actionCreate(){
        $key = ArrayHelper::getValue($this->post,'key', '');
        $category = ArrayHelper::getValue($this->post,'category', '');
        /** @var SourceMessage $source */
        $source = SourceMessage::find()->where(['category' => $category, 'message' => $key])->one();
        if(!$source){
            $source = new SourceMessage();
        }else{
            return $this->response(false, 'duplicate key language!');
        }
        $source->category = $category;
        $source->message = $key;
        $source->save();
        return $this->response(true, 'create success!');
    }
}
