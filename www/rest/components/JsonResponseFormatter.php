<?php

namespace rest\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * Class JsonResponseFormatter
 * @package app\components\response
 */
class JsonResponseFormatter extends \yii\web\JsonResponseFormatter
{
    /**
     * @var bool
     */
    public $useJsonr = false;

    /**
     * @var bool
     */
    public $useJsonvp = false;

    /**
     * @param Response $response
     */
    public function format($response)
    {
        if ($this->useJsonr) {
            $this->formatJsonr($response);
        } elseif ($this->useJsonvp) {
            $this->formatJsonvp($response);
        } elseif ($this->useJsonp) {
            $this->formatJsonp($response);
        } else {
            $this->formatJson($response);
        }
    }

    /**
     * @param Response $response
     */
    protected function formatJsonr($response)
    {
        if ($response->data !== null) {
            // has error
            if (isset($response->data['errors'])) {
                $response->data = [
                    'success' => false,
                    'errors' => Response::parseErrors($response->data['errors'])
                ];
            } elseif ($response->isSuccessful === false) {
                $response->data = [
                    'success' => $response->isSuccessful,
                    'errors' => $response->data
                ];
            } else {
                $response->data = [
                    'success' => $response->isSuccessful,
                    'data' => ArrayHelper::getValue($response->data, 'data', $response->data)
                ];
            }

            // DEBUG
            $this->printDebug($response);
        }

        $response->format = $response::FORMAT_JSON;
        $this->formatJson($response);
    }

    /**
     * @param Response $response
     */
    protected function formatJsonvp($response)
    {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        if ($response->data !== null) {
            $options = $this->encodeOptions;
            if ($this->prettyPrint) {
                $options |= JSON_PRETTY_PRINT;
            }
            $response->content = ")]}',\n" . Json::encode($response->data, $options);
        }
    }

    /**
     * @param $response
     */
    protected function printDebug($response)
    {
        // DEBUG
        $request = Yii::$app->getRequest();
        $headerDebug = $request->getHeaders()->get('Debug');
        if ($headerDebug !== null) {
            foreach (explode(';', $headerDebug) as $item) {
                $item = sprintf('get%s', Inflector::id2camel(trim($item)));
                if (method_exists($request, $item)) {
                    $response->data['debug'][$item] = $request->{$item}();
                }
            }
        }
    }
}