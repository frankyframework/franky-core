<?php
namespace Franky\Core;

class MessageAlert
{
	var $m_message_key;
        var $m_uiMessage;

	function __construct()
	{
		$this->m_message_key = "";
                $this->m_uiMessage = array();
        }

        function pushMessage($key,$value)
        {

            $this->m_uiMessage[$key] = $value;
        }

	function Message($key,$input="")
	{
		
            if(!isset($this->m_uiMessage[$key]))
            {
                return $this->m_uiMessage["error_message_key"];
            }
            if(empty($input))
            {
                return $this->m_uiMessage[$key];
            }
            else
            {
                return sprintf($this->m_uiMessage[$key], $input);
            }
	}
}
?>
