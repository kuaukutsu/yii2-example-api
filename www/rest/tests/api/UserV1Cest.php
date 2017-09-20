<?php

namespace rest\tests;

use rest\tests\Helper\AbstractModelCest;

/**
 * Class UserV1Cest
 * @package rest\tests
 */
class UserV1Cest extends AbstractModelCest
{
    /**
     * @inheritdoc
     */
    public function tryIndexTest(ApiTester $I, array $params = [])
    {
        throw new \Exception('Not Implemented');
    }

    /**
     * @inheritdoc
     */
    public function tryViewTest(ApiTester $I, array $params = [])
    {
        throw new \Exception('Not Implemented');
    }
}
