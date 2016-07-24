<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends BaseController {

    //独立方法 就是公共的方法放到actions()
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
//        echo '<pre>';
//        print_r($_SESSION);
//        print_r(Yii::$app->session);
//        print_r(\yii::$app->user->isGuest);
//        print_r(\Yii::$app->user->identity);
//        exit;
        return $this->render('index');
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
