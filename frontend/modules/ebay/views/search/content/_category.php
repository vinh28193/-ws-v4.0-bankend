<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $categories array */

// Todo UrlRule
// Hãy tham khảo mục filter để làm phần này
// push start
$url = function ($id) {
    return Yii::$app->getUrlManager()->createUrl([
        'ebay/search',
        'category' => $id
    ]);
}
?>

<div class="filter-box category">
    <div class="title">Danh mục</div>
    <ul id="sub-menu-collapse">
        <?php foreach ($categories as $index => $category): ?>
            <?php /* @var $category array */ ?>
            <li class="accordion">
                <?= Html::a($category['category_name'], $url($category['category_id']), []); ?>
                <?php if (isset($category['child_category']) && ($childs = $category['child_category']) !== null && count($childs) > 0): ?>
                    <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-<?= $index; ?>"
                       aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-down"></i></a>
                    <div id="sub-<?= $index; ?>" class="collapse" aria-labelledby="headingOne"
                         data-parent="#sub-menu-collapse">
                        <ul>
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
