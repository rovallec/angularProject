import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { process } from '../process';
import { card_assignation, process_templates } from '../process_templates';

@Component({
  selector: 'app-fhome',
  templateUrl: './fhome.component.html',
  styleUrls: ['./fhome.component.css']
})
export class FhomeComponent implements OnInit {

  constructor(public apiService:ApiService, public authService:AuthServiceService, public route:Router) { }

  filter:string = null;
  value:string = null;
  searching:boolean = null;
  allEmployees:employees[] = [];

  ngOnInit() {
  }

  gotoProfile(emp:employees){
    this.route.navigate(['./fProfile', emp.idemployees]);
  }

  start(){
    this.apiService.getallEmployees({nm:this.authService.getAuthusr().department}).subscribe((emp:employees[])=>{
      this.allEmployees = emp;
    })
  }

  searchEmployee(){
    this.apiService.getSearchEmployees({filter:this.filter, value:this.value, dp:this.authService.getAuthusr().department}).subscribe((emp:employees[])=>{
      this.allEmployees = emp;
    });
    this.searching = true;
  }

  cancelSearch(){
    this.searching = false;
    this.start();
  }
}
