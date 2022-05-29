<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LoginForm */
/* @var $form ActiveForm */
?>
<div class="container">
    <div class="row d-flex justify-content-left pt-5">
        <div class="col-lg-6 pr-0">
            <div class="section-tittle">
                <h2><?php echo $formTitle; ?></h2>
            </div>
        </div>
    </div>
    <p>Your answer has been saved, thankyou</p>
    <p><a href="<?= Url::toRoute('site/index')?>" class="btn hero-btn">Continue</a></p>
</div>
