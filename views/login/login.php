<?php

//登陆操作首页
use app\assets\LoginAsset;
use yii\helpers\Html;
use yii\captcha\Captcha;

LoginAsset::register($this);

//$jsfile = '@web/js/jquery-1.12.4.min.js';
//////引入css 文件 等
//AppAsset::addCss($this, $cssfile);
$this->title = '推广精灵';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="wrap">
            <nav class="navbar navbar-inverse">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">菜单</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#" style="padding: 0px !important;">
                            <img src="/public/image/system.png" id="system-png">
                        </a>
                        <a><img src="/public/image/logo.png" style="padding-top: 3px"></a>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <!--                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                            <ul class="nav navbar-nav">
                                                <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                                                <li><a href="#">Link</a></li>
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">Action</a></li>
                                                        <li><a href="#">Another action</a></li>
                                                        <li><a href="#">Something else here</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">Separated link</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">One more separated link</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <form class="navbar-form navbar-left" role="search">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Search">
                                                </div>
                                                <button type="submit" class="btn btn-default">Submit</button>
                                            </form>
                                            <ul class="nav navbar-nav navbar-right">
                                                <li><a href="#">Link</a></li>
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">Action</a></li>
                                                        <li><a href="#">Another action</a></li>
                                                        <li><a href="#">Something else here</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">Separated link</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>-->
                    <!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <div class="container login-container">
            <div class="row">
                <div class="col-sm-8 col-xs-12">
                    <div class="jumbotron">
                        <h3>Hello, world!</h3>
                        <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12 login-right-container">
                    <div>
                        <form action="<?php echo Yii::$app->urlManager->createUrl('login/login'); ?>" method="post" class="form-signin">
                            <fieldset>
                                <legend>
                                    <h3>
                                        登录
                                        <small> 霸屏精灵 - PASCREEN</small>
                                    </h3>
                                </legend>
                                <div class="alert alert-danger" role="alert"><?php echo $msg;?></div>
                                <label for="user_name" class="sr-only">用户名</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="用户名" value="" required="" autofocus="true">
                                <label for="inputPassword" class="sr-only">密码</label>
                                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="密码" value="" required="">
                                <br>
                                <input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?= \Yii::$app->request->getCsrfToken(); ?>">
                                <!--分成两个左右部分-->
                                <div class="row">
                                    <div class="col-md-4 col-xs-4">
                                        <input type="text" name="verifyCode" id="verifyCode" class="form-control" placeholder="验证码" required="">
                                    </div>
                                    <div class="col-md-8 col-xs-8">
                                        <?php
                                        echo Captcha::widget(
                                                ['name' => 'captchaimg',
                                                    'captchaAction' => 'login/captcha',
                                                    'imageOptions' => ['id' => 'captchaimg', 'title' => '换一个', 'alt' => '换一个', 'style' => 'cursor:pointer;'],
                                                    'template' => '{image}']);
                                        ?>
                                    </div>
                                </div>
                                <div class="row remember">
                                    <div class="col-md-6 col-xs-6">
                                        <button type="submit" class="btn btn-primary" aria-label="Left Align">
                                            <span class="glyphicon glyphicon-user" aria-hidden="true">&nbsp;登录
                                            </span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <input name="rememberMe" type="checkbox" value="1" checked="">
                                        记住我
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="row login-bottom">
                        <div class="col-sm-12">
                            <p><a href="">霸屏精灵</a> © 赵兴壮 </p>
                            <p>
                                <small>
                                    <a href="" target="_blank">使用帮助
                                    </a>
                                    <span class="muted">·</span>
                                    <a href="" target="_blank">技术支持</a>
                                    <span class="muted">·</span>
                                    <a href="" target="_blank">联系我们</a>
                                    <span class="muted">·</span>
                                    <a href="/index.php/Home/Interview/index.html" target="_blank">面试填写</a>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
<!--                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
                <p class="pull-right"><?= Yii::powered() ?></p>-->
            </div>
        </footer>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>