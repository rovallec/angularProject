import { Component, OnInit } from '@angular/core';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { disciplinary_processes } from '../process_templates';

@Component({
  selector: 'app-dp-maintenance',
  templateUrl: './dp-maintenance.component.html',
  styleUrls: ['./dp-maintenance.component.css']
})

export class DpMaintenanceComponent implements OnInit {

  dps: disciplinary_processes[] = [];
  dps_ch: disciplinary_processes[] = [];
  eval: boolean = false;
  working:boolean = false;
  completed:number = 0;
  inactive:number = 0;
  active:number = 0;
  percent:number = 0;

  constructor(private apiService: ApiService) { }

  ngOnInit() {
  }

  getActive() {
    this.active = 0;
    this.inactive = 0;
    this.completed = 0;
    this.eval = false;
    this.apiService.getDisciplinaryProcesses({ id: 'active' }).subscribe((dp: disciplinary_processes[]) => {
      this.dps = dp;
    })
  }

  getAll() {
    this.active = 0;
    this.inactive = 0;
    this.completed = 0;
    this.eval = false;
    this.apiService.getDisciplinaryProcesses({ id: 'all' }).subscribe((dp: disciplinary_processes[]) => {
      this.dps = dp;
    })
  }

  saveChanges(){
    this.working = true;
    this.dps_ch.forEach(element => {
      if(element.status == 'INACTIVE'){
        this.apiService.updateDP(element).subscribe((str:string)=>{
        })
        this.completed = this.completed + 1;
        this.percent = (this.completed/this.inactive) * 100;
      }
    });
    this.getAll();
  }

  setEvaluation() {
    this.eval = true;
    this.apiService.getDisciplinaryProcesses({ id: 'active' }).subscribe((dp: disciplinary_processes[]) => {
      let move: number = 0;
      this.dps = dp;
      this.dps_ch = dp;
      this.dps_ch.forEach(el => {
        switch (el.cathegory) {
          case '1*No adherirse a su horario por mas de 15% del tiempo total estipulado de trabajo.':
            move = 30;
            break;
          case ('2*Abandonar el trabajo en horas de labor, sin causa justificada o sin licencia del patrono.') || ('3*Ingresar tarde.  A su hora de entrada. Todos los empleados deben estar en el trabajo a las horas asignadas segun su horario establecido en el reglamento interior de trabajo.') || ('4*En recesos y almuerzos todos los empleados deben estar a su hora puntual.  Quien se pase de su hora estara cometiendo una falta.') || ('5*Ausentarse sin causa justificada. El trabajador debera presentar  documentacion requerida antes de 48 horas.') || ('6*Cuando el trabajador deje de asistir al trabajo sin permiso del patrono, sin causa justificada, durante 2 días laborales completos y consecutivos o durante 6 medios dias laborales dentro de un mismo mes calendario.') || ('7*Cuando el trabajador deje de asistir a sus labores en un dia festivo (asueto nacional), habiendose comprometido hacerlo a traves del acuerdo firmado en su serie de contratos al iniciar labores, sin una excusa justificada.') || ('19*Incumplimiento del estandard de calidad de satisfaccion al cliente.') || ('20*Cuando un trabajador pida prestado dinero a los demás trabajadores.') || ('82*Cuando el Operador presenta un mal desempeño durante un periodo de un mes, y termina dentro del 5% del ranking de operadores que se destacan por tener mal desempeño. En un lapso de un mes deben mejorar sus estadisticas.'):
            move = 60;
            break;
          case ('59*Accesar o enviar material pornográfico u ofensivo') || ('61*Prestar o transferir el gafete a personas ajenas a la empresa') || ('68*Colgarle a un cliente cuando se determina que fue porque utilizó lenguaje ofensivo, sin haberle advertido como minimo una vez.') || ('69*Colgarle a un cliente de manera deliberada') || ('70*Utilizar claves de acceso o códigos que no le han sido asignados') || ('71*Compartir las contraseñas personales con otro colaborador, poniendo en riesgo la informacion de empresa.'):
            move = 360;
          case '60*No respetar los alimentos de los demás colaboradores':
            move = 0;
            break;
          case ('12*Hablarle al cliente mal de los productos o servicios de la empresa o proporcionar mala informacion causando perjuicio a la empresa.') || ('18*Insultar al cliente causando perjuicio al patrono.') || ('48*Cuando el trabajador se conduzca durante sus labores de forma abiertamente inmoral, o acuda a la injuria, a la calumnia o a las vías de hecho contra su patrono.') || ('49*Cuando el trabajador cometa alguno de los actos enumerados en el inciso anterior , contra algún compañero, durante el tiempo que se ejecuten los trabajos, siempre que como consecuencia de ello se altere gravemente la disciplina y se interrumpan las labores.') || ('50*Cuando el trabajador, fuera del lugar donde se ejecutan las faenas y en horas que no sean de trabajo, acuda a la injuria o la calumnia o a las vías de hecho contra su patrono o contra los representantes de este en la dirección de las labores.') || ('51*Cuando el trabajador cometa algún delito o falta contra la propiedad en perjuicio del patrono o cuando cause intencionalmente un daño material en las máquinas, herramientas, materias primas, productos y demás objetos relacionados en forma inmediata.') || ('52*Cuando el trabajador comprometa con su imprudencia o descuido absolutamente inexcusable, la seguridad del lugar donde se realizan las labores o la de las personas que ahí se encuentran. (por ejemplo activar alarmas, desactivas o retirar dispositivos de seguridad)') || ('53*Cuando el trabajador sufra prisión por sentencia ejecutoriada.') || ('54*Proferir amenazas o tener un comportamiento violento en contra de sus compañeros de trabajo o clientes de la empresa.') || ('55*Cuando el trabajador incurra en cualquier otra falta grave a las obligaciones que le imponga el contrato de trabajo.') || ('56*Acoso sexual') || ('57*Acoso o intimidacion de diferentes indoles que no sean sexuales') || ('58*Insubordinacion o irrespeto a un superior') || ('85*Proporcionar informacion sobre procesos, procedimientos, actividades, o resultados de la empresa, asi como asuntos administrativos reservados cuya divulgacion pueda causar perjuicio a la empresa.') || ('88*Presentar documentacion falsa a Recursos Humanos para la validacion de ausencias.') || ('89*Presentar documentacion alterada a Recursos Humanos para la validacion de ausencias.') || ('90*Realizar reembolsos de pagos y procesarlos nuevamente sin la autorización del cliente o sin que el cliente comprenda expresamente dicha acción.') || ('91*Otorgar creditos y descuentos no autorizados a clientes,  sin autorizacion de su superior.'):
            move = 1;
            break;
          default:
            move = 180;
            break;
        }
        console.log(move);
        let dt:any = new Date(el.imposition_date);
        let da:any = new Date();
        let days = Math.floor((da - dt)/(1000*60*60*24));
        el.id_department = days.toString();
        if(move > 1){
          if(days > move){
            this.inactive = this.inactive + 1;
            el.status = 'INACTIVE';
          }else{
            this.active = this.active + 1;
          }
        }
      });
    })
  }

  printReport(){
    window.open("http://172.18.2.45/phpscripts/mintrab.php", '_blank');
  }

  isNull(val):boolean{
    return (isNullOrUndefined(val));
  }
}
