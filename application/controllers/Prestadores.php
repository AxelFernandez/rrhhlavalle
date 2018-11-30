<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 20/3/18
 * Time: 12:15
 */

class Prestadores extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');

    }

    public function index()
    {
        $crud = new grocery_CRUD();

        /* Seleccionamos el tema */
        $crud->set_theme('flexigrid');

        /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
        $crud->set_table('Prestadores');

        /* Le asignamos un nombre */
        $crud->set_subject('Prestadores');

        /* Asignamos el idioma español */
        $crud->set_language('spanish');
        $output = $crud->render();

$this->load->view('v_accidentes_prestadores.html',$output);
    }
}