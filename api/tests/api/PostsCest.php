<?php
namespace api\tests;

use api\tests\ApiTester;
use Codeception\Util\HttpCode;
use tests\fixtures\PostFixture;
use yii\helpers\Url;

class PostsApiCest
{
    function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'post' => [
                'class' => PostFixture::className(),
                'dataFile' => codecept_data_dir() . 'post.php'
            ]
        ]);

    }

    public function testGetAll(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','f4a71c2e6caf7e310551b41a8411f1f3');
        $I->sendGET('/1/post');
        $I->seeResponseCodeIs(200);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'First Post']);
    }

    public function testGetOne(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','f4a71c2e6caf7e310551b41a8411f1f3');
        $I->sendGET('/1/post/view/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'First Post']);
    }

    public function testGetNotFound(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','f4a71c2e6caf7e310551b41a8411f1f3');
        $I->sendGET('/1/post/view/100');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["errors"=> "Invalid Record requested"]);
    }

    public function testCreate(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','f4a71c2e6caf7e310551b41a8411f1f3');
        $I->sendPOST('/1/post/create', [
            'title' => 'Test Title',
            'text' => 'Test Text',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'Test Title']);
    }

    public function testUpdate(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','f4a71c2e6caf7e310551b41a8411f1f3');
        $I->sendPUT('/1/post/update/2', [
            'title' => 'New Title Weshop 2019',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'title' => 'New Title Weshop 2019',
            'text' => 'Old Text For Updating',
        ]);
    }

    public function testDelete(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','f4a71c2e6caf7e310551b41a8411f1f3');
        $I->sendDELETE('/1/post/delete/3');
        $I->seeResponseCodeIs(200);
    }
}