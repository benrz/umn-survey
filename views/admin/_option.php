<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use app\models\FormQuestionOption;

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-options',
    'widgetItem' => '.option-item',
    'limit' => 20,
    'min' => 1,
    'insertButton' => '.add-option',
    'deleteButton' => '.remove-option',
    'model' => $modelsFormQuestionOption[0],
    'formId' => 'form-list',
    'formFields' => [
        'FORMQUESTIONOPTIONID',
        'FORMQUESTIONVALUE',
    ],
]); ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 90px; text-align: center"></th>
            <th>Option</th>
            <th class="text-center">
                <button type="button" class="add-option btn btn-success btn-xs" style="padding: 1px 3px"><span class="fas fa-plus"></span></button>
                <button type="button" class="option-other btn btn-warning btn-xs" style="padding: 1px 3px"><span class="fas fa-ellipsis-v"></span></button>
            </th>
        </tr>
    </thead>
    <tbody class="container-options">
        <?php foreach ($modelsFormQuestionOption as $indexFormQuestionOption => $modelFormQuestionOption): ?>
            <tr class="option-item">
                <td class="sortable-handle text-center vcenter" style="cursor: move;">
                    <i class="glyphicon glyphicon-sort"></i>
                </td>
                <td class="vcenter">
                    <?php
                        // For Update
                        if (!$modelFormQuestionOption->isNewRecord) {
                            echo Html::activeHiddenInput($modelFormQuestionOption, "[{$indexFormQuestion}][{$indexFormQuestionOption}]FORMQUESTIONOPTIONID");
                        }
                    ?>
                    <?= $form->field($modelFormQuestionOption, "[{$indexFormQuestion}][{$indexFormQuestionOption}]FORMQUESTIONVALUE")->textInput(['maxlength' => true]) ?>
                </td>
                <td class="text-center vcenter" style="width: 90px;">
                    <button type="button" class="remove-option btn btn-danger btn-xs" style="padding: 1px 3px"><span class="fas fa-minus"></span></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php DynamicFormWidget::end(); ?>

<?php
    $css = <<< CSS
        .dynamicform_inner {
            display: none;
        }
    CSS;
    $this->registerCss($css);

    $script = <<< JS
         $("body").on("click", "button.option-other", function() {
            console.log("Deleted item!");    
            other = $(this).parent().parent().parent().siblings(".container-options").find("tr:first").clone();
            console.log(other);
            other.find("input:text").val("Other").attr("readonly", true);
            $(this).parent().parent().parent().siblings(".container-options").append(other);
        });
        $(".option-item input").each(function(){
            if($(this).val().length != 0) {
                $(this).parent().parent().parent().parent().parent().parent().show();
            }
        });
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
    JS;
    $this->registerJs($script);
?>