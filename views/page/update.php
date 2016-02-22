<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('infoweb/pages', 'Page'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/pages', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'                   => $model,
        'templates'               => $templates,
        'sliders'                 => $sliders,
        'allowContentDuplication' => $allowContentDuplication
    ]) ?>

</div>