<?php
namespace infoweb\pages\components;

use Yii;
use yii\helpers\ArrayHelper;
use infoweb\pages\models\Page as PageModel;
use infoweb\menu\models\MenuItem;
use infoweb\alias\models\AliasLang;

class Page extends \yii\base\Component
{
    /**
     * @var infoweb\pages\models\Page
     */
    public $model = null;

    /**
     * The menu items that are (in)directly linked to the page and have to be
     * marked as 'active' in the navigatio widget(s).
     * @var infoweb\menu\models\MenuItem[]
     */
    public $linkedMenuItems = [];

    /**
     * The id's of the menu items that are (in)directly linked to the page and have to be
     * marked as 'active' in the navigatio widget(s).
     * @var infoweb\menu\models\MenuItem[]
     */
    public $linkedMenuItemsIds = [];

    /**
     * The url of the error page to wich the component redirects in case of a
     * request for a non-existing page
     * @var string
     */
    public $errorPageUrl = '@web/404';

    /**
     * @var array  The entity that is attached to the page
     */
    protected $entity = null;

    public function init()
    {
        // Try to load the Page model based on the request
        $page = $this->findRequestedPage();

        // No page found, redirect to error page
        if ($page === false) {
           Yii::$app->response->redirect($this->errorPageUrl)->send();
           exit;
        } else {
            $this->setModel($page);
        }

        parent::init();
    }

    /**
     * Returns a page, based on the alias that is provided in the request
     *
     * @return  infoweb\pages\models\Page | null
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
                return false;
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
            return false;
        }

        // Set the page language
        $page->language = Yii::$app->language;

        return $page;
    }

    /**
     * Sets the page model.
     * If not null, it also loads all the linked menu-items.
     *
     * @param   infoweb\pages\models\Page | null
     */
    public function setModel($value = null)
    {
        $this->model = $value;

        // Set the entity and (re)load the linked menu items each time the model is set
        if ($this->model !== null) {
            $this->entity = [
                'id'    => $value->id,
                'type'  => MenuItem::ENTITY_PAGE
            ];
            $this->loadLinkedMenuItems();
        }
    }

    /**
     * Sets the page entity.
     * If not null, it also loads all the linked menu-items.
     *
     * @param   array | null
     */
    public function setEntity($value = null)
    {
        $this->entity = $value;

        // (Re)load the linked menu items each time the entity is set
        if ($this->entity !== null)
            $this->loadLinkedMenuItems();
    }

    /**
     * Loads all the menu items that are (in)directly linked to the page
     */
    protected function loadLinkedMenuItems()
    {
        // Load the menu-items to which the page is directly linked
        $menuItems = MenuItem::findAll([
            'entity'    => $this->entity['type'],
            'entity_id' => $this->entity['id'],
            'active'    => 1,
        ]);

        // Merge directly linked menu items
        $this->linkedMenuItems = ArrayHelper::merge(
            $this->linkedMenuItems,
            ArrayHelper::index($menuItems, 'id')
        );

        // Add all parents of the directly linked items
        foreach ($menuItems as $menuItem) {
            $this->linkedMenuItems = ArrayHelper::merge(
                $this->linkedMenuItems,
                ArrayHelper::index($menuItem->getParents(), 'id')
            );
        }

        $this->linkedMenuItemsIds = $this->linkedMenuItemsIds();

        // Reverse the sorting order of the menu items
        $this->linkedMenuItems = array_reverse($this->linkedMenuItems);
    }

    protected function linkedMenuItemsIds()
    {
        return array_keys($this->linkedMenuItems);
    }
}