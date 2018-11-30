<?php
/**
 * Created by PhpStorm.
 * User: axelfernandez
 * Date: 28/3/18
 * Time: 09:40
 */

class a_reporte extends CI_Controller
{
function index(){
$this->load->library('pdf.php');
//Consultas a la BD

   $primarykey = $this->session->flashdata('a_pk');
    $this->db->distinct();
    $this->db->select('*');
    $this->db->from('Accidentes');
    $this->db->where('idAccidentes', $primarykey);
    $queri = $this->db->get();
    foreach ($queri->result_array() as $dataaccidente);{

        $estado =$dataaccidente['estado'];}


    $hoy = getdate();
    $this->db->distinct();
    $this->db->select('*');
    $this->db->from('personas');
    $this->db->where('idPersonas', $dataaccidente['idPersonas']);
    $query = $this->db->get();
    foreach ($query->result_array() as $datapersona);{

        $nombre =$datapersona['nombreapellido'];}

    $this->db->distinct();
    $this->db->select('*');
    $this->db->from('Establecimientos');
    $this->db->where('idestablecimiento', $dataaccidente['idestablecimiento']);
    $query = $this->db->get();
    foreach ($query->result_array() as $dataestablecimiento);{

        $nombreestablecimiento =$dataestablecimiento['nombre'];}

    $this->db->distinct();
    $this->db->select('*');
    $this->db->from('Ciiu');
    $this->db->where('idCiiu', $dataaccidente['idCiiu']);
    $query = $this->db->get();
    foreach ($query->result_array() as $dataciiu);{

        $ciiu =$dataciiu['codigo'];}

    $añoingreso = substr($datapersona['fechadeinicio'],'0','4');
    $antiguedad= $hoy['year']-$añoingreso;


    $this->db->distinct();
    $this->db->select('*');
    $this->db->from('Enfermedadvar');
    $this->db->where('idEnfermedadvar', $dataaccidente['idEnfermedadvar']);
    $query = $this->db->get();
    foreach ($query->result_array() as $dataenfermedadvar);{
        if (isset($dataenfermedadvar['codigo'])){
            $enfermedadvar =$dataenfermedadvar['codigo'];
        }else{
            $dataenfermedadvar['codigo']='';
             $enfermedadvar= $dataenfermedadvar['codigo'];
        }}


    $this->db->distinct();
    $this->db->select('*');
    $this->db->from('Prestadores');
    $this->db->where('idPrestadores', $dataaccidente['idPrestadores']);
    $queryprestador = $this->db->get();


    $this->db->distinct();
    $this->db->select('*');
    $this->db->from('usuario');
    $this->db->where('idusuario', $dataaccidente['idusuario_insert']);
    $queryuser = $this->db->get();

    $queAsoc=$this->db->query("Select * from Accidentes_AgenteAsociado
 inner join AgenteAsociado on 
 Accidentes_AgenteAsociado.idAgenteAsociado = AgenteAsociado.idAgenteAsociado 
 where idAccidentes=".$dataaccidente['idAccidentes'].";");

    $queryagentecausante=$this->db->query("Select * from Accidentes_AgenteCausante
 inner join Agente_Causante on 
 Accidentes_AgenteCausante.idAgente_Causante = Agente_Causante.idAgente_Causante 
 where idAccidentes=".$dataaccidente['idAccidentes'].";");

    $queryNlesion=$this->db->query("Select * from Accidentes_NLesion
 inner join NLesion on 
 Accidentes_NLesion.idNLesion = NLesion.idNLesion 
 where idAccidentes=".$dataaccidente['idAccidentes'].";");

    $queryzonaafectada=$this->db->query("Select * from Accidentes_ZonaCuerpoAfectada
 inner join ZonaCuerpoAfectada on 
 Accidentes_ZonaCuerpoAfectada.idZonaCuerpoAfectada = ZonaCuerpoAfectada.idZonaCuerpoAfectada 
 where idAccidentes=".$dataaccidente['idAccidentes'].";");


    $queryformaaccidente=$this->db->query("Select * from Accidentes_FormaAccidente
 inner join FormaAccidente on 
 Accidentes_FormaAccidente.idFormaAccidente = FormaAccidente.idFormaAccidente 
 where idAccidentes=".$dataaccidente['idAccidentes'].";");



    $this->pdf = new Pdf();
    $this->pdf->AddPage();
    $this->pdf->SetFont('Arial','I', 10);
//Margen decorativo iniciando en 0, 0
    $this->pdf->Image('assets/uploads/Denuncia_accidente_trabajo-001.jpeg', 0,0, 210, 295, 'PNG');
//nro de accidente
    $this->pdf->SetXY(15, 32);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['nroaccidente']), 0, 'C');
//nombre de la empresa
    $this->pdf->SetXY(32, 41);
    $this->pdf->MultiCell(65, 5, utf8_decode('Municipalidad de Lavalle'), 0, 'C');
//cuit de la empresa
    $this->pdf->SetXY(82, 41);
    $this->pdf->MultiCell(65, 5, utf8_decode('30-67639188-2'), 0, 'C');
//domicilio de la empresa
    $this->pdf->SetXY(3, 45);
    $this->pdf->MultiCell(65, 5, utf8_decode('Beltrán 37'), 0, 'C');
//Localidad
    $this->pdf->SetXY(95, 45);
    $this->pdf->MultiCell(65, 5, utf8_decode('Lavalle'), 0, 'C');
//provincia
    $this->pdf->SetXY(3, 50);
    $this->pdf->MultiCell(65, 5, utf8_decode('Mendoza'), 0, 'C');
//CP
    $this->pdf->SetXY(105, 50);
    $this->pdf->MultiCell(65, 5, utf8_decode('5535'), 0, 'C');
//telefono
    $this->pdf->SetXY(140, 50);
    $this->pdf->MultiCell(65, 5, utf8_decode('2612076733'), 0, 'C');
//nombre del establecimiento donde ocurrio el accidente
    $this->pdf->SetXY(126, 54);
    $this->pdf->MultiCell(65, 5, utf8_decode($nombreestablecimiento), 0, 'C');
//codigo del establcimeinto
    $this->pdf->SetXY(10, 59);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataestablecimiento['idestablecimiento']), 0, 'C');
//CIIU
    $this->pdf->SetXY(51, 59);
    $this->pdf->MultiCell(65, 5, utf8_decode($ciiu), 0, 'C');
//Empresa Subcontratada
    if($dataestablecimiento['subcontratada']=="Si"){
        $this->pdf->SetXY(99, 59);
        $this->pdf->MultiCell(65, 5, utf8_decode('X'), 0, 'C');

    }
    else{
        $this->pdf->SetXY(110, 59);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }
//CUIT Establecimiento
    $this->pdf->SetXY(164, 59);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataestablecimiento['cuit']), 0, 'C');

//Domicilio de Establecimiento
    $this->pdf->SetXY(10, 63);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataestablecimiento['direccion']), 0, 'C');
