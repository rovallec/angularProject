<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reporteNomina.csv" . '"');
require 'database.php';

echo "\xEF\xBB\xBF";
$AID_Period = $_GET['AID_Period'];
$i = 0;

$sql = "SELECT
A1.idpayments,
A1.idemployees AS 'IdEmployees',
A1.client_id As 'Client_ID',
A1.NombreDelTrabajador,
A1.JORNADA 'Jornada',
A1.SECCION AS 'Seccion',
A1.bank,
A1.`Transferencia/Cheque`,
A1.dpi AS 'DPI',
A1.iggs AS 'No.DeIGSS',
A1.PERIODO AS 'Periodo',
SUM(DISTINCT A1.Salario) AS Salario,
SUM(DISTINCT A1.DiasTrabajados) AS DiasTrabajados,
SUM(DISTINCT A1.HorasOrdinarias) AS HorasOrdinarias,
SUM(DISTINCT A1.HorasExtraordinarias) AS HorasExtraordinarias,
SUM(A1.HorasAsuetos) AS HorasAsuetos,
SUM(DISTINCT A1.SalarioBase) AS SalarioBase,
SUM(DISTINCT A1.SalarioExtraordinario) AS SalarioExtraordinario,
SUM(DISTINCT A1.SalarioComisiones) AS SalarioComisiones,
SUM(DISTINCT A1.SalarioSeptimos) AS SalarioSeptimos,
SUM(DISTINCT A1.SalarioAsuetos) AS SalarioAsuetos,
SUM(DISTINCT A1.SalarioTotal) AS SalarioTotal,
SUM(DISTINCT A1.Ausencias) AS Ausencias,
SUM(DISTINCT A1.SalarioNeto) AS SalarioNeto,
SUM(DISTINCT A1.IGSS) AS IGSS,
SUM(DISTINCT A1.Otras) AS Otras,
SUM(DISTINCT A1.Descuentos) AS Descuentos,
SUM(DISTINCT A1.AnticipoSobreSueldo) AS AnticipoSobreSueldo,
SUM(DISTINCT A1.TotalDeducciones) AS TotalDeducciones,
SUM(DISTINCT A1.Aguinaldo) AS Aguinaldo,
SUM(DISTINCT A1.Bono14) AS Bono14,
SUM(DISTINCT A1.Vacaciones) AS Vacaciones,
SUM(DISTINCT A1.Idemnizacion) AS Idemnizacion,
SUM(DISTINCT A1.VentajasEconomicas) AS VentajasEconomicas,
SUM(DISTINCT A1.AjusteSalarial) AS AjusteSalarial,
SUM(DISTINCT A1.BonificacionIncentivo) AS BonificacionIncentivo,
SUM(DISTINCT A1.OtrasBonificacioneseincentivos) AS OtrasBonificacioneseIncentivos,
SUM(DISTINCT A1.LiquidoARecibir) AS LiquidoARecibir,
A1.Observaciones,
SUM(DISTINCT A1.BoletoDeOrnato)  AS BoletoDeOrnato,
SUM(DISTINCT A1.DescuentoSeguro) AS DescuentoSeguro,
SUM(DISTINCT A1.DescuentosJudiciales) AS DescuentosJudiciales,
SUM(DISTINCT A1.HeadSet) AS HeadSet,
SUM(DISTINCT A1.ISREmpleados) AS ISREmpleados,
SUM(DISTINCT A1.ParqueoEmpleados) AS ParqueoEmpleados,
SUM(DISTINCT A1.ParqueoMotos) AS ParqueoMotos,
SUM(DISTINCT A1.TarjetaDeParqueo) AS TarjetaDeParqueo,
SUM(DISTINCT A1.TransporteEnBus) AS TransporteEnBus,
SUM(DISTINCT A1.PrestamoPersonal) AS PrestamoPersonal,
SUM(DISTINCT A1.AjustesPeriodos) AS AjustesPeriodos,
SUM(DISTINCT A1.BonosDiversos) AS BonosDiversos,
SUM(DISTINCT A1.BonoPorAsistencia) AS BonoPorAsistencia,
SUM(DISTINCT A1.TreasureHunt) AS TreasureHunt,
SUM(DISTINCT A1.BonosPorReferidos) AS BonosPorReferidos,
SUM(DISTINCT A1.BonosPorReclutamiento) AS BonosPorReclutamiento
FROM (
/* CREDITOS */
SELECT DISTINCT
  g.idpayments,
  b.NEARSOL_ID as 'idemployees', 
  a.client_id,
  UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) as NombreDelTrabajador, 
  IF (e.idclients = 2, 'ADMINISTRATION', 'OPERATIONS') AS JORNADA,  
  d.name AS SECCION,
  f.bank,
  IF (f.type = 'BANK CHECK', 'CHEQUE', f.number) AS 'Transferencia/Cheque',
  c.dpi, 
  c.iggs,
  CONCAT(h.start, ' - ', h.end) AS PERIODO,
  g.base AS Salario,
  ROUND(g.base_hours / 8, 0) AS 'DiasTrabajados',
  g.base_hours AS 'HorasOrdinarias',
  g.ot_hours AS 'HorasExtraordinarias',
  g.holidays_hours AS 'HorasAsuetos',
  (g.base_complete / 2) AS 'SalarioBase',
  g.ot AS 'SalarioExtraordinario',
  0.00 AS 'SalarioComisiones',
  0.00 AS 'SalarioSeptimos',
  g.holidays AS 'SalarioAsuetos',
  ROUND(COALESCE(g.base_complete / 2, 0.00) + COALESCE(g.ot, 0.00) + 0.00 + COALESCE(g.holidays, 0.00), 2) AS 'SalarioTotal',
  -1 * (if(g.base_complete<>0.00, (g.base_complete / 2), 0.00) - g.base) AS 'Ausencias',
  g.base + g.ot + g.holidays +
  g.base - (g.base_complete / 2) AS 'SalarioNeto',
  /* DEDUCCIONES */
  0.00 AS 'IGSS',
  0.00 AS 'Otras',  
  0.00 AS 'Descuentos',
  0.00 AS 'AnticipoSobreSueldo',
  /* SE SUMAN TODAS LAS DEDUCCIONES */
  0.00 AS 'TotalDeducciones',
  0.00 AS 'Aguinaldo',
  0.00 AS 'Bono14',
  0.00 AS 'Vacaciones',
  0.00 AS 'Idemnizacion',
  0.00 AS 'VentajasEconomicas',
  0.00 AS 'AjusteSalarial',
  0.00 AS 'BonificacionIncentivo',
  ROUND(COALESCE(k.amount, 0.00), 2) AS 'OtrasBonificacioneseincentivos',
   /* SE SUMAN TODOS LOS CREDITOS Y DEBITOS */
  coalesce(g.base, 0.00) + COALESCE(g.ot, 0.00) + COALESCE(g.holidays, 0.00) + COALESCE(k.amount, 0.00) As 'LiquidoARecibir',
  'Planilla de Sueldo Ordinario' AS 'Observaciones',
   /*  Detalle de Descuentos */ 
  0.00 AS 'BoletoDeOrnato',
  0.00 AS 'DescuentoSeguro',
  0.00 AS 'DescuentosJudiciales',
  0.00 AS 'HeadSet',
  0.00 AS 'ISREmpleados',
  0.00 AS 'ParqueoEmpleados',
  0.00 AS 'ParqueoMotos',
  0.00 AS 'TarjetaDeParqueo',
  0.00 AS 'TransporteEnBus',
  0.00 AS 'PrestamoPersonal', 
   /* Detalle de Bonos / Ajustes */
  IF(j.type='Ajustes Periodos Anteriores',         ROUND(COALESCE(j.amount, 0.00), 2), 0.00) AS 'AjustesPeriodos',
  IF(j.type = 'Bonos Diversos' OR j.type 'Performance Bonus',         ROUND(COALESCE(j.amount, 0.00), 2), 0.00) AS 'BonosDiversos',
  IF(j.type='BONOS POR ASISTENCIA',     ROUND(COALESCE(j.amount, 0.00), 2), 0.00) AS 'BonoPorAsistencia',
  IF(j.type='TREASURE HUNT',            ROUND(COALESCE(j.amount, 0.00), 2), 0.00) AS 'TreasureHunt',
  IF(j.type like '%RAF%',                 ROUND(COALESCE(j.amount, 0.00), 2), 0.00) AS 'BonosPorReferidos', 
  IF(j.type='BONOS POR RECLUTAMIENTO',  ROUND(COALESCE(j.amount, 0.00), 2), 0.00) AS 'BonosPorReclutamiento'
