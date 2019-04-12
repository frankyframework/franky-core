<?php
namespace Franky\Core;

class flashMessages
{	
    private $msg;
    private $response;
    private $namespace;      
    
    function __construct($request) {
        $this->namespace = "general";
        $this->msg = isset($_SESSION["flash_messages_".$this->namespace]) ? $_SESSION["flash_messages_".$this->namespace] : array();
        $this->response = isset($_SESSION["flash_response"]) ? $_SESSION["flash_response"] : array();
        
        $_SESSION["flash_response"] = $request;
        $_SESSION["flash_messages_".$this->namespace] = array();
    }
    
    public function free()
    {
        $_SESSION["flash_response"] = array();
        $_SESSION["flash_messages_".$this->namespace] = array();
    }
    
    public function setMsg($type,$val)
    {
        $_SESSION["flash_messages_".$this->namespace][$type][] = $val;
    }
    public function getMsg($type)
    {
        $html = "";
        if(isset($this->msg[$type]))
        {
            $html = "<ul>";
            foreach ($this->msg[$type] as $msg):
                    $html .= "<li>$msg</li>";
            endforeach;
            $html .= "</ul>";
        }
       
        
        return $html;
    }
    
    public function getResponse($key="",$default="",$html = false,$allowable_tags = array())
    {
        global $MyRequest;
        
        if(empty($key))
        {
            $vars = array();
            if(!empty($this->response))
            {
                
                foreach ($this->response as $key => $val)
                {
                    $vars[$key] = (is_array($val) ? $val : $MyRequest->Sanitizacion($val,$html,$allowable_tags));
                }
            }
            return $vars;
        }
        
        $response = isset($this->response[$key]) ? $this->response[$key] : $default;
        if(is_array($response))
        {
            
            return $response;
        }
        
        return $MyRequest->Sanitizacion($response,$html,$allowable_tags);
    }
    public function hayMensajes()
    {
        return !empty($this->msg);
    }
}
?>