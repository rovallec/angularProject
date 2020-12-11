import { Component, OnInit } from '@angular/core';
import { SSL_OP_SSLEAY_080_CLIENT_DH_BUG } from 'constants';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { accounts, attendences, attendences_adjustment, clients, marginalization, ot_manage } from '../process_templates';
import { users } from '../users';

@Component({
  selector: 'app-ottracker',
  templateUrl: './ottracker.component.html',
  styleUrls: ['./ottracker.component.css']
})
export class OttrackerComponent implements OnInit {
  accounts: accounts[] = [];
  ots: ot_manage[] = [];
  clients: clients[] = [];
  marginalizations: marginalization[] = [];
  approvals: users[] = []
  selectedAccount: accounts = new accounts;
  selectedClient: string = null;
  marginalazing: boolean = false;
  showAccount: boolean = false;
  iduser: string = null;

  constructor(public apiServices: ApiService, public authService: AuthServiceService) { }

  ngOnInit() {
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = this.clients[0].idclients;
      this.setClient(this.clients[0].idclients);
    })
    this.apiServices.getApprovers().subscribe((usrs: users[]) => {
      this.approvals = usrs;
    })
  }

  setSelection(acc: accounts) {
    this.marginalazing = false;
    this.marginalizations = [];
    this.ots = [];
    let date: Date = new Date();
    let start: string = null;
    let end: string = null;

    if (date.getDate() > 15) {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '16';
      end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());
    } else {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '01';
      end = end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + "15";
    }

    this.selectedAccount = acc;
    this.apiServices.getSearchEmployees({ filter: "id_account", value: this.selectedAccount.idaccounts, dp: 'exact' }).subscribe((emp: employees[]) => {
      emp.forEach(employee => {
        this.apiServices.getAttPeriod({ id: employee.idemployees, date_1: start, date_2: end }).subscribe((att: attendences[]) => {
          if (att.length > 0) {
            let ot: ot_manage = new ot_manage;
            ot.id_employee = employee.idemployees;
            ot.name = employee.name;
            ot.nearsol_id = employee.nearsol_id;
            ot.status = employee.client_id;
            att.forEach(attendance => {
              if (attendance.scheduled != 'OFF') {
                ot.amount = (Number(ot.amount) + (Number(attendance.worked_time) - Number(attendance.scheduled))).toFixed(2);
              } else {
                ot.amount = (Number(ot.amount) + Number(attendance.worked_time)).toFixed(2);
              }
            })
            if (Number(ot.amount) > 0) {
              this.ots.push(ot);
            }
          }
        })
      })
      this.showAccount = true;
    })
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAcconts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.setSelection(this.accounts[0]);
    })
  }

  marginalize() {
    this.marginalizations = [];
    let date: Date = new Date();
    let start: string = null;
    let end: string = null;
    let cnt: number = 0;

    if (date.getDate() > 15) {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '16';
      end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());
    } else {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '01';
      end = end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + "15";
    }

    this.ots.forEach(ot => {
      this.apiServices.getAttPeriod({ id: ot.id_employee, date_1: start, date_2: end }).subscribe((att: attendences[]) => {
        cnt = cnt + 1;
        att.forEach(attendance => {
          let marg: marginalization = new marginalization;
          if (attendance.scheduled != 'OFF' && Number(attendance.worked_time) > Number(attendance.scheduled)) {
            marg.before = attendance.worked_time;
            attendance.worked_time = attendance.scheduled;
            marg.id_attendance = attendance.idattendences;
            marg.after = attendance.scheduled;
            marg.approve_by = this.iduser;
            marg.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
            marg.id_user = this.authService.getAuthusr().iduser;
            marg.type = 'OT Reduction';
            marg.value = (Number(marg.before) - Number(marg.after)).toFixed(2);
            marg.idemployees = attendance.id_employee;
            marg.name = ot.name;
            marg.nearsol_id = ot.nearsol_id;
            marg.idmarginalization_details = attendance.client_id;
            this.marginalizations.push(marg);
          } else {
            if (attendance.scheduled == 'OFF' && Number(attendance.worked_time) > 0) {
              marg.before = attendance.worked_time;
              attendance.worked_time = '0.00';
              marg.id_attendance = attendance.idattendences;
              marg.after = '0.00';
              marg.approve_by = this.iduser;
              marg.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
              marg.id_user = this.authService.getAuthusr().iduser;
              marg.type = 'OT Reduction';
              marg.value = (Number(marg.before) - Number(marg.after)).toFixed(2);
              marg.idemployees = attendance.id_employee;
              marg.name = ot.name;
              marg.id_marginalization = attendance.scheduled;
              marg.nearsol_id = ot.nearsol_id;
              this.marginalizations.push(marg);
            }
          }
        })
        if ((this.ots.length - 1) == cnt) {
          this.marginalazing = true;
        }
      })
    })
  }

  saveOTMerge() {
    let count: number = 0;
    let lastId: string = 'N/A';
    this.marginalizations.forEach(mar => {
      let att: attendences = new attendences;
      att.idattendences = mar.id_attendance;
      att.worked_time = mar.after;
      att.scheduled = mar.id_marginalization;
      if (mar.idemployees != lastId) {
        lastId = mar.idemployees;
        this.apiServices.insertMarginalizations(mar).subscribe((str: string) => {
          mar.id_marginalization = str;
          this.apiServices.insertMarginalizationsDetails(mar).subscribe((str: string) => {
            count = count + 1;
            //this.apiServices.updateAttendances(att).subscribe((str:string)=>{})
            console.log(att);
            if (count == (this.marginalizations.length - 1)) {
              this.marginalazing = false;
              this.setSelection(this.accounts[0]);
            }
          })
        })
      } else {
        this.apiServices.insertMarginalizationsDetails(mar).subscribe((str: string) => {
          count = count + 1;
          //this.apiServices.updateAttendances(att).subscribe((str:string)=>{})
          console.log(att);
          if (count == (this.marginalizations.length - 1)) {
            this.marginalazing = false;
            this.setSelection(this.accounts[0]);
          }
        })
      }
    })
  }
}
