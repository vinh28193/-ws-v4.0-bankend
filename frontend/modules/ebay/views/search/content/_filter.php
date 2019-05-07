<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $filters array */

/**
 * Todo Html::encode
 * Todo widget, click push pjax
 * Todo checkbox attribute id, label attribute for
 */
?>

<?php foreach ($filters as $filter): ?>
    <div class="filter-box">
        <?= Html::tag('div', $filter['name'], ['class' => 'title']); ?>
        <ul>
            <?php foreach ($filter['values'] as $value): ?>
                <?php /* @var $value string */ ?>
                <?php $value = Html::encode($value); ?>
                <li>
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
        </ul>
    </div>
<?php endforeach; ?>