FROM employees a
INNER JOIN hires b ON (a.id_hire = b.idhires) 
INNER JOIN profiles c ON (b.id_profile = c.idprofiles)
INNER JOIN accounts d ON (a.id_account = d.idaccounts)
INNER JOIN clients e ON (d.id_client = e.idclients)
INNER JOIN payment_methods f ON (f.id_employee = a.idemployees and f.predeterm=1)
INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods)
INNER JOIN periods h ON (g.id_period = h.idperiods)
INNER JOIN credits j on (g.idpayments = j.id_payment)
INNER JOIN (SELECT c2.id_payment, SUM(ROUND(c2.amount, 2)) AS amount FROM credits c2 where (c2.TYPE NOT IN('Bonificacion Decreto', 'Anticipo Sobre Sueldo', 'Salario Base') AND c2.TYPE NOT LIKE'%Horas%Extra%Laboradas%' AND c2.TYPE NOT LIKE'%Horas%De%Asueto%') GROUP BY c2.id_payment) k on (g.idpayments = k.id_payment)
AND h.idperiods = $AID_Period
UNION 
/* DEBITOS */
SELECT DISTINCT
g.idpayments,
b.NEARSOL_ID as 'idemployees', 
a.client_id,
UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) as 'NombreDelTrabajador', 
IF (e.idclients = 2, 'ADMINISTRATION', 'OPERATIONS') AS 'Jornada',  
d.name AS SECCION,
f.bank,
IF (f.type = 'BANK CHECK', 'CHEQUE', f.number) AS 'Transferencia/Cheque',
c.dpi, 
c.iggs,
CONCAT(h.start, ' - ', h.end) AS 'Periodo',
0.00 AS Salario,
0 AS 'DiasTrabajados',
0 AS 'HorasOrdinarias',
0 AS 'HorasExtraordinarias',
0 AS 'HorasAsuetos',
0.00 AS 'SalarioBase',
0.00 AS 'SalarioExtraordinario',
0.00 AS 'SalarioComisiones',
0.00 AS 'SalarioSeptimos',
0.00 AS 'SalarioAsuetos',
0.00 AS 'SalarioTotal',
0.00 AS 'Ausencias',
0.00 AS 'SalarioNeto', 
/* DEDUCCIONES */
(-1*(COALESCE(IF(i.type='Descuento IGSS',                        ROUND(i.amount, 2), 0.00), 0.00))) AS 'IGSS',
(-1*(COALESCE(IF(i.type in('Descuentos Varios', 'Discount'),     ROUND(i.amount, 2), 0.00), 0.00))) AS 'Otras',
(-1*(COALESCE(IF(i.type not in('Descuento IGSS', 'Descuentos Varios','Anticipo Sobre Sueldo', 'Discount'),ROUND(i.amount, 2), 0.00), 0.00))) AS 'Descuentos',
(-1*(COALESCE(IF(i.type='Anticipo Sobre Sueldo',                 ROUND(i.amount, 2), 0.00), 0.00))) AS 'AnticipoSobreSueldo', 
/* SE SUMAN TODAS LAS DEDUCCIONES */
(-1*(COALESCE(IF(i.TYPE='Descuento IGSS',                        ROUND(i.amount, 2), 0.00), 0.00) + /* IGSS */ 
COALESCE(IF(i.type not in('Descuento IGSS', 'Descuentos Varios', 'Anticipo Sobre Sueldo', 'Discount'),ROUND(i.amount, 2), 0.00), 0.00) + /* Otras */
COALESCE(IF(i.type in('Descuentos Varios', 'Discount'),          ROUND(i.amount, 2), 0.00), 0.00) + /* Descuentos */
COALESCE(IF(i.type='Anticipo Sobre Sueldo',                      ROUND(i.amount, 2), 0.00), 0.00))) AS 'TotalDeducciones', /* AnticipoSobreSueldo */ 
0.00 AS 'Aguinaldo',
0.00 AS 'Bono14',
0.00 AS 'Vacaciones',
0.00 AS 'Idemnizacion',
0.00 AS 'VentajasEconomicas',
IF(i.type='Ajuste Salarial',                                     ROUND(COALESCE(i.amount, 0.00), 2), 0.00) AS 'AjusteSalarial',
0.00 AS 'BonificacionIncentivo',
0.00 AS 'OtrasBonificacioneseincentivos',
/* SE SUMAN TODOS LOS DEBITOS */
ROUND( 
0.00 +  /* Salario Base (SalarioTotal) */ 
0.00 + /* Ausencias */ 
(-1* (
  /*COALESCE(IF(i.TYPE='Descuento IGSS',                       ROUND(i.amount, 2), 0.00), 0.00) + */  /* IGSS */   
  COALESCE(IF(i.type in('Descuento IGSS', 'ISR'),                i.amount + (RAND()*0.000000005), 0.00), 0.00) + 
  COALESCE(IF(i.type not in('Descuento IGSS', 'Descuentos Varios','Anticipo Sobre Sueldo', 'Discount', 'ISR'),ROUND(i.amount, 2), 0.00), 0.00)  +  /* Otras */
  COALESCE(IF(i.TYPE in('Descuentos Varios', 'Discount'), ROUND(i.amount, 2), 0.00), 0.00) +  /* Descuentos */
  COALESCE(IF(i.type='Anticipo Sobre Sueldo',                    ROUND(i.amount, 2), 0.00), 0.00))) +  /* TotalDeducciones En Negativo */
0.00 + 0.00 + 0.00 + 0.00 + 0.00 +                                                  /* Otros Descuentos */
COALESCE(IF(i.type='Ajuste Salarial',                            ROUND(i.amount, 2), 0.00), 0.00) +  /* Ajuste Salarial */ 
0.00 +  /* BonificacionIncentivo */ 
0.00, 2) AS 'LiquidoARecibir',  /* OtrasBonificacioneseincentivos */
'Planilla de Sueldo Ordinario' AS 'Observaciones',
 /*  Detalle de Descuentos */ 