//Localidad del Establecimiento
    $this->pdf->SetXY(65, 63);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataestablecimiento['localidad']), 0, 'C');
//Provincia del establecimiento
    $this->pdf->SetXY(133, 63);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataestablecimiento['provincia']), 0, 'C');
//CP del establecimiento
    $this->pdf->SetXY(155, 63);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataestablecimiento['CP']), 0, 'C');


//DATOS DEL TRABAJADOR

//Nombre y Apellido
    $this->pdf->SetXY(27, 72);
    $this->pdf->MultiCell(65, 5, utf8_decode($datapersona['nombreapellido']), 0, 'C');
//DNI
    $this->pdf->SetXY(138, 72);
    $this->pdf->MultiCell(65, 5, utf8_decode($datapersona['dni']), 0, 'C');
//Telefono
    $this->pdf->SetXY(1, 77);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['telefono']), 0, 'C');

//Nacionalidad
    $this->pdf->SetXY(5, 81);
    $this->pdf->MultiCell(65, 5, utf8_decode('Argetina'), 0, 'C');


//Fecha de Nacimiento
$this->pdf->SetXY(114, 81);
$this->pdf->MultiCell(65, 5, utf8_decode($datapersona['fechadenac']), 0, 'C');
//Sexo
if($dataaccidente['sexo']=='Masculino') {

    $this->pdf->SetXY(149, 81);
    $this->pdf->MultiCell(65, 5, utf8_decode('X'), 0, 'C');
}
else{
    $this->pdf->SetXY(155, 81);
    $this->pdf->MultiCell(65, 5, utf8_decode('X'), 0, 'C');

}
//Domicilio
    $this->pdf->SetXY(3, 86);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['direccion']), 0, 'C');

