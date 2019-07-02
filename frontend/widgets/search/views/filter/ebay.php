<?php

use yii\helpers\Html;
use yii\web\View;

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
        <ul id="<?= md5($filter['name']) ?>">
            <?php foreach ($filter['values'] as $k => $value): ?>
                <?php /* @var $value string */ ?>
                <?php $value = Html::encode($value); ?>
                <li class="<?= $k < 10 ? '' : 'filter-'.md5($filter['name']).' hide-filter'?>">
                    <div class="form-check">
                        <?php
                        $id = $filter['name'] . $value;
                        ?>
                        <?= Html::checkbox('filter', false, [
                            'class' => 'form-check-input',
                            'value' => $value,
                            'id' => $id,
                            'data-for' => $filter['name'],
                            'data-value' => $value
                        ]); ?>
                        <?= Html::label($value, $id, [
                            'class' => 'form-check-label',
                        ]); ?>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php
                if (count($filter['values']) >= 10 ){
                    echo "<li class='type-show-filter-".md5($filter['name'])."'><a href='javascript:void(0);' style='color: #2e9ab8' onclick='ws.showMoreFilter(this)' data-target='filter-".md5($filter['name'])."' >".Yii::t('frontend','<<< See more >>>')."</a></li>";
                    echo "<li class='type-show-filter-".md5($filter['name'])." hide-filter'><a href='javascript:void(0);' style='color: #2e9ab8' onclick='ws.showMoreFilter(this)' data-target='filter-".md5($filter['name'])."' >".Yii::t('frontend','>>> See less <<<')."</a></li>";
                }
            ?>
        </ul>
    </div>
</div>

