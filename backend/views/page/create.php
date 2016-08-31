<?php
/**
 * User: timeless
 * Date: 16-8-31
 * Time: 下午9:45
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Content */

$this->title = '创建新页面';
?>
<div class="content-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
