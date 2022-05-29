<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/animate.min.css',
        'css/bootstrap.min.css',
        'css/flaticon.css',
        'css/fontawesome-all.min.css',
        'css/gijgo.css',
        'css/magnific-popup.css',
        'css/nice-select.css',
        'css/owl.carousel.min.css',
        'css/responsive.css',
        'css/slick.css',
        'css/slicknav.css',
        'css/style.css',
        'css/themify-icons.css',
        'css/mdb.min.css',
    ];
    public $js = [
        'js/vendor/modernizr-3.5.0.min.js',
        'js/vendor/jquery-1.12.4.min.js',
        'js/popper.min.js',
        'js/bootstrap.min.js',
        'js/jquery.slicknav.min.js',
        'js/owl.carousel.min.js',
        'js/slick.min.js',
        'js/gijgo.min.js',
        'js/wow.min.js',
        'js/animated.headline.js',
        'js/jquery.magnific-popup.js',
        'js/jquery.scrollUp.min.js',
        'js/jquery.nice-select.min.js',
        'js/jquery.sticky.js',
        'js/contact.js',
        'js/jquery.form.js',
        'js/jquery.validate.min.js',
        'js/mail-script.js',
        'js/jquery.ajaxchimp.min.js',
        'js/plugins.js',
        'js/main.js',
        'js/mdb.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
