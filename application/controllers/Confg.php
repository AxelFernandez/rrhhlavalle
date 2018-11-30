<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 29/11/17
 * Time: 22:06
 */

class Confg extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
    }
public function index(){
        //$this->load->view('Home.html');
        if ($_SESSION['categoria']=='3') {

            redirect('Confg/listar');
        }
        else{

            redirect('Welcome/index');
        }
        }
     public function listar()
    {
        if ($_SESSION['categoria']=='3') {

            $crud = new grocery_CRUD();

        /* Seleccionamos el tema */
        $crud->set_theme('flexigrid');

        /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
        $crud->set_table('usuario');

        /* Le asignamos un nombre */
        $crud->set_subject('Usuarios');

        $crud->change_field_type('contraseña', 'password');
        /* Asignamos el idioma español */
        $crud->set_language('spanish');

        /* Aqui le decimos a grocery que estos campos son obligatorios */
        $crud->required_fields(

            'usuario',
            'contraseña'

        );
        $crud->set_relation('categoria', 'categoria', 'categoriadesc');
        /* Aqui le indicamos que campos deseamos mostrar */
        $crud->columns(

            'usuario',
            'contraseña'

        );
        $crud->unset_edit();
        /* Generamos la tabla */
        $output = $crud->render();

        /* La cargamos en la vista situada en
        /applications/views/productos/administracion.php */
        $this->load->view('Config.html', $output);}
        else{

            redirect('Welcome/index');
        }
}

   


    }