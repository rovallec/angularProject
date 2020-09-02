<?php
$avaya_id = $_GET['avaya'];
$date = $_GET['date'];
$employee = $_GET['employee'];
$position = $_GET['pos'];
$reportsTo = $_GET['sup'];
$account = $_GET['acc'];
$grade = $_GET['grade'];
$retroalimentacion = '';
$verbal = '';
$escrita = '';
$final = '';
$dosDias = '';
$cuatroDias = '';
$term = '';
switch ($grade) {
    case 'Retroalimentacion':
        $retroalimentacion = 'checked'
    break;
    case 'Advertencia Verbal';
        $verbal = 'checked';
    break;
    case 'Advertencia Escrita':
        $escrita = 'checked';
    break;
    case 'Advertencia Final':
        $final = 'checked';
    break;
    case 'Suspencion 2 Dias':
        $dosDias = 'checked';
    break;
    case 'Suspencion 4 Dias':
        $cuatroDias = 'checked';
    break;
    case 'Terminacion Laboral':
        $term = 'checked';
    break;
}

$late = $_GET['Entrada/Salida Tarde/Temprano'];
$attendence = $_GET['Ausencia Injustificada'];
$abuse = $_GET['Abuso de Receso/Almuerzo/Auxiliares'];
$rules = $_GET['Violacion de Reglas de la Compañia'];
$performance = $_GET['Desempeño'];
$conduct = $_GET['Conducta'];
$fraud = $_GET['Fraude'];
$other = $_GET['Otro'];
$description = $_GET['description'];
$foundament = $_GET['mot'];
$article = $_GET['legal'];
$consequences = $_GET['consequences'];
$observatinos = $_GET['observations'];

echo("
    <div style='margin-left:50px; width:900px'>
        <table style='width:100%; margin-top:50px'>
            <tr>
                <td style='font-weight:bold; text-align:center;'>Forma de Disciplina del Empleado</td> 
            </tr>
            <tr>
                <td style='background:black; color:white; font-weight:bold; text-align:center;'>Información del Empleado</td>
            </tr>
            <tr>
                <td>
                    <table style='width:100%'>
                        <tr>
                            <td>Avaya:</td>
                            <td>$avaya_id</td>
                            <td>Fecha:</td>
                            <td>$date</td>
                        </tr>
                        <tr>
                            <td>Empleado:</td>
                            <td>$employee</td>
                            <td>Posicion:</td>
                            <td>$position</td>
                        </tr>
                        <tr>
                            <td>Supervisor:</td>
                            <td>$reportsTo</td>
                            <td>Departamento:</td>
                            <td>$account</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style='background:black; color:white; font-weight:bold; text-align:center'>Grado de Indisciplina</td>
            </tr>
            <tr>
                <td>
                    <table style='width:100%'>
                        <tr>
                            <td>
                                <input type='radio' $retroalimentacion>
                                <label>Retroalimentacion</label>
                            </td>
                            <td>
                                <input type='radio' $verbal>
                                <label>Advertencia Verbal</label>
                            </td>
                            <td>
                                <input type='radio' $escrita>
                                <label>Advertencia Escrita</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type='radio' $final>
                                <label>Advertencia Final</label>
                            </td>
                            <td>
                                <input type='radio' $dosDias>
                                <label>Suspencion 2 Dias</label>
                            </td>
                            <td>
                                <input type='radio' $cuatroDias>
                                <label>Suspencion 4 Dias</label>
                            </td>
                        </tr>
                        <td>
                            <input type='radio' $term>
                            <label>Terminacion Laboral</label>
                        </td>
                    </table>
                </td>
            </tr>

            <tr>
            <td style='background:black; color:white; font-weight:bold; text-align:center'>Motivo de la Sancion</td>
            </tr>
            <tr>
                <td>
                    <table style='width:100%'>
                        <tr>
                            <td>
                                <input type='radio' $late>
                                <label>Entrada Tarde/Salida Temprano</label>
                            </td>
                            <td>
                                <input type='radio' $attendence>
                                <label>Ausencia Injustificada</label>
                            </td>
                            <td>
                                <input type='radio' $abuse>
                                <label>Abuso de Receso/Almuerzo/Auxiliares</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type='radio' $rules>
                                <label>Violacion de Reglas de la Compañia</label>
                            </td>
                            <td>
                                <input type='radio' $performance>
                                <label>Desempeño</label>
                            </td>
                            <td>
                                <input type='radio' $conduct>
                                <label>Conducta</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type='radio' $fraud>
                                <label>Fraude</label>
                            </td>
                            <td>
                                <input type='radio' $other>
                                <label>Otro</label>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
            <td style='background:black; color:white; font-weight:bold; text-align:center'>Detalles</td>
            </tr>
            <tr>
                <td>
                    <table style='width:100%'>
                        <tr>
                            <td style='width:140px;font-weight:bold;'>Descripcion de la Infraccion</td>
                            <td>$description</td>
                        </tr>
                        <tr>
                            <td style='width:140px;font-weight:bold;'>Fundamento Legal</td>
                            <td>
                                <table style='width'100%'>
                                    <tr>
                                        <td style='font-weight:bold;'>$article</td>
                                    </tr>
                                    <tr>
                                        <td>Se prohibe a los trabajadores:</td>
                                    </tr>
                                    <tr>
                                        <td>$foundament</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style='width:140px;font-weight:bold;'>Consecuencias Futuras</td>
                            <td>$consequences</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style='background:black; color:white; font-weight:bold; text-align:center'>Reconocimiento de la Advertencia</td>
            </tr>
            <tr>
                <td>Firmando esta forma, se confirma el entendimiento completo de la advertencia.  Además se confirma que el empleado y su supervisor han discutido la sanción y el plan para mejorar. </td>
            </tr>
            <tr style='height:60px'></tr>
            <tr>
                <td>
                    <table style='width:100%'>
                        <tr>
                            <td style='width:46px'></td>
                            <td style='width:200px;border-bottom:solid 2px black'></td>
                            <td style='width:200px'></td>
                            <td style='width:200px;border-bottom:solid 2px black'></td>
                            <td style='width:46px'></td>
                        </tr>
                        <tr>
                            <td style='width:46px'></td>
                            <td style='width:200px;text-align:center'>Firma del Empleado</td>
                            <td style='width:200px'></td>
                            <td style='width:200px;text-align:center'>Firma del Supervisor</td>
                            <td style='width:46px'></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style='height:25px'></tr>
            <tr>
                <td style='font-weight:bold'>Observaciones:</td>
            </tr>
            <tr>
                <td style='border:dotted black 1px'><label>$observatinos</label><td>
            </tr>
        </table>
    </div>
"
);
?>