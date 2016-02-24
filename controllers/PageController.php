<?php

namespace infoweb\pages\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use infoweb\pages\models\Page;
use infoweb\pages\models\Lang;
use infoweb\pages\models\PageTemplate;
use infoweb\pages\models\PageSearch;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'active' => ['post'],
                    'homepage' => ['post'],
                    'public' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'        => $searchModel,
            'dataProvider'       => $dataProvider,
            'enablePrivatePages' => Yii::$app->getModule('pages')->enablePrivatePages
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // Create the model with default values
        $model = new Page([
            'type'        => 'user-defined',
            'active'      => 1,
            'homepage'    => 0,
            'template_id' => 1,
            'public'      => (int) $this->module->defaultPublicVisibility
        ]);

        // The view params
        $params = $this->getDefaultViewParams($model);

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate
            if (Yii::$app->request->isAjax) {

                return $this->validateModel($model, $post);

            // Normal request, save
            } else {
                return $this->saveModel($model, $post);
            }
        }

        return $this->render('create', $params);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Load the model
        $model = $this->findModel($id);

        // The view params
        $params = $this->getDefaultViewParams($model);

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate
            if (Yii::$app->request->isAjax) {

                return $this->validateModel($model, $post);

            // Normal request, save models
            } else {
                return $this->saveModel($model, $post);
            }
        }

        return $this->render('update', $params);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $name = $model->name;

        try {
            // Only Superadmin can delete system pages
            if ($model->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin'))
                throw new Exception(Yii::t('app', 'You do not have the right permissions to delete this item'));

            $transaction = Yii::$app->db->beginTransaction();

            if (!$model->delete()) {
                throw new Exception(Yii::t('app', 'Error while deleting the node'));
            }

            $transaction->commit();
        } catch (Exception $e) {
            // Set flash message
            Yii::$app->getSession()->setFlash('page-error', $e->getMessage());

            return $this->redirect(['index']);
        }

        // Set flash message
        Yii::$app->getSession()->setFlash('page', Yii::t('app', '{item} has been deleted', ['item' => $name]));

        return $this->redirect(['index']);
    }

    /**
     * Set active state
     * @param string $id
     * @return mixed
     */
    public function actionActive()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->active = ($model->active == 1) ? 0 : 1;

        return $model->save();
    }

    /**
     * Set as homepage
     * @param string $id
     * @return mixed
     */
    public function actionHomepage()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->homepage = 1;
        $model->active = 1;

        return $model->save();
    }

    /**
     * Set public state
     * @param string $id
     * @return mixed
     */
    public function actionPublic()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->public = ($model->public == 1) ? 0 : 1;

        return $model->save();
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }

    /**
     * Returns an array of the default params that are passed to a view
     *
     * @param Page $model The model that has to be passed to the view
     * @return array
     */
    protected function getDefaultViewParams($model = null)
    {
        return [
            'model'                   => $model,
            'templates'               => $this->getTemplates(),
            'sliders'                 => ($this->module->enableSliders) ? ArrayHelper::map(\infoweb\sliders\models\Slider::find()->select(['id', 'name'])->orderBy('name')->all(), 'id', 'name') : [],
            'allowContentDuplication' => $this->module->allowContentDuplication
        ];
    }

    /**
     * Returns an array of page templates
     *
     * @return PageTemplate[]
     */
    protected function getTemplates()
    {
        return PageTemplate::find()->orderBy(['name' => SORT_ASC])->all();
    }

    /**
     * Performs validation on the provided model and $_POST data
     *
     * @param \infoweb\pages\models\Page $model The page model
     * @param array $post The $_POST data
     * @return array
     */
    protected function validateModel($model, $post)
    {
        $languages = Yii::$app->params['languages'];

        // Populate the model with the POST data
        $model->load($post);

        // Create an array of translation models and populate them
        $translationModels = [];
        // Insert
        if ($model->isNewRecord) {
            foreach ($languages as $languageId => $languageName) {
                $translationModels[$languageId] = new Lang(['language' => $languageId]);
            }
        // Update
        } else {
            $translationModels = ArrayHelper::index($model->getTranslations()->all(), 'language');
        }
        Model::loadMultiple($translationModels, $post);

        // Create an array of alias models and populate them
        $aliasModels = [];
        foreach ($translationModels as $languageId => $translation) {
            $aliasModels[$languageId] = $translation->alias;
        }
        Model::loadMultiple($aliasModels, $post);

        // Validate the model and translation
        $response = array_merge(
            ActiveForm::validate($model),
            ActiveForm::validateMultiple($translationModels),
            ActiveForm::validateMultiple($aliasModels)
        );

        // Return validation in JSON format
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

    protected function saveModel($model, $post)
    {
        // Wrap everything in a database transaction
        $transaction = Yii::$app->db->beginTransaction();

        // Get the params
        $params = $this->getDefaultViewParams($model);

        // Validate the main model
        if (!$model->load($post)) {
            return $this->render($this->action->id, $params);
        }

        // Add the translations
        foreach (Yii::$app->request->post('Lang', []) as $language => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($language)->$attribute = $translation;
            }
        }

        // Save the main model
        if (!$model->save()) {
            return $this->render($this->action->id, $params);
        }

        $transaction->commit();

        // Set flash message
        if ($this->action->id == 'create') {
            Yii::$app->getSession()->setFlash('page', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));
        } else {
            Yii::$app->getSession()->setFlash('page', Yii::t('app', '"{item}" has been updated', ['item' => $model->name]));
        }

        // Take appropriate action based on the pushed button
        if (isset($post['save-close'])) {
            // No referrer
            if (Yii::$app->request->get('referrer') != 'menu-items')
                return $this->redirect(['index']);
            else
                return $this->redirect(['/menu/menu-item/index']);

        } elseif (isset($post['save-add'])) {
            return $this->redirect(['create']);
        } else {
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }
}