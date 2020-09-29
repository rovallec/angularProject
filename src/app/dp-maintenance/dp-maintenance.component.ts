import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { disciplinary_processes } from '../process_templates';

@Component({
  selector: 'app-dp-maintenance',
  templateUrl: './dp-maintenance.component.html',
  styleUrls: ['./dp-maintenance.component.css']
})

export class DpMaintenanceComponent implements OnInit {

  dps:disciplinary_processes[] = [];
  dps_ch:disciplinary_processes[] = [];
  eval:boolean = false;

  constructor(private apiService:ApiService) { }

  ngOnInit() {
  }

  getActive(){
    this.apiService.getDisciplinaryProcesses({id:'active'}).subscribe((dp:disciplinary_processes[])=>{
      this.dps = dp;
    })
  }

  getAll(){
    this.apiService.getDisciplinaryProcesses({id:'all'}).subscribe((dp:disciplinary_processes[])=>{
      this.dps = dp;
    })
  }

  setEvaluation(){
    this.apiService.getDisciplinaryProcesses({id:'all'}).subscribe((dp:disciplinary_processes[])=>{
      this.dps = dp;
      this.dps.forEach(el => {
        switch (el.cathegory) {
          case '1*No adherirse a su horario por mas de 15% del tiempo total estipulado de trabajo.' && 'Asistencia':
            
            break;
        
          default:
            break;
        }
      });
    })
  }

}
