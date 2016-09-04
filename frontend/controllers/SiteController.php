<?php
namespace frontend\controllers;

use common\models\Post;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\mongodb\ActiveRecord;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * 首页信息 首先第一需要展现全部的文章
     */
    public function actionIndex()
    {
        $pagination = new Pagination([
            'totalCount' => Post::find()->count(),
        ]);
        $posts = Post::find()
            ->published()
            ->orderById()
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('list', ['posts' => $posts, 'pagination' => $pagination]);
    }

    /**
     *
     */
    public function actionPost()
    {

    }
}
