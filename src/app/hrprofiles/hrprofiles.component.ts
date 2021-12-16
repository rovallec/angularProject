import { Component, OnInit, NgModule, ɵCodegenComponentFactoryResolver } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { profiles, profiles_family } from '../profiles';
import { ApiService } from '../api.service';
import { ActivatedRoute } from '@angular/router';
import { attendences, attendences_adjustment, vacations, leaves, waves_template, disciplinary_processes, insurances, beneficiaries, terminations, reports, advances, accounts, rises, call_tracker, letters, supervisor_survey, judicials, irtra_requests, messagings, credits, periods, payments, Fecha, vacyear, leavesAction, contractCheck, patronal, file_info, alertModal, hr_process } from '../process_templates';
import { AuthServiceService } from '../auth-service.service';
import { employees, fullPreapproval, hrProcess, payment_methods, queryDoc_Proc } from '../fullProcess';
import { users } from '../users';
import { isNullOrUndefined, isUndefined, isNull } from 'util';
import { process } from '../process';
import { Time, TranslationWidth } from '@angular/common';
import { AotCompiler, isEmptyExpression, ThrowStmt } from '@angular/compiler';
import { typeWithParameters } from '@angular/compiler/src/render3/util';
import { time } from 'console';
import { parse } from 'querystring';
import { Z_STREAM_END } from 'zlib';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { exit } from 'process';
import { element } from 'protractor';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-hrprofiles',
  templateUrl: './hrprofiles.component.html',
  styleUrls: ['./hrprofiles.component.css']
})

export class HrprofilesComponent implements OnInit {


  actualTerm: terminations = new terminations;
  countTerm: number = 0;

  profile: profiles[] = [new profiles()];
  staffes: users[] = [];
  family: profiles_family[] = [];
  selected_family: profiles_family = new profiles_family;
  imagenPrevia: any;
  filePDF: file_info = new file_info;
  files: any = [];
  loading: boolean;
  previsualizacion: string;

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
  profiletoMarge: string[][] = [[]];
  profiletoMargeHeaders: string[] = [];
  vac: vacyear[] = [];

  beneficiaryName: string;
  todayDate: string = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");

  showAttAdjustments: attendences_adjustment[] = [];
  showVacations: vacations[] = [];
  showAttendences: attendences[] = [new attendences];
  leaves: leaves[] = [];
  discilplinary_processes: disciplinary_processes[] = [];
  insurances: insurances = new insurances;
  beneficiaries: beneficiaries[] = [];
  process_templates: hr_process[] = [];
  processRecord: hr_process[] = [];
  allAccounts: accounts[] = [];
  municipios: string[] = [];
  tovalidate: profiles[] = [];
  selectedToMerge: string = null;
  actualMessagings: messagings = new messagings;
  actualIrtrarequests: irtra_requests = new irtra_requests;
  actualJudicial: judicials = new judicials;
  actualReport: reports = new reports;
  actualAdvance: advances = new advances;
  actualRise: rises = new rises;
  actualCallTracker: call_tracker = new call_tracker;
  actualLetters: letters = new letters;
  actualSurvey: supervisor_survey = new supervisor_survey;
  first_interview: queryDoc_Proc = new queryDoc_Proc;
  second_interview: queryDoc_Proc = new queryDoc_Proc;
  municipio: string = null;
  zone: string = null;
  first_line: string = null;

  edition_status: string = 'Browse';
  editInview: boolean = false;
  viewRecProd: boolean = false;
  addProc: boolean = false;
  actuallProc: hr_process = new hr_process;
  newProcess: boolean = false;
  addBeneficiary: boolean = false;
  modifyInsurance: boolean = false;
  newInsurance: boolean = false;
  insuranceNull: boolean = true;
  validating: boolean = false;
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
  editingNames: boolean = false;
  newAudience: string = "NO";
  editRequest: boolean = true;
  newSuspension: string = "NO";
  accChange: string = null;
  editableIrtra: boolean = false;
  editableIgss: boolean = false;
  editingDPI: boolean = false;
  editingPhones: boolean = false;
  editingAddress: boolean = false;
  processUpload: string = '';
  departamento: string = null;
  earnVacations: number = 0;
  tookVacations: number = 0;
  availableVacations: number = 0;
  returnedVacations: number = 0;
  dismissedVacations: number = 0;
  haveAvailableVacations = function haveAvailableVacations() { return this.actuallProc.mount <= Number(this.availableVacations); };
  actualPeriod: periods = new periods;
  complete_adjustment: boolean = false;

  original_account: string = null;

  approvals: users[] = [new users];
  motives: string[] = ['Leave of Absence Unpaid', 'Maternity', 'Others Paid', 'Others Unpaid', 'IGSS Unpaid', 'VTO Unpaid', 'COVID Unpaid', 'COVID Paid', 'IGSS Paid'];

  maxDate: string = null;
  minDate: string = null;

  leaveDates: leavesAction[] = [];

  currentPayVacations: boolean = false;

  transfer_newCode: string = '';

  termNotification: string = 'YES';
  editingEmail: boolean = false;
  editingGender:boolean = false;
  editingBirthday:boolean = false;
  editingProfesion:boolean = false;
  editingJob:boolean = false;
  editingCivil:boolean = false;
  editingNat:boolean = false;
  editingReporter:boolean = false;
  editingSuspension:boolean = false;
  editingAccessCard:boolean = false;
  editingHeadsets:boolean = false;
  temp_day_1:string = null;
  temp_day_2:string = null;
  temp_day_3:string = null;
  temp_day_4:string = null;
  max_vac:number = 10;
  showMore: string = 'Show More';
  patron: patronal[] = [];
  actPatron: patronal = new patronal;
  selectedReporter:string = null;
  advanceDays: number = 1;

  alert:alertModal = new alertModal;

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

  constructor(private apiService: ApiService, private route: ActivatedRoute, public authUser: AuthServiceService, private sanitizer: DomSanitizer) { }

