<?php
namespace Franky\Core;

class MYDEBUG
{
    var $m_msg;
    var $m_session;
    var $m_server;
    var $m_debugOn;
    var $PHP_TUSAGE;
    var $PHP_RUSAGE;
    var $PHP_MEMORY_USAGE;

    function __construct()
    {
        $this->m_debugOn = 0;
        $this->I_Init();
    }

    function I_Init()
    {
          $this->m_msg = isset($GLOBALS['debug_msg']) ? $GLOBALS['debug_msg'] : array();
      $this->m_session = $_SESSION;
      $this->m_server = $_SERVER;

      $this->onRequestStart();
    }

    function setMessage( $msg, $key='default' )
    {

            $this->m_msg[$key][] = $msg;
            $GLOBALS['debug_msg'][$key][] = $msg;
    }

    function DebugEnabled()
    {
        return ($this->m_debugOn ? 1 : 0);
    }

    function SetDebug( $debugOn )
    {
        $this->m_debugOn = $debugOn;
    }

        private function getIncludeFiles()
        {
            $html = "<ul>";

            foreach (get_included_files() as $file)
            {
                $html .= "<li>".str_replace(PROJECT_DIR,"",$file)."</li>";
            }
            $html .= "</ul>";
            return $html;
        }

        private function getSession()
        {
            $html = "<ul>";

            foreach ($this->m_session as $k => $session)
            {
                $html .= "<li>".$k ."=".(is_array($session) ? json_encode($session) : $session)."</li>";
            }
            $html .= "</ul>";
            return $html;
        }

        private function getMessages($key="default")
        {

            $html = "<ul>";
            if(isset($this->m_msg[$key]))
            {
                $mensajes = $this->m_msg[$key];
                foreach($mensajes as $msg)
                {
                    $html .= "<li>" . $msg . "</li>";
                }
            }
            $html .= "</ul>";

            return $html;
        }

        private function getPhpErrors()
        {
            $html = "<ul>";
            if(file_exists(PHP_ERROR_LOG))
            {
                $fp = fopen(PHP_ERROR_LOG,"r");

                while(!feof($fp)) {

                $linea = fgets($fp);
                    if(!empty($linea))
                    {
                        $html .= "<li>".$linea . "</li>";
                    }
                }

                fclose($fp);
                unlink(PHP_ERROR_LOG);
            }
            $html .= "</ul>";
            return $html;
        }

        private function getRequest()
        {

            $html = "<ul>";

            foreach($this->m_server as $k => $v)
            {
        $html .= "<li>" . $k .": ".$v . "</li>";
            }
            $html .= "</ul>";

            return $html;
        }


        function onRequestStart() {
            if (function_exists('getrusage'))
            {
                $dat = getrusage();
                $this->PHP_TUSAGE =  microtime(true);
                $this->PHP_RUSAGE = $dat["ru_utime.tv_sec"]*1e6+$dat["ru_utime.tv_usec"];
            }
            if (function_exists('memory_get_usage'))
            {
                $this->PHP_MEMORY_USAGE = memory_get_usage();
            }
        }

        function getCpuUsage() {


            $memory = "NS";
            if (function_exists('getrusage'))
            {
                $dat = getrusage();
                $dat["ru_utime.tv_usec"] = ($dat["ru_utime.tv_sec"]*1e6 + $dat["ru_utime.tv_usec"]) - $this->PHP_RUSAGE;
                $time = (microtime(true) - $this->PHP_TUSAGE ) * 1000000;

                // cpu per request
                if($time > 0) {
                    $cpu = sprintf("%01.2f", ($dat["ru_utime.tv_usec"] / $time) * 100);
                } else {
                    $cpu = '0.00';
                }
            }
            $memory = "NS";
            if (function_exists('memory_get_usage'))
            {
                $unit=array('b','kb','mb','gb','tb','pb');

                $memory = memory_get_usage() - $this->PHP_MEMORY_USAGE;
                $memory = @round($memory/pow(1024,($i=floor(log($memory,1024)))),2).' '.$unit[$i];

            }
            $html = "<ul><li>Uso de CPU: $cpu</li><li>Uso de memoria: $memory</li></ul>";
            return $html;
        }


    function Dump()
    {
        $this->I_Init();
        if($this->DebugEnabled())
        {
            return array("messages" => $this->getMessages(),
                            "phpErrors" => $this->getPhpErrors(),
                            "includeFiles" => $this->getIncludeFiles(),
                            "messagesSQL" => $this->getMessages("sql"),
                            "session" => $this->getSession(),
                            "request" => $this->getRequest(),
                            "cpu" => $this->getCpuUsage(),
                            "debugEnabled" => $this->DebugEnabled());
        }

        return array("debugEnabled" => $this->DebugEnabled());


    }

    function DumpArray( $arrayName, $a )
    {
        foreach( $a as $k => $v )
        {
            $this->DebugMessage( "$arrayName.[$k] = [$v]" );
        }
    }


}
?>
