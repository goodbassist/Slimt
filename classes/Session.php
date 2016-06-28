<?php

class Session
{

    public static function init()
    {
        @session_start();
    }
    
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key)
    {
        //echo $key;
        //echo $_SESSION[$key];
        //$_SESSION[$key] = 1;
        //$_SESSION['sesOnlineEppComp'] = 5;
        if (isset($_SESSION[$key]))
        return $_SESSION[$key];
    }
    
    public static function destroy()
    {
        session_destroy();
        $_SESSION = array(); // empty the values 
    }
    public function chksessmerc(){
    $data = array(
        'mid' =>$_SESSION['merID'],
        );
    if(!isset($_SESSION['merID'] ) ){
         header("location: index.php");
        }
    return $data;
    }

    public function chksessstaff(){
    $data = array(
         'sid' =>$_SESSION['stfID'],
        );
    if(!isset($_SESSION['stfID'] ) ){
         header("location: index.php");
        }
    return $data;
    }

     public function chksessadmin(){
    $data = array(
        'aid' =>$_SESSION['admID'],
        'rid' =>$_SESSION['roleID']
        );
    if(!isset($_SESSION['admID'] ) ){
         header("location: index.php");
        }
    return $data;
    }


     public function chksessbuilder(){
    $data = array(
        'bid' =>$_SESSION['bldID']
        );
    if(!isset($_SESSION['bldID'] ) ){
         header("location: index.php");
        }
    return $data;
    }


     public function chksesscour(){
    $data = array(
        'cid' =>$_SESSION['courID'],
        );
    if(!isset($_SESSION['courID'] ) ){
         header("location: index.php");
        }
    return $data;
    }

    public function chksesscourstaff(){
    $data = array(
         'csid' =>$_SESSION['courstfID'],
        );
    if(!isset($_SESSION['courstfID'] ) ){
         header("location: index.php");
        }
    return $data;
    }

    public function sessexist(){
    if(isset($_SESSION['merID']) || $_SESSION['stfID']){
         header("location: dashboard.php");
    }
}

    public function sessexistcour(){
    if(isset($_SESSION['courID']) || $_SESSION['courstfID']){
         header("location: dashboard.php");
    }
}
    public function sessadminexist(){
    if(isset($_SESSION['admID'])){
         header("location: dashboard.php");
    }
}

}