<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
// use yii\widgets\Breadcrumbs;
use app\assets\DashboardAsset;
DashboardAsset::register($this);

// use kartik\icons\FontAwesomeAsset;
// FontAwesomeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <nav id="sidebar" class="sidebar">
		<div class="sidebar-content ">
			<a class="sidebar-brand" href="#"><i class="align-middle" data-feather="box"></i><span class="align-middle">UMN  SURVEY</span></a>
            <ul class="sidebar-nav">
                <li class="sidebar-item active"><?= Html::a('Home', ['admin/index'], ['class' => 'sidebar-link']) ?></li>
                <li class="sidebar-item"><?= Html::a('Create New', ['admin/create'], ['class' => 'sidebar-link']) ?></li>
                <li class="sidebar-item"><?= Html::a('Create Using Template', ['admin/template'], ['class' => 'sidebar-link']) ?></li>
                <li class="sidebar-item"><?= Html::a('Spread Out', ['admin/spread'], ['class' => 'sidebar-link']) ?></li>
                <li class="sidebar-item"><?= Html::a('Result', ['admin/result'], ['class' => 'sidebar-link']) ?></li>
                <li class="sidebar-item"><?= Html::a('Log Out', ['login/logout'], ['class' => 'sidebar-link']) ?></li>
            </ul>
		</div>
	</nav>

    <div class="main">
		<nav class="navbar navbar-expand navbar-light bg-white">
            <a class="sidebar-toggle d-flex mr-2"><i class="hamburger align-self-center"></i></a>
		</nav>
		<main class="content">
            <?= $content ?>
		</main>
	</div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
