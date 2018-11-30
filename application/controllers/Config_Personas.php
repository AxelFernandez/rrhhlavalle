<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 29/11/17
 * Time: 22:06
 */

class Config_Personas extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
    }

    public function index(){
        if(isset($_SESSION['id'])) {
            $crud = new grocery_CRUD();

            /* Seleccionamos el tema */
            $crud->set_theme('flexigrid');

            /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
            $crud->set_table('Personas');

            /* Le asignamos un nombre */
            $crud->set_subject('Personas');

            /* Asignamos el idioma español */
            $crud->set_language('spanish');
            $crud->set_relation('idDependencia','Dependencia','DependenciaDesc');
            $crud->display_as('idFunciones','Funcion');
            $crud->display_as('idDependencia','Direccion de ');
            /* Aqui le decimos a grocery que estos campos son obligatorios */
            $crud->required_fields(
                'nombreapellido',
                'dni',
                'modalidad',
                'dependencia',

                'DependenciaDesc'

            );

            /* Aqui le indicamos que campos deseamos mostrar */
            $crud->columns(
                'nombreapellido',
                'dni',
                'modalidad',
                'Funcion',
                'idDependencia'


            );
            $crud->display_as('nombreapellido','Nombre y Apellido');
            /* Generamos la tabla */


            if($_SESSION['categoria']=='4'){
                $crud->unset_edit();
                $crud->unset_add();
                $crud->unset_delete();
                $crud->unset_print();
                $crud->unset_export();


            }
            $output = $crud->render();

            $this->load->view('config_persona.html', $output);

        }

        else{

            redirect('Welcome/index');
        }    }

    public function listar()
    {



    }


}