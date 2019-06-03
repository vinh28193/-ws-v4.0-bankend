<?php

use common\helpers\WeshopHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var string $portal */
/* @var array $keyword */
/* @var integer $total_product */
/* @var integer $page */
/* @var integer $total_page */
/* @var integer $item_per_page */
/* @var array $products */
/* @var array $sorts */
/* @var common\components\StoreManager $storeManager */

$sort = Yii::$app->request->get('sort','price');
$url_page = function ($p){
    $param = [explode('?',\yii\helpers\Url::current())[0]];
    $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
    $param['page'] = $p;
//           $param['portal'] = $portal;
    return Yii::$app->getUrlManager()->createUrl($param);
};
?>
<div class="search-content search-2 <?= $portal ?>">
    <div class="title-box">
        <div class="left" style="width: 50%; text-align: left;">
            <div class="text"><?= Yii::t('frontend', 'Search “{keyword}” from', [
                    'keyword' => $keyword
                ]); ?></div>
            <img src="<?= WeshopHelper::getLogoByPortal($portal) ?>" alt=""/>
            <span><?= Yii::t('frontend', 'Showing {from}-{to} of {total} result', [
                    'from' => 1,
                    'to' => count($products),
                    'total' => $total_product
                ]) ?></span>
        </div>
        <div class="right" style="width: 50%; text-align: right;">
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= isset($sorts[$sort]) ? $sorts[$sort] : 'Sắp xếp theo' ?></button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="top-end" style="position: absolute; transform: translate3d(-56px, -102px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <?php
                        foreach ($sorts as $k => $v){
                            $param = [explode('?',\yii\helpers\Url::current())[0]];
                            $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
                            $param['sort'] = $k;
                            if(isset($param['keyword'])){
                                unset($param['keyword']);
                            }
                            $url = Yii::$app->getUrlManager()->createUrl($param);
                            echo '<a href="'.$url.'" class="dropdown-item">'.$v.'</a>';
                        }
                    ?>
                </div>
            </div>
            <ul class="control-page">
                <li><a href="<?= $page > 1 ? $url_page($page-1) : '#' ?>" class="control prev"></a></li>
                <li><a href="<?= $page < $total_page ? $url_page($page+1) : '#' ?>" class="control next"></a></li>
            </ul>
        </div>
    </div>

    <div class="product-list row">
        <?php
        foreach ($products as $product) {
            echo $this->render('_item', [
                'portal' => $portal,
                'product' => $product,
                'storeManager' => $storeManager
            ]);
        }
        ?>
    </div>
</div>

<nav aria-label="...">
    <ul class="pagination justify-content-center">
        <?php
        $limitPage = 10;
        $arr = WeshopHelper::getArrayPage($total_page,$page,$limitPage);
        if($arr && count($arr) > 1){
        ?>
            <li class="page-item">
                <a class="page-link" href="<?= $page>1 ? $url_page($page-1) : 'javascript: void (0)' ?>" tabindex="-1" aria-disabled="true"></a>
            </li>
        <?php
        if($arr[0] != 1){
            echo "<li class='page-item'><a class='page-link' href='".$url_page(1)."'>1</a></li>";
            echo "<li class='page-item'><span class='more'>...</span></li>";
        }
        foreach ($arr as $p){
            if($p == $page){
                echo "<li class='page-item active' aria-current='page'>" .
                    "<a class='page-link' href='".$url_page($p)."'>" .
                    "".$p." <span class='sr-only'>(current)</span>".
                    "</a>" .
                    "</li>";
            }elseif ($p == $total_page){
                echo "<li class='page-item active' aria-current='page'><a class='page-link last' href='".$url_page($p)."'>".$p."</a></li>";
            }else{
                echo "<li class='page-item'><a class='page-link' href='".$url_page($p)."'>".$p."</a></li>";
            }
        }
            if($arr[count($arr)-1] != $total_page){
                echo "<li class='page-item'><span class='more'>...</span></li>";
                echo "<li class='page-item'><a class='page-link last' href='".$url_page($total_page)."'>".$total_page."</a></li>";
            }
        ?>
        <li class="page-item">
            <a class="page-link" href="<?= $page<$total_page ? $url_page($page+1) : 'javascript: void (0)' ?>"></a>
        </li>
        <?php } ?>
    </ul>
</nav>