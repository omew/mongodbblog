<?php
namespace backend\controllers;

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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
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
     * 上传文件
     * @return array
     */
    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        //error ['err'=>1,'msg'=>'error message']
        //success ['err'=>0,'msg'=>'success message','data'=>['id'=>'','name'=>'','url'=>'','isImage'=>'']]
        $upload = new Upload([
            'savePath' => Attachment::SAVE_PATH,
        ]);
        if ($upload->checkFileInfoAndSave()) {
            //保存到数据库
            $attachment = new Attachment();
            $attachment->title = $upload->originalFileName;
            $attachment->text = [
                'name' => Html::encode($upload->originalFileName),
                'path' => Yii::getAlias(Attachment::WEB_URL . $upload->saveRelativePath),
                'minetype' => $upload->fileMimeType,
                'ext' => $upload->fileExt,
                'size' => $upload->filesize,
            ];
            $attachment->save(false);
            return [
                'err' => 0,
                'msg' => '上传成功',
                'data' => [
                    'id' => $attachment->cid,
                    'name' => Html::encode($upload->originalFileName),
                    'url' => Yii::getAlias(Attachment::WEB_URL . $upload->saveRelativePath),
                    'isImage' => in_array($upload->fileMimeType, Attachment::$imageMineType),
                ],
            ];
        } else {
            return ['err' => 1, 'msg' => $upload->error];
        }
    }
}
