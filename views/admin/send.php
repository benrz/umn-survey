<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = 'Spread Out';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <?php ?>
    <h5>
        The email was successfully sent.
        <br>
        <br>
    </h5>
    <?= Html::a('BACK', ['admin/spread'], ['class'=>'btn btn-primary']) ?>
    <?php else: ?>

    <div class="row">
        <div class="col-md-12">

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <div class="col-md-6">
                    <div>    
                        <?= $form->field($model, 'user',[
                        'template'=>"<h3>{label}</h3>{input}"
                        ])->checkboxList([
                            1 => 'PUBLIC', 2 => 'LECTURER', 3 => 'STUDENT', 4 => 'STAFF']); ?>

                        <?= $form->field($model, 'PRODI',[
                            'template'=>"<h3>{label}</h3>{input}"
                            ])->checkboxList([
                            1 => 'INFORMATIKA', 2 => 'SISTEM INFORMASI', 3 => 'TEKNIK KOMPUTER', 
                            4 => 'TEKNIK ELEKTRO', 5 => 'TEKNIK FISIKA', 6 => 'DKV', 7 => 'ARSITEKTUR',
                            8 => 'FILM', 9 => 'KOMUNIKASI STRATEGIS', 10 => 'JURNALISTIK',
                            11 => 'MANAGEMENT', 12 => 'AKUNTANSI', 13 => 'PERHOTELAN']); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'subject',[
                        'template'=>"<h3>{label}</h3>{input}"
                        ]) ?>

                    <?= $form->field($model, 'body', [
                        'template'=>"<h3>{label}</h3>{input}"
                    ])->textarea(['rows' => 6,'value'=>Url::toRoute('site/survey',true).'&id='.$id.'','placeholder'=>'Enter Message Here.....'])->label('Message') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Send', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
                        <?= Html::a('Cancel', ['admin/spread'], ['class'=>'btn btn-primary']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>

            </div>
        </div>
    
    <?php endif; ?>
</div>
