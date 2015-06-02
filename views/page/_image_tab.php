<?php
use kartik\widgets\FileInput;
use yii\helpers\Html;

$smallImage = $model->getImageByIdentifier('small');
$largeImage = $model->getImageByIdentifier('large');
?>
<div class="tab-content image-tab">
    
    <div class="form-group">
        <label class="control-label"><?= Yii::t('infoweb/pages', 'Small image') ?></label>
        <?= FileInput::widget([
            'name' => 'ImageUploadForm[image][small]',
            'options' => [
                'accept' => 'image/*',
            ],
            'pluginOptions' => [
                'initialPreview' => ($smallImage) ? $smallImage->getFileInputWidgetPreview() : [],
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                'initialCaption' => ($smallImage) ? $smallImage->getFileInputWidgetCaption() : '',
            ],
            'pluginEvents' => [
                "fileclear" => "function() {
                    var request = $.post('remove-image', {model: '{$model->id}', identifier: 'small'});
    
                    request.done(function(response) {
    
                    });
                }"
            ]
        ]) ?>    
    </div>    
    
    <div class="form-group">
        <label class="control-label"><?= Yii::t('infoweb/pages', 'Large image') ?></label>
        <?= FileInput::widget([
            'name' => 'ImageUploadForm[image][large]',
            'options' => [
                'accept' => 'image/*',
            ],
            'pluginOptions' => [
                'initialPreview' => ($largeImage) ? $largeImage->getFileInputWidgetPreview() : [],
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                'initialCaption' => ($largeImage) ? $largeImage->getFileInputWidgetCaption() : '',
            ],
            'pluginEvents' => [
                "fileclear" => "function() {
                    var request = $.post('remove-image', {model: '{$model->id}', identifier: 'large'});
    
                    request.done(function(response) {
    
                    });
                }"
            ]
        ]) ?>    
    </div>    

</div>
