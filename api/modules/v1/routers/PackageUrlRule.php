<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 17:25
 */

namespace api\models\v1\routers;

class PackageUrlRule extends \yii\base\BaseObject implements \yii\web\UrlRuleInterface
{

    /**
     * @param \yii\web\UrlManager $manager
     * @param string $route
     * @param array $params
     * @return bool|string|void
     */
   public function createUrl($manager, $route, $params)
   {
       // TODO: Implement createUrl() method.
   }

    /**
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     * @return array|bool|void
     */
   public function parseRequest($manager, $request)
   {
       // TODO: Implement parseRequest() method.
   }
}