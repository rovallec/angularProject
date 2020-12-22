import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts } from '../process_templates';

@Component({
  selector: 'app-attendance-report',
  templateUrl: './attendance-report.component.html',
  styleUrls: ['./attendance-report.component.css']
})
export class AttendanceReportComponent implements OnInit {

  selectedAccounts:string[] = [];
  accounts:accounts[] = [];

  constructor(public apiServices:ApiService) { }

  ngOnInit() {
    this.apiServices.getAcconts().subscribe((acc:accounts[])=>{
      this.accounts = acc;
    })
  }

  addSelected(acc:string){
    let newPool:accounts[] = [];
    this.selectedAccounts.push(acc);
    
    this.accounts.forEach(account=>{
      if(account.idaccounts != acc.split(".")[0]){
        newPool.push(account);
      }
    })

    this.accounts = newPool;
  }

  ommit(acc:string){
    let newSelection:string[] = [];
    this.selectedAccounts.forEach(account=>{
      if(account != acc){
        newSelection.push(account);
      }
    })
    this.selectedAccounts = newSelection;

    let newAccount:accounts = new accounts;
    newAccount.idaccounts = acc.split(".")[0];
    newAccount.name = acc.split(".")[1];

    this.accounts.push(newAccount);
  }
}