//Localidad
    $this->pdf->SetXY(82, 86);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['localidad']), 0, 'C');
//provincia
    $this->pdf->SetXY(125, 86);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['provincia']), 0, 'C');

//fecha de ingreso
    $this->pdf->SetXY(23, 95);
    $this->pdf->MultiCell(65, 5, utf8_decode($datapersona['fechadeinicio']), 0, 'C');

//Turno habitual (Fijo o Rotativo)
    if ($dataaccidente['turnohabitual']=='Fijo'){
        $this->pdf->SetXY(87, 95);
        $this->pdf->MultiCell(65, 5, utf8_decode('X'), 0, 'C');

    }
    else{
        $this->pdf->SetXY(107, 95);
        $this->pdf->MultiCell(65, 5, utf8_decode('X'), 0, 'C');

    }
//Jornada Habitual
    $this->pdf->SetXY(35, 100);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['horarioentrada'].'-'.$dataaccidente['horariosalida']), 0, 'C');
//Obra Social
    $this->pdf->SetXY(3, 104);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['obrasocial']), 0, 'C');

//puesto en el que se detecto la enfermedad Profesional (Puesto Actual)
    $this->pdf->SetXY(110, 109);
    $this->pdf->MultiCell(65, 5, utf8_decode($datapersona['Funcion']), 0, 'C');
//Antiguedad
    $this->pdf->SetXY(157, 109);
    $this->pdf->MultiCell(65, 5, utf8_decode($antiguedad." Años"), 0, 'C');
//Puesto de trabajo anterior
    $this->pdf->SetXY(33, 113);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['puestoanterior']), 0, 'C');
//Antiguedad en el Puesto Anterior
    $this->pdf->SetXY(157, 113);
    $this->pdf->MultiCell(65, 5, utf8_decode($antiguedad." Años"), 0, 'C');

//Detalles del Siniestro
//
//
//Eleccion (accidente de trabajo - Enfermedad Profesional)
    if($dataaccidente['tipoaccidente']=='Accidente de Trabajo'){

        $this->pdf->SetXY(18, 122);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');

    }
    else{

        $this->pdf->SetXY(77, 122);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }
//Muerte Si o no
    if($dataaccidente['muerte']=='Si') {
        $this->pdf->SetXY(111, 122);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }
    else{
        $this->pdf->SetXY(124, 122);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }

//fecha del accidente o siniestro
    $this->pdf->SetXY(0, 136);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['fechaaccidente']), 0, 'C');
//hora del accidente
    $this->pdf->SetXY(60, 136);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['horarioaccidente']), 0, 'C');
//fecha de inasistencia laboral
    $this->pdf->SetXY(40, 141);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['fechainicioinasistencia']), 0, 'C');

//Tarea habitual al momento del accidente
    if($dataaccidente['tareahabitualaccidente']=='Si'){
        $this->pdf->SetXY(126, 141);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }
    if($dataaccidente['tareahabitualaccidente']=='No'){
        $this->pdf->SetXY(139, 141);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }
//Descripcion del Accidente
    $this->pdf->SetXY(3, 145);
    $this->pdf->MultiCell(200, 5, utf8_decode(strip_tags($dataaccidente['descripcionaccidente'])), 0, 'C');

