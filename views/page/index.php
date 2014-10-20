<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('infoweb/pages', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php // Flash messages ?>
    <?php if (Yii::$app->getSession()->hasFlash('page')): ?>
    <div class="alert alert-success">
        <p><?= Yii::$app->getSession()->getFlash('page') ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (Yii::$app->getSession()->hasFlash('page-error')): ?>
    <div class="alert alert-danger">
        <p><?= Yii::$app->getSession()->getFlash('page-error') ?></p>
    </div>
    <?php endif; ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('infoweb/pages', 'Page'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin([
        'id'=>'grid-pjax'
    ]); ?>
    <?php echo GridView::widget([
        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            [
                'class' => 'kartik\grid\DataColumn',
                'label' => Yii::t('app', 'Url'),
                'value' => function($data) {
                    $url = '/'.Yii::$app->language.'/'.$data->alias->url;
                    return $url;
                    //return Html::a($url, $url, ['target' => '_blank']);
                },
                'enableSorting' => true
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'homepage',
                'vAlign'=>'middle'
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete} {active} {homepage}',
                'buttons' => [
                    'active' => function ($url, $model) {
                        if ($model->active == true) {
                            $icon = 'glyphicon-eye-open';
                        } else {
                            $icon = 'glyphicon-eye-close';
                        }

                        return Html::a('<span class="glyphicon ' . $icon . '"></span>', $url, [
                            'title' => Yii::t('app', 'Toggle active'),
                            'data-pjax' => '0',
                            'data-toggleable' => 'true',
                            'data-toggle-id' => $model->id,
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                    'homepage' => function ($url, $model) {
                        if ($model->homepage == true)
                            return '<span class="glyphicon glyphicon-home icon-disabled"></span>';

                        return Html::a('<span class="glyphicon glyphicon-home"></span>', $url, [
                            'title' => Yii::t('infoweb/pages', 'Set as homepage'),
                            'data-pjax' => '0',
                            'data-toggleable' => 'true',
                            'data-toggle-id' => $model->id,
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                ],
                'updateOptions'=>['title' => 'Update', 'data-toggle' => 'tooltip'],
                'deleteOptions'=>['title' => 'Delete', 'data-toggle' => 'tooltip'],
                'width' => '140px',
            ],
        ],
        'responsive' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 88],
        'hover' => true,
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>