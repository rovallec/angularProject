import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { waves_template, schedules, clients, accounts, employeesByWaves } from '../process_templates';

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

  constructor(public apiServices: ApiService, public router: Router) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })
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
    this.accounts.forEach(ac=>{
      if (acc==ac.idaccounts) {
        this.selectedAccount = acc;
      }
    })
    
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
    this.waves.forEach(w => {
      if (w.idwaves== sw) {
        this.selectedWave = w;
        this.apiServices.getEmployeeByWave(this.selectedWave).subscribe((emp: employeesByWaves[]) => {
          this.employees = emp;
          this.employees.forEach(emp=>{
            emp.action = 'IGNORE';
          })
        });
      }
    })
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
}
