<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
            $this->load->library('session');
        }

        public
        function index()
        {
            $data = array();
            $this->load->view('index.html', $data);
        }




public function iniciar_sesion_post(){
		$this->load->view('index.html');
        if ($this->input->post()) {
            $nombre = $this->input->post('username');
            $contrasena = $this->input->post('password');
            $this->load->model('usuario_model');
            $usuario = $this->usuario_model->validardatos($nombre, $contrasena);
            if ($usuario) {
                $usuario_data = array(
                    'id'=>$usuario->idusuario,
                    'nombre' => $usuario->usuario,
                    'categoria' => $usuario->categoria,

                    'logueado' => TRUE

                );
               $this->session->set_userdata($usuario_data);

                redirect('Welcome/logueado');
               
            } else {
                redirect('Welcome/index');
              
            }
        } else {
            $this->iniciar_sesion();
            
        }
    }
    public function logueado() {

            if($this->session->userdata('logueado')) {
            $data = array();
            $data['nombre'] = $this->session->userdata('nombre');

                if ($this->session->userdata('categoria') == '4') {
                    $this->load->view('visitante_home.html');
                }
            if ($this->session->userdata('categoria') == '1') {
                redirect('HomeControler/index');}
            if ($this->session->userdata('categoria') == '2') {
                redirect('Accidente_Home/index');}
            if ($this->session->userdata('categoria') == '3') {
                $this->load->view('visitante_home.html');}
                  if ($this->session->userdata('categoria') == '5') {
                    redirect('CambiodeFuncion_Home/index');}
                      if ($this->session->userdata('categoria') == '6' ||$this->session->userdata('categoria') == '7' ) {
                    redirect('Reportes_semanales/index');}
            }

            else {
                redirect('Welcome/index');
            }

}

    public function cerrar_sesion() {
        $usuario_data = array(
            'logueado' => FALSE
        );

       $this->session->sess_destroy();
        redirect('Welcome/index');
    }
}