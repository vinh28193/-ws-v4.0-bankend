<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $filter array */
?>

<div class="filter-box">
    <div class="filter-box">
        <div onclick="showFilter('<?= md5($filter['name']) ?>')" style="cursor: pointer">
            <h6>
                <?= Html::tag('b', $filter['name'], []); ?>
                <span><i class="fa fa-chevron-up" id="ico-<?= md5($filter['name']) ?>"></i></span>
            </h6>
        </div>
        <ul style="display: block" id="<?= md5($filter['name']) ?>">
            <?php foreach ($filter['values'] as $item): ?>
                <?php /* @var $value string */ ?>
                <?php $value = Html::encode($item['param']); ?>
                <li>
                    <div class="form-check">
                        <?php
//                        $value = $filter['name'] . $value;
                        ?>
                        <?= Html::checkbox('filter', $item['is_selected'], [
                            'class' => 'form-check-input',
                            'value' => $value,
                            'id' => $item['value'],
                            'data-for' => $filter['name'],
                            'data-value' => $value
                        ]); ?>
                        <?= Html::label($item['value'], $item['value'], [
                            'class' => 'form-check-label',
                        ]); ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
