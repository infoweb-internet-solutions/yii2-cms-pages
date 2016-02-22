<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
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
                'sliders'                 => $sliders
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