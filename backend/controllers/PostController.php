<?php

namespace backend\controllers;

use common\models\Post;
use Yii;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * PostController implements the CRUD actions for Content model.
 * 发布文章实现
 */
class PostController extends BaseController
{


    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*        $dataProvider = new ActiveDataProvider([
                    'query' => Post::find()->selectNoText()->with('categories')->with('author')->orderByCid(),
                ]);*/
        return $this->render('index', [
//            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->allowComment = true;
        $model->allowFeed = true;
        $model->allowPing = true;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->inputCategorie = Yii::$app->request->post('inputCategorie');
                $model->inputTags = Yii::$app->request->post('inputTags', []);
//              $model->inputAttachments=Yii::$app->request->post('inputAttachments',[]);
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->inputCategories = Yii::$app->request->post('inputCategories', []);
                $model->inputTags = Yii::$app->request->post('inputTags', []);
//                $model->inputAttachments=Yii::$app->request->post('inputAttachments',[]);
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
     * Deletes an existing Content model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
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
    protected function findModel($id, $flag = '_id')
    {
        if ($flag == '_id') {
            //根据mongodb _id 取数据
            if (($model = Post::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            //根据 mongodb 自定义的主键取数据 方式
            if (($model = Post::find()->andWhere(['id' => intval($id)])->one()) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
}
