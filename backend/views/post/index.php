<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model common\models\Content */

$this->title = '文章';
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
//                    return $model->title.'&nbsp;'.Html::a('<span class="glyphicon glyphicon-link"></span>',Yii::$app->frontendUrlManager->createUrl(['site/post','id'=>$key]),['target'=>'_blank','title'=>'查看']);
                    return $model->title;
                }
            ],
            ['label' => '作者', 'value' => 'authorName'],
            ['label' => '分类', 'value' => 'category_name'],
            [
                'label' => '标签',
                'value' => function ($model) {
                    $tags = $model->tags;
                    $names = ArrayHelper::getColumn($tags, 'name');
                    return implode(' ', $names);
                },
            ],
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
