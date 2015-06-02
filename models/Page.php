<?php

namespace infoweb\pages\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use dosamigos\translateable\TranslateableBehavior;
use infoweb\seo\models\Seo;
use infoweb\alias\models\Alias;
use infoweb\pages\models\PageTemplate;
use infoweb\pages\behaviors\HomepageBehavior;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $template
 * @property integer $active
 * @property string $created_at
 * @property string $time_updated
 *
 * @property PagesLang[] $pagesLangs
 */
class Page extends \yii\db\ActiveRecord
{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER_DEFINED = 'user-defined';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'trans' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'title',
                    'content',
                    'snippet'
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ],
            'homepage'  => [
                'class' => HomepageBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'homepage',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'homepage',
                ],
            ],
            'image' => [
                'class' => 'infoweb\cms\behaviors\ImageBehave',
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id'], 'required'],
            [['active', 'template_id', 'created_at', 'updated_at', 'slider_id'], 'integer'],
            // Types
            [['type'], 'string'],
            ['type', 'in', 'range' => ['system', 'user-defined']],
            // Default type to 'user-defined'
            ['type', 'default', 'value' => 'user-defined'],
            [['homepage', 'slider_id'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'template_id' => Yii::t('app', 'Template'),
            'homepage' => Yii::t('infoweb/pages', 'Homepage'),
            'active' => Yii::t('app', 'Active'),
            'slider_id' => Yii::t('infoweb/sliders', 'Slider')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PageLang::className(), ['page_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(PageTemplate::className(), ['id' => 'template_id'])->where(['active' => 1]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::className(), ['entity_id' => 'id'])->where(['entity' => Seo::TYPE_PAGE]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlias()
    {
        return $this->hasOne(Alias::className(), ['entity_id' => 'id'])->where(['entity' => Alias::ENTITY_PAGE]);
    }
    
    /**
     * Returns the layout model for the page
     * 
     * @return  frontend\models\layout\Layout
     */
    public function getLayout()
    {
        return Yii::createObject(['class' => "frontend\models\layout\\{$this->template->layout_model}"]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlider()
    {
        if (Yii::$app->getModule('pages')->enableSliders) {
            return $this->hasOne(\infoweb\sliders\models\Slider::className(), ['slider_id' => 'id']);
        } else {
            return null;
        }
    }

    /**
     * Deletes the attached entities
     * 
     * @throws  \yii\base\Exception
     * @return  boolean
     */
    public function deleteAttachedEntities()
    {
        // Try to load and delete the attached 'Alias' entity
        if (!$this->alias->delete())
            throw new \yii\base\Exception(Yii::t('infoweb/pages', 'Error while deleting the attached alias'));
        
        // Try to load and delete the attached 'Seo' entity
        if (!$this->seo->delete())
            throw new \yii\base\Exception(Yii::t('infoweb/pages', 'Error while deleting the attached seo tag'));        
        
        return true;
    }
    
    /**
     * Checks if a page is used in a menu
     * 
     * @return  boolean
     */
    public function isUsedInMenu()
    {
        return (new \yii\db\Query)
                    ->select('id')
                    ->from(\infoweb\menu\models\MenuItem::tableName())
                    ->where([
                        'entity'    => \infoweb\menu\models\MenuItem::ENTITY_PAGE,
                        'entity_id' => $this->id
                    ])
                    ->exists();    
    }
    
    /**
     * Returns the html anchors that are used in the content of a page
     * 
     * @return  array
     */
    public function getHtmlAnchors()
    {
        // Parse the anchors out of the page content
        // They should be found in the format '<a id="" name=""></a>'
        $anchors = [];
        $content = $this->content;
        preg_match_all('/<a id="([^\"]+)" name="([^\"]+)">[^<]*<\/a>/', $content, $matches);
   
        // The 'id' tag is used as key and the 'name' tag as value of the anchors array 
        if (isset($matches[1]) && isset($matches[2])) {
            $anchors = array_combine($matches[1], $matches[2]);
        }
        
        return $anchors;        
    }
    
    /**
     * Returns the url of the page
     * 
     * @param   string  $includeLanguage
     * @return  string  $url
     */
    public function getUrl($includeLanguage = true)
    {
        $url = Yii::getAlias('@baseUrl') . '/';
        if ($includeLanguage)
            $url .= (($this->language == null) ? Yii::$app->language : $this->language) . '/';
        
        $url .= $this->alias->url;
        
        return $url;
    }
}
