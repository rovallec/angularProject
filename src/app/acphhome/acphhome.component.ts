import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { periods } from '../process_templates';

@Component({
  selector: 'app-acphhome',
  templateUrl: './acphhome.component.html',
  styleUrls: ['./acphhome.component.css']
})
export class AcphhomeComponent implements OnInit {

  constructor(public apiServices:ApiService, public router: Router, public authUser:AuthServiceService) { }

  filter:string = 'name';
  value:string = null;
  searching:boolean = false;
  employees:employees[] = [];
  periods:periods[] = [];

  ngOnInit() {
    this.start();
  }

  start(){
    this.apiServices.getallEmployees_ph({department:'all'}).subscribe((emp:employees[])=>{
      this.employees = emp;
    })
    this.apiServices.getPeriods_ph().subscribe((period:periods[])=>{
      this.periods = period;
    })
  }

  searchEmployee(){
    this.searching = true;
    this.apiServices.getFilteredEmployees_ph({filter:this.filter, value:this.value, dp:this.authUser.getAuthusr().department}).subscribe((emp:employees[]) =>{
      this.employees = emp;
    })
  }

  cancelSearch(){
    this.start();
    this.searching = false;
  }

  gotoPeriod(period:periods){
    this.router.navigate(['./periods_ph', period.idperiods]);
  }

  gotoEmployee(emp:employees){
    this.router.navigate(['./accprofiles_ph', emp.idemployees]);
  }
}
