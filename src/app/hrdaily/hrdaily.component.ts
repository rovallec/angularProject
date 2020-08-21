import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts } from '../process_templates';
import { isNull } from 'util';

@Component({
  selector: 'app-hrdaily',
  templateUrl: './hrdaily.component.html',
  styleUrls: ['./hrdaily.component.css']
})

export class HrdailyComponent implements OnInit {

  accounts:accounts[] = [];
  minDate:string = null;
  todayDate = new Date();
  maxDate:string = this.todayDate.getFullYear().toString() + "-" +  (this.todayDate.getMonth() + 1).toString().padStart(2,"0") + "-" +  this.todayDate.getUTCDate().toString();
  dateFrom:string = null;
  dateTo:string = null;
  accountAdd:string = null;
  exportAccounts:accounts[] = [];
  isExportable:boolean = false;

  constructor(private apiService: ApiService){}

  ngOnInit(){
    this.apiService.getAcconts().subscribe((acc:accounts[])=>{
      this.accounts = acc;
    })
  }

  setFrom(str:string){
    this.minDate = str;
    this.dateFrom = str;
    if(isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0){
      this.isExportable = false;
    }else{
      this.isExportable = true;
    }
  }

  setTo(str:string){
    this.maxDate = str;
    this.dateTo = str;
    if(isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0){
      this.isExportable = false;
    }else{
      this.isExportable = true;
    }
  }

  addAccount(){
    console.log(this.accountAdd);
    let acc: accounts[] = [];

    this.accounts.forEach((item:accounts)=>{  
      if(item.idaccounts != this.accountAdd){
        acc.push(item);
      }else{
        this.exportAccounts.push(item);
      }
    })

    this.accounts = acc;
    if(isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0){
      this.isExportable = false;
    }else{
      this.isExportable = true;
    }
  }

  deleteAccount(ac:accounts){
    let acc:accounts[] = [];
    this.exportAccounts.forEach((itm:accounts)=>{
      if(itm.idaccounts != ac.idaccounts){
        acc.push(itm);
      }else{
        this.accounts.push(itm);
      }
    })
    this.exportAccounts = acc;

    if(isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0){
      this.isExportable = false;
    }else{
      this.isExportable = true;
    }
  }

  generateReprot(){
    if(!this.isExportable){
      alert("Missing information to generate the report");
    }else{
      alert("Yey!");
    }
  }
}
