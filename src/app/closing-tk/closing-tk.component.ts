import { isNull, TypeModifier } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { employees, hrProcess } from '../fullProcess';
import { accounts, attendences, attendences_adjustment, clients, disciplinary_processes, leaves, ot_manage, paid_attendances, payments, payroll_resume, payroll_values, payroll_values_gt, periods, terminations, vacations } from '../process_templates';

@Component({
  selector: 'app-closing-tk',
  templateUrl: './closing-tk.component.html',
  styleUrls: ['./closing-tk.component.css']
})
export class ClosingTkComponent implements OnInit {

  accounts: accounts[] = [];
  periods: periods[] = [];
  years: string[] = [new Date().getFullYear().toString()];
  selectedAccount: accounts = new accounts;
  selectedClient: string = null;
  clients: clients[] = [];
  selectedPeriod: string = null;
  actualPeriod: periods = new periods;
  selectedYear: string = new Date().getFullYear().toString();
  payments: payments[] = [];
  payroll_values: payroll_values_gt[] = [];
  show_payroll_values: payroll_values_gt[] = [];
  isLoading: boolean = false;
  finished: boolean = true;
  progress: number = 0;
  max_progress: number = 0;
  step: string = null;
  show_attendances: attendences[] = [];
  save_attendances: attendences[] = [];
  saved_paid: paid_attendances[] = [];
  selected_payroll_value: payroll_values_gt = new payroll_values_gt;
  close_period: boolean = false;
  waitForfinish: boolean = false;
  isClosing: boolean = false;
  searchFilter: string = null;
  searchValue: string = null;
  resumes:payroll_resume[] = [];

