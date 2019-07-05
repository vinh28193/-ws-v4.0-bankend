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
$js = <<<JS
    $('.dropdown-collapse').click(function() {
      if(!$(this).hasClass('collapsed')){
          $('.dropdown-collapse').find('i').removeClass('la-chevron-down');
          $('.dropdown-collapse').find('i').addClass('la-chevron-right');
      }else {
          $('.dropdown-collapse').find('i').removeClass('la-chevron-down');
          $('.dropdown-collapse').find('i').addClass('la-chevron-right');
          $(this).find('i').removeClass('la-chevron-right');
          $(this).find('i').addClass('la-chevron-down');
      }
    });
JS;
$this->registerJs($js);
?>
<div class="filter-content mobile-hide">
    <div class="filter-box category">
        <div class="title"><?= Yii::t('frontend', 'Category'); ?></div>
        <ul id="sub-menu-collapse">
            <?php foreach ($categories as $index => $category): ?>
                <?php /* @var $category array */ ?>
                <li class="accordion">
                    <?= Html::a(Yii::t('frontend',$category['category_name']), $url($category['category_id']), ['onclick' => "ws.loading(true);"]); ?>
                    <?php if (isset($category['child_category']) && ($childs = $category['child_category']) !== null && count($childs) > 0): ?>
                        <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-<?= $index; ?>"
                           aria-expanded="true" aria-controls="collapseOne"><i class="la la-chevron-right"></i></a>
                        <div id="sub-<?= $index; ?>" class="collapse" aria-labelledby="headingOne"
                             data-parent="#sub-menu-collapse">
                            <ul class="sub-category">
                                <?php foreach ($childs as $child): ?>
                                    <li>
                                        <?= Html::a(Yii::t('frontend',$child['category_name']), $url($child['category_id']), []); ?>
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

