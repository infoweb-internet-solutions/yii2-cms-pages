<?php

namespace infoweb\pages\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $active
 * @property string $time_created
 * @property string $time_updated
 */
class Page extends \yii\db\ActiveRecord
{

    /**
     * Active status
     */
    const STATUS_ACTIVE = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['active'], 'integer'],
            [['time_created', 'time_updated'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'active' => Yii::t('app', 'Active'),
            'time_created' => Yii::t('app', 'Time Created'),
            'time_updated' => Yii::t('app', 'Time Updated'),
        ];
    }
}
