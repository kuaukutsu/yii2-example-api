<?php

namespace rest\api\graphql\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use GraphQL\Error\Error;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use rest\api\graphql\components\FormattedError;
use rest\api\graphql\components\Types;

/**
 * Class DefaultController
 * @package rest\api\graph\controllers
 */
class DefaultController extends AbstractActiveController
{
    /**
     * @var string
     */
    public $modelClass = '';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // AccessControl
        $behaviors['access'] = [
            'class' => 'yii\filters\AccessControl',
            'rules' => [
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'verbs' => ['GET', 'POST']
                ],
            ],
        ];

        // authMethods
        $behaviors['authenticator'] = self::getAuthMethods([], [
            [
                'class' => HttpBearerAuth::className(),
            ]
        ], ['index', 'help']);

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        $request = Yii::$app->getRequest();

        $query = $request->get('query', $request->post('query'));
        $variables = $request->get('variables', $request->post('variables', []));
        $operation = $request->get('operation', $request->post('operation', null));

        if (empty($query) && $rawInput = file_get_contents('php://input')) {
            $input = json_decode($rawInput, true);
            $query = ArrayHelper::getValue($input, 'query');
            $variables = ArrayHelper::getValue($input, 'variables', []);
            $operation = ArrayHelper::getValue($input, 'operation');
        }

        $schema = new Schema([
            'query' => Types::query(),
            'mutation' => Types::mutation()
        ]);

        try {

            $result = GraphQL::executeQuery(
                $schema,
                $query,
                null,
                null,
                empty($variables) ? null : Json::decode($variables),
                empty($operation) ? null : $operation
            )
                ->setErrorFormatter(function(Error $error) {
                    return FormattedError::createFromException($error);
                })
                ->toArray();

        } catch (\Exception $exception) {
            $result['errors'] = $exception->getMessage();
        }

        return $result;
    }
}