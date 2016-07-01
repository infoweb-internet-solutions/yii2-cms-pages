<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;
?>
<div class="tab-content sliders-tab">
    
    <?php if (Yii::$app->getModule('pages')->enableSliders) : ?>

    <?= $form->field($model, 'slider_id')->widget(Select2::classname(), [
        'data' => $sliders,
        'options' => ['placeholder' => Yii::t('app', 'Select a slider')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <?php endif; ?>
</div>