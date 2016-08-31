<?php
/**
 * Created by PhpStorm.
 * User: timeless
 * Date: 16-8-31
 * Time: 下午9:29
 */

namespace backend\controllers;


use common\models\Page;
use yii\data\ActiveDataProvider;
use Yii;

class PageController extends BaseController
{
    /**
     * 首页数据
     * @access public
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Page::find(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 创建页面
     * @access public
     */
    public function actionCreate()
    {
        $model = New Page();
        $model->allowComment = true;
        $model->allowFeed = true;
        $model->allowPing = true;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
                'model' => $model
            ]
        );
    }

    /**
     * 执行更新操作
     * @access public
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id, '_id');
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除数据操作
     * @access public
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param 标志根据什么取出|string $flag 标志根据什么取出 model
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id, $flag = '_id')
    {
        if ($flag == '_id') {
            //根据mongodb _id 取数据
            if (($model = Page::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            //根据 mongodb 自定义的主键取数据 方式
            if (($model = Page::find()->andWhere(['id' => intval($id)])->one()) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }

}