COALESCE(IF(i.type='Boleto de Ornato',                           ROUND(i.amount, 2), 0.00), 0.00) AS 'BoletoDeOrnato',
COALESCE(IF(i.type like'%SEGURO%',                               ROUND(i.amount, 2), 0.00), 0.00) AS 'DescuentoSeguro',
COALESCE(IF(i.type IN('Descuento Judicial', 'Acuerdo Judicial'), ROUND(i.amount, 2), 0.00), 0.00) AS 'DescuentosJudiciales',
COALESCE(IF(i.type like'%HEADSET%',                              ROUND(i.amount, 2), 0.00), 0.00) AS 'HeadSet',
COALESCE(IF(i.type='ISR',                                        ROUND(i.amount, 2), 0.00), 0.00) AS 'ISREmpleados',
COALESCE(IF(i.type like'%Car%Parking%',                          ROUND(i.amount, 2), 0.00), 0.00) AS 'ParqueoEmpleados',
COALESCE(IF(i.type='MOTORCYCLE PARKING',                         ROUND(i.amount, 2), 0.00), 0.00) AS 'ParqueoMotos',
COALESCE(IF(i.type IN('Descuento Por Servicio de Active Parking', 'TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso'), ROUND(i.amount, 2), 0.00), 0.00) AS 'TarjetaDeParqueo',
COALESCE(IF(i.type='BUS TRANSPORTATION',                         ROUND(i.amount, 2), 0.00), 0.00) AS 'TransporteEnBus',
COALESCE(IF(i.type='Prestamo Personal',                              ROUND(i.amount, 2), 0.00), 0.00) AS 'PrestamoPersonal', 
 /* Detalle de Bonos / Ajustes */
