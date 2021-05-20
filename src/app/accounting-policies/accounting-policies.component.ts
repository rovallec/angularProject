import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, hrProcess } from '../fullProcess';
import { AccountingAccounts, accountingPolicies, accounts, attendences, attendences_adjustment, clients, disciplinary_processes, leaves, ot_manage, paid_attendances, payments, payroll_resume, payroll_values_gt, periods, terminations, vacations, policies } from '../process_templates';

@Component({
  selector: 'app-accounting-policies',
  templateUrl: './accounting-policies.component.html',
  styleUrls: ['./accounting-policies.component.css']
})
export class AccountingPoliciesComponent implements OnInit {

  constructor(public apiServices: ApiService, public route: ActivatedRoute, public authService:AuthServiceService) { }

  periods: periods[] = [];
  actualPeriod: periods = new periods;
  clients: clients[] = [];
  selectedClient: string = null;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;
  show_attendances: attendences[] = [];
  save_attendances: attendences[] = [];
  saved_paid: paid_attendances[] = [];
  resumes: payroll_resume[] = [];
  finished: boolean = true;
  progress: number = 0;
  max_progress: number = 0;
  step: string = null;
  isSearching:boolean = false;
  years: string[] = [new Date().getFullYear().toString()];
  selectedYear: string = new Date().getFullYear().toString();
  isLoading: boolean = false;
  searchFilter: string = null;
  searchValue: string = null;
  payroll_values: payroll_values_gt[] = [];
  show_payroll_values: payroll_values_gt[] = [];
  close_period: boolean = true;
  adjustments: attendences_adjustment[] = [];
  accountingPolicies: accountingPolicies[] = [];
  arrAccountingAccounts: AccountingAccounts[] = [];
  zero:number = 0.00;
  finalRow: string = null;
  totalDebe: string = null;
  totalHaber: string = null;

  ngOnInit() {
    this.apiServices.getPeriods().subscribe((per: periods[]) => {
      this.periods = per.filter(p => p.status =='0');      
        this.actualPeriod = this.periods[this.periods.length-1];
    });

    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    });
  }

  setActualPeriod() {
    let per: periods[] = [];
    per = this.periods.filter(p => p.idperiods == this.actualPeriod.idperiods);
    if (per.length > 0) {
      this.actualPeriod = per[0];
    }
    this.getAccounting();
  }

  setAccount(acc: accounts) {
    this.isSearching = false;
    this.selectedAccount = acc;
    this.apiServices.getPayroll_resume({ id_account: this.selectedAccount.idaccounts, id_period: this.actualPeriod.idperiods }).subscribe((p: payroll_resume[]) => {
      this.resumes = p;
    })
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc.filter(ac => ac.id_client == cl);
      this.selectedAccount = this.accounts[0];      
    })
  }
  
  setYear() {
    this.apiServices.getPeriods().subscribe((p: periods[]) => {
      this.periods = [];
      p.forEach(pe => {
        if (pe.start.split("-")[0] == this.selectedYear) {
          this.periods.push(pe);
          this.actualPeriod = pe;
          this.actualPeriod.idperiods = pe.idperiods;
        }
      })
      this.isLoading = true;
      //this.setPayments();
    })
  }

  getAccounting() {
    let element: policies = new policies;
    this.accountingPolicies = [];
    this.isLoading = true;
    this.finished = false;
    this.progress = 1;
    this.max_progress = 100;
    this.step = 'Obtaining data.';
    this.finalRow = 'Obtaining data...';
    try {
      element.idperiod = this.actualPeriod.idperiods;
      element.idaccounts = this.selectedAccount.idaccounts;
      element.id_client = this.selectedClient;
      this.apiServices.getAccountingPolicies(element).subscribe((acp: accountingPolicies[]) => {
        this.accountingPolicies = acp;
        for (let index = 0; index < this.accountingPolicies.length; index++) {
          this.accountingPolicies[index].idperiod = this.actualPeriod.idperiods;
          if (this.accountingPolicies[index].clasif == 'D') {
            this.totalDebe = String(Number(this.totalDebe) + Number(this.accountingPolicies[index].amount));
          } else {
            this.totalHaber = String(Number(this.totalHaber) + Number(this.accountingPolicies[index].amount));
          }

        }
        //this.accountingPolicies = acp.filter(ap => ap.id_client == this.selectedClient);
        if (this.accountingPolicies.length==0) {
          this.finalRow = 'No data to display.';
        } else {
          this.finalRow = 'TOTAL ROWS: ' + String(this.accountingPolicies.length);
        }
        this.progress = this.progress + 1;
      })
    }
    finally {
      this.progress = 0;
      this.max_progress = 0;
      this.step = 'Finalizado.';
      this.finished = true;
      this.isLoading = false;
    }
  }

  saveAccounting() {
    let i: number = 0;
    //this.accountingPolicies.forEach(element => {
      this.apiServices.insertAccountingPolicies(this.accountingPolicies).subscribe((str: string) => {
        if (i >= this.accountingPolicies.length) {
          if (String(str).split("|")[0]=='0') {
            window.alert("Accounting policies saved correctly.");
          } else {
            console.log(String(str).split("|")[1]);
            window.alert("Error when recording accounting policies.");
          }
        }
      })
      i++;
    //})
    
  }

}
