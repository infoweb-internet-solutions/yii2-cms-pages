<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model infoweb\partials\models\PagePartial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>
    
    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'page-form',
        'options'                   => ['class' => 'tabbed-form'],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,       
    ]);
    
    // Initialize the tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'General'),
            'content' => $this->render('_default_tab', ['model' => $model, 'form' => $form]),
        ],
        [
            'label'     => Yii::t('app', 'Data'),
            'content'   => $this->render('_data_tab', [
                'model'         => $model,
                'form'          => $form,
                'templates'     => $templates,
                'sliders'       => $sliders,
            ]),
        ],
        [
            'label' => 'SEO',
            'content' => $this->render('@infoweb/seo/views/behaviors/seo/_seo_tab', ['model' => $model, 'form' => $form]),
        ],
    ];

    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>
    
    <div class="form-group buttons">

        <?php // Modal referrer, custom buttons ?>
        <?php if (Yii::$app->session->get('modal') == true): ?>

        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success btn-modal', 'name' => 'modal'/*, 'data-url' => Url::toRoute('/pages/page/create') */]) ?>
        <?= Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>

        <?php // No referrer, default buttons ?>
        <?php elseif (Yii::$app->request->get('referrer') != 'menu-items') : ?>

        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'close']) ?>
        <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create & new' : 'Update & new'), ['class' => 'btn btn-default', 'name' => 'new']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>

        <?php // Referrer, custom buttons ?>
        <?php else : ?>

        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'close']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['/menu/menu-item/index'], ['class' => 'btn btn-danger']) ?>
            
        <?php endif; ?>     
    </div>

    <?php ActiveForm::end(); ?>

</div>