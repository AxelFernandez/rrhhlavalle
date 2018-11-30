<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 5/2/18
 * Time: 09:18
 */

class Accidente_Home extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
        $this->load->library('image_CRUD');
    }

    public function index()
    {
        //$this->load->view('Home.html');
        redirect('Accidente_Home/listar');
    }

    public function listar()
    {
        $crud = new grocery_CRUD();

        /* Seleccionamos el tema */
        $crud->set_theme('flexigrid');

        /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
        $crud->set_table('Accidentes');
        $crud->order_by('fechavencimiento');
        $where = 'altamedica =0';
        $crud->where($where);
        $where2 = 'tipocontrato ="Planta Permanente"';
        $crud->where($where2);

        $crud->set_relation_n_n('AgenteCausante', 'Accidentes_AgenteCausante', 'Agente_Causante', 'idAccidentes', 'idAgente_Causante', '{codigo} ({descripcion})', 'idAccidentes_AgenteCausante');
        $crud->set_relation_n_n('ZonaAfectada', 'Accidentes_ZonaCuerpoAfectada', 'ZonaCuerpoAfectada', 'idAccidentes', 'idZonaCuerpoAfectada', '{codigo} ({descripcion})', 'idAccidentes_ZonaCuerpoAfectada');
        $crud->set_relation_n_n('AgenteAsociado', 'Accidentes_AgenteAsociado', 'AgenteAsociado', 'idAccidentes', 'idAgenteAsociado', '{codigo} ({descripcion})', 'idAccidentes_AgenteAsociado');
        $crud->set_relation_n_n('NLesion', 'Accidentes_NLesion', 'NLesion', 'idAccidentes', 'idNLesion', '{codigo} ({descripcion})', 'idAccidentes_NLesion');
        $crud->set_relation_n_n('FormaAccidente', 'Accidentes_FormaAccidente', 'FormaAccidente', 'idAccidentes', 'idFormaAccidente', '{codigo} ({descripcion})', 'idAccidentes_FormaAccidente');
        $crud->field_type('descripcionaccidente', 'text');
        /* Le asignamos un nombre */
        $crud->set_subject('Accidentes Planta Permanente');

        /* Asignamos el idioma español */
        $crud->set_language('spanish');
        $crud->display_as('idPersonas', 'Nombre y Apellido');
        $crud->columns('nroaccidente', 'idPersonas', 'telefono', 'direccion', 'fechaaccidente', 'fechavencimiento', 'estado');
        $crud->field_type('idAccidentes', 'invisible');
        $crud->field_type('estado', 'invisible');
        $crud->field_type('fechaaltamedica', 'invisible');
        $crud->field_type('altamedica', 'invisible');
        $crud->field_type('idusuario_insert', 'invisible');
        $crud->field_type('idusuario_update', 'invisible');
        $crud->field_type('idusuario_altamedica', 'invisible');
        $crud->field_type('fechapostergado', 'invisible');
        $crud->field_type('fechaaceptado', 'invisible');
        $crud->field_type('idusuario_aceptado', 'invisible');
        $crud->field_type('fechaformulario', 'invisible');
        $crud->field_type('nroaccidente', 'invisible');
        $crud->add_action('Agregar Observaciones', 'https://png.icons8.com/office/16/000000/visible.png', 'Accidente_Home/observaciones');

        $crud->set_read_fields('idPersonas', 'telefono', 'direccion', 'descripcionaccidente', 'fechavencimiento', 'AgenteCausante', 'ZonaAfectada', 'AgenteAsociado', 'NLesion', 'FormaAccidente', 'estado', 'altamedica');
        $crud->set_relation('idPersonas', 'Personas', '{dni} ({nombreapellido}) ');
        $crud->set_relation('idestablecimiento', 'Establecimientos', 'nombre');
        $crud->set_relation('idCiiu', 'Ciiu', '{descripcion} ({codigo}) ');
        $crud->set_relation('idPrestadores', 'Prestadores', 'nombre');
        $crud->set_relation('idEnfermedadvar', 'Enfermedadvar', 'descripcion');

        $crud->callback_before_insert((array($this, 'guardarEstado')));
        $crud->callback_after_insert((array($this, 'insertarobservacion_denuncia')));
        $crud->add_action('Cargar Archivos', 'https://png.icons8.com/office/16/000000/upload.png', 'Accidente_Home/imagenes');
        $crud->add_action('Aceptar o Rechazar Tratamiento', "https://png.icons8.com/ios/16/000000/menu-2-filled.png", 'Accidente_Home/decisionTratamiento', 'ui-icon-plus');
        $crud->add_action('Imprimir', 'https://sasktenders.ca/App_Themes/SaskTenders/Images/pdf.png', 'Accidente_Home/descargar');

        //$crud->edit_fields('descripcionaccidente','fechavencimiento', 'estado', 'idusuario_update', 'fechapostergado');
        $crud->callback_before_update((array($this, 'guardarEstado_edit')));

        $output = $crud->render();

        /* La cargamos en la vista situada en
        /applications/views/productos/administracion.php */
        $this->load->view('v_accidentes_home.html', $output);


    }

    public function guardarEstado($post_array)
    {
        $hoy = getdate();
         $hoys = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];
        $post_array['estado'] = 'Pendiente';
        $post_array['altamedica'] = FALSE;
        $post_array['idusuario_insert'] = $this->session->userdata('id');
        $this->load->model('Accidentes_model');
        $post_array['nroaccidente']= $this->Accidentes_model->obtenernroaccidente($post_array['tipocontrato']);
        $post_array['fechaformulario']= $hoys;
        return $post_array;

    }
