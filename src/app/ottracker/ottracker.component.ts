import { applySourceSpanToStatementIfNeeded } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { SSL_OP_SSLEAY_080_CLIENT_DH_BUG } from 'constants';
import { isNullOrUndefined } from 'util';
import { MarginInfo } from 'xlsx/types';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { accounts, activities, attendences, attendences_adjustment, clients, marginalization, ot_manage, periods, vacations } from '../process_templates';
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
  history: boolean = false;
  otView: boolean = false;
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
    let activeVacation: boolean = false;
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
          this.apiServices.getVacations({ id: employee.id_profile }).subscribe((vac: vacations[]) => {
            if (att.length > 0) {
              let ot: ot_manage = new ot_manage;
              ot.amount = '0.00';
              ot.status = '0.00';
              ot.id_employee = employee.idemployees;
              ot.name = employee.name;
              ot.nearsol_id = employee.nearsol_id;
              ot.client_id = employee.client_id;
              att.forEach(attendance => {
                activeVacation = false;

                vac.forEach(vacation => {
                  if (vacation.took_date == attendance.date && vacation.status == "PENDING") {
                    activeVacation = true;
                  }
                })

                if (!activeVacation) {
                  if (attendance.scheduled != 'OFF') {
                    if ((Number(attendance.worked_time) - Number(attendance.scheduled)) > 0) {
                      ot.amount = (Number(ot.amount) + Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(2);
                    } else if ((Number(attendance.worked_time) - Number(attendance.scheduled)) < 0 && attendance.scheduled != "OFF") {
                      ot.status = (Number(ot.status) + Number(Number(attendance.worked_time) - Number(attendance.scheduled))).toFixed(2);
                    }
                  } else if (attendance.scheduled == 'OFF') {
                    ot.amount = (Number(ot.amount) + Number(attendance.worked_time)).toFixed(2);
                  }
                }
              })
              if (Number(ot.amount) != 0 || Number(ot.status) != 0) {
                ot.balance = (Number(ot.status) + Number(ot.amount)).toFixed(2);
                this.ots.push(ot);
              }
            }
          })
        })
      })
      this.showAccount = true;
    })
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
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
      if (Number(ot.status) < 0) {
        this.apiServices.getAttPeriod({ id: ot.id_employee, date_1: start, date_2: end }).subscribe((att: attendences[]) => {
          cnt = cnt + 1;
          att.forEach(attendance => {
            let marg: marginalization = new marginalization;
            if (attendance.scheduled != 'OFF' && Number(attendance.worked_time) > Number(attendance.scheduled) && Number(ot.status) < 0) {
              marg.before = attendance.worked_time;
              attendance.worked_time = attendance.scheduled;
              marg.id_attendance = attendance.idattendences;
              marg.after = attendance.scheduled;
              marg.approved_by = this.iduser;
              marg.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
              marg.id_user = this.authService.getAuthusr().iduser;
              marg.type = 'Makeup Reduction';
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
                marg.approved_by = this.iduser;
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
          if (this.ots.length == cnt) {
            this.marginalazing = true;
            this.history = false;
          }
        })
      } else {
        cnt = cnt + 1;
      }
      if (this.ots.length == cnt) {
        this.marginalazing = true;
        this.history = false;
      }
    })
  }

  getOT() {
    this.marginalizations = [];
    this.ots.forEach(ot => {
      let marginisation: marginalization = new marginalization;
      if ((Number(ot.amount) + Number(ot.status)) > 0) {
        console.log(ot);
        marginisation.idemployees = ot.id_employee;
        marginisation.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
        marginisation.name = ot.name;
        marginisation.before = (Number(ot.status) + Number(ot.amount)).toFixed(2);
        marginisation.after = "0.00";
        marginisation.value = marginisation.before;
        marginisation.action = "OMMIT";
        marginisation.nearsol_id = ot.nearsol_id;
        marginisation.approved_by = this.iduser;
        marginisation.type = "OT Remover";
        console.log(marginisation);
        this.marginalizations.push(marginisation);
      }
      this.marginalazing = true;
      this.history = false;
      this.otView = true;
    })
  }

  viewHistory() {
    let date: Date = new Date();
    let start: string = null;
    let end: string = null;
    let cnt: number = 0;
    this.marginalazing = false;
    if (date.getDate() > 15) {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '16';
      end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());
    } else {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '01';
      end = end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + "15";
    }

    this.apiServices.getMarginalizations({ start: start, end: end, account: this.selectedAccount.idaccounts }).subscribe((margins: marginalization[]) => {
      this.marginalizations = margins;
      this.history = true
    })
  }

  saveOTMerge() {
    if (!this.otView) {
      this.apiServices.insertMarginalizations(this.marginalizations[0]).subscribe((str: string) => {
        this.marginalizations.forEach(mar => {
          let att: attendences = new attendences;

          att.idattendences = mar.id_attendance;
          att.worked_time = mar.after;
          att.scheduled = mar.after;
          mar.id_marginalization = str;

          this.apiServices.updateAttendances(att).subscribe((str: string) => {
            this.apiServices.insertMarginalizationsDetails(mar).subscribe((str: string) => {
              if (this.marginalizations.indexOf(mar) == (this.marginalizations.length - 1)) {
                this.marginalazing = false;
                this.setSelection(this.selectedAccount);
              }
            })
          });
        })
      })
    } else {
      this.apiServices.getPeriods().subscribe((period: periods[]) => {
        this.marginalizations.forEach(margin => {
          let ot: ot_manage = new ot_manage;
          ot.id_employee = margin.idemployees;
          ot.id_period = period[period.length - 1].idperiods;
          if (margin.action == 'APPLY') {
            ot.amount = margin.before;
          } else if (margin.action == 'DENY') {
            ot.amount = '0.00';
          }
          if(margin.action != "OMMIT"){
            this.apiServices.getApprovedOt(ot).subscribe((ots: ot_manage) => {
              if (isNullOrUndefined(ots.amount)) {
                this.apiServices.insertApprovedOt(ot).subscribe((str: string) => {
                  if (this.marginalizations.indexOf(margin) == (this.marginalizations.length - 1)) {
                    this.marginalazing = false;
                    this.setSelection(this.selectedAccount);
                  }
                })
              } else {
                this.apiServices.updateApproveOt(ot).subscribe((str: string) => {
                  if (this.marginalizations.indexOf(margin) == (this.marginalizations.length - 1)) {
                    this.marginalazing = false;
                    this.setSelection(this.selectedAccount);
                  }
                })
              }
            })
          }else{
            if (this.marginalizations.indexOf(margin) == (this.marginalizations.length - 1)) {
              this.marginalazing = false;
              this.setSelection(this.selectedAccount);
            }
          }
        })
      })
    }
  }
}
