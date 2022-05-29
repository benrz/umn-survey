<?php

/* @var $this \yii\web\View */
/* @var $content string */

// use app\widgets\Alert;
use yii\helpers\Html;
// use yii\bootstrap\Nav;
// use yii\bootstrap\NavBar;
// use yii\widgets\Breadcrumbs;
// use app\assets\AppAsset;
use app\assets\HomeAsset;
use yii\helpers\Url;

// AppAsset::register($this);
HomeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= Url::toRoute('assets/img/favicon.ico')?>">

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header>
    <!-- Header Start -->
    <div class="header-area header-transparrent ">
        <div class="main-header header-sticky">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-md-1">
                        <div class="logo">
                            <a href="<?= Url::toRoute('site/index')?>"><img src="assets/img/logo/umnsurveylogo.png" height="38" width="123" alt=""></a>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8">
                        <!-- Main-menu -->
                        <div class="main-menu f-right d-none d-lg-block">
                            <nav> 
                                <ul id="navigation">    
                                    <li><a href="<?= Url::toRoute('site/index')?>"> Home</a></li>
                                    <li><a href="<?= Url::toRoute('site/survey')?>">Survey</a></li>
                                    <li><a href="<?= Url::toRoute('site/index')?>#graph">Graph</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>             
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        <div class="header-right-btn f-right d-none d-lg-block">
                            <?php
                                if(Yii::$app->session->get('role') != NULL){
                                    echo "<a href='".Url::toRoute('login/logout')."' class='btn header-btn'>Logout</a>";
                                }
                                else{
                                    echo "<a href='".Url::toRoute('login/index')."' class='btn header-btn'>Login</a>";
                                }
                            ?>
                        </div>
                    </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>

<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer>
<div class="footer-main" data-background="assets/img/shape/footer_bg.png">
    <div class="footer-area footer-padding">
        <div class="container">
            <div class="row d-flex justify-content-between">
                <div class="col-lg-2 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>QUICK LINKS</h4>
                            <ul>
                                <li><a href="https://tv.umn.ac.id/">UMN TV</a></li>
                                <li><a href="https://radio.umn.ac.id/">UMN RADIO</a></li>
                                <li><a href="https://www.umn.ac.id/aktifitas-mahasiswa/">AKTIFITAS</a></li>
                                <li><a href="https://www.umn.ac.id/en/calendar/">KALENDER</a></li>
                                <li><a href="https://www.umn.ac.id/pemesanan-jas-almamater-umn/">JAS UMN</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-7">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>OTHER LINKS</h4>
                            <ul>
                                <li><a href="#">STUDENT</a></li>
                                <li><a href="#">STAFF</a></li>
                                <li><a href="#">ALUMNI</a></li>
                                <li><a href="#">CAREER</a></li>
                                <li><a href="#">DORMITORY</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4 class="mb-4">INFORMASI PENDAFTARAN</h4>
                            <ul>
                                <li style="color: #707b8e;">Email : <a href="#">admisi@umn.ac.id</a></li>
                                <li style="color: #707b8e;">Format : nama, alamat, no.telp, jurusan, dan keterangan</li>
                                
                            </ul>
                            <h4 class=" pt-5 mb-4">INFORMASI UMN</h4>
                            <ul>
                                <li style="color: #707b8e;">Email : <a href="#">marketing@umn.ac.id</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-8">
                    <div class="single-footer-caption mb-50">
                        <div class="single-footer-caption mb-30">
                            <!-- logo -->
                            <div class="footer-logo">
                                <a href="index.html"><img src="assets/img/logo/umnlogo.png" height="55" width="123" alt=""></a>
                            </div>
                            <div class="footer-tittle">
                                <div class="footer-pera">
                                    <p class="info1">Universitas Multimedia Nusantara Jl. Scientia Boulevard, Gading Serpong, Tangerang, Banten-15811 Indonesia</p>
                                    <p class="info2">
                                        (T)+62-21.5422.0808</br>
                                        (F)+62-21.5422.0800</br>
                                        e-mail: marketing@umn.ac.id
                                    </p>
                            </div>
                            </div>
                            <div class="footer-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-globe"></i></a>
                            <a href="#"><i class="fab fa-behance"></i></a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</footer>
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
