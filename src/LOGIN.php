<?php
namespace Franky\Core;

class LOGIN
{
        protected $m_inputs;
        protected $m_user;
        protected $m_pass;
        protected $m_tabla;
        protected $m_extra_valids;

        function __construct($tabla = "",$user = "",$pass="",$extra = array(),$conexion = 'conexion_bd')
        {
                $this->m_ibd = new \vendor\database\IBD(new \vendor\database\configure,$conexion, new \vendor\core\MYDEBUG);
                $this->m_tabla = $tabla;
                $this->m_user = $user;
                $this->m_pass = $pass;
                if(is_array($extra))
                {
                    $this->m_extra_valids = $extra;
                }
                if(!empty($tabla))
                {

                    if($this->setInputs($tabla) == LOGIN_SUCCESS)
                    {
                        foreach($this->m_inputs as $input)
                        {
                            $this->{$input} = "";
                        }
                    }
                }
        }

        private function setInputs($tabla)
        {
                $consulta  = "DESCRIBE $tabla";

                if (($result = $this->m_ibd->Query("inputs", $consulta))!= IBD_SUCCESS)
                {

                        return $result;
                }

                if (($result = $this->m_ibd->NumeroRegistros("inputs")) < 1 )
                {
                        $this->m_ibd->Liberar("inputs");

                        return LOGIN_BADLOGIN;
                }
                else
                {
                    while($registro = $this->m_ibd->Fetch("inputs"))
                    {
                       $this->m_inputs[] = $registro['Field'];

                    }
                }

                $this->m_ibd->Liberar("inputs");
                return LOGIN_SUCCESS;
        }

        public function getInputs()
        {
            return $this->m_inputs;
        }

        public function setLogin($usuario, $hash)
        {

                $consulta  = "SELECT *";
                $consulta .= "FROM ".$this->m_tabla." WHERE (";
                $sql_user = "";
                    if(is_array($this->m_user))
                    {
                        foreach($this->m_user as $input_user)
                        {
                            $sql_user .= $input_user."='$usuario' or ";
                        }
                        $sql_user = trim($sql_user," or ");
                    }
                    else
                    {
                        $sql_user = $this->m_user."='$usuario'";
                    }

                $consulta .= $sql_user.") AND ".$this->m_pass."='$hash' ";


                if(!empty($this->m_extra_valids))
                {
                    $sql_extra = "";
                    foreach($this->m_extra_valids as $input_extra => $value)
                    {
                        $sql_extra .= $input_extra.(!empty($value) ? "='$value'" : "")." and ";
                    }
                    $sql_extra = trim($sql_extra," and ");
                    $consulta .= " and ".$sql_extra;

                }

                if (($result = $this->m_ibd->Query("Login", $consulta))!= IBD_SUCCESS)
                {

                        return $result;
                }

                if (($result = $this->m_ibd->NumeroRegistros("Login")) < 1 )
                {

                        $this->m_ibd->Liberar("Login");
                        return LOGIN_BADLOGIN;
                }

                $registro = $this->m_ibd->Fetch("Login");

                if ( ! $registro )
                {
                    $this->m_ibd->Liberar("Login");
                    return LOGIN_DBFAILURE;
                }
                else
                {
                    foreach ($this->m_inputs as $input)
                    {
                        $this->{$input} 		= $registro[$input];
                    }


                }
                $this->m_ibd->Liberar("Login");

                return LOGIN_SUCCESS;


        }



}
?>
