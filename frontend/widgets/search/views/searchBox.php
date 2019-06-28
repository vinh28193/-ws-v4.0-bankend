<?php

/* @var $this yii\web\View */
/* @var string $keyword */
/* @var string $placeholder */
?>

<div class="search-box">
    <div class="form-group">
        <div class="input-group">
            <input type="text" name="searchBoxInput" class="form-control searchBoxInput" value="<?=$keyword;?>" placeholder="<?=$placeholder?>"/>
            <span class="input-group-btn">
                <button type="button" id="searchBoxButton" class="btn btn-default"><i class="la la-search"></i></button>
            </span>
        </div>
    </div>
    <span class="search-tag"><i class="search-icon"></i></span>
</div>
