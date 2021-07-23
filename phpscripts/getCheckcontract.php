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

            if($platform == 'ON SITE' || $platform == 'WAH'){

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

            $res['name'] = $name;
            $res['birthday'] = $birthday;
            $res['year'] = $year;
            $res['today'] = $today;
            $res['now'] = $now;
            $res['age'] = $age;
            $res['platform'] = $platform;
            $res['gender'] = $gender;
            $res['address'] = $address;
            $res['municipio'] = $municipio;
            $res['dpi_n'] = $dpi_n;
            $res['hiring_date'] = $hiring_date;
            $res['job'] = $job;
            $res['base_n_n'] = $base_n_n;
            $res['incentivo_n_n'] = $incentivo_n_n;
            $res['total_n_n'] = $$total_n_n;
            $res['base_n'] = $base_n;
            $res['incentivo_exp'] = $incentivo_exp;
            $res['incentivo_n'] = $incentivo_n;
            $res['total_n'] = $total_n;
            $res['dpi_1'] = $dpi_1;
            $res['dpi_2'] = $dpi_2;
            $res['dpi_3'] = $dpi_3;
            $res['dpi_4'] = $dpi_4;
            $res['t'] = $t;
            $res['base_n_init'] = $base_n_init;
            $res['base_n_int_l'] = $base_n_int_l;
            $res['base_n_cent_l'] = $base_n_cent_l;
            $res['incentivo_n_init'] = $incentivo_n_init;
            $res['incentivo_n_int_l'] = $incentivo_n_int_l;
            $res['incentivo_n_cent_l'] = $incentivo_n_cent_l;
            $res['total_n_init'] = $total_n_init;
            $res['total_n_int_l'] = $total_n_int_l;
            $res['total_n_cent_l'] = $total_n_cent_l;
            $res['dt'] = $dt;
            $res['date_letters'] = $date_letters;
            $res['today_date'] = $today_date;
            $res['dt_end'] = $dt_end;
            $res['day'] = $day;
            $res['mn'] = $mn;
            $res['yr'] = $yr;
        }
    }
    echo(json_encode($res));
}
?>

