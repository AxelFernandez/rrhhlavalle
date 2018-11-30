<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 28/11/17
 * Time: 23:38
 */

class HomeControler extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('grocery_CRUD');

    //    $this->load->library('export_excel');

    }

    public function index(){
if($_SESSION['categoria']=='1' || $_SESSION['categoria']=='3' || $_SESSION['categoria']=='4') {
    redirect('HomeControler/listar');
}
else{redirect('Welcome/index');}
    }

    



    public function listar()
    {
    if($_SESSION['categoria']=='1' || $_SESSION['categoria']=='3' || $_SESSION['categoria']=='4') {

        $crud = new grocery_CRUD();

        /* Seleccionamos el tema */
        $crud->set_theme('flexigrid');

        /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
        $crud->set_table('Prescripciones');
      //  $crud->order_by('idprescripcion');

        $hoy = getdate();
        $hoys = $hoy['year']. '-'.$hoy['mon'].'-'.$hoy['mday'];
        $crud->order_by('fechavencimiento');
        $where = 'fechavencimiento > "'.$hoys.'"';
        $crud->where($where);
            $crud->columns(array('idPersonas','prescripcion', 'reposo','fechaprescripcion',  'fechavencimiento', 'fecharecibido',   'Fotos'));
        /* Le asignamos un nombre */
        $crud->set_subject('Prescripciones (Pendientes)');
        /* Asignamos el idioma español */
        $crud->set_language('spanish');

      //  $crud->set_relation('idusuario','usuario', 'usuario');
        $crud->required_fields('prescripcion','fecharecibido','reposo','fechaprescripcion','fechavencimiento','idPersonas','Fotos');

        $crud->set_field_upload('Fotos','assets/uploads/files');
        $crud->display_as('fechaprescripcion','Fecha de Prescripcion');
        $crud->display_as('fecharecibido','Fecha de Recepcion');
        $crud->display_as('fechavencimiento','Fecha de Alta');
        $crud->display_as('idPersonas','Nombre y Apellido');
        $crud->display_as('reposo','Reposo');
        $crud->field_type('idusuario','invisible' );
      //  $crud->field_type('fecharecibido','invisible' );
        $crud->callback_before_insert(array($this,'callback_usuario'));
       // $crud->callback_after_insert(array($this,'descargar'));
        if($_SESSION['categoria']=='4'){
                $crud->unset_edit();
                $crud->unset_add();
                $crud->unset_delete();
                $crud->unset_print();
                $crud->unset_export();
                $crud->set_relation('idPersonas','Personas', 'nombreapellido');


            }
            else{
                $crud->add_action('Ver Reporte', 'https://sasktenders.ca/App_Themes/SaskTenders/Images/pdf.png', 'HomeControler/descargar');
                $crud->set_relation('idPersonas', 'Personas', '{dni} ({nombreapellido}) ');


            }
        $output = $crud->render();

        /* La cargamos en la vista situada en
        /applications/views/productos/administracion.php */
        $this->load->view('Home.html', $output);

}
    }
    public function callback_usuario($post_array){
        $post_array['idusuario']=$this->session->userdata('id');
       // $hoy = getdate();


        //$post_array['fecharecibido']= $hoy['year']."/".$hoy['mon']."/".$hoy['mday'];

        return $post_array;

}
        public function descargar($primary_key){

        if($_SESSION['categoria']=='1' || $_SESSION['categoria']=='3') {




            $query = $this->db->query("select * from Prescripciones where idPrescripciones=".$primary_key.";");
            foreach ($query->result_array() as $row)
            {
                $querypersonas = $this->db->query("Select * from Personas where idPersonas=".$row['idPersonas']."");

                $rows = $querypersonas->row();

                $queryusuario = $this->db->query("Select * from usuario where idusuario=".$row['idusuario']."");

                $rowusu = $queryusuario->row();

                $this->load->library('Pdf.php');

                $this->pdf = new Pdf();
                $this->pdf->AddPage();
                $this->pdf->SetFont('Arial','I', 10);
                //Margen decorativo iniciando en 0, 0
                $this->pdf->Image('assets/uploads/partes-legajo.jpg', 0,0, 210, 295, 'JPG');

                $this->pdf->SetXY(52, 59);
                $this->pdf->MultiCell(65, 5, utf8_decode($rows->nombreapellido), 0, 'C');

                $this->pdf->SetXY(40, 68);
                $this->pdf->MultiCell(0, 5, utf8_decode($row['prescripcion']), 0, 'C');

                $this->pdf->SetXY(52, 77);
                $this->pdf->MultiCell(65, 5, utf8_decode($row['reposo'].' Dias'), 0, 'C');

                $this->pdf->SetXY(40, 86);
                $this->pdf->MultiCell(65, 5, utf8_decode($row['fechaprescripcion']), 0, 'C');

                $this->pdf->SetXY(130, 86);
                $this->pdf->MultiCell(65, 5, utf8_decode($row['fecharecibido']), 0, 'C');

                $this->pdf->SetXY(45, 265);
               $this->pdf->MultiCell(65, 5, utf8_decode($rowusu->usuario), 0, 'C');



                $this->pdf->Output();
}
}
        else{redirect('Welcome/index');}

        }

    public function listar2()
    {
            if($_SESSION['categoria']=='1' || $_SESSION['categoria']=='3' || $_SESSION['categoria']=='4') {

        $crud = new grocery_CRUD();

        /* Seleccionamos el tema */
        $crud->set_theme('flexigrid');

        /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
        $crud->set_table('Prescripciones');
        //  $crud->order_by('idprescripcion');


            $crud->columns(array('idPersonas','prescripcion', 'reposo','fechaprescripcion',  'fechavencimiento', 'fecharecibido',   'Fotos'));
        /* Le asignamos un nombre */
        $crud->set_subject('Prescripciones');
        /* Asignamos el idioma español */
        $crud->set_language('spanish');

        //  $crud->set_relation('idusuario','usuario', 'usuario');
        $crud->required_fields('prescripcion','reposo','fechaprescripcion','fechavencimiento','idPersonas','Fotos');

        $crud->set_field_upload('Fotos','assets/uploads/files');
        $crud->display_as('fechaprescripcion','Fecha de Prescripcion');
        $crud->display_as('fecharecibido','Fecha de Recepcion');
        $crud->display_as('fechavencimiento','Fecha de Alta');
        $crud->display_as('idPersonas','Nombre y Apellido');
        $crud->display_as('reposo','Reposo');
        $crud->field_type('idusuario','invisible' );
        $crud->field_type('fecharecibido','invisible' );
        $crud->callback_before_insert(array($this,'callback_usuario'));
        $crud->order_by('fecharecibido','asc');
        $crud->unset_add();

        // $crud->callback_after_insert(array($this,'descargar'));
        if($_SESSION['categoria']=='4'){
                $crud->unset_edit();
                $crud->unset_add();
                $crud->unset_delete();
                $crud->unset_print();
                $crud->unset_export();

                $crud->set_relation('idPersonas','Personas', 'nombreapellido');

            }
            else{
                $crud->add_action('Ver Reporte', 'https://sasktenders.ca/App_Themes/SaskTenders/Images/pdf.png', 'HomeControler/descargar');
                        $crud->set_relation('idPersonas','Personas', '{dni} ({nombreapellido}) ');


            }        $output = $crud->render();

        /* La cargamos en la vista situada en
        /applications/views/productos/administracion.php */
        $this->load->view('Home.html', $output);


    }
else{redirect('Welcome/index');}

}

public function calendario(){
    $this->load->model('Inculpables_model');
    $json=  $this->Inculpables_model->Json_Events();
    $data ['json']=$json;

    $this->load->view("v_cambiodeFuncion_calendario.html",$data);


}
public function dias(){
    $this->load->model('Inculpables_model');
    $name = $this->input->post('name');
    $fecha = $this->input->post('fecha');
    $dias= $this->Inculpables_model->diasAcumulados($name, $fecha);

    echo $dias;
}



}


