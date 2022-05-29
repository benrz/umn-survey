<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\UserJob;

    $this->title = $formQuestion->FORMQUESTIONNAME;
    $labels = array();
    $data = array();
    $bgColor = array();
    $bdColor = array();
    $datasets = array();
    $clientOptions = array();

    $backgroundColor = array(
        "rgba(255,0,0,0.2)", "rgba(0,255,0,0.2)", "rgba(0,0,255,0.2)", 
        "rgba(255,255,0,0.2)", "rgba(0,255,255,0.2)", "rgba(255,0,255,0.2)", 
        "rgba(192,192,192,0.2)", "rgba(128,0,0,0.2)", "rgba(0,128,0,0.2)");
    $borderColor = array(
        "rgba(255,0,0,1)", "rgba(0,255,0,1)", "rgba(0,0,255,1)", 
        "rgba(255,255,0,1)", "rgba(0,255,255,1)", "rgba(255,0,255,1)", 
        "rgba(192,192,192,1)", "rgba(128,0,0,1)", "rgba(0,128,0,1)");
    $type = null;

    $labels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    for($x = 0; $x < sizeof($keys); $x++ ) { 
        for($y = 0; $y < sizeof($month_keys); $y++){
            array_push($data, $countArray[$keys[$x]][$month_keys[$y]]);
        }
        // $data[$x] = $countArray[$keys[$x]];
        $bgColor[$x] = $backgroundColor[$x];
        $bdColor[$x] = $borderColor[$x];

        $datasets[$x] = 
        [
            'label' => $keys[$x],
            'backgroundColor' => $backgroundColor[$x],
            'borderColor' => $borderColor[$x],
            'pointBackgroundColor' => "rgba(179,181,198,1)",
            'pointBorderColor' => "#fff",
            'pointHoverBackgroundColor' => "#fff",
            'pointHoverBorderColor' => "rgba(179,181,198,1)",
            'data' => $data
        ];

        $data = [];
    }

    ///////////////////////////////////
    //          DEBUGGING            //
    ///////////////////////////////////
    //
    // echo "<pre>";
    // echo "formQuestion <br>";
    // print_r($formQuestion);
    // echo "formQuestionOption <br>";
    // print_r($formQuestionOption);
    // echo "labels <br>";
    // print_r($labels);
    // echo "data <br>";
    // print_r($data);
    // echo "datasets <br>";
    // print_r($datasets);
    // echo "countArray <br>";
    // print_r($countArray);
    // echo "keys <br>";
    // print_r($keys);
    // echo "</pre>";
    //
    ///////////////////////////////////
?>
    
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'chart', 'options' => ['enctype' => 'multipart/form-data']]); ?>
        <?php 
            echo "<br><strong>Choose at least one of the option: </strong><br><br>";
            // Kalau data sudah ada di DB, cek apakah checkbox di checked atau ga. Kalau iya, default di checked, vice versa.
            if($isPublished != NULL){
                if($isPublished->STAFF == 1)
                    $modelFormPublish->STAFF = 1;
                if($isPublished->LECTURER == 1)
                    $modelFormPublish->LECTURER = 1;
                if($isPublished->STUDENT == 1)
                    $modelFormPublish->STUDENT = 1;
                if($isPublished->PUBLICS == 1)
                    $modelFormPublish->PUBLICS = 1;
            }
        ?>
        <?= $form->field($modelFormPublish, 'STAFF')->checkbox() ?>
        <?= $form->field($modelFormPublish, 'LECTURER')->checkbox() ?>
        <?= $form->field($modelFormPublish, 'STUDENT')->checkbox() ?>
        <?= $form->field($modelFormPublish, 'PUBLICS')->checkbox() ?>
        <?= $form->field($modelFormPublish, 'FORMQUESTIONID')->hiddenInput(['value'=> $formQuestion->FORMQUESTIONID])->label(false) ?>
        <?= $form->field($modelFormPublish, 'FORMID')->hiddenInput(['value'=> $formID])->label(false) ?>
        
        <?= Html::submitButton('Publish Chart', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    <?= "<br>".Html::a('Back', ['answer', 'id' => $formID], ['class' => 'btn btn-danger'])."<br><br><br>" ?>
    
    <?=
        $chart = ChartJs::widget([
            'type' => 'line',
            // 'options' => [
            //     'height' => 200,
            //     'width' => 200,
            // ],
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
            'clientOptions' =>  $clientOptions,
        ]);
    ?>
