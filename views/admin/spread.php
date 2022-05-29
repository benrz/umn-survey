<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FormListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Spread Out';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-list-index">
<style>
.YourCustomTableClass table thead {
    background-color: #FF0000;
}
</style>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'FORMID',
            'FORMLISTID',
            [
                'header' => 'Title',
                'format' => 'raw',
                'headerOptions' => ['style'=>'color: #007bff;'],
                'attribute' => 'FORMLISTTITLE',
                'value' => 'formlist.FORMLISTTITLE',
            ],

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{send}{status}',
                'buttons' => [
                    'send' =>  function($url,$model) {
                        return Html::a('<i class="fas fa-envelope"></i>', $url, [
                            'title' => Yii::t('app', 'send')
                        ]);
                    },
                    'status' =>  function($url,$model) {
                        if($model->FORMSTATUS == '1')
                        {
                            return Html::a('<button style="margin-left:20px; background:#81cc49; width:80px;" class="btn btn-primary">OPEN</button>', $url, [
                                'status' => Yii::t('app', 'status')
                            ]);
                        }
                        else
                        {   
                            return Html::a('<button style="margin-left:20px; background:red; width:80px;" class="btn btn-primary">CLOSE</button>', $url, [
                                'status' => Yii::t('app', 'status')
                            ]);
                        }
                    },
                 ]
            ],
        ],
    ]); ?>


</div>
