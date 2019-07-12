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
/* @var array $categories */
/* @var array $filters */
/* @var array $sorts */
/* @var common\components\StoreManager $storeManager */
$sort = Yii::$app->request->get('sort','price');
$this->title = Yii::t('frontend','{keyword} - Shopping US Amazon, eBay - Weshop {web_name}',['keyword' => $keyword, 'web_name' => Yii::$app->storeManager->getName()]);
$url_page = function ($p){
    $param = [explode('?',\yii\helpers\Url::current())[0]];
    $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
    $param['page'] = $p;
    if (isset($param['keyword'])) {
        unset($param['keyword']);
    }
//           $param['portal'] = $portal;
    return Yii::$app->getUrlManager()->createUrl($param);
};
$url_cate = function ($id) use ($portal) {
    $param = [explode('?', \yii\helpers\Url::current())[0]];
    $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
    if(strtolower($portal) != 'ebay'){
        $param['filter'] = $id;
    }else{
        $param['category'] = $id;
    }
    if (isset($param['keyword'])) {
        unset($param['keyword']);
    }
//    $param['portal'] = $portal;
    return Yii::$app->getUrlManager()->createUrl($param);
};
$default = Yii::$app->request->cookies->getValue('user_setting_default_search');
$tooltip = Yii::t('frontend','Press ENTER to search ');
$js = <<<JS
    $('input[name=formPrice]').tooltip({'trigger':'focus', 'title': '$tooltip'});
    $('input[name=toPrice]').tooltip({'trigger':'focus', 'title': '$tooltip'});
