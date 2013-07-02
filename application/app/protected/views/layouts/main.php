<?php

$controller = $this->uniqueId;
$action = $this->action->Id;
$themepath = Yii::app()->basePath . "/.." . Yii::app()->theme->baseUrl;
$timestamp = time();
$token = md5(Yii::app()->params["uploadsalt"] . $timestamp . Yii::app()->user->id . Yii::app()->session->sessionId);

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php if($this->isMobile() && !$this->isTablet()){ ?>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<?php }else if ($this->isTablet()){ ?>
		 <meta content="initial-scale=.75, maximum-scale=.75, user-scalable=0" name="viewport" />
<? } ?>

        <meta charset="utf-8">

        <meta name="description" content="" />
        <meta name="language" content="en" />

        <meta property="og:image"       content="http://<?=Yii::app()->params["url"];?><?=Yii::app()->params["logo"];?>" />
        <meta property="og:url"         content="http://<?=Yii::app()->params["url"];?>" />
        <meta property="og:title"       content="<?=Yii::app()->name;?>" />
        <meta property="og:site_name"   content="Grammy Amplifier" />
        <meta property="og:description" content="GRAMMY Amplifer is a way for musicians to share their music and get a shot at being heard by some of the biggest artists in the world. It's easy to get started. You could be discovered by one of our Amplifier Curators and have your track tweeted out to millions of fans." />
        
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

        <!-- jQuery -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/jquery.js"></script>

        <!-- Foundation -->
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl;?>/css/foundation/foundation.css" />
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/foundation/modernizr.foundation.js"></script>
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/foundation/foundation.min.js"></script>

        <!-- Uniform -->
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl;?>/plugins/uniform/css/uniform.agent.css" />
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/uniform/jquery.uniform.min.js"></script>

        <!-- JW Player -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/jwplayer/jwplayer.js" ></script>
        <script type="text/javascript">jwplayer.key="USpaoOgCgSoJd5sadoOoLC88tkK0iRdU40iTrg==";</script>

        <!-- Global Scripts -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/global.js"></script>

        <!-- Artist Scripts -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/artists.js"></script>

        <!-- Global CSS -->
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl;?>/css/global.css" />

        <!-- IE Fix for HTML5 Tags -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Popup Window Plugin -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/jquery.popupWindow.js"></script>

        <!-- Hover Intent Plugin -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/jquery.hoverIntent.min.js"></script>

        <!-- Easing Plugin -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/jquery.easing.js"></script>

        <!-- Cycle Plugin -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/jquery.cycle.lite.js"></script>

        <!-- Slider Plugin -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/jscslider/jscslider.js"></script>
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl;?>/css/slider.css" />

        <!-- FancyBox Plugin -->
        <link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl;?>/plugins/fancybox/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/fancybox/jquery.fancybox.pack.js?v=2.1.3"></script>

        <!-- InView -->
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/jquery.inview.min.js"></script>

        <!-- Media Element JS -->
        <link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl;?>/plugins/mediaelement/mediaelementplayer.min.css" type="text/css" />
        <link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl;?>/css/player.css" type="text/css" />
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/plugins/mediaelement/mediaelement-and-player.min.js"></script>

        <!-- Controller and Action specific CSS and Scripts -->
<? if(file_exists($themepath . "/js/$controller-$action-mobile.js") && $this->isMobile() && !$this->isTablet()){ ?>
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/<?=$controller;?>-<?=$action;?>-mobile.js" ></script>
<? } elseif (file_exists($themepath . "/js/$controller-$action.js")){ ?>
        <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/<?=$controller;?>-<?=$action;?>.js" ></script>
<? } ?>

