<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $full_name
 * @property string $facebook
 * @property string $twitter
 * @property string $bitly
 * @property integer $soundcloud_id
 * @property string $soundcloud_accesstoken
 * @property integer $track_id
 * @property string $soundcloud_data
 * @property string $description
 * @property string $slug
 * @property string $track_title
 * @property string $track_genre
 * @property string $track_tags
 * @property string $track_data
 * @property integer $points
 * @property string $points_dateupdated
 * @property integer $qualified
 * @property string $dateadded
 */
class Users extends CActiveRecord
{

    public $profile_image;
    public $share_image;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, soundcloud_id, soundcloud_accesstoken, soundcloud_data, slug', 'required'),
			array('soundcloud_id, track_id, points, trend, qualified', 'numerical', 'integerOnly'=>true),
			array('username, full_name, facebook, twitter, bitly, soundcloud_accesstoken, slug, track_title', 'length', 'max'=>200),
			array('track_genre', 'length', 'max'=>100),
			array('description, track_data, points_dateupdated, dateupdated, track_tags, isactive', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, full_name, facebook, twitter, bitly, soundcloud_id, soundcloud_accesstoken, track_id, soundcloud_data, description, slug, track_title, track_genre, track_tags, track_data, points, points_dateupdated, trend, qualified, dateadded, dateupdate', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'full_name' => 'Full Name',
			'facebook' => 'Facebook',
			'twitter' => 'Twitter',
			'bitly' => 'Bitly',
			'soundcloud_id' => 'Soundcloud',
			'soundcloud_accesstoken' => 'Soundcloud Accesstoken',
			'track_id' => 'Soundcloud Track',
			'soundcloud_data' => 'Soundcloud Data',
			'description' => 'Description',
			'slug' => 'Slug',
			'track_title' => 'Track Title',
			'track_genre' => 'Track Genre',
			'track_tags' => 'Track Tags',
			'track_data' => 'Track Data',
            'points' => 'Points',
            'trend' => 'Trend Rate',
			'points_dateupdated' => 'Points Dateupdated',
			'qualified' => 'Qualified',
			'dateadded' => 'Dateadded',
            'dateupdated' => 'Date Updated',
		);
	}

	public function search($inputs, $limited = true)
	{
        $keyword = $inputs["keyword"];
        $genre = $inputs["genre"];
        $page = $inputs["page"];
        $sort = $inputs["sort"];

        $criteria = new CDbCriteria();
        $criteria->addCondition("
            (
                track_genre LIKE :input_loose
                OR track_title LIKE :input_loose
                OR username LIKE :input_loose
                OR full_name LIKE :input_loose
            )
            AND isactive = 1
        ");
        $criteria->addCondition("track_id != ''");
        $params[":input_loose"] = '%' . $keyword . '%';
        if($genre != ''){
            $criteria->addCondition("track_genre = :genre");
            $params[":genre"] = $genre;
        } else {
            $criteria->order = "rand()";
        }

        if($sort == "all"){

        } elseif($sort == "trending"){
            $criteria->order = "trend desc";
        } elseif($sort == "new"){
            $criteria->order = "id desc";
        }

        $criteria->params = $params;
        $results = Users::model()->findAll($criteria);

        if($limited){
            $count = count($results);
            $criteria->offset = $page * Yii::app()->params["rpp"];
            $criteria->limit = Yii::app()->params["rpp"];
            $results = Users::model()->findAll($criteria);
            $results = self::addUserFields($results);

            $pages = ceil($count / Yii::app()->params["rpp"]);
            $more = false;
            if($pages > $page + 1){ $more = true; }

            $return = array(
                "more" => $more,
                "count" => $count,
                "results" => $results
            );
        } else {
            $return = $results;
        }

        return $return;
	}
	public static function getCuratedUsers(){
	
		$criteria = new CDbCriteria;
		$criteria->condition = "qualified = 1";
		$users = Users::model()->findAll($criteria);
		$users = self::addUserFields($users);
		return $users;
		
	
	}
	
	public static function getWinners($curatorID){
	
		$curators= Curators::model()->findByPk($curatorID); 
		$arr = explode(",",$curators->artists);

        $users = array();
        if(count($arr)){
            foreach($arr as $id){
                $user = Users::model()->findByPk($id);
                if($user){
                    $users[$user->id]=$user;
                }
            }

            $users = self::addUserFields($users);
        }
        return $users;

	}
	

    public static function getRandomUsers(){
        $genres = Genres::model()->findAll(
            array("limit" => 3, "order" => "rand()")
        );
        $return = array();
        foreach($genres as $v){
            $criteria = new CDbCriteria;
            $criteria->condition = "track_id != '' AND isactive = 1 AND track_genre = '" . $v->title . "'";
            $criteria->order = 'rand()';
            $criteria->limit = 6;
            $users = Users::model()->findAll($criteria);
            $users = self::addUserFields($users);
            $return[] = array(
                "genre" => $v->title,
                "users" => $users,
            );
        }
        return $return;
    }

    private function addUserFields($users){
        foreach($users as $k => $v){
            $users[$k] = self::addUserFieldsSingle($v);
        }
        return $users;
    }
    private function addUserFieldsSingle($user){
        $user->profile_image = self::getProfileImage($user->id);
        $user->share_image = self::getShareImage($user->id);
        return $user;
    }

    public static function getProfileImage($id){
        $image = Yii::app()->params["default_profile_image"];
        $path = $_SERVER['DOCUMENT_ROOT'] . '/media/users/' . $id . "/profile.jpg";
        if(file_exists($path)){
            $image = "/media/users/" . $id . "/profile.jpg";
        } else {
            $image = Yii::app()->params["default_profile_image"];
        }
        return $image;
    }

    public static function getShareImage($id){
        $image = Yii::app()->params["default_share_image"];
        $path = $_SERVER['DOCUMENT_ROOT'] . '/media/users/' . $id . "/share.jpg";
        if(file_exists($path)){
            $image = "/media/users/" . $id . "/share.jpg";
        } else {
            $image = Yii::app()->params["default_share_image"];
        }
        return $image;
    }

    /*
    * Retrieves list of genres for all tracks
    */
    public static function getAllGenres(){
        $return = array();
        $genres = Users::model()->findAll(
            array(
                "select" => "track_genre",
                "condition" => "track_genre != '' AND isactive = 1",
                "distinct" => true,
                "order" => "track_genre asc",
            )
        );
        foreach($genres as $k => $v){
            $return[] = $v->track_genre;
        }
        return $return;
    }

    /*
 * Retrieves list of tracks matching a specific genre
 */
    public static function getTracksByGenre($genre){
        $tracks = Users::model()->findAll(
            array(
                "condition" => "track_genre = :genre",
                "params" => array(
                    "genre" => $genre
                )
            )
        );
        $tracks = self::addUserFields($tracks);
        return $tracks;
    }

    /*
     * Retrieves tracks based on genre or tag
     */
    public static function getTracksByKeyword($input){
        $tracks = Users::model()->findAll(
            array(
                "condition" => "
                    track_tags LIKE :input_loose
                    OR track_genre = :input
                    OR track_title LIKE :input_loose
                    OR username LIKE :input_loose
                    OR description LIKE :input_loose
                    OR full_name LIKE :input_loose
                ",
                "params" => array(
                    ":input_loose" => '%' . $input . '%',
                    ":input" => $input
                )
            )
        );
        $tracks = self::addUserFields($tracks);
        return $tracks;
    }

    public static function getShareData($model){
        $share_data = array(
            "track_id" => $model->track_id,
            "facebook" => array(
                "name" => "Grammy's Amplifier",
                "link" => $model->bitly,
                "picture" => "http://" . Yii::app()->params["url"] . $model->share_image,
                "caption" => "Cool track! Check it out on Grammy Amplifier. " .$model->bitly,
                "description" => "GRAMMY Amplifier gives musicians a chance to be heard by some of the biggest tastemakers in music. Post your original track and it could be shared with millions of fans worldwide."
            ),
            "twitter" => array(
                "url" => "http://twitter.com/share?text=" . urlencode("Cool track! Check it out on Grammy Amplifier #theworldislistening") . "&url=" . $model->bitly
            )
        );
        return $share_data;
    }


}