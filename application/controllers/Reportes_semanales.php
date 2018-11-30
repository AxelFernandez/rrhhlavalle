<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 16/4/18
 * Time: 12:27
 */

class Reportes_semanales extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->database();

    }

    public function index()
    {


        $hoy = getdate();
        $hoys = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];

        $crud = new grocery_CRUD();


        $crud->set_theme('flexigrid');

        $crud->set_table('Reportessemanal');

        if ($_SESSION['categoria'] == '6') { //Administrador de Reportes
            $crud->columns('reporte', 'fecha', 'idusuario');

            $crud->set_relation('idusuario','usuario','usuario');
            $crud->display_as('idusuario','Nombre de Usuario');
        }

        if ($_SESSION['categoria'] == '7') { //Oficina
            $crud->columns('reporte', 'fecha');

            $where = "idusuario=" . $_SESSION['id'];
            $crud->where($where);
            $crud->unset_delete();
        }

        $crud->set_subject('Reportes Semanales');

        $crud->set_language('spanish');

        $crud->field_type('idusuario', 'hidden', $_SESSION['id']);

        $crud->field_type('fecha', 'hidden', $hoys);

        $quer= $this->db->query("Select count(mensaje) as 'cuenta' from Mensaje where idusuario=".$_SESSION['id']." and leido='No Leido';");
        $num= $quer->row();
        $this->session->set_flashdata('num', $num->cuenta);

        $output = $crud->render();

        $this->load->view('v_reportes_semanales.html', $output);

    }

public function mensajes(){
    $crud = new grocery_CRUD();


    $crud->set_theme('flexigrid');

    $crud->set_table('Mensaje');

    if ($_SESSION['categoria'] == '6') { //Administrador de Reportes

        $crud->set_relation('idusuario','usuario','usuario');
        $crud->display_as('idusuario','Nombre de Usuario');
        $crud->field_type('leido', 'hidden','No leido');

        $num=0;
        $this->session->set_flashdata('num', $num);

    }

    if ($_SESSION['categoria'] == '7') { //Oficina
        $crud->columns('mensaje');

        $where = "idusuario=" . $_SESSION['id'];
        $crud->where($where);
        $crud->unset_delete();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_export();
        $crud->unset_print();
         $crud->set_read_fields('mensaje');

        $this->db->query("Update Mensaje set leido='Leido' where idusuario=".$_SESSION['id'].";");
        $quer= $this->db->query("Select count(mensaje) as 'cuenta' from Mensaje where idusuario=".$_SESSION['id']." and leido='No Leido';");
        $num= $quer->row();

        $this->session->set_flashdata('num', $num->cuenta);

    }

    $crud->set_subject('Mensajes');

    $crud->set_language('spanish');

    $output = $crud->render();



    $this->load->view('v_reportes_semanales.html', $output);


}

}
