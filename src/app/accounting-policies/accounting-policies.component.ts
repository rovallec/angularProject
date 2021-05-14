import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, hrProcess } from '../fullProcess';
import { AccountingAccounts, accountingPolicies, accounts, attendences, attendences_adjustment, clients, disciplinary_processes, leaves, ot_manage, paid_attendances, payments, payroll_resume, payroll_values_gt, periods, terminations, vacations } from '../process_templates';

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
    //this.setPayments();
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc.filter(ac => ac.id_client == cl);
      this.selectedAccount = this.accounts[0];
      //this.setPayments();
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
    let element: AccountingAccounts = new AccountingAccounts;
    this.accountingPolicies = [];
    this.isLoading = true;
    this.progress = 1;
    this.max_progress = 100;
    this.step = 'Obteniendo Pólizas.';    
    try {
      element.idperiod = this.actualPeriod.idperiods;
      this.apiServices.getAccountingPolicies(element).subscribe((acp: accountingPolicies[]) => {
        this.accountingPolicies = acp;
        //this.accountingPolicies.push.apply(acp);
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

}
