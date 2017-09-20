<?php

namespace rest\api\ajax\components;

use yii\filters\auth\AuthMethod;

/**
 * Class AuthToken
 * @package rest\api\v1\components
 */
class AuthToken extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'token';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Token\s+(.*?)$/', $authHeader, $matches)) {
            $identity = $user->loginByAccessToken($matches[1], get_class($this));
            if ($identity) {
                return $identity;
            }
        }

        $accessToken = $request->get($this->tokenParam, $request->post($this->tokenParam));
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken);
            if ($identity === null) {
                $this->handleFailure($response);
            }

            return $identity;
        }

        return null;
    }


}
