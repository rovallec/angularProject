import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { waves_template, hires_template, attendences } from '../process_templates';
import { employees, hrProcess } from '../fullProcess';
import { isUndefined, isNull } from 'util';
import { AuthServiceService } from '../auth-service.service'
import { Router } from '@angular/router';

@Component({
  selector: 'app-hrhome',
  templateUrl: './hrhome.component.html',
  styleUrls: ['./hrhome.component.css']
})
export class HrhomeComponent implements OnInit {

  showWaveDetails:boolean = false;
  showEmployeeDetails:boolean[] = [];
  showAttendenceDetails:boolean[] = [];
  makeEmployee:boolean = false;
  toggleDate:boolean = false;
  searching:boolean = false;
  base_payment:string;
  
  filter:string = null;
  value:string = null;

  allEmployees:employees[] = [new employees];
  allProcesses:hrProcess[] = [new hrProcess];

  weekdays:string[] = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday"
  ]

  wavesToShow:waves_template[] = [new waves_template];
  hiresToShow:hires_template[] = [new hires_template];
  emplAttendences:attendences[] = [];

  editWave:boolean[] = [];

  stringDate:string = new Date().getFullYear() + "-" + (new Date().getMonth() + 1).toString().padStart(2,"0") + "-" + new Date().getDate().toString().padStart(2, "0");

  weekday:string;

  constructor(private authService: AuthServiceService, private apiService:ApiService, private route:Router) { }

  ngOnInit() {
    this.start();
  }

  start(){
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate() + " AND `state` = 0";
    this.apiService.getfilteredWaves({str: date}).subscribe((wv:waves_template[])=>{
      this.wavesToShow = wv;
    })
    for (let i = 0; i < this.wavesToShow.length; i++) {
      this.showEmployeeDetails.push(false);
      this.showAttendenceDetails.push(false);
      this.editWave.push(false);
    }
    this.weekday = this.weekdays[new Date().getDay()];
    
    this.apiService.getallEmployees().subscribe((emp:employees[])=>{
      this.allEmployees = emp;
    });

    this.apiService.getallHrProcesses().subscribe((proc:hrProcess[])=>{
      this.allProcesses = proc;
    })

  }

  gotoProfile(emp:employees){
    this.route.navigate(['./hrprofiles', emp.id_profile]);
  }

  showWave(wv:waves_template){
    this.hideEmployees();
    this.wavesToShow.forEach(element => {
      element.show = '0';
    });
    this.hideAt();
    this.wavesToShow[this.wavesToShow.indexOf(wv)].show = '1';
  }

  hideWave(){
    this.wavesToShow.forEach(element => {
      element.show = '0';
    });
    this.hideEmployees();
    this.hideAt();
  }

  editW(wv:waves_template){
    if(this.editWave[this.wavesToShow.indexOf(wv)]){
      this.apiService.updateWaveState(wv).subscribe((st:string)=>{
      });
      this.start();
    }
    this.editWave[this.wavesToShow.indexOf(wv)] = !this.editWave[this.wavesToShow.indexOf(wv)];
  }

  getWvView(wv:waves_template){
    return this.editWave[this.wavesToShow.indexOf(wv)]
  }

  showEmployees(wv:waves_template){
    this.showEmployeeDetails[this.wavesToShow.indexOf(wv)] = true;
    this.apiService.getHiresAsEmployees(wv).subscribe((res:hires_template[])=>{
      this.hiresToShow = res;
    })

  }

  showEmployeesAt(wv:waves_template){
    
    this.showAttendenceDetails[this.wavesToShow.indexOf(wv)] = true;
    this.apiService.getAttendences({id:wv.idwaves, date:this.stringDate}).subscribe((rs:attendences[])=>{
      this.emplAttendences = rs;
    })
  }

  getDef(att:attendences){
    return isNull(att.idattendences);
  }

  hideEmployees(){
    for (let i = 0; i < this.showEmployeeDetails.length; i++) {
      this.showEmployeeDetails[i] = false;
    }
  }

  hideAt(){
    for (let i = 0; i < this.showAttendenceDetails.length; i++) {
      this.showAttendenceDetails[i] = false;
    }
  }

  getShow(wv:waves_template){
    return this.showEmployeeDetails[this.wavesToShow.indexOf(wv)]
  }

  editEmployee(){
    this.makeEmployee = true;
  }

  cancelEmployeeEdit(wv:waves_template){
    this.makeEmployee = false;
    this.showEmployees(wv);
  }

  setEmployees(wv:waves_template){
    let employees_col:employees[] = [];
    let actual_emp:employees;
    this.base_payment = wv.base_payment;
    actual_emp = new employees();
    this.hiresToShow.forEach(hire => {
      if(!isUndefined(hire.client_id) && hire.status == "EMPLOYEE"){
        actual_emp = new employees();
        actual_emp.id_hire = hire.idhires;
        actual_emp.id_account = wv.id_account;
        actual_emp.reporter = hire.reporter;
        actual_emp.client_id = hire.client_id;
        actual_emp.hiring_date = wv.ops_start;
        actual_emp.job = wv.job;
        actual_emp.base_payment = this.base_payment;
        actual_emp.id_user = this.authService.getAuthusr().iduser;
        actual_emp.id_department = this.authService.getAuthusr().department
        actual_emp.state = "EMPLOYEE";
        actual_emp.productivity_payment = wv.productivity_payment;
        employees_col.push(actual_emp);
        console.log(actual_emp);
      }
    });

    this.apiService.insertEmployees(employees_col).subscribe((str:string)=>{
    });
    this.cancelEmployeeEdit(wv);
    this.getShow(wv);
  }

  changeDate(a:string, wv:waves_template){
    this.stringDate = a;
    var dt = new Date(a.split('-')[0] + "-" + a.split('-')[1] + '-' + (Number.parseInt(a.split('-')[2]) + 1).toString());
    this.weekday = this.weekdays[dt.getDay()];
    this.emplAttendences.forEach(emp => {
      if(emp.day_off1 !== this.weekday && emp.day_off2 !== this.weekday){
        emp.scheduled = '8';
      }else{
        emp.scheduled = '0';
      }
    });
    this.showEmployeesAt(wv);
    this.toggleDate = false;
  }

  editDate(){
    this.toggleDate = !this.toggleDate;
  }


  insertAttendence(){
    var insertAttendences:attendences[] = [];
    this.emplAttendences.forEach(emp => {
      if(isNull(emp.idattendences)){
        emp.date = this.stringDate;
        if(emp.day_off1 !== this.weekday && emp.day_off2 !== this.weekday){
          emp.scheduled = '8';
        }else{
          emp.scheduled = '0';
        }
        insertAttendences.push(emp);
      }
    });
    this.apiService.insertAttendences(insertAttendences).subscribe((at:attendences[])=>{
      this.emplAttendences = at;
    })
  }

  searchEmployee(){
    this.apiService.getSearchEmployees({filter:this.filter, value:this.value}).subscribe((emp:employees[])=>{
      this.allEmployees = emp;
    });
    this.searching = true;
  }

  cancelSearch(){
    this.searching = false;
    this.start();
  }
}
