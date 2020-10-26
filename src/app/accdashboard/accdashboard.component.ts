import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { waves_template, schedules, hires_template, periods } from '../process_templates'
import { employees, payment_methods } from '../fullProcess';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';

@Component({
  selector: 'app-accdashboard',
  templateUrl: './accdashboard.component.html',
  styleUrls: ['./accdashboard.component.css']
})
export class AccdashboardComponent implements OnInit {

  waves: waves_template[] = [new waves_template];
  wave_ToEdit: waves_template = new waves_template;
  schedules: schedules[] = [new schedules];
  schedule_to_edit: schedules = new schedules;
  hires: hires_template[] = [new hires_template];
  employees: employees[] = [];
  sch_hrs_st: string;
  sch_min_st: string;
  sch_hrs_e: string;
  sch_min_e: string;
  includeAll: boolean = false;
  periods: periods[] = [];
  filter: string = null;
  value: string = null;
  searching: boolean = false;
  edit_bank: boolean = false;

  constructor(private apiService: ApiService, public router: Router, private authSrv: AuthServiceService) { }

  ngOnInit() {
    this.getWavesAll();
    this.getPeriods();
    this.getAllEmployees();
  }

  getPeriods() {
    this.apiService.getPeriods().subscribe((prd: periods[]) => {
      this.periods = prd;
    });
  }

  getAllEmployees() {
    this.apiService.getallEmployees({ department: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
  }

  searchEmployee() {
    this.searching = true;
    this.apiService.getSearchEmployees({ filter: this.filter, value: this.value, dp: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
  }

  gotoProfile(emp: employees) {
    this.router.navigate(['./accProfile', emp.idemployees]);
  }

  cancelSearch() {
    this.getWavesAll();
    this.getPeriods();
    this.getAllEmployees();
    this.searching = false;

  }

  gotoPeriod(id: string) {
    this.router.navigate(['./periods', id]);
  }

  editBank() {
    this.edit_bank = true;
  }

  finishEdit() {
    this.hires.forEach(hire => {
      if (hire.status == 'EMPLOYEE') {
        this.apiService.updateBank(hire).subscribe((str: string) => {
        });
        this.hideSchedules();
        this.getWavesAll();
        this.getPeriods();
        this.getAllEmployees();
      }
    })
  }

  completeWave(){
    this.hires.forEach(hire=>{
      if(hire.status == 'EMPLOYEE'){
        let paymentMethod:payment_methods = new payment_methods;
        paymentMethod.id_employee = hire.idemployees;
        paymentMethod.bank = hire.bank;
        paymentMethod.number = hire.account;
        paymentMethod.predeterm = '1';
        this.apiService.insertPaymentMethod(paymentMethod).subscribe((str:string)=>{
        })
      }
    })
    this.hideSchedules();
    this.getWavesAll();
    this.getPeriods();
    this.getAllEmployees();
  }

  getWavesAll() {
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate() + " AND (`state` = 0 OR `state` = '2')";
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      this.waves = readWaves;
      this.wave_ToEdit = readWaves[0];
    })
  }

  editW(wv:waves_template){
      this.apiService.updateWaveState(wv).subscribe((st:string)=>{
        this.hideSchedules();
        this.getWavesAll();
        this.getPeriods();
        this.getAllEmployees();
      });
  }


  getSchedules(wv: waves_template) {
    this.waves.forEach(wa => {
      wa.show = '0';
    });
    this.apiService.getFilteredHires(wv).subscribe((hires: hires_template[]) => {
      this.hires = hires
    });
    this.waves[this.waves.indexOf(wv)].show = "1";
  }

  hideSchedules() {
    this.waves.forEach(w => {
      w.show = '0';
    })
  }

  cancelEdit() {
    this.edit_bank = false;
    this.hideSchedules();
    this.getWavesAll();
    this.getPeriods();
    this.getAllEmployees();
  }
}
