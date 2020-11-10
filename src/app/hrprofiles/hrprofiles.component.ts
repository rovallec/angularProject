import { Component, OnInit } from '@angular/core';
import { profiles } from '../profiles';
import { ApiService } from '../api.service';
import { ActivatedRoute } from '@angular/router';
import { attendences, attendences_adjustment, vacations, leaves, waves_template, disciplinary_processes, insurances, beneficiaries, terminations, reports, advances, accounts, rises, call_tracker, letters, supervisor_survey, judicials, irtra_requests, messagings, credits, periods, payments } from '../process_templates';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { users } from '../users';
import { isNullOrUndefined, isUndefined, isNull } from 'util';
import { process } from '../process';
import { TranslationWidth } from '@angular/common';
import { ThrowStmt } from '@angular/compiler';
import { typeWithParameters } from '@angular/compiler/src/render3/util';

@Component({
  selector: 'app-hrprofiles',
  templateUrl: './hrprofiles.component.html',
  styleUrls: ['./hrprofiles.component.css']
})
export class HrprofilesComponent implements OnInit {


  actualTerm: terminations = new terminations;

  profile: profiles[] = [new profiles()];
  staffes: users[] = [];

  attAdjudjment: attendences_adjustment = new attendences_adjustment;
  activeVacation: vacations = new vacations;
  activeLeave: leaves = new leaves;
  activeRequest = new disciplinary_processes;
  idInsurance: string;
  checkDate1: string;
  checkDate2: string;
  checkDay: string;
  vacationsEarned: number = 0;
  accId: string = '';
  addVac: boolean = true;
  useCompany: string = null;
  igss_patronal: string = null;
  dpTerm: boolean = false;
  workingEmployee: employees = new employees;
  riseIncrease: string = null;

  beneficiaryName: string;
  todayDate: string = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");

  showAttAdjustments: attendences_adjustment[] = [];
  showVacations: vacations[] = [];
  showAttendences: attendences[] = [];
  leaves: leaves[] = [];
  discilplinary_processes: disciplinary_processes[] = [];
  insurances: insurances = new insurances;
  beneficiaries: beneficiaries[] = [];
  process_templates: process[] = [];
  processRecord: process[] = [];
  allAccounts: accounts[] = [];
  actualMessagings: messagings = new messagings;
  actualIrtrarequests: irtra_requests = new irtra_requests;
  actualJudicial: judicials = new judicials;
  actualReport: reports = new reports;
  actualAdvance: advances = new advances;
  actualRise: rises = new rises;
  actualCallTracker: call_tracker = new call_tracker;
  actualLetters: letters = new letters;
  actualSurvey: supervisor_survey = new supervisor_survey;

  editInview: boolean = false;
  viewRecProd: boolean = false;
  addProc: boolean = false;
  actuallProc: process = new process;
  newProcess: boolean = false;
  addBeneficiary: boolean = false;
  modifyInsurance: boolean = false;
  newInsurance: boolean = false;
  insuranceNull: boolean = true;
  activeEmp: string = null;
  editAdj: boolean = false;
  vacationAdd: boolean = false;
  addJ: boolean = false;
  editVac: boolean = true;
  editLeave: boolean = false;
  showLeave: boolean = false;
  newRequest: boolean = false;
  reasonRequiered: boolean = false;
  setNewRequest: boolean = false;
  storedRequest: boolean = false;
  newAudience: string = "NO";
  editRequest: boolean = true;
  newSuspension: string = "NO";
  accChange: string = null;
  editableIrtra: boolean = false;
  editableIgss: boolean = false;

  earnVacations: number = 0;
  tookVacations: number = 0;
  availableVacations: number = 0;

  approvals: users[] = [new users];
  motives: string[] = ['Leave of Absence Unpaid', 'Maternity', 'Others Paid', 'Others Unpaid'];

  reasons: string[] = [
    "Asistencia",
    "Calidad",
    "Conducta",
    "Cumplimiento",
    "Desempeño",
    "Fraude"
  ]

  motive_select: string[] = [];

