<?php
$this->db->distinct();
$this->db->select('*');
$this->db->from('Personas');
$this->db->where('idPersonas', $idPersonas);
$queri = $this->db->get();
foreach ($queri->result_array() as $datarow);{

$nombre =$datarow['nombreapellido'];}




$this->db->distinct();
$this->db->select('*');
$this->db->from('usuario');
$this->db->where('idusuario', $idusuario);
$querie = $this->db->get();
foreach ($querie->result_array() as $datasrow);{

    $user =$datasrow['usuario'];}

$hoy = getdate();

echo'<html>

<title>Reporte</title>
<head>
<style>
.footer {
   position: fixed;
   left: 0;
   bottom: 20px;
   width: 100%;
   color: black;
   text-align: left;
}
</style>
</head>


<body>



<table align="center" width="575" border="1" >

<tr align="center">
<th width="90" rowspan="2"> <img  src="http://www.uncuyo.edu.ar/relaciones_institucionales/cache/logo-lavalle_999_750.jpg" width="80" height="60"></th>
<th>Salud Laboral <br>
Departamento de Recursos Humanos</th>
<th rowspan="2">'; echo $hoy['year'].'</th>
</tr>

<tr>
<th> <br> Certificado médico</th>
</tr>

</table>


<p align="right">';


echo'<h2> Se recibio el Parte medico de '.$nombre. ' con fecha el '.$fechaprescripcion.', y con vigencia hasta el '. $fechavencimiento.' </h2>';
echo'<h2> Parte recibido el '.$fecharecibido.' por '.$reposo.' Dias de Reposo </h2>';
echo'<h3> Datos del Empleado: ';
echo '<br> <br> DNI:' . $datarow['dni'];
echo '<br> Fecha de Nacimiento' .$datarow['fechadenac'];
echo '<br> Modalidad:' .$datarow['modalidad'];
echo '<br> Funcion:' .$datarow['Funcion'];
echo '<br>';

echo '<br> Firma y Aclaracion: _________________________________________';


echo '<div class="footer" >';
echo '<br> Atendido por: ' .$user;
echo '<br>';
echo '<br>';

echo '<br> Autorizado por: _______________________';
echo '</div>';
echo'<script>
    window.print();
</script>';

echo '</body></html>';
?>