<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use app\models\FormQuestion;
use app\models\FormQuestionType;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $modelFormList app\models\FormList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-list-form">
    <?php $form = ActiveForm::begin(['id' => 'form-list', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($modelFormList, 'FORMLISTTITLE')->textInput(['maxlength' => true]) ?>
    <!-- <?= $form->field($modelFormList,'FORMLISTDATE')->widget(DatePicker::className(),[
        'language' => 'en',
        'dateFormat' => 'YYYY-MM-DD',
    ]) ?> -->

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.question-item',
        'limit' => 20,
        'min' => 1,
        'insertButton' => '.add-question',
        'deleteButton' => '.remove-question',
        'model' => $modelsFormQuestion[0],
        'formId' => 'form-list',
        'formFields' => [
            'FORMQUESTIONID',
            'FORMQUESTIONNAME',
            'FORMQUESTIONTYPEID',
            'FORMREQUIRED',
            'FORMDESCRIPTION',
            'FORMIMAGE',
        ],
    ]); ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Question</th>
                <th class="text-center" style="width: 90px;">
                    <button type="button" class="add-question btn btn-success btn-xs" style="padding: 1px 3px"><span class="fas fa-plus"></span></button>
                </th>
            </tr>
        </thead>
        <tbody class="container-items">
            <?php foreach ($modelsFormQuestion as $indexFormQuestion => $modelFormQuestion): ?>
                <tr class="question-item">
                    <td class="vcenter">
                        <?php
                            // For Update
                            if (!$modelFormQuestion->isNewRecord) {
                                echo Html::activeHiddenInput($modelFormQuestion, "[{$indexFormQuestion}]FORMQUESTIONID");
                            }
                        ?>
                        <?= $form->field($modelFormQuestion, "[{$indexFormQuestion}]FORMQUESTIONNAME")->textInput(['maxlength' => true]) ?>
                        <?= $form->field($modelFormQuestion, "[{$indexFormQuestion}]FORMQUESTIONTYPEID")->dropDownList(
                            ArrayHelper::map(FormQuestionType::find()->all(), 'FORMQUESTIONTYPEID', 'FORMQUESTIONTYPENAME'), [
                                'prompt' => 'Select Question Type',
                                'onChange' => '
                                    renderInputType(this); 
                                    function renderInputType(data) {
                                        if(data.value == 3 || data.value == 4) {
                                            $(data).parent().siblings(".dynamicform_inner").show();
                                            var fixHelperSortable = function(e, ui) {
                                                ui.children().each(function() {
                                                    $(this).width($(this).width());
                                                });
                                                return ui;
                                            };
                                            $(".container-options").sortable({
                                                items: "tr",
                                                cursor: "move",
                                                opacity: 0.6,
                                                axis: "y",
                                                handle: ".sortable-handle",
                                                helper: fixHelperSortable,
                                                update: function(ev){
                                                    $(".dynamicform_inner").yiiDynamicForm("updateContainer");
                                                }
                                            }).disableSelection();
                                            $(".container-options").sortable("refresh");
                                        } else {
                                            $(data).parent().siblings(".dynamicform_inner").hide();
                                            $(data).parent().siblings(".dynamicform_inner").find("tr.option-item").next("tr").remove();
                                            $(data).parent().siblings(".dynamicform_inner").find("input").each(function() {
                                                $(this).val("");
                                            });
                                        }
                                    }'
                        ]) ?>
                        <?= $form->field($modelFormQuestion, "[{$indexFormQuestion}]FORMDESCRIPTION")->textInput(['maxlength' => true]) ?>
                        <?php echo $form->field($modelFormQuestion, "[{$indexFormQuestion}]FORMIMAGE")->fileInput() ?>
                        <?php //echo $form->field($modelFormQuestion, "[{$indexFormQuestion}]FORMIMAGE")->textInput(['maxlength' => true]) ?>
                        <?= $form->field($modelFormQuestion, "[{$indexFormQuestion}]FORMREQUIRED")->checkbox(['data-toggle' => 'toggle', 'data-onstyle' => 'primary']); ?>
                        <?= $this->render('_option', [
                            'form' => $form,
                            'indexFormQuestion' => $indexFormQuestion,
                            'modelsFormQuestionOption' => $modelsFormQuestionOption[$indexFormQuestion],
                        ]) ?>
                    </td>
                    <td class="text-center vcenter" style="width: 90px; verti">
                        <button type="button" class="remove-question btn btn-danger btn-xs" style="padding: 1px 3px"><span class="fas fa-minus"></span></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton($modelFormList->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>

<?php
    // Sumber: 
    //     Toggle Button CSS: https://www.youtube.com/watch?v=BQSNBa3gZJU
    $css = <<< CSS
        input[type='checkbox'] {
            position: absolute;
            width: 40px;
            height: 20px;
            -webkit-appearance: none;
            background: #c6c6c6;
            outline: none;
            border-radius: 20px;
            box-shadow: inset 0 0 5px rgba(0,0,0,2);
        }
        input:checked[type='checkbox'] {
            background: #03a9f4;
        }
        input[type='checkbox']:before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 20px;
            top: 0;
            left: 0;
            background: #fff;
            transform: scale(1.1);
            box-shadow: 0 2px 5px rgba(0,0,0,2);
            transition: .5s;
        }
        input:checked[type='checkbox']:before {
            left: 20px;
        }
    CSS;
    $this->registerCss($css);

    $script = <<< JS
        //
    JS;
    $this->registerJs($script);
?>