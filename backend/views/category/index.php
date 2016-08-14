<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '管理分类';
?>

<div class="meta-index">
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>
    <?php if($parentCategory): ?>
        <?= Html::a('返回上一级', ['/category/index','parent'=>$parentCategory->parent], ['class' => 'btn btn-default']) ?>
    <?php endif; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => \yii\grid\CheckboxColumn::className()],
            'name',
            [
                'header'=>'子分类',
                'class' => yii\grid\Column::className(),
                'content'=>function ($model, $key, $index, $column){
                    $count= \common\components\CategoryTree::getInstance()->getSubCategoriesCount($model->id);
                    if($count==0){
                        return Html::a('新增',['/category/create','parent'=>$model->id]);
                    }else{
                        return Html::a($count.'个分类',['/category','parent'=>$model->id]);
                    }
                }
            ],
            'slug',
            'count',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
            ],
        ],
        'tableOptions'=>['class' => 'table table-striped']
    ]); ?>

</div>
