<?php
namespace Franky\Core;

class menuSitio{

    var $menu;
    function __construct() {
        $this->menu = array();
    }


    function setArraySeccion($archivo,$name)
    {
        global $MyRequest;
        if(file_exists($archivo))
        {
            $menuXML = include($archivo);
            $this->menu[$name] = $menuXML;

        }
    }

    function getItemMenu($formato="<li><a href=\"%s\">%s</a></li>")
    {
        global $MyAccessList;

        $html = "";

        foreach($this->menu as $name_menu => $_menu)
        {
            
            foreach($_menu as $__menu)
            {
                
                  $html .= sprintf($formato,$__menu['url'],$__menu['etiqueta']);

            }

        }
        return $html;
    }

    function getMenu()
    {
        global $MyAccessList;
        global $MyRequest;
        $html = "";
       
        $total_links = 0;
        $machote = "%s";
        foreach($this->menu as $name_menu => $menu)
        {
             
            
            foreach($menu as $_menu)
            {
               
                $machote = "<li class=\"title ".$this->getFriendly($_menu['title'])."\"><h3>".$_menu['title']."</h3>"
                        . "<ul class='children'>%s</ul></li>";

               

                $_html = "";
                $total_links = 0;
                foreach($_menu['children'] as $node => $__menu)
                {
                    if($MyAccessList->MeDasChancePasar($__menu['permiso'])):


                        $_html .= "<li class=\"".str_replace("/","-",trim($__menu['url'],"/"))."\"><a href=\"".$__menu['url']."\">".$__menu['etiqueta']."</a></li>";


                        $total_links++;

                    endif;
                }


                if($total_links > 0)
                {
                    $html .= sprintf($machote,$_html);
                }
            }
        }

        return $html;
    }
    
    
    function getFriendly($string)
    {
            $string = trim($string);
            $string = trim($string,"?");
            $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
            $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            $string = utf8_decode($string);
            $string = strtr($string, utf8_decode($a), $b);
            $string = strtolower($string);
            $string = preg_replace('#([^.a-z0-9]+)#i', '-', $string);
            $string = preg_replace('#-{2,}#','-',$string);
            $string = trim($string,"-");
            return $string;
    }

}
?>
