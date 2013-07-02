<?php

class ArtistsController extends Controller
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
        $this->pageTitle = "Artists - " . Yii::app()->name;

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

        $genres = Users::getAllGenres();
        if($this->isMobile() && !$this->isTablet()){
            $this->render('index-mobile', array("genres" => $genres));
        } else {
            $this->render('index', array("genres" => $genres, "ticker" => $ticker));
        }
    }


    public function actionPoints(){
        if(Yii::app()->request->isPostRequest) {
            $type = Yii::app()->request->getPost("type");
            $track_id = Yii::app()->request->getPost("track_id");
            $token = Yii::app()->request->getPost("token");
            $timestamp = Yii::app()->request->getPost("timestamp");
            $post_id = Yii::app()->request->getPost("post_id");
            $verifyToken = md5(Yii::app()->params["uploadsalt"] . $timestamp . Yii::app()->user->id . Yii::app()->session->sessionId);
            if($token == $verifyToken){
                if($type == "play"){
                    Points::addPlay($track_id);
                } elseif($type == "facebook"){
                    Points::addFacebook($track_id, $post_id);
                } elseif($type == "twitter"){
                    Points::addTwitterPlaceholder($track_id);
                }
            }

        }
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