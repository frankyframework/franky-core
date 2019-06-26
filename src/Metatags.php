<?php
namespace Franky\Core;

class Metatags
{
    var $titulo;
    var $descripcion;
    var $keywords;
    var $autor;
    var $js;
    var $css;
    var $code;
    var $hreflang;
    var $image;
    var $vars;

    function __construct() {
        $this->titulo = "";
        $this->descripcion = "";
        $this->keywords = "";
        $this->autor = "";
        $this->js = array();
        $this->vars = array();
        $this->css = array();
        $this->code = array();
        $this->hreflang = array();
        $this->image = "";
        
  
    }

    function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }
    
    function setVars($vars)
    {
        $this->vars = $vars;
    }
    function setAuthor($autor)
    {
        $this->autor = $autor;
    }

    function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }
    function setCss($css)
    {
        $this->css[] = $css;
    }
    function setJs($js)
    {
        $this->js[] = $js;
    }
    function setHreflang($lang,$link)
    {
        $this->hreflang[$lang] = $link;
    }

    function setCode($code)
    {
        $this->code[] = $code;
    }
    function setImage($image)
    {
        $this->image = $image;
    }
    function getTitulo()
    {
        $plantilla =  new Plantilla();
        $plantilla->asigna_variables($this->vars);
        return $plantilla->muestra($this->titulo);
    }
      function getVars()
    {
        return $this->vars;
    }
    function getAuthor()
    {
        return $this->autor;
    }
    function getDescripcion()
    {
        $plantilla =  new Plantilla();
        $plantilla->asigna_variables($this->vars);
        return $plantilla->muestra($this->descripcion);
    }

    function getKeywords()
    {
        $plantilla =  new Plantilla();
        $plantilla->asigna_variables($this->vars);
        return $plantilla->muestra($this->keywords);
    }
    function getJs($meta = true)
    {
        if($meta)
        {
            $html = "<script src='%s'  ></script>";
            $js = "";
            if(!empty($this->js ))
            {
                foreach($this->js as $file)
                {
                    $js .= sprintf($html,$file);
                }
            }
            return  $js;
        }
        return $this->js;
    }
    function getCss()
    {
        $html = "<link href=\"%s\" rel=\"stylesheet\" type='text/css'  />";
        $css = "";
        if(!empty($this->css ))
        {
            foreach($this->css as $file)
            {
                $css .= sprintf($html,$file);
            }
        }
        return  $css;

    }

    function getCode()
    {
        $html = "";

        if(!empty($this->code ))
        {
            foreach($this->code as $code)
            {
                $plantilla =  new Plantilla();
                $plantilla->asigna_variables($this->vars);
                $html .=  $plantilla->muestra($code)."\n";
 
            }
        }
        return  $html;

    }

    function getHreflang()
    {
        $html = "";
        $base = "<link rel=\"alternate\" hreflang=\"%s\" href=\"%s\">";
        if(!empty($this->hreflang))
        {
            foreach($this->hreflang as $lang => $code)
            {
                $html .= sprintf($base,$lang,$code);
            }
        }
        return  $html;

    }
    
     function getImage()
    {
        return $this->image;
    }

}
?>
