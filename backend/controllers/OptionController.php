<?php
/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang/>]
 */
namespace backend\controllers;

use yii;
use common\models\OptionGeneralForm;

/**
 * 设置
 * @package backend\controllers
 */
class OptionController extends BaseController
{

    public function actionIndex()
    {
        $model = new OptionGeneralForm();
        if (yii::$app->request->isPost) {
            if ($model->load(yii::$app->request->post()) && $model->saveForm()) {
                $this->refresh();
            }
        }
        return $this->render('index', ['model' => $model]);
    }

}