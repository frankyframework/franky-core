<?php
namespace Franky\Core;

class MYSESSION
{
        
    public $espacio;

        function __construct($espacio = "default")
        {
            $this->espacio = $espacio;
        }	

        function LoggedIn()
        {
                return ($this->GetVar("is_login") == true);
        }
        
        function SetVar( $varname, $value )
        {
                $_SESSION[$this->espacio][$varname] = $value;
        }

        function GetVar( $varname )
        {
                return ( isset($_SESSION[$this->espacio][$varname]) ? $_SESSION[$this->espacio][$varname] : false );
        }
        
        function UnsetVar( $varname )
        {
                if( isset($_SESSION[$this->espacio][$varname]))
                {
                    unset($_SESSION[$this->espacio][$varname]);
                }
        }

        function EndSession()
        {
            $_SESSION[$this->espacio] = array();	
        }

}
?>
