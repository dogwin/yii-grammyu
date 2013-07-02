<?php

class MaintenanceController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionCurators()
    {
        ini_set("max_execution_time", 60 * 60);
        Maintenance::updateCurators();
    }

    public function actionTweets(){
        ini_set("max_execution_time", 60 * 60);
        Maintenance::updateTweets();
    }

    public function actionPoints(){
        ini_set("max_execution_time", 60 * 60);
        Maintenance::updatePoints();
    }

    public function actionUsers(){
        ini_set("max_execution_time", 60 * 60 * 12);
        Maintenance::updateUsers();
    }

    public function actionTrending(){
        ini_set("max_execution_time", 60 * 60);
        Maintenance::updateTrending();
    }

    public function actionDuplicates(){
        ini_set("max_execution_time", 60 * 60);
        Maintenance::removeDuplicates();
    }

}