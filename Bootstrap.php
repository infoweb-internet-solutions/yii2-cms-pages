<?php
namespace infoweb\pages;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use infoweb\pages\models\Page;

class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        if ($app->hasModule('pages') && ($module = $app->getModule('pages')) instanceof Module) {

            // Set eventhandlers
            $this->setEventHandlers();

            // If the 'enableSliders' setting is true, the sliders module has to
            // be activated
            if ($module->enableSliders && !$app->hasModule('sliders')) {
                // Disable sliders in the module
                $module->enableSliders = false;
            }
        }
    }

    protected function setEventHandlers()
    {
        // Set eventhandlers for the 'Page' model
        Event::on(Page::className(), ActiveRecord::EVENT_BEFORE_DELETE, function ($event) {

            // Check if the page is the homepage
            if ($event->sender->homepage == 1)
                throw new \yii\base\Exception(Yii::t('infoweb/pages', 'The page can not be deleted because it is the homepage'));

            // Check if the page is not used in a menu
            if ($event->sender->isUsedInMenu())
                throw new \yii\base\Exception(Yii::t('infoweb/pages', 'The page can not be deleted because it is used in a menu'));
        });
    }
}