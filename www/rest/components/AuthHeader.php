<?php

namespace rest\components;

use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;

/**
 * Class AuthHeader
 * @package rest\api\v1\components
 */
class AuthHeader extends AuthMethod
{
    /**
     * @var string
     */
    public $header = 'edededed';

    /**
     * @var string
     */
    public $apikey = 'edededed';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        if (! $request->getHeaders()->has($this->header)) {
            throw new UnauthorizedHttpException('Your request was made with invalid headers.');
        }

        // access
        if ($request->getHeaders()->get($this->header) !== $this->apikey) {
            $this->handleFailure($response);
            return null;
        }

        return true;
    }
}
