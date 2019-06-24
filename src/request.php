<?php
namespace Franky\Core;

class request
{
    private $urlparam;

    function __construct() {

       $this->urlparam = array();
    }
    private function setUrlParam()
    {
        global $MyFrankyMonster;


        $params = explode("/",trim($MyFrankyMonster->MySeccion(),"/"));
        $uri = explode("/",  trim($this->getURI(),"/"));
        $this->urlparam = array();
        $variable = array();

        foreach ($uri as $k => $v)
        {

            if(isset($params[$k]) && preg_match("/^\[([a-z0-9-_]+)\]$/i",$params[$k],$variable))
            {
                $this->urlparam[$variable[1]] = $uri[$k];
            }
        }
    }

    public function url($str,$variables = array(),$domain = false)
    {

        if(!empty($variables))
        {
            foreach($variables as $k => $v)
            {
                   $str = str_replace("[$k]",$v,$str);
            }
        }

        return $this->link(($domain ? ($this->getVarRequest("HTTPS") != "" ? "https://" : "http://").$this->getSERVER() : "")."/".$str,false,$domain);
    }

    public function link($url="",$decode=false,$domain = false)
    {

        if(empty($url))
        {
            $url = "/";
        }

        $url_disec = parse_url($url);

        if($decode && isset($url_disec["query"]))
        {
            $url_disec["query"] = str_replace("amp;","",$url_disec["query"]);
        }

        if(!isset($url_disec["scheme"]) || empty($url_disec["scheme"]))
        {
            $url_disec["scheme"] = (isset($_SERVER["HTTPS"]) ? "https" : "http");
        }
        if(!isset($url_disec["host"]) || empty($url_disec["host"]))
        {
            $url_disec["host"] = $this->getSERVER();
        }
        if(substr($url_disec["path"],0,1) != "/")
        {
            $url_disec["path"] = "/".$url_disec["path"];
        }


        if($url_disec["host"] == $this->getSERVER() && !$domain)
        {

            $url_location = $url_disec["path"].(!empty($url_disec["query"]) ? "?".$url_disec["query"] : "");

            $url_location = str_replace("//","/",$url_location);
        }
        else {
            $url_location = $url_disec["scheme"]."://".$url_disec["host"].$url_disec["path"].(!empty($url_disec["query"]) ? "?".$url_disec["query"] : "");
        }

        return $url_location;
    }

    public function redirect($url="",$code="302")
    {
        if($code ==  "302")
        {
            header("HTTP/1.1 302 Moved Temporarily");
        }
        elseif($code == "301")
        {
            header("HTTP/1.1 301 Moved Permanently");
        }

        header("Location: ".$this->link($url,true));
        exit();
    }


    public function Sanitizacion($var,$html = false,$allowable_tags = array())
    {
            if(!$html)
            {
                $var = htmlspecialchars ($var,ENT_NOQUOTES);
            }
            else
            {
                if(!empty($allowable_tags))
                {
                   $var = strip_tags($var,implode('',$allowable_tags));
                }
            }
            if(!get_magic_quotes_gpc())
            {
                    $var  = addslashes($var);
            }
            return $var;
    }

    public function getMETHOD()
    {
        return strtolower($this->getVarRequest('REQUEST_METHOD'));
    }

    public function getRequest($var ="",$default = "", $html = false,$allowable_tags = array())
    {

        $method = $this->getMETHOD();
        if($var === "")
        {
            if($method == "get")
            {
                $array_vals = $_GET;
            }
            if($method == "post")
            {
                $array_vals = $_POST;
            }
            if($method == "argv")
            {
                global $argv;

                foreach($argv as $k){
                    if(preg_match('/(.*)=(.*)/', $k))
                    {
                        $k = explode("=",$k);
                        $argv[$k[0]] = $k[1];
                    }
                }
                $array_vals = $argv;
            }
            $new_array_vals = array();
            foreach($array_vals as $k => $v)
            {
              if(is_array($v))
              {
                foreach($v as $_v)
                {
                  $new_array_vals[$k][] = $this->Sanitizacion($_v,$html,$allowable_tags);;
                }

              }
              else {
                $new_array_vals[$k] = $this->Sanitizacion($v,$html,$allowable_tags);
              }

            }
            return $new_array_vals;

        }



        if($method == "get")
        {
            $new_val =  isset($_GET[$var])	? 	$_GET[$var] 		: $default;
        }
        if($method == "post")
        {
            $new_val =  isset($_POST[$var])	? 	$_POST[$var] 		: $default;
        }
        if($method == "argv")
        {
            global $argv;

            foreach($argv as $k){
                if(preg_match('/(.*)=(.*)/', $k))
                {
                    $k = explode("=",$k);
                    $argv[$k[0]] = $k[1];
                }
            }

            $new_val =  isset($argv[$var])	? 	$argv[$var] 		: $default;
        }
        if(is_array($new_val))
        {
          foreach($new_val as $k => $v)
          {
            $new_val[$k] = $this->Sanitizacion($v,$html,$allowable_tags);
          }
          return $new_val;

        }

        return $this->Sanitizacion($new_val,$html,$allowable_tags);
    }

    public function getUrlParam($var = "",$default = "")
    {
        if(empty($this->urlparam))
        {
            $this->setUrlParam();
        }
        if(empty($var)){
            return $this->urlparam;
        }
        return (isset($this->urlparam[$var]) ? $this->Sanitizacion($this->urlparam[$var]) : $default);

    }

    public function getFORWARDED()
    {
        return ($this->getVarRequest("HTTP_X_FORWARDED_FOR") != "" ? $this->getVarRequest("HTTP_X_FORWARDED_FOR") : "");
    }

    public function isAjax()
    {
        return ($this->getVarRequest('HTTP_X_REQUESTED_WITH') != "") && $this->getVarRequest('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    public function getIP()
    {
        $ip = $this->getFORWARDED();
        if (!empty($ip))
	{
            return $ip;

	}
	else
	{
            return $this->getVarRequest('REMOTE_ADDR');
	}

    }

    public function getVarRequest($var)
    {
         return isset($_SERVER[$var]) ? $this->Sanitizacion($_SERVER[$var]): "";
    }

    /*********************** Response *************/


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
        return preg_replace('#([^.a-z0-9 s]+)#i', '', ($path != "/" ? trim(str_replace("/", " ", $path)) : "home"));
    }

    public function getReferer()
    {
        return $this->getVarRequest("HTTP_REFERER");
    }
  }
?>
