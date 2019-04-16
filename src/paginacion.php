<?php
namespace Franky\Core;
use Franky/corerequest;

class paginacion
{
    private $show_tampag;
    private $show_pages;
    private $total;
    private $page;
    private $orden;
    private $campo_orden;
    private $tamanos_validos;
    private $tamppage_default;
    private $request;

    function __construct() {
        $this->show_tampag = true;
        $this->show_pages = true;
        $this->total = 0;
        $this->page = 1;
        $this->paginas_visibles = 6;
        $this->tamppage_default = 25;
        $this->orden = "";
        $this->campo_orden = "ASC";
        $this->tamanos_validos = array(10, 25,50,100);
        $this->request = new request();
    }


    function getPaginacion()
    {

        $u = parse_url($_SERVER["REQUEST_URI"]);
        $uri = $u["path"]."?";

        $request = $this->request->getRequest();
        if(!empty($request))
        {
            foreach ($request as $k => $v)
            {
                if(!in_array(strtolower($k),array("my_url_friendly","page","tampag","order","por")))
                {
                  if(is_array($v))
                  {
                    foreach($v as $_v)
                    {
                      $uri .= $this->request->Sanitizacion($k)."[]=".$_v."&amp;";
                    }

                  }
                  else {
                      $uri .= $this->request->Sanitizacion($k)."=".$v."&amp;";
                  }

                }
                elseif(strtolower($k) == "order")
                {
                    $uri .= "order=".$this->orden."&amp;";
                }
                elseif(strtolower($k) == "por")
                {
                    $uri .= "por=".$this->campo_orden."&amp;";
                }
                elseif(strtolower($k) == "tampag")
                {
                    $uri .= "tampag=".$this->tamppage_default."&amp;";
                }
            }
        }


        $terminamosconel        = $this->page * $this->tamppage_default;
        $maxPage		= ceil($this->total/$this->tamppage_default);


        $html = "";

        if($this->total <= $maxPage)
        {
            return $html;
        }
        $html .= "<div>";
        if ($terminamosconel >= $this->total)
        {
                $terminamoscon = $this->total;
        }
        else
        {
                $terminamoscon = $terminamosconel;
        }
        $empezamoscon = $terminamosconel - ($this->tamppage_default-1);

        if ($this->page > 1)
        {
                $page = $this->page - 1;
                $prev = " <a  class=\"nav_left\"  href=\"".$uri."page=$page\">";
                $prev .= "Anterior</a> ";
        }
        else
        {
                $prev  = '';
        }

        if ($this->page < $maxPage)
        {
                $page = $this->page + 1;
                $next = " <a  class=\"nav_right\"  href=\"".$uri."page=$page\"> ";
                $next .= "Siguiente </a> ";
        }
        else
        {
                $next = '';
        }

        $html .= $prev."<span class='cont_paginacion_prev_next'>"."  $empezamoscon - $terminamoscon (de $this->total) "."</span>".$next;

        if($this->tamppage_default <= $this->total && $this->show_pages)
        {

                $html .= "";


                if ($this->page > $this->paginas_visibles)
                    $html .= "<a  href=\"".$uri."page=1\" >1</a>... ";



                if ($this->page< $this->paginas_visibles)
                {
                        for ($i=1; $i<$this->page; $i++)
                                $html .= "<a  href=\"".$uri."page=$i\" > $i</a> ";
                }
                else
                {
                        for ($i=$this->page-($this->paginas_visibles-1); $i<$this->page; $i++)
                                $html .= "<a  href=\"".$uri."page=$i\" > $i</a> ";
                }

                $html .= "<span>[<b>".$this->page."</b>]</span>";

                if($this->page +$this->paginas_visibles > $maxPage)
                {
                        for ($i=$this->page+1; $i<=$maxPage; $i++)
                                $html .= "<a  href=\"".$uri."page=$i\" > $i</a> ";
                }
                else
                {
                        for ($i=$this->page+1; $i<=$this->page+$this->paginas_visibles; $i++)
                                $html .= "<a  href=\"".$uri."page=$i\" > $i</a> ";
                }
                if (($this->page+$this->paginas_visibles)<$maxPage)
                        $html .= "...<a  href=\"".$uri."page=$maxPage\" > $maxPage</a>";





        }
         $html .= "</div>";
        if($this->show_tampag)
        {
            $html .= "<div><form name='tamanopag'  action='' method='post'>
            <span class='datos'>Mostrar</span>
            <select name='tam' >";
            foreach($this->tamanos_validos as $v)
            {
                $html .= "<option value='$v' ".($this->tamppage_default == "$v" ? "selected=\"selected\" " : "")." class=\"datos\">$v</option>";
            }

            $html .= " </select>
            <span class='datos'>Registros</span>
            </form></div>";


            $html .= "
            <script>
            $('form[name=tamanopag]').find('select[name=tam]').change(function(){
              window.location='".preg_replace("/tampag=(\d+)\&amp;/", "", $uri)."page=1&tampag='+$(this).val();
            });
            </script>
            ";
        }
        return $html;
    }


    function setShowTampage($val)
    {
         $this->show_tampag = $val;
    }
    function getShowTampage()
    {
         return $this->show_tampag;
    }

    function setShowPages($val)
    {
         $this->show_pages = $val;
    }
    function getShowPages()
    {
         return $this->show_pages;
    }

     function setTotal($val)
    {
         $this->total = $val;
    }
    function getTotal()
    {
         return $this->total;
    }

    function setPage($val)
    {
         $this->page = $val;
    }
    function getPage()
    {
         return $this->page;
    }
    function setPaginasVisibles($val)
    {
         $this->paginas_visibles = $val;
    }
    function getPaginasVisibles()
    {
         return $this->paginas_visibles;
    }

    function setTampageDefault($val)
    {
         $this->tamppage_default = $val;
    }
    function getTampageDefault()
    {
         return $this->tamppage_default;
    }

    function setOrden($val)
    {
         $this->orden = $val;
    }
    function getOrden()
    {
         return $this->orden;
    }
    function setCampoOrden($val)
    {
         $this->campo_orden = $val;
    }
    function getCampoOrden()
    {
         return $this->campo_orden;
    }
    function setTamanosValidos($val)
    {
         $this->tamanos_validos = $val;
    }
    function getTamanosValidos()
    {
         return $this->tamanos_validos;
    }
}
?>
