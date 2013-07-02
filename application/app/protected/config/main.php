<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

//set timezone to UTC
date_default_timezone_set('UTC');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'theme'=>'grammys',
	'name'=>'Grammy Amplifier',
	'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'lollerskates',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),
	'components'=>array(
		'user'=>array(
			'allowAutoLogin' => false,
            'loginUrl' => array('/app/connect'),
            'returnUrl' => array('/app/control-panel')
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
                /*
                '' => 'site/index',
                //'connect' => 'connect/index'
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                //*/
                ///*
                'site/login' => 'connect/index',
                'app/connect' => 'connect/index',
                'app/artists' => 'artists/index',
                'app/curated' => 'users/curated',
                'app/winners' => 'users/winners',
                'app/api/<action:\w+>' => 'api/<action>',
                'app/artists/<action:\w+>' => 'artists/<action>',
                'app/connect/<action:\w+>' => 'connect/<action>',
                'app/control-panel' => 'users/update',
                'app/logout' => 'site/logout',
                'app/vote' => 'votes/create',
                'app/users/<action:\w+>/<id:\d+>' => 'users/<action>',
                'app/users/<action:\w+>' => 'users/<action>',
                'app/terms-and-conditions' => 'site/terms',
                'app/privacy-policy' => 'site/privacy',
                'app/curators' => 'site/curators',
                'app/curators/<slug:\S+>' => 'site/curator',
                'app/about' => 'site/about',
                'app/copyright' => 'site/copyright',
                'app/maintenance/<action:\w+>' => 'maintenance/<action>',
                'app/rules' => 'site/rules',
                '<slug:\S+>'=>'users/view',
                //*/

			),
            'showScriptName'=>false,
        ),
		'db'=>array(
			'connectionString' => 'mysql:host=' . getenv("DB_HOST") . ';dbname=' . getenv("DB_NAME"),
			//'connectionString' => 'mysql:host=localhost;dbname=grammyu',
			'emulatePrepare' => true,
			'username' => getenv("DB_USER"),
			'password' => getenv("DB_PASS"),
			'charset' => 'utf8',
            'initSQLs' => array("set time_zone='+00:00';"),
		),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
        'CURL' => array(
            'class' => 'application.extensions.curl.Curl',
        ),
	),
	'params'=>array(
        'url' => 'grammyu',
		'adminEmail'=>'webmaster@theworldislistening.com',
        'trackcachetime' => 60 * 60 * 24,
        'uploadsalt' => "l0llersk@tes!",
        'profile_image_size' => '600x400',
        'logo' => '/themes/grammys/img/logo.jpg',
        'default_profile_image' => '/themes/grammys/img/default_profile_image.jpg',
        'default_share_image' => '/themes/grammys/img/default_share_image.jpg',
        'share_image_size' => '90x90',
        'waveform' => array(
            "small" => "230x53",
            "large"  => "390x100",
        ),
        'rpp' => 12,

        'soundcloud' => array(
            'clientId' => '6e079c1d77117673a476ba2be0e970b9',
            'clientSecret' => '13fc4d315d19acecfa7615c7e536097a'
        ),
        'bitly' => array(
            'username' => '',
            'key' => '',
            'accesstoken' => '',
        ),
        'facebook' => array(
            'appId' => '',
            'appSecret' => ''
        )
	),
);

