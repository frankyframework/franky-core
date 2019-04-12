<?php
namespace Franky\Core;

class Plantilla
{
        var $m_vars;
        var $tpl_file;
        var $m_html;


        function Plantilla()
        {
                        $this->I_init();
        }

        function I_init()
        {
                $this->tpl_file = "";
                $this->m_vars	= "";
                $this->m_html	= "";
        }

        function asigna_variables($m_vars)
	       {


            $this->m_vars = (empty($this->m_vars)) ? $m_vars : $this->m_vars . $m_vars;
        }

    	function muestra($template)
    	{

            $this->tpl_file = $template;

            if(is_file($template))
            {
              if (!($fd = @fopen($this->tpl_file, 'r')))
              {

              }
              else
              {
                  $this->template_file = fread($fd, filesize($this->tpl_file));
                  fclose($fd);
                  $this->m_html = $this->template_file;
                }
              }
            else{
              $this->m_html = $template;
            }
                $this->m_html = str_replace ("'", "\'", $this->m_html);
                $this->m_html = preg_replace('#\{([a-z0-9\-_]*?)\}#is', "' . $\\1 . '", $this->m_html);
                  $this->m_html = preg_replace('#\%7B([a-z0-9\-_]*?)\%7D#is', "' . $\\1 . '", $this->m_html);
                reset ($this->m_vars);
                while (list($key, $val) = each($this->m_vars)) {
                    $$key = $val;
                }
                eval("\$this->m_html = '$this->m_html';");
                reset ($this->m_vars);
                while (list($key, $val) = each($this->m_vars))
                {
                    unset($$key);
                }

                $this->m_html=str_replace ("\'", "'", $this->m_html);
                return $this->m_html;

	}
}
?>
