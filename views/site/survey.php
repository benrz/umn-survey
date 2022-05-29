<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->title = 'UMN SURVEY';
?>

<main>
    <!-- Visit Stuffs Start -->
    <div class="visit-area fix visite-padding" style="padding-top: 60px;">
        <div class="container">
            <!-- Section-tittle -->
            <div class="row d-flex justify-content-left">
                <div class="col-lg-6 pr-0">
                    <div class="section-tittle">
                        <h2 class="mb-5">List Of Survey</h2>
                    </div>
                </div>
            </div>
            <div class="input-group mb-4 row d-flex">
                <input id="searchbar" type="text" class="form-control" placeholder="Survey name">
                <div class="header-right-btn f-right d-none d-lg-block">
                    <button class="btn header-btn" type="button" onclick="searchForm()">Search</button>
                </div>
            </div>
        </div>
        <div class="container-fluid p-0">
            <div class="row ">
                <?php foreach($data as $form) :?>
                    <div id="formItem" class="col-lg-3 col-md-4">
                        <div class="single-visited mb-30">
                            <div class="visited-img">
                                <img src="assets/img/visit/visit_1.jpg" alt="">
                            </div>
                            <div class="visited-cap">
                            <?php 
                                if(strlen($form->formlist->FORMLISTTITLE) > 15){
                                    $title = substr($form->formlist->FORMLISTTITLE, 0, 15)." ...";
                                }
                                else{
                                    $title = $form->formlist->FORMLISTTITLE;
                                }
                            ?>
                                <h3><a style='font-size: 20px;'  title="<?=$form->formlist->FORMLISTTITLE?>" href="<?= Url::toRoute(['site/form', 'formID' => $form->FORMID])?>"><?= $title."<br>".$form->FORMDATESTART?></a></h3>
                                <!-- <p>Email Marketing</p> -->
                            </div>
                        </div>
                    </div> 
                <?php endforeach;?>
            </div>
        </div>
    </div>
    <!-- Visit Stuffs Start -->
</main>
<script>
    function searchForm() { 
        let container = document.getElementsByClassName('col-lg-3 col-md-4'); 
        let input = document.getElementById('searchbar').value.toLowerCase();
        let x = document.getElementsByClassName('visited-cap'); 
        
        for (i = 0; i < x.length; i++) {  
            if (!x[i].innerHTML.toLowerCase().includes(input)) { 
                container[i].style.display="none"; 
            } 
            else { 
                container[i].style.display="flex";                  
            } 
        } 
    } 
</script>