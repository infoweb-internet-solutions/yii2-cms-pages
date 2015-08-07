<?php
use kartik\widgets\FileInput;
use yii\helpers\Html;

$image = $model->image;

?>
<div class="tab-content image-tab">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('infoweb/cms', 'Image') ?></label>
        <?= FileInput::widget([
            'name' => 'ImageUploadForm[image]',
            'options' => [
                'accept' => 'image/*',
            ],
            'pluginOptions' => [
                'initialPreview' => ($image) ? $image->getFileInputWidgetPreview() : [],
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                'initialCaption' => ($image) ? $image->getFileInputWidgetCaption() : '',
            ],
            'pluginEvents' => [
                "fileclear" => "function() {
                    var request = $.post('remove-images', {model: '{$model->id}'});
    
                    request.done(function(response) {
    
                    });
                }"
            ]
        ]) ?>    
    </div>
</div>
