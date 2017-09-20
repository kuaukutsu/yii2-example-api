<?php

namespace rest\components;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Class ActiveController
 * @package rest\components
 */
class ActiveController extends \yii\rest\ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = '';

    /**
     * @var string
     */
    public $modelClassSearch = '';

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->user->enableSession = false;

        // name model
        if ($this->modelName === null && preg_match('#[\\\w]+(\w+)Controller#', get_called_class(), $match)) {
            $this->modelName = $match[1];
        }

        // namespace
        if (empty($this->modelClass)) {
            $this->modelClass = 'rest\\api\\' . $this->module->id . '\\models\\' . $this->modelName;
        }

        if (empty($this->modelClassSearch)) {
            $this->modelClassSearch = $this->modelClass . 'Search';
        }
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    /**
     * @param int $id
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function getModel($id)
    {
        $model = call_user_func_array([$this->modelClass, 'findOne'], [$id]);
        if ($model === null) {
            throw new NotFoundHttpException(sprintf('%s#%d not found', $this->modelName, $id));
        }

        return $model;
    }

    /**
     * @param $id
     * @param array $fields
     * @param array $expand
     * @return array
     */
    protected function getModelArray($id, array $fields = [], array $expand = [])
    {
        return $this->getModel($id)->toArray($fields, $expand);
    }

    /**
     * @param array $params
     * @param array $config
     * @return \yii\data\ActiveDataProvider
     */
    protected function getModelSearch(array $params, array $config = [])
    {
        $model = new $this->modelClassSearch($config);
        if ($model instanceof Model) {
            return call_user_func_array([$model, 'search'], [$params]);
        }

        throw new \BadMethodCallException();
    }

    /**
     * @param array $require
     * @param array $auth
     * @param array $optional
     * @return array
     */
    protected static function getAuthMethods(array $require = [], array $auth = [], array $optional = [])
    {
        return [
            'class' => AuthComposite::className(),
            'authRequire' => $require,
            'authMethods' => $auth,
            'optional' => $optional
        ];
    }
}