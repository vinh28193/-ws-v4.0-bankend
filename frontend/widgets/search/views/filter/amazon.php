<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $filter array */
?>

<div class="filter-box">
    <div class="filter-box">
        <div onclick="ws.showFilter('<?= md5($filter['name']) ?>')" style="cursor: pointer">
            <h6>
                <?= Html::tag('b', $filter['name'], []); ?>
                <span><i class="fa fa-chevron-up" id="ico-<?= md5($filter['name']) ?>"></i></span>
            </h6>
        </div>
        <ul style="display: block" id="<?= md5($filter['name']) ?>">
            <?php foreach ($filter['values'] as $key => $item) { ?>
                <?php /* @var $value string */ ?>
                <?php $value = Html::encode($item['param']); ?>
                <li>
                    <div class="form-check">
                        <input class="form-check-input" name="filter"
                               type="checkbox"
                               value="<?= $value ?>"
                               id="<?= md5($item['value'].'-'.$filter['name']) ?>"
                               data-for="<?= $filter['name'] ?>"
                               data-value="<?= $value ?>">
                        <label class="form-check-label" for="<?= md5($item['value'].'-'.$filter['name']) ?>">
                            <?php if (strpos(strtolower($filter['name']), 'customer review')) { ?>
                                <i class="a-icon a-icon-star a-star-<?= 5 - $key - 1 ?> review-rating">
                                </i>
                            <?php } ?>
                            <?= $item['value'] ?>
                        </label>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
