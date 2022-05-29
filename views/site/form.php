
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use dosamigos\chartjs\ChartJs;
use practically\chartjs\Chart;
use yii\widgets\ActiveForm;
// use yii\bootstrap\ActiveForm;
$required = false;
?>

<div class="container">
    <div class="row d-flex justify-content-left py-5">
        <div class="col-lg-6 pr-0">
            <div class="section-tittle">
                <h2><?php echo $formlist['FORMLISTTITLE']; ?></h2>
            </div>
        </div>
    </div>
    <?=  Html::beginForm(['answer','formID' => $formID, 'formTitle' => $formlist['FORMLISTTITLE']], 'post', ['data-toggle' => 'validator', 'novalidate' => 'true']);
        foreach($data as $row) :?>
            <?php if($row['ID_TYPE'] == 1 || $row['ID_TYPE'] == 2){?>
                <?php if($row['ID_TYPE'] == 1){?>
                    <div class="card mb-3 p-5">
                        <div class="card-body">
                            <h3 class="card-title"><?= Html::encode($row['NAME'])?></h3>
                            <p class="card-text"><?= Html::encode($row['DESCRIPTION'])?></p>
                            <div class="form-row">
                                <div class="col">
                                    <div class="md-form mt-0">
                                        <?php if(html::encode($row['REQUIRED'])==1){
                                            $required = true;
                                        }else{
                                            $required = false;
                                        } ?>
                                        <?= Html::input('text',Html::encode($row['ID']),'', ['class' => 'form-control', 'required'=>$required, ])?>
                                        <div class="invalid-feedback">
                                            Please choose a username.
                                        </div>
                                    </div>
                                </div>
                                <div class="col"></div>
                            </div>
                        </div>
                    </div>
                <?php } else if($row['ID_TYPE'] == 2){?>
                    <div class="card mb-3 p-5">
                        <div class="card-body">
                            <h3 class="card-title"><?= Html::encode($row['NAME'])?></h3>
                            <p class="card-text"><?= Html::encode($row['DESCRIPTION'])?></p>
                            <div class="md-form form-lg">
                                <?php if(html::encode($row['REQUIRED'])==1){
                                    $required = true;
                                }else{
                                    $required = false;
                                } ?>
                                <?= Html::input('text',Html::encode($row['ID']),'', ['class' => 'form-control form-control-lg', 'required'=>$required, 'data-error' => "Field cannot blank"])?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else if($row['ID_TYPE'] == 6){?>
                <div class="card mb-3 p-5">
                    <div class="card-body">
                        <h3 class="card-title"><?= Html::encode($row['NAME'])?></h3>
                        <p class="card-text"><?= Html::encode($row['DESCRIPTION'])?></p>
                            <?php if(html::encode($row['REQUIRED'])==1){
                                $required = true;
                            }else{
                                $required = false;
                            } ?>
                            <?php for ($x = 1; $x <= 5; $x++) { ?>
                                <input type="radio" class="input sm" id="<?= Html::encode($row['ID'])?>" name="<?= Html::encode($row['ID'])?>" value="<?= Html::encode($x)?>" required />
                                <label class="pr-5" for=""><?php echo $x ?></label>
                            <?php } ?>
                    </div>
                </div>
            <?php } else  {?>
                <div class="card mb-3 p-5">
                    <div class="card-body">
                        <?php if(html::encode($row['REQUIRED'])==1){
                            $required = true;
                        }else{
                            $required = false;
                        } ?>
                        <h3 class="card-title">
                            <?php 
                                if($required) echo Html::encode($row['NAME'])." <span style='color: red;'>*</span>";
                                else echo Html::encode($row['NAME']);
                            ?>
                        </h3>
                        <p class="card-text"><?= Html::encode($row['DESCRIPTION'])?></p>
                        <?php if($row['ID_TYPE'] == 3){?>
                            <?php foreach($value as $tmp) : ?>
                                <?php 
                                    if($tmp['ID'] == $row['ID']){?>
                                        <!-- <div class="custom-control"> -->
                                            <input type="radio" id="<?= Html::encode($row['ID'])?>" value="<?= Html::encode($tmp['VAL'])?>"name="<?= Html::encode($row['ID'])?>[]" required />
                                            <label for=""><?= Html::encode($tmp['VAL'])?></label><br>
                                        <!-- </div> -->
                                <?php }?>
                            <?php endforeach;Html::endForm()?>
                        <?php } 
                        else if($row['ID_TYPE'] == 4){ ?>
                            <?php foreach($value as $tmp) : ?>
                                <?php 
                                    if($tmp['ID'] == $row['ID']){?>
                                        <!-- <div class="custom-control"> -->
                                            <input type="checkbox" id="<?= Html::encode($row['ID'])?>" value="<?= Html::encode($tmp['VAL'])?>"name="<?= Html::encode($row['ID'])?>[]" required />
                                            
                                            <?php if(Html::encode($tmp['VAL'])=='Other'){ ?>
                                                <label for=""><?= Html::input('text',Html::encode($row['ID']),'', ['class' => 'form-control', 'required'=>$required, 'placeholder' => "Other", ])?></label><br>
                                            <?php }else{ ?>
                                                <label for=""><?= Html::encode($tmp['VAL'])?></label><br>
                                            <?php } ?>
                                        <!-- </div> -->
                                <?php }?>
                            <?php endforeach;Html::endForm()?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php endforeach;?>
    <?= Html::submitButton('Submit Your Answer', ['class' => 'btn btn-primary mb-3']) ?>
    </form>
</div>