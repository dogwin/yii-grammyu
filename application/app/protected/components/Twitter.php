<?php


class Twitter extends CApplicationComponent {

    public static function getFollowerCount($username){
        $request = "https://api.twitter.com/1/users/show.json?screen_name=$username&include_entities=true";
        try {
            $data = Yii::app()->CURL->run($request);
            if($data){
                $data = json_decode($data);
                $followers = $data->followers_count;
                return $followers;
            } else {
                throw new CHttpException(500, "Twitter request failed.");
            }
        } catch (CHttpException $e){
            throw new CHttpException(500, "Error retrieving curator follower count.");
        }

    }

}
