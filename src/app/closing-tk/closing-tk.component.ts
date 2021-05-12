import { importType, isNull, TypeModifier } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { isNullOrUndefined, isNumber } from 'util';
import { ApiService } from '../api.service';
import { employees, hrProcess } from '../fullProcess';
import * as XLSX from 'xlsx';
import * as FileSaver from 'file-saver';
import { accounts, attendance_accounts, attendences, attendences_adjustment, clients, credits, disciplinary_processes, leaves, ot_manage, paid_attendances, payments, payroll_resume, payroll_values, payroll_values_gt, periods, terminations, timekeeping_adjustments, vacations } from '../process_templates';
import { AuthServiceService } from '../auth-service.service';
import { BADFLAGS, DESTRUCTION } from 'dns';
import { exit } from 'process';
import { ViewChild, ElementRef } from '@angular/core';
import { stringify } from '@angular/compiler/src/util';

@Component({
  selector: 'app-closing-tk',
  templateUrl: './closing-tk.component.html',
  styleUrls: ['./closing-tk.component.css']
})

export class ClosingTkComponent implements OnInit {

  @ViewChild('userTable', { static: false }) userTable: ElementRef;

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
  resumes: payroll_resume[] = [];
  file: any;
  arrayBuffer: any;
  fileWait: boolean;
  adjustments: attendences_adjustment[] = [];
  printing: boolean = false;
  isSearching:boolean = false;
  credits:credits[] =[];
  completed:boolean = false;
  importEnd:boolean = false;
  working:boolean = false;
  p_val_update:payroll_values_gt[] = [];
  import_type:string = "PERFORMANCE BONUS";
  saving:boolean = false;
  deadline_date:string = new Date().toISOString().split('T')[0];
  deadline_time:string = new Date().getHours() + ":" + new Date().getMinutes();
  disable_date:boolean = false;
  disable_time:boolean = false;

