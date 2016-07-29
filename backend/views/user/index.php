<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理用户';
?>
<div class="user-index">

    <p>
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => "{begin}-{end}，共{totalCount}条数据，共{pageCount}页",
//        'summary'=>false,
        'columns' => [
            ['class' => \yii\grid\CheckboxColumn::className()],
            ['label' => '登录名', 'value' => 'name'],
            ['label' => '昵称', 'value' => 'screenname'],
            ['label' => '邮箱', 'value' => 'mail'],
            ['label' => '描述', 'value' => 'desc','headerOptions' => ['width' => '170'],],
            [
                'class' => 'yii\grid\DataColumn', //由于是默认类型，可以省略
                'label' => '添加时间',
                'value' => function ($data) {
                    return date('Y-m-d', $data->addtime);
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
