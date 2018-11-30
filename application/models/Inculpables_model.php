<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 13/4/18
 * Time: 11:58
 */

class Inculpables_model extends CI_Model
{
    public function Json_Events(){
        $query = $this->db->query('Select Personas.nombreapellido as "title", fechavencimiento as "start" from Prescripciones 
INNER JOIN Personas on Prescripciones.idPersonas= Personas.idPersonas;');
        $arr = array();

        foreach ($query->result_array() as $row)
        {
            $arr[] = $row;
        }
        $json= json_encode($arr);
        return $json;

    }
    public function diasAcumulados($name, $fecha){


            $string= 'Si el certificado tiene continuidad se detallarán a continuación: <br><br>';
            $query2 = $this->db->query('Select * from Prescripciones where idPersonas='.$name.';');
            $query3 = $this->db->query('Select * from Prescripciones where idPersonas='.$name.';');
            $string= $string."Certificados Anteriores : <br>";


            foreach ($query2->result_array() as $row2)
            {
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $row2['fechavencimiento'] ) ) ;
                $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
                if ($fecha==$row2['fechavencimiento']){
                    $string= $string." El Certificado presentado el dia ".$row2['fechaprescripcion']." por ".$row2['prescripcion']." Expiró el dia ".$row2['fechavencimiento'].". Dias: ".$row2['reposo']."<br>";

                }
                foreach ($query3->result_array() as $row3){


                   if($nuevafecha==$row3['fechaprescripcion']){

                      $string= $string." El Certificado presentado el dia ".$row2['fechaprescripcion']." por ".$row2['prescripcion']." Expiró el dia ".$row2['fechavencimiento'].". Dias: ".$row2['reposo']."<br>";



                   }
                }
            }


            return $string;

        }

}