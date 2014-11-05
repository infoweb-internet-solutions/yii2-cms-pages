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

    <?php // Title ?>
    <h1>
        <?= Html::encode($this->title) ?>
        <?php // Buttons ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => Yii::t('infoweb/pages', 'Page'),
            ]), ['create'], ['class' => 'btn btn-success']) ?>    
        </div>
    </h1>
    
    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php // Gridview ?>
    <?php Pjax::begin(['id'=>'grid-pjax']); ?>
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
                'template' => '{update} {delete} {active} {homepage} {view}',
                'buttons' => [
                    'active' => function ($url, $model) {
                        if ($model->active == true) {
                            $icon = 'glyphicon-eye-open';
                        } else {
                            $icon = 'glyphicon-eye-close';
                        }

                        if ($model->homepage)
                            return "<span class=\"glyphicon {$icon} icon-disabled\"></span>";

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
                            return '<span class="glyphicon glyphicon-home icon-active"></span>';

                        return Html::a('<span class="glyphicon glyphicon-home"></span>', $url, [
                            'title' => Yii::t('infoweb/pages', 'Set as homepage'),
                            'data-pjax' => '0',
                            'data-toggleable' => 'true',
                            'data-toggle-id' => $model->id,
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                    'view' => function ($url, $model) {

                        return Html::a('<span class="glyphicon glyphicon-globe"></span>', Yii::getAlias('@domain') . '/' .$model->alias->url, [
                            'title' => Yii::t('app', 'View'),
                            'target' => '_blank',
                            'data-toggle' => 'tooltip'
                        ]);
                    },
                ],
                'updateOptions'=>['title' => Yii::t('app', 'Update'), 'data-toggle' => 'tooltip'],
                'deleteOptions'=>['title' => Yii::t('app', 'Delete'), 'data-toggle' => 'tooltip'],
                'width' => '180px',
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