<?php
namespace Franky\Core;

class ObserverManager
{
    var $observer;

    function __construct() {
        $this->observer = isset($GLOBALS['observer']) ? $GLOBALS['observer'] : array();
    }

    public function dispatch($event,$vars=array())
    {
      if(isset($this->observer[$event]))
      {
        foreach($this->observer[$event] as $function)
        {
          if(function_exists ( $function ))
          {
            call_user_func_array($function,$vars);
          }
        }
      }

    }

    public function addObserver($event,$function)
    {
      $this->observer[$event][] = $function;
      $GLOBALS['observer'][$event][] = $function; 
    }

}
?>
