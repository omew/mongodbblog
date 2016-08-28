<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Content;
use backend\widgets\BootstrapDatetimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-9">
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'text')->widget('yidashi\markdown\Markdown', ['language' => 'zh']); ?>
        <div class="form-group">
            <?= Html::submitButton('发布文章', ['class' => 'btn btn-primary']) ?>
        </div>
    </div><!-- post -->
    <div class="col-md-3">
        <?= \yii\bootstrap\Tabs::widget([
            'renderTabContent' => false,
            'items' => [
                [
                    'label' => '选项',
                    'options' => ['id' => 'options'],
                ],
                [
                    'label' => '附件',
                    'options' => ['id' => 'files'],
                ],
            ],
        ]) ?>
        <div class="tab-content">
            <div id="options" class="tab-pane active">
                <?= BootstrapDatetimePicker::widget([
                    'model' => $model,
                    'attribute' => 'created'
                ]) ?>
                <?= \common\widgets\CategoryCheckboxList::widget(['postId' => $model->id]) ?>
                <?= \backend\widgets\TagsEdit::widget([
                    'name' => 'inputTags[]',
                    'tags' => $model->isNewRecord ? [] : $model->tags,
                ]) ?>
                <?= $form->field($model, 'status')->dropDownList([
                    Content::STATUS_PUBLISH => '公开',
                    Content::STATUS_HIDDEN => '隐藏',
                ]) ?>
                <?= $form->field($model, 'allowComment')->checkbox() ?>
                <?= $form->field($model, 'allowPing')->checkbox() ?>
                <?= $form->field($model, 'allowFeed')->checkbox() ?>
            </div>
            <div id="files" class="tab-pane">
                <!----><? //= \backend\widgets\Plupload::widget([
                //                    'attachments' => $model->isNewRecord ? [] : $model->attachments,
                //                    'fileInputName' => 'file',
                //                    'filesInputHiddenName' => 'inputAttachments[]',
                //                    'serverUrl' => Yii::$app->urlManager->createUrl('site/upload')
                //                ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>


</div>