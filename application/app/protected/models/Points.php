<?php

/**
 * This is the model class for table "points".
 *
 * The followings are the available columns in table 'points':
 * @property integer $track_id
 * @property string $ipaddress
 * @property integer $value
 * @property string $type
 * @property string $dateadded
 */
class Points extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Points the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'points';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('track_id, session_id, ipaddress, type, dateadded', 'required'),
			array('track_id, value', 'numerical', 'integerOnly'=>true),
			array('ipaddress', 'length', 'max'=>15),
            array('session_id', 'length', 'max' => 200),
			array('type', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('track_id, session_id, ipaddress, value, type, dateadded', 'safe', 'on'=>'search'),
		);
	}

    public static function addPlay($track_id){
        $points = Points::model()->findAll(
            array(
                "condition" => "type = :type AND track_id = :track_id AND ipaddress = :ipaddress AND dateadded >= NOW() - INTERVAL 15 MINUTE",
                "params" => array(
                    ":type" => "play",
                    ":track_id" => $track_id,
                    ":ipaddress" => Yii::app()->request->userHostAddress,
                )
            )
        );
        if(!$points){
            $points = new Points();
            $points->type = "play";
            $points->track_id = $track_id;
            $points->session_id = Yii::app()->session->sessionId;
            $points->ipaddress = Yii::app()->request->userHostAddress;
            $points->value = 1;
            $points->dateadded = new CDbExpression('NOW()');
            if(!$points->save()){
                throw new CHttpException(500, print_r($points->getErrors(), 1));
            }
        }
    }

    public static function addFacebook($track_id, $post_id){
        $points = Points::model()->findAll(
            array(
                "condition" => "type = :type AND track_id = :track_id AND ipaddress = :ipaddress AND dateadded >= NOW() - INTERVAL 1 HOUR",
                "params" => array(
                    ":type" => "facebook",
                    ":track_id" => $track_id,
                    ":ipaddress" => Yii::app()->request->userHostAddress,
                )
            )
        );
        if(!$points){
            $points = new Points();
            $points->type = "facebook";
            $points->track_id = $track_id;
            $points->session_id = Yii::app()->session->sessionId;
            $points->ipaddress = Yii::app()->request->userHostAddress;
            $points->external_id = $post_id;
            $points->value = 10;
            $points->dateadded = new CDbExpression('NOW()');
            if(!$points->save()){
                throw new CHttpException(500, print_r($points->getErrors(), 1));
            }
        }
    }

    public static function addTwitterPlaceholder($track_id){
        $points = Points::model()->findAll(
            array(
                "condition" => "type = :type AND track_id = :track_id AND ipaddress = :ipaddress AND dateadded >= NOW() - INTERVAL 1 HOUR",
                "params" => array(
                    ":type" => "twitter_placeholder",
                    ":track_id" => $track_id,
                    ":ipaddress" => Yii::app()->request->userHostAddress,
                )
            )
        );
        if(!$points){
            $points = new Points();
            $points->type = "twitter_placeholder";
            $points->track_id = $track_id;
            $points->session_id = Yii::app()->session->sessionId;
            $points->ipaddress = Yii::app()->request->userHostAddress;
            $points->value = 0;
            $points->dateadded = new CDbExpression('NOW()');
            if(!$points->save()){
                throw new CHttpException(500, print_r($points->getErrors(), 1));
            }
        }
    }

    public static function addTwitter($track_id, $tweet){
        $post_id = $tweet->id_str;
        $date = date("Y-m-d H:i:s a",strtotime($tweet->created_at));
        $exists = Points::model()->exists(
            array(
                "condition" => "type = :type AND external_id = :external_id",
                "params" => array(
                    ":type" => "twitter",
                    ":external_id" => $post_id
                )
            )
        );
        if(!$exists){
            $points = new Points;
            $points->type = "twitter";
            $points->track_id = $track_id;
            $points->session_id = Yii::app()->session->sessionId;
            $points->ipaddress = Yii::app()->request->userHostAddress;
            $points->external_id = $post_id;
            $points->value = 10;
            $points->dateadded = $date;
            if(!$points->save()){
                throw new CHttpException(500, print_r($points->getErrors(), 1));
            }
        }
    }



}