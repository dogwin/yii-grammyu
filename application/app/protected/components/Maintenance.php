<?php

    class Maintenance extends CApplicationComponent {

        public static function updateCurators(){
            $curators = Curators::model()->findAll(
                array(
                    "condition" => "isactive = 1"
                )
            );
            if($curators){
                foreach($curators as $curator){
                    $followers = Twitter::getFollowerCount($curator->twitter);
                    $curator->reach = $followers;
                    $curator->save();
                }
            }
        }

        public static function updateUsers(){
            $users = Users::model()->findAll(
                array(
                    "condition" => "track_id != '' AND dateupdated <= NOW() - INTERVAL 24 HOUR"
                )
            );
            foreach($users as $user){
                Soundcloud::refreshTrack($user->track_id);
            }
        }

        public static function updatePoints(){
            self::removeDuplicates();
            $users = Users::model()->findAll(
                array(
                    "condition" => "track_id != ''"
                )
            );
            foreach($users as $user){
                $query = Yii::app()->db->createCommand()
                    ->select("sum(value) as kount")
                    ->from("points")
                    ->where("track_id = :id AND isactive = 1", array(":id" => $user->track_id))
                    ->queryRow();
                $points = $query['kount'];
                if($points > $user->points){
                    $user->points = $points;
                    $user->points_dateupdated = new CDbExpression('NOW()');
                    $user->save();
                }
            }
        }

        public static function updateTweets(){
            //first get only tracks that we detect have had the tweet button clicked in order to minimize calls to twitter api
            $tweeted = Points::model()->findAll(
                array(
                    "select" => "track_id",
                    "condition" => "type = :type",
                    "distinct" => true,
                    "params" => array(
                        ":type" => "twitter_placeholder"
                    )
                )
            );

            foreach($tweeted as $v){
                $user = Users::model()->find(
                    array(
                        "select" => "bitly, track_id",
                        "condition" => "track_id = :track_id",
                        "params" => array(
                            ":track_id" => $v->track_id
                        )
                    )
                );
                if($user){
                    $since = date("Y-m-d", time() - 60 * 60 * 24 * 2);
                    $request = "http://search.twitter.com/search.json?rpp=100&since=$since&q=" . urlencode($user->bitly);
                    $data = Yii::app()->CURL->run($request);
                    if($data){
                        $data = json_decode($data);
                        if(count($data->results) > 0){
                            foreach($data->results as $tweet){
                                $exists = Points::model()->exists(
                                    array(
                                        "condition" => "type = :type AND external_id = :external_id",
                                        "params" => array(
                                            ":type" => "twitter",
                                            ":external_id" => $tweet->id_str
                                        )
                                    )
                                );
                                if(!$exists){
                                    Points::addTwitter($v->track_id, $tweet);
                                }
                            }
                        }
                    }

                    //sleep so we dont pass twitter rate limit
                    sleep(1);
                }
            }
            self::removeDuplicates();
        }

        public static function updateTrending(){
            Yii::app()->db->createCommand("update users set trend = 0")->execute();
            $users = Yii::app()->db->createCommand()
                ->select("track_id, SUM(value) as trend")
                ->from("points")
                ->where("dateadded >= NOW() - INTERVAL 1 DAY AND isactive = 1")
                ->order("trend desc")
                ->group("track_id")
                ->queryAll();
            foreach($users as $k => $v){
                $user = Users::model()->find(
                    array(
                        "condition" => "track_id = :track_id",
                        "params" => array(
                            ":track_id" => $v["track_id"]
                        )
                    )
                );
                if($user){
                    $user->trend = $v["trend"];
                    $user->save();
                }
            }
        }

        public static function removeDuplicates(){
            $twitter = Yii::app()->db->createCommand()
                ->select('id, track_id, external_id, count(external_id) as kount')
                ->from('points')
                ->where("type = 'twitter'")
                ->group('external_id')
                ->order('kount desc')
                ->queryAll();

            foreach($twitter as $k => $v){
                if($v["kount"] > 1){

                    $tweets = Points::model()->findAll(
                        array(
                            "condition" => "type = 'twitter' AND external_id = :external_id",
                            "params" => array(
                                ":external_id" => $v["external_id"],
                            ),
                            "limit" => 1,
                            "order" => "id asc"
                        )
                    );


                    if($tweets){
                        $tweet = $tweets[0];
                        Points::model()->updateAll(
                            array(
                                "isactive" => 0
                            ),
                            array(
                                "condition" => "type = 'twitter' AND external_id = :external_id AND id > :id",
                                "params" => array(
                                    ":external_id" => $v["external_id"],
                                    ":id" => $tweet->id,
                                )
                            )
                        );
                    }
                }
            }
        }
    }