  dp_motives: string[] = [
    '1*No adherirse a su horario por mas de 15% del tiempo total estipulado de trabajo.',
    '2*Abandonar el trabajo en horas de labor, sin causa justificada o sin licencia del patrono.',
    '3*Ingresar tarde.  A su hora de entrada. Todos los empleados deben estar en el trabajo a las horas asignadas segun su horario establecido en el reglamento interior de trabajo.',
    '4*En recesos y almuerzos todos los empleados deben estar a su hora puntual.  Quien se pase de su hora estara cometiendo una falta.',
    '5*Ausentarse sin causa justificada. El trabajador debera presentar  documentacion requerida antes de 48 horas.',
    '6*Cuando el trabajador deje de asistir al trabajo sin permiso del patrono, sin causa justificada, durante 2 días laborales completos y consecutivos o durante 6 medios dias laborales dentro de un mismo mes calendario.',
    '7*Cuando el trabajador deje de asistir a sus labores en un dia festivo (asueto nacional), habiendose comprometido hacerlo a traves del acuerdo firmado en su serie de contratos al iniciar labores, sin una excusa justificada.',
    '8*No atender al cliente concentrado, es decir no prestándole la atención debida o haciendo otra cosa distinta a la atención de la llamada',
    '9*Prolongar innecesariamente las llamadas para evitar recibir más de estas. ( utlizando HOLD, AFTERCALL, etc)',
    '10*Poner al cliente en espera o mute, de forma prolongada y deliberadamente para que el cliente cuelgue.',
    '11*No contestar la llamada en el momento en que ingresa',
    '12*Hablarle al cliente mal de los productos o servicios de la empresa o proporcionar mala informacion causando perjuicio a la empresa.',
    '13*Hablarle al cliente de manera irrespetuosa es decir gritandole o insultandolo de forma deliverada. ',
    '14*Insinuarle al cliente que está mintiendo',
    '15*Hacer comentarios sarcásticos al cliente',
    '16*Hacer comentarios negativos sobre algún grupo o actividad distintos al servicio prestado.',
    '17*Poner al cliente en mute, para gritar o insultarlo, sin que escuche.',
    '18*Insultar al cliente causando perjuicio al patrono.',
    '19*Incumplimiento del estandard de calidad de satisfaccion al cliente.',
    '20*Cuando un trabajador pida prestado dinero a los demás trabajadores.',
    '21*No desempeñar el servicio siguiendo las direcciones e indicaciones del patrono o su representante, bajo cuya autoridad está sujeto en lo concerniente al trabajo.',
    '22*Maltratar o destruir Equipo de Trabajo',
    '23*No observar buenas costumbres durante las horas de trabajo (por ej. Higiene Personal, utilizar vocabulario obsceno o vulgar, demostrar una actitud no profesional, así como cualquier otra cosa, que a criterio de la empresa sea ofensiva o que perturbe el ambiente de trabajo.',
    '24*Comer en su lugar de trabajo sin previa autorizacion de Gerencia',
    '25*Gritar en el piso, tener conducta revoltosa o inapropiada en el piso.',
    '26*Hacer durante el trabajo o dentro de la empresa, propaganda politica o contraria a las instrucciones democraticas creadas por la Constitucion o ejecutar cualquier acto que signifique coaccion de la libertad de la conciencia que la misma establece',
    '27*Promover la compra y/o venta de articulos, ropa, accesorios, venta de catalogos, etc. fuera de los canales proporcionados por la empresa, durante horas laborales del trabajador o sus compañeros.',
    '28*Cuando el trabajador al celebrar el contrato haya inducido en error al patrono, pretendiendo tener cualidades, condiciones o conocimientos que evidentemente no posee, o presentándole referencias o atestados personales cuya falsedad éste luego compruebe',
    '29*Uso del teléfono para llamadas personales, cuando estén expresamente prohibidas teniendo a un cliente en espera.',
    '30*Introducir artículos expresamente prohibidos en las cuentas que por seguridad en la información así se solicite (por ej. Lapiceros, papel, artículos electrónicos, radios, celulares, bolsos, maletines o cualquier otro similar)',
    '31*Dormir durante el tiempo efectivo de labores',
    '32*Uso de teléfonos celulares dentro de áreas donde hay prohibición expresa de utilizarlo.',
    '33*Uso inapropiado del correo electrónico  para asuntos personales',
    '34*Instalar programas o accesorios en el equipo, tener películas, MP3 o software no autorizado expresamente por la empresa , en la computadora o equipo asignado para realizar su trabajo.',
    '35*Incumplimiento de la política sobre uso de internet',
    '36*No portar el gafete en lugar visible ',
    '37*Prestar o transferir el gafete a otro empleado ',
    '38*Sujetar o bloquear puertas para permitir el acceso a personal que no porte gafete o a lugares que no están autorizados.',
    '39*Irrespetar la política de la empresa , acerca del uso del parqueo.',
    '40*Presentarse a laborar sin el gafete',
    '41*No respetar el código de vestuario de la empresa.',
    '42*Alejarse del cubículo sin autorización, mientras el teléfono se encuentra activado',
    '43*Utilizar los útiles y herramientas del patrono, para objeto distinto de áquel que están normalmente destinados. ',
    '44*Movilizar sin autorización cualquier tipo de activo o material perteneciente a Convergent, clientes u otros trabajadores.',
    '45*Remover marchamos (número de identificación) de los activos de la empresa',
    '46*Trabajar en estado de embriaguez o bajo la influencia de drogas, estupefacientes o en cualquier otra condicion anormal analoga',
    '47*Portar armas de cualquier clase, durante las horas de labor, excepto en los casos autorizados debidamente por las leyes y por la empresa, o cuando se trate de instrumentos punzocortantes que formaren parte de las herramientas o útiles propios del trabajo.',
    '48*Cuando el trabajador se conduzca durante sus labores de forma abiertamente inmoral, o acuda a la injuria, a la calumnia o a las vías de hecho contra su patrono.',
    '49*Cuando el trabajador cometa alguno de los actos enumerados en el inciso anterior , contra algún compañero, durante el tiempo que se ejecuten los trabajos, siempre que como consecuencia de ello se altere gravemente la disciplina y se interrumpan las labores.',
    '50*Cuando el trabajador, fuera del lugar donde se ejecutan las faenas y en horas que no sean de trabajo, acuda a la injuria o la calumnia o a las vías de hecho contra su patrono o contra los representantes de este en la dirección de las labores.',
    '51*Cuando el trabajador cometa algún delito o falta contra la propiedad en perjuicio del patrono o cuando cause intencionalmente un daño material en las máquinas, herramientas, materias primas, productos y demás objetos relacionados en forma inmediata.',
    '52*Cuando el trabajador comprometa con su imprudencia o descuido absolutamente inexcusable, la seguridad del lugar donde se realizan las labores o la de las personas que ahí se encuentran. (por ejemplo activar alarmas, desactivas o retirar dispositivos de seguridad)',
    '53*Cuando el trabajador sufra prisión por sentencia ejecutoriada.',
    '54*Proferir amenazas o tener un comportamiento violento en contra de sus compañeros de trabajo o clientes de la empresa.',
    '55*Cuando el trabajador incurra en cualquier otra falta grave a las obligaciones que le imponga el contrato de trabajo.',
    '56*Acoso sexual',
    '57*Acoso o intimidacion de diferentes indoles que no sean sexuales',
    '58*Insubordinacion o irrespeto a un superior',
    '59*Accesar o enviar material pornográfico u ofensivo',
    '60*No respetar los alimentos de los demás colaboradores',
    '61*Prestar o transferir el gafete a personas ajenas a la empresa',
    '62*Utilizar la planta telefónica para realizar llamadas personales en repetidas ocasiones y sin previa autorización.',
    '63*Revelar o transmitir información confidencial, como salarios o ciertos temas que fueron confiados a una persona en especial.',
    '64*Cuando el trabajador es sorprendido en acciones no autorizadas ( dormir, usar gorras, uso de celular, uso de cualquier tipo de sofware  ) por uno de los miembros de gerencia, se le reportara al supervisor inmediato del empleado para iniciar un proceso disciplinario.',
    '65*No cumplir con las metas e indicadores de desempeño establecidos mensualmente para cada una de las areas de trabajo.',
    '66*Tomar decisiones arbitrarias que afectan las labores de uno más colaboradores.',
    '67*No seguir las instrucciones o procedimientos que le fueron indicadas al colaborador.',
    '68*Colgarle a un cliente cuando se determina que fue porque utilizó lenguaje ofensivo, sin haberle advertido como minimo una vez.',
    '69*Colgarle a un cliente de manera deliberada',
    '70*Utilizar claves de acceso o códigos que no le han sido asignados',
    '71*Compartir las contraseñas personales con otro colaborador, poniendo en riesgo la informacion de empresa.',
    '72*Dejar caer llamadas, lo cual quiere decir no responder a las llamadas que llegan al teléfono, dejando que estas se corten, cuelguen o desconecten.',
    '73*Inconsistencia para reportar las llamadas recibidas y/o el tipo de delegacion de autoridad usado para resolver el problema del cliente en la base de datos correspondiente.',
    '74*No revisar, resolver y dar asesoramiento diario de las ordenes sin completar de los agentes. ',
    '75*No aplicar la matriz disciplinaria en aquellos casos que especifica la misma o hacer el uso adecuado de las sansiones previamente establecidas.',
    '76*Hablar idioma no autorizado en el piso.',
    '77*Cuando el trabajador se niegue de manera manifiesta y reiterada a adoptar las medidas preventivas o a seguir los procedimientos indicados para prevenir accidentes o enfermedades.',
    '78*Ponerse en código de actividad (por ejemplo AUX, ACW, etc) unos segundos, ocasional o frecuentemente durante su jornada, manipular la recepcion de llamadas, obstaculizar el monitoreo de llamadas, recibir menos llamadas y/o evitar recibir llamadas.',
    '79*Desconecta el dispositivo de observación / grabación ',
    '80*Colgarle a un cliente de manera accidental sin reportarlo a un superior. Si hay reincidencia se investigará el caso para determinar si la reincidencia es de forma deliberada.',
    '81*En los casos en que se de servicio por correo electrónico, o por escrito  las obligaciones y sanciones se regiran por lo establecido el Codigo de conducta y en el Acuerdo del uso de herramientas de tecnologia',
    '82*Cuando el Operador presenta un mal desempeño durante un periodo de un mes, y termina dentro del 5% del ranking de operadores que se destacan por tener mal desempeño. En un lapso de un mes deben mejorar sus estadisticas.',
    '83*Si el cliente al terminar la llamada, falla en colgar, que el agente no cuelgue con el fin de evadir llamadas.',
    '84*Conectarse a tomar llamadas fuera de su horario asignado con el fin de recuperar horas perdidas o generar tiempo extraordinario sin previa autorizacion.',
    '85*Proporcionar informacion sobre procesos, procedimientos, actividades, o resultados de la empresa, asi como asuntos administrativos reservados cuya divulgacion pueda causar perjuicio a la empresa.',
    '86*Proveer Informacion confidencial a personas ajenas a la cuenta de un cliente de SPRINT.  Ejemplo:  Revelar contraseñas, PIN, etc.',
    '87*Utilizar la funcion "Reveal" sin haber realizado el intento de ingresar la contraseña que haya indicado el cliente o bien sin que el cliente la haya indicado.',
    '88*Presentar documentacion falsa a Recursos Humanos para la validacion de ausencias.',
    '89*Presentar documentacion alterada a Recursos Humanos para la validacion de ausencias.',
    '90*Realizar reembolsos de pagos y procesarlos nuevamente sin la autorización del cliente o sin que el cliente comprenda expresamente dicha acción.',
    '91*Otorgar creditos y descuentos no autorizados a clientes,  sin autorizacion de su superior.'
  ]

