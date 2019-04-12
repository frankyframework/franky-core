<?php
namespace Franky\Core;
class Ajax
{
    private $functions;
    
    function __construct() {
        $this->functions = array();
    }
    
    public function register($function)
    {
        $this->functions[] = $function;
    }
    
    public function execute($request)
    {
        global $MyRequest;
        
        if(!empty($request["function"]) && in_array($request["function"], $this->functions) && $MyRequest->isAjax())
        {
          
            echo str_replace(array('&amp;'),'&',json_encode(
                    call_user_func_array($request["function"],isset($request["vars_ajax"]) ? $request["vars_ajax"] : array())                        
                ));
            
        }
        else
        {
            echo json_encode(array("message" => "La funcion solicitada no existe"));
        }
        
    }
}

?>