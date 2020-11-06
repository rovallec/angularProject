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
  activeProc:process_templates = new process_templates;
  newProc:boolean = false;
  activeChangeID:change_id = new change_id;

  constructor(public apiService:ApiService, public route:ActivatedRoute, public authUser:AuthServiceService) { }

  ngOnInit() {
    this.start();
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

  setTemplate(process:process_templates){
    switch (process.name) {
      case "Access Card Assignation":
        this.activeProc = process;
    
      default:
        break;
    }
    this.newProc = true;
  }

  cancelView(){
    this.newProc = false;
    this.activeProc = new process_templates;
  }

}