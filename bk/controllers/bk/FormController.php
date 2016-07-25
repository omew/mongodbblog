<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * @author timeless
 */
class FormController extends Controller {

    //put your code here

    public function actionIndex() {
        return $this->renderPartial('index');
    }

}