0.00 AS 'AjustesPeriodos',
0.00 AS 'BonosDiversos',
0.00 AS 'BonoPorAsistencia',
0.00 AS 'TreasureHunt',
0.00 AS 'BonosPorReferidos', 
0.00 AS 'BonosPorReclutamiento' 
FROM employees a
INNER JOIN hires b ON (a.id_hire = b.idhires) 
INNER JOIN profiles c ON (b.id_profile = c.idprofiles)
INNER JOIN accounts d ON (a.id_account = d.idaccounts)
INNER JOIN clients e ON (d.id_client = e.idclients)
INNER JOIN payment_methods f ON (f.id_employee = a.idemployees and f.predeterm=1)
INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods)
INNER JOIN periods h ON (g.id_period = h.idperiods)
INNER JOIN debits i ON (g.idpayments = i.id_payment)
AND h.idperiods = $AID_Period
UNION 
/* DEBITOS SOLO DUPLICADOS */
SELECT DISTINCT
g.idpayments,
b.NEARSOL_ID as 'idemployees', 
a.client_id,
UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) as 'NombreDelTrabajador', 
IF (e.idclients = 2, 'ADMINISTRATION', 'OPERATIONS') AS 'Jornada',  
d.name AS SECCION,
f.bank,
IF (f.type = 'BANK CHECK', 'CHEQUE', f.number) AS 'Transferencia/Cheque',
c.dpi, 
c.iggs,
CONCAT(h.start, ' - ', h.end) AS 'Periodo',
0.00 AS Salario,
0 AS 'DiasTrabajados',
0 AS 'HorasOrdinarias',
0 AS 'HorasExtraordinarias',
0 AS 'HorasAsuetos',
0.00 AS 'SalarioBase',
0.00 AS 'SalarioExtraordinario',
0.00 AS 'SalarioComisiones',
0.00 AS 'SalarioSeptimos',
0.00 AS 'SalarioAsuetos',
0.00 AS 'SalarioTotal',
0.00 AS 'Ausencias',
0.00 AS 'SalarioNeto', 
/* DEDUCCIONES */
-1 * ROUND(COALESCE(j.amount, 0.00), 2) AS 'IGSS',
0.00 AS 'Otras',
0.00 AS 'Descuentos',
0.00 AS 'AnticipoSobreSueldo', 
/* SE SUMAN TODAS LAS DEDUCCIONES */
-1 * ROUND(COALESCE(j.amount, 0.00), 2) AS 'TotalDeducciones', /* AnticipoSobreSueldo */ 
0.00 AS 'Aguinaldo',
0.00 AS 'Bono14',
0.00 AS 'Vacaciones',
0.00 AS 'Idemnizacion',
0.00 AS 'VentajasEconomicas',
0.00 AS 'AjusteSalarial',
0.00 AS 'BonificacionIncentivo',
0.00 AS 'OtrasBonificacioneseincentivos',
/* SE SUMAN TODOS LOS DEBITOS */
(-1 * ROUND(COALESCE(i.amount, 0.00), 2)) AS 'LiquidoARecibir',  /* OtrasBonificacioneseincentivos */
'Planilla de Sueldo Ordinario' AS 'Observaciones',
 /*  Detalle de Descuentos */ 
