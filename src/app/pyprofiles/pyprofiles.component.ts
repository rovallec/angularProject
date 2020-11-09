import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, RouterLinkActive } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { process } from '../process';
import { change_id, process_templates } from '../process_templates';
import { profiles } from '../profiles';

@Component({
  selector: 'app-pyprofiles',
  templateUrl: './pyprofiles.component.html',
  styleUrls: ['./pyprofiles.component.css']
})
export class PyprofilesComponent implements OnInit {

  employee:employees = new employees;
  profile:profiles = new profiles;
  processes_template:process_templates[] = [];
  processRecord:process[] = [];
  activeProc:process_templates = new process_templates;
  newProc:boolean = false;
  newProcess:boolean = false;
  viewRecProd:boolean = false;
  activeChangeID:change_id = new change_id;
  todayDate:string = new Date().getFullYear().toString() + (new Date().getMonth() + 1).toString() + new Date().getDate().toString();

  constructor(public apiService:ApiService, public route:ActivatedRoute, public authUser:AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  setProcess(proc:process_templates){
    this.activeProc = proc;
    this.newProc = true;
    switch (process.name) {
      case 'Client ID Change':
        this.activeChangeID.proc_name = proc.name;
        this.activeChangeID.proc_status = 'PENDING';
        this.activeChangeID.date = this.todayDate;
        this.activeChangeID.id_user = this.authUser.getAuthusr().user_name;
        this.activeChangeID.old_id = this.employee.client_id;
      default:
        break;
    }
  }

  addProcess(){
    this.newProcess = true
  }

  insertProc(){
    switch (this.activeProc.name) {
      case 'Client ID Change':
          this.activeChangeID.id_user = this.authUser.getAuthusr().iduser;
          this.activeChangeID.proc_status = 'COMPLETED';
        break;
    
      default:
        break;
    }
  }

  start(){
    this.apiService.getSearchEmployees({dp:'all', filter:'idemployees', value:this.route.snapshot.paramMap.get('id')}).subscribe((emp:employees[])=>{
      this.employee = emp[0]
      let prof:profiles = new profiles;
      prof.idprofiles = emp[0].id_profile;
      this.apiService.getProfile(prof).subscribe((profile:profiles[])=>{
        this.profile = profile[0];
      })
    })

    this.apiService.getPayrollTemplates().subscribe((temp:process_templates[])=>{
      this.processes_template = temp;
    })
  }


  cancelView(){
    this.newProc = false;
    this.activeProc = new process_templates;
  }

}
