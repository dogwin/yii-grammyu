<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

        $this->pageTitle = Yii::app()->name;

        if($this->isMobile() && !$this->isTablet()){

            $this->render('index-mobile');

        } else {

            $criteria = new CDbCriteria;
            $criteria->condition = "track_id is not null";
            $criteria->order = "id desc";
            $criteria->limit = 25;
            $recentusers = Users::model()->findAll($criteria);
            $ticker = array();
            foreach($recentusers as $v){
                $ticker[] = array(
                    "artist" => $v->username,
                    "bitly" => $v->bitly,
                    "time" => $this->ago(strtotime($v->dateadded))
                );
            }

            $criteria = new CDbCriteria;
            $criteria->condition = "isactive = 1";
            $criteria->order = 'displayorder asc';
            $criteria->limit = 3;
            $curators = Curators::model()->findAll($criteria);

            $visited =  isset(Yii::app()->request->cookies['visited']) ? Yii::app()->request->cookies['visited']->value : false;
            if($visited){
                $visited = "visited";
            } else {
                $cookie = new CHttpCookie('visited', true);
                $cookie->expire = time() + 60*60*24*7;
                Yii::app()->request->cookies['visited'] = $cookie;
                $visited = false;
            }

            $query = Yii::app()->db->createCommand()
                ->select("sum(reach) as kount")
                ->from("curators")
                ->where("isactive = 1")
                ->queryRow();
            $totalreach = $query['kount'];

            $this->render('index', array('curators' => $curators, 'visited' => $visited, 'totalreach' => $totalreach, 'ticker' => $ticker));

        }

    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
	public function actionRules()
	{
        $this->pageTitle = "Rules - " . Yii::app()->name;
        $this->render("pages/rules");
	}
	
	public function actionTerms()
	{
        $this->pageTitle = "Terms and Conditions - " . Yii::app()->name;
        $this->render("pages/terms");
	}

	public function actionCopyright()
	{
        $this->pageTitle = "Copyright Notice - " . Yii::app()->name;
        $this->render("pages/copyright");
	}

	public function actionCurators()
	{
        $criteria = new CDbCriteria;
        $criteria->condition = "isactive = 1";
        $criteria->order = 'displayorder asc';
        $curators = Curators::model()->findAll($criteria);
        
		$totalReach = 0;
		
		foreach($curators as $k => $curator){
			$totalReach += $curator->reach;
		}
		$humandate = strtoupper(date("l M j, o"));

        $this->pageTitle = "Curators - " . Yii::app()->name;
        
        $this->render("pages/curators",array("curators" => $curators, "totalreach" => $totalReach, "humanDate" => $humandate));
	}

    public function actionCurator($slug){
        $model = Curators::model()->find(
            array(
                "condition" => "slug = :slug AND isactive = 1",
                "params" => array(
                    ":slug" => $slug
                )
            )
        );

        $this->render('pages/curator',array(
            'model'=> $model,
        ));

    }

	public static function buildReachImage($numberValue){
	
	 	$bigImageString = "";

		for($i=0;$i<strlen($numberValue);$i++){
			$thischar = substr($numberValue,$i,1);
			if ((strlen($numberValue) - $i) % 3 == 1 && $i != strlen($numberValue)-1){
				$comma = 1;
			}else{
				$comma = 0;
			}
			$bigImageString .= "<img src=". Yii::app()->theme->baseUrl . "/img/global_whitnumbers_" . $thischar . ".png width='19' height='29'> ";
			if($comma == 1){
				$bigImageString .= "<img src=". Yii::app()->theme->baseUrl . "/img/global_whitnumbers_comma.png width='10' height='29'> ";
			}
		}
		return $bigImageString;
			
	}
	
	
	public static function calculateTotalReach($totalReach){
	
 	$totalReachImageString = "";
		
		for($i=0;$i<strlen($totalReach);$i++){
			$thischar = substr($totalReach,$i,1);
			if ((strlen($totalReach) - $i) % 3 == 1 && $i != strlen($totalReach)-1){
				$comma = 1;
			}else{
				$comma = 0;
			}
			$totalReachImageString .= "<img src=". Yii::app()->theme->baseUrl . "/img/curators_social_reach_numbers_" . $thischar . ".png width='29' height='42'> ";
			if($comma == 1){
				$totalReachImageString .= "<img src=". Yii::app()->theme->baseUrl . "/img/curators_social_reach_numbers_comma.png width='10' height='29'> ";
			}
		}
		
 		return $totalReachImageString;
	
    }

	public function actionAbout()
	{
        $this->pageTitle = "About - " . Yii::app()->name;
        if($this->isMobile() && !$this->isTablet()){
            $this->render('pages/about-mobile');
        } else {
            $this->render('pages/about');
        }
	}

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    function ago($time)
    {
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");

        $now = time();

        $difference     = $now - $time;
        $tense         = "ago";

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] $tense ";
    }



}