<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FormList */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Form Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelFormList' => $modelFormList,
        'modelsFormQuestion' => $modelsFormQuestion,
        'modelsFormQuestionOption' => $modelsFormQuestionOption,
    ]) ?>

</div>