0.00 AS 'BoletoDeOrnato',
0.00 AS 'DescuentoSeguro',
0.00 AS 'DescuentosJudiciales',
0.00 AS 'HeadSet',
0.00 AS 'ISREmpleados',
0.00 AS 'ParqueoEmpleados',
0.00 AS 'ParqueoMotos',
0.00 AS 'TarjetaDeParqueo',
0.00 AS 'TransporteEnBus',
0.00 AS 'PrestamoPersonal', 
 /* Detalle de Bonos / Ajustes */
0.00 AS 'AjustesPeriodos',
0.00 AS 'BonosDiversos',
0.00 AS 'BonoPorAsistencia',
0.00 AS 'TreasureHunt',
0.00 AS 'BonosPorReferidos', 
0.00 AS 'BonosPorReclutamiento' 
FROM employees a
INNER JOIN hires b ON (a.id_hire = b.idhires) 
INNER JOIN profiles c ON (b.id_profile = c.idprofiles)
INNER JOIN accounts d ON (a.id_account = d.idaccounts)
INNER JOIN clients e ON (d.id_client = e.idclients)
INNER JOIN payment_methods f ON (f.id_employee = a.idemployees and f.predeterm=1)
INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods)
INNER JOIN periods h ON (g.id_period = h.idperiods)
INNER JOIN debits i ON (g.idpayments = i.id_payment)
INNER JOIN (SELECT (z1.amount + RAND() * 0.0005) AS 'amount', z1.id_payment from debits z1 INNER JOIN debits z2 on (z1.id_payment = z2.id_payment) where z1.type = 'Descuento IGSS'
            AND z2.type = 'ISR' and z1.amount = z2.amount) j on (g.idpayments = j.id_payment)
