<?php
/**
 * User: timeless
 * Date: 16-8-31
 * Time: 下午9:41
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '独立页面';
?>
<div class="content-index">
    <p>
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => \yii\grid\CheckboxColumn::className()],
            [
                'header' => '标题',
                'class' => yii\grid\Column::className(),
                'content' => function ($model, $key, $index, $column) {
                    return $model->title . '&nbsp;' . Html::a('<span class="glyphicon glyphicon-link"></span>', Yii::$app->frontendUrlManager->createUrl(['site/page', 'slug' => $model->slug]), ['target' => '_blank', 'title' => '查看']);
                }
            ],
            'slug',
            ['label' => '作者', 'value' => 'authorName'],
            [
                'label' => '发布时间',
                'value' => function ($model) {
                    if ($model->created) {
                        return Yii::$app->formatter->asDatetime($model->created, 'php:Y-m-d');
                    } else {
                        return '';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
        'tableOptions' => ['class' => 'table table-striped']
    ]); ?>

</div>


