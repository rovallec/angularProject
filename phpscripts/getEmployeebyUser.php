<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $result = [];

    $sql = "SELECT
              e.idemployees,
              h.id_profile,
              e.id_hire,
              e.id_account,
              a.name AS account,
              u.user_name AS reporter,
              e.client_id,
              e.hiring_date,
              e.job,
              e.base_payment,
              e.productivity_payment,
              e.state,
              p.gender,
              h.nearsol_id,
              p2.name,
              e.platform,
              e.active,
              e.society,
              p.dpi,
              f.children
            FROM employees e
            INNER JOIN hires h ON (h.idhires = e.id_hire)
            INNER JOIN accounts a ON (a.idaccounts = e.id_account)
            INNER JOIN profiles p ON (p.idprofiles = h.id_profile)
            INNER JOIN users u ON (u.idUser = e.reporter)
            INNER JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) AS name, p1.idprofiles FROM profiles p1) p2 ON (p2.idprofiles = h.id_profile)
            LEFT JOIN (SELECT COUNT(f1.idfamilies) AS children, f1.id_profile FROM families f1 WHERE f1.relationship IN('Hijo', 'Hija')) f ON (p.idprofiles = f.id_profile)
            WHERE p.idprofiles = $id;";

    if ($res = mysqli_query($con, $sql)) {
        while($r = mysqli_fetch_assoc($res)){
            $result['id_profile'] = $r['id_profile'];
            $result['idemployees']= $r['idemployees'];
            $result['id_hire'] = $r['id_hire'];
            $result['id_account'] = $r['id_account'];
            $result['account'] = $r['account'];
            $result['reporter'] = $r['reporter'];
            $result['client_id'] = $r['client_id'];
            $result['hiring_date'] = $r['hiring_date'];
            $result['job'] = $r['job'];
            $result['base_payment'] = $r['base_payment'];
            $result['productivity_payment'] = $r['productivity_payment'];
            $result['state'] = $r['state'];
            $result['gender'] = $r['gender'];
            $result['nearsol_id'] = $r['nearsol_id'];
            $result['name'] = $r['name'];
            $result['platform'] = $r['platform'];
            $result['active'] = $r['active'];
            $result['society'] = $r['society'];
            $result['dpi'] = $r['dpi'];
            $result['children'] = $r['children'];
        }
        echo(json_encode($result));
    }
?>