  legal_foundament: string[] = [
    "Artículo 63, a*a) desempeñar el servicio contratado bajo la dirección del patrono o de su representante, a cuya autoridad quedan sujetos en todo lo concerniente al trabajo;",
    "Artículo 63, b*b) ejecutar el trabajo con la eficiencia, cuidado y esmero apropiados y en la forma, tiempo y lugar convenidos;",
    "Artículo 63, c*c) restituir al patrono los materiales no usados y conservar en buen estado los instrumentos y útiles que se les faciliten para el trabajo. Es entendido que no son responsables por el deterioro normal ni por el que se ocasione por caso fortuito, fuerza mayor, mala calidad o defectuosa construcción;",
    "Artículo 63, d*d) observar buenas costumbres durante el trabajo;",
    "Artículo 63, f*f) someterse a reconocimiento médico, sea al solicitar su ingreso al trabajo o durante éste, a solicitud del patrono, para comprobar que no padecen alguna incapacidad permanente o alguna enfermedad profesional, contagiosa o incurable; o petición del Instituto Guatemalteco de Seguridad Social, con cualquier motivo;",
    "Artículo 63, g*g) guardar los secretos técnicos, comerciales o de fabricación de los productos a cuya elaboración concurran directa o indirectamente, con tanta más fidelidad cuanto más alto sea el cargo del trabajador o la responsabilidad que tenga de guardarlos por razón de la ocupación que desempeña; así como los asuntos administrativos reservados, cuya divulgación pueda causar perjuicio a la empresa;",
    "Artículo 64, a*a) abandonar el trabajo en horas de labor sin causa justificada o sin licencia del patrono o de sus jefes inmediatos;",
    "Artículo 64, b*b) hacer durante el trabajo o dentro del establecimiento, propaganda política o contraria a las instituciones democráticas creadas por la Constitución, o ejecutar cualquier acto que signifique coacción de la libertad de conciencia que la misma establece;",
    "Artículo 64, c*c) trabajar en estado de embriaguez o bajo la influencia de drogas estupefacientes o en cualquier otra condición anormal análoga;",
    "Artículo 64, d*d) usar los útiles o herramientas suministrados por el patrono para objeto distinto de aquel a que estén normalmente destinados;",
    "Artículo 64, e*e) portar armas de cualquier clase durante las horas de labor o dentro del establecimiento, excepto en los casos especiales autorizados debidamente por las leyes, o cuando se trate de instrumentos cortantes o punzocortantes, que formen parte de las herramientas o útiles propios del trabajo; y",
    "Artículo 64, f*f) la ejecución de hechos o la violación de normas de trabajo, que constituyan actos manifiestos de sabotaje contra la producción normal de la empresa.",
    "Artículo 77, a*a) cuando el trabajador se conduzca durante sus labores en forma abiertamente inmoral o acuda a la injuria, la calumnia o a las vías de hecho contra su patrono o los representantes de éste en la dirección de las labores;",
    "Artículo 77, b*b) cuando el trabajador cometa alguno de los actos enumerados en el inciso anterior contra algún compañero de trabajo, durante el tiempo que se ejecuten las labores, siempre que como consecuencia de ello se altere gravemente la disciplina o se interrumpan las labores;",
    "Artículo 77, c*c) cuando el trabajador, fuera del lugar donde se ejecutan las labores y en horas que sean de trabajo, acuda a la injuria, a la calumnia o a las vías de hecho contra su patrono o contra los representantes de éste en la dirección de las labores, siempre que dichos actos no hayan sido provocados y que, como consecuencia de ellos, se haga imposible la convivencia y armonía para la realización del trabajo;",
    "Artículo 77, d*d) cuando el trabajador cometa algún delito o falta contra la propiedad en perjuicio del patrono, de alguno de sus compañeros de trabajo o en perjuicio de un tercero en el interior del establecimiento; asimismo cuando cause intencionalmente por descuido o negligencia, daño material en las máquinas, herramientas, materias primas, productos y demás objetos relacionados, en forma inmediata o indudable con el trabajo;",
    "Artículo 77, f*f) cuando el trabajador deje de asistir al trabajo sin permiso del patrono o sin causa justificada, durante dos días laborales completos y consecutivos o durante seis medios días laborables en un mismo mes calendario. ",
    "Artículo 77, g*g) cuando el trabajador se niegue de manera manifiesta a adoptar las medidas preventivas o a seguir los procedimientos, indicados para evitar accidentes o enfermedades; o cuando el trabajador se niegue en igual forma a acatar las normas o instrucciones que el patrono o sus representantes en la dirección de los trabajadores le indiquen con claridad para obtener la mayor eficacia y rendimiento en las labores;",
    "Artículo 77, h*h) cuando infrinja cualquiera de las prohibiciones del artículo 64 o del reglamento interior de trabajo debidamente aprobado, después de que el patrono lo aperciba una vez por escrito.",
    "Artículo 77, i*i) cuando el trabajador, al celebrar el contrato haya inducido en error al patrono, pretendiendo tener cualidades, condiciones o conocimientos que evidentemente no posee, o presentándole referencias o atestados personales cuya falsedad éste luego compruebe, o ejecutando su trabajo en forma que demuestre claramente su incapacidad en la realización de las labores para la cuales haya sido contratado;",
    "Artículo 77, j*j) cuando el trabajador sufra pena de arresto mayor o se le imponga prisión correccional, por sentencia ejecutoriada;"

  ]

  constructor(private apiService: ApiService, private route: ActivatedRoute, public authUser: AuthServiceService) { }

