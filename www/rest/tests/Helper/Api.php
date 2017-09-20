<?php

namespace rest\tests\Helper;

use Codeception\Module;

/**
 * Class Api
 * @package rest\tests\Helper
 */
class Api extends Module
{
    /**
     * @var \Codeception\Module\REST
     */
    private $_rest;

    /**
     * @return \Codeception\Module\REST|\Codeception\Module
     */
    protected function getModuleRest()
    {
        if ($this->_rest === null) {
            $this->_rest = $this->getModule('REST');
        }

        return $this->_rest;
    }

    /**
     * Need modify
     */
    public function amKeyAuthenticated()
    {
        $this->getModuleRest()->deleteHeader('api-header');
        $this->getModuleRest()->haveHttpHeader('api-header', 'api-key');
    }

    /**
     * @param string $login
     * @param string $pass
     */
    public function amHttpCustomAuthenticated($login, $pass)
    {
        $this->getModuleRest()->deleteHeader('Authorization');
        $this->getModuleRest()->haveHttpHeader('Authorization',
            sprintf('Basic %s', base64_encode($login .':'. $pass)));
    }

    /**
     * @param string $token
     */
    public function amHttpBearerAuthenticated($token)
    {
        $this->getModuleRest()->deleteHeader('Authorization');
        $this->getModuleRest()->haveHttpHeader('Authorization', sprintf('Bearer %s', $token));
    }

    /**
     * @param string $what
     */
    public function amDebug($what = 'headers')
    {
        $this->getModuleRest()->haveHttpHeader('Debug', $what);
    }

    /**
     *
     */
    public function seeResponseSuccess()
    {
        $this->getModuleRest()->seeResponseContainsJson(['success' => true]);
    }

    /**
     *
     */
    public function dontSeeResponseSuccess()
    {
        $this->getModuleRest()->dontSeeResponseContainsJson(['success' => true]);
    }
}
