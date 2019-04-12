<?php
namespace Franky\Core;

class configure
{
    protected $modulo;
    function __construct()
    {
        $configure = include(PROJECT_DIR."/configure/data.php");
        $this->modulo =  $configure[$_SERVER["SERVER_NAME"]];

    }

    public function getPathSite()
    {
        return $this->modulo;
    }


    public function getServerUploadDir()
    {
        return PROJECT_DIR."/public/upload/".$this->modulo;
    }

    public function getUploadDir()
    {
        return "/public/upload/".$this->modulo;
    }

}
?>
