<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FormList */

$this->title = $modelFormList->FORMLISTID;
$this->params['breadcrumbs'][] = ['label' => 'Form Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="form-list-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Update', ['update', 'id' => $modelFormList->FORMLISTID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $modelFormList->FORMLISTID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p> -->

    <?= DetailView::widget([
        'model' => $modelFormList,
        'attributes' => [
            // 'FORMID',
            'FORMLISTID',
            'FORMLISTTITLE',
            'FORMLISTDATE',
            'FORMLISTTOTALSECTION',
            'FORMLISTTOTALQUESTION',
            // 'USERJOBID'
        ],
    ]) ?>

</div>
