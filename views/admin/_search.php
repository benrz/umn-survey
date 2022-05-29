<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FormListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'FORMLISTID') ?>

    <?= $form->field($model, 'FORMLISTTITLE') ?>

    <?= $form->field($model, 'FORMLISTDATE') ?>

    <?= $form->field($model, 'FORMLISTTOTALSECTION') ?>

    <?= $form->field($model, 'FORMLISTTOTALQUESTION') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
