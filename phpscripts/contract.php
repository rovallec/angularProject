<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

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

    $id_employee = $_GET['id'];

    $sql = "SELECT `hires`.*, `employees`.*, `waves`.`ops_start`, `waves`.`base_payment` AS `base`, `waves`.`productivity_payment` AS `prod`, `profiles`.*, `education_details`.*, `contact_details`.* FROM `hires` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires` LEFT JOIN `waves` ON `waves`.`idwaves` = `hires`.`id_wave` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN `education_details` ON `education_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `contact_details` ON `contact_details`.`id_profile` = `profiles`.`idprofiles` WHERE `idemployees` = '$id_employee';";
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

            $gender = $gender . ", " . ", " . $row['profesion'] . ", " . $row['nationality'];

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
            $total_n = number_format(((float)$total_n),2);

            if(count(explode(".",$total_n)) < 2){
                $total_n = $total_n . ".00";
            }
            
            $dpi_1 = str_split($dpi_n, 1);
            $dpi_2 = $dpi_1[0] . $dpi_1[1] . $dpi_1[2] . $dpi_1[3];
            $dpi_3 = $dpi_1[4] . $dpi_1[5] . $dpi_1[6] . $dpi_1[7] . $dpi_1[8];
            $dpi_4 = $dpi_1[10] . $dpi_1[11];
            $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
            $t = $f->format($dpi_2);
            $t = $t . " espacio " . $f->format($dpi_3);
            $t = $t . " espacio " . $f->format($dpi_1[9]);
            $t = $t . " " . $f->format($dpi_4);

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
            $prs =
            $date_letters = $f->format($dt[0]) . " de " . $mnth[intval($dt[1])-1] . " de " . $f->format($dt[2]);

            $today_date = date("Y-m-d");
            $dt_end = explode("-", $today_date);
            $day = $dt_end[2];
            $mn = $mnth[intval($dt_end[1])-1];
            $yr = $dt_end[0];
        }

            if($platform == 'WAH'){
                echo("
                <head>
                </head>
                <body>
                <div style='margin-left:20px; width:800px'>
                    <table>
                        <tr>
                            <td colspan='2' style='text-align:center;font-family:Stencil;font-size:14px;border-bottom:solid black 2px;color:#808080'>NEARSOL, SOCIEDAD ANONIMA</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align:center;font-family:Calibri;font-size:11px;font-weight:bold'>CONTRATO INDIVIDUAL DE TRABAJO</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='font-family:Calibri;font-size:11px;  text-align: justify;text-justify: inter-word;line-height:1.9'><p style='margin:0px;padding:0px'>CONTRATO INDIVIDUAL DE TRABAJO
                            Nosotros, la entidad NEARSOL, SOCIEDAD ANONIMA, con sede social ubicada en Veinte calle cinco guion veinticinco zona diez, Ciudad de Guatemala, debidamente inscrita bajo el número ciento cincuenta y cinco mil cuatrocientos treinta y ocho, folio ciento setenta y uno del libro cuatrocientos ochenta y dos de Sociedades Mercantiles. Representada por la señora NANCY LUCRECIA SALAZAR ESCOBAR, de treinta y nueve años de edad, de sexo femenino, soltera, Empresaria, guatemalteca, de este domicilio, quien se identifica con documento personal de identificación (DPI) Código Único de Identificación (CUI) dos mil cuatrocientos noventa y dos espacio doce mil seiscientos sesenta y ocho espacio mil doscientos diecisiete (2492126681217), extendido por el Registro Nacional de las Personas, quien actúa en su calidad de Administrador Único y Representante Legal, calidad que acredita con el acta de su nombramiento de fecha diecinueve de diciembre de dos mil dieciocho, registrada bajo el número quinientos cincuenta y un mil trescientos veinticinco, folio trescientos treinta y siete del libro setecientos dos de Auxiliares de Comercio, Registro Mercantil y que en adelante podre ser denominado 'el Patrono', señalo como lugar para recibir cualquier comunicación, citación o notificación Avenida Reforma quince guion ochenta y cinco zona diez (10), Edificio Torre Internacional, séptimo nivel y por la otra parte  (B) $name, de $age de edad, de sexo $gender, con domicilio en $address, vecino de $nationality, con DPI # $t ($dpi_n) extendido por el Registro Nacional de las Personas, República de Guatemala, quien en el transcurso del presente Contrato podré ser denominado <b>'el Trabajador'</b> o <b>'el Empleado'</b>, señalo como lugar para recibir cualquier comunicación, citación o notificación en $address, vecino del municipio de $municipio, ambas partes reconocemos expresamente la personalidad con que se ostentan, para todos los efectos legales a que haya lugar. En virtud de lo declarado y para el logro de las finalidades que las partes nos proponemos, el Contrato Individual de Trabajo Exclusivo <b>por tiempo indefinido</b>, lo sujetamos al tenor de las siguientes cláusulas: <u><b>PRIMERA:</b></u> El Trabajador manifiesta bajo protesta de decir verdad, que tiene los conocimientos y capacidad suficiente, así como la práctica e interés necesario para el desempeño del trabajo que ha solicitado el cual desempeñará en forma exclusiva para el patrono. El patrón manifiesta por su parte, tener las facultades legales para obligarse, celebrando el presente Contrato. <u><b>SEGUNDA:</b></u> <b>DEL INICIO DE LA RELACION LABORAL:</b> <u><b>El día $date_letters ($hiring_date) se inició la relación laboral. TERCERA:</b></u> <b>DEL CARGO A DESEMPEÑAR.</b> El Trabajador desempeñará el cargo de <u>$job</u> El Patrono podrá asignar al Trabajador una nueva área de trabajo, siempre dentro de sus capacidades, en igualdad de condiciones y dentro del giro del negocio que presta el Patrono referido al inicio de este contrato, manteniendo el Trabajador el mismo salario, y sin que ello pueda ser considerado como despido indirecto. El Empleado deberá ejecutar todos los servicios y funciones que le instruya el Patrono por escrito o verbalmente, así como todos aquellos que por su propia naturaleza estén comprendidos, incluidos, relacionados o sean compatibles con su cargo y tendrá las obligaciones que le imponga el Reglamento Interior de Trabajo del Patrono, los manuales técnicos y políticas internas del Patrono, emitidos por el Patrono. <u><b>CUARTA:</b></u> <b>DEL LUGAR DE LA PRESTACION DE LOS SERVICIOS.</b> En vista de la naturaleza del giro del negocio del Patrono y del cargo asignado al Trabajador, los servicios serán prestados personalmente por EL TRABAJADOR en cualquiera de nuestras sedes, bajo la modalidad de trabajo desde casa o en las instalaciones que el patrono designe conforme los requerimientos y las necesidades de la empresa: sin que el cambio de local o establecimiento signifique cambio en las condiciones de trabajo. Por lo anterior, el trabajador expresamente acepta que prestará sus servicios en las instalaciones que el patrono designe siendo estas del patrono o de terceras personas. Para el efecto, cuando el trabajador se encuentre prestando sus servicios en las instalaciones de terceras personas, por virtud de la relación comercial de éste con el Patrono, se entenderá que la supervisión y dirección no será delegada en el tercero a favor del cual se estén prestando los servicios, la misma corresponderá en todo momento al Patrono. <u><b>QUINTA:</b></u> <b>DE LA DURACION DEL CONTRATO.</b> El presente contrato se celebra por <u><b>plazo indefinido</b></u> y podrá modificarse, rescindirse o terminarse, en los casos establecidos en la Legislación Laboral. De conformidad con lo establecido en el artículo 81 del Código de Trabajo, se reputan como período de prueba los dos primeros meses, durante el cual, cualquiera de las partes puede ponerle término al contrato, por su propia voluntad, con justa causa o sin ella, sin incurrir en responsabilidad alguna. El trabajador queda obligado a someterse a las pruebas o exámenes que sean considerados por el Patrono necesarios, durante este período. <u><b>SEXTA:</b></u> <u><b>HORARIO DE LA JORNADA:</b></u> Por la naturaleza de los servicios que presta la parte patronal, el trabajador (a) acepta y tiene conocimiento que existen varias jornadas y horarios de trabajo, y que por este instrumento legal <u>acepta trabajar en cualquiera de ellas de acuerdo a las necesidades que se den dentro de la empresa</u>, de igual forma entiende que los días de descanso variaran de acuerdo a la jornada en la cual le toque desempeñar sus actividades. Los horarios de los colaboradores serán establecidos de acuerdo al siguiente rango de horas laborales: La jornada de trabajo efectivo diurno será de hasta cuarenta y cuatro horas a la semana, la jornada de trabajo efectivo mixta será de hasta cuarenta y dos horas a la semana, la jornada de trabajo efectivo nocturna será de hasta treinta y seis horas a la semana; según lo establecido en el Reglamento Interno de Trabajo y sus modificaciones futuras y/o en el Código de Trabajo de Guatemala. Los horarios pueden sufrir cambios de acuerdo a las necesidades de los clientes, la empresa notificará al trabajador previamente al cambio requerido. Todo trabajador tendrá derecho a disfrutar de uno o dos días de descanso remunerado después de cada semana de trabajo, según la jornada en que labore. Según lo estipulado en el artículo 119 los empleados tienen derecho a gozar de descanso en una jornada continua o discontinua, en la cual se contempla de treinta minutos a una hora de almuerzo y dos descansos de quince minutos cada uno en los horarios pactados. <u><b>SEPTIMA:</b></u> EL SALARIO A DEVENGAR El Trabajador devengará un salario base de $base_n_int_l Quetzales con $base_n_cent_l centavos mensuales <b>(Q.$base_n)</b> más la bonificación incentivo de ley de doscientos cincuenta quetzales <b>(Q.250.00)</b> Adicionalmente el trabajador <u><b>podrá</b></u> recibir el pago de bonificación incentivo por productividad (Decreto 78-89), de acuerdo a la evaluación de desempeño que realizará mensualmente el patrono del cumplimiento de los estándares de efectividad que establezcan para su puesto de trabajo y que le serán comunicados periódicamente por escrito.  Los pagos se realizarán por medios electrónicos, de mutuo acuerdo, o en cualquiera de los establecimientos del patrono. Las partes expresamente reconocen que de conformidad con la ley (Decreto 78-89 del Congreso de la República), el artículo 4 del Acuerdo 1118 de la Junta Directiva del IGSS y de acuerdo al criterio constitucional esgrimido en la sentencia de fecha 8 de octubre del 2003 por la Corte de Constitucionalidad, dentro de los expedientes acumulados 580-2003, 613-2003 y 649-2003, el monto total que se cancele en concepto de bonificación incentivo, no se encuentra afecta al pago de cuotas del IGSS, IRTRA e INTECAP, tanto para el trabajador, como para el patrono de $incentivo_n_int_l Quetzales con $incentivo_n_cent_l centavos <b>(Q.$incentivo_n)</b> para un total de $total_n_int_l con $total_n_cent_l centavos <b>(Q.$total_n)</b> menos los descuentos de ley, que el Patrono pagará al trabajador sin perjuicio de las demás prestaciones legales que correspondan al Trabajador. Asimismo, el trabajador gozará del pago de: a) aguinaldo anual; y b) Bonificación Anual para Trabajadores del Sector Privado y Público (Bono 14).  El trabajador no laborara horas extras si no es con previa autorización por escrito de su patrono quedando entendido que dicha autorización es el único comprobante para acreditar al trabajador las horas extraordinarias que pudiere llegar a laborar para el patrono cuando se las requiera. Las cuales se pagarán como lo estipula el Código de Trabajo de Guatemala Art. 121,122, 128 y 129. <u><b>OCTAVA:</b></u> <b>DEL LUGAR, FECHA, FORMA, Y HORA DE PAGO.</b> De conformidad con lo establecido por los artículos del noventa al noventa y cinco del Código de Trabajo, el salario será pagado por medio de cheque o depósito en la cuenta bancaria que para el efecto se establezca, en forma quincenal dentro de las horas de trabajo o inmediatamente después de que concluya la jornada, siempre en moneda de curso legal pagándosele al trabajador su salario menos los descuentos de ley, los días 3 y 18 de cada mes incluyendo bonificación de ley. <u><b>NOVENA:</b></u> <b>OBLIGACIONES DEL PATRONO:</b> El Patrono se obliga a cumplir con todas las obligaciones y prohibiciones contenidas en los artículos 61 y 62 del Código de Trabajo. <u><b>DECIMA:</b></u> <b>OBLIGACIONES DEL TRABAJADOR:</b>
                            El Trabajador se obliga a cumplir con todas las obligaciones y prohibiciones contenidas en los artículos 63 y 64 del Código de Trabajo, pero especialmente a: 1) Contestar llamadas que ingresen en su estación de trabajo de acuerdo a los lineamientos de la
                            capacitación. 2) Proveer asistencia sobre las llamadas entrantes de acuerdo a los lineamientos de
                            la capacitación. 3) Documentar las acciones tomadas en todas las llamadas de acuerdo a los
                            lineamientos de la capacitación. 4) Cumplir con las metas y los estándares de calidad requeridos
                            de parte de la empresa, los cuales son: -tiempo promedio de llamada, el cual es establecido según
                            las necesidades de la empresa. - Calidad en procedimientos durante las llamadas, el cual es
                            establecido por la empresa. - Nivel de atención al cliente, el cual es medido por lo clientes que se
                            atienden. - Porcentaje de retención de clientes en ventas establecido por la empresa. -
                            Cumplimiento de metas de ventas y/o cobros establecidos por la empresa. 5) Cumplir con la
                            cantidad de horas de conexión requeridas por la empresa. 6) Asistir a los departamentos de
                            supervisión, calidad o control de operaciones cuando sea requerido. 7) Hacer uso adecuado del
                            sistema, evitando el desvío de llamadas o la omisión de tomar llamadas. 8) Otras que se vayan
                            estableciendo en común acuerdo con el trabajador en forma individual o bien en forma global, de
                            acuerdo a las necesidades de la Empresa, los clientes o los trabajadores. 9) Cualquier actividad
                            inherente al puesto. <b>A)</b> A ejecutar el trabajo personal con la intensidad, cuidado y esmero apropiados a NEARSOL, SOCIEDAD ANONIMA, bajo su dirección, dependencia y subordinación, así como a cumplir las órdenes e instrucciones que reciba en todo lo concerniente al trabajo que, precisamente, consistirá en <b>B)</b> Marcar tarjeta, reloj biométrico</p></td>
                            </td>
                        </tr>
                        <tr>
                            <td><div style='page-break-after:always;'></div></td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align:center;font-family:Stencil;font-size:14px;border-bottom:solid black 2px;color:#808080'>NEARSOL, SOCIEDAD ANONIMA</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='font-family:Calibri;font-size:11px;  text-align: justify;text-justify: inter-word;line-height:1.9'><p style='margin:0px;padding:0px'>o a firmar las listas de asistencia respectivas a la entrada y salida de sus labores. El incumplimiento de este requisito se computará como falta injustificada para todos los efectos legales a que haya lugar. <b>C)</b> A someterse a los exámenes y reconocimientos médicos que en forma periódica determine la empresa sean necesarios, el negarse a ello, será causa suficiente para ser separado de su trabajo sin responsabilidad para el Patrón. <b>D)</b> Mantener una relación de exclusividad en la cual, el Trabajador prestará sus servicios en forma exclusiva al Patrono y no podrá prestar sus servicios de forma simultánea en otras empresas similares o que realicen actividades comerciales parecidas a esta empresa, salvo que éste autorice lo contrario por escrito. <u><b>La violación de este pacto será considerada falta grave del Trabajador y será causa de despido justificado;</b></u> <b>E)</b> A sujetarse y recibir los cursos de Capacitación y Adiestramiento que el patrono le proporcione, en su caso; y,  <b>F)</b> Mantener la confidencialidad por virtud de la cual el Trabajador se obliga a guardar fielmente y no transmitir a terceros, en ninguna forma, los datos, informaciones, clases de negocios, procedimientos de operación, situación financiera y en general cualquier dato o información del Patrono o con los que éste se relacione, y a la que tenga acceso durante el desempeño de sus labores por la naturaleza de confianza de éstas, o por cualquier otra razón. El incumplimiento de esta obligación otorga derecho al Patrono para dar por terminado con justa causa este contrato de trabajo y para deducir al Trabajador las responsabilidades civiles y penales en que hubiere incurrido. Deberá mantener la confidencialidad por un año luego de haber terminado la relación laboral. <u><b>DECIMA PRIMERA:</b></u> El Trabajador y el Patrón convienen en que éste cuenta con la facultad de cambiar al trabajador de horario, lugar o actividad, siempre y cuando se le respete la categoría y el salario. El trabajador se compromete a ejecutar sus labores en el domicilio de la Empresa o en aquél donde llegare a cambiarse. <u><b>DECIMA SEGUNDA: CONFIDENCIALIDAD Y RESPONSABILIDAD::</b></u> El trabajador entiende que la información con la que trabaja es de carácter confidencial de los clientes que utilizan los servicios prestados por esta empresa, en la cual el trabajador presta sus servicios y se compromete a no publicar, guardar, trasferir, compartir, ni aprovecharse personalmente o por medio de terceras personas de dicha información. Aceptando que de no cumplir con lo estipulado aquí ser objeto de responsabilidades de carácter civil y/o penal, ante los tribunales de justicia siendo responsable de daños y perjuicios así como de cualquier reclamación por parte de los clientes en contra de la empresa. Queda claramente convenido que en caso de que el trabajador incumpla con lo anterior, podrá ser inmediatamente separado de su puesto sin responsabilidad alguna para el patrono, estipulado el Artículo 77 inciso E del código de trabajo de Guatemala. <u><b>DECIMA TERCERA:</b></u> Si llegase el trabajador, con motivo de sus funciones subordinadas, a realizar alguna obra susceptible de ser protegida por el Derecho de Autor, los derechos patrimoniales sobre la misma pertenecerán al patrón, y desde este mismo momento cede a favor NEARSOL, SOCIEDAD ANONIMA los derechos que llegase a generar. En virtud del presente contrato y de conformidad con las disposiciones legales vigentes aplicables en materia de Derechos de Autor, el patrón será el único y exclusivo titular de los derechos que deriven del resultado de las actividades desarrolladas por el trabajador en cumplimiento de las obligaciones que asume en este contrato, contando el patrón como consecuencia de lo anterior, con las facultades relativas a la divulgación, integridad y de colección sobre el resultado de tales actividades. <u><b>DECIMA CUARTA: ERRORES O PERDIDAS:</b></u> Las pérdidas que sufra el patrono en numerario o mercancía, basadas en errores, pérdidas o averías, serán a cargo del trabajador responsable al desempeñar su puesto. <u><b>DECIMA QUINTA:</u><b><u>DECIMA QUINTA:</u></b> <b> DE LA ACEPTACION.</b> Las partes manifestamos que hemos leído en forma íntegrael presente Contrato Individual de Trabajo y sabedores de su alcance y consecuencias jurídicas, lo aceptamos,ratificamos, y firmamosen tres copias, una para cada una de las partes y la tercera que el Patrono entregará a las autoridades de Trabajo dentro del término legal.</p></td>
                        <tr>
                        <tr>
                            <td colspan='2' style='font-family:Calibri;font-size:11px;  text-align: justify;text-justify: inter-word;line-height:1.9'><p style='margin:0px;padding:0px'>GUATEMALA, $day de $mn de $yr</p><td>
                        </tr>
                        <tr style='height:20px'></tr>
                        <tr>
                        <td>
                            <table style='width:100%'>
                                <tr>
                                    <td style='width:10%'><td>
                                    <td style='width:35%'>
                                        <table style='width:100%'>
                                            <tr style='height:80px'>
                                            <td><img src='http://200.94.251.67/assets/nSalazar.png' style='width:100%; height:75px'></td>
                                            </tr>
                                            <tr>
                                                <td style='border-top:2px solid black;font-size:12px;text-align:center'>NANCY LUCRECIA SALAZAR ESCOBAR</td>
                                            </tr>
                                            <tr>
                                                <td>Patrono</td>
                                            </tr>
                                        </table>
                                    <td>
                                    <td style='width:10%'><td>
                                    <td style='width:35%'>
                                        <table style='width:100%'>
                                            <tr style='height:80px'>
                                            </tr>
                                            <tr>
                                                <td style='border-top:2px solid black;font-size:12px;text-align:center'>$name</td>
                                            </tr>
                                            <tr>
                                                <td>Trabajador</td>
                                            </tr>
                                        </table>
                                    <td>
                                    <td style='width:10%'><td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </table>
                    <div style='page-break-after:always;'></div>
                </body>
                </div>
                ");
            }else{
                if($platform == 'ON SITE'){
                echo("
            <head>
            </head>
            <body>
            <div style='margin-left:20px; width:800px'>
                <table>
                    <tr>
                        <td colspan='2' style='text-align:center;font-family:Stencil;font-size:14px;border-bottom:solid black 2px;color:#808080'>NEARSOL, SOCIEDAD ANONIMA</td>
                    </tr>
                    <tr>
                        <td colspan='2' style='text-align:center;font-family:Calibri;font-size:11px;font-weight:bold'>CONTRATO INDIVIDUAL DE TRABAJO</td>
                    </tr>
                    <tr>
                        <td colspan='2' style='font-family:Calibri;font-size:11px;  text-align: justify;text-justify: inter-word;line-height:1.9'><p style='margin:0px;padding:0px'>Nosotros, la entidad NEARSOL, SOCIEDAD ANONIMA, con sede social ubicada en Veinte calle cinco guion veinticinco zona diez, Ciudad de Guatemala, debidamente inscrita bajo  el número ciento cincuenta y cinco mil cuatrocientos treinta y ocho, folio ciento setenta y uno del libro cuatrocientos ochenta y dos de Sociedades Mercantiles. Representada por la señora NANCY LUCRECIA SALAZAR ESCOBAR, de treinta y nueve años de edad, de sexo femenino, soltera, Empresaria, guatemalteca, de este domicilio, quien se identifica con documento personal de identificación (DPI) Código Único de Identificación (CUI) dos mil cuatrocientos noventa y dos espacio doce mil seiscientos sesenta y ocho espacio mil doscientos diecisiete (2492126681217), extendido por el Registro Nacional de las Personas, quien actúa en su calidad de Administrador Único y Representante Legal, calidad que acredita con el acta de su nombramiento de fecha diecinueve de diciembre de dos mil dieciocho, registrada bajo el número quinientos cincuenta y un mil trescientos veinticinco, folio trescientos treinta y siete del libro setecientos dos de Auxiliares de Comercio, Registro Mercantil y que en adelante podre ser denominado 'el Patrono', señalo como lugar para recibir cualquier comunicación, citación o notificación Avenida Reforma quince guion ochenta y cinco zona diez (10), Edificio Torre Internacional, séptimo nivel, oficina 701 y por la otra parte (B) $name, de $age de edad, de sexo $gender, con domicilio en la $address, vecino de $municipio, con DPI # $t ($dpi_n) extendido por el Registro Nacional de las Personas, República de Guatemala, quien en el transcurso del presente Contrato podré ser denominado <b>'el Trabajador'</b> o <b>'el Empleado'</b>, señalo como lugar para recibir cualquier comunicación, citación o notificación en $address, vecino del municipio de $municipio, ambas partes reconocemos expresamente la personalidad con que se ostentan, para todos los efectos legales a que haya lugar. En virtud de lo declarado y para el logro de las finalidades que las partes nos proponemos, el Contrato Individual de Trabajo Exclusivo <b>por tiempo indefinido</b>, lo sujetamos al tenor de las siguientes cláusulas: <u><b>PRIMERA:</b></u> El Trabajador manifiesta bajo protesta de decir verdad, que tiene los conocimientos y capacidad suficiente, así como la práctica e interés necesario para el desempeño del trabajo que ha solicitado el cual desempeñará en forma exclusiva para el patrono. El patrón manifiesta por su parte, tener las facultades legales para obligarse, celebrando el presente Contrato. <u><b>SEGUNDA:</b></u> <b>DEL INICIO DE LA RELACION LABORAL:</b> <u><b>El día $date_letters ($hiring_date) se inició la relación laboral. TERCERA:</b></u> <b>DEL CARGO A DESEMPEÑAR.</b> El Trabajador desempeñará el cargo de <u>$job</u> El Patrono podrá asignar al Trabajador una nueva área de trabajo, siempre dentro de sus capacidades, en igualdad de condiciones y dentro del giro del negocio que presta el Patrono referido al inicio de este contrato, manteniendo el Trabajador el mismo salario, y sin que ello pueda ser considerado como despido indirecto. El Empleado deberá ejecutar todos los servicios y funciones que le instruya el Patrono por escrito o verbalmente, así como todos aquellos que por su propia naturaleza estén comprendidos, incluidos, relacionados o sean compatibles con su cargo y tendrá las obligaciones que le imponga el Reglamento Interior de Trabajo del Patrono, los manuales técnicos y políticas internas del Patrono, emitidos por el Patrono. <u><b>CUARTA:</b></u> <b>DEL LUGAR DE LA PRESTACION DE LOS SERVICIOS.</b> . En vista de la naturaleza del giro del negocio del Patrono y del cargo asignado al Trabajador, los servicios serán prestados personalmente por EL TRABAJADOR en cualquiera de nuestras sedes, bajo la modalidad de trabajo desde casa o en las instalaciones que el patrono designe conforme los requerimientos y las necesidades de la empresa: sin que el cambio de local o establecimiento signifique cambio en las condiciones de trabajo. Por lo anterior, el trabajador expresamente acepta que prestará sus servicios en las instalaciones que el patrono designe siendo estas del patrono o de terceras personas. Para el efecto, cuando el trabajador se encuentre prestando sus servicios en las instalaciones de terceras personas, por virtud de la relación comercial de éste con el Patrono, se entenderá que la supervisión y dirección no será delegada en el tercero a favor del cual se estén prestando los servicios, la misma corresponderá en todo momento al Patrono. <u><b>QUINTA:</b></u> <b>DE LA DURACION DEL CONTRATO.</b> El presente contrato se celebra por <u><b>plazo indefinido</b></u> y podrá modificarse, rescindirse o terminarse, en los casos establecidos en la Legislación Laboral. De conformidad con lo establecido en el artículo 81 del Código de Trabajo, se reputan como período de prueba los dos primeros meses, durante el cual, cualquiera de las partes puede ponerle término al contrato, por su propia voluntad, con justa causa o sin ella, sin incurrir en responsabilidad alguna. El trabajador queda obligado a someterse a las pruebas o exámenes que sean considerados por el Patrono necesarios, durante este período. <u><b>SEXTA:</b></u> <u><b>HORARIO DE LA JORNADA:</b></u> Por la naturaleza de los servicios que presta la parte patronal, el trabajador (a) acepta y tiene conocimiento que existen varias jornadas y horarios de trabajo, y que por este instrumento legal <u>acepta trabajar en cualquiera de ellas de acuerdo a las necesidades que se den dentro de la empresa</u>, de igual forma entiende que los días de descanso variaran de acuerdo a la jornada en la cual le toque desempeñar sus actividades. Los horarios de los colaboradores serán establecidos de acuerdo al siguiente rango de horas laborales: La jornada de trabajo efectivo diurno será de hasta cuarenta y cuatro horas a la semana, la jornada de trabajo efectivo mixta será de hasta cuarenta y dos horas a la semana, la jornada de trabajo efectivo nocturna será de hasta treinta y seis horas a la semana; según lo establecido en el Reglamento Interno de Trabajo y sus modificaciones futuras y/o en el Código de Trabajo de Guatemala. Los horarios pueden sufrir cambios de acuerdo a las necesidades de los clientes, la empresa notificará al trabajador previamente al cambio requerido. Todo trabajador tendrá derecho a disfrutar de uno o dos días de descanso remunerado después de cada semana de trabajo, según la jornada en que labore. Según lo estipulado en el artículo 119 los empleados tienen derecho a gozar de descanso en una jornada continua o discontinua, en la cual se contempla de treinta minutos a una hora de almuerzo y dos descansos de quince minutos cada uno en los horarios pactados. <u><b>SEPTIMA:</b></u> EL SALARIO A DEVENGAR El Trabajador devengará un salario base de $base_n_int_l Quetzales con $base_n_cent_l centavos mensuales <b>(Q.$base_n)</b> más la bonificación incentivo de ley de doscientos cincuenta quetzales <b>(Q.250.00)</b> Adicionalmente el trabajador <u><b>podrá</b></u> recibir el pago de bonificación incentivo por productividad (Decreto 78-89), de acuerdo a la evaluación de desempeño que realizará mensualmente el patrono del cumplimiento de los estándares de efectividad que establezcan para su puesto de trabajo y que le serán comunicados periódicamente por escrito.  Los pagos se realizarán por medios electrónicos, de mutuo acuerdo, o en cualquiera de los establecimientos del patrono. Las partes expresamente reconocen que de conformidad con la ley (Decreto 78-89 del Congreso de la República), el artículo 4 del Acuerdo 1118 de la Junta Directiva del IGSS y de acuerdo al criterio constitucional esgrimido en la sentencia de fecha 8 de octubre del 2003 por la Corte de Constitucionalidad, dentro de los expedientes acumulados 580-2003, 613-2003 y 649-2003, el monto total que se cancele en concepto de bonificación incentivo, no se encuentra afecta al pago de cuotas del IGSS, IRTRA e INTECAP, tanto para el trabajador, como para el patrono de $incentivo_n_int_l Quetzalez con $incentivo_n_cent_l centavos <b>(Q.$incentivo_n)</b> para un total de $total_n_int_l con $total_n_cent_l centavos <b>(Q.$total_n)</b> menos los descuentos de ley, que el Patrono pagará al trabajador sin perjuicio de las demás prestaciones legales que correspondan al Trabajador. Asimismo, el trabajador gozará del pago de: a) aguinaldo anual; y b) Bonificación Anual para Trabajadores del Sector Privado y Público (Bono 14).  El trabajador no laborara horas extras si no es con previa autorización por escrito de su patrono quedando entendido que dicha autorización es el único comprobante para acreditar al trabajador las horas extraordinarias que pudiere llegar a laborar para el patrono cuando se las requiera. Las cuales se pagarán como lo estipula el Código de Trabajo de Guatemala Art. 121,122, 128 y 129. <u><b>OCTAVA:</b></u> <b>DEL LUGAR, FECHA, FORMA, Y HORA DE PAGO.</b> De conformidad con lo establecido por los artículos del noventa al noventa y cinco del Código de Trabajo, el salario será pagado por medio de cheque o depósito en la cuenta bancaria que para el efecto se establezca, en forma quincenal dentro de las horas de trabajo o inmediatamente después de que concluya la jornada, siempre en moneda de curso legal pagándosele al trabajador su salario menos los descuentos de ley, los días 3 y 18 de cada mes incluyendo bonificación de ley. <u><b>NOVENA:</b></u> <b>OBLIGACIONES DEL PATRONO:</b> El Patrono se obliga a cumplir con todas las obligaciones y prohibiciones contenidas en los artículos 61 y 62 del Código de Trabajo. <u><b>DECIMA:</b></u> <b>OBLIGACIONES DEL TRABAJADOR:</b> 
                        El Trabajador se obliga a cumplir con todas las obligaciones y prohibiciones contenidas en los artículos 63 y 64 del Código de Trabajo, pero especialmente a: 1) Contestar llamadas que ingresen en su estación de trabajo de acuerdo a los lineamientos de la
                        capacitación. 2) Proveer asistencia sobre las llamadas entrantes de acuerdo a los lineamientos de
                        la capacitación. 3) Documentar las acciones tomadas en todas las llamadas de acuerdo a los
                        lineamientos de la capacitación. 4) Cumplir con las metas y los estándares de calidad requeridos
                        de parte de la empresa, los cuales son: -tiempo promedio de llamada, el cual es establecido según
                        las necesidades de la empresa. - Calidad en procedimientos durante las llamadas, el cual es
                        establecido por la empresa. - Nivel de atención al cliente, el cual es medido por lo clientes que se
                        atienden. - Porcentaje de retención de clientes en ventas establecido por la empresa. -
                        Cumplimiento de metas de ventas y/o cobros establecidos por la empresa. 5) Cumplir con la
                        cantidad de horas de conexión requeridas por la empresa. 6) Asistir a los departamentos de
                        supervisión, calidad o control de operaciones cuando sea requerido. 7) Hacer uso adecuado de las
                        instalaciones y el equipo de cómputo asignado. 8) Hacer uso adecuado del sistema, evitando el
                        desvío de llamadas o la omisión de tomar llamadas. 9) Otras que se vayan estableciendo en común
                        acuerdo con el trabajador en forma individual o bien en forma global, de acuerdo a las
                        necesidades de la Empresa, los clientes o los trabajadores. 10) Cualquier actividad inherente al
                        puesto. <b>A)</b> A ejecutar el trabajo personal con la intensidad, cuidado y esmero apropiados a  NEARSOL,  SOCIEDAD ANONIMA,  bajo su dirección, dependencia y subordinación, así como a cumplir las órdenes e instrucciones que reciba en todo lo concerniente al trabajo que, <b style='color:white'>a</b> precisamente, <b style='color:white'>a</b>  consistirá  en  <b>B)</b> Marcar tarjeta,</p>
                        </td>
                    </tr>
                    <tr>
                        <td><div style='page-break-after:always;'></div></td>
                    </tr>
                    <tr>
                        <td colspan='2' style='text-align:center;font-family:Stencil;font-size:14px;border-bottom:solid black 2px;color:#808080'>NEARSOL, SOCIEDAD ANONIMA</td>
                    </tr>
                    <tr>
                        <td colspan='2' style='font-family:Calibri;font-size:11px;  text-align: justify;text-justify: inter-word;line-height:1.9'><p style='margin:0px;padding:0px'>biométrico o a firmar las listas de asistencia respectivas a la entrada y salida de sus labores. El incumplimiento de este requisito se computará como falta injustificada para todos los efectos legales a que haya lugar. <b>C)</b> A someterse a los exámenes y reconocimientos médicos que en forma periódica determine la empresa sean necesarios, el negarse a ello, será causa suficiente para ser separado de su trabajo sin responsabilidad para el Patrón. <b>D)</b> Mantener una relación de exclusividad en la cual, el Trabajador prestará sus servicios en forma exclusiva al Patrono y no podrá prestar sus servicios de forma simultánea en otras empresas similares o que realicen actividades comerciales parecidas a esta empresa, salvo que éste autorice lo contrario por escrito. <u><b>La violación de este pacto será considerada falta grave del Trabajador y será causa de despido justificado;</b></u> <b>E)</b> A sujetarse y recibir los cursos de Capacitación y Adiestramiento que el patrono le proporcione, en su caso; y,  <b>F)</b> Mantener la confidencialidad por virtud de la cual el Trabajador se obliga a guardar fielmente y no transmitir a terceros, en ninguna forma, los datos, informaciones, clases de negocios, procedimientos de operación, situación financiera y en general cualquier dato o información del Patrono o con los que éste se relacione, y a la que tenga acceso durante el desempeño de sus labores por la naturaleza de confianza de éstas, o por cualquier otra razón. El incumplimiento de esta obligación otorga derecho al Patrono para dar por terminado con justa causa este contrato de trabajo y para deducir al Trabajador las responsabilidades civiles y penales en que hubiere incurrido. Deberá mantener la confidencialidad por un año luego de haber terminado la relación laboral. <u><b>DECIMA PRIMERA:</b></u> El Trabajador y el Patrón convienen en que éste cuenta con la facultad de cambiar al trabajador de horario, lugar o actividad, siempre y cuando se le respete la categoría y el salario. El trabajador se compromete a ejecutar sus labores en el domicilio de la Empresa o en aquél donde llegare a cambiarse. <u><b>DECIMA SEGUNDA: CONFIDENCIALIDAD Y RESPONSABILIDAD::</b></u> El trabajador entiende que la información con la que trabaja es de carácter confidencial de los clientes que utilizan los servicios prestados por esta empresa, en la cual el trabajador presta sus servicios y se compromete a no publicar, guardar, trasferir, compartir, ni aprovecharse personalmente o por medio de terceras personas de dicha información. Aceptando que de no cumplir con lo estipulado aquí ser objeto de responsabilidades de carácter civil y/o penal, ante los tribunales de justicia siendo responsable de daños y perjuicios así como de cualquier reclamación por parte de los clientes en contra de la empresa. Queda claramente convenido que en caso de que el trabajador incumpla con lo anterior, podrá ser inmediatamente separado de su puesto sin responsabilidad alguna para el patrono, estipulado el Artículo 77 inciso E del código de trabajo de Guatemala. <u><b>DECIMA TERCERA:</b></u> Si llegase el trabajador, con motivo de sus funciones subordinadas, a realizar alguna obra susceptible de ser protegida por el Derecho de Autor, los derechos patrimoniales sobre la misma pertenecerán al patrón, y desde este mismo momento cede a favor NEARSOL, SOCIEDAD ANONIMA los derechos que llegase a generar. En virtud del presente contrato y de conformidad con las disposiciones legales vigentes aplicables en materia de Derechos de Autor, el patrón será el único y exclusivo titular de los derechos que deriven del resultado de las actividades desarrolladas por el trabajador en cumplimiento de las obligaciones que asume en este contrato, contando el patrón como consecuencia de lo anterior, con las facultades relativas a la divulgación, integridad y de colección sobre el resultado de tales actividades. <u><b>DECIMA CUARTA: ERRORES O PERDIDAS:</b></u> Las pérdidas que sufra el patrono en numerario o mercancía, basadas en errores, pérdidas o averías, serán a cargo del trabajador responsable al desempeñar su puesto. <u><b>DECIMA QUINTA:</u></b> <b>SISTEMA DE CIRCUITO CERRADO</b> El trabajador está enterado, entiende, acepta y manifiesta estar de acuerdo en que las instalaciones de la empresa cuentan con sistemas de seguridad  especial,  consistentes  en cámaras  de grabación digitalpor  medio de circuito cerrado; y  que por  medio de  este se  le  brinda de mayor seguridad  así  como  de control de  las actividades que realiza este dentro de la empresa, pudiendo constituirse en prueba si su actuar no obedece a lo acordado en este contrato o en las leyes del Estado.Las partes convienen que lo no previsto en este pacto se regirá por la legislación laboral de aplicación general, y para su interpretación, observancia, ejecución y cumplimiento, se someten expresamente a la competencia y jurisdicción de los tribunales laborales de la ciudad de Guatemala. <b><u>DECIMA SEXTA:</u> DE LA ACEPTACION.</b> Las partes manifestamos que hemos leído en forma íntegrael presente Contrato Individual de Trabajo y sabedores de su alcance y consecuencias jurídicas, lo aceptamos,ratificamos, y firmamosen tres copias, una para cada una de las partes y la tercera que el Patrono entregará a las autoridades de Trabajo dentro del término legal.</p></td>
                    </tr>
                    <tr>
                        <td colspan='2' style='font-family:Calibri;font-size:11px;  text-align: justify;text-justify: inter-word;line-height:1.9'><p style='margin:0px;padding:0px'>GUATEMALA, $day de $mn de $yr</p><td>
                    </tr>
                    <tr style='height:20px'></tr>
                    <tr>
                        <td>
                            <table style='width:100%'>
                                <tr>
                                    <td style='width:10%'><td>
                                    <td style='width:35%'>
                                        <table style='width:100%'>
                                            <tr style='height:80px'>
                                            <td><img src='http://200.94.251.67/assets/nSalazar.png' style='width:100%; height:75px'></td>
                                            </tr>
                                            <tr>
                                                <td style='border-top:2px solid black;font-size:12px;text-align:center'>NANCY LUCRECIA SALAZAR ESCOBAR</td>
                                            </tr>
                                        </table>
                                    <td>
                                    <td style='width:10%'><td>
                                    <td style='width:35%'>
                                        <table style='width:100%'>
                                            <tr style='height:80px'>
                                            </tr>
                                            <tr>
                                                <td style='border-top:2px solid black;font-size:12px;text-align:center'>$name</td>
                                            </tr>
                                        </table>
                                    <td>
                                    <td style='width:10%'><td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div style='page-break-after:always;'></div>
            </body>
            </div>
            ");
                }
            }
        }
    }
?>

