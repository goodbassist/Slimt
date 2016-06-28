<?php if (!defined('BASEURL')) exit('No Direct access to page');

class DbClass
{

    public function __construct()
    {
        $this->db = new Db(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }


}


?>