<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
?>
<div class="tab-content default-tab">
    <?= $form->field($model, 'type')->dropDownList([
        'system'        => Yii::t('app', 'System'),
        'user-defined'  => Yii::t('app', 'User defined')
    ],[
        'options' => [
            'system' => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true]
        ]
    ]); ?>
    
    <?= $form->field($model, 'template_id')->dropDownList(ArrayHelper::map($templates, 'id', 'name'),[
        'options' => [
            'system' => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true]
        ]
    ]); ?>
    
    <?php echo $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>
</div>