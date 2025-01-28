<?php

namespace app\models;

use Yii;
use yii\elasticsearch\ActiveRecord;

/**
 * This is the model class for Elasticsearch index "user".
 */
class UserSearch extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function index()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public static function type()
    {
        return '_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return ['id', 'username', 'email'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email'], 'string'],
        ];
    }

    /**
     * Search users by query.
     *
     * @param string $query
     * @return array
     */
    public static function searchUsers($query)
    {
        $params = [
            'bool' => [
                'should' => [
                    ['wildcard' => ['username' => "*$query*"]],
                    ['wildcard' => ['email' => "*$query*"]],
                ],
            ],
        ];

        return self::find()->query($params)->all();
    }
}