//Descripcion de los agentes materiales

    //Si es Accidente de trabajo
if ($dataaccidente['tipoaccidente']=='Accidente de Trabajo'){


    //Accidente en el puesto, in itinere o intercurrencia
    if($dataaccidente['accidentevar']=='En el trabajo'){
        $this->pdf->SetXY(0, 131);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');

    }
    if($dataaccidente['accidentevar']=='En otro centro o lugar de trabajo') {
        $this->pdf->SetXY(49, 131);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }

    if($dataaccidente['accidentevar']=='Al ir o volver del trabajo') {
        $this->pdf->SetXY(87, 131);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }

    if($dataaccidente['accidentevar']=='Desplazamiento en dia laboral') {
        $this->pdf->SetXY(132, 131);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }
    if($dataaccidente['accidentevar']=='Otro') {
        $this->pdf->SetXY(151, 131);
        $this->pdf->MultiCell(65, 5, utf8_decode("X"), 0, 'C');
    }

    //Agente asociado
    foreach ($queAsoc->result_array() as $Asoc){
        $this->pdf->SetXY(18, 166);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($Asoc['codigo'],'0','1')), 0, 'C');

        $this->pdf->SetXY(22, 166);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($Asoc['codigo'],'1','1')), 0, 'C');

        $this->pdf->SetXY(26, 166);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($Asoc['codigo'],'2','1')), 0, 'C');


        $this->pdf->SetXY(30, 166);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($Asoc['codigo'],'3','1')), 0, 'C');

        $this->pdf->SetXY(34, 166);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($Asoc['codigo'],'4','1')), 0, 'C');

    }
//Forma del accidente
    foreach ($queryformaaccidente->result_array() as $FormaAccidente){

        $this->pdf->SetXY(18, 175);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($FormaAccidente['codigo'],'0','1')), 0, 'C');

        $this->pdf->SetXY(22, 175);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($FormaAccidente['codigo'],'1','1')), 0, 'C');

        $this->pdf->SetXY(26, 175);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($FormaAccidente['codigo'],'2','1')), 0, 'C');

    }

    //Naturaleza de la Lesion
$xlesion=86;
    $xlesion2=90;
    foreach ($queryNlesion->result_array() as $NLesion){
        $this->pdf->SetXY($xlesion, 170);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($NLesion['codigo'],'0','1')), 0, 'C');

        $this->pdf->SetXY($xlesion2, 170);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($NLesion['codigo'],'1','1')), 0, 'C');

        $xlesion= $xlesion+19;
        $xlesion2= $xlesion+3;


    }
//Zona Afectada
    $zafectada=86;
    $zafectada2=90;
    $zafectada3=94;
    foreach ($queryzonaafectada->result_array() as $zonaafectada){

        $this->pdf->SetXY($zafectada, 175);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($zonaafectada['codigo'],'0','1')), 0, 'C');

        $this->pdf->SetXY($zafectada2, 175);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($zonaafectada['codigo'],'1','1')), 0, 'C');

        $this->pdf->SetXY($zafectada3, 175);
        $this->pdf->MultiCell(65, 5, utf8_decode(substr($zonaafectada['codigo'],'2','1')), 0, 'C');


        $zafectada= $zafectada+19;
        $zafectada2= $zafectada+3;
        $zafectada3= $zafectada2+3;

    }
}
else{

//Agente Causante
$xagentecausante=207;

foreach ($queryagentecausante->result_array() as $queryagentecausante){
        $this->pdf->SetXY(0, $xagentecausante);
        $this->pdf->MultiCell(65, 5, utf8_decode($queryagentecausante['codigo']), 0, 'C');
        $xagentecausante= $xagentecausante +14;
    }
//Material Asociado
$xMaterialAsociado=198;
    foreach ($queAsoc->result_array() as $Asoc){
        $this->pdf->SetXY(60, $xMaterialAsociado);
        $this->pdf->MultiCell(65, 5, utf8_decode($Asoc['codigo']), 0, 'C');
$xMaterialAsociado= $xMaterialAsociado+ 5;
    }



//Zona Afectada
    $zafectada=198;

    foreach ($queryzonaafectada->result_array() as $zonaafectada){
        $this->pdf->SetXY(76, $zafectada);
        $this->pdf->MultiCell(65, 5, utf8_decode($zonaafectada['codigo']), 0, 'C');
        $zafectada= $zafectada+4;
    }
//Tiempo de Exposicion
    $this->pdf->SetXY(99, 198);
    $this->pdf->MultiCell(65, 5, utf8_decode($antiguedad.' Años'), 0, 'C');

//Fecha de inicio de Inasistencia
    $this->pdf->SetXY(160, 198);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['fechainicioinasistencia']), 0, 'C');

