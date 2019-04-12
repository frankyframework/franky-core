<?php
namespace Franky\Core;

Class AccessList 
{
    var $m_permisos = array();

        function __construct()
        {
           global $_asignar_roles;
          
           $this->m_permisos = array();  
            #print_r($this->m_permisos);
        }
        
        function addRoll($nivel,$seccion)
        {
            $this->m_permisos[$nivel][] = $seccion;
        }
	
        function removeRoll($nivel,$seccion)
        {
            foreach($this->m_permisos[$nivel] as $k => $v)
            {
                if($v == $seccion)
                {
                    unset($this->m_permisos[$nivel][$k]);
                }
            }
        }
        
        function MeDasChancePasar($seccion)
        {     
            global $MySession;
            
            if($MySession->LoggedIn())
            {
                if(in_array($seccion,$this->m_permisos[$MySession->GetVar('nivel')]))
                {
                    
                    return true;

                }

      
            }
            else
            {
                if(isset($this->m_permisos[NIVEL_USERPUBLICO]) && in_array($seccion,$this->m_permisos[NIVEL_USERPUBLICO]))
                {
                    return true;

                }
            }
            
            return false;
        }	
}
?>