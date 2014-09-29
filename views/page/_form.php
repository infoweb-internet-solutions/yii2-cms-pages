<?php

use kartik\widgets\SwitchInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="page-form">

    <div class="form-group field-page-template">
        <label class="control-label" for="template">Template</label>
        <?= Html::dropDownList('Page[template]', $model->template, $templates, ['class' => 'form-control', 'prompt' => 'Kies een template']) ?>
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
