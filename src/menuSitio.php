<?php
namespace Franky\Core;

class menuSitio{

    var $menu;
    function __construct() {
        $this->menu = array();
    }

    function setSeccion($permisos,$url,$etiqueta,$seccion="default")
    {
        global $MyRequest;

        $this->menu[$seccion][] = array($permisos,$MyRequest->link($url),$etiqueta);


    }

    function setTitle($title,$seccion="default")
    {


        $this->menu[$seccion]['title'] = $title;


    }

    function setArraySeccion($archivo,$name)
    {
        global $MyRequest;
        if(file_exists($archivo))
        {
            $menuXML = include($archivo);

            if(isset($menuXML['title'])){
                $this->setTitle($menuXML['title'],$name);
                unset($menuXML['title']);
            }
            foreach ($menuXML as $_menuXML)
            {

                $permisos = $_menuXML["permiso"];

                $url = $_menuXML["url"];


                $this->setSeccion($permisos,$url,$_menuXML["etiqueta"],$name);

            }
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

                  $html .= sprintf($formato,$__menu[1],$__menu[2]);

            }

        }
        return $html;
    }

    function getMenu()
    {
        global $MyAccessList;
        $html = "";
        $total_links = 0;
        foreach($this->menu as $name_menu => $_menu)
        {
            $_html = "<ul class='$name_menu'>";

            if(isset($_menu['title'])){
                 $_html .= "<li class=\"\"><b>".$_menu['title']."</b></li>";
                 unset($_menu['title']);


            }


            
            $total_links = 0;
            foreach($_menu as $__menu)
            {
                if($MyAccessList->MeDasChancePasar($__menu[0])):


                    $_html .= "<li class=\"".str_replace("/","-",trim($__menu[1],"/"))."\"><a href=\"".$__menu[1]."\">".$__menu[2]."</a></li>";


                    $total_links++;

                endif;
            }
            $_html .= "</ul>";

            if($total_links > 0)
            {
              $html .= $_html;
            }
        }

        return $html;
    }

}
?>
