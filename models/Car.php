<?php

namespace infoweb\pages\models;

use Yii;

/**
 * This is the model class for table "car".
 *
 * @property string $entity
 * @property string $entity_id
 * @property string $language
 * @property string $url
 * @property string $created_at
 */
class Car extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'entity_id', 'language', 'url', 'created_at'], 'required'],
            [['entity_id', 'created_at'], 'integer'],
            [['entity', 'url'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity' => 'Entity',
            'entity_id' => 'Entity ID',
            'language' => 'Language',
            'url' => 'Url',
            'created_at' => 'Created At',
        ];
    }
}