AND h.idperiods = $AID_Period
UNION 
/* TODOS LOS EMPLEADOS QUE NO POSEEN CRÉDITOS NI DEBITOS. */
SELECT DISTINCT
  g.idpayments,
  b.NEARSOL_ID as 'idemployees', 
  a.client_id,
  UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) as NombreDelTrabajador, 
  IF (e.idclients = 2, 'ADMINISTRATION', 'OPERATIONS') AS JORNADA,  
  d.name AS SECCION,
  f.bank,
  IF (f.type = 'BANK CHECK', 'CHEQUE', f.number) AS 'Transferencia/Cheque',
  c.dpi, 
  c.iggs,
  CONCAT(h.start, ' - ', h.end) AS PERIODO,
  0 AS Salario,
  0 AS 'DiasTrabajados',
  0 AS 'HorasOrdinarias',
  0 AS 'HorasExtraordinarias',
  0 AS 'HorasAsuetos',
  0 AS 'SalarioBase',
  0 AS 'SalarioExtraordinario',
  0.00 AS 'SalarioComisiones',
  0 AS 'SalarioSeptimos',
  0 AS 'SalarioAsuetos',
  0 AS 'SalarioTotal',
  0 AS 'Ausencias',
  0 AS 'SalarioNeto',   
  /* DEDUCCIONES */
  0.00 AS 'IGSS',
  0.00 AS 'Otras',  
  0.00 AS 'Descuentos',
  0.00 AS 'AnticipoSobreSueldo',
  /* SE SUMAN TODAS LAS DEDUCCIONES */
  0.00 AS 'TotalDeducciones',  
  0.00 AS 'Aguinaldo',
  0.00 AS 'Bono14',
  0.00 AS 'Vacaciones',
  0.00 AS 'Idemnizacion',
  0.00 AS 'VentajasEconomicas',
  0.00 AS 'AjusteSalarial',
  0.00 AS 'BonificacionIncentivo',
  0.00 AS 'OtrasBonificacioneseincentivos',  
  /* SE SUMAN TODOS LOS CREDITOS Y DEBITOS */
  0.00 AS 'LiquidoARecibir', /* OtrasBonificacioneseincentivos */
  'Planilla de Sueldo Ordinario' AS 'Observaciones',
   /*  Detalle de Descuentos */ 
  0.00 AS 'BoletoDeOrnato',
  0.00 AS 'DescuentoSeguro',
  0.00 AS 'DescuentosJudiciales',
  0.00 AS 'HeadSet',
  0.00 AS 'ISREmpleados',
  0.00 AS 'ParqueoEmpleados',
  0.00 AS 'ParqueoMotos',
  0.00 AS 'TarjetaDeParqueo',
  0.00 AS 'TransporteEnBus',
  0.00 AS 'PrestamoPersonal', 
   /* Detalle de Bonos / Ajustes */
  0.00 AS 'AjustesPeriodos',
  0.00 AS 'BonosDiversos',
  0.00 AS 'BonoPorAsistencia',
  0.00 AS 'TreasureHunt',
  0.00 AS 'BonosPorReferidos', 
  0.00 AS 'BonosPorReclutamiento'
