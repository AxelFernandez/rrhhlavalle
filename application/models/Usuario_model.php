<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 28/11/17
 * Time: 23:42
 */

class Usuario_model extends CI_Model
{


    public function validardatos($user,$pass){



        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('usuario', $user);
        $this->db->where('contraseña', $pass);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }

    public function cambiarcontraseña($user, $pass, $newpass){

        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('usuario', $user);
        $this->db->where('contraseña', $pass);
        $consulta = $this->db->get();
        foreach ($consulta->result_array() as $datarow){

        if ($datarow['contraseña']==$pass){
            $this->db->query("Update usuario set contraseña='".$newpass."' where idusuario='".$datarow['idusuario']."';");
          return true;

        }
        else{return false;}
        }

    }


}