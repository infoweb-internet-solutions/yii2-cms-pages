<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;
?>
<div class="tab-content images-tab">

    <?php if (Yii::$app->getModule('pages')->enableImage) : ?>

    <?= FileInput::widget([
        'name' => 'ImageUploadForm[image]',
        'options' => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'initialPreview' => $model->image->fileInputWidgetPreview,
            'initialCaption' => $model->image->name,
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
            'browseLabel' => Yii::t('app', 'Browse'),
            'removeLabel' => Yii::t('app', 'Remove'),
            'removeTitle' => Yii::t('app', 'Remove selected files'),
            'uploadLabel' => Yii::t('app', 'Upload'),
            'uploadTitle' => Yii::t('app', 'Upload selected files'),
            'cancelLabel' => Yii::t('app', 'Cancel'),
        ],
        'pluginEvents' => [
            "fileclear" => "function() {
                var request = $.post({$model->removeRequest});
                //request.done(function(response) {});
            }"
        ],
    ]) ?>

    <?php endif; ?>
</div>