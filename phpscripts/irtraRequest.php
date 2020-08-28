<?php
$date = date('Y/m/d');
$arr_date = explode('/',$day);
$day = $arr_date[2];
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
$months[10] = "October";
$months[11] = "November";
$months[12] = "December";
$month = $months[$arr_date[1]];
$year = $arr_date[0];

echo(
    "
    <div style='margin-left:50px; width:900px'>
        <table style='width:100%'>
            <tr>
                <td><img src='http://200.94.251.67/assets/Nearsol.png'></td>
                <td></td>
                <td>Guatemala, $day de $month $year</td>
            </tr>
        </table>
    </div>
    "
);
?>