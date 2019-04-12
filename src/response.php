<?php
namespace Franky\Core;

class response extends request
{

    function __construct() {

    }
  
    public function getURI()
    {
        return $this->getVarRequest("REQUEST_URI");
    }
    public function getQUERY()
    {
      if($this->getMETHOD() == 'get')
      {
          $request = $this->getRequest();
          if(!empty($request))
          {
              unset($request["my_url_friendly"]);
              foreach ($request as $k => $v)
              {
                      $uri .= $this->Sanitizacion($k)."=".$v."&";
              }
          }

        return trim($uri,"&");
      }
      return "";
    }
    public function getSERVER()
    {
        return $this->getVarRequest("SERVER_NAME");
    }
    public function getPROTOCOLO()
    {
        return (isset($_SERVER["HTTPS"]) ? "https://" : "http://");
    }
    public function getClassBody($idioma= "en")
    {
        $request = $this->getRequest();
        $path = "/".$request["my_url_friendly"];
        $path = str_replace("/$idioma/", "/", $path);
        return ($path != "/" ? trim(str_replace("/", " ", $path)) : "home");
    }

    public function getReferer()
    {
        return $this->getVarRequest("HTTP_REFERER");
    }

}
?>
