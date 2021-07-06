<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$month = ($request->month);

$res = [];
$i = 0;
$sql = "SELECT 
          br.avaya,
          br.name,
          br.account,
          br.nearsol_id,
          br.minimum_wage,
          br.incentive,
          br.days_discounted,
          br.7th_deduction,
          br.discounted_hours,
          br.minimum_wage_deductions,
          br.incentive_deductions,
          br.minimum_wage_with_deductions,
          br.incentive_with_deductions,
          br.overtime_hours,
          br.overtime,
          br.holiday_hours,
          br.holiday,
          br.bonuses,
          br.treasure_hunt,
          br.adjustments,
          br.total_income,
          br.bus,
          br.parking_car,
          br.parking_motorcycle_bicycle,
          br.igss,
          br.isr,
          br.equipment,
          br.total_deductions,
          br.total_payment,
          br.bonus_13,
          br.bonus_13_bonif,
          br.bonus_14,
          br.bonus_14_bonif,
          br.vacation_reserves,
          br.vacation_reserves_bonif,
          br.severance_reserves,
          br.employer_igss,
          br.health_insurance,
          br.parking,
          br.bus_client,
          br.total_reserves_and_fees,
          br.total_cost
        FROM billing b
        inner join billing_details bd on (b.idbillings = bd.id_billing)
        where month(b.`date`) = $month;";
        
if($request = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($request)){
    $res[$i]['avaya'] = $row['avaya'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['account'] = $row['account'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['minimum_wage'] = $row['minimum_wage'];
    $res[$i]['incentive'] = $row['incentive'];
    $res[$i]['days_discounted'] = $row['days_discounted'];
    $res[$i]['deduction_7th'] = $row['7th_deduction'];
    $res[$i]['discounted_hours'] = $row['discounted_hours'];
    $res[$i]['minimum_wage_deductions'] = $row['minimum_wage_deductions'];
    $res[$i]['incentive_deductions'] = $row['incentive_deductions'];
    $res[$i]['minimum_wage_with_deductions'] = $row['minimum_wage_with_deductions'];
    $res[$i]['incentive_with_deductions'] = $row['incentive_with_deductions'];
    $res[$i]['overtime_hours'] = $row['overtime_hours'];
    $res[$i]['overtime'] = $row['overtime'];
    $res[$i]['holiday_hours'] = $row['holiday_hours'];
    $res[$i]['holiday'] = $row['holiday'];
    $res[$i]['bonuses'] = $row['bonuses'];
    $res[$i]['treasure_hunt'] = $row['treasure_hunt'];
    $res[$i]['adjustments'] = $row['adjustments'];
    $res[$i]['total_income'] = $row['total_income'];
    $res[$i]['bus'] = $row['bus'];
    $res[$i]['parking_car'] = $row['parking_car'];
    $res[$i]['parking_motorcycle_bicycle'] = $row['parking_motorcycle_bicycle'];
    $res[$i]['igss'] = $row['igss'];
    $res[$i]['isr'] = $row['isr'];
    $res[$i]['equipment'] = $row['equipment'];
    $res[$i]['total_deductions'] = $row['total_deductions'];
    $res[$i]['total_payment'] = $row['total_payment'];
    $res[$i]['bonus_13'] = $row['bonus_13'];
    $res[$i]['bonus_13_bonif'] = $row['bonus_13_bonif'];
    $res[$i]['bonus_14'] = $row['bonus_14'];
    $res[$i]['bonus_14_bonif'] = $row['bonus_14_bonif'];
    $res[$i]['vacation_reserves'] = $row['vacation_reserves'];
    $res[$i]['vacation_reserves_bonif'] = $row['vacation_reserves_bonif'];
    $res[$i]['severance_reserves'] = $row['severance_reserves'];
    $res[$i]['employer_igss'] = $row['employer_igss'];
    $res[$i]['health_insurance'] = $row['health_insurance'];
    $res[$i]['parking'] = $row['parking'];
    $res[$i]['bus_client'] = $row['bus_client'];
    $res[$i]['total_reserves_and_fees'] = $row['total_reserves_and_fees'];
    $res[$i]['total_cost'] = $row['total_cost'];
    $i++;
  }
  echo(json_encode($res));
}
?>