  constructor(public apiServices: ApiService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })

    let i: number = 0;
    this.years = [new Date().getFullYear().toString()];
    this.apiServices.getPeriods().subscribe((p: periods[]) => {
      p.forEach(element => {
        let b: boolean = false;
        this.years.forEach(yr => {
          if (element.start.split("-")[0] == yr) {
            b = true;
          }
        })
        if (!b) {
          this.years.push(element.start.split("-")[0]);
        }
      });
      this.setYear();
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
      this.selectedAccount = this.accounts[0];
      this.setPayments();
    })
  }

  setPeriod(p: string) {
    this.isLoading = true;
    this.periods.forEach(element => {
      if (element.idperiods == p) {
        this.actualPeriod = element;
      }
    })
    this.setPayments();
  }

  setYear() {
    this.apiServices.getPeriods().subscribe((p: periods[]) => {
      this.periods = [];
      p.forEach(pe => {
        if (pe.start.split("-")[0] == this.selectedYear) {
          this.periods.push(pe);
          this.selectedPeriod = pe.idperiods;
          this.actualPeriod = pe;
        }
      })
      this.isLoading = true;
      this.setPayments();
    })
  }

  setAccount(acc: accounts) {
    this.selectedAccount = acc;
    this.setPayments();
  }

  setPayments() {
    this.show_attendances = [];
    this.save_attendances = [];
    this.resumes = [];
    this.finished = false;
    this.progress = 0;
    this.step = "Getting necessary information";
    if (this.actualPeriod.status == '1') {
      let provitional_period: periods = new periods;
      provitional_period.end = this.actualPeriod.end;
      provitional_period.idperiods = this.actualPeriod.idperiods;
      provitional_period.start = "from_close";
      provitional_period.status = this.actualPeriod.status;
      provitional_period.type_period = this.actualPeriod.type_period;
      this.apiServices.getPayments(provitional_period).subscribe((pys: payments[]) => {
        let cnt: number = 0;
        if (pys[0].idpayments == "ERROR") {
          this.show_attendances = [];
          this.save_attendances = [];
          this.finished = true;
          this.progress = 0;
          this.max_progress = 0;
        } else {
          this.step = "Building the array";
          let py: payments[] = [];
          if (!this.close_period) {
            pys.forEach(p => {
              if (p.account == this.selectedAccount.idaccounts && isNullOrUndefined(p.id_account_py)) {
                py.push(p);
              } else if (p.id_account_py == this.selectedAccount.idaccounts) {
                py.push(p);
              }
            })
          } else {
            py = pys;
          }
          this.max_progress = py.length;
          py.forEach(pay => {
            let rs:payroll_resume = new payroll_resume;
            this.step = "Calculating discounts";
            this.payroll_values = [];
            this.apiServices.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: pay.id_employee }).subscribe((emp: employees[]) => {
              this.apiServices.getVacations({ id: emp[0].id_profile }).subscribe((vac: vacations[]) => {
                this.apiServices.getLeaves({ id: emp[0].id_profile }).subscribe((leave: leaves[]) => {
                  this.apiServices.getDPAtt({ id: emp[0].idemployees, date_1: this.actualPeriod.start, date_2: this.actualPeriod.end }).subscribe((dp: disciplinary_processes[]) => {
                    this.apiServices.getAttPeriod({ id: emp[0].idemployees, date_1: this.actualPeriod.start, date_2: this.actualPeriod.end }).subscribe((att: attendences[]) => {
                      this.apiServices.getTermdt(emp[0]).subscribe((trm: terminations) => {
                        this.apiServices.getTransfers({ id_employee: emp[0].idemployees, start: this.actualPeriod.start, end: this.actualPeriod.end }).subscribe((trns: hrProcess) => {
                          let ot_manager: ot_manage = new ot_manage;
                          ot_manager.id_employee = emp[0].idemployees;
                          ot_manager.id_period = this.actualPeriod.idperiods;
                          this.apiServices.getApprovedOt(ot_manager).subscribe((ot_mng: ot_manage) => {
                            this.apiServices.getAttAdjustments({id:"id|p;"+emp[0].idemployees+"|'"+this.actualPeriod.start+"' AND '"+this.actualPeriod.end+"'"}).subscribe((just:attendences_adjustment[])=>{
                            let activeVacation: boolean = false;
                            let activeLeave: boolean = false;
                            let activeSuspension: boolean = false;
                            let non_show: boolean = false;
                            let sevenths: number = 0;
                            let discounted_days: number = 0;
                            let discounted_hours: number = 0;
                            let ot_hours: number = 0;
                            let hld_hours: number = 0;
                            let performance_bonus: number = 0;
                            let treasure_hunt: number = 0;
                            let janp_sequence: number = 0;
                            let janp_on_off: number = 0;
                            let non_show_sequence: number = 0;
                            let days_off: number = 0;
                            let off_on_week: number = 0;
                            let cnt_days: number = 0;
                            let valid_trm: boolean = false;
                            let valid_transfer: boolean = false;
                            let ult_seventh: number = 0;

                            if (pay.last_seventh == '1') {
                              non_show = true;
                            }

                            att.forEach(attendance => {
                              valid_trm = false;
                              valid_transfer = false;
                              activeVacation = false;
                              activeLeave = false;
                              activeSuspension = false;

                              if (!isNullOrUndefined(trm.valid_from)) {
                                if (new Date(trm.valid_from).getTime() <= new Date(attendance.date).getTime()) {
                                  valid_trm = true;
                                }
                              }

                              if (!isNullOrUndefined(trns)) {
                                if (new Date(trns.date).getTime() <= new Date(attendance.date).getTime() && pay.id_account_py != emp[0].id_account && !isNullOrUndefined(pay.id_account_py)) {
                                  valid_transfer = true;
                                } else if (new Date(trns.date).getTime() > new Date(attendance.date).getTime() && isNullOrUndefined(pay.id_account_py)) {
                                  valid_transfer = true;
                                }
                              }

                              if (!valid_trm && !valid_transfer) {
                                dp.forEach(disciplinary_process => {
                                  if (disciplinary_process.day_1 == attendance.date || disciplinary_process.day_2 == attendance.date || disciplinary_process.day_3 == attendance.date || disciplinary_process.day_4 == attendance.date) {
                                    activeSuspension = true;
                                    attendance.balance = 'JANP';
                                    discounted_days = discounted_days + 1;
                                    janp_sequence = janp_sequence + 1;
                                    rs.janp = (Number(rs.janp) + 1).toFixed(0);
                                  }
                                })

                                if (!activeSuspension) {
                                  vac.forEach(vacation => {
                                    if (vacation.status != "COMPLETED" && vacation.status != 'DISMISSED' && vacation.took_date == attendance.date && vacation.action == "Take") {
                                      activeVacation = true;
                                      if (attendance.scheduled == 'OFF') {
                                        days_off = days_off + 1;
                                      }
                                      attendance.balance = 'VAC';
                                      rs.vacations = (Number(rs.vacations) + 1).toFixed(0);
                                    }
                                  })
                                }

                                if (!activeSuspension && !activeVacation) {
                                  leave.forEach(lv => {
                                    if (lv.status != "COMPLETED" && lv.status != 'DISMISSED' && (new Date(lv.start).getTime()) <= (new Date(attendance.date).getTime()) && (new Date(lv.end).getTime()) >= (new Date(attendance.date).getTime())) {
                                      activeLeave = true;
                                      if (lv.motive == 'Leave of Absence Unpaid') {
                                        attendance.balance = 'LOA';
                                        discounted_days = discounted_days + 1;
                                        rs.janp = (Number(rs.janp) + 1).toFixed(0);
                                        janp_sequence = janp_sequence + 1;
                                        if (attendance.scheduled == 'OFF') {
                                          days_off = days_off + 1;
                                          janp_on_off = janp_on_off + 1;
                                        }
                                      }
                                      if (lv.motive == 'Others Unpaid' || lv.motive == "IGSS Unpaid" || lv.motive == "VTO Unpaid") {
                                        attendance.balance = 'JANP';
                                        discounted_days = discounted_days + 1;
                                        janp_sequence = janp_sequence + 1;
                                        if(lv.motive == "IGSS Unpaid"){
                                          rs.igss = (Number(rs.igss) + 1).toFixed(0);
                                        }else{
                                          rs.janp = (Number(rs.janp) + 1).toFixed(0);
                                        }
                                        if (attendance.scheduled == 'OFF') {
                                          days_off = days_off + 1;
                                          janp_on_off = janp_on_off + 1;
                                        }
                                      }
                                      if (lv.motive == 'Maternity' || lv.motive == 'Others Paid') {
                                        attendance.balance = 'JAP';
                                        rs.jap = (Number(rs.jap) + 1).toFixed(0);
                                      }
                                    }
                                  })
                                }

                                if (!activeVacation && !activeSuspension && !activeLeave) {
                                  if (attendance.scheduled == "OFF") {
                                    attendance.balance = "OFF";
                                    days_off = days_off + 1;
                                    if (Number(attendance.worked_time) > 0) {
                                      attendance.balance = (Number(attendance.worked_time)).toFixed(3);
                                      discounted_hours = discounted_hours + Number(attendance.worked_time);
                                    }
                                  } else {
                                    if (attendance.date != (new Date().getFullYear + "-01-01")) {
                                      if (Number(attendance.scheduled) > 0) {
                                        if (Number(attendance.worked_time) == 0) {
                                          attendance.balance = "NS";
                                          if (!non_show) {
                                            non_show = true;
                                            discounted_days = discounted_days + 1;
                                            sevenths = sevenths + 1;
                                            non_show_sequence = non_show_sequence + 1;
                                            ult_seventh = 1;
                                          } else {
                                            discounted_days = discounted_days + 1;
                                            non_show_sequence = non_show_sequence + 1;
                                          }
                                        } else {
                                          attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toString();
                                          discounted_hours = discounted_hours + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                        }
                                      } else {
                                        discounted_days = discounted_days + 1;
                                      }
                                    } else {
                                      if (Number(attendance.worked_time) > 0) {
                                        hld_hours = hld_hours + Number(attendance.worked_time);
                                      }
                                    }
                                  }
                                }
                                cnt_days = cnt_days + 1;
                                if (new Date(attendance.date).getDay() == 6) {
                                  off_on_week = days_off - off_on_week;
                                  let disc:boolean = false;

                                  if (janp_sequence >= 5) {
                                    disc = true;
                                    discounted_days = discounted_days + (off_on_week - janp_on_off);
                                  }

                                  if (non_show_sequence == 5) {
                                    disc = true;
                                    discounted_days = discounted_days + 1
                                  }

                                  if (off_on_week == cnt_days) {
                                    discounted_days = discounted_days + cnt_days;
                                  }

                                  if((janp_sequence + non_show_sequence) == 5 && !disc){
                                    discounted_days = discounted_days + 1;
                                  }

                                  janp_on_off = 0;
                                  non_show_sequence = 0;
                                  non_show = false;
                                  ult_seventh = 0;
                                  janp_sequence = 0
                                }
                              } else if (valid_trm) {
                                attendance.balance = "TERM";
                              } else if (valid_transfer) {
                                attendance.balance = "TRANSFER";
                              }
                            })

                            if ((discounted_days + sevenths) >= 15) {
                              let max_days: number = 0;
                              max_days = ((new Date(this.actualPeriod.end).getTime()) - (new Date(this.actualPeriod.start).getTime())) / (1000 * 3600 * 24);
                              if (discounted_hours != 0) {
                                discounted_days = 15 - (discounted_days + (max_days - 15));
                              }
                              discounted_days = (15 - max_days) + max_days;
                              sevenths = 0;
                            }

                            if((((new Date(this.actualPeriod.end).getTime() - new Date(this.actualPeriod.start).getTime())/(1000*3600*24)) + 1) <= (discounted_days + sevenths)){
                              discounted_days = (15 - sevenths);
                            }

                            just.forEach(justification=>{
                              if(justification.reason == "IGSS"){
                                rs.igss_hrs = (Number(rs.igss_hrs) + Number(justification.amount)).toFixed(3);
                              }else if(justification.reason == "Private Doctor"){
                                rs.insurance = (Number(rs.insurance) + Number(justification.amount)).toFixed(3);
                              }else{
                                rs.other_hrs = (Number(rs.other_hrs) + Number(justification.amount)).toFixed(3);
                              }
                            });

                            let payroll_value: payroll_values_gt = new payroll_values_gt;
                            rs.nearsol_id = emp[0].nearsol_id;
                            rs.client_id = emp[0].client_id;
                            rs.name = emp[0].name;
                            this.resumes.push(rs);
                            payroll_value.client_id = pay.client_id;
                            payroll_value.discounted_days = discounted_days.toString();
                            payroll_value.discounted_hours = discounted_hours.toString();
                            payroll_value.holidays_hours = hld_hours.toString();
                            if (isNullOrUndefined(pay.id_account_py)) {
                              payroll_value.id_account = emp[0].id_account;
                            } else {
                              payroll_value.id_account = pay.id_account_py;
                            }
                            payroll_value.id_employee = pay.id_employee;
                            payroll_value.id_payment = pay.idpayments;
                            payroll_value.id_period = pay.id_period;
                            payroll_value.next_seventh = ult_seventh;
                            if (this.close_period) {
                              payroll_value.id_reporter = emp[0].reporter;
                            } else {
                              payroll_value.id_reporter = emp[0].user_name;
                            }
                            payroll_value.nearsol_id = emp[0].nearsol_id;
                            payroll_value.agent_name = pay.employee_name;
                            payroll_value.account_name = this.selectedAccount.name;
                            if (discounted_hours >= 0) {
                              if (!isNullOrUndefined(ot_mng.amount)) {
                                if (Number(ot_mng.amount) >= discounted_hours) {
                                  payroll_value.ot_hours = discounted_hours.toString()
                                } else {
                                  payroll_value.ot_hours = ot_mng.amount;
                                }
                              } else {
                                payroll_value.ot_hours = discounted_hours.toString()
                              }
                              payroll_value.discounted_hours = "0";

                            } else {
                              payroll_value.ot_hours = "0";
                              payroll_value.discounted_hours = discounted_hours.toString();
                            }
                            payroll_value.hrs = discounted_hours;
                            payroll_value.agent_status = emp[0].active;
                            payroll_value.seventh = sevenths.toString();

                            if (!this.close_period) {
                              if (payroll_value.id_account == this.selectedAccount.idaccounts) {
                                this.payroll_values.push(payroll_value);
                                att.forEach(attendance => {
                                  this.save_attendances.push(attendance);
                                });
                              }
                            } else {
                              this.payroll_values.push(payroll_value);
                              att.forEach(attendance => {
                                this.save_attendances.push(attendance);
                              })
                            }
                            this.progress = this.progress + 1;
                            if (this.progress == this.max_progress || this.progress == (pys.length - 1)) {
                              this.step = "Formating table";
                              this.finished = true;
                              if (this.close_period && this.progress != (pys.length - 1)) {
                                this.apiServices.insertPayroll_values_gt(this.payroll_values).subscribe((str: string) => {
                                  if (str == "1") {
                                    window.alert("Period successfully frozen\n" + "Next Step freeze attendance");
                                    this.finished = false;
                                    this.max_progress = 1;
                                    this.progress = 1;
                                    this.step = "Fetching payroll values";
                                    this.apiServices.getPayroll_values_gt(this.actualPeriod).subscribe((py_close: payroll_values_gt[]) => {
                                      this.progress = 0;
                                      this.max_progress = this.save_attendances.length - 1;
                                      this.step = "Fetching attendances to save: " + this.progress + "/" + this.max_progress;
                                      let paid_attendances_insert: paid_attendances[] = [];
                                      this.save_attendances.forEach(att_close => {
                                        py_close.forEach(payroll_value_close => {
                                          if (payroll_value_close.id_employee == att_close.id_employee) {
                                            let paid_attendance: paid_attendances = new paid_attendances;
                                            paid_attendance.date = att_close.date;
                                            paid_attendance.id_payroll_value = payroll_value_close.idpayroll_values;
                                            paid_attendance.scheduled = att_close.scheduled;
                                            paid_attendance.worked = att_close.worked_time;
                                            if (!isNullOrUndefined(att_close.balance)) {
                                              paid_attendance.balance = att_close.balance;
                                            } else {
                                              paid_attendance.balance = "0";
                                            }
                                            paid_attendances_insert.push(paid_attendance);
                                            this.progress = this.progress + 1;
                                            this.step = "Saving Attendances: " + this.progress + "/" + this.max_progress;
                                          }
                                        })
                                      })
                                      this.apiServices.insertPaidAttendances_gt(paid_attendances_insert).subscribe((str2: string) => {
                                        if (str2 == "1") {
                                          window.alert("Period successfuly frozen\n" + "Next step retrieve information");
                                          this.show_attendances = [];
                                          this.save_attendances = [];
                                          this.apiServices.getPaidAttendances(this.actualPeriod).subscribe((pd_att: paid_attendances[]) => {
                                            this.saved_paid = pd_att;
                                            window.alert("Period with values and attendances successfuly closed");
                                            this.finished = true;
                                            this.isLoading = true;
                                          })
                                          this.apiServices.updatePeriods({ id: this.actualPeriod.idperiods, status: '3' }).subscribe((str: string) => {
                                            if (str.split("|")[0] == "1") {
                                              window.alert("Period with values and attendances successfuly frozen\n  Next steep: freeze processes.");
                                            } else {
                                              window.alert("Error on update " + str.split("|")[1]);
                                            }
                                          })
                                          /*ASEGURARSE QUE SEIEMPRE ESTE EN FALSE EN CUALQUIERA DE LOS DEMAS CASOS */
                                        } else {
                                          window.alert("Please contact your administrator with the following information:\n" + str.split("|")[1]);
                                        }
                                        this.finished = true;
                                        this.isLoading = true;
                                      })
                                    })
                                  } else {
                                    window.alert("Please contact your administrator with the following information:\n" + str.split("|")[1]);
                                  }
                                })
                              }
                            }
                          })
                        })
                        });
                      })
                    })
                  })
                })
              })
            })
            if (pay.account == this.selectedAccount.idaccounts) {
              cnt = cnt + 1;
            }
          })
          if (cnt == 0) {
            this.show_attendances = [];
            this.show_payroll_values = [];
            this.save_attendances = [];
            this.payroll_values = [];
            this.finished = true;
            this.isLoading = false;
            window.alert("No records to show");
          }
          this.isLoading = false;
        }
      })
    } else {
      this.finished = true;
      this.isLoading = false;
      this.payroll_values = [];
      this.save_attendances = [];
      this.apiServices.getPayroll_values_gt(this.actualPeriod).subscribe((p: payroll_values_gt[]) => {
        this.apiServices.getPaidAttendances(this.actualPeriod).subscribe((att: paid_attendances[]) => {
          if (!isNullOrUndefined(p) && !isNullOrUndefined(att)) {
            p.forEach(payroll_val => {
              if (payroll_val.id_account == this.selectedAccount.idaccounts) {
                this.payroll_values.push(payroll_val);
              }
            })
            this.saved_paid = att;
          } else {
            if (isNullOrUndefined(this.actualPeriod.idperiods)) {
              window.alert("There's no data to show");
            }
          }
        })
      })
    }
  }

  saveClosing() {
    this.close_period = true;
    this.setPayments();
  }

  setAttendance(py: payroll_values_gt) {
    if (this.actualPeriod.status == '1') {
      this.selected_payroll_value = py;
      this.selected_payroll_value.total_days = (Number(this.selected_payroll_value.discounted_days) + Number(this.selected_payroll_value.seventh)).toFixed(0);
      this.show_attendances = [];
      this.save_attendances.forEach(att => {
        if (att.id_employee == this.selected_payroll_value.id_employee) {
          this.show_attendances.push(att);
        }
      })
    } else {
      this.selected_payroll_value = py;
      this.selected_payroll_value.total_days = (Number(this.selected_payroll_value.discounted_days) + Number(this.selected_payroll_value.seventh)).toFixed(0);
      this.show_attendances = [];
      this.saved_paid.forEach(att => {
        if (att.id_payroll_value == this.selected_payroll_value.idpayroll_values) {
          let attend: attendences = new attendences;
          attend.date = att.date;
          attend.scheduled = att.scheduled;
          attend.worked_time = att.worked;
          attend.balance = att.balance;
          this.show_attendances.push(attend)
        }
      })
    }
  }

  exportPayroll_values() {

  }

  searchNow() {

  }
}