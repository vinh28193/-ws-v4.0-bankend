<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var string $portal */
/* @var string $category */
/* @var string $filter */
/* @var array $categories */
/* @var array $filters */
/* @var array $conditions */

// Todo UrlRule
// Hãy tham khảo mục filter để làm phần này
// push start
$url = function ($id) use ($portal) {
    $param = [explode('?', \yii\helpers\Url::current())[0]];
    $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
    $param['category'] = $id;
    if (isset($param['keyword'])) {
        unset($param['keyword']);
    }
//    $param['portal'] = $portal;
    return Yii::$app->getUrlManager()->createUrl($param);
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light" id="menu-left-search-sm" style="display: none">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="filter-content">
            <div class="filter-box category">
                <div class="title"><?= Yii::t('frontend', 'Category'); ?></div>
                <ul id="sub-menu-collapse">
                    <?php foreach ($categories as $index => $category): ?>
                        <?php /* @var $category array */ ?>
                        <li class="accordion">
                            <?= Html::a($category['category_name'], $url($category['category_id']), ['onclick' => "ws.loading(true);"]); ?>
                            <?php if (isset($category['child_category']) && ($childs = $category['child_category']) !== null && count($childs) > 0): ?>
                                <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-<?= $index; ?>"
                                   aria-expanded="true" aria-controls="collapseOne"><i class="la la-chevron-down"></i></a>
                                <div id="sub-<?= $index; ?>" class="collapse" aria-labelledby="headingOne"
                                     data-parent="#sub-menu-collapse">
                                    <ul class="sub-category">
                                        <?php foreach ($childs as $child): ?>
                                            <li>
                                                <?= Html::a($child['category_name'], $url($child['category_id']), []); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="filter-box category">
                <div class="title"><u><?= Yii::t('frontend', 'Filter'); ?></u></div>
                <?php foreach ($filters as $item) {
                    if ($portal === 'amazon-jp') {
                        $portal = 'amazon';
                    }
                    echo $this->render("filter/{$portal}", ['filter' => $item]);
                } ?>
            </div>
        </div>
    </div>
</nav>
<div class="filter-content"  id="menu-left-search">
    <div class="filter-box category">
        <div class="title"><?= Yii::t('frontend', 'Category'); ?></div>
        <ul id="sub-menu-collapse">
            <?php foreach ($categories as $index => $category): ?>
                <?php /* @var $category array */ ?>
                <li class="accordion">
                    <?= Html::a($category['category_name'], $url($category['category_id']), ['onclick' => "ws.loading(true);"]); ?>
                    <?php if (isset($category['child_category']) && ($childs = $category['child_category']) !== null && count($childs) > 0): ?>
                        <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-<?= $index; ?>"
                           aria-expanded="true" aria-controls="collapseOne"><i class="la la-chevron-down"></i></a>
                        <div id="sub-<?= $index; ?>" class="collapse" aria-labelledby="headingOne"
                             data-parent="#sub-menu-collapse">
                            <ul class="sub-category">
                                <?php foreach ($childs as $child): ?>
                                    <li>
                                        <?= Html::a($child['category_name'], $url($child['category_id']), []); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="filter-box category">
        <div class="title"><u><?= Yii::t('frontend', 'Filter'); ?></u></div>
        <?php foreach ($filters as $item) {
            if ($portal === 'amazon-jp') {
                $portal = 'amazon';
            }
            echo $this->render("filter/{$portal}", ['filter' => $item]);
        } ?>
    </div>
</div>

