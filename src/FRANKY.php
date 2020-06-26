<?php
namespace Franky\Core;

class FRANKY
{
	private $m_ajax;
	private $m_js;
        private $m_css;
	private $m_jquery;
	private $m_php;
	private $m_permisos;
	private $m_seccion;
        private $m_uiCommand;
        private $m_modulo;
        private $m_name;
        private $m_id;
	private $m_is_admin;
        private $m_layout;


	function __construct()
	{
		$this->m_id		= 0;
                $this->m_name		= "";
                $this->m_ajax		= "";
		$this->m_js		= "";
                $this->m_css		= "";
		$this->m_php		= "";
		$this->m_jquery		= "";
		$this->m_seccion 	= "";
		$this->m_permisos	= "";
                $this->m_layout	= "";
		$this->m_is_admin	= false;
    $this->m_modulo         = "";
    $this->m_uiCommand      = array();
	}

  function MyId()
	{
		return $this->m_id;
	}

  function MyName()
	{
		return $this->m_name;
	}


	function MyAjaxFile()
	{
		return $this->m_ajax;
	}

	function isAdmin()
	{
		return $this->m_is_admin;
	}


	function MyPHPFile()
	{
		return $this->m_php;
	}
        
        function MyLayout()
	{
		return $this->m_layout;
	}


        function MyModulo()
	{
		return $this->m_modulo;
	}

	function MyJSFile()
	{
		return $this->m_js;
        }

	function MyJQueyfile()
	{
		return $this->m_jquery;
        }

        function MyCSSFile()
	{
		return $this->m_css;
        }

  function setPHPFile($file)
	{
		$this->m_php = $file;
  }
    function setLayout($file)
	{
		$this->m_layout = $file;
  }

	function addCss($css)
	{
		 $this->m_css[] = $css;
	}

	function MySeccion()
	{
		return $this->m_seccion;
	}



  function MyPermisos()
	{
		return $this->m_permisos;
	}

  function pushCommand($key,$array_content)
  {
      $this->m_uiCommand[$key] = $array_content;
  }

  function getUiCommand($key=null)
  {

      if($key == null)
      {
          return $this->m_uiCommand;
      }

      return (isset($this->m_uiCommand[$key]) ? $this->m_uiCommand[$key] : false);
  }


  function getSeccion($seccion)
  {
          if(empty($seccion))
          {
             $seccion=HOME;
          }

          if(!isset($this->m_uiCommand[$seccion]))
          {
              $string_regex = "[a-z0-9-_.]";
              $match =  array();
              foreach($this->m_uiCommand as $keyUiCommand => $info_content)
              {
                  $regex = preg_replace("/\[[a-z0-9-_]+\]/i","$string_regex",$keyUiCommand);
                  $count_match = substr_count($regex, $string_regex);

                  $match[$keyUiCommand] = $count_match."--".$regex;

              }
              asort($match);



              if(!empty($match))
              {
                  foreach($match as  $key => $llave)
                  {
                      $str =  substr($llave,3);


                      if(preg_match("/^".str_replace(["/","."],["+\/","+\."],$str)."$/i", $seccion))
                      {
                          $seccion = $key;
                          break;
                      }
                  }
              }

          }

          return $seccion;
  }

	function crearMonstruo($seccion)
	{


		global $MySession;
    global $MyRequest;
    global $MyRequest;
    global $MyFlashMessage;
    global $MyMessageAlert;

    $seccion = $this->getSeccion($seccion);

        if(!isset($this->m_uiCommand[$seccion]) )
	  {
	      return false;
	  }


		$this->m_permisos	= $this->m_uiCommand[$seccion]['0'];
		$this->m_js		= $this->m_uiCommand[$seccion]['1'];
    $this->m_css		= $this->m_uiCommand[$seccion]['2'];
		$this->m_jquery		= $this->m_uiCommand[$seccion]['3'];
		$this->m_ajax		= $this->m_uiCommand[$seccion]['4'];
		$this->m_php		= $this->m_uiCommand[$seccion]['5'];
    $this->m_modulo		= $this->m_uiCommand[$seccion]['6'];
    $this->m_id		= $this->m_uiCommand[$seccion]['7'];
    $this->m_name		= $this->m_uiCommand[$seccion]['8'];

    $this->m_seccion = $seccion;


		$_seccion = explode("/",$seccion);
		if(PREFIDIOMA=="")
		{
			if($_seccion[0] == PATH_ADMIN)
			{
				$this->m_is_admin = true;

			}
		}
		else {

			if($_seccion[1] == PATH_ADMIN)
			{
				$this->m_is_admin = true;
			}
		}


    return true;
	}
}

?>
