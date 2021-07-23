<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $res = [];

    $mnth = [
        'enero',
        'febrero',
        'marzo',
        'abril',
        'mayo',
        'junio',
        'julio',
        'agosto',
        'septiembre',
        'octubre',
        'noviembre',
        'diciembre'
    ];

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = ($request->id);
    $name = '';
    $birthday = '';
    $year = '';
    $today = '';
    $now = '';
    $age = '';
    $platform = '';
    $gender = '';
    $address = '';
    $municipio = '';
    $dpi_n = '';
    $hiring_date = '';
    $job = '';
    $base_n_n = '';
    $incentivo_n_n = '';
    $total_n_n = '';
    $base_n = '';
    $incentivo_exp = '';
    $incentivo_n = '';
    $total_n = '';
    $dpi_1 = '';
    $dpi_2 = '';
    $dpi_3 = '';
    $dpi_4 = '';
    $t = '';
    $base_n_init = '';
    $base_n_int_l = '';
    $base_n_cent_l = '';
    $incentivo_n_init = '';
    $incentivo_n_int_l = '';
    $incentivo_n_cent_l = '';
    $total_n_init = '';
    $total_n_int_l = '';
    $total_n_cent_l = '';
    $dt = '';
    $date_letters = '';
    $today_date = '';
    $dt_end = '';
    $day = '';
    $mn = '';
    $yr = '';

    $sql = "SELECT `hires`.*, `employees`.*, `waves`.`ops_start`, `waves`.`base_payment` AS `base`, `waves`.`productivity_payment` AS `prod`, `profiles`.*, `education_details`.*, `contact_details`.* FROM `hires` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires` LEFT JOIN `waves` ON `waves`.`idwaves` = `hires`.`id_wave` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN `education_details` ON `education_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `contact_details` ON `contact_details`.`id_profile` = `profiles`.`idprofiles` WHERE `idemployees` = '$id';";

    if($result = mysqli_query($con, $sql)){
        while($row = mysqli_fetch_assoc($result)){
            $name = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
            $birthday = $row['day_of_birth'];
            $year = explode("-",$birthday);
            $today = date('Y-m-d');
            $now = explode("-", $today);
            $age = $now[0] - $year[0];
            $platform = $row['platform'];
            $gender = '';
            $gender = $row['gender'];
            $gender = $gender . ", " .$row['marital_status'];

            $gender = $gender . ", " . $row['profesion'] . ", " . $row['nationality'];

            $address = $row['address'];
            $municipio = $row['nationality'];

            $dpi_n = $row['dpi'];
            $hiring_date = explode("-", $row['ops_start'])[2] . "/" . explode("-", $row['ops_start'])[1] . "/" . explode("-", $row['ops_start'])[0];
            $job = $row['job'];
            $base_n_n = $row['base_payment'];
            $incentivo_n_n = $row['productivity_payment'];
            $total_n_n = $base_n_n + $incentivo_n_n;
            $base_n = number_format(((float)$base_n_n),2);
            $incentivo_n = (float)$incentivo_n_n - 250;
            $incentivo_exp = explode(".", $incentivo_n);
            $incentivo_n = number_format(((float)$incentivo_n),2);
            $total_n = number_format(((float)$total_n_n),2);

            if(count(explode(".",$total_n)) < 2){
                $total_n = $total_n . ".00";
            }
            
            $dpi_1 = str_split($dpi_n, 1);
            $dpi_2 = $dpi_1[0] . $dpi_1[1] . $dpi_1[2] . $dpi_1[3];
            $dpi_3 = $dpi_1[4] . $dpi_1[5] . $dpi_1[6] . $dpi_1[7] . $dpi_1[8];
            $dpi_4 = $dpi_1[9] . $dpi_1[10] . $dpi_1[11] . $dpi_1[12];
            $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
            $t = $f->format($dpi_2);
            $t = $t . " espacio " . $f->format($dpi_3);
            if($dpi_4 == "0101"){
                $t = $t . " " . 'espacio cero ciento uno';
            }else{
                $t = $t . " " . $f->format($dpi_4);
            }

            $base_n_init = explode(".", $base_n_n);
            $base_n_int_l = $f->format($base_n_init[0]);
            $base_n_cent_l = $f->format($base_n_init[1]);

            $incentivo_n_init = explode(".", $incentivo_n);
            $incentivo_n_int_l = $f->format($incentivo_n_init[0]);
            $incentivo_n_cent_l = $f->format($incentivo_n_init[1]);
            
            $total_n_init = explode(".", $total_n);
            $total_n_int_l = $f->format($total_n_init[0]);
            $total_n_cent_l = $f->format($total_n_init[1]);

            $dt = explode("/", $hiring_date);
            $date_letters = $f->format($dt[0]) . " de " . $mnth[intval($dt[1])-1] . " de " . $f->format($dt[2]);

            $today_date = date("Y-m-d");
            $dt_end = explode("-", $today_date);
            $day = $dt_end[2];
            $mn = $mnth[intval($dt_end[1])-1];
            $yr = $dt_end[0];
        }
        $res['name'] = strtoupper(strval($name));
        $res['birthday'] = strtoupper(strval($birthday));
        $res['year'] = strtoupper(strval($year));
        $res['today'] = strtoupper(strval($today));
        $res['now'] = strtoupper(strval($now));
        $res['age'] = strtoupper(strval($age));
        $res['platform'] = strtoupper(strval($platform));
        $res['gender'] = strtoupper(strval($gender));
        $res['address'] = strtoupper(strval($address));
        $res['municipio'] = strtoupper(strval($municipio));
        $res['dpi_n'] = strtoupper(strval($dpi_n));
        $res['hiring_date'] = strtoupper(strval($hiring_date));
        $res['job'] = strtoupper(strval($job));
        $res['base_n_n'] = strtoupper(strval($base_n_n));
        $res['incentivo_n_n'] = strtoupper(strval($incentivo_n_n));
        $res['total_n_n'] = strtoupper(strval($total_n_n));
        $res['base_n'] = strtoupper(strval($base_n));
        $res['incentivo_exp'] = strtoupper(strval($incentivo_exp));
        $res['incentivo_n'] = strtoupper(strval($incentivo_n));
        $res['total_n'] = strtoupper(strval($total_n));
        $res['dpi_1'] = strtoupper(strval($dpi_1));
        $res['dpi_2'] = strtoupper(strval($dpi_2));
        $res['dpi_3'] = strtoupper(strval($dpi_3));
        $res['dpi_4'] = strtoupper(strval($dpi_4));
        $res['t'] = strtoupper(strval($t));
        $res['base_n_init'] = strtoupper(strval($base_n_init));
        $res['base_n_int_l'] = strtoupper(strval($base_n_int_l));
        $res['base_n_cent_l'] = strtoupper(strval($base_n_cent_l));
        $res['incentivo_n_init'] = strtoupper(strval($incentivo_n_init));
        $res['incentivo_n_int_l'] = strtoupper(strval($incentivo_n_int_l));
        $res['incentivo_n_cent_l'] = strtoupper(strval($incentivo_n_cent_l));
        $res['total_n_init'] = strtoupper(strval($total_n_init));
        $res['total_n_int_l'] = strtoupper(strval($total_n_int_l));
        $res['total_n_cent_l'] = strtoupper(strval($total_n_cent_l));
        $res['dt'] = strtoupper(strval($dt));
        $res['date_letters'] = strtoupper(strval($date_letters));
        $res['today_date'] = strtoupper(strval($today_date));
        $res['dt_end'] = strtoupper(strval($dt_end));
        $res['day'] = strtoupper(strval($day));
        $res['mn'] = strtoupper(strval($mn));
        $res['yr'] = strtoupper(strval($yr));
        echo(json_encode($res));
}
?>

