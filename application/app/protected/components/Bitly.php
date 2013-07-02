<?php

class Bitly
{
    static $api_url = "https://api-ssl.bitly.com/v3/";

    public static function shortenUrl($url){
        $request = self::$api_url . "shorten?access_token=" . Yii::app()->params["bitly"]["accesstoken"] . "&longUrl=" . urlencode($url);
        $data = Yii::app()->CURL->run($request);
        if($data){
            $data = json_decode($data);
            if($data->status_code == "200"){
                return $data->data->url;
            }
        }
    }

}
