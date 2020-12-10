import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { accounts, attendences, clients, ot_manage } from '../process_templates';

@Component({
  selector: 'app-ottracker',
  templateUrl: './ottracker.component.html',
  styleUrls: ['./ottracker.component.css']
})
export class OttrackerComponent implements OnInit {
  accounts:accounts[] = [];
  ots:ot_manage[] = [];
  clients:clients[] = [];
  selectedAccount:accounts = new accounts;
  selectedClient:string = null;
  showAccount:boolean = false;

  constructor(public apiServices:ApiService) { }

  ngOnInit() {
    this.apiServices.getClients().subscribe((cls:clients[])=>{
      this.clients = cls;
      this.selectedClient = this.clients[0].idclients;
      this.setClient(this.clients[0].idclients);
    })
  }

  setSelection(acc:accounts){
    let date: Date = new Date();
    let start: string = null;
    let end: string = null;

    if (date.getDate() > 15) {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '16';
      end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());
    }else{
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '01';
      end = end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + "15";
    }

    this.selectedAccount = acc;
    this.apiServices.getSearchEmployees({filter:"id_account", value: this.selectedAccount.idaccounts, dp:'all'}).subscribe((emp:employees[])=>{
      emp.forEach(employee =>{
        this.apiServices.getAttPeriod({id: employee.idemployees, date_1: start, date_2: end}).subscribe((att:attendences[])=>{
          if(att.length > 0){
            let ot:ot_manage = new ot_manage;
            att.forEach(attendance=>{
              if(attendance.scheduled != 'OFF'){
                ot.amount = (Number(ot.amount) + (Number(attendance.worked_time) - Number(attendance.scheduled))).toFixed(2);
              }else{
                ot.amount = (Number(ot.amount) + Number(attendance.worked_time)).toFixed(2);
              }
            })
            this.ots.push(ot);
          }
        })
      })
      this.showAccount = true;
    })
  }

  setClient(cl:string){
    this.accounts = [];
    this.apiServices.getAcconts().subscribe((acc:accounts[])=>{
      acc.forEach(account => {
        if(account.id_client == cl){
          this.accounts.push(account);
        }
      });
    })
  }

}
