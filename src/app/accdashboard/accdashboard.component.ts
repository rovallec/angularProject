import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { waves_template, schedules, hires_template, periods } from '../process_templates'
import { employees, payment_methods } from '../fullProcess';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { isNull, isNullOrUndefined } from 'util';

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
  hires: hires_template[] = [];
  employees: employees[] = [];
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
      }
      this.hideSchedules();
      this.getWavesAll();
      this.getPeriods();
      this.getAllEmployees();
    })
  }

  completeWave() {
    this.hires.forEach(hire => {
      if (hire.status == 'EMPLOYEE') {
        if (parseFloat(hire.account) > 0) {
          let paymentMethod: payment_methods = new payment_methods;
          this.apiService.getSearchEmployees({ filter: 'id_profile', value: hire.id_profile, dp: 'all' }).subscribe((emp: employees[]) => {
            paymentMethod.type = "BANK ACCOUNT";
            paymentMethod.bank = hire.bank;
            paymentMethod.number = hire.account;
            paymentMethod.predeterm = '1';
            paymentMethod.id_employee = emp[0].idemployees;
            this.apiService.insertPaymentMethod(paymentMethod).subscribe((str: string) => {
            })
          })
        }
      }
    })
    this.hideSchedules();
    this.getWavesAll();
    this.getPeriods();
    this.getAllEmployees();
    this.cancelEdit();
  }

  getWavesAll() {
    this.waves = [];
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      this.wave_ToEdit = readWaves[0];
      readWaves.forEach(wv=>{
        wv.state = wv.state.split(",")[0];
          if(wv.state == '0'){
            this.waves.push(wv);
          }
        })
      })
  }

  editW(wv: waves_template) {
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      readWaves.forEach(ww=>{
        if(ww.idwaves == wv.idwaves){
          wv.state = wv.state + "," + ww.state.split(",")[1] + "," + ww.state.split(",")[2] + "," + ww.state.split(",")[3];
        }
      })
      this.apiService.updateWaveState(wv).subscribe((st: string) => {
        this.getWavesAll();
        this.getPeriods();
        this.getAllEmployees();
        this.hideSchedules();
      });
    })
  }


  getSchedules(wv: waves_template) {
    this.hires = [];
    this.waves.forEach(wa => {
      wa.show = '0';
    });
    this.apiService.getFilteredHires(wv).subscribe((hires: hires_template[]) => {
      hires.forEach(hire => {
        this.apiService.getSearchEmployees({dp:'all', filter:'id_profile', value:hire.id_profile}).subscribe((emp:employees[])=>{
          this.apiService.getPaymentMethods(emp[0]).subscribe((pym:payment_methods[])=>{
            if(isNull(pym)){
              hire.bool = false;
            }else{
              if(pym.length>0){
                hire.bool = true;
              }else{
                hire.bool = false;
              }
            }
            this.hires.push(hire);
          })
        })
      });
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
