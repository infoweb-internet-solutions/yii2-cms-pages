<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
        'options'                   => ['class' => 'tabbed-form', 'enctype' => 'multipart/form-data'],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,
        
    ]);

    // Initialize the tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'General'),
            'content' => $this->render('_default_tab', [
                'model'                   => $model,
                'form'                    => $form,
                'allowContentDuplication' => $allowContentDuplication
            ]),
        ],
        [
            'label'     => Yii::t('app', 'Data'),
            'content'   => $this->render('_data_tab', [
                'model'                   => $model,
                'form'                    => $form,
                'templates'               => $templates,
            ]),
        ],
        [
            'label' => 'SEO',
            'content' => $this->render('@infoweb/seo/views/behaviors/seo/_seo_tab', ['model' => $model, 'form' => $form]),
        ],
    ];

    if (Yii::$app->getModule('pages')->enableSliders):
        $tabs[] = [
            'label' => Yii::t('app', 'Sliders'),
            'content'   => $this->render('_sliders_tab', [
                'model'                   => $model,
                'form'                    => $form,
                'templates'               => $templates,
                'sliders'                 => $sliders
            ]),
        ];
    endif;

    if (Yii::$app->getModule('pages')->enableMenu):
        $tabs[] = [
            'label' => Yii::t('app', 'Menu'),
            'content'   => $this->render('_menu_tab', [
                'model'                   => $model,
                'form'                    => $form,
                'templates'               => $templates,
                'menus'                   => $menus
            ]),
        ];
    endif;

    if (Yii::$app->getModule('pages')->enableForm):
        $tabs[] = [
            'label' => Yii::t('app', 'Form'),
            'content'   => $this->render('_form_tab', [
                'model'                   => $model,
                'form'                    => $form,
                'templates'               => $templates,
                'forms'                   => $forms
            ]),
        ];
    endif;

    if (Yii::$app->getModule('pages')->enableImage):
        $tabs[] = [
            'label' => Yii::t('app', 'Image'),
            'content'   => $this->render('_image_tab', [
                'model'                   => $model,
                'form'                    => $form,
                'templates'               => $templates,
                'sliders'                 => $sliders
            ]),
        ];
    endif;

    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>

    <div class="form-group buttons">
        <?php // No referrer, default buttons ?>
        <?php if (Yii::$app->request->get('referrer') != 'menu-items') : ?>

        <?= $this->render('@infoweb/cms/views/ui/formButtons', ['model' => $model]) ?>

        <?php // Referrer, custom buttons ?>
        <?php else : ?>

        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'save-close']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['/menu/menu-item/index'], ['class' => 'btn btn-danger', 'name' => 'close']) ?>

        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
