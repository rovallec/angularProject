import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, hrProcess } from '../fullProcess';
import { process } from '../process';
import { attendance_accounts, attendences, hires_template, schedules, terminations, waves_template } from '../process_templates';
import { profiles } from '../profiles';

@Component({
  selector: 'app-pyhome',
  templateUrl: './pyhome.component.html',
  styleUrls: ['./pyhome.component.css']
})
export class PyhomeComponent implements OnInit {


  waves: waves_template[] = [new waves_template];
  wave_ToEdit: waves_template = new waves_template;
  schedules: schedules[] = [new schedules];
  accounts: attendance_accounts[] = [];
  schedule_to_edit: schedules = new schedules;
  hires: hires_template[] = [];
  employees: employees[] = [];
  pointedAtt: employees[] = [];
  transfers: process[] = [];
  profilesTransfer: employees[] = [];
  profilesTerm: employees[] = [];
  overlaps: employees[] = [];
  filter: string = null;
  value: string = null;
  searching: boolean = false;
  editWave: boolean = false;

  constructor(public apiService: ApiService, public route: Router, public authService: AuthServiceService) { }

  ngOnInit() {
    this.getWavesAll();
    this.getAllEmployees();
    this.getAttAccounts();
    this.getTransfers();
    this.getTerminations();
    this.getOverlaps();
  }

  getOverlaps() {
    let date: Date = new Date();
    let start: string = null;
    let end: string = null;
    start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString().padStart(2,"0") + "-" + '01';
    end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString().padStart(2,"0") + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());

    this.apiService.getOverlaps({ start: start, end: end }).subscribe((emp: employees[]) => {
      this.overlaps = emp;
    })
  }

  getAttAccounts() {
    let date: Date = new Date();
    let start: string = null;
    let end: string = null;
    if (date.getDate() > 15) {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '16';
      end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());
    } else {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '01';
      end = end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + "15";
    }

    this.apiService.getAttAccounts({ start: start, end: end }).subscribe((acc: attendance_accounts[]) => {
      acc.forEach(accoun => {
        if (accoun.max > accoun.value) {
          this.accounts.push(accoun);
        }
      })
    })
  }

  getAllEmployees() {
    this.apiService.getallEmployees({ department: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
  }

  gotoProfile(emp: employees) {
    this.route.navigate(['./pyprofiles', emp.idemployees]);
  }


  getWavesAll() {
    this.waves = [];
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      readWaves.forEach(ww => {
        ww.state = ww.state.split(",")[2];
        if (ww.state === '0') {
          this.waves.push(ww);
        }
      })
      this.wave_ToEdit = readWaves[0];
    })
  }

  searchEmployee() {
    this.apiService.getSearchEmployees({ filter: this.filter, value: this.value, dp: this.authService.getAuthusr().department, rol:this.authService.getAuthusr().id_role}).subscribe((emp: employees[]) => {
      this.employees = emp;
    });
    this.searching = true;
  }

  cancelSearch() {

  }

  saveEmployees() {
    this.hires.forEach(hire => {
      if (hire.client_id.length > 0) {
        this.apiService.getSearchEmployees({ dp: 'all', filter: 'nearsol_id', value: hire.nearsol_id, rol:this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
          if ((emp[0].client_id != hire.client_id) || (emp[0].society != hire.society)) {
            emp[0].platform = hire.client_id;
            emp[0].society = hire.society;
            this.apiService.updateEmployee(emp[0]).subscribe((str: string) => {
              this.hideSchedules();
            });
          }
        })
      }
    })
  }

  setWave(wave: waves_template) {
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      readWaves.forEach(ww => {
        if (ww.idwaves == wave.idwaves) {
          wave.state = ww.state.split(",")[0] + "," + ww.state.split(",")[1] + "," + wave.state + "," + ww.state.split(",")[3]
        }
      })
      this.apiService.updateWaveState(wave).subscribe((str: string) => {
        this.getWavesAll();
        this.getAllEmployees();
      })
    })
  }

  getSchedules(wv: waves_template) {
    this.hires = [];
    this.waves.forEach(wa => {
      wa.show = '0';
    });
    this.apiService.getHiresAsEmployees(wv).subscribe((hires: hires_template[]) => {
      this.hires = hires;
    })
    this.waves[this.waves.indexOf(wv)].show = "1";
  }

  hideSchedules() {
    this.waves.forEach(wv => {
      wv.show = '0';
    })
  }

  showAccount(acc: attendance_accounts) {
    this.apiService.getAttMissing({ date: acc.date, account: acc.idaccounts }).subscribe((att: employees[]) => {
      this.pointedAtt = att;
    })
    this.accounts[this.accounts.indexOf(acc)].show = '1';
  }

  hideAccounts() {
    this.accounts.forEach(element => {
      element.show = '0';
    });
  }

  getTransfers() {
    this.transfers = [];
    this.apiService.getProcRecorded({ id: 'all' }).subscribe((proc: process[]) => {
      proc.forEach(proc => {
        if (proc.name == "Transfer") {
          this.transfers.push(proc)
        }
      })

      this.transfers.forEach(trans => {
        this.apiService.getSearchEmployees({ filter: 'idemployees', value: trans.id_profile, dp: '4', rol:this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
          emp[0].platform = trans.prc_date;
          this.profilesTransfer.push(emp[0]);
        })
      })
    })
  }

  getTerminations() {
    let term: process[] = [];
    let terminated: terminations[] = [];
    let date: Date = new Date();
    let start: string = null;
    let end: string = null;

    if (date.getDate() > 15) {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '16';
      end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());
    } else {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '01';
      end = end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + "15";
    }

    this.apiService.getProcRecorded({ id: 'all' }).subscribe((proc: process[]) => {
      proc.forEach(proc => {
        if (proc.name == "Termination" && new Date(proc.prc_date) < new Date(end) && new Date(proc.prc_date) > new Date(start)) {
          this.apiService.getTerm(proc).subscribe((termed: terminations) => {
            this.apiService.getSearchEmployees({ filter: 'idemployees', value: proc.id_profile, dp: '4', rol:this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
              emp[0].platform = "NO DATE SET";
              if (!isNullOrUndefined(termed.valid_from)) {
                emp[0].platform = termed.valid_from;
              }
              this.profilesTerm.push(emp[0]);
            })
          })
        }
      })
    })
  }
}