FROM employees a
INNER JOIN hires b ON (a.id_hire = b.idhires) 
INNER JOIN profiles c ON (b.id_profile = c.idprofiles)
INNER JOIN accounts d ON (a.id_account = d.idaccounts)
INNER JOIN clients e ON (d.id_client = e.idclients)
INNER JOIN payment_methods f ON (f.id_employee = a.idemployees and f.predeterm=1)
INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods)
INNER JOIN periods h ON (g.id_period = h.idperiods)
AND h.idperiods = $AID_Period
UNION
/* CREDITOS BONIFICACION DECRETO*/
SELECT DISTINCT
  g.idpayments,
  b.NEARSOL_ID as 'idemployees', 
  a.client_id,
  UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) as NombreDelTrabajador, 
  IF (e.idclients = 2, 'ADMINISTRATION', 'OPERATIONS') AS JORNADA,  
  d.name AS SECCION,
  f.bank,
  IF (f.type = 'BANK CHECK', 'CHEQUE', f.number) AS 'Transferencia/Cheque',
  c.dpi, 
  c.iggs,
  CONCAT(h.start, ' - ', h.end) AS PERIODO,
  0.00 AS Salario,
  0.00 AS 'DiasTrabajados',
  0.00 AS 'HorasOrdinarias',
  0.00 AS 'HorasExtraordinarias',
  0.00 AS 'HorasAsuetos',
  0.00 AS 'SalarioBase',
  0.00 AS 'SalarioExtraordinario',
  0.00 AS 'SalarioComisiones',
  0.00 AS 'SalarioSeptimos',
  0.00 AS 'SalarioAsuetos',
  0.00 AS 'SalarioTotal',
  0.00 AS 'Ausencias',
  0.00 AS 'SalarioNeto',   
  /* DEDUCCIONES */
  0.00 AS 'IGSS',
  0.00 AS 'Otras',  
  0.00 AS 'Descuentos',
  0.00 AS 'AnticipoSobreSueldo',
  /* SE SUMAN TODAS LAS DEDUCCIONES */
  0.00 AS 'TotalDeducciones',
  0.00 AS 'Aguinaldo',
  0.00 AS 'Bono14',
  0.00 AS 'Vacaciones',
  0.00 AS 'Idemnizacion',
  0.00 AS 'VentajasEconomicas',
  0.00 AS 'AjusteSalarial',
  COALESCE(i.amount, 0.00) AS 'BonificacionIncentivo',
  0.00 AS 'OtrasBonificacioneseincentivos',
  /* SE SUMAN TODOS LOS CREDITOS Y DEBITOS */
  COALESCE(i.amount, 0.00) AS 'LiquidoARecibir', /* OtrasBonificacioneseincentivos */
  'Planilla de Sueldo Ordinario' AS 'Observaciones',
   /*  Detalle de Descuentos */ 
  0.00 AS 'BoletoDeOrnato',
  0.00 AS 'DescuentoSeguro',
  0.00 AS 'DescuentosJudiciales',
  0.00 AS 'HeadSet',
  0.00 AS 'ISREmpleados',
  0.00 AS 'ParqueoEmpleados',
  0.00 AS 'ParqueoMotos',
  0.00 AS 'TarjetaDeParqueo',
  0.00 AS 'TransporteEnBus',
  0.00 AS 'PrestamoPersonal', 
   /* Detalle de Bonos / Ajustes */
  0.00 AS 'AjustesPeriodos',
  0.00 AS 'BonosDiversos',
  0.00 AS 'BonoPorAsistencia',
  0.00 AS 'TreasureHunt',
  0.00 AS 'BonosPorReferidos', 
  0.00 AS 'BonosPorReclutamiento'
FROM employees a
INNER JOIN hires b ON (a.id_hire = b.idhires) 
INNER JOIN profiles c ON (b.id_profile = c.idprofiles)
INNER JOIN accounts d ON (a.id_account = d.idaccounts)
INNER JOIN clients e ON (d.id_client = e.idclients)
INNER JOIN payment_methods f ON (f.id_employee = a.idemployees and f.predeterm=1)
INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods)
INNER JOIN periods h ON (g.id_period = h.idperiods)
INNER JOIN credits i on (g.idpayments = i.id_payment)
AND h.idperiods = $AID_Period  
and i.type='Bonificacion Decreto'
) A1 
GROUP BY A1.idpayments,A1.idemployees,A1.client_id,A1.NombreDelTrabajador,A1.JORNADA,A1.SECCION,A1.bank,
A1.`Transferencia/Cheque`,
A1.dpi,A1.iggs,A1.PERIODO,
A1.Observaciones 
LIMIT 0, 10000;";



