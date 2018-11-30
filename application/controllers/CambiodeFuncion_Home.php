<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CambiodeFuncion_Home extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
    }

    public function index()
    {

        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('CambiodeFuncion');
        $crud->set_subject('Cambio de Funcion');
        $crud->set_language('spanish');
        $crud->set_relation('idPersonas', 'Personas', '{nombreapellido} ({dni})');
        $crud->add_action('Agregar Observaciones', 'https://png.icons8.com/office/16/000000/visible.png', 'CambiodeFuncion_home/observaciones');
        $crud->add_action('Programar nueva visita', 'https://png.icons8.com/office/16/000000/today.png', 'CambiodeFuncion_home/programarvisita');
        $crud->field_type('puestoanterior', 'hidden');
        $crud->callback_before_insert((array($this, 'guardarpuestoanterior')));
        $crud->callback_after_insert((array($this, 'guardarnuevopuesto')));
        $crud->field_type('estado', 'hidden');
        $crud->columns('idPersonas', 'fechacambio','nuevopuesto','puestoanterior','estado');
        $crud->unset_edit();
        $crud->unset_delete();
        $output = $crud->render();
        $this->load->view('v_cambiodefuncion.html', $output);
    }

    public function observaciones($primary_key)
    {
        $crud = new grocery_CRUD();

        $crud->set_theme('flexigrid');
        $crud->set_table('Observaciones_CambiodeFuncion');
        $crud->set_subject('Observaciones');
        $where = 'idCambiodeFuncion= ' . $primary_key;
        $crud->where($where);

        $crud->set_language('spanish');
        $crud->columns('descripcion', 'fecha', 'imagenes');
        $hoy = getdate();
        $hoys = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];
        $crud->unset_edit();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_delete();
        $crud->set_field_upload('imagenes', 'assets/uploads/accidentes');
        $crud->field_type('idusuario', 'hidden', $_SESSION['id']);
        $crud->field_type('idAccidentes', 'hidden', $primary_key);
        $crud->field_type('fecha', 'hidden', $hoys);
        $output = $crud->render();
        $this->load->view('v_cambiodefuncion.html', $output);


    }
    public function guardarpuestoanterior($post_array){

        $query = $this->db->query('SELECT * FROM personas where idPersonas="'.$post_array['idPersonas'].'";');
        $row = $query->row_array();
        $post_array['puestoanterior']= $row['Funcion'];
        $post_array['estado']='Con Cambio de Funcion';

        return $post_array;


    }
    public function guardarnuevopuesto($post_array, $primary_key){
        $fecha=explode('/',$post_array['fechaproximavisita']);
        $fechanew= $fecha[2]."-".$fecha[1]."-".$fecha[0];
        $this->db->query('UPDATE personas set funcion="'.$post_array['nuevopuesto'].'" where idPersonas="'.$post_array['idPersonas'].'";');
        $this->db->query('Insert into CambiodeFuncion_Visitas values (null,"'.$fechanew.'",'.$primary_key.','.$post_array['idPersonas'].');');

    }
    public function programarvisita($primary_key){

        $persona= $this->db->query('Select idPersonas from CambiodeFuncion WHERE idCambiodeFuncion='.$primary_key.'');
        $row= $persona->row();
        $crud = new grocery_CRUD();

        $crud->set_theme('flexigrid');
        $crud->set_table('CambiodeFuncion_Visitas');
        $crud->set_subject('Visitas');
        $where = 'idCambiodeFuncion= ' . $primary_key;
        $crud->where($where);
        $crud->columns('fecha');
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->set_language('spanish');
        $crud->field_type('idCambiodeFuncion', 'hidden',$primary_key);
        $crud->field_type('idPersonas', 'hidden',$row->idPersonas);
        $output = $crud->render();
        $this->load->view('v_cambiodefuncion.html', $output);


    }
    public function verCalendario(){
        $this->load->model('CambiodeFuncion_model');
  $json=  $this->CambiodeFuncion_model->Json_Events();
        $data ['json']=$json;

$this->load->view("v_cambiodeFuncion_calendario.html",$data);
    }
}

