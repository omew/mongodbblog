<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\Page;
use common\models\Post;
use common\models\Tag;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;

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
     * 点击文章页面之后显示的信息
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPost($id)
    {
        $post = Post::find()->andWhere(['id' => intval($id)])->published()->one();
        if (!$post) {
            throw new NotFoundHttpException('页面不存在');
        }
        return $this->render('post', ['post' => $post]);
    }

    /**
     * Category 信息相关修改
     * @access public
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCategory($id)
    {
        $category = Category::find()->andWhere(['id' => intval($id)])->one();
        if (!$category) {
            throw new NotFoundHttpException('页面不存在');
        }
        return $this->getPost($category);
    }

    /**
     * tag action  获取制定ｔａｇ　下的　文章
     * @access public
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTag($id)
    {
        $tag = Tag::find()->andWhere(['id' => intval($id)])->one();
        if (!$tag) {
            throw new NotFoundHttpException('页面不存在');
        }
        return $this->getPost($tag, 'tag');
    }

    /**
     * 获取post 文件
     * @param $model tag 或者是　category　的数据
     * @param string $flag
     * @return string
     */
    private function getPost($model, $flag = 'category')
    {
        $count = $model->count;
        $posts = $model->post;
        $pagination = new Pagination([
            'totalCount' => $count
        ]);
        $limit = $pagination->limit;
        $offset = $pagination->offset;
        $thisstop = $limit + $offset;
        $thisstop = $thisstop < $count ?: $count;
        //循环取出数据来
        $postid = [];
        for ($offset; $offset < $thisstop; $offset++) {
            $postid[] = $posts[$offset]['id'];
        }
        //这种方式获取到的信息是 通过 新封装mongodb 接口获取的
//        $posts = Post::getPosts($postid);
        $posts = Post::find()->where(['in', 'id', $postid])->all();
        return $this->render('list', [
            'posts' => $posts,
            'pagination' => $pagination,
            "$flag" => $model]);
    }


    /**
     * 获取独立页面
     * ＠access public
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPage($id)
    {
        $page = Page::find()->andWhere(['id' => intval($id)])->one();
        if (!$page) {
            throw new NotFoundHttpException('页面不存在');
        }
        return $this->render('page', ['page' => $page]);
    }


}
