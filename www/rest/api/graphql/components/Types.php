<?php

namespace rest\api\graphql\components;

use yii\base\Model;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;
use rest\api\graphql\mutations\UserMutationType;
use rest\api\graphql\types\IdeaType;
use rest\api\graphql\types\UserType;

/**
 * Class Types
 * @package rest\api\graphql\types
 */
class Types
{
    /********************
     * BASE
     *******************/

    /**
     * @var QueryType
     */
    private static $query;

    /**
     * @return QueryType
     */
    public static function query()
    {
        return self::$query ?: (self::$query = new QueryType());
    }

    /**
     * @var MutationType
     */
    private static $mutation;

    /**
     * @return MutationType
     */
    public static function mutation()
    {
        return self::$mutation ?: (self::$mutation = new MutationType());
    }

    /**
     * @var ErrorType
     */
    private static $error;

    /**
     * @return ErrorType
     */
    public static function error()
    {
        return self::$error ?: (self::$error = new ErrorType());
    }

    /**
     * @var ValidationType
     */
    private static $validation;

    /**
     * @return ValidationType
     */
    public static function validation()
    {
        return self::$validation ?: (self::$validation = new ValidationType());
    }

    /**
     * @var array
     */
    private static $valitationTypes;

    /**
     * @param ObjectType $type
     * @return UnionType
     */
    public static function verifyValidation(ObjectType $type)
    {
        if (!isset(self::$valitationTypes[$type->name . 'VerifyValidationType'])) {
            self::$valitationTypes[$type->name . 'VerifyValidationType'] = new UnionType([
                'name' => $type->name . 'VerifyValidationType',
                'types' => [
                    $type,
                    Types::validation(),
                ],
                'resolveType' => function ($value) use ($type) {
                    return ($value instanceof Model) ? $type : Types::validation();
                }
            ]);
        }

        return self::$valitationTypes[$type->name . 'VerifyValidationType'];
    }

    /********************
     * MODEL
     *******************/

    /**
     * @var UserType
     */
    private static $user;

    /**
     * @return UserType
     */
    public static function user()
    {
        return self::$user ?: (self::$user = new UserType());
    }

    /**
     * @var UserMutationType
     */
    private static $userMutation;

    /**
     * @return UserMutationType
     */
    public static function userMutation()
    {
        return self::$userMutation ?: (self::$userMutation = new UserMutationType());
    }

    /**
     * @var IdeaType
     */
    private static $idea;

    /**
     * @return IdeaType
     */
    public static function idea()
    {
        return self::$idea ?: (self::$idea = new IdeaType());
    }
}