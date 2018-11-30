<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 5/2/18
 * Time: 09:18
 */

class Tratamiento extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');


    }

    public function index(){
        $data ['Mensaje']='';

        $this->load->view('v_accidentes_tratamiento.html',$data);
    }

public function seleccionAceptar(){
    $hoy = getdate();
    $pk = $this->session->tempdata('primary');

    $hoys = $hoy['year']. '-'.$hoy['mon'].'-'.$hoy['mday'];
   $idusuario= $this->session->userdata('id');
$query= "Update Accidentes set fechaaceptado='".$hoys."', idusuario_aceptado=".$idusuario.", estado='Tratamiento Aceptado' where idAccidentes=".$pk.";";
    if ($this->db->simple_query($query))
    {

        $this->session->unset_tempdata('primary');
        redirect('Welcome/Logueado');
    }
    else
    {
        $data ['Mensaje']='Hubo un error al Actualizar, intenta cerrar sesion y volver a intentar';
        $this->load->view('v_accidentes_tratamiento.html',$data);
    }
}


public function seleccionRechazo(){
    $hoy = getdate();
    $pk = $this->session->tempdata('primary');

    $hoys = $hoy['year']. '-'.$hoy['mon'].'-'.$hoy['mday'];
    $idusuario= $this->session->userdata('id');
    $query= "Update Accidentes set fechaaltamedica='".$hoys."', idusuario_altamedica=".$idusuario.", estado='Tratamiento Rechazado', altamedica=TRUE where idAccidentes=".$pk.";";
    if ($this->db->simple_query($query))
    {

        $this->session->unset_tempdata('primary');
        redirect('Welcome/Logueado');
    }
    else
    {
        $data ['Mensaje']='Hubo un error al Actualizar, intenta cerrar sesion y volver a intentar';
        $this->load->view('v_accidentes_tratamiento.html',$data);
    }

    }


    public function seleccionAlta(){


        $hoy = getdate();
        $pk = $this->session->tempdata('primary');

        $hoys = $hoy['year']. '-'.$hoy['mon'].'-'.$hoy['mday'];
        $idusuario= $this->session->userdata('id');
        $query= "Update Accidentes set fechaaltamedica='".$hoys."', idusuario_altamedica=".$idusuario.", estado='Tratamiento Finalizado', altamedica=TRUE where idAccidentes=".$pk.";";
        if ($this->db->simple_query($query))
        {

            $this->session->unset_tempdata('primary');
            redirect('Welcome/Logueado');
        }
        else
        {
            $data ['Mensaje']='Hubo un error al Actualizar, intenta cerrar sesion y volver a intentar';
            $this->load->view('v_accidentes_tratamiento.html',$data);
        }    }

}
