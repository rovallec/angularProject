import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, hrProcess } from '../fullProcess';
import { AccountingAccounts, accountingPolicies, accounts, attendences, attendences_adjustment, clients, Fecha, paid_attendances, payroll_resume, periods, policies, policyHeader, selectedOption } from '../process_templates';

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
  header: policyHeader = new policyHeader;
  policeType: selectedOption;
  policeTypes: selectedOption[] = [];



  ngOnInit() {
    let today: Fecha = new Fecha;
    let pt: selectedOption = new selectedOption;
    this.apiServices.getPeriods().subscribe((per: periods[]) => {
      this.periods = per.filter(p => p.status =='0');      
        this.actualPeriod = this.periods[this.periods.length-1];
        this.selectedPeriod = this.periods[this.periods.length-1].idperiods;
    });

    this.selectedClient = '-1';
    this.header.date = today.getToday();
    this.header.type = '1'; // Las pólizas tipo 1 serán consideradas de costos.
    pt.id = 1;
    pt.description = 'Cost Policy';
    this.policeTypes.push(pt);
    pt = new selectedOption;
    pt.id = 2;
    pt.description = 'Expence Policy';
    this.policeTypes.push(pt);
  }

  setActualPeriod(p) {
    this.periods.forEach(element => {
      if (element.idperiods == p) {
        this.actualPeriod = element;
        this.header.id_period = this.actualPeriod.idperiods;
      }
    })
    this.getAccounting();
  }

  setHeaderType(t) {
    this.policeTypes.forEach(element => {
      if (element.id == t) {
        this.policeType = element;
        this.policeType.id = t;
      }
    })
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
    let accounting: accountingPolicies = new accountingPolicies;
    let filteredap: accountingPolicies[] = [];
    this.totalDebe = '0';
    this.totalHaber = '0';
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
            filteredap = this.accountingPolicies.filter(count => count.external_id == accounting.external_id);
            if (filteredap.length == 0) {
              this.accountingPolicies.push(accounting);
            }
          })
          if (this.accountingPolicies.length==0) {
            this.finalRow = 'No data to display.';
          } else {
            this.finalRow = 'TOTAL ROWS: ' + String(this.accountingPolicies.length);
          }  
          for (let index = 0; index < this.accountingPolicies.length; index++) {
            this.accountingPolicies[index].idperiod = this.actualPeriod.idperiods;
            if (this.accountingPolicies[index].clasif == 'D') {
              this.totalDebe = String(Number(this.totalDebe) + Number(this.accountingPolicies[index].amount));
            } else {
              this.totalHaber = String(Number(this.totalHaber) + Number(this.accountingPolicies[index].amount));
            }
          }
        })
      })
    }
    finally {
      // no hacer nada por el momento.
    }
  }

  filterAccounts(Aaccountingpolicies: accountingPolicies[], Aext_id: string, Aaccounting: accountingPolicies): accountingPolicies {
    let aa: accountingPolicies[] = [];
    let acP: accountingPolicies = Aaccounting;
    let amount: number = 0;
    aa = Aaccountingpolicies.filter(Aap => Aap.external_id == Aext_id)
    if (!isNullOrUndefined(aa[0])) {
      acP = aa[0];
    } else {
      amount = 0;
    }
    
    aa.forEach(Aap => {
      amount = amount + Number(Aap.amount);
    })

    acP.amount = amount.toFixed(2);
    return acP;
  }

  saveAccounting() {
    let i: number = 0;
    this.header.detail = this.accountingPolicies;
    this.apiServices.insertAccountingPolicies(this.header).subscribe((str: string) => {
      if (String(str).split("|")[0]=='0') {
        this.header.correlative = String(str).split("|")[2];
        console.log("Accounting policies saved correctly.");
        window.alert("Accounting policies saved correctly.");
      } else {
        console.log(String(str).split("|")[1]);
        window.alert("Error when recording accounting policies.");
      }
    })
  }
}
