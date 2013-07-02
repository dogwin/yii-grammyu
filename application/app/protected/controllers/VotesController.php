<?php

class VotesController extends Controller
{
	public function actionCreate()
	{
        if(Yii::app()->request->isPostRequest){
            $vote_allowed = false;

            $track_id = isset($_POST["track_id"]) ? $_POST["track_id"] : "";

            if($track_id){

                $cookie = Yii::app()->request->cookies['votes_' . $track_id]->value;

                if(!$cookie){
                    Yii::app()->request->cookies['votes'] = new CHttpCookie('votes_' . $track_id, "1", array("expire" => time() + 60 * 60 * 24));

                    $voted_recently = Votes::model()->exists(
                        array(
                            "condition" => "track_id = :track_id AND ipaddress = :ipaddress AND dateadded > NOW() - INTERVAL 60 SECOND",
                            "params" => array(
                                ":track_id" => $track_id,
                                ":ipaddress" => CHttpRequest::getUserHostAddress()
                            )
                        )
                    );

                    if(!$voted_recently){
                        $vote_allowed = true;
                    }
                }

                if($vote_allowed){
                    $model = new Votes();
                    $model->ipaddress = CHttpRequest::getUserHostAddress();
                    $model->track_id = $track_id;
                    $model->save();
                }
            }

        }
	}


}