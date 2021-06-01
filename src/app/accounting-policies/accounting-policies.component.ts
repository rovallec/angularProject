import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, hrProcess } from '../fullProcess';
import { AccountingAccounts, accountingPolicies, accounts, attendences, attendences_adjustment, clients, paid_attendances, payroll_resume, payroll_values_gt, periods, policies } from '../process_templates';

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
  close_period: boolean = true;
  adjustments: attendences_adjustment[] = [];
  accountingPolicies: accountingPolicies[] = [];
  arrAccountingAccounts: AccountingAccounts[] = [];
  zero:number = 0.00;
  finalRow: string = null;
  totalDebe: string = null;
  totalHaber: string = null;
  selectedPeriod:string = null;

  ngOnInit() {
    this.apiServices.getPeriods().subscribe((per: periods[]) => {
      this.periods = per.filter(p => p.status =='0');      
        this.actualPeriod = this.periods[this.periods.length-1];
        this.selectedPeriod = this.periods[this.periods.length-1].idperiods;
    });

    this.selectedClient = '-1';
  }

  setActualPeriod(p) {
    this.periods.forEach(element => {
      if (element.idperiods == p) {
        this.actualPeriod = element;
      }
    })
    this.getAccounting();
  }

  setAccount(acc: accounts) {

  }

  setClient(cl: string) {
    this.selectedClient = cl;
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
    })
  }

  getAccounting() {
    let policie: policies = new policies;
    let locacp: accountingPolicies[] = [];
    let accounting: accountingPolicies = new accountingPolicies;
    let external_id: string = '';
    let amount: number = 0;
    this.totalDebe = '0';
    this.totalHaber = '0';
    this.isLoading = true;
    this.finished = false;
    this.progress = 1;
    this.max_progress = 100;
    this.step = 'Obtaining data.';
    this.finalRow = 'Obtaining data...';
    try {
      this.accountingPolicies = [new accountingPolicies];
      policie.idperiod = this.actualPeriod.idperiods;
      policie.idaccounts = this.selectedClient;
      policie.id_client = this.selectedClient;
      this.accountingPolicies.pop();
      this.apiServices.getAccounting_Accounts().subscribe((account: AccountingAccounts[]) =>{
        this.apiServices.getAccountingPolicies(policie).subscribe((acp: accountingPolicies[]) => {
          acp.sort((a, b) => Number(a.external_id) - Number(b.external_id));
          account.sort((a, b) => Number(a.external_id) - Number(b.external_id));
          account.forEach(acc => {
            accounting = this.filterAccounts(acp, acc.external_id, accounting);
            this.accountingPolicies.push(accounting);
            
            /*acp.forEach(lacp => {
              locacp = acp.filter(loc => loc.external_id === acc.external_id);
              if (locacp.external_id == acc.external_id) {
                amount = amount + Number(accounting.amount);
              } else {
                accounting.amount = amount.toFixed();
              
              }
              locacp.forEach(element => {
                accounting = element;
                amount = amount + Number(element.amount);
              })
              accounting.amount = amount.toFixed();
              this.accountingPolicies.push(accounting);
              */
            })
          //})
        })
          //this.accountingPolicies = acp;

          

          for (let index = 0; index < this.accountingPolicies.length; index++) {
            this.accountingPolicies[index].idperiod = this.actualPeriod.idperiods;
            if (this.accountingPolicies[index].clasif == 'D') {
              this.totalDebe = String(Number(this.totalDebe) + Number(this.accountingPolicies[index].amount));
            } else {
              this.totalHaber = String(Number(this.totalHaber) + Number(this.accountingPolicies[index].amount));
            }
          }
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

  filterAccounts(Aaccountingpolicies: accountingPolicies[], Aext_id: string, Aaccounting: accountingPolicies): accountingPolicies {
    let aa: accountingPolicies[] = [];
    let amount: number = 0;
    aa = Aaccountingpolicies.filter(Aap => Aap.external_id == Aext_id)
    Aaccounting = aa[0];
    aa.forEach(Aap => {
      amount = amount + Number(Aap.amount);
    })

    Aaccounting.amount = amount.toString();
    return Aaccounting;
  }

  saveAccounting() {
    let i: number = 0;
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
  }

}
