<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 9/3/18
 * Time: 11:05
 */

class Accidentes_model extends CI_Model
{


public function obtenernroaccidente($tipo_contrato){
    $query = $this->db->query("select MAX(nroaccidente) as numero from Accidentes where tipocontrato='".$tipo_contrato."';");
    //$row =$query->result_array();
    //$num= int($row['numero']);

    //return $num;



    foreach ($query->result_array() as $datasrow);{

        $nro = (int)$datasrow['numero'];
        if(is_null($nro)){

            return 1;
        }
      else  {return $nro+1;}


    }




}

}