JS;
$this->registerJs($js);
?>
<div class="search-content search-2 <?= $portal ?>">
    <div class="title-box inline mobile-hide">
        <div class="lable-titlebox"><?= Yii::t('frontend','Choose website') ?> </div>
        <div class="btn-group btn-group-sn" style="padding-right: 20px">
            <button class="btn btn-default <?= $portal != 'ebay' ? 'active-btn' : '' ?>" <?= $portal != 'ebay' ? '' : 'onclick="ws.loading(true);location.assign(\'/amazon/search/'.Yii::$app->request->get('keyword','').'.html\')"' ?>>
                <i class="ico ico-amazon <?= $portal != 'ebay' ? 'active' : '' ?>"></i>
            </button>
            <button class="btn btn-default <?= $portal == 'ebay' ? 'active-btn' : '' ?>" <?= $portal == 'ebay' ? '' : 'onclick="ws.loading(true);location.assign(\'/ebay/search/'.Yii::$app->request->get('keyword','').'.html\')"' ?>>
                <i class="ico ico-ebay <?= $portal == 'ebay' ? 'active' : '' ?>"></i>
            </button>
        </div>
        <div class="btn-group btn-group-sm"  style="padding-right: 20px">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= isset($sorts[$sort]) ? $sorts[$sort] : Yii::t('frontend','Sort by'); ?></button>
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
        <?php if($portal == 'ebay') {?>
            <div class="lable-titlebox"><?= Yii::t('frontend','Price range (USD)') ?> </div>
            <div class="form-inline" style="padding-right: 20px">
                <input class="form-control form-control-sm" type="number" name="formPrice" placeholder="Price form">
                <span style="padding: 10px">—</span>
                <input class="form-control form-control-sm" type="number" name="toPrice" placeholder="Price to">
            </div>
        <?php }?>
        <div class="form-check lable-titlebox" style="margin-left: 15px;">
            <?php
            if($portal != 'ebay'){ ?>
                <input class="form-check-input" type="checkbox" name="isPrime" id="isPrime">
                <label class="form-check-label isPrime" for="isPrime">
                    <img src="/images/logo/prime.png" >
                </label>

            <?php } ?>
        </div>
    </div>
    <div class="mobile-show">
        <div class="row" style="margin-top: -15px;margin-bottom: 15px;background: #fff;display:flex;border-top: 0.5px solid;padding:  10px">
            <div class="col-sm-6 col-6">
                <div class="btn-group" style="padding-right: 20px">
                    <button class="btn btn-outline-dark" <?= $portal != 'ebay' ? '' : 'onclick="ws.loading(true);location.assign(\'/amazon/search/'.Yii::$app->request->get('keyword','').'.html\')"' ?>>
                        <i class="ico ico-amazon <?= $portal != 'ebay' ? 'active' : '' ?>"></i>
                    </button>
                    <button class="btn btn-outline-dark" <?= $portal == 'ebay' ? '' : 'onclick="ws.loading(true);location.assign(\'/ebay/search/'.Yii::$app->request->get('keyword','').'.html\')"' ?>>
                        <i class="ico ico-ebay <?= $portal == 'ebay' ? 'active' : '' ?>"></i>
                    </button>
                </div>
            </div>
            <div class="col-sm-6 col-6 text-right">
                <button class="btn" id="showFilter-mb" <?= $portal == 'ebay' ? '' : 'onclick="ws.loading(true);location.assign(\'/ebay/search/'.Yii::$app->request->get('keyword','').'.html\')"' ?>>
                    <i class="la la-filter"></i> <?= Yii::t('frontend','Filter') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="mb-menu-filter mobile-show">
        <div class="mb-wraper-filter" style="float: right">
            <div class="title-mb-menu">
                <i class="la la-angle-left"></i>
                <span>Lọc theo kết quả</span>
            </div>
            <div class="content-cate-mb">
                <ul class="mb-menu-filter-cate">
                    <li role="presentation">
                        <div class="title-submenu">
                            <a class="dropdown-collapse" data-toggle="collapse"
                               data-target="#sort-by-filter" style="display: block"
                               aria-expanded="true" aria-controls="collapseOne">
                                <?= Yii::t('frontend','Sort by'); ?>
                                <i class="la la-angle-right alert-right"></i>
                            </a>
                        </div>
                        <div class="clearfix submenu-2 collapse" id="sort-by-filter">
                            <ul>
                                <?php
                                $activeSort = isset($sorts[$sort]) ? $sorts[$sort] : '';
                                foreach ($sorts as $k => $v){
                                    $param = [explode('?',\yii\helpers\Url::current())[0]];
                                    $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
                                    $param['sort'] = $k;

                                    if(isset($param['keyword'])){
                                        unset($param['keyword']);
                                    }
                                    $url = Yii::$app->getUrlManager()->createUrl($param);
                                    echo '<li><a href="'.$url.'" class="dropdown-item '.($activeSort == $v ? 'active' : '').'">'.$v.'</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </li>
                    <li role="presentation">
                        <div class="title-submenu">
                            <a class="dropdown-collapse" data-toggle="collapse"
                               data-target="#category-filter" style="display: block"
                               aria-expanded="true" aria-controls="collapseOne">
                                <?= Yii::t('frontend', 'Category'); ?>
                                <i class="la la-angle-right alert-right"></i>
                            </a>
                        </div>
                        <div class="clearfix submenu-2 collapse" id="category-filter">
                            <ul>
                                <?php foreach ($categories as $index => $category): ?>
                                    <?php /* @var $category array */?>
                                    <li class="accordion">
                                        <?= Html::a($category['category_name'], $url_cate($category['category_id']), ['onclick' => "ws.loading(true);"]); ?>
                                        <?php if (isset($category['child_category']) && ($childs = $category['child_category']) !== null && count($childs) > 0): ?>
                                            <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-<?= $index; ?>"
                                               aria-expanded="true" aria-controls="collapseOne"><i class="la la-angle-right alert-right"></i></a>
                                            <div id="sub-<?= $index; ?>" class="collapse" aria-labelledby="headingOne"
                                                 data-parent="#sub-menu-collapse">
                                                <ul class="sub-category">
                                                    <?php foreach ($childs as $child): ?>
                                                        <li>
                                                            <?= Html::a($child['category_name'], $url_cate($child['category_id']), []); ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                    <?php foreach ($filters as $k => $filter) {
                        if(!$filter['name']){
                            continue;
                        }
                        if ($portal === 'amazon-jp') {
                            $portal = 'amazon';
                        }?>
                        <li role="presentation">
                            <div class="title-submenu">
                                <a class="dropdown-collapse" data-toggle="collapse"
                                   data-target="#filter-<?= $k ?>-filter" style="display: block"
                                   aria-expanded="true" aria-controls="collapseOne">
                                    <?= Yii::t('frontend', $filter['name']); ?>
                                    <i class="la la-angle-right alert-right"></i>
                                </a>
                            </div>
                            <div class="clearfix submenu-2 collapse" id="filter-<?= $k ?>-filter">
                                <ul>
                                    <?php foreach ($filter['values'] as $key => $item): ?>
                                        <?php
                                        if(is_array($item)){
                                            $value = Html::encode($item['param']);
                                        }else{
                                            $value = Html::encode($item);
                                        }
                                        ?>
                                        <li>
                                            <div class="form-check">
                                                <?php
                                                $id = is_array($item) ? $item['value'] : $filter['name'] . $value;
                                                ?>
                                                <input class="form-check-input" name="filter"
                                                       type="checkbox"
                                                       value="<?= $value ?>"
                                                       id="<?= md5($id) ?>"
                                                       data-for="<?= $filter['name'] ?>"
                                                       data-value="<?= $value ?>">
                                                <label class="form-check-label" for="<?= md5($id) ?>">
                                                    <?php if (strpos(strtolower($filter['name']), 'customer review') && trim(strtolower($value)) != 'clear') { ?>
                                                        <i class="a-icon a-icon-star a-star-<?= intval($value) ?> review-rating">
                                                        </i>
                                                    <?php } ?>
                                                    <?= $value ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                        <?php } ?>
                    <li role="presentation">
                        <div class="title-submenu">
                            <a class="dropdown-collapse" data-toggle="collapse"
                               data-target="#price-range-filter" style="display: block"
                               aria-expanded="true" aria-controls="collapseOne">
                                <?= Yii::t('frontend', 'Price range (USD)'); ?>
                                <i class="la la-angle-down alert-right"></i></span>
                            </a>
                        </div>
                        <div class="clearfix submenu-2 collapse show" id="price-range-filter">
                            <div class="form-inline" style="padding-left: 20px">
                                <input class="form-control" style="width: 40%" type="number" name="formPrice" placeholder="Price form">
                                <span style="padding: 10px">—</span>
                                <input class="form-control" style="width: 40%" type="number" name="toPrice" placeholder="Price to">
                            </div>
                            <div class="btn-search-mb">
                                <button class="btn btn-info"><?= Yii::t('frontend','Confirm')?></button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="product-list row">
        <?php
        if($products && count($products)){
            foreach ($products as $product) {
                echo $this->render('_item', [
                    'portal' => $portal,
                    'product' => $product,
                    'storeManager' => $storeManager
                ]);
            }
        }else{
            echo '<div class="col-12" style="font-size: 18px;font-weight: 700;">'.Yii::t('frontend','No results for {keyword}.',['keyword' => $keyword]).'</div>';
            echo '<div class="col-12" style="font-size: 14px;font-weight: 700;">'.Yii::t('frontend','Try checking your spelling or use more general terms. Or you can try search on {portal}.',['portal' => $portal == 'ebay' ? 'Amazon' : 'eBay']).'</div>';
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-12 col-12" style="display: none;">
        <span><?= Yii::t('frontend', 'Showing {from}-{to} of {total} result', [
                'from' => 1,
                'to' => count($products),
                'total' => $total_product
            ]) ?></span>
    </div>
    <div class="col-md-8 col-sm-12 col-12">
        <nav aria-label="...">
            <ul class="pagination justify-content-center" style="margin-top: 0px;">
                <?php
                $limitPage = 5;
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
    </div>
</div>