<?php

namespace api\tests\unit\fixtures;

use yii\test\ActiveFixture;

class PostFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Post';
    public $dataFile = '@tests/unit/fixtures/data/post.php';
}
