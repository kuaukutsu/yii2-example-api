<?php

/**
 * @var $scenario \Codeception\Scenario
 */

use Codeception\Util\HttpCode;
use Codeception\Util\Fixtures;
use rest\tests\ApiTester;

$I = new ApiTester($scenario);
$I->wantTo('GET /v1/auth');

$login = Fixtures::get('login');

// получаем HELP без пароля
$I->amKeyAuthenticated();
$I->sendGET(Fixtures::get('auth') . '/help');
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseSuccess();