  ngOnInit() {
    this.todayDate = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");
    this.profile[0].idprofiles = this.route.snapshot.paramMap.get('id');
    this.getValidatingData();
    this.apiService.getProfile(this.profile[0]).subscribe((prof: profiles[]) => {
      this.profile = prof;
      this.apiService.getFamilies(this.profile[0]).subscribe((fam: profiles_family[]) => {
        this.family = fam;
      });
    });

    this.apiService.getPeriods().subscribe((p: periods[]) => {
      this.actualPeriod = p[p.length - 1];
    });

    this.apiService.getApprovers().subscribe((usrs: users[]) => {
      let reporters: users[] = usrs.filter(usr => usr.department == this.workingEmployee.account);
      this.approvals = reporters;
    });

    this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
      this.workingEmployee = emp;
      this.apiService.getPatronalNumber().subscribe((pat: patronal[]) => {
        this.patron = pat;
        this.patron.forEach(p => {
          if (this.workingEmployee.society == p.name) {
            this.actPatron = p;
          }
        });
        this.actualLetters.patronal_number = this.actPatron.patronal_number;
        this.actualLetters.company = this.actPatron.name;
        this.profile[0].date_joining = emp.hiring_date;
        this.activeEmp = emp.idemployees;
        this.accId = emp.account;
        this.vacationsEarned = (new Date(this.todayDate).getMonth() - new Date(this.profile[0].date_joining).getMonth() + ((new Date(this.todayDate).getFullYear() - new Date(this.profile[0].date_joining).getFullYear()) * 12));
        this.getVacations();
        this.getAllaccounts();
      })
      this.getAttendences(this.todayDate);
    })


    this.attAdjudjment.id_user = this.authUser.getAuthusr().iduser;
    this.attAdjudjment.date = this.todayDate;
    this.attAdjudjment.state = 'PENDING';
    this.attAdjudjment.status = 'PENDING';
    this.editAdj = false;

    this.vacationAdd = false;
    this.activeVacation.date = this.todayDate;
    this.activeVacation.setYear();
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

  getProfile() {
    this.todayDate = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");
    this.profile[0].idprofiles = this.route.snapshot.paramMap.get('id')
    this.getValidatingData();
    this.apiService.getProfile(this.profile[0]).subscribe((prof: profiles[]) => {
      this.profile = prof;
    });
  };

  getFamily(fam: profiles_family) {
    this.selected_family = fam;
  };

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
    let actualPeriod: periods;
    this.apiService.getPeriods().subscribe((pr: periods[]) => {
      pr.forEach(per => {
        if (new Date(per.start).getTime() <= new Date(dt).getTime() && new Date(per.end).getTime() >= new Date(dt).getTime()) {
          actualPeriod = per;
        }
      })
      this.apiService.getAttendences({ id: this.route.snapshot.paramMap.get('id'), date: "<= '" + dt + "'" }).subscribe((att: attendences[]) => {
        this.apiService.getVacations({ id: this.route.snapshot.paramMap.get('id') }).subscribe((vac: vacations[]) => {
          this.apiService.getLeaves({ id: this.route.snapshot.paramMap.get('id') }).subscribe((leave: leaves[]) => {
            this.apiService.getDPAtt({ id: this.workingEmployee.idemployees, date_1: actualPeriod.start, date_2: actualPeriod.end }).subscribe((dp: disciplinary_processes[]) => {
              this.apiService.getTermdt(this.workingEmployee).subscribe((trm: terminations) => {
                this.showAttendences = att;

                this.showAttendences.forEach(atte => {
                  let valid_trm: boolean = false;
                  let activeSuspension: boolean = false;
                  let activeVacation: boolean = false;
                  let activeLeave: boolean = false;
                  let mother_father_day: boolean = false;

                  if (!isNullOrUndefined(trm.valid_from)) {
                    if (new Date(trm.valid_from).getTime() <= new Date(atte.date).getTime()) {
                      valid_trm = true;
                      atte.balance = 'TERM';
                    }
                  }

                  if (!valid_trm) {
                    dp.forEach(disciplinary_process => {
                      if (!activeSuspension && disciplinary_process.day_1 == atte.date || disciplinary_process.day_2 == atte.date || disciplinary_process.day_3 == atte.date || disciplinary_process.day_4 == atte.date) {
                        activeSuspension = true;
                        atte.balance = 'SUSPENSION';
                      }
                    })
                  }

                  if (!activeSuspension) {
                    vac.forEach(vacation => {
                      if (!activeVacation && vacation.status != "COMPLETED" && vacation.status != 'DISMISSED' && vacation.took_date == atte.date && vacation.action == "Take") {
                        activeVacation = true;
                        atte.balance = 'VAC';
                        if (Number(vacation.count) < 1) {
                          if (atte.scheduled != "OFF") {
                            atte.worked_time = (Number(atte.worked_time) + (Number(atte.scheduled) * Number(vacation.count))).toFixed(5);
                            atte.balance = (Number(atte.worked_time) - Number(atte.scheduled)).toFixed(3);
                          }
                        }
                      }
                    })
                  }

                  if (!activeSuspension && !activeVacation) {
                    leave.forEach(lv => {
                      if (!activeLeave && lv.status != "COMPLETED" && lv.status != 'DISMISSED' && (new Date(lv.start).getTime()) <= (new Date(atte.date).getTime()) && (new Date(lv.end).getTime()) >= (new Date(atte.date).getTime())) {
                        activeLeave = true;
                        if (lv.motive == 'Leave of Absence Unpaid') {
                          atte.balance = 'LOA';
                        }
                        if (lv.motive == 'Others Unpaid' || lv.motive == "IGSS Unpaid" || lv.motive == "VTO Unpaid" || lv.motive == "COVID Unpaid") {
                          atte.balance = 'JANP';
                        }
                        if (lv.motive == 'Maternity' || lv.motive == 'Others Paid' || lv.motive == "COVID Paid" || lv.motive == "IGSS Paid") {
                          atte.balance = 'JAP';
                        }
                      }
                    })
                  }
                  if (!activeVacation && !activeSuspension && !activeLeave) {
                    if (atte.scheduled == "OFF") {
                      atte.balance = "OFF";
                    } else {
                      if (!isNullOrUndefined(this.workingEmployee.children) && !isNullOrUndefined(this.workingEmployee.gender)) {
                        if (Number(this.workingEmployee.children) > 0) {
                          if (this.workingEmployee.gender == 'Femenino') {
                            if (atte.date == (new Date().getFullYear() + "-05-10")) {
                              mother_father_day = true;
                              atte.balance = "HLD";
                            }
                          }
                        }
                      }
                      if (atte.date != (new Date().getFullYear() + "-01-01") && atte.date != (new Date().getFullYear() + "-06-28") && atte.date != (new Date().getFullYear() + "-04-01") && atte.date != (new Date().getFullYear() + "-04-02") && atte.date != (new Date().getFullYear() + "-04-03") && atte.date != (new Date().getFullYear() + "-05-01") && !mother_father_day) {
                        if (Number(atte.scheduled) > 0) {
                          if (Number(atte.worked_time) == 0) {
                            atte.balance = "NS";
                          } else {
                            atte.balance = (Number(atte.worked_time) - Number(atte.scheduled)).toString();
                          }
                        }
                      } else {
                        if (atte.scheduled != "OFF") {
                          if (Number(atte.worked_time) > Number(atte.scheduled)) {
                            atte.balance = (Number(atte.worked_time) - Number(atte.scheduled)).toFixed(2);
                          } else {
                            if (Number(atte.worked_time) > 0) {
                              atte.balance = 'HLD';
                            } else {
                              atte.balance = 'HLD';
                            }
                          }
                        }
                      }
                    }
                  }

                  if (this.complete_adjustment == true) {
                    if (this.attAdjudjment.id_attendence == atte.idattendences) {
                      this.complete_adjustment = false;
                      if (this.attAdjudjment.time_after == atte.worked_time) {
                        window.alert("Adjustment successfuly recorded");
                      } else {
                        window.alert("Adjustment not applyed correctly please try again or contact your administrator");
                      }
                    }
                  }
                })
                this.getAttAdjustemt();
              })
            })
          })
        })
      })
    });
    this.addJ = false;
    this.editAdj = false;
  }

  getAttAdjustemt() {
    this.editAdj = false;
    this.apiService.getAttAdjustments({ id: this.activeEmp }).subscribe((adj: attendences_adjustment[]) => {

      this.showAttAdjustments = [];
      if (adj.length >= 16) {
        for (let i = (adj.length - 1); i > (adj.length - 16); i = i - 1) {
          if (adj[i].id_department == '5' || adj[i].id_department == '27') {
            this.showAttAdjustments.push(adj[i]);
          }
        }
      } else {
        adj.forEach(ev => {
          if (ev.id_department == '27' || ev.id_department == '5') {
            this.showAttAdjustments.push(ev);
          }
        })
      }
      this.showAttendences.forEach(chng=>{
        if(isNullOrUndefined(chng.igss)){
          chng.igss = '0';
        }
        if(isNullOrUndefined(chng.tk_exp)){
          chng.tk_exp = '0';
        }
      })
      adj.forEach(at_adj=>{
        this.showAttendences.forEach(att_show=>{
          if(att_show.date == at_adj.attendance_date && (at_adj.id_department == '5' || at_adj.id_department == '27')){
            att_show.igss = (Number(att_show.igss) + Number(at_adj.amount)).toFixed(2);
          }else if(att_show.date == at_adj.attendance_date && at_adj.id_department == '28' && (at_adj.reason != 'Schedule FIX')){
            att_show.tk_exp = (Number(att_show.tk_exp) + Number(at_adj.amount)).toFixed(2);
          }
        })
      })
    })
  }

  addJustification(att: attendences) {
    this.attAdjudjment = new attendences_adjustment;
    this.attAdjudjment.start = "12:00";
    this.attAdjudjment.end = "12:00";
    this.editAdj = false;
    this.attAdjudjment.date = this.todayDate;
    this.attAdjudjment.state = "PENDING";
    this.attAdjudjment.status
    this.attAdjudjment.amount = "0";
    this.attAdjudjment.time_before = att.worked_time;
    this.attAdjudjment.id_attendence = att.idattendences;
    this.attAdjudjment.id_type = '2';
    this.attAdjudjment.id_employee = att.id_employee;
    this.attAdjudjment.time_after = this.attAdjudjment.time_before;
    this.attAdjudjment.id_department = this.authUser.getAuthusr().department;
    this.attAdjudjment.id_user = this.authUser.getAuthusr().iduser;
    this.attAdjudjment.id_import = '0';
    this.addJ = true;
  }

  insertAdjustment() {
    this.apiService.insertAttJustification(this.attAdjudjment).subscribe((str: string) => {
      this.complete_adjustment = true;
      this.getAttendences(this.todayDate);
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
    let found: boolean = false;
    let cnt:number = 0;
    let vacYears: vacyear[] = [];
    this.vac = [];
    this.earnVacations = this.vacationsEarned * 1.25;
    this.tookVacations = 0;
    this.availableVacations = 0;
    this.returnedVacations = 0;
    this.dismissedVacations = 0;
    this.apiService.getVacations({ id: this.route.snapshot.paramMap.get('id') }).subscribe((res: vacations[]) => {
      this.showVacations = res;
      this.showVacations.forEach(sv => {
        sv.year = new Date(sv.took_date).getFullYear();
      })

      res.forEach(vac => {
        let vacYear: vacyear = new vacyear;
        let VacFiltered: vacations[] = [];
        if (this.complete_adjustment) {
          if (vac.date == this.activeVacation.date) {
            found = true;
            this.complete_adjustment = false;
            window.alert("Vacation successfuly recorded");
          }
        }
        if (vac.action == "Add") {
          this.returnedVacations = this.returnedVacations + Number(vac.count);
        }
        if (((vac.action == "Paid") || (vac.action == "Take")) && vac.status != "DISMISSED") {
          this.tookVacations = this.tookVacations + Number(vac.count);
        } else if (vac.status == 'DISMISSED') {
          this.dismissedVacations = this.dismissedVacations + Number(vac.count);
        }

        vacYears = this.vac.filter(v => String(v.year) == new Date(vac.took_date).getFullYear().toString());
        if (vacYears.length == 0) {
          vacYear.year = new Date(vac.took_date).getFullYear();
          vacYear.selected = false;
          VacFiltered = this.showVacations.filter(svf => String(svf.year) == new Date(vac.took_date).getFullYear().toString());
          vacYear.vacations.push.apply(vacYear.vacations, VacFiltered);
            this.vac.push(vacYear);
        }

        this.vac.sort((a, b) => a.year - b.year);
        this.vac.forEach(toSort=>{
          toSort.vacations.sort((a,b)=> new Date(b.date).getTime() - new Date(a.date).getTime());
        })

        vacYears.forEach(vv => {
          if (vv.year.toString() == new Date().getFullYear().toString()) {
              vv.selected = true;
          }
        })
      })

      if (this.complete_adjustment && !found) {
        window.alert("Vacation not applyed correctly please try again or contact your administrator");
      }
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
    if (action == "Add") {
      this.activeVacation.status = "COMPLETED";
      this.activeVacation.notes = "PAID VACATIONS";
    } else {
      if (this.currentPayVacations) {
        this.activeVacation.status = "COMPLETED";
      } else {
        this.activeVacation.status = 'PENDING';
      }
    }
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
      this.complete_adjustment = true;
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
    let found: boolean = false;
    this.apiService.getLeaves({ id: this.route.snapshot.paramMap.get('id') }).subscribe((leaves: leaves[]) => {
      if (this.complete_adjustment) {
        leaves.forEach(lv => {
          if (lv.start == this.activeLeave.start && lv.end == this.activeLeave.end) {
            this.complete_adjustment = false;
            found = true;
            window.alert("Leave successfuly recorded");
          }
        })
      }
      if (this.complete_adjustment && !found) {
        window.alert("Leave not correctly applayed please try again latter or contact your administrator");
      }
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
    this.actuallProc = new hr_process;
    this.viewRecProd = false;
    this.getProcessesrecorded();
    this.actualTerm = new terminations;
    this.actualReport = new reports;
    this.actuallProc = new hr_process;
    this.actualRise = new rises;
    this.actualAdvance = new advances;
    this.apiService.getPeriods().subscribe((p: periods[]) => {
      this.actualPeriod = p[p.length - 1];
    })
  }

  setLeave() {
    this.activeLeave = new leaves;
    this.leaveDates = [];
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

  onChange(ld: leavesAction, event) {
    this.leaveDates[this.leaveDates.indexOf(ld)].action = event.target.value;
  }

  fillLeave(): leaves {
    let leave = new leaves;
    leave.id_user = this.activeLeave.id_user;
    leave.id_employee = this.workingEmployee.idemployees;
    leave.id_type = '5';
    leave.id_department = this.activeLeave.id_department;
    leave.date = this.activeLeave.date;
    leave.notes = this.activeLeave.notes + '|| Created by split start: ' +
                  this.activeLeave.start + ' end: ' + this.activeLeave.end + '.';
    leave.status = 'PENDING';
    leave.motive = this.activeLeave.motive;
    leave.approved_by = this.activeLeave.approved_by;
    return leave;
  }

  saveActionLeaves() {
    let note: string = '';
    let leave: leaves = this.activeLeave;
    let leavesNew: leaves[] = [];
    let start: string = '';
    let end: string = '';
    let f: Date = new Date(this.leaveDates[0].dates);
    f = this.addDays(f, 1);

    this.activeLeave.id_type = '5';
    this.activeLeave.id_employee = this.workingEmployee.idemployees;
    note = leave.notes;
    leave.notes = note + ' | Dismissed by split.';
    this.apiService.updateLeaves(leave).subscribe((str: string) => {
      start = (f.getFullYear().toString() + '-' + String(f.getMonth() + 1).padStart(2, '0') + '-' + String(f.getDate()).padStart(2,'0'));
      leave.start = start;
      leave.notes = note;
      leave = this.fillLeave();
      for (let i = 0; i < this.leaveDates.length; i++) {
        let ld: leavesAction = this.leaveDates[i];
        if (ld.action=='PENDING') {
          start = (f.getFullYear().toString() + '-' + String(f.getMonth() + 1).padStart(2, '0') + '-' + String(f.getDate()).padStart(2,'0'));

          if ((leave.start == null) || (leave.start.trim() == '')) {
            leave.start = start;
          }

          leave.status = 'PENDING';

          console.log('Estado pendiente: ');
          console.log(leave);
          console.log("<br>");
        } else {
          f = this.addDays(f, -1);
          end = (f.getFullYear().toString() + '-' + String(f.getMonth() + 1).padStart(2, '0') + '-' + String(f.getDate()).padStart(2,'0'));
          if (!isNullOrUndefined(leave.start)) {
            leave.end = end;
            leavesNew.push(leave);
          }
          console.log(leave);
          leave = this.fillLeave();
          f = this.addDays(f, 1);
        }
        end = (f.getFullYear().toString() + '-' + String(f.getMonth() + 1).padStart(2, '0') + '-' + String(f.getDate()).padStart(2,'0'));
        f = this.addDays(f, 1);
        if ((i==this.leaveDates.length-1) && (ld.action=='PENDING')) {
          leave.end = end;
          leavesNew.push(leave);
          console.log('Todos los Leaves: ');
          console.log(leavesNew);
        }
      }

      leavesNew.forEach(ln => {
        this.apiService.insertLeaves(ln).subscribe((_str: string) => {
          this.complete_adjustment = true;
          this.getLeaves();
        })
      })
      window.alert("Change successfuly recorded");
      this.cancelView();
    })
  }

  insertLeave() {
    this.apiService.insertLeaves(this.activeLeave).subscribe((str: string) => {
      this.complete_adjustment = true;
      this.getLeaves();
      this.cancelView();
    })
  }

  selectLeave(leave: leaves) {
    let start: number = 0;
    let end: number = 0;
    let f: Date = new Date(leave.start);

    let days: number = new Date(leave.end).getTime() - new Date(leave.start).getTime();
    days = days / (1000*3600*24);

    start = new Date(leave.start).getDate();
    end = new Date(leave.end).getDate();

    this.activeLeave = leave;
    this.editLeave = true;
    this.showLeave = true;
    this.leaveDates = [];

    for (let i = 0; i <= days; i++) {
      let ld: leavesAction = new leavesAction;
      f = this.addDays(f, 1);

      ld.dates = (f.getFullYear().toString() + '-' + String(f.getMonth() + 1).padStart(2, '0') + '-' + String(f.getDate()).padStart(2,'0'));
      ld.action = 'PENDING';
      this.leaveDates.push(ld);
    }

  }

  addDays(fecha: Date, days: number): Date {
    fecha.setDate(fecha.getDate() + days);
    return fecha;
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
    if (dp.status == 'DISPATCHED' || dp.status == "COMPLETED") {
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
          let act: hr_process = new hr_process;
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
    this.activeRequest.day_1 = str;
  }

  pushDay2(str: any) {
    this.activeRequest.day_2 = str;
  }

  pushDay3(str: any) {
    this.activeRequest.day_3 = str;
  }

  pushDay4(str: any) {
    this.activeRequest.day_4 = str;
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
    this.apiService.getTemplates().subscribe((prs: hr_process[]) => {
      this.process_templates = prs;
    });
  }

  addProcess() {
    this.newProcess = !this.newProcess;
    if (this.newProcess == false) {
      this.cancelView();
    }
  }

  setProcess(act: hr_process) {
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
    this.transfer_newCode = this.workingEmployee.nearsol_id;
    switch (this.actuallProc.name) {
      case 'Supervisor Survey':
        this.actuallProc.descritpion = null;
        break;
      case 'Advance':
        this.actualAdvance = new advances;
        this.actuallProc.status = 'PENDING';
        break;
      case 'Rise':
        this.actualRise = new rises;
        this.actualRise.new_position = this.workingEmployee.job;
        this.actualRise.old_position = this.workingEmployee.job;
        break;
      case 'Pay Vacations':
        if (this.availableVacations < 1) {
          this.addVac = false;
        } else {
          this.actuallProc.idprocesses = '1';
          this.addVac = true;
        }
        this.actuallProc.descritpion = null;
        break;
      case 'Termination':
        this.termNotification = 'YES';
        this.actualTerm.access_card = "YES";
        this.actualTerm.headsets = "YES";
        this.actualTerm.nearsol_experience = '0';
        this.actualTerm.supervisor_experience = '0';
        let proc: hr_process = new hr_process;
        proc.id_profile = this.workingEmployee.id_profile;
        proc.id_role = '1';
        this.apiService.getProcesses(proc).subscribe((processes: hr_process[]) => {
          processes.forEach(process => {
            if (process.name == 'First Interview') {
              let prP: fullPreapproval = new fullPreapproval;
              prP.idprocesses = process.idprocesses;
              this.apiService.getFullTestResults(prP).subscribe((qry: queryDoc_Proc[]) => {
                this.first_interview.username = qry[0].username;
              })
            } else {
              if (process.name == 'Second Interview') {
                let prP2: fullPreapproval = new fullPreapproval;
                prP2.idprocesses = process.idprocesses;
                this.apiService.getFullTestResults(prP2).subscribe((qry: queryDoc_Proc[]) => {
                  this.second_interview.english_test = qry[0].english_test;
                })
              }
            }
          });
        })
        break;
      case 'Transfer':
        this.termNotification = 'YES';
        if (Number(this.todayDate.split("-")[2]) <= 15) {
          this.minDate = this.todayDate.split("-")[0] + "-" + this.todayDate.split("-")[1] + "-01";
          this.maxDate = this.todayDate.split("-")[0] + "-" + this.todayDate.split("-")[1] + "-15";
        } else {
          this.minDate = this.todayDate.split("-")[0] + "-" + this.todayDate.split("-")[1] + "-16";
          this.maxDate = this.todayDate.split("-")[0] + "-" + this.todayDate.split("-")[1] + new Date(Number(this.todayDate.split('-')[0]), (Number(this.todayDate.split('-')[1]) + 1), 0);
        }
        this.original_account = this.workingEmployee.account;
        this.accChange = this.accId;
        this.newProcess = true;
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
    let Abort: AbortController = new AbortController;

    if(this.actuallProc.name == 'Transfer'){
      this.actuallProc.descritpion = this.actuallProc.descritpion + "|" + this.workingEmployee.account + "|" + this.workingEmployee.nearsol_id;
    }

    this.apiService.insertProc(this.actuallProc).subscribe((str: string) => {
      switch (this.actuallProc.name) {
        case 'Termination':
          let proc: hrProcess = new hrProcess;
          proc.idhr_process = str;
          this.actualTerm.id_process = str;
          this.apiService.insertTerm(this.actualTerm).subscribe((str: string) => {
            if (str == "1") {
              if (this.termNotification == 'YES') {
                this.sendMail();
              }
              this.cancelView();
            } else {
              window.alert("An error has occured:\n" + str.split("|")[1]);
              proc.notes = this.actuallProc.descritpion + "|" + ("An error has occured:" + str.split("|")[1]);
              this.apiService.updatehr_process(proc).subscribe((str: string) => { });
            }
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
            if (str.split("|")[0] == "1") {
              window.alert("Action successfuly recorded.");
              this.cancelView();
            } else {
              window.alert("An error has occured:\n" + str.split("|")[1]);
            }
          })
          break;
        case 'Rise':
          let end: boolean = false;
          // Prompt
          if (window.confirm("Are you sure you want to save?")) {
            this.actualRise.id_process = str;
            this.actualRise.id_employee = this.actuallProc.id_profile;
            this.actualRise.old_salary = (Number(this.workingEmployee.productivity_payment) + Number(this.workingEmployee.base_payment)).toFixed(2);
            this.actualRise.new_productivity_payment = (Number(this.actualRise.new_salary) - Number(this.workingEmployee.base_payment)).toFixed(2);
            try {
              if (isNullOrUndefined(this.actualRise.approved_date) || isNullOrUndefined(this.actualRise.approved_by) ||
                isNullOrUndefined(this.actualRise.effective_date) || isNullOrUndefined(this.actualRise.trial_start) || isNullOrUndefined(this.actualRise.trial_end)) {
                throw new Error('Incomplete data.');
              } else {
                end = false;
              }
            } catch (error) {
              window.alert(error);
              end = true;
            }

            if (!end) {
              this.apiService.insertRise(this.actualRise).subscribe((str: string) => {
                if (str.split("|")[0] == "1") {
                  window.alert("Action successfuly recorded.");
                  this.cancelView();
                } else {
                  window.alert("An error has occured:\n" + str.split("|")[1]);
                }
              })
            }
          }
          break;
        case 'Call Tracker':
          this.actualCallTracker.id_process = str;
          this.apiService.insertCallTracker(this.actualCallTracker).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Letter':
          this.actualLetters.id_process = str;
          this.actualLetters.patronal_number = this.actPatron.patronal_number;
          this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
            this.actualLetters.base_salary = emp.base_payment;
            this.actualLetters.productivity_salary = emp.productivity_payment;
          })
          this.apiService.insertLetters(this.actualLetters).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'Pay Vacations':
          let cnt: number = 0;
          for (let i = 0; i < (parseFloat(this.actuallProc.mount.toString())); i++) {
            this.apiService.getPeriods().subscribe((periods: periods[]) => {
              this.apiService.getPayments(periods[periods.length - 1]).subscribe((payment: payments[]) => {
                this.currentPayVacations = true;
                this.addVacation("Paid", "4");
                this.currentPayVacations = false;
                this.activeVacation.id_employee = this.route.snapshot.paramMap.get('id');
                this.activeVacation.id_user = this.authUser.getAuthusr().iduser;
                this.activeVacation.id_department = this.workingEmployee.account;
                this.activeVacation.date = this.todayDate;
                this.activeVacation.notes = 'Total: ' + this.actuallProc.mount + ' | ' + this.actuallProc.descritpion;
                this.activeVacation.took_date = this.todayDate;
                this.insertVacation();
                let cred: credits = new credits;
                cred.amount = ((parseFloat(this.workingEmployee.base_payment) / 30) + ((parseFloat(this.workingEmployee.productivity_payment)) / 30)).toFixed(2);
                cred.id_employee = this.workingEmployee.idemployees;
                cred.id_user = this.authUser.getAuthusr().iduser;
                cred.date = this.todayDate;
                payment.forEach(py => {
                  if (py.id_employee == this.workingEmployee.idemployees) {
                    cred.idpayments = py.idpayments;
                  }
                });
                cred.notes = 'Vacations Payed';
                cred.type = 'HR Vacations Payed';
                this.apiService.insertPushedCredit(cred).subscribe((str: string) => {
                  this.apiService.insertCredits(cred).subscribe((str: string) => {
                    cnt = cnt + 1;
                    if (cnt == (parseFloat(this.actuallProc.idprocesses) - 1)) {
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
          this.apiService.getSearchEmployees({ filter: 'neasol_id', value: this.actuallProc.idprocesses, dp: this.authUser.getAuthusr().department, rol: this.authUser.getAuthusr().id_role }).subscribe((emp: employees[]) => {
            if (isNullOrUndefined(emp)) {
              this.apiService.insertTransfer({ employee: this.activeEmp, account: this.accId, client_id: this.transfer_newCode }).subscribe((_str: string) => {
                this.apiService.getPeriods().subscribe((p: periods[]) => {
                  this.apiService.getPaymentMethods(this.workingEmployee).subscribe((p_methods: payment_methods[]) => {
                    let py: payments = new payments;
                    let old_payment = new payments;
                    let period: periods = new periods;
                    py.id_employee = this.activeEmp;
                    if (!isNullOrUndefined(p_methods)) {
                      py.id_period = p[p.length - 1].idperiods;
                      p_methods.forEach(payment_method => {
                        if (payment_method.predeterm == '1') {
                          py.id_paymentmethod = payment_method.idpayment_methods;
                        }
                      })
                      period.start = 'explicit_employee';
                      period.idperiods = p[p.length - 1].idperiods;
                      period.status = this.workingEmployee.idemployees;
                      this.apiService.getPayments(period).subscribe((actual_payments: payments[]) => {
                        old_payment = actual_payments[actual_payments.length - 1];
                        old_payment.id_account_py = this.original_account;
                        this.apiService.setPayment(old_payment).subscribe((str: string) => {
                          this.apiService.insertPayments(py).subscribe((str: string) => {
                            if (isNullOrUndefined(str.toString().split("|"))) {
                              window.alert("An error has occurred please contact your administrator");
                              this.cancelView();
                            } else {
                              let emp_toUpdate = new employees;
                              emp_toUpdate.idemployees = this.workingEmployee.idemployees;
                              emp_toUpdate.platform = "nearsol_id";
                              emp_toUpdate.society = this.transfer_newCode;
                              emp_toUpdate.id_profile = this.route.snapshot.paramMap.get('id');
                              emp_toUpdate.state = 'EMPLOYEE';
                              this.apiService.updateEmployee(emp_toUpdate).subscribe((str: string) => {
                                if(this.termNotification == 'YES'){
                                  this.sendMailTransfer();
                                }
                                window.alert("Record successfuly inserted");
                              })
                              this.cancelView();
                            }
                            this.newProcess = false;
                          })
                        })
                      })
                    } else {
                      window.alert("There is no payment method available to this employee please communicate with Accounting to clarify this");
                    }
                  })
                })
              })
            } else {
              window.alert("This EmploeyeID is already took please try with other");
            }
          })
          break;
        case 'Legal Discount':
          this.actualJudicial.id_process = str;
          this.apiService.insertJudicials(this.actualJudicial).subscribe((str: string) => {
            this.cancelView();
          })
          break;
        case 'IRTRA Request':
          this.actualIrtrarequests.idprocess = str;
          if (isNullOrUndefined(this.profile[0].address)) {
            window.alert("No correct address associated to this employee, please confir it meet the next syntax\n'casa/calle/avenida/colonia,(COMA)ZONA: #,(COMA)Municipio,(COMMA)Departamento' \n Or create the address in 'Contact' Tab");
            this.cancelView();
          } else {
            if ((this.profile[0].address.split(',').length != 4)) {
              window.alert("No correct address associated to this employee, please confir it meet the next syntax\n'casa/calle/avenida/colonia,(COMA)ZONA: #,(COMA)Municipio,(COMMA)Departamento' \n Or create the address in 'Contact' Tab")
              this.cancelView();
            } else {

              if (isNullOrUndefined(this.actualIrtrarequests.spouse_name)) {
                this.actualIrtrarequests.spouse_name = "-";
              }

              if (isNullOrUndefined(this.actualIrtrarequests.spouse_lastname)) {
                this.actualIrtrarequests.spouse_lastname = "-";
              }
              this.apiService.insertIrtra_request(this.actualIrtrarequests).subscribe((str: string) => {
                this.getIrtra();
                this.cancelView();
              })
            }
          }
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
    this.countTerm = 0;
    this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
      this.apiService.getProcRecorded({ id: emp.idemployees }).subscribe((prc: hr_process[]) => {
        this.processRecord = prc;
        this.processRecord.forEach(pr => {
          if (pr.name == 'Termination') {
            this.countTerm++;
          }
        })
      })
    })
  }

  viewProcess(pr: hr_process) {
    this.viewRecProd = true;
    this.actuallProc = pr;
    switch (this.actuallProc.name) {
      case 'Transfer':
        this.accId = this.actuallProc.descritpion.split("|")[1];
      case 'Messaging':
        this.apiService.getMessagings(this.actuallProc).subscribe((msg: messagings) => {
          this.actualMessagings = msg;
        })
        break;
      case 'Termination':
        this.apiService.getTerm(this.actuallProc).subscribe((trm: terminations) => {
          this.countTerm++;
          let proc: hr_process = new hr_process;
          proc.id_profile = this.profile[0].id_profile;
          proc.id_role = '1';
          this.apiService.getProcesses(proc).subscribe((processes: hr_process[]) => {
            processes.forEach(process => {
              if (process.name == 'First Interview') {
                let prP: fullPreapproval = new fullPreapproval;
                prP.idprocesses = process.idprocesses;
                prP.id_profile = '1';
                this.apiService.getFullTestResults(prP).subscribe((qry: queryDoc_Proc[]) => {
                  this.first_interview.username = qry[0].username;
                })
              } else {
                if (process.name == 'Second Interview') {
                  let prP2: fullPreapproval = new fullPreapproval;
                  prP2.idprocesses = process.idprocesses;
                  prP2.id_profile = '1';
                  this.apiService.getFullTestResults(prP2).subscribe((qry: queryDoc_Proc[]) => {
                    this.second_interview.english_test = qry[0].english_test;
                  })
                }
              }
            });
          })
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
          this.validateAmount();
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
      case 'IRTRA Request':
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
        url = "http://172.18.2.45/phpscripts/letterLaboral.php?date=" + e_date + "&name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&puesto=" + emp.job + "&departamento=" + emp.id_account + "/" + this.actualLetters.company + "&start=" + dt + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&iduser=" + this.authUser.getAuthusr().iduser;
        window.open(url, "_blank");
      })
    }
    if (this.actualLetters.type == 'INTECAP') {
      this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
        e_date = "Guatemela, " + this.actualLetters.emition_date.split("-")[2] + " de " + month[parseInt(this.actualLetters.emition_date.split("-")[1])] + " del " + year[parseInt(this.actualLetters.emition_date.split("-")[0]) - 2020];
        var dt = numbers[parseInt(emp.hiring_date.split("-")[2]) - 1] + " de " + month[parseInt(emp.hiring_date.split("-")[1]) - 1] + " de; " + parseInt(emp.hiring_date.split("-")[0]);
        var afiliacion = this.profile[0].iggs;
        var patrono = '145998';
        url = "http://172.18.2.45/phpscripts/letterIntecap.php?date=" + e_date + "&name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&id=" + this.profile[0].dpi + "&company=" + this.actualLetters.company + "&hiring=" + dt + "&afiliacion=" + afiliacion + "&patronal=" + patrono + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&iduser=" + this.authUser.getAuthusr().iduser;
        window.open(url, "_blank");
      })
    }
    if (this.actualLetters.type == 'Ingresos') {
      this.apiService.getEmployeeId({ id: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees) => {
        var dt = numbers[parseInt(emp.hiring_date.split("-")[2]) - 1] + " de " + month[parseInt(emp.hiring_date.split("-")[1]) - 1] + " de; " + parseInt(emp.hiring_date.split("-")[0]);
        e_date = numbers[parseInt(this.actualLetters.emition_date.split("-")[2]) - 1] + " de " + month[parseInt(this.actualLetters.emition_date.split("-")[1]) - 1] + " de " + year[parseInt(this.actualLetters.emition_date.split("-")[0]) - 2020];
        var prod = parseFloat(emp.productivity_payment) - 250;
        var total = parseFloat(emp.base_payment) + parseFloat(emp.productivity_payment);
        url = "http://172.18.2.45/phpscripts/letterIngresos.php?name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&position=" + emp.job + "&department=" + emp.id_account + "/" + this.actualLetters.company + "&hire=" + dt + "&base=" + emp.base_payment + "&productivity=" + prod + "&total=" + total + "&date=" + e_date + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&iduser=" + this.authUser.getAuthusr().iduser;
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
          var url = "http://172.18.2.45/phpscripts/letterBaja.php?name=" + this.profile[0].first_name + ' ' + this.profile[0].second_name + ' ' + this.profile[0].first_lastname + ' ' + this.profile[0].second_lastname + "&position=" + emp.job + "&department=" + emp.id_account + "/" + this.actualLetters.company + "&hire=" + dt + "&date=" + e_date + "&user=" + this.authUser.getAuthusr().user_name + "&contact=" + this.authUser.getAuthusr().signature.split(";")[1] + "&job=" + this.authUser.getAuthusr().signature.split(";")[0] + "&id=" + emp.idemployees + "&term=" + trem + "&iduser=" + this.authUser.getAuthusr().iduser;
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
    const length = 8;
    let transfer: accounts = new accounts;
    this.actuallProc.descritpion = this.accId;
    this.allAccounts.forEach(element => {
      if (element.idaccounts == this.accId) {
        transfer = element;
      }
    })

    this.transfer_newCode = this.apiService.getCode(transfer.prefix, transfer.correlative, length);
  }

  getIrtra() {
    let m: string;
    let f: string;
    let married: string;

    if (this.workingEmployee.gender == 'M' || this.workingEmployee.gender == 'Masculino') {
      f = ' ';
      m = "x";
    } else {
      m = ' ';
      f = "x";
    }
    if ((this.actualIrtrarequests.type == "Nuevo Carnet" || this.actualIrtrarequests.type == "Reposición" || this.actualIrtrarequests.type == "Cambio de plástico") && !this.viewRecProd) {
      window.open('http://172.18.2.45/phpscripts/irtraNewcarnet.php?address=' + this.profile[0].address.split(',')[0] + '&afiliacion=' +
        this.profile[0].iggs + '&birthday_day=' + this.profile[0].day_of_birth.split('-')[2] + '&birthday_month=' + this.profile[0].day_of_birth.split('-')[1] +
        '&birthday_year=' + this.profile[0].day_of_birth.split('-')[0] + '&book= ' + '&cedula= ' + '&company=' + this.useCompany + '&conyuge_firstname= ' +
        '&conyuge_lastname= ' + '&department=' + this.profile[0].city + '&dpi=' + this.profile[0].dpi + '&extended= ' + '&f=' + f + '&first_lastname=' +
        this.profile[0].first_lastname + '&first_name=' + this.profile[0].first_name + '&fold= ' + '&m=' + m + '&married= ' + '&municipio=' +
        this.profile[0].address.split(',')[2].replace('de ', '') + '&partida= ' + '&pasaport= ' + '&patronal=' + this.igss_patronal + '&phone=' + this.profile[0].primary_phone + '&reg= ' +
        '&second_lastname=' + this.profile[0].second_lastname + '&second_name=' + this.profile[0].second_name + '&zone=' + this.profile[0].address.split(",")[1].split(" ")[2] +
        "&email=" + this.profile[0].email + "&spouse_name=" + this.actualIrtrarequests.spouse_name + "&spouse_lastname=" + this.actualIrtrarequests.spouse_lastname, "_blank");
    } else {
      window.open('http://172.18.2.45/phpscripts/irtraRequest.php?name=' + this.profile[0].first_name + " " + this.profile[0].second_name + " " + this.profile[0].first_lastname + " " + this.profile[0].second_lastname + "&dpi=" + this.profile[0].dpi + "&status=" + this.actualIrtrarequests.type, "_blank");
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
      window.open("http://172.18.2.45/phpscripts/disciplinariProcess.php?avaya=" + emp.client_id + "&employee=" + nm + "&sup=" + emp.reporter + "&date=" + this.todayDate.split("-")[2] + " de " + months[parseInt(this.todayDate.split('-')[1]) - 1] + " de " + this.todayDate.split('-')[0] + "&pos=" + emp.job + "&acc=Operaciones&grade=" + this.activeRequest.dp_grade + "&type=" + this.activeRequest.type + "&description=" + this.activeRequest.description + "&legal=" + this.activeRequest.legal_foundament + "&mot=" + this.activeRequest.motive + "&consequences=" + this.activeRequest.consequences + "&observations=" + this.activeRequest.observations, "_blank");
    })
  }

  change_time(any: any) {
    let str_split: Date = new Date(2020, 1, 1, parseFloat(this.attAdjudjment.start.split(":")[0]), parseFloat(this.attAdjudjment.start.split(":")[1].split(" ")[0]));
    let end_split: Date = new Date(2020, 1, 1, parseFloat(this.attAdjudjment.end.split(":")[0]), parseFloat(this.attAdjudjment.end.split(":")[1].split(" ")[0]));

    this.attAdjudjment.amount = ((end_split.getTime() - str_split.getTime()) / 3600000).toFixed(2);

    this.attAdjudjment.time_after = (parseFloat(this.attAdjudjment.time_before) + parseFloat(this.attAdjudjment.amount)).toFixed(2);
  }

  editDPI() {
    this.editingDPI = true;
  }

  editNames() {
    this.editingNames = true;
  }

  editPhone() {
    this.editingPhones = true;
  }

  editEmail() {
    this.editingEmail = true;
  }

  editAddress() {
    this.editingAddress = true;
  }

  editBirthday(){
    this.editingBirthday = true;
  }

  editGender(){
    this.editingGender = true;
  }

  editProfesion(){
    this.editingProfesion = true;
  }

  editJob(){
    this.editingJob = true;
  }

  editCivil(){
    this.editingCivil = true;
  }

  editNat(){
    this.editingNat = true;
  }

  editReporter(){
    this.editingReporter = true;
  }

  editSuspension(){
    this.editingSuspension = true;
  }

  editAccessCard(){
    this.editingAccessCard = true;
  }

  editHeadsets(){
    this.editingHeadsets = true;
  }


  closeEditNames() {
    if (this.editingAddress) {
      this.profile[0].address = this.first_line + ", Zona: " + this.zone + ", de " + this.municipio + ", " + this.departamento;
      this.profile[0].city = this.departamento;
    }
    this.profile[0].gender = this.workingEmployee.gender;
    this.workingEmployee.platform = 'explicit_change';
    this.workingEmployee.state = 'reporter';
    this.apiService.updateProfile(this.profile[0]).subscribe((prof: profiles) => {
      this.approvals.forEach(element=>{
        if(element.user_name == this.workingEmployee.reporter){
          this.workingEmployee.society = element.iduser;
        }
      })
      this.apiService.updateEmployee(this.workingEmployee).subscribe((str:string)=>{
        this.workingEmployee.platform = 'explicit_change';
        this.workingEmployee.state = 'job';
        this.workingEmployee.society = this.workingEmployee.job;
        this.apiService.updateEmployee(this.workingEmployee).subscribe((str:string)=>{
          window.alert("Changes successfuly recorded please re enter on this profile to retrive the new information");
        })
      })
    })
    this.editingNames = false;
    this.editingDPI = false;
    this.editingPhones = false;
    this.editingAddress = false;
    this.editingEmail = false;
    this.editingBirthday = false;
    this.editingGender = false;
    this.editingReporter = false;
    this.editingProfesion = false;
    this.editingCivil = false;
    this.editingNat = false;
    this.editingJob = false;
  }

  changeDistrit() {
    this.municipios = [];
    switch (this.departamento) {
      case 'Alta Verapaz':
        this.municipios.push('Cobán ');
        this.municipios.push('Santa Cruz Verapaz');
        this.municipios.push('San Cristóbal Verapaz');
        this.municipios.push('Tactic');
        this.municipios.push('Tamahú');
        this.municipios.push('San Miguel Tucurú');
        this.municipios.push('Panzóz');
        this.municipios.push('Senahú.');
        this.municipios.push('San Pedro Carchá');
        this.municipios.push('San Juan Chamelco');
        this.municipios.push('San Agustín Lanquín');
        this.municipios.push('Santa María Cahabón');
        this.municipios.push('Chisec');
        this.municipios.push('Chahal');
        this.municipios.push('Fray Bartolomé de las Casas');
        this.municipios.push('Santa Catalina La Tinta');
        this.municipios.push('Raxruhá');
        break;
      case 'Chimaltenango':
        this.municipios.push('Chimaltenango');
        this.municipios.push('San José Poaquil');
        this.municipios.push('San Martín Jilotepeque');
        this.municipios.push('San Juan Comalapa');
        this.municipios.push('Santa Apolonia');
        this.municipios.push('Tecpán');
        this.municipios.push('Patzún');
        this.municipios.push('San Miguel Pochuta');
        this.municipios.push('Patzicía');
        this.municipios.push('Santa Cruz Balanyá');
        this.municipios.push('Acatenango');
        this.municipios.push('San Pedro Yepocapa');
        this.municipios.push('San Andrés Itzapa');
        this.municipios.push('Parramos');
        this.municipios.push('Zaragoza');
        this.municipios.push('El Tejar');
        break;
      case 'Chiquimula':
        this.municipios.push('Chiquimula');
        this.municipios.push('Jocotán');
        this.municipios.push('Esquipulas');
        this.municipios.push('Camotán');
        this.municipios.push('Quezaltepeque');
        this.municipios.push('Olopa');
        this.municipios.push('Ipala');
        this.municipios.push('San Juan Hermita');
        this.municipios.push('Concepción Las Minas');
        this.municipios.push('San Jacinto');
        this.municipios.push('San José la Arada');
        break;
      case 'Peten':
        this.municipios.push('Flores');
        this.municipios.push('San José');
        this.municipios.push('San Benito');
        this.municipios.push('San Andrés');
        this.municipios.push('La Libertad');
        this.municipios.push('San Francisco');
        this.municipios.push('Santa Ana');
        this.municipios.push('Dolores');
        this.municipios.push('San Luis');
        this.municipios.push('Sayaxché');
        this.municipios.push('Melchor de Mencos');
        this.municipios.push('Poptún');
        break;
      case 'El Progreso':
        this.municipios.push('El Jícaro');
        this.municipios.push('Morazán');
        this.municipios.push('San Agustín Acasaguastlán');
        this.municipios.push('San Antonio La Paz');
        this.municipios.push('San Cristóbal Acasaguastlán');
        this.municipios.push('Sanarate');
        this.municipios.push('Guastatoya');
        this.municipios.push('Sansare');
        break;
      case 'Quiche':
        this.municipios.push('Santa Cruz del Quiché');
        this.municipios.push('Chiché');
        this.municipios.push('Chinique');
        this.municipios.push('Zacualpa');
        this.municipios.push('Chajul');
        this.municipios.push('Santo Tomás Chichicastenango');
        this.municipios.push('Patzité');
        this.municipios.push('San Antonio Ilotenango');
        this.municipios.push('San Pedro Jocopilas');
        this.municipios.push('Cunén');
        this.municipios.push('San Juan Cotzal');
        this.municipios.push('Santa María Joyabaj');
        this.municipios.push('Santa María Nebaj');
        this.municipios.push('San Andrés Sajcabajá');
        this.municipios.push('Uspantán');
        this.municipios.push('Sacapulas');
        this.municipios.push('San Bartolomé Jocotenango');
        this.municipios.push('Canillá');
        this.municipios.push('Chicamán');
        this.municipios.push('Ixcán');
        this.municipios.push('Pachalum');
        break;
      case 'Escuintla':
        this.municipios.push('Escuintla ');
        this.municipios.push('Santa Lucía Cotzumalguapa');
        this.municipios.push('La Democracia');
        this.municipios.push('Siquinalá');
        this.municipios.push('Masagua');
        this.municipios.push('Tiquisate');
        this.municipios.push('La Gomera');
        this.municipios.push('Guaganazapa');
        this.municipios.push('San José');
        this.municipios.push('Iztapa');
        this.municipios.push('Palín');
        this.municipios.push('San Vicente Pacaya');
        this.municipios.push('Nueva Concepción');
        break;
      case 'Guatemala':
        this.municipios.push('Santa Catarina Pinula');
        this.municipios.push('San José Pinula');
        this.municipios.push('Guatemala');
        this.municipios.push('San José del Golfo');
        this.municipios.push('Palencia');
        this.municipios.push('Chinautla');
        this.municipios.push('San Pedro Ayampuc');
        this.municipios.push('Mixco');
        this.municipios.push('San Pedro Sacatapéquez');
        this.municipios.push('San Juan Sacatepéquez');
        this.municipios.push('Chuarrancho');
        this.municipios.push('San Raymundo');
        this.municipios.push('Fraijanes');
        this.municipios.push('Amatitlán');
        this.municipios.push('Villa Nueva');
        this.municipios.push('Villa Canales');
        this.municipios.push('San Miguel Petapa');
        break;
      case 'Huehuetenango':
        this.municipios.push('Huehuetenango');
        this.municipios.push('Chiantla');
        this.municipios.push('Malacatancito');
        this.municipios.push('Cuilco');
        this.municipios.push('Nentón');
        this.municipios.push('San Pedro Necta');
        this.municipios.push('Jacaltenango');
        this.municipios.push('Soloma');
        this.municipios.push('Ixtahuacán');
        this.municipios.push('Santa Bárbara');
        this.municipios.push('La Libertad');
        this.municipios.push('La Democracia');
        this.municipios.push('San Miguel Acatán');
        this.municipios.push('San Rafael La Independencia');
        this.municipios.push('Todos Santos Cuchumatán');
        this.municipios.push('San Juan Atitlán');
        this.municipios.push('Santa Eulalia');
        this.municipios.push('San Mateo Ixtatán');
        this.municipios.push('Colotenango');
        this.municipios.push('San Sebastián Huehuetenango');
        this.municipios.push('Tectitán');
        this.municipios.push('Concepción Huista');
        this.municipios.push('San Juan Ixcoy');
        this.municipios.push('San Antonio Huista');
        this.municipios.push('Santa Cruz Barillas');
        this.municipios.push('San Sebastián Coatán');
        this.municipios.push('Aguacatán');
        this.municipios.push('San Rafael Petzal');
        this.municipios.push('San Gaspar Ixchil');
        this.municipios.push('Santiago Chimaltenango');
        this.municipios.push('Santa Ana Huista');
        break;
      case 'Izabal':
        this.municipios.push('Morales');
        this.municipios.push('Los Amates');
        this.municipios.push('Livingston');
        this.municipios.push('El Estor');
        this.municipios.push('Puerto Barrios');
        break;
      case 'Quetzaltenango':
        this.municipios.push('Quetzaltenango');
        this.municipios.push('Salcajá');
        this.municipios.push('San Juan Olintepeque');
        this.municipios.push('San Carlos Sija');
        this.municipios.push('Sibilia');
        this.municipios.push('Cabricán');
        this.municipios.push('Cajolá');
        this.municipios.push('San Miguel Siguilá');
        this.municipios.push('San Juan Ostuncalco');
        this.municipios.push('San Mateo');
        this.municipios.push('Concepción Chiquirichapa');
        this.municipios.push('San Martín Sacatepéquez');
        this.municipios.push('Almolonga');
        this.municipios.push('Cantel');
        this.municipios.push('Huitán');
        this.municipios.push('Zunil');
        this.municipios.push('Colomba Costa Cuca');
        this.municipios.push('San Francisco La Unión');
        this.municipios.push('El Palmar');
        this.municipios.push('Coatepeque');
        this.municipios.push('Génova');
        this.municipios.push('Flores Costa Cuca');
        this.municipios.push('La Esperanza');
        this.municipios.push('Palestina de Los Altos');
        break;
      case 'Retalhuleu':
        this.municipios.push('Retalhuleu ');
        this.municipios.push('San Sebastián');
        this.municipios.push('Santa Cruz Muluá');
        this.municipios.push('San Martín Zapotitlán');
        this.municipios.push('San Felipe');
        this.municipios.push('San Andrés Villa Seca');
        this.municipios.push('Champerico');
        this.municipios.push('Nuevo San Carlos');
        this.municipios.push('El Asintal');
        break;
      case 'Sacatepequez':
        this.municipios.push('Antigua Guatemala ');
        this.municipios.push('Jocotenango');
        this.municipios.push('Pastores');
        this.municipios.push('Sumpango');
        this.municipios.push('Santo Domingo Xenacoj');
        this.municipios.push('Santiago Sacatepéquez');
        this.municipios.push('San Bartolomé Milpas Altas');
        this.municipios.push('San Lucas Sacatepéquez');
        this.municipios.push('Santa Lucía Milpas Altas');
        this.municipios.push('Magdalena Milpas Altas');
        this.municipios.push('Santa María de Jesús');
        this.municipios.push('Ciudad Vieja');
        this.municipios.push('San Miguel Dueñas');
        this.municipios.push('San Juan Alotenango');
        this.municipios.push('San Antonio Aguas Calientes');
        this.municipios.push('Santa Catarina Barahona');
        break;
      case 'San Marcos':
        this.municipios.push('San Marcos');
        this.municipios.push('Ayutla');
        this.municipios.push('Catarina');
        this.municipios.push('Comitancillo');
        this.municipios.push('Concepción Tutuapa');
        this.municipios.push('El Quetzal');
        this.municipios.push('El Rodeo');
        this.municipios.push('El Tumblador');
        this.municipios.push('Ixchiguán');
        this.municipios.push('La Reforma');
        this.municipios.push('Malacatán');
        this.municipios.push('Nuevo Progreso');
        this.municipios.push('Ocós');
        this.municipios.push('Pajapita');
        this.municipios.push('Esquipulas Palo Gordo');
        this.municipios.push('San Antonio Sacatepéquez');
        this.municipios.push('San Cristóbal Cucho');
        this.municipios.push('San José Ojetenam');
        this.municipios.push('San Lorenzo');
        this.municipios.push('San Miguel Ixtahuacán');
        this.municipios.push('San Pablo');
        this.municipios.push('San Pedro Sacatepéquez');
        this.municipios.push('San Rafael Pie de la Cuesta');
        this.municipios.push('Sibinal');
        this.municipios.push('Sipacapa');
        this.municipios.push('Tacaná');
        this.municipios.push('Tajumulco');
        this.municipios.push('Tejutla');
        this.municipios.push('Río Blanco');
        this.municipios.push('La Blanca');
        break;
      case 'Santa Rosa':
        this.municipios.push('Cuilapa');
        this.municipios.push('Casillas Santa Rosa');
        this.municipios.push('Chiquimulilla');
        this.municipios.push('Guazacapán');
        this.municipios.push('Nueva Santa Rosa');
        this.municipios.push('Oratorio');
        this.municipios.push('Pueblo Nuevo Viñas');
        this.municipios.push('San Juan Tecuaco');
        this.municipios.push('San Rafael Las Flores');
        this.municipios.push('Santa Cruz Naranjo');
        this.municipios.push('Santa María Ixhuatán');
        this.municipios.push('Santa Rosa de Lima');
        this.municipios.push('Taxisco');
        this.municipios.push('Barberena');
        break;
      case 'Solola':
        this.municipios.push('Sololá');
        this.municipios.push('Concepción');
        this.municipios.push('Nahualá');
        this.municipios.push('Panajachel');
        this.municipios.push('San Andrés Semetabaj');
        this.municipios.push('San Antonio Palopó');
        this.municipios.push('San José Chacayá');
        this.municipios.push('San Juan La Laguna');
        this.municipios.push('San Lucas Tolimán');
        this.municipios.push('San Marcos La Laguna');
        this.municipios.push('San Pablo La Laguna');
        this.municipios.push('San Pedro La Laguna');
        this.municipios.push('Santa Catarina Ixtahuacán');
        this.municipios.push('Santa Catarina Palopó');
        this.municipios.push('Santa Clara La Laguna');
        this.municipios.push('Santa Cruz La Laguna');
        this.municipios.push('Santa Lucía Utatlán');
        this.municipios.push('Santa María Visitación');
        this.municipios.push('Santiago Atitlán');
        break;
      case 'Suchitepequez':
        this.municipios.push('Mazatenango');
        this.municipios.push('Cuyotenango');
        this.municipios.push('San Francisco Zapotitlán');
        this.municipios.push('San Bernardino');
        this.municipios.push('San José El Ídolo');
        this.municipios.push('Santo Domingo Suchitépequez');
        this.municipios.push('San Lorenzo');
        this.municipios.push('Samayac');
        this.municipios.push('San Pablo Jocopilas');
        this.municipios.push('San Antonio Suchitépequez');
        this.municipios.push('San Miguel Panán');
        this.municipios.push('San Gabriel');
        this.municipios.push('Chicacao');
        this.municipios.push('Patulul');
        this.municipios.push('Santa Bárbara');
        this.municipios.push('San Juan Bautista');
        this.municipios.push('Santo Tomás La Unión');
        this.municipios.push('Zunilito');
        this.municipios.push('Pueblo Nuevo');
        this.municipios.push('Río Bravo');
        break;
      case 'Tecpan':
        this.municipios.push('Totonicapán');
        this.municipios.push('San Cristóbal Totonicapán');
        this.municipios.push('San Francisco El Alto');
        this.municipios.push('San Andrés Xecul');
        this.municipios.push('Momostenango');
        this.municipios.push('Santa María Chiquimula');
        this.municipios.push('Santa Lucía La Reforma');
        this.municipios.push('San Bartolo');
        break;
      case 'Zacapa':
        this.municipios.push('Cabañas');
        this.municipios.push('Estanzuela');
        this.municipios.push('Gualán');
        this.municipios.push('Huité');
        this.municipios.push('La Unión');
        this.municipios.push('Río Hondo');
        this.municipios.push('San Jorge');
        this.municipios.push('San Diego');
        this.municipios.push('Teculután');
        this.municipios.push('Usumatlán');
        this.municipios.push('Zacapa');
        break;
      case 'Baja Verapaz':
        this.municipios.push('Salamá ');
        this.municipios.push('San Miguel Chicaj');
        this.municipios.push('Rabinal');
        this.municipios.push('Cubulco');
        this.municipios.push('Granados');
        this.municipios.push('Santa Cruz el Chol');
        this.municipios.push('San Jerónimo');
        this.municipios.push('Purulhá');
        break;
      case 'Jalapa':
        this.municipios.push('Jalapa');
        this.municipios.push('San Pedro Pinula');
        this.municipios.push('San Luis Jilotepeque');
        this.municipios.push('San Manuel Chaparrón');
        this.municipios.push('San Carlos Alzatate');
        this.municipios.push('Monjas');
        this.municipios.push('Mataquescuintla');
        break;
      case 'Jutiapa':
        this.municipios.push('Jutiapa');
        this.municipios.push('El Progreso');
        this.municipios.push('Santa Catarina Mita');
        this.municipios.push('Agua Blanca');
        this.municipios.push('Asunción Mita');
        this.municipios.push('Yupiltepeque');
        this.municipios.push('Atescatempa');
        this.municipios.push('Jerez');
        this.municipios.push('El Adelanto');
        this.municipios.push('Zapotitlán');
        this.municipios.push('Comapa');
        this.municipios.push('Jalpatagua');
        this.municipios.push('Conguaco');
        this.municipios.push('Moyuta');
        this.municipios.push('Pasaco');
        this.municipios.push('Quesada');
        break;
    }
  }

  getValidatingData() {
    this.apiService.getToValidate().subscribe((prof: profiles[]) => {
      this.tovalidate = prof;
    })
  }

  setSelectedProf(val: profiles) {
    this.profiletoMarge = [];
    this.selectedToMerge = val.idprofiles;
    let i: number = 1;
    Object.getOwnPropertyNames(val).forEach(obj => {
      let str: string[] = [];
      str[0] = obj;
      str[1] = (Object.values(val)[i - 1]);
      this.profiletoMarge.push(str);
      i++;
    })
  }

  mergeProfile() {
    this.validating = true;
    this.apiService.insertMergeProfile({ id_old: this.workingEmployee.id_profile, id_new: this.selectedToMerge }).subscribe((str: string) => {
      let proc: hr_process = new hr_process;
      proc.id_profile = this.workingEmployee.idemployees;
      proc.id_user = this.authUser.getAuthusr().iduser;
      proc.idprocesses = '20';
      proc.descritpion = "Data Merge from " + this.selectedToMerge;
      proc.prc_date = this.todayDate;
      proc.status = "CLOSED";
      this.apiService.insertProc(proc).subscribe((str: string) => {
        this.getProfile();
      })
    })
  }

  changeRadio(val: profiles) {
    this.tovalidate.forEach(element => {
      element.doc_type = '';
    });
    this.selectedToMerge = val.idprofiles;
  }

  setEdit(state: string) {
    this.edition_status = state;
  }

  addFamily() {
    this.setEdit('Insert');
    this.selected_family = new profiles_family;
    this.selected_family.affinity_id_profile = Number(this.profile[0].idprofiles);
  }

  delFamily() {
    this.apiService.delFamily(this.selected_family).subscribe((_fams: string) => {
      this.apiService.getFamilies(this.profile[0]).subscribe((fam: profiles_family[]) => {
        this.family = fam;
      })
    })
  }

  empty_family() {
    if (isNullOrUndefined(this.selected_family.affinity_first_name) || (this.selected_family.affinity_first_name == '') ||
      isNullOrUndefined(this.selected_family.affinity_first_last_name) || (this.selected_family.affinity_first_last_name == '') ||
      (this.selected_family.affinity_id_profile == 0)) {
      return true;
    } else {
      return false;
    }
  }

  saveFamily() {
    if (!this.empty_family()) {
      if (this.edition_status == 'Insert') {
        this.apiService.createFamily(this.selected_family).subscribe((fams: string) => {
          if (fams.split("|")[0] == "1") {
            window.alert("Action successfuly recorded.");
            this.cancelView();
            this.apiService.getFamilies(this.profile[0]).subscribe((fam: profiles_family[]) => {
              this.family = fam;
            });
          } else {
            window.alert("An error has occured:\n" + fams.split("|")[1]);
          }
        });
      } else if (this.edition_status == 'Edit') {
        this.apiService.updateFamily(this.selected_family).subscribe((fams: string) => {
          if (fams.split("|")[0] == "1") {
            window.alert("Action successfuly recorded.");
            this.cancelView();
            this.apiService.getFamilies(this.profile[0]).subscribe((fam: profiles_family[]) => {
              this.family = fam;
            });
          } else {
            window.alert("An error has occured:\n" + fams.split("|")[1]);
          }
        });
      }
    }
    this.setEdit('Browse');
  }

  cancelFamily() {
    this.selected_family = new profiles_family;
    this.setEdit('Browse');
  }

  updateVacation() {
    if(this.activeVacation.status == 'DISMISSED'){
      this.apiService.updateVacations(this.activeVacation).subscribe((str: string) => {
        window.alert("Change successfuly recorded");
        let revertVac: vacations = new vacations;
        revertVac.date = this.activeVacation.date;
        revertVac.took_date = this.todayDate;
        revertVac.id_department = this.authUser.getAuthusr().department;
        revertVac.id_employee = this.route.snapshot.paramMap.get('id');
        revertVac.id_type = '3';
        revertVac.action = "Add";
        revertVac.count = this.activeVacation.count;
        revertVac.id_user = this.authUser.getAuthusr().iduser;
        revertVac.notes = "Revert From Dismissed";
        revertVac.status = 'COMPLETED';
        this.activeVacation = revertVac;
        this.insertVacation();
        this.cancelView();
      })
    }else{
      this.apiService.updateVacation(this.activeVacation).subscribe((str:string)=>{
        if(str == '1'){
          this.alert.header = 'Vacation Approved';
          this.alert.content = 'Change successfully recorded';
          let ele = document.getElementById('dispay_alert');
          ele.click();
          this.cancelView();
        }
      })
    }
  }

  updateLeave() {
    this.activeLeave.notes = this.activeLeave.notes + ' | DISMISSED By attendance overlap';
    this.apiService.updateLeaves(this.activeLeave).subscribe((str: string) => {
      window.alert("Change successfuly recorded");
      this.cancelView();
    })
  }

  sendMail() {
    this.apiService.sendMailTerm({ idemployees: this.workingEmployee.idemployees }).subscribe((str: string) => {
    })
  }

  sendMailTransfer(){
    this.apiService.sendMailTransfer({id_employee:this.workingEmployee.idemployees}).subscribe((str:string)=>{})
  }

  getAccountName(str:string):string{
    let rtn:string = 'N/A';
    this.allAccounts.forEach(acc=>{
      if(acc.idaccounts == str){
        rtn = acc.name;
      }
    })
    return rtn;
  }

  cancelTransfer(){
    this.apiService.revertTransfer({id:this.workingEmployee.idemployees}).subscribe((str=>{
      this.cancelView();
    }))
  }

  revertTerm(){
    this.apiService.revertTermination({idhr_processes:this.actualTerm.id_process}).subscribe((str:string)=>{
      this.cancelView();
    })
  }

  revertAdjustment(){
    this.attAdjudjment.notes = this.authUser.getAuthusr().user_name;
    this.attAdjudjment.id_user = this.authUser.getAuthusr().iduser;
    this.attAdjudjment.start = '12:00';
    this.attAdjudjment.end = '12:00';
    this.attAdjudjment.amount = (Number(this.attAdjudjment.amount) * - 1).toFixed(2);
    this.attAdjudjment.time_before = this.attAdjudjment.time_after;
    this.attAdjudjment.time_after = (Number(this.attAdjudjment.time_after) + Number(this.attAdjudjment.amount)).toFixed(2);
    this.attAdjudjment.id_import = '0';
    this.apiService.revertJustification(this.attAdjudjment).subscribe((str:string)=>{
      this.attAdjudjment.notes = 'Reverted from ' + this.attAdjudjment.id_process + ' created at ' + this.attAdjudjment.date;
      this.insertAdjustment();
    })
  }

  closeSuspension(){
    this.apiService.updateSuspensionsDays(this.activeRequest).subscribe((str:string)=>{
      this.editingSuspension = false;
      this.cancelView();
    })
  }

  updateTermination(){
    this.apiService.updateTermination(this.actualTerm).subscribe((str:string)=>{
      this.editingHeadsets = false;
      this.editingAccessCard = false;
      window.alert("Record Successfully Updated");
    })
  }

  showMoreVac(){
    if(this.max_vac == 10){
      this.max_vac = 1000;
      this.showMore = 'Show Less';
    }else{
      this.max_vac = 10;
      this.showMore = 'Show More';
    }
  }

  printLetter() {
    var url = this.apiService.PHP_API_SERVER + "/phpscripts/letterVacations.php?name=" + this.workingEmployee.name +
      "&date=" + this.activeVacation.took_date + "&job=" + this.workingEmployee.job +
      "&start_date=" + this.workingEmployee.hiring_date + "&department=" + this.workingEmployee.id_account +
      "&days_requested=" + this.activeVacation.count + "&nearsol_id=" + this.workingEmployee.nearsol_id ;
    window.open(url, "_blank");
  }

  uploadLetter(event): any {
    const archivoCapturado = event.target.files[0];
    if (archivoCapturado.type=='application/pdf') {
      this.filePDF.id = this.activeVacation.id_process;
      this.filePDF.name = this.workingEmployee.nearsol_id + '-' + this.activeVacation.id_process + '.pdf';
      this.filePDF.type = archivoCapturado.type;

      this.extraerBase64(archivoCapturado).then((pdf: any) => {
        this.filePDF.file = pdf.base;
      })
      this.files.push(archivoCapturado);
      console.log(this.filePDF);
    } else {
      window.alert('It is only allowed to upload files with a .pdf extension.');
    }
  }

  saveFile(): any {
    try {
      this.apiService.saveFile(this.filePDF).subscribe(res => {
        console.log('Respuesta del servidor: ', res);
      })
    } catch (e) {
      console.log('Error', e);
    }
  }

  getFilePDF(type: string) {
    let url: string = '';
    if (type == 'Vacation') {
      this.processUpload = 'Vacation';
      url = this.apiService.PHP_API_SERVER + '/phpscripts/getFilePDF.php?id=' + this.activeVacation.id_process;
    } else if (type == 'Advance') {
      this.processUpload = 'Advance';
      url = this.apiService.PHP_API_SERVER + '/phpscripts/getFilePDF.php?id=' + this.actualAdvance.id_process;
    }
    window.open(url, "_blank");
  }

  extraerBase64 = async ($event: any) => new Promise((resolve, reject) => {
    try {
      const unsafePdf = window.URL.createObjectURL($event);
      const filePDF = this.sanitizer.bypassSecurityTrustUrl(unsafePdf);
      const reader = new FileReader();
      reader.readAsDataURL($event);
      reader.onload = () => {
        resolve({
          blob: $event,
          filePDF,
          base: reader.result
        });
      };
      reader.onerror = error => {
        resolve({
          blob: $event,
          filePDF,
          base: null
        });
      };
    } catch (e) {
      return null;
    }
  })

  prorataSalary() {
    const DiasMes = 30; // Se toma a Mes comercial.
    let salary: number;
    if(this.advanceDays <= DiasMes) {
      salary = this.advanceDays * ((Number(this.workingEmployee.base_payment) + Number(this.workingEmployee.productivity_payment)) / DiasMes);
      this.actualAdvance.amount = salary.toFixed(2);
    } else {
      this.actualAdvance.amount = '0.00';
      this.advanceDays = DiasMes;
      this.prorataSalary();
    }
  }

  validateAmount() {
    const DiasMes = 30; // Se toma a Mes comercial.
    let maxSalary: number = (Number(this.workingEmployee.base_payment) + Number(this.workingEmployee.productivity_payment));
    let dailyPayment = maxSalary / DiasMes;

    if (Number(this.actualAdvance.amount) > maxSalary) {
      this.actualAdvance.amount = maxSalary.toFixed(2);
    }

    this.advanceDays = Number(Number(Number(this.actualAdvance.amount) / dailyPayment).toFixed(0));
    if (this.advanceDays == 0) {
      this.advanceDays = 1;
    }
  }

  uploadAdvance(event): any {
    const archivoCapturado = event.target.files[0];
    if (archivoCapturado.type=='application/pdf') {
      this.filePDF.id = this.actuallProc.idprocesses;
      this.filePDF.name = this.workingEmployee.nearsol_id + '-' + this.actuallProc.idprocesses + '.pdf';
      this.filePDF.type = archivoCapturado.type;

      this.extraerBase64(archivoCapturado).then((pdf: any) => {
        this.filePDF.file = pdf.base;
      })
      this.files.push(archivoCapturado);
      console.log(this.filePDF);
    } else {
      window.alert('It is only allowed to upload files with a .pdf extension.');
    }
  }

  typeProcessUpload(type: string) {
    this.processUpload = type;
  }

  printAdvances() {
    var url = this.apiService.PHP_API_SERVER + "/phpscripts/letterVacations.php?name=" + this.workingEmployee.name +
      "&date=" + this.activeVacation.took_date + "&job=" + this.workingEmployee.job +
      "&start_date=" + this.workingEmployee.hiring_date + "&department=" + this.workingEmployee.id_account +
      "&days_requested=" + this.activeVacation.count + "&nearsol_id=" + this.workingEmployee.nearsol_id ;
    window.open(url, "_blank");
  }

  updateMountProc(Amount: number) {
    if (this.availableVacations <= Amount) {
      this.actuallProc.mount = Amount;
    } else {
      this.actuallProc.mount = this.availableVacations;
    }
  }
}