  constructor(public apiServices: ApiService, public authUser: AuthServiceService) { }

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
    this.isSearching = false;
    this.selectedAccount = acc;
    this.apiServices.getPayroll_resume({ id_account: this.selectedAccount.idaccounts, id_period: this.actualPeriod.idperiods }).subscribe((p: payroll_resume[]) => {
      this.resumes = p;
    })
    this.setPayments();
  }

  setPayments() {
    this.disable_time = false;
    this.disable_date = false;
    let refTime:number = new Date(this.deadline_date + " " + this.deadline_time).getTime();

    this.completed = false;
    this.working = false;
    this.importEnd = false;
    this.saving = false;
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
              if(!this.isSearching){
              if (p.account == this.selectedAccount.idaccounts && isNullOrUndefined(p.id_account_py)) {
                py.push(p);
              } else if (p.id_account_py == this.selectedAccount.idaccounts) {
                py.push(p);
              }
            }else{
              this.selectedAccount = new accounts;
              if(this.searchFilter == 'name'){
                if(p.employee_name.toUpperCase().includes(this.searchValue.toUpperCase())){
                  py.push(p);
                }
              }else if(this.searchFilter == 'client_id'){
                if(p.client_id.toUpperCase().includes(this.searchValue.toUpperCase())){
                  py.push(p);
                }
              }else if(this.searchFilter == 'nearsol_id'){
                if(p.nearsol_id.toUpperCase().includes(this.searchValue.toUpperCase())){
                  py.push(p);
                }
              }
            }
            })
          } else {
            py = pys;
          }
          this.max_progress = py.length;
          py.forEach(pay => {
            let rs: payroll_resume = new payroll_resume;
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
                            this.apiServices.getAttAdjustments({ id: "id|p;" + emp[0].idemployees + "|'" + this.actualPeriod.start + "' AND '" + this.actualPeriod.end + "'" }).subscribe((just: attendences_adjustment[]) => {

                              let prov_period: periods = new periods;
                              let dt: Date = new Date(this.actualPeriod.start);
                              dt.setDate((dt.getDate() + 1) - (dt.getDay()));
                              prov_period.idperiods = emp[0].idemployees;
                              prov_period.end = "'" + dt.getFullYear() + "-" +
                                (dt.getMonth() + 1).toString().padStart(2, "0") + "-" +
                                (dt.getDate()).toString().padStart(2, "0") + "' AND '" +
                                this.actualPeriod.start + "' AND `balance` = 'NS'";
                              prov_period.start = "explicit";
                              this.apiServices.getPaidAttendances(prov_period).subscribe((p_at: paid_attendances[]) => {
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
                                let worked_days: number = 0;
                                let ns_count: number = 0;
                                let janp_on_off_2: number = 0;
                                let carry_seventh: boolean = null;
                                let trm_count: number = 0;
                                let hld:number= 0;
                                let hldT:number= 0;
                                let week_work:number = 0;

                                if (pay.last_seventh == '1') {
                                  non_show = true;
                                }

                                if (!isNullOrUndefined(p_at)) {
                                  if (p_at.length > 0) {
                                    carry_seventh = true;
                                    non_show = true;
                                    non_show_sequence = p_at.length;
                                  }
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
                                      if (!activeSuspension && (new Date(disciplinary_process.dateTime).getTime() <= refTime) && disciplinary_process.day_1 == attendance.date || disciplinary_process.day_2 == attendance.date || disciplinary_process.day_3 == attendance.date || disciplinary_process.day_4 == attendance.date) {
                                        activeSuspension = true;
                                        attendance.balance = 'JANP';
                                        discounted_days = discounted_days + 1;
                                        janp_sequence = janp_sequence + 1;
                                        rs.janp = (Number(rs.janp) + 1).toFixed(0);
                                      }
                                    })

                                    if (!activeSuspension) {
                                      vac.forEach(vacation => {
                                        if (!activeVacation && vacation.status != "COMPLETED" && vacation.status != 'DISMISSED' && vacation.took_date == attendance.date && vacation.action == "Take" && new Date(vacation.dateTime).getTime() <= refTime) {
                                          activeVacation = true;
                                          week_work++;
                                          worked_days++;
                                          if (attendance.scheduled == 'OFF') {
                                            days_off = days_off + 1;
                                          }
                                          attendance.balance = 'VAC';
                                          rs.vacations = (Number(rs.vacations) + Number(vacation.count)).toFixed(0);
                                          if (Number(vacation.count) < 1) {
                                            if (attendance.scheduled != "OFF") {
                                              attendance.worked_time = (Number(attendance.worked_time) + (Number(attendance.scheduled) * Number(vacation.count))).toFixed(5);
                                              attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(3);
                                              discounted_hours = discounted_hours + Number(attendance.worked_time) - Number(attendance.scheduled);
                                            }
                                          }
                                        }
                                      })
                                    }

                                    if (!activeSuspension && !activeVacation) {
                                      leave.forEach(lv => {
                                        if (!activeLeave && (new Date(lv.dateTime).getTime() <= refTime) && lv.status != "COMPLETED" && lv.status != 'DISMISSED' && (new Date(lv.start).getTime()) <= (new Date(attendance.date).getTime()) && (new Date(lv.end).getTime()) >= (new Date(attendance.date).getTime())) {
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
                                            if (lv.motive == "IGSS Unpaid") {
                                              rs.igss = (Number(rs.igss) + 1).toFixed(0);
                                            } else {
                                              rs.janp = (Number(rs.janp) + 1).toFixed(0);
                                            }
                                            if (attendance.scheduled == 'OFF') {
                                              days_off = days_off + 1;
                                              janp_on_off = janp_on_off + 1;
                                            }
                                          }
                                          if (lv.motive == 'Maternity' || lv.motive == 'Others Paid') {
                                            attendance.balance = 'JAP';
                                            worked_days++;
                                            week_work++;
                                            rs.jap = (Number(rs.jap) + 1).toFixed(0);
                                          }
                                        }
                                      })
                                    }

                                    if (!activeVacation && !activeSuspension && !activeLeave) {
                                      just.forEach(justification => {
                                        if(justification.id_attendence == attendance.idattendences){
                                          if(new Date(justification.dateTime).getTime() >= refTime){
                                            attendance.worked_time = (Number(attendance.worked_time) - Number(justification.amount)).toFixed(4);
                                          }
                                        }
                                      });

                                      if (attendance.scheduled == "OFF") {
                                        attendance.balance = "OFF";
                                        days_off = days_off + 1;
                                        if (Number(attendance.worked_time) > 0) {
                                          attendance.balance = (Number(attendance.worked_time)).toFixed(3);
                                          discounted_hours = discounted_hours + Number(attendance.worked_time);
                                        }  
                                      } else {
                                        if (attendance.date != (new Date().getFullYear() + "-01-01") && attendance.date != (new Date().getFullYear() + "-04-01") && attendance.date != (new Date().getFullYear() + "-04-02") && attendance.date != (new Date().getFullYear() + "-04-03")) {
                                          if (Number(attendance.scheduled) > 0) {
                                            if (Number(attendance.worked_time) == 0) {
                                              attendance.balance = "NS";
                                              ns_count++;
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
                                              worked_days++;
                                              week_work++;
                                              attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toString();
                                              discounted_hours = discounted_hours + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                            }
                                          } else {
                                            discounted_days = discounted_days + 1;
                                          }
                                        } else {
                                          hld = hld + 1;
                                          hldT = hldT + 1;
                                          if(attendance.scheduled != "OFF"){
                                            if(Number(attendance.worked_time) > Number(attendance.scheduled)){
                                              hld_hours = hld_hours + Number(attendance.scheduled);
                                              discounted_hours = discounted_hours + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                              attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(2);
                                            }else{
                                              if (Number(attendance.worked_time) > 0) {
                                                hld_hours = hld_hours + (Number(attendance.worked_time));
                                                attendance.balance = 'HLD';
                                              }
                                            }
                                          }
                                        }
                                      }
                                    }

                                    cnt_days = cnt_days + 1;
                                    if (new Date(attendance.date).getDay() == 6) {
                                      off_on_week = days_off - off_on_week;
                                      let disc: boolean = false;

                                      if (janp_sequence >= 5) {
                                        disc = true;
                                        discounted_days = discounted_days + (off_on_week - janp_on_off);
                                      }

                                      if (non_show_sequence == 5) {
                                        if (carry_seventh) {
                                          discounted_days = discounted_days + 1;
                                        }
                                        disc = true;
                                        discounted_days = discounted_days + 1
                                        
                                      }

                                      if ((janp_sequence + non_show_sequence) == 5 && !disc) {
                                        discounted_days = discounted_days + 1;
                                      }

                                      janp_on_off_2 = janp_on_off_2 + janp_on_off;
                                      janp_on_off = 0;
                                      non_show_sequence = 0;
                                      non_show = false;
                                      ult_seventh = 0;
                                      janp_sequence = 0;
                                      hld = 0;
                                      week_work = 0;
                                    }
                                  } else if (valid_trm) {
                                    attendance.balance = "TERM";
                                    trm_count++;
                                  } else if (valid_transfer) {
                                    attendance.balance = "TRANSFER";
                                  }

                                  if(isNullOrUndefined(pay.id_account_py)){
                                    attendance.id_wave = emp[0].id_account;
                                  }else{
                                    attendance.id_wave = pay.id_account_py;
                                  }

                                })

                                if (!isNullOrUndefined(trm.valid_from) && worked_days == 0 && discounted_days > 0) {
                                  if (new Date(trm.valid_from).getTime() > new Date(this.actualPeriod.start).getTime()) {
                                    sevenths = (((new Date(trm.valid_from).getTime() - new Date(this.actualPeriod.start).getTime()) / (1000 * 3600 * 24)) - (ns_count + janp_sequence + hldT));
                                  }
                                } else if(isNullOrUndefined(trm.valid_from)){
                                  if (discounted_days + sevenths > att.length) {
                                    sevenths = days_off;
                                    discounted_days = att.length - days_off + worked_days;
                                  }
                                }

                                if ((discounted_days + sevenths) >= 15) {
                                  let max_days: number = 0;
                                  max_days = ((new Date(this.actualPeriod.end).getTime()) - (new Date(this.actualPeriod.start).getTime())) / (1000 * 3600 * 24);
                                  if (discounted_hours != 0) {
                                    discounted_days = 15 - (discounted_days + (max_days - 15));
                                  }
                                  discounted_days = (15 - max_days) + max_days - worked_days;
                                  sevenths = 0;
                                }

                                if ((((new Date(this.actualPeriod.end).getTime() - new Date(this.actualPeriod.start).getTime()) / (1000 * 3600 * 24)) + 1) <= (discounted_days + sevenths)) {
                                  discounted_days = (15 - sevenths);
                                }

                                if (ns_count + days_off >= ((new Date(this.actualPeriod.end).getTime()) - (new Date(this.actualPeriod.start).getTime())) / (1000 * 3600 * 24) || Number(rs.janp) + days_off - janp_on_off_2 >= ((new Date(this.actualPeriod.end).getTime()) - (new Date(this.actualPeriod.start).getTime())) / (1000 * 3600 * 24)) {
                                  discounted_days = 15;
                                  sevenths = 0;
                                }

                                if(att.length == 0){
                                  discounted_days = 15;
                                }

                                just.forEach(justification => {
                                  if (justification.reason == "IGSS") {
                                    rs.igss_hrs = (Number(rs.igss_hrs) + Number(justification.amount)).toFixed(3);
                                  } else if (justification.reason == "Private Doctor") {
                                    rs.insurance = (Number(rs.insurance) + Number(justification.amount)).toFixed(3);
                                  } else {
                                    rs.other_hrs = (Number(rs.other_hrs) + Number(justification.amount)).toFixed(3);
                                  }
                                });

                                let payroll_value: payroll_values_gt = new payroll_values_gt;
                                rs.nearsol_id = emp[0].nearsol_id;
                                rs.client_id = emp[0].client_id;
                                rs.id_period = this.actualPeriod.idperiods;
                                rs.id_employee = emp[0].idemployees;
                                rs.name = emp[0].name;
                                if (!isNullOrUndefined(rs)) {
                                  this.resumes.push(rs);
                                }
                                payroll_value.performance_bonus = pay.client_bonus;
                                payroll_value.adj_holidays = pay.adj_holidays;
                                payroll_value.adj_ot = pay.adj_ot;
                                payroll_value.adj_hours = pay.adj_hours;
                                payroll_value.nearsol_bonus = pay.nearsol_bonus;
                                payroll_value.treasure_hunt = pay.treasure_hunt;
                                payroll_value.client_id = pay.client_id;
                                payroll_value.discounted_days = discounted_days.toString();
                                payroll_value.discounted_hours = discounted_hours.toString();
                                payroll_value.holidays_hours = hld_hours.toString();
                                if (isNullOrUndefined(pay.id_account_py)) {
                                  payroll_value.id_account = emp[0].id_account;
                                } else {
                                  payroll_value.id_account = pay.id_account_py;
                                }
                                rs.account = payroll_value.id_account
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
                                  if(!this.isSearching){
                                  if (payroll_value.id_account == this.selectedAccount.idaccounts) {
                                    this.payroll_values.push(payroll_value);
                                    att.forEach(attendance => {
                                      this.save_attendances.push(attendance);
                                    });
                                  }
                                }else{
                                  payroll_value.account_name = pay.account;
                                  this.payroll_values.push(payroll_value);
                                  att.forEach(attendance=>{
                                    this.save_attendances.push(attendance);
                                  })
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
                                              if (payroll_value_close.id_employee == att_close.id_employee && payroll_value_close.id_account == att_close.id_wave) {
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
                                          this.finished = true;
                                          this.isLoading = true;
                                          this.apiServices.insertPaidAttendances_gt(paid_attendances_insert).subscribe((str2: string) => {
                                            if (str2 == "1") {
                                              window.alert("Attendances successfuly frozen\n" + "Next Step: Retrieve information");
                                              this.show_attendances = [];
                                              this.save_attendances = [];
                                              this.apiServices.getPaidAttendances(this.actualPeriod).subscribe((pd_att: paid_attendances[]) => {
                                                this.saved_paid = pd_att;
                                                this.apiServices.updatePeriods({ id: this.actualPeriod.idperiods, status: '3' }).subscribe((str: string) => {
                                                  if (str.split("|")[0] == "1") {
                                                    window.alert("Period set on frozen\n" + "Next Step: freeze resume");
                                                    this.apiServices.insertPayroll_resume(this.resumes).subscribe((str3: string) => {
                                                      if (str3 == "1") {
                                                        window.alert("Resume Successfuly frozen\n" + "Next Step: Get Information");
                                                        this.resumes = [];
                                                        this.apiServices.getPayroll_resume({ id_period: this.actualPeriod.idperiods, id_account: this.selectedAccount.idaccounts }).subscribe((pr: payroll_resume[]) => {
                                                          /*ASEGURARSE QUE SEIEMPRE ESTE EN FALSE EN CUALQUIERA DE LOS DEMAS CASOS */
                                                          this.apiServices.setCompleted(this.actualPeriod).subscribe((str: string) => {
                                                            if (String(str).split("|")[0]=='Info:') {
                                                              window.alert(String(str).split("|")[1]);
                                                            }
                                                            else {
                                                              window.alert( String(str).split("|")[0] + "\n" + 
                                                                            String(str).split("|")[1] + "\n" + 
                                                                            String(str).split("|")[2]);
                                                            }
                                                            this.resumes = pr;
                                                            window.alert("Process Completed");
                                                            this.isLoading = false;
                                                            this.finished = true;
                                                          })
                                                        })
                                                      } else {
                                                        window.alert("Error on insert " + str.split("|")[1]);
                                                        this.isLoading = false;
                                                        this.finished = true;
                                                      }
                                                    })
                                                  } else {
                                                    window.alert("Error on update " + str.split("|")[1]);
                                                    this.isLoading = false;
                                                    this.finished = true;
                                                  }
                                                })
                                              })
                                            } else {
                                              window.alert("1. Please contact your administrator with the following information:\n" + str.split("|")[1]);
                                              this.isLoading = false;
                                              this.finished = true;
                                            }
                                          })
                                        })
                                      } else {
                                        window.alert("0. Please contact your administrator with the following information:\n" + str.split("|")[1]);
                                        this.isLoading = false;
                                        this.finished = true;
                                      }
                                    })
                                  }
                                }
                              })
                            })
                          })
                        })
                      })
                    })
                  })
                })
              })
            })
            if (pay.account == this.selectedAccount.idaccounts || pay.id_account_py == this.selectedAccount.idaccounts) {
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
      this.adjustments = [];
      this.apiServices.getPayroll_values_gt(this.actualPeriod).subscribe((p: payroll_values_gt[]) => {
        this.apiServices.getPaidAttendances(this.actualPeriod).subscribe((att: paid_attendances[]) => {
          this.apiServices.getPayroll_resume({ id_period: this.actualPeriod.idperiods, id_account: this.selectedAccount.idaccounts }).subscribe((pr_res: payroll_resume[]) => {
            this.apiServices.getAttAdjustments({ id: "id|p|t;'" + this.actualPeriod.start + "' AND '" + this.actualPeriod.end + "'" }).subscribe((ad: attendences_adjustment[]) => {
              if (!isNullOrUndefined(p) && !isNullOrUndefined(att) && !isNullOrUndefined(pr_res)) {
                ad.forEach(adjustment => {
                  if (adjustment.account == this.selectedAccount.idaccounts) {
                    this.adjustments.push(adjustment);
                  }
                })
                p.forEach(payroll_val => {
                  if (payroll_val.id_account == this.selectedAccount.idaccounts) {
                    this.payroll_values.push(payroll_val);
                  }
                })
                this.resumes = pr_res;
                this.saved_paid = att;
              } else {
                if (isNullOrUndefined(this.actualPeriod.idperiods)) {
                  window.alert("There's no data to show");
                }
              }
            })
          })
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
    this.printing = true;
    if (this.actualPeriod.status == '3') {
      window.open("http://172.18.2.45/phpscripts/exportPayroll_values.php?id_period=" + this.actualPeriod.idperiods, "_blank");
    } else {
      this.exportTableElmToExcel(this.userTable, 'user_data');
      window.alert("Data Successfully exported");
      this.printing = false;
    }
  }

  exportPaidAttendances() {
    window.open("http://172.18.2.45/phpscripts/exportPaid_attendances.php?id_period=" + this.actualPeriod.idperiods, "_blank");
  }

  searchNow() {
    this.isSearching = true;
    this.setPayments();
  }

  setFile(event: any) {
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let t: number = 0;
    this.waitForfinish = true;
    let f: boolean = true;

    let provitional_period: periods = new periods;
    provitional_period.end = this.actualPeriod.end;
    provitional_period.idperiods = this.actualPeriod.idperiods;
    provitional_period.start = "from_close";
    provitional_period.status = this.actualPeriod.status;
    provitional_period.type_period = this.actualPeriod.type_period;

    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      this.arrayBuffer = fileReader.result;
      var data = new Uint8Array(this.arrayBuffer);
      var arr = new Array();
      for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
      var bstr = arr.join("");
      var workbook = XLSX.read(bstr, { type: "binary" });
      var first_sheet_name = workbook.SheetNames[0];
      var worksheet = workbook.Sheets[first_sheet_name];
      let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
      sheetToJson.forEach(element => {
        this.apiServices.getSearchEmployees({ dp: '28', filter: 'nearsol_id', value: element['Nearsol ID'] }).subscribe((emp: employees[]) => {
          if (emp[0].id_account != this.selectedAccount.idaccounts) {
            if (f) {
              f = false;
              window.alert("Please redirect yourself to the right account to upload this file");
              this.waitForfinish = false;
            }
          } else {
            let adj: attendences_adjustment = new attendences_adjustment;
            if (!isNullOrUndefined(emp[0])) {
              adj.id_employee = emp[0].idemployees;
              adj.nearsol_id = emp[0].nearsol_id;
              this.apiServices.getAttPeriod({ id: emp[0].idemployees, date_1: this.actualPeriod.start, date_2: this.actualPeriod.end }).subscribe((att: attendences[]) => {
                if (!isNullOrUndefined(att)) {
                  if (element['Action'] == '1') {
                    adj.adj_type = 'Hours';
                    adj.id_attendence = att[0].idattendences;
                    adj.id_user = this.authUser.getAuthusr().iduser;
                    adj.id_type = '2';
                    adj.id_department = this.authUser.getAuthusr().department;
                    adj.notes = "Closing Hours Exception Authorized By: " + element['Approver'];
                    adj.start = "COMPLETED";
                    adj.id_period = this.actualPeriod.idperiods;
                    adj.reason = 'Closing Exception'
                    adj.time_before = '0';
                    adj.time_after = (0 + Number(element['Amount'])).toFixed(3);
                    adj.amount = element['Amount'];
                    adj.state = "COMPLETED";
                    adj.start = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
                    adj.end = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
                    adj.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                    adj.id_process = emp[0].name;
                    this.apiServices.insertAttJustification(adj).subscribe((str: string) => {
                      if (str == "1") {
                        this.payroll_values.forEach(py_v => {
                          if (py_v.id_employee == emp[0].idemployees) {
                            py_v.discounted_hours = (Number(py_v.discounted_hours) + Number(adj.amount)).toFixed(3);
                            this.apiServices.updatePayroll_values(py_v).subscribe((str2: string) => {
                              if (str2 == '1') {
                                adj.error = "SUCCESS";
                              } else {
                                adj.error = str2.split("|")[1];
                              }
                              this.adjustments.push(adj);
                              t++;
                              if (t >= (sheetToJson.length - 1)) {
                                this.waitForfinish = false;
                              }
                            })
                          }
                        })
                      } else {
                        adj.error = str.split("|")[1];
                        this.adjustments.push(adj);
                        t++;
                        if (t >= (sheetToJson.length - 1)) {
                          this.waitForfinish = false;
                        }
                      }
                    })
                  } else if (element['Action'] == '2') {
                    adj.adj_type = 'Days';
                    adj.id_attendence = att[0].idattendences;
                    adj.id_user = this.authUser.getAuthusr().iduser;
                    adj.id_type = '2';
                    adj.id_department = this.authUser.getAuthusr().department;
                    adj.notes = "Closing Day Exception Authorized By: " + element['Approver'];
                    adj.start = "COMPLETED";
                    adj.id_period = this.actualPeriod.idperiods;
                    adj.reason = 'Closing Exception'
                    adj.time_before = '0';
                    adj.time_after = (0 + Number(element['Amount'])).toFixed(3);
                    adj.amount = element['Amount'];
                    adj.state = "COMPLETED";
                    adj.start = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
                    adj.end = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
                    adj.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                    adj.id_process = emp[0].name;
                    this.apiServices.insertAttJustification(adj).subscribe((str: string) => {
                      if (str == "1") {
                        this.payroll_values.forEach(py_v => {
                          if (py_v.id_employee == emp[0].idemployees) {
                            py_v.discounted_days = (Number(py_v.discounted_days) + Number(adj.amount)).toFixed(3);
                            this.apiServices.updatePayroll_values(py_v).subscribe((str2: string) => {
                              if (str2 == '1') {
                                adj.error = "SUCCESS";
                              } else {
                                adj.error = str2.split("|")[1];
                              }
                              this.adjustments.push(adj);
                              t++;
                              if (t >= (sheetToJson.length - 1)) {
                                this.waitForfinish = false;
                              }
                            })
                          }
                        })
                      } else {
                        adj.error = str.split("|")[1];
                        this.adjustments.push(adj);
                        t++;
                        if (t >= (sheetToJson.length - 1)) {
                          this.waitForfinish = false;
                        }
                      }
                    })
                  } else if (element['Action'] == '3') {
                    att.forEach(attendance => {
                      if (attendance.date == element['Date']) {
                        adj.id_attendence = attendance.idattendences;
                        adj.adj_type = 'Hours For Day :' + attendance.date;
                      }
                    })
                    adj.id_user = this.authUser.getAuthusr().iduser;
                    adj.id_type = '2';
                    adj.id_department = this.authUser.getAuthusr().department;
                    adj.notes = "Closing Hours for " + element['Date'] + "Exception Authorized By: " + element['Approver'];
                    adj.start = "COMPLETED";
                    adj.id_period = this.actualPeriod.idperiods;
                    adj.reason = 'Closing Exception'
                    adj.time_before = '0';
                    adj.time_after = (0 + Number(element['Amount'])).toFixed(3);
                    adj.amount = element['Amount'];
                    adj.state = "COMPLETED";
                    adj.start = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
                    adj.end = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
                    adj.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                    adj.id_process = emp[0].name;
                    this.apiServices.insertAttJustification(adj).subscribe((str: string) => {
                      if (str == "1") {
                        this.payroll_values.forEach(py_v => {
                          if (py_v.id_employee == emp[0].idemployees) {
                            if ((Number(py_v.discounted_hours) + Number(adj.amount)) <= 0) {
                              if (Number(py_v.ot_hours) + Number(adj.amount) <= 0) {
                                py_v.discounted_hours = (Number(py_v.ot_hours) + Number(adj.amount) + Number(py_v.discounted_hours)).toFixed(3)
                                py_v.ot_hours = '0'
                              } else {
                                py_v.discounted_hours = (Number(py_v.discounted_hours) + Number(adj.amount)).toFixed(3);
                              }
                            } else {
                              py_v.ot_hours = (Number(py_v.ot_hours) + Number(adj.amount) + Number(py_v.discounted_hours)).toFixed(3);
                              py_v.discounted_hours = '0';
                            }
                            this.apiServices.updatePayroll_values(py_v).subscribe((str2: string) => {
                              if (str2 == '1') {
                                let att_paid: paid_attendances = new paid_attendances;
                                att_paid.id_payroll_value = py_v.idpayroll_values;
                                att_paid.date = element['Date'];
                                att_paid.worked = element['Amount'];
                                this.apiServices.updatePaid_attendances(att_paid).subscribe((str4: string) => {
                                  if (str4 == '1') {
                                    adj.error = "SUCCESS";
                                    t++;
                                    this.adjustments.push(adj);
                                    if (t >= (sheetToJson.length - 1)) {
                                      this.waitForfinish = false;
                                    }
                                  } else {
                                    adj.error = str4.split("|")[1];
                                    t++;
                                    this.adjustments.push(adj);
                                    if (t >= (sheetToJson.length - 1)) {
                                      this.waitForfinish = false;
                                    }
                                  }
                                })
                              } else {
                                adj.error = str2.split("|")[1];
                                t++;
                                this.adjustments.push(adj);
                                if (t >= (sheetToJson.length - 1)) {
                                  this.waitForfinish = false;
                                }
                              }
                            })
                          }
                        })
                      } else {
                        adj.error = str.split("|")[1];
                        this.adjustments.push(adj);
                        t++;
                        if (t >= (sheetToJson.length - 1)) {
                          this.waitForfinish = false;
                        }
                      }
                    })
                  }
                } else {
                  adj.error = "Employee Had No Attendance";
                  this.adjustments.push(adj);
                  t++;
                  if (t >= (sheetToJson.length - 1)) {
                    this.waitForfinish = false;
                  }
                }
              })
            } else {
              adj.error = "No Employee To Match";
              this.adjustments.push(adj);
              t++;
              if (t >= (sheetToJson.length - 1)) {
                this.waitForfinish = false;
              }
            }
          }
        })
      })
    }
  }

  exportTableElmToExcel(element: ElementRef, fileName: string): void {
    const EXCEL_EXTENSION = '.xlsx';
    const ws: XLSX.WorkSheet = XLSX.utils.table_to_sheet(element.nativeElement);
    // generate workbook and add the worksheet
    const workbook: XLSX.WorkBook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, ws, 'Sheet1');
    // save to file
    XLSX.writeFile(workbook, `${fileName}${EXCEL_EXTENSION}`);

  }


  addfile_bonus(event) {
    this.working = true;
    this.credits = [];
    this.p_val_update = [];
    this.file = event.target.files[0];
    let partial_credits: credits[] = [];
    let fileReader = new FileReader();
    let found: boolean = false;
    let provitional_period: periods = new periods;
    provitional_period.end = this.actualPeriod.end;
    provitional_period.idperiods = this.actualPeriod.idperiods;
    provitional_period.start = "from_close";
    provitional_period.status = this.actualPeriod.status;
    provitional_period.type_period = this.actualPeriod.type_period;

    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      if (!this.completed) {
        this.arrayBuffer = fileReader.result;
        var data = new Uint8Array(this.arrayBuffer);
        var arr = new Array();
        for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
        var bstr = arr.join("");
        var workbook = XLSX.read(bstr, { type: "binary" });
        workbook.SheetNames.forEach(sheets => {
          let ws:number = 0;
          var first_sheet_name = workbook.SheetNames[ws];
          var worksheet = workbook.Sheets[first_sheet_name];
          let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
          sheetToJson.forEach(element => {
            let cred: credits = new credits;
            cred.iddebits = element['Nearsol ID'];
            cred.amount = element['Amount'];
            partial_credits.push(cred);
          })
          ws++;
        })
        let count: number = 0;
        this.apiServices.getPayments(provitional_period).subscribe((paymnts: payments[]) => {
          partial_credits.forEach(ele => {
            this.apiServices.getSearchEmployees({ dp: 'exact', filter: 'nearsol_id', value: ele.iddebits }).subscribe((emp: employees[]) => {
              if (!isNullOrUndefined(emp[0])) {
                paymnts.forEach(py => {
                  if (py.id_employee == emp[0].idemployees) {
                    ele.idpayments = py.idpayments;
                    this.payroll_values.forEach((p_val:payroll_values_gt)=>{
                      if(p_val.id_payment == py.idpayments){
                        if(this.import_type == "NEARSOL BONUS"){
                          p_val.nearsol_bonus = (Number(p_val.nearsol_bonus) + Number(ele.amount)).toFixed(2);
                        }else if(this.import_type == "CLIENT BONUS"){
                          p_val.performance_bonus = (Number(p_val.performance_bonus) + Number(ele.amount)).toFixed(2);
                        }else if(this.import_type == "TREASURE HUNT"){
                          p_val.treasure_hunt = (Number(p_val.treasure_hunt) + Number(ele.amount)).toFixed(2);
                        }else if(this.import_type == "AJUSTES A PERIODOS ANTERIORES HORAS"){
                          p_val.adj_hours = ele.amount;
                        }else if(this.import_type == "AJUSTES A PERIODOS ANTERIORES OT"){
                          p_val.adj_ot = ele.amount;
                        }else if(this.import_type == "AJUSTES A PERIODOS ANTERIORES ASUETOS"){
                          p_val.adj_holidays = ele.amount;
                        }
                        this.p_val_update.push(p_val);
                      }
                    })
                  }
                });
                ele.notes = emp[0].name;
              } else {
                ele.notes = "ERROR";
              }
              count = count + 1;
              ele.type = this.import_type;
              this.credits.push(ele);
              if (count >= (partial_credits.length - 1)) {
                this.importEnd = true;
                this.completed = true;
              }              
            })
          })
        })
      }
    }
  }

  saveImport(){
    this.saving = true;
    let cnt:number = 0;
    if(this.import_type == "CLIENT BONUS"){
      this.credits.forEach((cred:credits)=>{
        cred.type = "Bonos Diversos Cliente TK";
        this.apiServices.insertCredits(cred).subscribe((str:string)=>{
          cnt++;
          if(cnt >= this.credits.length - 1){
            this.saving = false;
            this.working = false;
            this.importEnd = false;
          }
        });
      })
    }else if(this.import_type == "NEARSOL BONUS"){
      this.credits.forEach((cred:credits)=>{
        cred.type = "Bonos Diversos Nearsol TK";
        this.apiServices.insertCredits(cred).subscribe((str:string)=>{
          cnt++;
          if(cnt >= this.credits.length - 1){
            this.saving = false;
            this.working = false;
            this.importEnd = false;
          }
        });
      })
    }else if(this.import_type == "TREASURE HUNT"){
      this.credits.forEach((cred:credits)=>{
        cred.type = "Treasure Hunt";
        this.apiServices.insertCredits(cred).subscribe((str:string)=>{
          cnt++;
          if(cnt >= this.credits.length - 1){
            this.saving = false;
            this.working = false;
            this.importEnd = false;
          }
        });
      })
    }else{
      cnt = 0;
      this.credits.forEach(adj=>{
        let adjustment:timekeeping_adjustments = new timekeeping_adjustments();
        adjustment.id_payment = adj.idpayments;
        if(this.import_type == "AJUSTES A PERIODOS ANTERIORES HORAS"){
          adjustment.amount_hrs = adj.amount;
        }else  if(this.import_type == "AJUSTES A PERIODOS ANTERIORES OT"){
          adjustment.amount_ot = adj.amount;
        } else  if(this.import_type == "AJUSTES A PERIODOS ANTERIORES ASUETOS"){
          adjustment.amount_holidays = adj.amount;
        }
        this.apiServices.insertTkAdjustments(adjustment).subscribe((str:string)=>{
          cnt++;
          if(cnt >= this.credits.length - 1){
            this.saving = false;
            this.working = false;
            this.importEnd = false;
          }
        });
      })
    }
    this.start();
  }
  
  disableDate(){
    this.disable_date = true;
  }

  changeDeadlineDate(str:string){
    this.deadline_date = str;
    this.disable_date = false;
  }

  disableTime(){
    this.disable_time = true;
  }

  changeDeadlineTime(str:string){
    this.deadline_time = str;
    this.disable_time = false;
  }

  enableDate(){
    this.disable_date = false;
  }

  enableTime(){
    this.disable_time = false;
  }
}
