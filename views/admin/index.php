<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\FormList;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FormListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Home';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-list-index">

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
            // // 'FORMLISTTITLE',
            // 'FORMLISTDATE',
            // 'FORMLISTTOTALSECTION',
            // 'FORMLISTTOTALQUESTION',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' =>  function($url,$model) {
                        return Html::a('<i class="fas fa-edit"></i>', Url::to(['admin/update', 'id' => $model->FORMLISTID]), [
                            'title' => Yii::t('app', 'update')
                        ]);
                    },
                    'view' =>  function($url,$model) {
                        return Html::a('<i class="fas fa-eye"></i>', Url::to(['admin/view', 'id' => $model->FORMLISTID]), [
                            'title' => Yii::t('app', 'view')
                        ]);
                    },
                    'delete' => function($url,$model) {
                        return Html::a('<i class="fas fa-trash"></i>', Url::to(['admin/delete', 'id' => $model->FORMID]), [
                            'title' => Yii::t('app', 'delete')
                        ]);
                    }
                 ]
            ],
        ],
    ]); ?>


</div>
