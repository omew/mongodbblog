<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;

/**
 * 登陆操作 控制器
 * @author timeless
 */
class LoginController extends BaseController
{

    //独立方法 就是公共的方法放到actions()
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 4,
                'minLength' => 4,
            ],
        ];
    }

    /**
     * 登陆操作
     */
    public function actionLogin()
    {

        //mongodb 实例操作
/*        $collection = Yii::$app->mongodb->getCollection('customer');
        $res = $collection->insert([
            'name' => 'John Smith22',
            'status' => 2
        ]);
        var_dump($res);*/
        echo 'dsadas';
        $password='201671zhuang';
        echo Yii::$app->security->generatePasswordHash($password);
        exit;

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        //yii 组件生成的表单元素提交的时候是这样的
//        LoginForm[username]:admin
//        LoginForm[password]:123456
//        LoginForm[rememberMe]:0
//        load 对于自定义的表单不能正确 load 详情查看 load 函数定义处   如果是自定义的表单自动 加载属性需要把第二个参数设置为空
        if ($model->load(Yii::$app->request->post(), '') && $model->validateVerifyCode($this->createAction('captcha')->getVerifyCode()) && $model->login()) {
            //获取验证码  然后验证是不是验证码正确与否
            //也可以使用框架封装好的函数 
//            $this->createAction('captcha')->validate($model->verifyCode, false)
            return $this->goBack();
        }
        $error = $model->errors;
        if (empty($error)) {
            $msg = '请填写用户名密码!';
        } else {
            $msg_arr = array_values($error);
            $msg = $msg_arr[0][0];
        }
        return $this->renderPartial('login', ['msg' => $msg]);
    }

}
