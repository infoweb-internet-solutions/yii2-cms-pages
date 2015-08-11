<?php
namespace infoweb\pages\components;

use Yii;
use infoweb\pages\models\Page as PageModel;
use infoweb\alias\models\AliasLang;

class Page extends \yii\base\Component
{    
    /**
     * @var infoweb\pages\models\Page
     */
    public $model = null;
    
    public function init()
    {
        // Try to load the Page model based on the request
        $this->model = $this->findRequestedPage();
        
        parent::init();    
    }
    
    /**
     * Returns a page, based on the alias that is provided in the request or, if
     * no alias is provided, the homepage
     * 
     * @return  Page
     */
    public function findRequestedPage()
    {
        // An alias is provided
        if (Yii::$app->request->get('alias')) {

            // Load the alias translation
            $aliasLang = AliasLang::findOne([
                'url'       => Yii::$app->request->get('alias'),
                'language'  => Yii::$app->language
            ]);

            if (!$aliasLang) {
                throw new \yii\web\HttpException(404, Yii::t('app', 'The requested page could not be found.'));
            }

            // Get the alias
            $alias = $aliasLang->alias;

            // Get the page
            $page = $alias->entityModel;

        // No specific page is requested, load the homepage
        } elseif (empty(Yii::$app->request->pathInfo)) {
            // Load the page that is marked as the 'homepage'
            $page = PageModel::findOne(['homepage' => 1]);
        // A custom page is requested and this has to be set in the controller action    
        } else {
            return null;
        }

        // The page must be active
        if ($page->active != 1) {
            throw new \yii\web\HttpException(404, Yii::t('app', 'The requested page could not be found.'));
        }

        // Set the page language
        $page->language = Yii::$app->language;
        
        return $page;
    }   
}