<? if(file_exists($themepath . "/css/$controller-$action-mobile.css") && $this->isMobile() && !$this->isTablet()){ ?>
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl;?>/css/<?=$controller;?>-<?=$action;?>-mobile.css" />
<? } elseif(file_exists($themepath . "/css/$controller-$action.css")){ ?>
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl;?>/css/<?=$controller;?>-<?=$action;?>.css" />
<? } ?>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <script type="text/javascript" >
            var themePath = '<?=Yii::app()->theme->baseUrl;?>';
            var clientId = '<?=Yii::app()->params["soundcloud"]["clientId"];?>';
            var timestamp = '<?=$timestamp;?>';
            var token = '<?=$token;?>';
        </script>

    </head>

    <body id="<?=$controller;?>-<?=$action;?>" class="<?php echo ($this->isMobile() ? ($this->isTablet() ? 'tablet' : 'phone') : 'computer'); ?>">

        <div id="header">
            <div class="inner">
                <div id="topbar">
                    <div class="toggle">
                        <img src="<?=Yii::app()->theme->baseUrl;?>/img/header_nav_toggle_2x.png" />
                    </div>
                     <?php if(!$this->isMobile()){ ?>
                    <div class="update">
                        <a href="/app/about"><img src="<?=Yii::app()->theme->baseUrl;?>/img/badge_update.png" /></a>
                    </div>
                     <?php } ?>
                    <div class="logo">
                        <a href="/"><img src="<?=Yii::app()->theme->baseUrl;?>/img/header_logo_2x.png" /></a>
                    </div>

                    <div class="social">
                        <a href="http://www.twitter.com/thegrammys" class="twitter" target="_blank"><img src="<?=Yii::app()->theme->baseUrl;?>/img/topbar_twitter.png"  /></a>
                        <a href="http://www.facebook.com/thegrammys" class="facebook" target="_blank"><img src="<?=Yii::app()->theme->baseUrl;?>/img/topbar_facebook.png"  /></a>
                     </div>

                    <div class="controls">
                        <? if(Yii::app()->user->isGuest){ ?>
                        <div class="signin menubutton">
                            <a href="/app/connect"><img src="<?=Yii::app()->theme->baseUrl;?>/img/pixel.png"  /></a>
                        </div>
                        <? } else { ?>
                        <div class="signout menubutton">
                            <a href="/app/logout"><img src="<?=Yii::app()->theme->baseUrl;?>/img/pixel.png" /></a>
                        </div>
                        <div class="settings menubutton">
                            <a href="/app/control-panel" class="settings"><img src="<?=Yii::app()->theme->baseUrl;?>/img/pixel.png" /></a>
                        </div>
                        <? } ?>
                    </div>
                </div>
                <div id="menu">
                    <div class="inner">
                        <ul>
                            <li><a href="/">HOME</a></li>
                            <li><a href="/app/artists">ARTISTS</a></li>
                            <li><a href="/app/curators">CURATORS</a></li>
                            <li><a href="/app/about">ABOUT</a></li>
                        </ul>
                    </div>
                    <div class="centerstagebutton">
                    	<img src="<?=Yii::app()->theme->baseUrl;?>/img/button_centerstage.png" />
                    </div>
                     <div class="clear"></div>

                </div>
               
            </div>
        </div>

        <div id="background">
            <div class="row" id="content">
                <?php echo $content; ?>
                <div class="clear"></div>
            </div>
        </div>

        <div class="row" id="footer">
            <img src="<?=Yii::app()->theme->baseUrl;?>/img/footer_amp_logo.png" alt="WIL logo"><br/>
            <a href="/app/rules">OFFICIAL RULES</a> - <a href="http://www.grammy.com/legal#privacy" target="_blank">PRIVACY&nbsp;POLICY</a> - <a href="/app/terms-and-conditions">TERMS OF USE</a> - <a href="/app/copyright">COPYRIGHT NOTICE</a> - &copy; 2013 THE RECORDING ACADEMY.  ALL RIGHTS RESERVED.
            <br/><a target="_blank" href="http://www.soundcloud.com"><img src="<?=Yii::app()->theme->baseUrl;?>/img/soundcloud_logo_white.png" /></a>
        </div>

         <div id="exit_modals">
            <div id="exit_modal"><div class="visitbutton"><a target="_blank" href="http://www.hyundaicenterstage.com/"><img src="<?=Yii::app()->theme->baseUrl;?>/img/exit_modal_button_centerstage.png" width="147" height="30"></a></div></div>
        </div>

        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '<?=Yii::app()->params["facebook"]["appId"];?>',
                    status     : false,
                    cookie     : false,
                    xfbml      : true
                });
            };

            (function(d, debug){
                var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement('script'); js.id = id; js.async = true;
                js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
                ref.parentNode.insertBefore(js, ref);
            }(document, /*debug*/ false));
        </script>

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-36745151-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>


    </body>
</html>
