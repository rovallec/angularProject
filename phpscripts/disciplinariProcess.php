<?php
$avaya_id = '7137937';
$date = '1 de septiembre de 2020';
$employee = 'Raul Alejandro Ovalle Castillo';
$position = 'Arquitecto de Sistemas';
$reportsTo = 'Ulises Orozco';
$account = 'IT';
$retroalimentacion = 'false';
$verbal = 'false';
echo("
    <div style='margin-left:50px; width:900px'>
        <table style='width:100%; margin-top:50px'>
            <tr>
                <td>Forma de Disciplina del Empleado</td> 
            </tr>
            <tr>
                <td style='background:black; font-color:white; font-weight:bold'>Información del Empleado</td>
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
                <td style='background:black; font-color:white; font-weight:bold'>Grado de Indisciplina</td>
            </tr>
            <tr>
                <td>
                    <table style='width:100%'>
                        <tr>
                            <td>
                                <input type='radio' checked=$retroalimentacion>
                                <label>Retroalimentacion</label>
                            </td>
                            <td>
                                <input type='radio' checked=$verbal>
                                <label>Advertencia Verbal</label>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
            <td style='background:black; font-color:white; font-weight:bold'>Motivo de la Sancion</td>
            </tr>

            <tr>
            <td style='background:black; font-color:white; font-weight:bold'>Detalles</td>
            </tr>
        </table>
    </div>
"
);
?>