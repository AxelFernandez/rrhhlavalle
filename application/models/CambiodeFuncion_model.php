<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 13/4/18
 * Time: 10:55
 */

class CambiodeFuncion_model extends CI_Model

{
    public function Json_Events(){
       $query = $this->db->query('Select Personas.nombreapellido as "title", fecha as "start" from CambiodeFuncion_Visitas 
INNER JOIN Personas on CambiodeFuncion_Visitas.idPersonas= Personas.idPersonas;');
$arr = array();

        foreach ($query->result_array() as $row)
        {
            $arr[] = $row;
        }
    $json= json_encode($arr);
    return $json;

    }



}