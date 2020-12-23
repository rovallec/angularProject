import { ThrowStmt } from '@angular/compiler';
import { Component, OnInit } from '@angular/core';
import { stringify } from 'querystring';
import { isNull } from 'util';
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
  get:boolean = false;
  lastSelection:string = null;
  fromDate:string = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + (new Date().getDate() - 1);
  toDate:string = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();

  constructor(public apiServices:ApiService) { }

  ngOnInit() {
    this.apiServices.getAcconts().subscribe((acc:accounts[])=>{
      this.accounts = acc;
    })
  }

  addSelected(acc:string){
    let newPool:accounts[] = [];
    this.selectedAccounts.push(acc);
    this.lastSelection = acc;
    
    this.accounts.forEach(account=>{
      if(account.idaccounts != acc.split(".")[0]){
        newPool.push(account);
      }
    })

    this.accounts = newPool;
    if((new Date(this.toDate).getTime()) >= (new Date(this.fromDate).getTime()) && this.selectedAccounts.length > 0){
      this.get = true;
    }else{
      this.get = false;
    }
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
    if((new Date(this.toDate).getTime()) >= (new Date(this.fromDate).getTime()) && this.selectedAccounts.length > 0){
      this.get = true;
    }else{
      this.get = false;
    }
  }

  changeFrom(str:string){
    this.fromDate = str;
    if((new Date(this.toDate).getTime()) >= (new Date(this.fromDate).getTime()) && this.selectedAccounts.length > 0){
      this.get = true;
    }else{
      this.get = false;
    }
  }

  changeTo(str:string){
    this.toDate = str;
    if((new Date(this.toDate).getTime()) >= (new Date(this.fromDate).getTime()) && this.selectedAccounts.length > 0){
      this.get = true;
    }else{
      this.get = false;
    }
  }

  getReport(){
    let acc:string = null;
    this.selectedAccounts.forEach(str=> {
      if(!isNull(acc)){
        acc = str.split(".")[0] + "," + acc;
      }else{
        acc = str.split(".")[0];
      }
    })
    window.open("http://200.94.251.67/phpscripts/exportAtt.php?from=" + this.fromDate + "&to=" + this.toDate + "&acc=" + acc, "_blank");
  }
}
