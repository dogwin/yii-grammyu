<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view','random','search','upload','curated','winners'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update','crop','image','tracks'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($slug)
	{

        $model = Users::model()->find(
            array(
                "condition" => "slug = :slug AND isactive = 1",
                "params" => array(
                    ":slug" => $slug
                )
            )
        );

        if(!$model){
            throw new CHttpException(404,'The requested page does not exist.');
        }

        $model->soundcloud_data = json_decode($model->soundcloud_data);
        $model->profile_image = Users::getProfileImage($model->id);
        $model->share_image = Users::getShareImage($model->id);
        $model->track_data = json_decode($model->track_data);
        $share_data = Users::getShareData($model);

        $this->pageTitle = $model->username . " - " . Yii::app()->name;

        $view = "view";
        if($this->isMobile() && !$this->isTablet()){
            $view = $view . "-mobile";
        }

        $this->render($view, array(
            'model'=> $model,
            'share_data' => $share_data,
        ));

	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{

        $id = Yii::app()->user->id;
        $model=$this->loadModel($id);

        $has_unplayable_tracks = false;
        $tracks = array();

        if($model->track_id == ''){
            $soundcloud = Soundcloud::setup();
            $tracks_all = $soundcloud->get("me/tracks", array("oauth_token" => $model->soundcloud_accesstoken));
            if($tracks_all){
                $tracks_all = json_decode($tracks_all);
                foreach($tracks_all as $k => $v){
                    if($v->streamable && $v->embeddable_by != "none" && $v->sharing != "private"){
                        $tracks[] = $v;
                    } else {
                        $has_unplayable_tracks = true;
                    }
                }
            }
        }

		if(isset($_POST['Users']))
		{
            if($model->track_id == ''){
                if(isset($_POST["selected_track_id"])){
                    $model->track_id = $_POST["selected_track_id"];
                }
            }

			$model->attributes=$_POST['Users'];

            if($model->facebook != ''){
                if (strpos($model->facebook,'http://') === false && strpos($model->facebook,'https://') === false ){
                    $model->facebook = 'http://' . $model->facebook;
                }
            }
            if($model->twitter != ''){
                if (strpos($model->twitter,'http://') === false && strpos($model->twitter,'https://') === false){
                    $model->twitter = 'http://' . $model->twitter;
                }
            }

			if($model->save()){
                if($model->track_id != ''){
                    Soundcloud::refreshTrack($model->track_id);
                }
				$this->redirect('/' . $model->slug);
            }
		}

        $genres = array();
        $genres_all = Genres::model()->findAll(
            array("order" => "title asc")
        );
        foreach($genres_all as $v){
            $genres[$v->title] = $v->title;
        }


        $model->soundcloud_data = json_decode($model->soundcloud_data);
        $model->profile_image = Users::getProfileImage($model->id);
        $model->share_image = Users::getShareImage($model->id);
        $model->track_data = json_decode($model->track_data);

        $this->pageTitle = "Profile Setup - " . Yii::app()->name;

        if($this->isMobile() && !$this->isTablet()){
            $this->render('update-mobile',array(
                'model'=>$model,
                'tracks'=>$tracks,
                'has_unplayable_tracks' => $has_unplayable_tracks,
                'genres' => $genres,
            ));
        } else {
            $this->render('update',array(
                'model'=>$model,
                'tracks'=>$tracks,
                'has_unplayable_tracks' => $has_unplayable_tracks,
                'genres' => $genres,
            ));
        }
	}

	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function actionRandom(){
        $data = Users::getRandomUsers();
        $this->renderPartial("_users_genres", array("users" => $data));
    }

    public function actionUpload(){
        $verifyToken = md5(Yii::app()->params["uploadsalt"] . $_POST['timestamp'] . $_POST["userId"]);
        if($_POST['token'] == $verifyToken && $_POST["userId"] > 0 && is_numeric($_POST["userId"])){
            $user_id = $_POST["userId"];
            $basepath = $_SERVER['DOCUMENT_ROOT'];
            $targetFolder = '/uploads/' . $user_id;
            if(!file_exists($basepath . $targetFolder)){
                mkdir($basepath . $targetFolder);
            }
            if (!empty($_FILES)) {
                $tempFile = $_FILES['Filedata']['tmp_name'];
                $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                $fileTypes = array('jpg','jpeg','gif','png');
                $fileParts = pathinfo($_FILES['Filedata']['name']);
                $fileParts["extension"] = strtolower($fileParts["extension"]);
                $targetFile = rtrim($targetPath,'/') . '/' . "source.jpg";
                if (in_array($fileParts['extension'],$fileTypes) && getimagesize($tempFile)) {
                    //convert and save source image
                    Yii::import("ext.EPhpThumb.EPhpThumb");
                    $thumb=new EPhpThumb();
                    $thumb->init();
                    $thumb->create($tempFile)->save($targetFile, "jpg");
                    list($width, $height) = getimagesize($tempFile);
                    $ar = explode("x",Yii::app()->params["profile_image_size"]);
                    $ar = $ar[0]/$ar[1];
                    $return = array(
                        "src" => $targetFolder . "/source.jpg",
                        "width" => $width,
                        "height" => $height,
                        "ar" => $ar
                    );
                    echo json_encode($return);
                } else {
                    echo 'Invalid file type.';
                }
            }
        } else {
            throw new Exception("Invalid token. " . print_r($_POST,1) . " Expecting " . $verifyToken);
        }

    }

    public function actionCrop(){
        $fields = array("x","y","x2","y2","w","h","original_w","original_h","original_adjusted_w","original_adjusted_h");
        foreach($fields as $v){
            if(preg_match('/^[0-9]*$/', round($_POST[$v]))){
                $$v = isset($_POST[$v]) ? $_POST[$v] : "";
            }
        }

        $useCrop = true;
        foreach($fields as $v){
            if($$v == ""){
                $useCrop = false;
                continue;
            }
        }

        $user = Users::model()->findByPk(Yii::app()->user->getId());
        $source = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $user->id . "/source.jpg";
        $final = $_SERVER['DOCUMENT_ROOT'] . '/media/users/' . $user->id . "/profile.jpg";
        $share = $_SERVER['DOCUMENT_ROOT'] . '/media/users/' . $user->id . "/share.jpg";

        if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/media/users/' . $user->id)){
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/media/users/' . $user->id);
        }


        $final_size = explode("x",Yii::app()->params["profile_image_size"]);
        $final_w = $final_size[0];
        $final_h = $final_size[1];

        $share_size = explode("x",Yii::app()->params["share_image_size"]);
        $share_w = $share_size[0];
        $share_h = $share_size[1];

        Yii::import("ext.EPhpThumb.EPhpThumb");
        $thumb=new EPhpThumb();
        $thumb->init();


        if($useCrop){
            //determine proportionate crop
            $ratio = $original_w / $original_adjusted_w;
            foreach(array("x","y","x2","y2","w","h") as $v){
                $$v = $ratio * $$v;
            }
            $thumb->create($source)->crop($x, $y, $w, $h)->resize($final_w, $final_h)->save($final);
            $thumb->create($final)->adaptiveResize($share_w, $share_h)->save($share);
        } else {
            $thumb->create($source)->adaptiveResize($final_w, $final_h)->save($final);
            $thumb->create($final)->adaptiveResize($share_w, $share_h)->save($share);
        }

    }

    public function actionImage(){
        $id = Yii::app()->user->id;
        echo Users::getProfileImage($id);
    }

    public function actionSearch(){

        $keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : "";
        $genre = isset($_POST["genre"]) ? $_POST["genre"] : "";
        $page = isset($_POST["page"]) ? $_POST["page"] : 0;
        $sort = isset($_POST["sort"]) ? $_POST["sort"] : "all";

        if($keyword == "Search"){
            $keyword = "";
        }

        $data = Users::search(
            array(
                "keyword" => $keyword,
                "genre" => $genre,
                "page" => $page,
                "sort" => $sort,
            )
        );

        if(count($data["results"]) > 0){
            $this->renderPartial("_users", array("users" => $data["results"], "more" => $data["more"], "count" => $data["count"]));
        } else {
            $this->renderPartial("_noresults");
        }
    }
    
    public function actionWinners(){
    
    	$curatorID = isset($_POST["curatorID"]) ? $_POST["curatorID"] : "0";
     	$model = Users::getWinners($curatorID);
     	$this->renderPartial("_winners", array("users" => $model));

    }
    
    

    public function actionTracks(){
        $id = Yii::app()->user->id;
        $model=$this->loadModel($id);
        $soundcloud = Soundcloud::setup();
        $tracks_all = $soundcloud->get("me/tracks", array("oauth_token" => $model->soundcloud_accesstoken));
        echo $tracks_all;
    }
    
    public function actionCurated()
	{
        
        $model = Users::getCuratedUsers();
        $this->render("curated", array("model" => $model));

	}
	
	
}
