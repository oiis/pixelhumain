<?php

class Notification
{
    const NOTIFICATION_LOGIN	               = "citizenLogin";
    const NOTIFICATION_REGISTER	               = "citizenRegister";
    const NOTIFICATION_ACTIVATED	           = "citizenActivated";
    const NOTIFICATION_INVITATION	           = "citizenInvitation";
    
    const ASSOCIATION_SAVED	                   = "associationSaved";
    const ENTREPRISE_SAVED	                   = "entrepriseSaved";
    const COLLECTIVITE_SAVED	               = "collectiviteSaved";
    
    const NOTIFICATION_SWE_COACH_REQUEST	   = "startUpWeekendCoachRequest";
    const NOTIFICATION_SWE_SAVED_INFOS	       = "sweSavedInfos";
    
    /*
     * Save a certain Norification to the notification table
     * */
    public static function add($params){
        $params["created"] = time();
        
        if(!in_array($_SERVER['SERVER_NAME'], array('127.0.0.1',"localhost")))
            Yii::app()->mongodb->notifications->insert($params); 
    }
    
}