<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>


    <?php echo $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create and close') : Yii::t('app', 'Update and close'), ['class' => 'btn btn-default']) ?>
        <?php if ($model->isNewRecord): ?>
        <?= Html::a(Yii::t('app', 'Create & new'), ['update', 'id' => $model->id, 'new' => true], ['class' => 'btn btn-info']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>



    <?php ActiveForm::end(); ?>

</div>