public function insertarobservacion_denuncia($post_array,$primary_key){

    $hoy = getdate();
    $hoys = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];
    $id=$primary_key;
    $this->db->query('Insert into Observaciones values (null,"Se cargo la denuncia el dia '.$hoys. ' puede ver la denuncia aqui","'.$hoys.'","'.$id.'",'.$post_array['idusuario_insert'].',"'.$this->session->userdata('nombre').'"," " );');

}
    public function imagenes($primary_key)
    {

        $image_crud = new image_CRUD();

        $image_crud->set_table('imagenes_accidentes');

        $image_crud->set_primary_key_field('idimagenes_accidentes');
        $image_crud->set_url_field('imagen');
        $image_crud->set_title_field('titulo');

        $image_crud->set_relation_field('idAccidentes')
            ->set_image_path('assets/uploads/accidentes');

        $output = $image_crud->render();

        $this->load->view('v_accidentes_cargaimagenes.html', $output);
    }

    public function guardarEstado_edit($post_array)
    {
       // $hoy = getdate();
       // $hoys = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];
       // $post_array['estado'] = 'Postergado';
        //$post_array['idusuario_update'] = $this->session->userdata('id');
       // $post_array['fechapostergado'] = $hoys;


        return $post_array;

    }

    public function decisionTratamiento($primary_key)
    {
        $this->session->set_tempdata('primary', $primary_key, 300); // Expire in 5 minutes
        redirect('Tratamiento/index');


    }

    public function listar_todos()
    {
        $crud = new grocery_CRUD();

        /* Seleccionamos el tema */
        $crud->set_theme('flexigrid');

        /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
        $crud->set_table('Accidentes');

      $crud->set_subject('Accidentes Finalizados Planta Permanente');
        $where2 = 'tipocontrato ="Planta Permanente"';
        $crud->where($where2);
        $crud->unset_add();
        $where= 'altamedica = 1';
        $crud->where($where);
        /* Asignamos el idioma español */
        $crud->set_language('spanish');
        $crud->display_as('idPersonas', 'Nombre y Apellido');
        $crud->columns('nroaccidente', 'telefono', 'direccion', 'fechaaccidente', 'fechavencimiento', 'estado');
        $crud->add_action('Imprimir', 'https://sasktenders.ca/App_Themes/SaskTenders/Images/pdf.png', 'Accidente_Home/descargar');
        $crud->add_action('Agregar Observaciones', 'https://png.icons8.com/office/16/000000/visible.png', 'Accidente_Home/observaciones');

        $crud->set_read_fields('idPersonas', 'telefono', 'direccion', 'descripcionaccidente', 'fechavencimiento', 'AgenteCausante', 'ZonaAfectada', 'AgenteAsociado', 'NLesion', 'FormaAccidente', 'estado', 'altamedica');
        $crud->set_relation('idPersonas', 'Personas', '{dni} ({nombreapellido}) ');
        /* Generamos la tabla */
        $crud->add_action('Cargar Archivos', 'https://png.icons8.com/office/16/000000/upload.png', 'Accidente_Home/imagenes');
        // $crud->add_action('Aceptar o Rechazar Tratamiento', "https://png.icons8.com/ios/16/000000/menu-2-filled.png", 'Accidente_Home/decisionTratamiento','ui-icon-plus');
        $crud->unset_edit();


        $output = $crud->render();

        /* La cargamos en la vista situada en
        /applications/views/productos/administracion.php */
        $this->load->view('v_accidentes_home.html', $output);


    }

    public function descargar($primary_key)
    {


        $data['primarykey'] = $primary_key;
        $this->session->set_flashdata('a_pk', $primary_key);

       redirect("a_reporte/index");
        // $this->load->view('a_reporte2', $data);

    }

    public function listar_locacion()
    {
        $crud = new grocery_CRUD();

        $crud->set_theme('flexigrid');

        $crud->set_table('Accidentes');
        $crud->order_by('fechavencimiento');
        $where = 'altamedica =0';
        $crud->where($where);
        $where2 = 'tipocontrato ="Contrato de Locacion"';
        $crud->where($where2);

        $crud->set_relation_n_n('AgenteCausante', 'Accidentes_AgenteCausante', 'Agente_Causante', 'idAccidentes', 'idAgente_Causante', '{codigo} ({descripcion})', 'idAccidentes_AgenteCausante');
        $crud->set_relation_n_n('ZonaAfectada', 'Accidentes_ZonaCuerpoAfectada', 'ZonaCuerpoAfectada', 'idAccidentes', 'idZonaCuerpoAfectada', '{codigo} ({descripcion})', 'idAccidentes_ZonaCuerpoAfectada');
        $crud->set_relation_n_n('AgenteAsociado', 'Accidentes_AgenteAsociado', 'AgenteAsociado', 'idAccidentes', 'idAgenteAsociado', '{codigo} ({descripcion})', 'idAccidentes_AgenteAsociado');
        $crud->set_relation_n_n('NLesion', 'Accidentes_NLesion', 'NLesion', 'idAccidentes', 'idNLesion', '{codigo} ({descripcion})', 'idAccidentes_NLesion');
        $crud->set_relation_n_n('FormaAccidente', 'Accidentes_FormaAccidente', 'FormaAccidente', 'idAccidentes', 'idFormaAccidente', '{codigo} ({descripcion})', 'idAccidentes_FormaAccidente');
        $crud->field_type('descripcionaccidente', 'text');
        $crud->set_subject('Accidentes Contrato Locacion');
        $crud->set_language('spanish');
        $crud->display_as('idPersonas', 'Nombre y Apellido');
        $crud->columns('nroaccidente', 'idPersonas', 'telefono', 'direccion', 'fechaaccidente', 'fechavencimiento', 'estado');
        $crud->field_type('estado', 'invisible');
        $crud->field_type('fechaaltamedica', 'invisible');
        $crud->field_type('altamedica', 'invisible');
        $crud->field_type('idusuario_insert', 'invisible');
        $crud->field_type('idusuario_update', 'invisible');
        $crud->field_type('idusuario_altamedica', 'invisible');
        $crud->field_type('fechapostergado', 'invisible');
        $crud->field_type('fechaaceptado', 'invisible');
        $crud->field_type('idusuario_aceptado', 'invisible');
        $crud->field_type('fechaformulario', 'invisible');
        $crud->field_type('nroaccidente', 'invisible');
        $crud->unset_edit();
        $crud->add_action('Agregar Observaciones', 'https://png.icons8.com/office/16/000000/visible.png', 'Accidente_Home/observaciones');
        $crud->set_relation('idCiiu', 'Ciiu', '{descripcion} ({codigo}) ');
        $crud->set_relation('idestablecimiento', 'Establecimientos', 'nombre');
        $crud->set_relation('idEnfermedadvar', 'Enfermedadvar', 'descripcion');
        $crud->set_read_fields('idPersonas', 'telefono', 'direccion', 'descripcionaccidente', 'fechavencimiento', 'AgenteCausante', 'ZonaAfectada', 'AgenteAsociado', 'NLesion', 'FormaAccidente', 'estado', 'altamedica');
        $crud->set_relation('idPersonas', 'Personas', '{dni} ({nombreapellido}) ');
        $crud->callback_after_insert((array($this, 'insertarobservacion_denuncia')));
        $crud->callback_before_insert((array($this, 'guardarEstado')));
        $crud->add_action('Cargar Archivos', 'https://png.icons8.com/office/16/000000/upload.png', 'Accidente_Home/imagenes');
        $crud->add_action('Aceptar o Rechazar Tratamiento', "https://png.icons8.com/ios/16/000000/menu-2-filled.png", 'Accidente_Home/decisionTratamiento', 'ui-icon-plus');
        $crud->add_action('Imprimir', 'https://sasktenders.ca/App_Themes/SaskTenders/Images/pdf.png', 'Accidente_Home/descargar');

        //$crud->edit_fields('descripcionaccidente','fechavencimiento', 'estado', 'idusuario_update', 'fechapostergado');
        $crud->callback_before_update((array($this, 'guardarEstado_edit')));

        $output = $crud->render();

        /* La cargamos en la vista situada en
        /applications/views/productos/administracion.php */
        $this->load->view('v_accidentes_home.html', $output);


    }

    public function listar_todos_locacion()
    {
        $crud = new grocery_CRUD();

        /* Seleccionamos el tema */
        $crud->set_theme('flexigrid');

        /* Seleccionmos el nombre de la tabla de nuestra base de datos*/
        $crud->set_table('Accidentes');

        $crud->set_subject('Accidentes Finalizados Planta Permanente');
        $where2 = 'tipocontrato ="Contrato de Locacion"';
        $crud->where($where2);
        $crud->unset_add();
        $where= 'altamedica = 1';
        $crud->where($where);

        $crud->set_language('spanish');
        $crud->display_as('idPersonas', 'Nombre y Apellido');
        $crud->columns('nroaccidente', 'telefono', 'direccion', 'fechaaccidente', 'fechavencimiento', 'estado');
        $crud->add_action('Imprimir', 'https://sasktenders.ca/App_Themes/SaskTenders/Images/pdf.png', 'Accidente_Home/descargar');
        $crud->add_action('Agregar Observaciones', 'https://png.icons8.com/office/16/000000/visible.png', 'Accidente_Home/observaciones');

        $crud->set_read_fields('idPersonas', 'telefono', 'direccion', 'descripcionaccidente', 'fechavencimiento', 'AgenteCausante', 'ZonaAfectada', 'AgenteAsociado', 'NLesion', 'FormaAccidente', 'estado', 'altamedica');
        $crud->set_relation('idPersonas', 'Personas', '{dni} ({nombreapellido}) ');
        $crud->add_action('Cargar Archivos', 'https://png.icons8.com/office/16/000000/upload.png', 'Accidente_Home/imagenes');

        $crud->unset_edit();


        $output = $crud->render();


        $this->load->view('v_accidentes_home.html', $output);

    }

    public function observaciones($primary_key)
    {
        $crud = new grocery_CRUD();

        $crud->set_theme('flexigrid');
        $crud->set_table('Observaciones');
        $crud->set_subject('Historia Clinica');
        $where = 'idAccidentes= '.$primary_key;
        $crud->where($where);

        $crud->set_language('spanish');
        $crud->columns('descripcion' ,'fecha','user', 'imagen');
        $hoy = getdate();
        $hoys = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];
        $crud->unset_edit();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_delete();
        $crud->set_field_upload('imagen','assets/uploads/accidentes');
        $crud->field_type('idusuario','hidden',$_SESSION['id']);
        $crud->field_type('idAccidentes','hidden',$primary_key);
        $crud->field_type('fecha','hidden',$hoys);
        $crud->field_type('user','hidden',$_SESSION['nombre']);
        $output = $crud->render();
        $this->load->view('v_accidentes_home.html', $output);


    }






}