  ngOnInit() {
    this.todayDate = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");
    this.profile[0].idprofiles = this.route.snapshot.paramMap.get('id')
    this.apiService.getProfile(this.profile[0]).subscribe((prof: profiles[]) => {
      this.profile = prof;
    });

    this.apiService.getApprovers().subscribe((usrs: users[]) => {
      this.approvals = usrs;
    });

    this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
      this.workingEmployee = emp;
      this.profile[0].date_joining = emp.hiring_date;
      this.activeEmp = emp.idemployees;
      this.accId = emp.account;
      this.vacationsEarned = (new Date(this.todayDate).getMonth() - new Date(this.profile[0].date_joining).getMonth() + ((new Date(this.todayDate).getFullYear() - new Date(this.profile[0].date_joining).getFullYear()) * 12));
      this.getVacations();
      this.getAllaccounts();
    })



    this.getAttendences(this.todayDate);
    this.attAdjudjment.id_user = this.authUser.getAuthusr().iduser;
    this.attAdjudjment.date = this.todayDate;
    this.attAdjudjment.state = 'PENDING';
    this.attAdjudjment.status = 'PENDING';
    this.editAdj = false;

    this.vacationAdd = false;
    this.activeVacation.date = this.todayDate;
    this.activeVacation.id_department = this.authUser.getAuthusr().department;
    this.activeVacation.id_employee = this.route.snapshot.paramMap.get('id');
    this.activeVacation.id_user = this.authUser.getAuthusr().iduser;
    this.activeVacation.status = 'PENDING';

    this.newRequest = false;
    this.editRequest = true;
    this.getLeaves();

    this.getDisciplinaryProcesses();
    this.getStaffes();

    this.newAudience = "NO";
    this.newSuspension = "NO";

    this.getInsurances();
    this.getTemplates();
    this.getProcessesrecorded();

  }

  getStaffes() {
    this.apiService.getStaffPeople().subscribe((usr: users[]) => {
      this.staffes = usr;
    })
  }

  getDisciplinaryProcesses() {
    this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
      this.activeEmp = emp.idemployees;
    })
    this.apiService.getDisciplinaryProcesses({ id: this.route.snapshot.paramMap.get('id') }).subscribe((dp: disciplinary_processes[]) => {
      this.discilplinary_processes = dp;
    })
  }

  getAttendences(dt: string) {
    this.apiService.getAttendences({ id: this.route.snapshot.paramMap.get('id'), date: "<= '" + dt + "'" }).subscribe((att: attendences[]) => {
      this.showAttendences = att;
      this.showAttendences.forEach(att => {
        att.balance = (Number.parseFloat(att.worked_time) - Number.parseFloat(att.scheduled)).toString();
      })
      this.getAttAdjustemt();
    });
    this.editAdj = false;
  }

  getAttAdjustemt() {
    this.editAdj = false;
    this.apiService.getAttAdjustments({ id: this.activeEmp }).subscribe((adj: attendences_adjustment[]) => {
      this.showAttAdjustments = adj;
    })
  }

  addJustification(att: attendences) {
    this.editAdj = false;
    this.attAdjudjment.time_before = att.worked_time;
    this.attAdjudjment.id_attendence = att.idattendences;
    this.attAdjudjment.id_type = '2';
    this.attAdjudjment.id_employee = att.id_employee;
    this.attAdjudjment.id_department = this.authUser.getAuthusr().department;
    this.attAdjudjment.id_user = this.authUser.getAuthusr().iduser;
    this.addJ = true;
  }

  insertAdjustment() {
    this.apiService.insertAttJustification(this.attAdjudjment).subscribe((str: string) => {
      this.getAttendences(this.todayDate);
      this.cancelView();
    });
  }

  getRecordAdjustment(id_justification: string) {
    this.apiService.getAttAdjustment({ justify: id_justification }).subscribe((requested: attendences_adjustment) => {
      this.attAdjudjment = requested;
    })
    this.addJ = true;
    this.editAdj = true;
  }

  cancelAdjustment() {
    this.addJ = false;
  }

  getVacations() {
    this.earnVacations = this.vacationsEarned * 1.25;
    this.tookVacations = 0;
    this.availableVacations = 0;
    this.apiService.getVacations({ id: this.route.snapshot.paramMap.get('id') }).subscribe((res: vacations[]) => {
      this.showVacations = res;
      res.forEach(vac => {
        if (vac.action == "Add") {
          this.earnVacations = this.earnVacations + parseFloat(vac.count);
        }
        if (vac.action == "Take") {
          this.tookVacations = this.tookVacations + parseFloat(vac.count);
        }
      })
      this.availableVacations = this.earnVacations - this.tookVacations;
    })
  }

  addVacation(action: string, type: string) {
    this.vacationAdd = true;
    this.editVac = true;
    this.activeVacation = new vacations;
    this.activeVacation.date = this.todayDate;
    this.activeVacation.id_department = this.authUser.getAuthusr().department;
    this.activeVacation.id_employee = this.route.snapshot.paramMap.get('id');
    this.activeVacation.id_user = this.authUser.getAuthusr().iduser;
    this.activeVacation.status = 'PENDING';
    this.activeVacation.count = '1';
    this.activeVacation.action = action;
    this.activeVacation.id_type = type;
  }

  pushVacationDate(str: string) {
    this.activeVacation.took_date = str;
  }

  cancelVacation() {
    this.vacationAdd = false;
    this.editVac = true;
  }

  insertVacation() {
    this.apiService.insertVacations(this.activeVacation).subscribe((str: any) => {
      this.getVacations();
    })
    this.vacationAdd = false;
  }

  getVacation(vac: vacations) {
    this.activeVacation = vac;
    this.vacationAdd = true;
    this.editVac = false;
    this.editLeave = false;
  }

  getLeaves() {
    this.apiService.getLeaves({ id: this.route.snapshot.paramMap.get('id') }).subscribe((leaves: leaves[]) => {
      this.leaves = leaves;
    })
  }

  cancelView() {
    this.addJ = false;
    this.addBeneficiary = false;
    this.editRequest = true;
    this.vacationAdd = false;
    this.editVac = false;
    this.editLeave = false;
    this.showLeave = false;
    this.setNewRequest = false;
    this.reasonRequiered = false;
    this.storedRequest = false;
    this.newAudience = "NO";
    this.newSuspension = "NO";
    this.getVacations();
    this.getLeaves();
    this.getBeneficiaries();
    this.modifyInsurance = false;
    this.newProcess = false;
    this.addProc = false;
    this.actuallProc = new process;
    this.viewRecProd = false;
    this.getProcessesrecorded();
    this.actualTerm = new terminations;
    this.actualReport = new reports;
    this.actuallProc = new process;
  }

  setLeave() {
    this.activeLeave.date = this.todayDate;
    this.activeLeave.id_department = '5';
    this.activeLeave.id_employee = this.activeEmp;
    this.activeLeave.id_type = '5';
    this.activeLeave.id_user = this.authUser.getAuthusr().iduser;
    this.activeLeave.status = 'PENDING';
    this.vacationAdd = false;
    this.editLeave = true;
    this.showLeave = false;
  }

  insertLeave() {
    this.apiService.insertLeaves(this.activeLeave).subscribe((str: string) => {
      this.getLeaves();
      this.cancelView();
    })
  }

  selectLeave(leave: leaves) {
    this.activeLeave = leave;
    this.editLeave = true;
    this.showLeave = true;
  }

  setRequest() {
    this.editRequest = true;
    this.activeRequest = new disciplinary_processes;
    this.activeRequest.day_1 = "null";
    this.activeRequest.day_2 = "null";
    this.activeRequest.day_3 = "null";
    this.activeRequest.day_4 = "null";
    this.activeRequest.id_user = this.authUser.getAuthusr().iduser;
    this.activeRequest.id_employee = this.activeEmp;
    this.activeRequest.id_type = "6";
    this.activeRequest.id_department = "5";
    this.activeRequest.status = "PENDING";
    this.activeRequest.date = this.todayDate;
    this.activeRequest.audience_status = 'PENDING';
    this.storedRequest = false;
    this.setNewRequest = true;
    this.newRequest = true;
  }

  pushRequestDate(str: string) {
    this.activeRequest.imposition_date = str;
  }

  reasonChange() {
    this.motive_select = [];
    this.activeRequest.motive = null;
    this.activeRequest.legal_foundament = null;

    switch (this.activeRequest.cathegory.split("*")[0]) {
      case "Asistencia":
        for (let i = 0; i < 7; i++) {
          this.motive_select.push(this.dp_motives[i]);
        }
        break;
      case "Calidad":
        for (let i = 7; i < 20; i++) {
          this.motive_select.push(this.dp_motives[i]);
        }
        break;
      case "Conducta":
        for (let i = 20; i < 64; i++) {
          this.motive_select.push(this.dp_motives[i]);
        }
        break;
      case "Cumplimiento":
        for (let i = 64; i < 84; i++) {
          this.motive_select.push(this.dp_motives[i]);
        }
        break;
      case "Fraude":
        for (let i = 84; i < 91; i++) {
          this.motive_select.push(this.dp_motives[i]);
        }
        break;
    }
    this.reasonRequiered = true;
  }

  motiveChange() {
    this.activeRequest.legal_foundament = null;

    if (this.activeRequest.motive.split("*")[0] == "21" || this.activeRequest.motive.split("*")[0] == "55" || this.activeRequest.motive.split("*")[0] == "58" || this.activeRequest.motive.split("*")[0] == "73" || this.activeRequest.motive.split("*")[0] == "74" || this.activeRequest.motive.split("*")[0] == "75") {
      this.activeRequest.legal_foundament = this.legal_foundament[0].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "1" || this.activeRequest.motive.split("*")[0] == "3" || this.activeRequest.motive.split("*")[0] == "4" || this.activeRequest.motive.split("*")[0] == "5" || this.activeRequest.motive.split("*")[0] == "7" || this.activeRequest.motive.split("*")[0] == "8" || this.activeRequest.motive.split("*")[0] == "9" || this.activeRequest.motive.split("*")[0] == "10" || this.activeRequest.motive.split("*")[0] == "11" || this.activeRequest.motive.split("*")[0] == "13" || this.activeRequest.motive.split("*")[0] == "14" || this.activeRequest.motive.split("*")[0] == "15" || this.activeRequest.motive.split("*")[0] == "16" || this.activeRequest.motive.split("*")[0] == "17" || this.activeRequest.motive.split("*")[0] == "19" || this.activeRequest.motive.split("*")[0] == "24" || this.activeRequest.motive.split("*")[0] == "25" || this.activeRequest.motive.split("*")[0] == "27" || this.activeRequest.motive.split("*")[0] == "31" || this.activeRequest.motive.split("*")[0] == "36" || this.activeRequest.motive.split("*")[0] == "37" || this.activeRequest.motive.split("*")[0] == "42" || this.activeRequest.motive.split("*")[0] == "61" || this.activeRequest.motive.split("*")[0] == "62" || this.activeRequest.motive.split("*")[0] == "64" || this.activeRequest.motive.split("*")[0] == "65" || this.activeRequest.motive.split("*")[0] == "67" || this.activeRequest.motive.split("*")[0] == "68" || this.activeRequest.motive.split("*")[0] == "69" || this.activeRequest.motive.split("*")[0] == "72" || this.activeRequest.motive.split("*")[0] == "76" || this.activeRequest.motive.split("*")[0] == "78" || this.activeRequest.motive.split("*")[0] == "79" || this.activeRequest.motive.split("*")[0] == "80" || this.activeRequest.motive.split("*")[0] == "81" || this.activeRequest.motive.split("*")[0] == "82" || this.activeRequest.motive.split("*")[0] == "83") {
      this.activeRequest.legal_foundament = this.legal_foundament[1].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "22" || this.activeRequest.motive.split("*")[0] == "32" || this.activeRequest.motive.split("*")[0] == "38" || this.activeRequest.motive.split("*")[0] == "39" || this.activeRequest.motive.split("*")[0] == "45") {
      this.activeRequest.legal_foundament = this.legal_foundament[2].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "23" || this.activeRequest.motive.split("*")[0] == "41") {
      this.activeRequest.legal_foundament = this.legal_foundament[3].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "86") {
      this.activeRequest.legal_foundament = this.legal_foundament[5].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "2") {
      this.activeRequest.legal_foundament = this.legal_foundament[6].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "26") {
      this.activeRequest.legal_foundament = this.legal_foundament[7].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "29" || this.activeRequest.motive.split("*")[0] == "33" || this.activeRequest.motive.split("*")[0] == "34" || this.activeRequest.motive.split("*")[0] == "35" || this.activeRequest.motive.split("*")[0] == "43" || this.activeRequest.motive.split("*")[0] == "44" || this.activeRequest.motive.split("*")[0] == "59" || this.activeRequest.motive.split("*")[0] == "70" || this.activeRequest.motive.split("*")[0] == "71") {
      this.activeRequest.legal_foundament = this.legal_foundament[9].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "47") {
      this.activeRequest.legal_foundament = this.legal_foundament[10].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "20" || this.activeRequest.motive.split("*")[0] == "30" || this.activeRequest.motive.split("*")[0] == "40" || this.activeRequest.motive.split("*")[0] == "60" || this.activeRequest.motive.split("*")[0] == "63" || this.activeRequest.motive.split("*")[0] == "66" || this.activeRequest.motive.split("*")[0] == "84" || this.activeRequest.motive.split("*")[0] == "87" || this.activeRequest.motive.split("*")[0] == "88" || this.activeRequest.motive.split("*")[0] == "89" || this.activeRequest.motive.split("*")[0] == "90" || this.activeRequest.motive.split("*")[0] == "91") {
      this.activeRequest.legal_foundament = this.legal_foundament[11].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "48") {
      this.activeRequest.legal_foundament = this.legal_foundament[12].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "49" || this.activeRequest.motive.split("*")[0] == "12") {
      this.activeRequest.legal_foundament = this.legal_foundament[13].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "50" || this.activeRequest.motive.split("*")[0] == "54") {
      this.activeRequest.legal_foundament = this.legal_foundament[14].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "51" || this.activeRequest.motive.split("*")[0] == "56" || this.activeRequest.motive.split("*")[0] == "57") {
      this.activeRequest.legal_foundament = this.legal_foundament[15].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "6") {
      this.activeRequest.legal_foundament = this.legal_foundament[16].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "52" || this.activeRequest.motive.split("*")[0] == "77") {
      this.activeRequest.legal_foundament = this.legal_foundament[17].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "28") {
      this.activeRequest.legal_foundament = this.legal_foundament[19].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "53") {
      this.activeRequest.legal_foundament = this.legal_foundament[20].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "18") {
      this.activeRequest.legal_foundament = this.legal_foundament[1].split("*")[0] + ", " + this.legal_foundament[15].split("*")[0];
    }
    if (this.activeRequest.motive.split("*")[0] == "85") {
      this.activeRequest.legal_foundament = this.legal_foundament[5].split("*")[0] + ", 77, e";
    }
  }

  insertDPRequest() {
    this.apiService.insertDisciplinary_Request(this.activeRequest).subscribe((str: string) => {
      this.getDisciplinaryProcesses();
      this.cancelView();
    })
  }

  showDp(dp: disciplinary_processes) {
    if (dp.status == 'DISPATCHED') {
      this.editRequest = false;
    } else {
      if (dp.status == 'PENDING') {
        this.editRequest = true;
        this.newAudience = 'NO';
        this.newSuspension = 'NO';
      }
    }
    this.newRequest = false;
    this.activeRequest = dp;
    this.storedRequest = true
  }

  pushAudienceDate(dt: any) {
    this.activeRequest.audience_date = dt;
  }

  pushAudienceTime(tm: any) {
    this.activeRequest.time = tm;
  }

  addDP() {
    this.activeRequest.audience_status = "SCHEDULED";
    this.activeRequest.status = "DISPATCHED";
    if (this.newAudience === "YES" && this.newSuspension === "NO") {
      this.apiService.insertDPA(this.activeRequest).subscribe((str: string) => {
        this.getDisciplinaryProcesses();
      })
    }
    if (this.newSuspension === "YES" && this.newAudience === "NO") {
      this.apiService.insertDPS(this.activeRequest).subscribe((str: string) => {
        this.getDisciplinaryProcesses();
      })
    }
    if (this.newSuspension === "YES" && this.newAudience === "YES") {
      this.apiService.insertDPSA(this.activeRequest).subscribe((str: string) => {
        this.getDisciplinaryProcesses();
      })
    }
    if (this.newSuspension === "NO" && this.newAudience === "NO") {
      this.apiService.insertDP(this.activeRequest).subscribe((str: string) => {
        this.getDisciplinaryProcesses();
        if (this.activeRequest.dp_grade == 'Terminacion Laboral') {
          this.newRequest = false;
          this.storedRequest = false;
          let act: process = new process;
          act.idprocesses = "8";
          act.name = 'Termination';
          act.descritpion = 'Employee Termination';
          this.actualTerm.kind = 'Despido';
          this.actualTerm.motive = 'Justificado';
          this.actualTerm.reason = 'Completar Proceso Disiciplinario';
          this.actualTerm.rehireable = 'NO REHIREABLE';
          this.actualTerm.valid_from = this.activeRequest.imposition_date;
          this.dpTerm = true;
          this.setProcess(act);
        }
      })
    }
    this.newRequest = false;
    this.storedRequest = false;
  }

  pushDay1(str: any) {
    this.activeRequest.day_1 = "'" + str + "'";
  }

  pushDay2(str: any) {
    this.activeRequest.day_2 = "'" + str + "'";
  }

  pushDay3(str: any) {
    this.activeRequest.day_3 = "'" + str + "'";
  }

  pushDay4(str: any) {
    this.activeRequest.day_4 = "'" + str + "'";
  }

  getBeneficiaries() {
    this.apiService.getBeneficiaries({ id: this.idInsurance }).subscribe((res: beneficiaries[]) => {
      this.beneficiaries = res;
    });
  }

  getInsurances() {
    this.apiService.getInsurances({ id: this.route.snapshot.paramMap.get('id') }).subscribe((ins: insurances) => {
      this.insurances = ins;
      if (isNullOrUndefined(this.insurances.id_process)) {
        this.insuranceNull = false;
      } else {
        this.idInsurance = ins.idinsurances;
        this.getBeneficiaries();
        this.insuranceNull = true;
      }
    });
  }

  insertInsurance() {
    this.insurances = new insurances;
    this.insurances.id_user = this.authUser.getAuthusr().user_name;
    this.insurances.date = this.todayDate;
    this.insurances.status = "PENDING";
    this.insurances.in_status = "PENDING";
    this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((txt: employees) => {
      this.insurances.id_employee = txt.idemployees;
    })
    this.newInsurance = true;
  }

  insertInsurances() {
    this.insurances.id_user = this.authUser.getAuthusr().iduser;
    this.insurances.id_type = '7';
    this.insurances.id_department = '5';
    this.insurances.place = "Guatemala";
    this.apiService.insertInsurances(this.insurances).subscribe((str: string) => {
      this.getInsurances();
    })
  }

  activeModify() {
    this.modifyInsurance = true;
  }

  insertBeneficiary() {
    if (isNullOrUndefined(this.beneficiaries)) {
      this.beneficiaries = [new beneficiaries];
    } else {
      this.beneficiaries.push(new beneficiaries);
    }
    this.addBeneficiary = true;
  }

  saveInsurance() {
    this.apiService.updateInsurance(this.insurances).subscribe((str: string) => {
      this.getInsurances();
      this.cancelView();
    })
  }

  saveBeneficiary() {
    this.beneficiaries[this.beneficiaries.length - 1].idbeneficiaries = this.insurances.idinsurances;
    this.beneficiaries[this.beneficiaries.length - 1].first_name = this.beneficiaryName.split(" ")[0];
    this.beneficiaries[this.beneficiaries.length - 1].second_name = this.beneficiaryName.split(" ")[1];
    this.beneficiaries[this.beneficiaries.length - 1].first_lastname = this.beneficiaryName.split(" ")[2];
    this.beneficiaries[this.beneficiaries.length - 1].second_lastname = this.beneficiaryName.split(" ")[3];
    this.addBeneficiary = false;
    this.apiService.insertBeneficiaryes(this.beneficiaries[this.beneficiaries.length - 1]).subscribe((str: string) => {
      this.getBeneficiaries();
    });
  }

  getTemplates() {
    this.apiService.getTemplates().subscribe((prs: process[]) => {
      this.process_templates = prs;
    });
  }

  addProcess() {
    this.newProcess = !this.newProcess;
    if (this.newProcess == false) {
      this.cancelView();
    }
  }

  setProcess(act: process) {
    this.newRequest = false;
    this.storedRequest = false;
    this.viewRecProd = false;
    this.actuallProc.descritpion = null;
    this.addProc = true;
    this.actuallProc = act;
    this.actuallProc.prc_date = this.todayDate;
    this.actuallProc.status = "CLOSED";
    this.actuallProc.user_name = this.authUser.getAuthusr().user_name;
    this.actuallProc.id_user = this.authUser.getAuthusr().iduser;
    this.actuallProc.id_profile = this.activeEmp;
    switch (this.actuallProc.name) {
      case 'Supervisor Survey':
        this.actuallProc.descritpion = null;
        break;
      case 'Pay Vacations':
        if (this.availableVacations < 1) {
          this.addVac = false;
          this.actuallProc.idprocesses = '1';
        } else {
          this.addVac = true;
        }
        this.actuallProc.descritpion = null;
        break;
      case 'Termination':
        this.actualTerm.access_card = "YES";
        this.actualTerm.headsets = "YES";
        this.actualTerm.nearsol_experience = '0';
        this.actualTerm.supervisor_experience = '0';
        break;
      case 'Transfer':
        this.accChange = this.accId;
        break;
      default:
        break;
    }
  }

  setCheckBanck(str: string) {
    this.actualTerm.bank_check = str;
  }

  setFromDate(str: string) {
    this.checkDate1 = str;
  }

  setToDate(str: string) {
    this.checkDate2 = str;
  }

  setValidFrom(str: string) {
    this.actualTerm.valid_from = str;
  }

  insertProc() {
    this.apiService.insertProc(this.actuallProc).subscribe((str: string) => {
      switch (this.actuallProc.name) {
        case 'Termination':
          this.actualTerm.id_process = str;
          this.actualTerm.period_to_pay = this.checkDate1 + " - " + this.checkDate2 + " Days:" + this.checkDay;
          this.apiService.insertTerm(this.actualTerm).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Report':
          this.actualReport.id_process = str;
          this.apiService.insertReport(this.actualReport).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Advance':
          this.actualAdvance.id_process = str;
          this.apiService.insertAdvances(this.actualAdvance).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Rise':
          this.actualRise.id_process = str;
          this.actualRise.id_employee = this.actuallProc.id_profile;
          this.workingEmployee.productivity_payment = (parseFloat(this.workingEmployee.productivity_payment) + parseFloat(this.riseIncrease)).toString();
          this.apiService.insertRise(this.actualRise).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Call Tracker':
          this.actualCallTracker.id_process = str;
          this.apiService.insertCallTracker(this.actualCallTracker).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Letter':
          this.actualLetters.id_process = str;
          this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
            this.actualLetters.base_salary = emp.base_payment;
            this.actualLetters.productivity_salary = emp.productivity_payment;
          })
          this.apiService.insertLetters(this.actualLetters).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Pay Vacations':
          let cnt:number = 0;
          for (let i = 0; i < (parseFloat(this.actuallProc.idprocesses) - 1); i++) {
            this.apiService.getPeriods().subscribe((periods: periods[]) => {
              this.apiService.getPayments(periods[periods.length - 1]).subscribe((payment: payments[]) => {
                this.addVacation("Take", "4");
                this.activeVacation.id_employee = this.route.snapshot.paramMap.get('id');
                this.activeVacation.id_user = this.authUser.getAuthusr().iduser;
                this.activeVacation.id_department = this.workingEmployee.account;
                this.activeVacation.date = this.todayDate;
                this.activeVacation.notes = this.actuallProc.descritpion;
                this.activeVacation.took_date = this.todayDate;
                this.insertVacation();
                let cred: credits = new credits;
                cred.amount = ((parseFloat(this.workingEmployee.base_payment) / 30) + (parseFloat(this.workingEmployee.productivity_payment) / 30)).toFixed(2);
                cred.id_employee = this.workingEmployee.idemployees;
                cred.id_user = this.authUser.getAuthusr().iduser;
                cred.date = this.todayDate;
                payment.forEach(py => {
                  if(py.id_employee == this.workingEmployee.idemployees){
                    cred.idpayments = py.idpayments;
                  }
                });
                cred.notes = 'Vacations Payed';
                cred.type = 'HR Vacations Payed';
                this.apiService.insertPushedCredit(cred).subscribe((str:string)=>{
                  this.apiService.insertCredits(cred).subscribe((str:string)=>{
                    cnt = cnt + 1;
                    if(cnt == (parseFloat(this.actuallProc.idprocesses) - 1)){
                      this.cancelView();
                    }
                  })
                })
              })
            })
          }
          break;
        case 'Supervisor Survey':
          this.actualSurvey.id_process = str; 2
          this.apiService.insertSurvey(this.actualSurvey).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Transfer':
          this.apiService.insertTransfer({ employee: this.activeEmp, account: this.accId }).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Legal Discount':
          this.actualJudicial.id_process = str;
          this.apiService.insertJudicials(this.actualJudicial).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Irtra Request':
          this.actualIrtrarequests.idprocess = str;
          this.apiService.insertIrtra_request(this.actualIrtrarequests).subscribe((str: string) => {
            this.getIrtra();
            this.cancelView();
          })
          break;
        case 'Messaging':
          this.actualMessagings.idprocess = str;
          this.apiService.insertMessagings(this.actualMessagings).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        default:
          break;
      }
    })
  }

  getProcessesrecorded() {
    this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
      this.apiService.getProcRecorded({ id: emp.idemployees }).subscribe((prc: process[]) => {
        this.processRecord = prc;
      })
    })
  }

  viewProcess(pr: process) {
    this.viewRecProd = true;
    this.actuallProc = pr;
    switch (this.actuallProc.name) {
      case 'Messaging':
        this.apiService.getMessagings(this.actuallProc).subscribe((msg: messagings) => {
          this.actualMessagings = msg;
        })
        break;
      case 'Termination':
        this.apiService.getTerm(this.actuallProc).subscribe((trm: terminations) => {
          this.actualTerm = trm;
        })
        break;
      case 'Report':
        this.apiService.getRerpot(this.actuallProc).subscribe((rpr: reports) => {
          this.actualReport = rpr;
        })
        break;
      case 'Advance':
        this.apiService.getAdvances(this.actuallProc).subscribe((adv: advances) => {
          this.actualAdvance = adv;
        })
        break;
      case 'Rise':
        this.apiService.getRises(this.actuallProc).subscribe((rs: rises) => {
          this.actualRise = rs;
        })
        break;
      case 'Call Tracker':
        this.apiService.getCallTracker(this.actuallProc).subscribe((cl: call_tracker) => {
          this.actualCallTracker = cl;
        })
        break;
      case 'Letter':
        this.apiService.getLetters(this.actuallProc).subscribe((lt: letters) => {
          this.actualLetters = lt;
        })
        break;
      case 'Supervisor Survey':
        this.apiService.getSurvey(this.actuallProc).subscribe((srv: supervisor_survey) => {
          this.actualSurvey = srv;
        })
        break;
      case 'Legal Discount':
        this.apiService.getJudicials(this.actuallProc).subscribe((jdc: judicials) => {
          this.actualJudicial = jdc;
        })
        break;
      case 'Irtra Request':
        this.apiService.getIrtra_request(this.actuallProc).subscribe((irt: irtra_requests) => {
          this.actualIrtrarequests = irt;
        })
        break;
      default:
        break;
    }
  }

  setApprovalDate(str: string) {
    this.actualRise.approved_date = str;
  }

  setEffectiveDate(str: string) {
    this.actualRise.effective_date = str;
  }

  setTrialStart(str: string) {
    this.actualRise.trial_start = str;
  }

  setTrialEnd(str: string) {
    this.actualRise.trial_end = str;
  }

  setLetterDate(str: string) {
    this.actualLetters.emition_date = str;

  }

  getLetter() {
    var numbers = [
      "Uno",
      "Dos",
      "Tres",
      "Cuatro",
      "Cinco",
      "Seis",
      "Siete",
      "Ocho",
      "Nueve",
      "Diez",
      "Once",
      "Doce",
      "Trece",
      "Catroce",
      "Quince",
      "Dieciseis",
      "Diecisiete",
      "Dieciocho",
      "Diecinueve",
      "Veinte",
      "Veintiuno",
      "Veintidos",
      "Veintitres",
      "Veinticuatro",
      "Veinticinco",
      "Veintiseis",
      "Veintisiete",
      "Veintiocho",
      "Veintinueve",
      "Treinta",
      "Treinta y uno"
    ]
    var month = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre"
    ]
    var year = [
      "Dos mil veinte",
      "Dos mil veintiuno",
      "Dos mil veintidos",
      "Dos mil veintitres"
    ]
    var numbers = [
      "Uno",
      "Dos",
      "Tres",
      "Cuatro",
      "Cinco",
      "Seis",
      "Siete",
      "Ocho",
      "Nueve",
      "Diez",
      "Once",
      "Doce",
      "Trece",
      "Catroce",
      "Quince",
      "Dieciseis",
      "Diecisiete",
      "Dieciocho",
      "Diecinueve",
      "Veinte",
      "Veintiuno",
      "Veintidos",
      "Veintitres",
      "Veinticuatro",
      "Veinticinco",
      "Veintiseis",
      "Veintisiete",
      "Veintiocho",
      "Veintinueve",
      "Treinta",
      "Treinta y uno"
    ]
    var month = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre"
    ]
    var year = [
      "Dos mil veinte",
      "Dos mil veintiuno",
      "Dos mil veintidos",
      "Dos mil veintitres"
    ]

    var e_date;

    var url = "";
    if (this.actualLetters.type == 'Laboral') {
      this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
        var dt = numbers[parseInt(emp.hiring_date.split("-")[2]) - 1] + " de " + month[parseInt(emp.hiring_date.split("-")[1]) - 1] + " de; " + parseInt(emp.hiring_date.split("-")[0]);
        e_date = numbers[parseInt(this.actualLetters.emition_date.split("-")[2]) - 1] + " de " + month[parseInt(this.actualLetters.emition_date.split("-")[1]) - 1] + " de " + year[parseInt(this.actualLetters.emition_date.split("-")[0]) - 2020];
        url = "http://200.94.251.67/phpscripts/letterLaboral.php?date=" + e_date + "&name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&puesto=" + emp.job + "&departamento=" + emp.id_account + "/" + this.actualLetters.company + "&start=" + dt + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&iduser=" + this.authUser.getAuthusr().iduser;
        window.open(url, "_blank");
      })
    }
    if (this.actualLetters.type == 'INTECAP') {
      this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
        e_date = "Guatemela, " + this.actualLetters.emition_date.split("-")[2] + " de " + month[parseInt(this.actualLetters.emition_date.split("-")[1])] + " del " + year[parseInt(this.actualLetters.emition_date.split("-")[0]) - 2020];
        var dt = numbers[parseInt(emp.hiring_date.split("-")[2]) - 1] + " de " + month[parseInt(emp.hiring_date.split("-")[1]) - 1] + " de; " + parseInt(emp.hiring_date.split("-")[0]);
        var afiliacion = this.profile[0].iggs;
        var patrono = '145998';
        url = "http://200.94.251.67/phpscripts/letterIntecap.php?date=" + e_date + "&name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&id=" + this.profile[0].dpi + "&company=" + this.actualLetters.company + "&hiring=" + dt + "&afiliacion=" + afiliacion + "&patronal=" + patrono + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&iduser=" + this.authUser.getAuthusr().iduser;
        window.open(url, "_blank");
      })
    }
    if (this.actualLetters.type == 'Ingresos') {
      this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
        var dt = numbers[parseInt(emp.hiring_date.split("-")[2]) - 1] + " de " + month[parseInt(emp.hiring_date.split("-")[1]) - 1] + " de; " + parseInt(emp.hiring_date.split("-")[0]);
        e_date = numbers[parseInt(this.actualLetters.emition_date.split("-")[2]) - 1] + " de " + month[parseInt(this.actualLetters.emition_date.split("-")[1]) - 1] + " de " + year[parseInt(this.actualLetters.emition_date.split("-")[0]) - 2020];
        var prod = parseFloat(emp.productivity_payment) - 250;
        var total = parseFloat(emp.base_payment) + parseFloat(emp.productivity_payment);
        url = "http://200.94.251.67/phpscripts/letterIngresos.php?name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&position=" + emp.job + "&department=" + emp.id_account + "/" + this.actualLetters.company + "&hire=" + dt + "&base=" + emp.base_payment + "&productivity=" + prod + "&total=" + total + "&date=" + e_date + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&iduser=" + this.authUser.getAuthusr().iduser;
        window.open(url, "_blank");
      })
    }
    if (this.actualLetters.type == 'Baja') {
      this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
        var trem = "N/A";
        this.apiService.getTermdt(emp).subscribe((term: terminations) => {
          var str = term.valid_from;
          trem = numbers[parseInt(str.split("-")[2]) - 1] + " de " + month[parseInt(str.split("-")[1]) - 1] + " de " + year[parseInt(str.split("-")[0]) - 2020];
          var dt = numbers[parseInt(emp.hiring_date.split("-")[2]) - 1] + " de " + month[parseInt(emp.hiring_date.split("-")[1]) - 1] + " de " + parseInt(emp.hiring_date.split("-")[0]);
          e_date = numbers[parseInt(this.actualLetters.emition_date.split("-")[2]) - 1] + " de " + month[parseInt(this.actualLetters.emition_date.split("-")[1]) - 1] + " de " + year[parseInt(this.actualLetters.emition_date.split("-")[0]) - 2020];
          var url = "http://200.94.251.67/phpscripts/letterBaja.php?name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&position=" + emp.job + "&department=" + emp.id_account + "/" + this.actualLetters.company + "&hire=" + dt + "&date=" + e_date + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&id=" + emp.idemployees + "&term=" + trem + "&iduser=" + this.authUser.getAuthusr().iduser;
          window.open(url, "_blank");
        })
      })
    }
  }

  setApproveddate(str: string) {
    this.actualSurvey.approved_date = str;
  }

  setNotificationdate(str: string) {
    this.actualSurvey.notification_date = str;
  }

  showAlert(str: string) {
    alert(str);
  }

  getAllaccounts() {
    this.apiService.getfilteredAccounts({ id: this.accId }).subscribe((ac: accounts[]) => {
      this.allAccounts = ac;
    })
  }

  addDescription() {
    this.actuallProc.descritpion = this.accId;
  }

  getIrtra() {
    let m: string;
    let f: string;
    let married: string;

    if (this.profile[0].gender == 'M') {
      f = ' ';
      m = "x";
    } else {
      m = ' ';
      f = "x";
    }

    if (this.actualIrtrarequests.type == "Nuevo Carnet" || this.actualIrtrarequests.type == "Reposición" || this.actualIrtrarequests.type == "Cambio de plástico") {
      window.open('http://200.94.251.67/phpscripts/irtraNewcarnet.php?address=' + this.profile[0].address.split(',')[0] + '&afiliacion=' + this.profile[0].irtra + '&birthday_day=' + this.profile[0].day_of_birth.split('-')[2] + '&birthday_month=' + this.profile[0].day_of_birth.split('-')[1] + '&birthday_year=' + this.profile[0].day_of_birth.split('-')[0] + '&book= ' + '&cedula= ' + '&company=' + this.useCompany + '&conyuge_firstname= ' + '&conyuge_lastname= ' + '&department=' + this.profile[0].city + '&dpi=' + this.profile[0].dpi + '&extended= ' + '&f=' + f + '&first_lastname=' + this.profile[0].first_lastname + '&first_name=' + this.profile[0].first_name + '&fold= ' + '&m=' + m + '&married= ' + '&municipio=' + this.profile[0].address.split(',')[2] + '&partida= ' + '&pasaport= ' + '&patronal=' + this.igss_patronal + '&phone=' + this.profile[0].primary_phone + '&reg= ' + '&second_lastname=' + this.profile[0].second_lastname + '&second_name=' + this.profile[0].second_name + '&zone=' + this.profile[0].address.split(",")[1].split(" ")[1] + "&email=" + this.profile[0].email + "&spouse_name=" + this.actualIrtrarequests.spouse_name + "&spouse_lastname=" + this.actualIrtrarequests.spouse_lastname, "_blank");
    } else {
      window.open('http://200.94.251.67/phpscripts/irtraRequest.php?name=' + this.profile[0].first_name + " " + this.profile[0].second_name + " " + this.profile[0].first_lastname + " " + this.profile[0].second_lastname + "&dpi=" + this.profile[0].dpi + "&status=" + this.actualIrtrarequests.type, "_blank");
    }
  }

  editIrtra() {
    if (this.editableIrtra) {
      this.apiService.updateIrtra(this.profile[0]).subscribe((str: string) => { })
    }
    this.editableIrtra = !this.editableIrtra;
  }

  editIgss() {
    if (this.editableIgss) {
      this.apiService.updateIgss(this.profile[0]).subscribe((str: string) => { })
    }
    this.editableIgss = !this.editableIgss;
  }

  printDP() {
    let nm: string = this.profile[0].first_name + " " + this.profile[0].second_name + " " + this.profile[0].first_lastname + " " + this.profile[0].second_lastname;
    let months: string[] = [
      "enero",
      "febrero",
      "marzo",
      "abril",
      "mayo",
      "junio",
      "julio",
      "agosto",
      "septiembre",
      "octubre",
      "noviembre",
      "diciembre",
    ];

    this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
      window.open("http://200.94.251.67/phpscripts/disciplinariProcess.php?avaya=" + emp.client_id + "&employee=" + nm + "&sup=" + emp.reporter + "&date=" + this.todayDate.split("-")[2] + " de " + months[parseInt(this.todayDate.split('-')[1]) - 1] + " de " + this.todayDate.split('-')[0] + "&pos=" + emp.job + "&acc=Operaciones&grade=" + this.activeRequest.dp_grade + "&type=" + this.activeRequest.type + "&description=" + this.activeRequest.description + "&legal=" + this.activeRequest.legal_foundament + "&mot=" + this.activeRequest.motive + "&consequences=" + this.activeRequest.consequences + "&observations=" + this.activeRequest.observations, "_blank");
    })
  }
}
