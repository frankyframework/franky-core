<?php
namespace Franky\Core;

class validaciones
{
    private $msg;
    function __construct() {
      $this->msg = "";
    }


    public function validarIP($ip)
    {
        $regex = "/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/";
          return preg_match($regex, $ip);
    }

    public function ValidaNames($names)
    {

      $regex = '/^[a-zA-Z áéíóúÁÉÍÓÚñÑ]+$/i';
      return preg_match($regex,$names);

    }

    public function ValidaMail($emial)
    {
        $regex = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$/";
        return preg_match($regex, $emial);
    }

    public function ValidaTel($tel)
    {
        $regex = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})'
            .'(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})'
            .'[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
        return preg_match($regex, $tel);
    }

    public function validaUrl($url)
    {

        return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url);
    }

    public function validaPassword($txt,$level)
    {

        switch($level)
        {
          case 1:
            return true;
          case 2:
            $regex = "/^(?=.*?[A-Z])/";
            break;
          case 3:
            $regex =  "/^(?=.*?[A-Z])(?=.*?[0-9])/";
            break;
          case 4:
            $regex =  "/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-\.])/";
            break;
          default:
            return true;
        }

        return   preg_match($regex, $txt);
    }


    public function validaCaracteres($txt,$permitidos="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_.")
    {

        for ($i=0; $i<strlen($txt); $i++)
        {
            if (strpos($permitidos, substr($txt,$i,1))===false){

                return false;
            }
        }
        return true;
    }



    public function isApropiado($txt)
    {
        $palabras = "";

        $fp = fopen(PROJECT_DIR."/configure/palabras_prohividas.txt", "r");
        while(!feof($fp)) {
            $palabras .= fgets($fp);
        }
        fclose($fp);
        $p = explode("\n",$palabras);
        foreach ($p as $p_)
        {
            $p_ = trim($p_);
            if(!empty($p))
            {
                if(preg_match("/$p_/i",$txt))
                {
                    return false;
                }
            }
        }

        return true;
    }

    public function isBoot($valor)
    {
        preg_match_all('/https?:\/\/[\S]+/', $valor, $matches);

        if(count($matches[0]) > 2){ return true; }

        return false;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function _empty($txt){
      $txt = str_replace(' ','',$txt);
      if(strlen($txt) > 0)
      {
        return false;
      }
      return true;
    }
    function validRules($rules)
    {

        global $MyMessageAlert;
        foreach ($rules as $campo => $rule)
        {

            $valor = $rule['valor'];


            if(in_array("required",$rule) && $this->_empty($valor))
            {

                $this->msg = $MyMessageAlert->Message("empty_input",$campo);
                return false;
            }
            if($valor != "" && isset($rule["valid_chars"]) && ! $this->validaCaracteres($valor,$rule["valid_chars"]))
            {
                $this->msg = $MyMessageAlert->Message("bad_chars",$campo);
                return false;
            }
            if($valor != "" && in_array("email",$rule) && !$this->ValidaMail($valor))
            {

                $this->msg = $MyMessageAlert->Message("bad_email",$campo);
                return false;
            }
            if($valor != "" && in_array("name-validation",$rule) && !$this->ValidaNames($valor))
            {

                $this->msg = $MyMessageAlert->Message("bad_names",$campo);
                return false;
            }
            if($valor != "" && in_array("url",$rule) && !$this->validaUrl($valor))
            {
                $this->msg = $MyMessageAlert->Message("bad_url",$campo);
                return false;
            }
            if($valor != "" && in_array("numeric",$rule) && !is_numeric($valor))
            {
                 $this->msg = $MyMessageAlert->Message("bad_numeric",$campo);
                 return false;
            }
            if($valor != "" && isset($rule["length"]["min"]) && strlen($valor)< $rule["length"]["min"])
            {
                $this->msg = $MyMessageAlert->Message("error_min_length",$campo);
                return false;
            }

            if($valor != "" && isset($rule["length"]["max"]) && strlen($valor)> $rule["length"]["max"])
            {
                $this->msg = $MyMessageAlert->Message("error_max_length",$campo);
                return false;
            }
            if($valor != "" && in_array("apropiado", $rule) && $this->isApropiado($valor) == false)
            {
                $this->msg = $MyMessageAlert->Message("puterias",$campo);
                return false;
            }
            if($valor != "" && in_array("boot", $rule) && $this->isBoot($valor))
            {
               $this->msg = $MyMessageAlert->Message("spam_boot");
               return false;
            }
            if($valor != "" && isset($rule["password"]) && !$this->validaPassword($valor,$rule["password"]))
            {
               $this->msg = $MyMessageAlert->Message("password_level_".$rule["password"]);
               return false;
            }

        }

        return true;
    }


}
?>
