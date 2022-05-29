<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FormList */

$this->title = 'Update Form : ' . $modelFormList->FORMLISTID;
$this->params['breadcrumbs'][] = ['label' => 'Form Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelFormList->FORMLISTID, 'url' => ['view', 'id' => $modelFormList->FORMLISTID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="form-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelFormList' => $modelFormList,
        'modelsFormQuestion' => $modelsFormQuestion,
        'modelsFormQuestionOption' => $modelsFormQuestionOption,
    ]) ?>

</div>
