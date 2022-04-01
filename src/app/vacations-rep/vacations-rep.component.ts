import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { Router } from '@angular/router';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';

@Component({
  selector: 'app-vacations-rep',
  templateUrl: './vacations-rep.component.html',
  styleUrls: ['./vacations-rep.component.css']
})
export class VacationsRepComponent implements OnInit {

  constructor(public apiService:ApiService, public authService:AuthServiceService, public route:Router) { }

  filter:string = 'name';
  value:string = null;
  searching:boolean = false;
  employees:employees[] = [];

  ngOnInit() {
    this.start();
  }

  start(){
    this.apiService.getallEmployees({department:'NoLimitAC'}).subscribe((emp:employees[])=>{
      this.employees = emp;
    })
  }

  searchEmployee(){
    this.searching = true;
    this.value = String(this.value).trim();
    this.apiService.getSearchEmployees({ filter: this.filter, value: this.value, dp: this.authService.getAuthusr().department, rol:this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
      this.employees = emp;
    });
  }

  cancelSearch(){
    this.start();
    this.searching = false;
  }

  gotoProfile(employee: employees) {

  }

  async exportVacationsReport() {
    let idemployees: string = '';
    let i: number = 0;
    for (let index = 0; index < this.employees.length; index++) {
      idemployees = idemployees + this.employees[index].idemployees + ',';
    }
    idemployees = idemployees + '0';
    try {
      window.open("http://172.18.2.45/phpscripts/exportVacationsReport.php?employees=" + idemployees, "_blank");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }
}
