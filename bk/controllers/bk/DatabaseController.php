<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Cookie;
use app\models\Country;
use yii\data\Pagination;

class DatabaseController extends Controller {

    public function actionGet() {
        // 获取 country 表的所有行并以 name 排序
        $countries = Country::find()->orderBy('name')->all();
        // 获取主键为 “US” 的行
        $country = Country::findOne('US');
        //输出 “United States”
        echo $country->name;
        // 修改 name 为 “U.S.A.” 并在数据库中保存更改
        $country->name = 'U.S.A.';
        $country->save();
        //测试
    }

    /**
     * 首页数据
     * @access public
     */
    public function actionIndex() {
        $query = Country::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $countries = $query->orderBy('name')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
        return $this->render('index', [
                    'countries' => $countries,
                    'pagination' => $pagination,
        ]);
    }

    /**
     * request 组件测试
     * @access public
     */
    public function actionRequestDemo() {
        $request = \YII::$app->request;
//        if ($request->isGet)
//            echo $request->get('a', 'a默认值');
//        else
//            echo '不是get请求';
    }

    public function actionResponseDemo() {
        $res = \yii::$app->response;
        //设置 404 not found
        $res->statusCode = '404';
        //设置 头
        $res->headers->add('pragma', 'no-cache');
        //设置缓存5秒
        $res->headers->add('pragma', 'max-age=5');
        //删除属性
        $res->headers->remove('pragma');
        //跳转操作
        $res->headers->add('location', 'http://www.baidu.com');
        //文件下载实现
        $res->header->add('content-disposition', 'attachment;filename="a.jpg"');
        //要下载的文件大小
        $res->sendFile('./b.jpg');
//        $this->redirect('http://www.baidu.com', '302');
    }

    /**
     * session demo
     */
    public function actionSessionDemo() {
        $session = \yii::$app->session;
        //开启session
        $session->open();
        if ($session->isActive) {
            //对象   ArrayAccess
            $session->set('user', '张三');
            echo $session->get('user');
            $session->remove('user');
            //或者还有其他的方式
            //数组
//            $session['user'] = '张三';
//            echo $session['user'];
//            unset($session['user']);
        }
    }

    public function actionCookiesDemo() {
        $rescookies = \yii::$app->response->cookies;
        $cookies_data = ['name' => 'zhuang', 'value' => 'zhangsan'];
        $rescookies->add(new Cookie($cookies_data));
        $rescookies->reove();
        $reqcookies = \yii::$app->request->cookies;
        $reqcookies->getValue('user');
    }

    /**
     * render 测试 传递数据测试
     */
    public function actionRenderDemo() {
        $data['demo1'] = 'aaa<script>alert("aaa");</script>';
        $data['demo2'] = array('demo' => '111<script>alert("aaa");</script>aa', 'demo2' => 222);
        //不使用布局文件
        return $this->renderPartial('renderdemo', $data);
        //使用布局文件     render函数调用view返回要渲染数据，传给layout返回给浏览器
        return $this->render('renderdemo', $data);
    }

}
