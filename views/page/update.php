<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;
use app\models\Seo;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Page',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Update')];
?>
<div class="page-update">

    <?php /*
    <?= \c006\spinner\SubmitSpinner::widget(); ?>
    */ ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $items = [
        [
            'label' => 'General',
            'content' => $this->render('_form', ['model' => $model, 'form' => $form, 'templates' => $templates]),
            'active' => true,
        ],
    ];

    // Get seo
    $seo = Seo::findOne(['entity' => 'page', 'entity_id' => $model->id]);

    if (! isset($seo)) {
        $seo = new Seo;
        $seo->loadTranslations('nl-BE', 'fr-FR', 'en-US');
    }

    // loop through languages to edit
    foreach (Yii::$app->params['languages'] as $k => $language) {

        $seo->language = $k;
        $model->language = $k;

        $items[] = [
            'label' => $language,
            'content' => $this->render('_translation_item', ['model' => $model, 'language' => $k, 'form' => $form, 'seo' => $seo]),
        ];
    }
    ?>

    <?php
    echo Tabs::widget([
        'items' => $items,
    ]);
    ?>

    <div class="form-group">&nbsp;</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'close']) ?>
        <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create & new' : 'Update & new'), ['class' => 'btn btn-default', 'name' => 'new']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>