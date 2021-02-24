import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts, clients, payments, periods } from '../process_templates';

@Component({
  selector: 'app-closing-tk',
  templateUrl: './closing-tk.component.html',
  styleUrls: ['./closing-tk.component.css']
})
export class ClosingTkComponent implements OnInit {

  accounts: accounts[] = [];
  periods: periods[] = [];
  years: string[] = [new Date().getFullYear().toString()];
  selectedAccount: accounts = new accounts;
  selectedClient: string = null;
  clients: clients[] = [];
  selectedPeriod: string = null;
  actualPeriod: periods = new periods;
  selectedYear: string = new Date().getFullYear().toString();
  payments: payments[] = [];

  constructor(public apiServices: ApiService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })

    let i: number = 0;
    this.years = [new Date().getFullYear().toString()];
    this.apiServices.getPeriods().subscribe((p: periods[]) => {
      p.forEach(element => {
        let b: boolean = false;
        this.years.forEach(yr => {
          if (element.start.split("-")[0] == yr) {
            b = true;
          }
        })
        if (!b) {
          this.years.push(element.start.split("-")[0]);
        }
      });
    })

    this.setYear();
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

  setPeriod(p: string) {
    this.periods.forEach(element => {
      if (element.idperiods == p) {
        this.actualPeriod = element;
      }
    })
  }

  setYear() {
    this.apiServices.getPeriods().subscribe((p: periods[]) => {
      this.periods = [];
      p.forEach(pe => {
        if (pe.start.split("-")[0] == this.selectedYear) {
          this.periods.push(pe);
          this.selectedPeriod = pe.idperiods
        }
      })
    })
  }

  setAccount(acc: accounts) {
    if (this.actualPeriod.status == '1') {
      let provitional_period: periods = new periods;
      provitional_period.end = this.actualPeriod.end;
      provitional_period.idperiods = this.actualPeriod.idperiods;
      provitional_period.start = "from_close";
      provitional_period.status = this.actualPeriod.status;
      provitional_period.type_period = this.actualPeriod.type_period;
      this.apiServices.getPayments(provitional_period).subscribe((py:payments[])=>{
        let activeVacation:boolean = false;
        let activeLeave:boolean = false;
        let activeSuspension:boolean = false;
        let non_show:boolean = false;
        let discounted_days:number = 0;
        
        py.forEach(pay=>{
          
        })
      })
    }
  }
}
