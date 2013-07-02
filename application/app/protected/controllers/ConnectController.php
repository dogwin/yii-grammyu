<?php

class ConnectController extends Controller
{


	public function actionIndex()
	{

        $soundcloud = Soundcloud::setup();

        //generate login url
        $connectUrl = $soundcloud->getAuthorizeUrl(
            array(
                "scope" => "non-expiring",
                "display" => "popup"
            )
        );

        $this->pageTitle = "Connect with SoundCloud - " . Yii::app()->name;

        if($this->isMobile() && !$this->isTablet()){
            $this->render('index-mobile', array('connectUrl' => $connectUrl));
        } else {
            $this->render('index', array('connectUrl' => $connectUrl));
        }
	}

    public function actionAuthorize(){
        $code = isset($_GET["code"]) ? $_GET["code"] : false;
        if($code){
            $model = new Connect;
            $model->code = $code;
            $model->password = '';
            if($model->login()){
                $this->renderPartial("success");
            } else {
                throw new CHttpException(500, "Login mechanism is broken.");
            }
        } else {
            $this->renderPartial("error");
        }

    }
}