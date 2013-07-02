<?php

class ApiController extends Controller
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
                'actions'=>array('search','genres'),
                'users'=>array('*'),
            ),
        );
    }

    public function actionSearch(){
        $keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : "";
        $genre = isset($_POST["genre"]) ? $_POST["genre"] : "";
        $page = isset($_POST["page"]) ? $_POST["page"] : 0;
        $sort = isset($_POST["sort"]) ? $_POST["sort"] : "all";
        $callback = isset($_GET["callback"]) ? $_GET["callback"] : "";

        $data = Users::search(
            array(
                "keyword" => $keyword,
                "genre" => $genre,
                "page" => $page,
                "sort" => $sort,
            ),
            false
        );

        $return = array();
        $fields = array(
            "username",
            "track_title",
            "track_genre",
            "track_id",
            "track_tags",
            "points",
            "facebook",
            "twitter",
            "trend",
            "slug",
            "bitly",
        );

        foreach($data as $user){
            $obj = new stdClass();
            foreach($fields as $field){
                $obj->{$field} = $user->{$field};
            }
            $return[] = $obj;
        }
        $return = json_encode($return);

        if($callback != '') {
            echo $callback . '(' . $return . ')';
        } else {
            echo $return;
        }
    }

    public function actionGenres(){
        $callback = isset($_GET["callback"]) ? $_GET["callback"] : "";
        $genres = Users::getAllGenres();
        $return = json_encode($genres);

        if($callback != '') {
            echo $callback . '(' . $return . ')';
        } else {
            echo $return;
        }
    }

}
