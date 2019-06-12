<?php

/**
 *
 * đặt trước thẻ div có class là container
 * @var $portal string
 * @var $params array
 */
?>

<nav aria-label="breadcrumb" class="breadcrumb-content">
    <div class="container">
        <ol class="breadcrumb">
            <?php foreach($params as $key => $param){?>
                <li class="breadcrumb-item"><a href="<?= $param ?>"><?= $key ?> </a></li>
            <?php } ?>
        </ol>
    </div>
</nav>
