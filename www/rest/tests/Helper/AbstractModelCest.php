<?php

namespace rest\tests\Helper;

use Codeception\Util\HttpCode;
use Codeception\Util\Fixtures;
use rest\tests\ApiTester;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class AbstractModelCest
 * @package rest\tests\Helper
 */
abstract class AbstractModelCest
{
    /**
     * @var bool
     */
    public $auth = false;

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var array
     */
    public $structure = [];

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $version;

    /**
     * AbstractModelCest constructor.
     */
    public function __construct()
    {
        if (preg_match('#(?<model>\w{1,})(?<version>(V\d{1,}|Ajax|graphql))Cest#i',
            StringHelper::basename(get_called_class()), $match))
        {
            $this->version = strtolower(ArrayHelper::getValue($match, 'version'));
            $this->modelName = ArrayHelper::getValue($match, 'model');
        }

        // base
        if (empty($this->baseUrl)) {
            $this->baseUrl = strtolower(sprintf('/%s/%s', $this->version, $this->modelName));
        }

        // structure
        if ($this->structure === []) {
            $className = sprintf('\\rest\\api\\%s\\models\\%s', $this->version, $this->modelName);
            if (class_exists($className) && method_exists($className, 'fieldTest')) {
                $this->structure = call_user_func([$className, 'fieldTest']);
            }
        }

        $this->_init();
    }

    /**
     * Initialization
     */
    public function _init()
    {

    }

    /*******************
     * TESTS
     ******************/

    /**
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        // APIKey
        if (!$this->isAjax()) {
            $I->amKeyAuthenticated();
        }

        // Get token
        if ($this->auth) {
            if ($this->token === null) {
                $login = Fixtures::get('login');
                $this->doHttpAuthenticated($I, $login['name'], $login['pass']);
            }

            // token required
            if (empty($this->token)) {
                throw new \PHPUnit_Framework_IncompleteTestError('Token is empty');
            }
        }
    }

    /**
     * @param ApiTester $I
     */
    public function _after(ApiTester $I)
    {

    }

    /**
     * GET index
     * @param ApiTester $I
     * @param array $params
     */
    public function tryIndexTest(ApiTester $I, array $params = [])
    {
        $this->trySuccesIsJsonTest($I, '', $params);
        // проверка структуры ответа
        $I->seeResponseMatchesJsonType($this->structure, '$.data[0]');
        // выбираем модель
        $this->setModel($I->grabDataFromResponseByJsonPath('$.data[0]')[0]);
    }

    /**
     * GET view
     * @param ApiTester $I
     * @param array $params
     */
    public function tryViewTest(ApiTester $I, array $params = [])
    {
        if (($id = $this->getAttribute('id')) === null) {
            throw new \PHPUnit_Framework_Exception($this->modelName . ' is empty');
        }

        $this->trySuccesIsJsonTest($I, $id, $params);
        // проверка структуры ответа
        $I->seeResponseMatchesJsonType($this->structure, '$.data');
    }

    /**
     * @param ApiTester $I
     * @param string $url
     * @param array $params
     * @param string $method
     */
    protected function trySuccesIsJsonTest(ApiTester $I, $url, array $params = [], $method = 'GET')
    {
        // build
        $url = $this->buildUrl($url);

        $I->wantTo(sprintf('%s %s', $method, $url));

        // тест с авторизацией
        if ($this->auth) {
            $this->doAuthorization($I, $url, $params, $method);
            if ($this->isAjax()) {
                $params['token'] = $this->token;
            }
        }

        // тест без авторизации
        call_user_func_array([$I, 'send' . strtoupper($method)], [$url, $params]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseSuccess();
    }

    /**
     * @param ApiTester $I
     * @param string $url
     * @param array $params
     * @param string $method
     */
    protected function tryDontSuccesIsJsonTest(ApiTester $I, $url, array $params = [], $method = 'GET')
    {
        // build
        $url = $this->buildUrl($url);

        $I->wantTo(sprintf('%s %s (error)', $method, $url));

        // тест с авторизацией
        if ($this->auth) {
            $this->doAuthorization($I, $url, $params, $method);
            if ($this->isAjax()) {
                $params['token'] = $this->token;
            }
        }

        // тест без авторизации
        call_user_func_array([$I, 'send' . strtoupper($method)], [$url, $params]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->dontSeeResponseSuccess();
    }

    /**
     * @param ApiTester $I
     * @param string $url
     * @param array $params
     * @param string $method
     * @param int $code
     */
    protected function tryErrorCodeTest(ApiTester $I, $url, array $params = [], $method = 'GET', $code = HttpCode::NOT_FOUND)
    {
        // build
        $url = $this->buildUrl($url);

        $I->wantTo(sprintf('%s %s (not found)', $method, $url));

        // тест с авторизацией
        if ($this->auth) {
            $this->doAuthorization($I, $url, $params, $method);
            if ($this->isAjax()) {
                $params['token'] = $this->token;
            }
        }

        // тест без авторизации
        call_user_func_array([$I, 'send' . strtoupper($method)], [$url, $params]);
        $I->seeResponseCodeIs($code);
        $I->seeResponseIsJson();
        $I->dontSeeResponseSuccess();
    }

    /************************
     * HELPERS
     ***********************/

    /**
     * @var array
     */
    private $_model = [];

    /**
     * @param array $value
     */
    protected function setModel(array $value)
    {
        $this->_model = $value;
    }

    /**
     * @return array
     */
    protected function getModel()
    {
        return $this->_model;
    }

    /**
     * @return bool
     */
    protected function isAjax()
    {
        return $this->version === 'ajax';
    }

    /**
     * @param string $attribute
     * @return mixed|null
     */
    protected function getAttribute($attribute = 'id')
    {
        return array_key_exists($attribute, $this->_model) ? $this->_model[$attribute] : null;
    }

    /**
     * @param ApiTester $I
     * @param string $url
     * @param array $params
     * @param string $method
     */
    protected function doAuthorization(ApiTester $I, $url, array $params = [], $method = 'GET')
    {
        // без token должна быть ошибка
        call_user_func_array([$I, 'send' . strtoupper($method)], [$url, $params]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
        $I->dontSeeResponseSuccess();

        // отправляем token
        if (! $this->isAjax()) {
            $I->amHttpBearerAuthenticated($this->token);
        }
    }

    /**
     * @param ApiTester $I
     * @param $login
     * @param $pass
     */
    protected function doHttpAuthenticated(ApiTester $I, $login, $pass)
    {
        // тестовый пользователь
        $I->wantTo(sprintf('%s %s', 'GET', Fixtures::get('auth')));

        $I->amKeyAuthenticated();

        // test bad pass login
        $I->amHttpCustomAuthenticated($login, '123efwr456');
        $I->sendGET(Fixtures::get('auth'));
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);

        // test pass login
        $I->amHttpCustomAuthenticated($login, $pass);
        $I->sendGET(Fixtures::get('auth'));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseSuccess();

        // token
        $this->token = $I->grabDataFromResponseByJsonPath('$.data.token')[0];
    }

    /**
     * @param string $url
     * @return string
     */
    private function buildUrl($url)
    {
        if ($url === '') {
            return $this->baseUrl;
        }

        return sprintf('%s/%s',
            $this->baseUrl, ltrim(str_replace($this->version . '/', '', $url), '/'));
    }
}