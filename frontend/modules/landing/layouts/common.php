<?php

use frontend\widgets\layout\FooterWidget;
use yii\helpers\Html;
use yii\helpers\Url;

use landing\LandingAsset;

/* @var $this yii\web\View */
/* @var $content string */
LandingAsset::register($this);
$this->beginPage();
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="drawer drawer--left" style="background:#fff;">
<?php $this->beginBody() ?>
<?= $content; ?>
<?php $this->endBody() ?>
<script type="text/javascript">
    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > $('#header').height()) {
                $('#header .ws-fixed-nav').css('top', 0);
            } else {
                $('#header .ws-fixed-nav').css('top', '-150px');
            }
        });

        $('#lsp-table-full').hide();
        $("#lsp-cal").click(function () {
            $("#lsp-table-full").slideToggle();
        });
    });
</script>

<script type="text/javascript">
    $('.price-request2').click(function () {
        $(this).addClass('close');
        $('.price-now').css('right', '0')
    })
    $(document).mouseup(function (e) {
        var container = $(".price-now");

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            $('.price-request2').removeClass('close');
            $('.price-now').css('right', '-300px')
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#LDSearch').on('keyup', function (e) {
            e.preventDefault();
            var $input = $(this);
            var helpText = $('#textsearch');
            if($input.val() !== ''){
                helpText.css('display','none');
            }else{
                helpText.css('display','block');
            }
            if(e.keyCode === 13){
                ws.searchNew($input[0],null);
            }
            return false;
        })
    })
</script>
</body>
</html>
<?php $this->endPage() ?>