$title = ['Empleado', 'Cliente', 'Nombre Del Trabajador', 'Jornada', 'Seccion', 'banco', 'Transferencia / Cheque', 'DPI', 'No. De IGSS', 'Periodo', 'Salario', 'Dias Trabajados', 'Horas Ordinarias', 'Horas Extraordinarias', 'Horas Asuetos', 'Salario Base', 'Salario Extraordinario', 'Salario Comisiones', 'Salario Septimos', 'Salario Asuetos', 'Salario Total', 'Ausencias', 'Salario Neto', 'IGSS', 'Otras', 'Descuentos', 'Anticipo Sobre Sueldo', 'Total Deducciones', 'Aguinaldo', 'Bono 14', 'Vacaciones', 'Idemnizacion', 'Ventajas Economicas', 'Ajuste Salarial', 'Bonificacion Incentivo', 'Otras Bonificaciones e Incentivos', 'Liquido A Recibir', 'Observaciones', 'Boleto De Ornato', 'Descuento De Seguro', 'Descuentos Judiciales', 'HeadSet', 'ISR Empleados', 'Parqueo Empleados', 'ParqueoMotos', 'Tarjeta De Parqueo', 'Transporte En Bus', 'Prestamo Personal', 'Ajustes Periodos', 'Bonos Diversos', 'Bono Por Asistencia', 'Treasure Hunt', 'Bonos Por Referidos', 'Bonos Por Reclutamiento'];
$output = fopen("php://output", "w");
fputcsv($output, $title);
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $row['IdEmployees'];
        $exportRow[1] = $row['Client_ID'];
        $exportRow[2] = $row['NombreDelTrabajador'];
        $exportRow[3] = $row['Jornada'];
        $exportRow[4] = $row['Seccion'];
        $exportRow[5] = $row['bank'];
        $exportRow[6] = $row['Transferencia/Cheque'];
        $exportRow[7] = $row['DPI'];
        $exportRow[8] = $row['No.DeIGSS'];
        $exportRow[9] = $row['Periodo'];
        $exportRow[10] = $row['Salario'];
        $exportRow[11] = $row['DiasTrabajados'];
        $exportRow[12] = $row['HorasOrdinarias'];
        $exportRow[13] = $row['HorasExtraordinarias'];
        $exportRow[14] = $row['HorasAsuetos'];
        $exportRow[15] = $row['SalarioBase'];
        $exportRow[16] = $row['SalarioExtraordinario'];
        $exportRow[17] = $row['SalarioComisiones'];
        $exportRow[18] = $row['SalarioSeptimos'];
        $exportRow[19] = $row['SalarioAsuetos'];
        $exportRow[20] = $row['SalarioTotal'];
        $exportRow[21] = $row['Ausencias'];
        $exportRow[22] = $row['SalarioNeto'];
        $exportRow[23] = $row['IGSS'];
        $exportRow[24] = $row['Otras'];
        $exportRow[25] = $row['Descuentos'];
        $exportRow[26] = $row['AnticipoSobreSueldo'];
        $exportRow[27] = $row['TotalDeducciones'];
        $exportRow[28] = $row['Aguinaldo'];
        $exportRow[29] = $row['Bono14'];
        $exportRow[30] = $row['Vacaciones'];
        $exportRow[31] = $row['Idemnizacion'];
        $exportRow[32] = $row['VentajasEconomicas'];
        $exportRow[33] = $row['AjusteSalarial'];
        $exportRow[34] = $row['BonificacionIncentivo'];
        $exportRow[35] = $row['OtrasBonificacioneseIncentivos'];
        $exportRow[36] = $row['LiquidoARecibir'];
        $exportRow[37] = $row['Observaciones'];
        $exportRow[38] = $row['BoletoDeOrnato'];
        $exportRow[39] = $row['DescuentoSeguro'];
        $exportRow[40] = $row['DescuentosJudiciales'];
        $exportRow[41] = $row['HeadSet'];
        $exportRow[42] = $row['ISREmpleados'];
        $exportRow[43] = $row['ParqueoEmpleados'];
        $exportRow[44] = $row['ParqueoMotos'];
        $exportRow[45] = $row['TarjetaDeParqueo'];
        $exportRow[46] = $row['TransporteEnBus'];
        $exportRow[47] = $row['PrestamoPersonal'];
        $exportRow[48] = $row['AjustesPeriodos'];
        $exportRow[49] = $row['BonosDiversos'];
        $exportRow[50] = $row['BonoPorAsistencia'];
        $exportRow[51] = $row['TreasureHunt'];
        $exportRow[52] = $row['BonosPorReferidos'];
        $exportRow[53] = $row['BonosPorReclutamiento'];
        fputcsv($output, $exportRow,",");
        $i++;

    };
}else{
    http_response_code(404);
}
fclose($output);
?>