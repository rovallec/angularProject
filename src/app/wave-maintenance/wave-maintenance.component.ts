import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { waves_template, schedules, clients, accounts, employeesByWaves, rises,  } from '../process_templates';
import { process } from '../process';
import { AuthServiceService } from '../auth-service.service';
import { isNullOrUndefined } from 'util';
import { users } from '../users';

@Component({
  selector: 'app-wave-maintenance',
  templateUrl: './wave-maintenance.component.html',
  styleUrls: ['./wave-maintenance.component.css']
})
export class WaveMaintenanceComponent implements OnInit {
  waves: waves_template[] = [];
  selectedWave: waves_template = new waves_template;
  schedule: schedules = new schedules;
  clients: clients[] = [];
  selectedClient: string = null;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;
  employees: employeesByWaves[] = [];
  all: string = 'IGNORE';
  actualRise: rises = new rises;
  actuallProc: process = new process;
  todayDate: string = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");
  transfer_newCode: string = '';
  approvals: users[] = [new users];

  activeEmp: string = null;
  viewRecProd: boolean = false;

  constructor(public apiServices: ApiService, public router: Router, public authUser: AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    });

    this.apiServices.getApprovers().subscribe((usrs: users[]) => {
      this.approvals = usrs;
    });
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.selectedAccount = this.accounts[0];
    })
  }

  setAccount(acc) {
    this.selectedAccount = acc;
    
    this.apiServices.getWaves().subscribe((wave: waves_template[]) => {
      this.waves = [];
      this.setWave(wave[0]);
      wave.forEach(w => {
        if (w.id_account == this.selectedAccount.idaccounts) {
          this.waves.push(w);
        }
      })
    })
  }

  setWave(sw) {
    this.apiServices.getEmployeeByWave(sw).subscribe((emp: employeesByWaves[]) => {
      this.employees = emp;
      this.employees.forEach(emp=>{
        emp.action = 'IGNORE';
      })
      this.setProcess();
    });
    this.selectedWave = sw;
  }

  setAll(value) {
    this.employees.forEach(emp=>{
      if (value == 'IGNORE') {
        emp.action = 'IGNORE';
      } else {
        emp.action = 'APPLY';
      }
      
    })
  }

  setProcess() {
    this.actuallProc.descritpion = null;
    this.actuallProc = new process;
    this.actuallProc.prc_date = this.todayDate;
    this.actuallProc.status = "CLOSED";
    this.actuallProc.user_name = this.authUser.getAuthusr().user_name;
    this.actuallProc.id_user = this.authUser.getAuthusr().iduser;
    this.actuallProc.id_profile = this.employees[0].id_profile;
    this.transfer_newCode = this.employees[0].nearsol_id;
    this.actualRise = new rises;
    this.actualRise.new_position = this.employees[0].job;
    this.actualRise.old_position = this.employees[0].job;
  }

  insertProc() {
    this.employees.forEach(employee => {
      
      if (employee.action=='APPLY') {
        this.apiServices.getEmployeeId({ id: employee.id_profile }).subscribe((emp: employees) => {
          this.actuallProc.prc_date = this.todayDate;
          this.actuallProc.status = "CLOSED";
          this.actuallProc.user_name = this.authUser.getAuthusr().user_name;
          this.actuallProc.id_user = this.authUser.getAuthusr().iduser;
          this.actuallProc.id_profile = emp.idemployees;
          this.actuallProc.idprocesses = '11';
          this.transfer_newCode = employee.nearsol_id;
          this.actualRise.new_position = emp.job;
          this.actualRise.old_position = emp.job;
          this.apiServices.insertProc(this.actuallProc).subscribe((str: string) => {
            let end: boolean = false;
            this.actualRise.id_process = str;
            this.actualRise.id_employee = this.actuallProc.id_profile;
            this.actualRise.old_salary = (Number(emp.productivity_payment) + Number(emp.base_payment)).toFixed(2);
            this.actualRise.new_productivity_payment = (Number(this.actualRise.new_salary) - Number(emp.base_payment)).toFixed(2);
            console.log(this.actualRise);
            try {
              if (isNullOrUndefined(this.actualRise.approved_date) || isNullOrUndefined(this.actualRise.approved_by) ||
                isNullOrUndefined(this.actualRise.effective_date) || isNullOrUndefined(this.actualRise.trial_start) || isNullOrUndefined(this.actualRise.trial_end)) {
                  let message: string = '';
                  message = 'Aproved Date: ' + isNullOrUndefined(this.actualRise.approved_date) + ' Approved by: ' + isNullOrUndefined(this.actualRise.approved_by) + 
                            ' Effective_Date: ' + isNullOrUndefined(this.actualRise.effective_date) + 
                            ' Trial_start: ' + isNullOrUndefined(this.actualRise.trial_start) + ' trial_end: ' + isNullOrUndefined(this.actualRise.effective_date);
                throw new Error('Incomplete data. ' + message);
              } else {
                end = false;
              }
            } catch (error) {
              window.alert(error);
              end = true;
            }

            if (!end) {
              this.apiServices.insertRise(this.actualRise).subscribe((str: string) => {
                if (str.split("|")[0] == "1") {
                  window.alert("Action successfuly recorded.");
                  
                } else {
                  window.alert("An error has occured:\n" + str.split("|")[1]);
                }
              })
            }
          })
        })
      }
    })
  }

  setApprovalDate(str: string) {
    this.actualRise.approved_date = str;
    console.log(this.actualRise.approved_date + ' info enviada: ' + str);
  }

  setEffectiveDate(str: string) {
    this.actualRise.effective_date = str;
    console.log(this.actualRise.effective_date + ' info enviada: ' + str);
  }

  setTrialStart(str: string) {
    this.actualRise.trial_start = str;
    console.log(this.actualRise.trial_start + ' info enviada: ' + str);
  }

  setTrialEnd(str: string) {
    this.actualRise.trial_end = str;
    console.log(this.actualRise.trial_end + ' info enviada: ' + str);
  }
}
