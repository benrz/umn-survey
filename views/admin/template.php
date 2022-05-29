<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FormListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Template';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-list-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'FORMLISTID',
            'FORMLISTTITLE',
            'FORMLISTDATE',
            'FORMLISTTOTALSECTION',
            'FORMLISTTOTALQUESTION',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{add}',
                'buttons' => [
                    'add' =>  function($url,$model) {
                        return Html::a('<i class="fas fa-plus-circle"></i>', $url, [
                            'title' => Yii::t('app', 'add')
                        ]);
                    },
                 ]
            ],
        ],
    ]); ?>


</div>
