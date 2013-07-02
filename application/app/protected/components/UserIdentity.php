<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    private $_id;
    private $_name;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        $soundcloud = Soundcloud::setup();

        try {
            $accessToken = $soundcloud->accessToken($this->username);
            $sc_user = $soundcloud->get("me");
            $sc_user = json_decode($sc_user);
            $sc_connections = $soundcloud->get("me/web-profiles");
            $sc_connections = json_decode($sc_connections);

            //check if user exists
            $user = Users::model()->find(
                array(
                    "condition" => "soundcloud_id = :soundcloud_id",
                    "params" => array(
                        ":soundcloud_id" => $sc_user->id
                    )
                )
            );


            if(!$user){
                $user = new Users();
                $user->username = $sc_user->username;
                $user->full_name = $sc_user->full_name;
                $user->soundcloud_id = $sc_user->id;
                $user->soundcloud_accesstoken = $accessToken["access_token"];
                $user->soundcloud_data = json_encode($sc_user);
                $user->description = $sc_user->description;
                $user->slug = $sc_user->permalink;
                $user->bitly = Bitly::shortenUrl("http://" . Yii::app()->params["url"] . "/" . $user->slug);
                $user->dateadded = new CDbExpression('NOW()');

                //set facebook and twitter links
                if($sc_connections){
                    if(count($sc_connections) > 0){
                        foreach($sc_connections as $v){
                            if($v->service == "facebook"){
                                $user->facebook = $v->url;
                            }
                            if($v->service == "twitter"){
                                $user->twitter = $v->url;
                            }
                        }
                    }
                }

                if(!$user->save()){
                    print_r($user->getErrors());
                    throw new CDbException("Error saving user.");
                }

            }

            if($user){
                $user->soundcloud_data = json_encode($sc_user);
                $user->save();

                $this->_id = $user->id;
                $this->setState("name",$sc_user->username);
                $this->errorCode = self::ERROR_NONE;

                return $this->errorCode;
            }
        } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
            exit($e->getMessage());
        }
	}

    public function getId(){
        return $this->_id;
    }

}