import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts } from '../process_templates';
import { isNull } from 'util';
import { users } from '../users';
import { AuthServiceService } from '../auth-service.service';

@Component({
  selector: 'app-hrdaily',
  templateUrl: './hrdaily.component.html',
  styleUrls: ['./hrdaily.component.css']
})

export class HrdailyComponent implements OnInit {

  accounts: accounts[] = [];
  minDate: string = null;
  todayDate = new Date();
  maxDate: string = this.todayDate.getFullYear().toString() + "-" + (this.todayDate.getMonth() + 1).toString().padStart(2, "0") + "-" + (this.todayDate.getUTCDate()).toString();
  dateFrom: string = null;
  dateTo: string = null;
  accountAdd: string = null;
  exportAccounts: accounts[] = [];
  isExportable: boolean = false;
  me: users = new users;
  targetStatus:string = "'PENDING'";
  filterList:string[] = [
    'IGSS',
    'WFM Correction',
    'Private Doctor',
    'Birth Certificate',
    'Supervisor Exception',
    'Death Certificate',
    'Closing Exception',
    'Marriage Certificate',
    'VTO Unpaid',
    'Others Unpaid',
    'Others Paid',
    'Maternity',
    'Leave of Absence Unpaid',
    'IGSS Unpaid',
    'IGSS Paid',
    'COVID Unpaid',
    'COVID Paid',
    'VAC'
  ];
  setFilter:string = "'-1'";

  constructor(private apiService: ApiService, private authUsr: AuthServiceService) { }

  ngOnInit() {
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc;
      this.me = this.authUsr.getAuthusr();
      if (this.me.department == '28') {
        this.accountAdd = "g1";
        let ac: accounts = new accounts;
        ac.name = "GET ALL"
        this.exportAccounts.push(ac);
      }
    })
  }

  setFrom(str: string) {
    this.minDate = str;
    this.dateFrom = str;
    if (isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0) {
      this.isExportable = false;
    } else {
      this.isExportable = true;
    }
  }

  setTo(str: string) {
    this.maxDate = str;
    this.dateTo = str;
    if (isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0) {
      this.isExportable = false;
    } else {
      this.isExportable = true;
    }
  }

  addAccount() {
    let acc: accounts[] = [];

    this.accounts.forEach((item: accounts) => {
      if (item.idaccounts != this.accountAdd) {
        acc.push(item);
      } else {
        this.exportAccounts.push(item);
      }
    })

    this.accounts = acc;
    if (isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0) {
      this.isExportable = false;
    } else {
      this.isExportable = true;
    }
  }

  deleteAccount(ac: accounts) {
    let acc: accounts[] = [];
    this.exportAccounts.forEach((itm: accounts) => {
      if (itm.idaccounts != ac.idaccounts) {
        acc.push(itm);
      } else {
        this.accounts.push(itm);
      }
    })
    this.exportAccounts = acc;

    if (isNull(this.dateFrom) || isNull(this.dateTo) || this.exportAccounts.length == 0) {
      this.isExportable = false;
    } else {
      this.isExportable = true;
    }
  }

  async generateReprot() {
    let acId: string = '';
    if (!this.isExportable) {
      alert("Missing information to generate the report");
    } else {
      this.exportAccounts.forEach((itm: accounts) => {
        if (this.exportAccounts.indexOf(itm) != 0) {
          acId = acId + ",";
        }
        acId = acId + itm.idaccounts;
      })
      if (this.me.department == '28' && this.setFilter == "'-1'") {
        try {
          window.open("http://172.18.2.45/phpscripts/exportExceptions_tk.php?start=" + this.dateFrom + "&end=" + this.dateTo + "&state=" + this.targetStatus, "_blank");
        } catch (error) {
          alert("It could not to generate the report.");
        }
        finally {
          await new Promise( resolve => setTimeout(resolve, 1000));
          //new Promise( resolve => setTimeout(resolve, 2000) );
          window.confirm("The report has been generated.");
        }
      } else {
        if(this.me.department == '28'){
          this.accounts.forEach((itm: accounts) => {
            if (this.exportAccounts.indexOf(itm) != 0) {
              acId = acId + ",";
            }
            acId = acId + itm.idaccounts;
          })
        }
        try {
          window.open("http://172.18.2.45/phpscripts/exportHRDaily.php?from=" + this.dateFrom + "&to=" + this.dateTo + "&accounts=" + acId + "&state=" + this.targetStatus + "&filter=" + this.setFilter, "_blank");
        } catch (error) {
          alert("It could not to generate the report.");
        }
        finally {
          await new Promise( resolve => setTimeout(resolve, 1000));
          //new Promise( resolve => setTimeout(resolve, 2000) );
          window.confirm("The report has been generated.");
        }
      }
    }
  }

  changeVal(val){
    if(val.target.checked == true){
      this.targetStatus = "'PENDING', 'DISPATCHED', 'COMPLETED'";
    }else{
      this.targetStatus = "'PENDING'";
    }
  }

  togleFilter(str:string){
    let add:boolean = true;
    let temp:string = "'-1'";
    this.setFilter.split(',').forEach(ss=>{
      if(ss != "'-1'"){
        ss = ss.replace("'", "");
        ss = ss.replace("'", "");
        ss = ss.substr(1, ss.length);
        if(ss.toString() == str.toString()){
          add = false;
        }
      }
    })
    if(add){
      this.setFilter = this.setFilter + ", '" + str + "'";
    }else{
      this.setFilter.split(',').forEach(ss=>{
        if(ss != "'-1'"){
          ss = ss.replace("'", "");
          ss = ss.replace("'", "");
          ss = ss.substr(1, ss.length);
          if(ss != str){
            temp = temp + ", '" + ss + "'";
          }
        }
      })
      this.setFilter = temp;
    }
  }
}
