<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Result';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'FORMID',
            'FORMLISTID',
            [
                'attribute' => 'FORMLISTTITLE',
                'value' => 'formlist.FORMLISTTITLE',
            ],
            'FORMDATESTART',
            'FORMDATEEND',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{answer}',
                'buttons' => [
                    'answer' =>  function($url,$model) {
                        return Html::a('<i class="fas fa-table"></i>', $url, [
                            'answer' => Yii::t('app', 'answer')
                        ]);
                    },
                ],
                // 'urlCreator' => function ($action, $model, $key, $index) {
                //     if ($action === 'answer') {
                //         $url = Url::to(['admin/answer', 'id' => $index + 1]); // your own url generation logic
                //         return $url;
                //     }
                // }
            ],
        ],
    ]); ?>


</div>