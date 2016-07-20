<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;

?>
<div class="tab-content default-tab">
    <?php

    echo $form->field($model, 'type')->dropDownList([
        'system' => Yii::t('app', 'System'),
        'user-defined' => Yii::t('app', 'User defined')
    ], [
        'options' => [
            'system' => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true],
            'user-defined' => ['disabled' => ($model->type == 'system' && !Yii::$app->user->can('Superadmin')) ? true : false],
        ]
    ]);

    echo $form->field($model, 'template_id')->dropDownList(ArrayHelper::map($templates, 'id', 'name'),[
        'options' => [
            'system' => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true]
        ]
    ]);

    echo $form->field($model, 'homepage')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]);

    echo $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]);

    if (Yii::$app->getModule('pages')->enablePrivatePages):
        echo $form->field($model, 'public')->widget(SwitchInput::classname(), [
            'inlineLabel' => false,
            'pluginOptions' => [
                'onColor' => 'success',
                'offColor' => 'danger',
                'onText' => Yii::t('app', 'Yes'),
                'offText' => Yii::t('app', 'No'),
            ]
        ]);
    endif;

    ?>
</div>