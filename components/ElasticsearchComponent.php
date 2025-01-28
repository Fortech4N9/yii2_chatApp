<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\elasticsearch\Command;

class ElasticsearchComponent extends Component
{
    public function init()
    {
        parent::init();

        // Проверка существования индекса
        $index = 'user';
        $response = Yii::$app->elasticsearch->createCommand()->indexExists($index);

        if (!$response) {
            // Создание индекса
            $params = [
                'index' => $index,
                'body' => [
                    'mappings' => [
                        '_doc' => [
                            'properties' => [
                                'username' => ['type' => 'text'],
                                'email' => ['type' => 'text'],
                            ],
                        ],
                    ],
                ],
            ];

            Yii::$app->elasticsearch->createCommand()->createIndex($params);
        }
    }
}