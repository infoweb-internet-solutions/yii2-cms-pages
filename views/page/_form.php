<?php

use kartik\widgets\SwitchInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="page-form">

    <div class="form-group">&nbsp;</div>

    <div class="form-group field-page-template">
        <label class="control-label" for="template">Template</label>
        <?php //= Html::dropDownList('template', $model->template, $templates, ['class' => 'form-control', 'style' => 'height: 150px;']) ?>
        <select name="Page[template]" class="form-control">
            <option value="0">-- Kies een template --</option>
            <option value="1" <?php echo ($model->template == 1) ? 'selected="selected"' : ''; ?>>Home</option>
            <option value="2" <?php echo ($model->template == 2) ? 'selected="selected"' : ''; ?>>Nieuws</option>
            <option value="3" <?php echo ($model->template == 3) ? 'selected="selected"' : ''; ?>>Contact</option>
        </select>
        <div class="help-block"></div>
    </div>



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
