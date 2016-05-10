<?php

namespace infoweb\pages;

use infoweb\menu\models\MenuItem;
use infoweb\pages\models\Page;
use Yii;
use yii\base\Event;
use yii\db\ActiveRecord;

class Module extends \yii\base\Module
{
    /**
     * Enable link between a page and a slider from the 'sliders' module
     * @var boolean
     */
    public $enableSliders = false;

    /**
     * Enable the possibility to toggle the public visibility of pages
     * @var boolean
     */
    public $enablePrivatePages = false;

    /**
     * The default value for the public visibility of a page
     * @var boolean
     */
    public $defaultPublicVisibility = true;

    /**
     * Allow content duplication with the "duplicateable" plugin
     * @var boolean
     */
    public $allowContentDuplication = true;

    /**
     * Module specific configuration of the ckEditor
     * @var array
     */
    public $ckEditorOptions = [
        'height' => 500,
    ];

    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php'));

        // Set eventhandlers
        $this->setEventHandlers();

        // Content duplication is only possible if there is more than 1 app language
        if (isset(Yii::$app->params['languages']) && count(Yii::$app->params['languages']) == 1) {
            $this->allowContentDuplication = false;
        }
    }

    public function setEventHandlers()
    {
        /**
         * Update menuitem active state
         */
        Event::on(Page::className(), Page::EVENT_BEFORE_ACTIVE, function ($event) {
            $menuItem = MenuItem::find()->where(['entity' => Page::className(), 'entity_id' => $event->sender->id])->one();

            if ($menuItem) {
                $menuItem->active = ($event->sender->active == 1) ? 0 : 1;
                $menuItem->save();
            }
        });

        // Set eventhandlers for the 'Menu' model
        Event::on(Page::className(), ActiveRecord::EVENT_BEFORE_DELETE, function ($event) {

            $menuItem = MenuItem::find()->where(['entity' => Page::className(), 'entity_id' => $event->sender->id])->one();

            if ($menuItem) {
                throw new \yii\base\Exception(Yii::t('app', "Deze pagina is gekoppeld aan menu item '{$menuItem->name}', verwijder eerst het menu item"));
            }
        });

    }

}