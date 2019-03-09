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
        $I->haveHttpHeader('X-Access-Token','c2c41e1d66e70e3b671182a0fc6eca56');
        $I->sendGET('/1/post');
        $I->seeResponseCodeIs(200);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        //$I->seeResponseContains('{ "status": 1, "data": [ { "id": "1", "name": "John ","email": "john@gmail.com"');
    }

    public function testGetOne(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','c2c41e1d66e70e3b671182a0fc6eca56');
        $I->sendGET('/1/post/1');
        $I->seeResponseCodeIs(404);
        //$I->seeResponseCodeIsSuccessful();
        //$I->seeResponseIsJson();
        //$I->seeResponseContainsJson(['title' => 'First Post']);
    }

    public function testGetNotFound(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','c2c41e1d66e70e3b671182a0fc6eca56');
        $I->sendGET('/1/post/100');
        $I->seeResponseCodeIs(404);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['name' => 'Not Found']);
    }

    public function testCreate(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','c2c41e1d66e70e3b671182a0fc6eca56');
        $I->sendPOST('/1/post', [
            'title' => 'Test Title',
            'text' => 'Test Text',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'Test Title']);
    }

    public function testUpdate(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','c2c41e1d66e70e3b671182a0fc6eca56');
        $I->sendPUT('/1/post/2', [
            'title' => 'New Title',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'title' => 'New Title',
            'text' => 'Old Text For Updating',
        ]);
    }

    public function testDelete(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','c2c41e1d66e70e3b671182a0fc6eca56');
        $I->sendDELETE('/1/post/3');
        $I->seeResponseCodeIs(204);
    }
}