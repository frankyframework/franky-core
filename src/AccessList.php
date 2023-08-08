<?php
namespace Franky\Core;

Class AccessList 
{
    var $m_permisos = array();

        function __construct()
        {
          
           $this->m_permisos = array();  
        }
        
        function addRoll($seccion)
        {
            $this->m_permisos[] = $seccion;
        }
	
        function removeRoll($seccion)
        {
            foreach($this->m_permisos as $k => $v)
            {
                if($v == $seccion)
                {
                    unset($this->m_permisos[$k]);
                }
            }
        }
        
        function MeDasChancePasar($seccion)
        {     
            global $MySession;
            if(in_array($seccion,$this->m_permisos))
            {
                
                return true;

            }
            
            return false;
        }	
}
?>