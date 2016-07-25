<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Base控制器  用于验证是不是已经登陆 没有的话跳转到首页
 * @author timeless
 */
class BaseController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //表示方可允许访问的地方  访客只允许 login error 操作
                        'actions' => ['login', 'error','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
//                      要跳、跳转想要的指定页面，只需要在config/main.php中components里面加上'loginUrl' => array('admin/login/index') 
//                      如果不填写的话 默认对全部的其他访问都起作用
//                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['post'],
                    'upload' => ['post'],
                ],
            ],
        ];
    }

}
