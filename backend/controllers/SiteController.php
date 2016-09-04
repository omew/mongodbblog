<?php
namespace backend\controllers;

use common\models\Page;
use common\models\Post;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 展现在首页
     * @return string
     */
    public function actionIndex()
    {
        //还需要获取评论信息  这块需要改成 用 activeQuery 的形式获取
        $posts = Post::find()->selectNoText()->recentPublished()->all();
        return $this->render('index',
            [
                //总的发布的单独的页面数量
                'pageCount' => Page::find()->count(),
                //总的发布的文章数量
                'postCount' => Post::find()->count(),
                'posts' => $posts,
            ]
        );
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 个人信息完善操作
     * @access public
     */
    public function actionProfile()
    {
        $model = Yii::$app->user->identity;
        $model->scenario = 'profile';
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['profile']);
            }
        }
        return $this->render('profile', ['model' => $model]);
    }
}
