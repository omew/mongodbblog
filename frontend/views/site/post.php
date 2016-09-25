<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $post common\models\Post */
$this->title = $post->title;
//print_r($post);
?>

<article class="post">
    <header class="post-head">
        <h1 class="post-title"><?= $post->title ?></h1>
        <section class="post-meta">
            <span class="author"><i class="fa fa-user"></i>
                <?= Html::a($post->authorName, ['site/author', 'id' => $post->authorId]) ?>
            </span> &bull;
            <span>
                <i class="fa fa-clock-o"></i>
                <time class="date" datetime="<?= Yii::$app->formatter->asDate($post->created) ?>">
                    <?= Yii::$app->formatter->asDate($post->created) ?>
                </time>
            </span> &bull;
            <span>
            <i class="fa fa-folder-open-o"></i>
                <?= Html::a($post->category_name, ['site/category', 'id' => $post->category_id]) ?>
            </span>
        </section>
    </header>
    <section class="post-content">
        <?= yii\helpers\Markdown::process($post->text, 'gfm') ?>
    </section>
    <footer class="post-footer clearfix">
        <div class="pull-left tag-list">
            <i class="fa fa-tag"></i>
            <?php
            $postTags = $post->tags;
            foreach ($postTags as $v):?>
                <?= Html::a($v['name'], ['site/tag', 'id' => $v['id']]) ?>
            <?php endforeach; ?>
        </div>
        <div class="pull-right share">
        </div>
    </footer>

</article>