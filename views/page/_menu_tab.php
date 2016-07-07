<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;
?>
<div class="tab-content menu-tab">
    
    <?php if (Yii::$app->getModule('pages')->enableMenu) : ?>

    <?= $form->field($model, 'menu_id')->widget(Select2::classname(), [
        'data' => $menus,
        'options' => ['placeholder' => Yii::t('app', 'Select a menu')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <?php endif; ?>
</div>