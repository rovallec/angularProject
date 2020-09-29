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
    
  }

}
