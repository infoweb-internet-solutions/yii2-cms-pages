<?php

namespace infoweb\pages\models;

use Yii;
use dosamigos\translateable\TranslateableBehavior;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $template
 * @property integer $active
 * @property string $time_created
 * @property string $time_updated
 *
 * @property PagesLang[] $pagesLangs
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    public function behaviors()
    {
        return [
            'trans' => [ // name it the way you want
                'class' => TranslateableBehavior::className(),
                // in case you named your relation differently, you can setup its relation name attribute
                // 'relation' => 'translations',
                // in case you named the language column differently on your translation schema
                // 'languageField' => 'language',
                'translationAttributes' => [
                    'title', 'content'
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template'], 'required'],
            [['active'], 'integer'],
            [['time_created', 'time_updated'], 'safe'],
            [['template'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'template' => Yii::t('app', 'Template'),
            'active' => Yii::t('app', 'Active'),
            'time_created' => Yii::t('app', 'Time Created'),
            'time_updated' => Yii::t('app', 'Time Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PageLang::className(), ['page_id' => 'id']);
    }
}
