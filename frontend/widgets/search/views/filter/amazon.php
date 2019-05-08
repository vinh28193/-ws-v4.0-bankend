<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $filter array */
?>

<div class="filter-box">
    <div class="filter-box">
        <?= Html::tag('div', $filter['name'], ['class' => 'title']); ?>
        <ul>
            <?php foreach ($filter['values'] as $item): ?>
                <?php /* @var $value string */ ?>
                <?php $value = Html::encode($item['param']); ?>
                <li>
                    <div class="form-check">
                        <?php
                        $value = $filter['name'] . $value;
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
