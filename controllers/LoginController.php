<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;

class LoginController extends \yii\web\Controller
{
    public $layout = false;

    public function actionIndex()
    {
        if(Yii::$app->session->get('role') != NULL){
            return $this->redirect(['site/index']);
        }
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $role = $model->getRole();
            Yii::$app->session->set('role',$role);
            Yii::$app->session->set('email', $model->email);

            if ($role == 5) { // Role is admin
                return $this->redirect(['admin/index']);
            } else { // Role is customer
                return $this->redirect(['site/index']);
            }
        }

        $model->password = '';
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
