<?php
$date = date('Y/m/d');
$exp = explode("/", $date);
$day = $exp[2];
$months = [];
$months[1] = "January";
$months[2] = "February";
$months[3] = "March";
$months[4] = "April";
$months[5] = "May";
$months[6] = "June";
$months[7] = "July";
$months[8] = "August";
$months[9] = "September";
$months[10] = "Octuber";
$months[11] = "November";
$months[12] = "December";
$month = $months[(int)$exp[1]];
$year = $exp[0];
$name = $_GET['name'];
$dpi = $_GET['dpi'];
$status = $_GET['status'];

echo(
    "
    <div style='margin-left:50px; width:900px'>
        <table style='width:100%'>
            <tr>
                <td><img src='http://172.18.2.45/assets/Nearsol.png' style='width:225;height:75'></td>
                <td></td>
                <td style='text-align:right'>Guatemala, $day de $month $year</td>
            </tr>

            <tr style='height:25px'><tr>

            <tr>
                <td colspan='3'>Señores</td>
            </tr>

            <tr>
                <td colspan='3' style='font-weight:bold'>IRTRA</td>
            </tr>

            <tr>
                <td colspan='3' style='font-weight:bold'>Ciudad de Guatemala</td>
            </tr>

            <tr style='height:25px'><tr>

            <tr>
                <td colspan='3'>Estimados Señores:</td>
            </tr>

            <tr style='height:25px'><tr>

            <tr>
                <td colspan='3'><div style='margin-left:25px'>Por este medio me dirijo a ustedes solicitando el trámite de los siguientes trabajadores:</div></td>
            </tr>

            <tr style='height:50px'></tr>

            <tr>
                <td colspan='3'>
                    <table style='text-align:center; border:solid 1.5px black;border-collapse: collapse;width:95%;margin-left:2.5%'>
                        <tr>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>No.</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>NOMBRE</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>IDENTIFICACION</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>FOTO</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>ESTATUS</td>
                        <tr>
                        <tr>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>1</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>$name</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>$dpi</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>Digital</td>
                            <td style='text-align:center; border:solid 1.5px black;border-collapse: collapse;'>$status</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    "
);
?>