<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 21/2/18
 * Time: 14:30
 */

class cambiarpass extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }

    public function index(){
         if(isset($_SESSION['id'])) {

            $data['titulo']='Se cerrará la sesion una vez que la contraseña haya sido cambiada';
        $this->load->view('cambiarpass-old.html', $data);}
        else{redirect('Welcome/index');}
    }

public function cambiar()
{
  if (isset($_SESSION['id'])) {
        $pass = $this->input->post('contraseñaactual');
        $newpass = $this->input->post('nuevacontraseña');
        $this->load->model('usuario_model');
        $user = $this->session->userdata('nombre');
        $boolean = $this->usuario_model->cambiarcontraseña($user, $pass, $newpass);
        if ($boolean) {


            redirect('Welcome/cerrar_sesion');

        } else {
            $data['titulo'] = 'La contraseña es incorrecta, compruebe la informacion';
            $this->load->view('cambiarpass-old.html', $data);


        }

    }
    else{redirect('Welcome/index');}

}

    }