//Detencion de la enfermedad
    if($enfermedadvar=='P'){ $this->pdf->SetXY(22,243);}
    if($enfermedadvar=='R'){ $this->pdf->SetXY(22,247);}
    if($enfermedadvar=='E'){ $this->pdf->SetXY(22,251);}
    if($enfermedadvar=='A'){ $this->pdf->SetXY(69,243);}
    if($enfermedadvar=='T'){ $this->pdf->SetXY(69,247);}
    if($enfermedadvar=='O'){ $this->pdf->SetXY(69,251);}
    if($enfermedadvar=='N'){ $this->pdf->SetXY(119,243);}
    if($enfermedadvar=='H'){ $this->pdf->SetXY(119,247);}
    if($enfermedadvar=='M'){ $this->pdf->SetXY(119,251);}
    if($enfermedadvar=='J'){ $this->pdf->SetXY(167,243);}
    if($enfermedadvar=='S'){ $this->pdf->SetXY(167,247);}
    if($enfermedadvar=='B'){ $this->pdf->SetXY(167,251);}
    $this->pdf->MultiCell(65, 5, utf8_decode('X'), 0, 'C');


}

//Prestador Asignado
    foreach ($queryprestador->result_array() as $dataprestador);{

        $nombre_prestador =$dataprestador['nombre'];}

    if($dataaccidente['accidentevar']=='In-Itinere'){$esitinere="Si";}else{$esitinere='No';}
    if(isset($dataaccidente['denunciapolicial'])){$denuncia=$dataaccidente['denunciapolicial'];}else{$denuncia=" ";}
//nombre del prestador
    $this->pdf->SetXY(18, 256);
    $this->pdf->MultiCell(65, 5, utf8_decode($nombre_prestador), 0, 'C');
//domicilio del prestador
    $this->pdf->SetXY(100, 256);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataprestador['domicilio']), 0, 'C');
//localidad
    $this->pdf->SetXY(8, 261);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataprestador['localidad']), 0, 'C');
//Provincia, siempre sera mendoza, por el momento
    $this->pdf->SetXY(74, 261);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataprestador['provincia']), 0, 'C');
//CP
    $this->pdf->SetXY(112, 261);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataprestador['CP']), 0, 'C');
//Telefono
    $this->pdf->SetXY(151, 261);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataprestador['telefono']), 0, 'C');
//Es in itinere??
    $this->pdf->SetXY(10, 266);
    $this->pdf->MultiCell(65, 5, utf8_decode($esitinere), 0, 'C');
//Denuncia Policial
    $this->pdf->SetXY(74, 266);
    $this->pdf->MultiCell(65, 5, utf8_decode($denuncia), 0, 'C');


//Firmas digitales

    $this->pdf->SetXY(18, 271);
    $this->pdf->MultiCell(65, 5, utf8_decode($dataaccidente['fechaformulario']), 0, 'C');


    foreach ($queryuser->result_array() as $datauser);{

        $nombreuser =$datauser['NombreApellido'];}
    $this->pdf->SetXY(120, 271);
    $this->pdf->MultiCell(65, 5, utf8_decode($nombreuser. " ".$datauser['dni']), 0, 'C');


    $this->pdf->Output();
}
}