<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;

?>
<div class="tab-content menu-tab">
    <?php if (Yii::$app->getModule('pages')->enableForm) : ?>
        <?= $form->field($model, 'form_id')->widget(Select2::classname(), [
            'data' => $forms,
            'options' => ['placeholder' => Yii::t('app', 'Select a form')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]); ?>
    <?php endif; ?>
</div>