import { isNull } from '@angular/compiler/src/output/output_ast';
import { NumberFormatStyle } from '@angular/common';
import { formattedError, IfStmt, ThrowStmt } from '@angular/compiler';
import { Route } from '@angular/compiler/src/core';
import { Component, ComponentFactoryResolver, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Console, timeStamp } from 'console';
import { AttachSession } from 'protractor/built/driverProviders';
import { parse } from 'querystring';
import { isNull, isNullOrUndefined, isUndefined } from 'util';
import { schedule_visit } from '../addTemplate';
import { ApiService } from '../api.service';
import { employees, hrProcess } from '../fullProcess';
import { attendences, attendences_adjustment, credits, deductions, disciplinary_processes, judicials, leaves, ot_manage, payments, periods, services, vacations, isr, accounts, payroll_values, payroll_values_gt, terminations, rises, paid_attendances, clients, billing_detail, debits, timekeeping_adjustments, accountsCount } from '../process_templates';
import * as XLSX from 'xlsx';
import { Observable, of } from 'rxjs';
import { promise } from 'protractor';
import { resolve } from 'url';
import { DH_NOT_SUITABLE_GENERATOR } from 'constants';
import { runInThisContext } from 'vm';
import { JitCompilerFactory } from '@angular/platform-browser-dynamic';
import { process } from '../process';
import { JsonpClientBackend } from '@angular/common/http';
import { ɵangular_packages_platform_browser_dynamic_testing_testing_a } from '@angular/platform-browser-dynamic/testing';
import { AuthServiceService } from '../auth-service.service';


@Component({
  selector: 'app-periods',
  templateUrl: './periods.component.html',
  styleUrls: ['./periods.component.css']
})

export class PeriodsComponent implements OnInit {
  completed: boolean = false;
  file: any;
  arrayBuffer: any;
  filelist: any;
  importEnd: boolean = false;
  selectedEmployee: boolean = false;
  attendances: attendences[] = [];
  deductions: deductions[] = [];
  debits: debits[] = [];
  credits: credits[] = [];
  vacations: vacations[] = [];
  leaves: leaves[] = [];
  employees: employees[] = [];
  payments: payments[] = [];
  global_credits: credits[] = [];
  global_debits: debits[] = [];
  global_judicials: judicials[] = [];
  global_services: services[] = [];
  credits_updates: credits[] = [];
  debits_updates: debits[] = [];
  backUp_payments: payments[];
  selected_accounts: string = 'GET ALL';
  period: periods = new periods;
  daysOff: number = 0;
  roster: number = 0;
  attended: number = 0;
  diff: number = 0;
  absence: number = 0;
  totalDebits: number = 0;
  totalCredits: number = 0;
  seventh: number = 0;
  progress: number = 0;
  filter: string = 'name';
  absence_fixed: string = null;
  value: string = null;
  ded: boolean = true;
  non_show_2: boolean = false;
  showPayments: boolean = false;
  searchClosed: boolean = false;
  importActive: boolean = false;
  working: boolean = false;
  count_payments: number = 0;
  importType: string = 'Bonus';
  importString: string = 'Bonos Diversos';
  loading: boolean = false;
  isrs: isr[] = [];
  accounts: accountsCount[] = [];
  payroll_values: payroll_values_gt[] = [];
  max_progress: number = 0;
  selectedPayment: payments = new payments;
  detailed_attendance: paid_attendances[] = [];
  detailed_credits: credits[] = [];
  detailed_debits: debits[] = [];
  selected_payroll_value: payroll_values_gt = new payroll_values_gt;
  detailed_hrs: string = null;
  selectedAccount: accounts = new accounts;
  clients: clients[] = [];
  show_payments: payments[] = [];
  selectedClient: string = null;
  base: boolean = false;
  emp_set: string = null;
  selected_patrono: string = 'PRG Recurso Humano, S.A.';
  payrollvalues: payroll_values_gt[] = [];
  adj_values: timekeeping_adjustments[] = [];
  closing_import: boolean = false;
  working_import: boolean = true;
  conflictedPeriods: payments[] = [];
  setImport:payroll_values_gt[] = [];
  loading_import:boolean = false;
  save_import:boolean = false;
  loading_save:boolean = false;
  loadAll:boolean = false

  constructor(public apiService: ApiService, public route: ActivatedRoute, public authService: AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.getAccounts();
    this.getDeductions();
    this.apiService.getFilteredPeriods({ id: this.route.snapshot.paramMap.get('id') }).subscribe((p: periods) => {
      this.period = p;
      if (this.period.status == '0') {
        let provitional_period: periods = new periods;
        provitional_period.start = 'from_close';
        provitional_period.idperiods = this.period.idperiods;
        provitional_period.end = this.period.end;
        provitional_period.status = this.period.status;
        this.apiService.getPayments(provitional_period).subscribe((py: payments[]) => {
          this.payments = py;
          this.apiService.getClients().subscribe((cl: clients[]) => {
            this.clients = cl;
            this.setClient(cl[0].idclients);
          })
        })
        this.showPayments = true;
      };
    });
    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
    this.apiService.getClients().subscribe((cl: clients[]) => {
      this.clients = cl;
      this.setClient(cl[0].idclients);
    })
  }

  getAccounts() {
    this.accounts = [];
    this.apiService.getAccounts().subscribe((acc: accountsCount[]) => {
      this.apiService.getPayroll_values_gt(this.period).subscribe((pv: payroll_values_gt[]) => {
        acc.filter(a => a.id_client == acc[0].id_client).forEach(account => {
          account.payrollCount = 0;
          if (!isNullOrUndefined(pv)) {
            pv.forEach(p => {
              if (account.idaccounts == p.id_account) {
                account.payrollCount++;
              }
            });
          }
          this.accounts.push(account);
        });
        this.setAccount_sh(this.accounts[0]);
      });
    });
  }

  getDeductions() {
    this.apiService.getDeductions({ id: this.route.snapshot.paramMap.get('id') }).subscribe((de: deductions[]) => {
      this.deductions = de;
    })
  }

  cancelSearch() {
    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
    this.start()
  }

  searchEmployee() {
    if (this.ded) {
      this.apiService.getFilteredDeductions({ id: this.period.idperiods, filter: this.filter, value: this.value }).subscribe((db: deductions[]) => {
        this.deductions = db;
      })
    } else {
      this.apiService.getSearchEmployees({ dp: 'all', filter: this.filter, value: this.value, rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
        this.employees = emp;
      })
    }
  }

  showAll() {
    this.apiService.getallEmployees({ department: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
    this.ded = false;
  }


  closePeriod() {
    if (this.period.status != '3') {
      window.alert("Period is still open, TimeKeeping must frozen it before run this closing");
    } else {
      this.working = true;
      this.loading = true;
      this.payments = [];
      this.global_credits = [];
      this.global_debits = [];
      this.global_judicials = [];
      this.credits_updates = [];
      this.debits_updates = [];
      let cnt: number = 0;
      this.showPayments = false;
      this.apiService.getPayroll_values_gt(this.period).subscribe((pv: payroll_values_gt[]) => {
        this.max_progress = pv.length - 1;
        if (this.base) {
          let p_toset: payroll_values_gt = new payroll_values_gt;
          pv.forEach(p => {
            if (p.id_employee == this.emp_set) {
              p_toset = p;
            }
          })
          pv = [];
          pv.push(p_toset);
        }
        pv.forEach(payroll_value => {
          let is_trm: boolean = false;
          let py: payments = new payments;
          this.apiService.getSearchEmployees({ dp: "exact", filter: "idemployees", value: payroll_value.id_employee, rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
            this.apiService.getTermdt(emp[0]).subscribe((trm: terminations) => {
              this.apiService.getClosingRise({ id_employee: emp[0].idemployees, start: this.period.start, end: this.period.end }).subscribe((rises: rises) => {
                this.apiService.getTransfers({ id_employee: emp[0].idemployees, start: this.period.start, end: this.period.end }).subscribe((trns: hrProcess) => {
                  this.apiService.getCredits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((cred: credits[]) => {
                    this.apiService.getDebits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((deb: debits[]) => {
                      this.apiService.getJudicialDiscounts({ id: emp[0].idemployees }).subscribe((judicials: judicials[]) => {
                        this.apiService.getServicesDiscounts({ id: emp[0].idemployees, date: this.period.end }).subscribe((services: services[]) => {

                          if (!isNullOrUndefined(trm.valid_from) && (new Date(trm.valid_from).getTime() >= new Date(this.period.start).getTime()) && (new Date(trm.valid_from).getTime() <= new Date(this.period.end).getTime())) {
                            is_trm = true;
                            py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - Number(payroll_value.seventh)).toFixed(2);
                          } else {
                            py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - Number(payroll_value.seventh)).toFixed(2);
                            if (new Date(trm.valid_from).getTime() <= new Date(this.period.start).getTime()) {
                              py.days = '0';
                            }
                          }

                          let base_salary: number = Number(emp[0].base_payment) / (240);
                          let productivity_salary: number = 0;

                          if (!isNullOrUndefined(rises.effective_date) || new Date(rises.effective_date).getTime() <= new Date(this.period.end).getTime()) {
                            productivity_salary = ((Number(rises.old_salary) - Number(emp[0].base_payment) - 250) / 30) * (((new Date(rises.effective_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24)));
                            productivity_salary = productivity_salary + ((Number(rises.new_salary) - Number(emp[0].base_payment) - 250) / 30) * (15 - (((new Date(rises.effective_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24))));
                            productivity_salary = productivity_salary / 120;

                          } else {
                            productivity_salary = ((Number(emp[0].productivity_payment) - 250) / 240);
                          }

                          if (new Date(emp[0].hiring_date).getTime() > new Date(this.period.start).getTime()) {
                            py.days = (((((new Date(this.period.end).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24)) + 1) - Number(payroll_value.discounted_days) - Number(payroll_value.seventh) + (Number(payroll_value.discounted_hours) / 8) - (((new Date(emp[0].hiring_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24))))).toFixed(2);
                            if (((new Date(emp[0].hiring_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24)) >= 14) {
                              py.days = (((((new Date(this.period.end).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24)) + 1) - Number(payroll_value.discounted_days) - Number(payroll_value.seventh) + (Number(payroll_value.discounted_hours) / 8) - (((new Date(emp[0].hiring_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24))))).toFixed(2);
                            }
                          }


                          if (!isNullOrUndefined(trns)) {
                            if (new Date(trns.date).getTime() >= new Date(this.period.start).getTime() && new Date(trns.date) <= new Date(this.period.end)) {
                              if (payroll_value.id_account == emp[0].id_account) {
                                py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - (((new Date(trns.date).getTime() - (new Date(this.period.start).getTime()))) / (1000 * 3600 * 24))).toFixed(2);
                              } else {
                                py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - (((new Date(this.period.end).getTime()) - (new Date(trns.date).getTime())) / (1000 * 3600 * 24))).toFixed(2);
                              }
                            }
                          }

                          if ((Number(py.days)) >= (((new Date(this.period.end).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24)) + 1) && (((Number(payroll_value.discounted_hours) * (- 1)) / 8) + Number(payroll_value.discounted_days) + Number(payroll_value.seventh)) == 0) {
                            py.days = "15";
                          }

                          if (Number(py.days) >= 15) {
                            py.days = "15";
                          }

                          if (Number(py.days) <= 0) {
                            py.days = "0";
                          }

                          if (is_trm) {
                            if ((Number(payroll_value.discounted_days) + Number(payroll_value.seventh) + (((new Date(this.period.end).getTime()) - (new Date(trm.valid_from).getTime())) / (1000 * 3600 * 24) + 1)) >= (((new Date(this.period.end).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24)) + 1)) {
                              py.days = '0';
                            }
                          }
                          py.id_employee = payroll_value.id_employee;
                          py.idpayments = payroll_value.id_payment;
                          py.id_period = payroll_value.id_period;
                          py.nearsol_id = payroll_value.nearsol_id;
                          py.ot_hours = payroll_value.ot_hours;
                          py.productivity_complete = (Number(emp[0].productivity_payment) - 250).toFixed(2);
                          py.productivity_hours = (Number(py.days) * 8).toFixed(2);
                          py.seventh = payroll_value.seventh;
                          py.account = payroll_value.account_name;
                          py.base_complete = emp[0].base_payment;
                          py.base_hours = (Number(py.days) * 8).toFixed(2);
                          py.client_id = payroll_value.client_id;
                          py.employee_name = emp[0].name;
                          py.holidays_hours = payroll_value.holidays_hours;
                          py.idpayroll_values = payroll_value.idpayroll_values;
                          py.holidays = (Number(payroll_value.holidays_hours) * (base_salary + productivity_salary + (250 / 240)) * 1.5).toFixed(2);
                          py.base = (Number(base_salary) * Number(py.base_hours)).toFixed(2);
                          py.productivity = (Number(productivity_salary) * Number(py.productivity_hours)).toFixed(2);

                          if (emp[0].job != 'Supervisor De Operaciones' && emp[0].id_account != '13' && emp[0].id_account != '25' && emp[0].id_account != '22' && emp[0].id_account != '23' && emp[0].id_account != '26' && emp[0].id_account != '12' && emp[0].id_account != '20' && emp[0].id_account != '38') {
                            py.ot = ((Number(base_salary) + Number(productivity_salary) + (250 / 240)) * Number(py.ot_hours) * 2).toFixed(2)
                          } else {
                            py.ot = ((Number(base_salary) + Number(productivity_salary) + (250 / 240)) * Number(py.ot_hours) * 1.5).toFixed(2)
                          }

                          let base_credit: credits = new credits;
                          let productivity_credit: credits = new credits;
                          let decreot_credit: credits = new credits;
                          let ot_credit: credits = new credits;
                          let holiday_credit: credits = new credits;
                          let adjustments: credits = new credits;
                          let isr: number = 0;
                          let bonus:number = 0;

                          let igss_debit: debits = new debits;

                          base_credit.amount = py.base;
                          base_credit.idpayments = py.idpayments;
                          base_credit.type = "Salario Base";

                          productivity_credit.amount = py.productivity;
                          productivity_credit.idpayments = py.idpayments;
                          productivity_credit.type = "Bonificacion Productividad";

                          decreot_credit.amount = ((250 / 240) * (Number(py.base_hours))).toFixed(2);
                          decreot_credit.idpayments = py.idpayments;
                          decreot_credit.type = "Bonificacion Decreto";

                          if (Number(py.ot) > 0) {
                            ot_credit.amount = py.ot;
                            ot_credit.idpayments = py.idpayments;
                            ot_credit.type = "Horas Extra Laboradas: " + py.ot_hours;
                            this.global_credits.push(ot_credit);
                          }

                          if (Number(py.holidays) > 0) {
                            holiday_credit.amount = py.holidays;
                            holiday_credit.idpayments = py.idpayments;
                            holiday_credit.type = "Horas De Asueto: " + py.holidays_hours;
                            this.global_credits.push(holiday_credit);
                          }

                          if (Math.abs(Number(payroll_value.adj_hours)) > 0 || Math.abs(Number(payroll_value.adj_holidays)) > 0 || Math.abs(Number(payroll_value.adj_ot)) > 0 || Math.abs(Number(payroll_value.performance_bonus)) > 0 || Math.abs(Number(payroll_value.nearsol_bonus)) > 0 || Math.abs(Number(payroll_value.treasure_hunt)) > 0) {

                            let adjustment_base: credits = new credits;
                            let adjustment_ot: credits = new credits;
                            let adjustment_hld: credits = new credits;
                            let performance_bonus:credits = new credits;
                            let treasure_hunt:credits = new credits;
                            let nearsol_bonus:credits = new credits;

                            adjustment_base.amount = (Number(payroll_value.adj_hours) * (Number(base_salary) + Number(productivity_salary) + (250 / 240))).toFixed(2);
                            adjustment_base.type = "Ajuste Horas Nominales";
                            adjustment_base.idpayments = py.idpayments;

                            if(Number(payroll_value.performance_bonus) != 0){
                              performance_bonus.amount = payroll_value.performance_bonus;
                              performance_bonus.type = "Performance Bonus";
                              performance_bonus.idpayments = py.idpayments;
                              this.global_credits.push(performance_bonus);
                              bonus = bonus + Number(performance_bonus.amount);
                            }

                            if(Number(payroll_value.nearsol_bonus) != 0){
                              nearsol_bonus.amount = payroll_value.nearsol_bonus;
                              nearsol_bonus.type = "Nearsol Bonus";
                              nearsol_bonus.idpayments = py.idpayments;
                              this.global_credits.push(nearsol_bonus);
                              bonus = bonus + Number(nearsol_bonus.amount);
                            }

                            if(Number(payroll_value.treasure_hunt) != 0){
                              treasure_hunt.amount = payroll_value.treasure_hunt;
                              treasure_hunt.type = "Treasure Hunt";
                              treasure_hunt.idpayments = py.idpayments;
                              this.global_credits.push(treasure_hunt);
                              bonus = bonus + Number(treasure_hunt.amount);
                            }

                            if (emp[0].job != 'Supervisor De Operaciones' && emp[0].id_account != '13' && emp[0].id_account != '25' && emp[0].id_account != '22' && emp[0].id_account != '23' && emp[0].id_account != '26' && emp[0].id_account != '12' && emp[0].id_account != '20' && emp[0].id_account != '38') {
                              adjustment_ot.amount = ((Number(payroll_value.adj_ot) * (Number(base_salary) + Number(productivity_salary) + (250 / 240)) * 2)).toFixed(2);
                              adjustment_ot.type = "Ajuste OT";
                            } else {
                              adjustment_ot.amount = ((Number(payroll_value.adj_ot) * (Number(base_salary) + Number(productivity_salary) + (250 / 240)) * 1.5)).toFixed(2);
                              adjustment_ot.type = "Ajuste OT";
                            }


                            adjustment_hld.amount = ((Number(payroll_value.adj_holidays) * (Number(base_salary) + Number(productivity_salary) + (250 / 240)) * 1.5)).toFixed(2);
                            adjustment_hld.type = "Ajuste HLD";

                            adjustment_hld.idpayments = py.idpayments;
                            adjustment_ot.idpayments = py.idpayments;
                            adjustments.idpayments = py.idpayments;

                            adjustments.amount = (Number(adjustment_base.amount) + Number(adjustment_hld.amount) + Number(adjustment_ot.amount)).toFixed(2);
                            adjustments.type = "Ajustes periodos anteriores";
                            this.global_credits.push(adjustment_base);
                            this.global_credits.push(adjustment_ot);
                            this.global_credits.push(adjustment_hld);
                          }

                          igss_debit.amount = ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * 0.0483).toFixed(2);
                          igss_debit.idpayments = py.idpayments;
                          igss_debit.type = "Descuento IGSS";

                          this.global_credits.push(base_credit);
                          this.global_credits.push(productivity_credit);
                          this.global_debits.push(igss_debit);
                          this.global_credits.push(decreot_credit);


                          let sum_cred: number = 0
                          cred.forEach(credit => {
                            if (credit.status == "PENDING" && credit.idpayments == py.idpayments) {
                              sum_cred = Number(Number(sum_cred) + Number(credit.amount));
                            }
                          })
                          py.credits = (Number(sum_cred) + Number(base_credit.amount) + Number(productivity_credit.amount) +
                                      Number(ot_credit.amount) + Number(holiday_credit.amount) + Number(decreot_credit.amount) +
                                      Number(adjustments.amount) + Number(bonus)).toFixed(2);


                          let sum_deb: number = 0
                          deb.forEach(debit => {
                            if (debit.status == "PENDING" && debit.idpayments == py.idpayments) {
                              sum_deb = sum_deb + Number(debit.amount);
                              if (debit.type = "ISR") {
                                isr = Number(debit.amount);
                              }
                            }
                          })
                          py.debits = (sum_deb + Number(igss_debit.amount)).toFixed(2);



                          services.forEach(service => {
                            if (service.type == '0') {
                              if ((Number(service.max) == 0 || Number(service.max) >= (Number(service.current) + Number(service.amount)))) {
                                let service_debit: debits = new debits;
                                service_debit.amount = Number(service.amount).toFixed(2);
                                service_debit.type = service.name;
                                service_debit.idpayments = py.idpayments;
                                py.debits = (Number(py.debits) + Number(service.amount)).toFixed(2);
                                service.current = (Number(service.current) + Number(service.amount)).toFixed(2);
                                this.global_debits.push(service_debit);
                              } else if (Number(service.max) != 0) {
                                if (Number(service.max) <= (Number(service.current) + Number(service.amount))) {
                                  let service_debit: debits = new debits;
                                  service_debit.amount = (Number(service.max) - Number(service.current)).toFixed(2);
                                  service_debit.type = service.name;
                                  service_debit.idpayments = py.idpayments;
                                  py.debits = (Number(py.debits) + Number(service.amount)).toFixed(2);
                                  service.current = (Number(service.current) + Number(service_debit.amount)).toFixed(2);
                                  service.status = '0';
                                  this.global_debits.push(service_debit);
                                }
                              }
                            } else if ((service.type == '1') && (Number(service.max) == 0 || Number(service.max) >= (Number(service.current) + Number(service.amount)))) {
                              let service_credit: credits = new credits;
                              service_credit.amount = Number(service.amount).toFixed(2);
                              service_credit.type = service.name;
                              service_credit.idpayments = py.idpayments;
                              py.credits = (Number(py.credits) + Number(service.amount)).toFixed(2);
                              service.current = (Number(service.current) + Number(service_credit.amount)).toFixed(2);
                              service.status = '0';
                              this.global_credits.push(service_credit);
                            } else if (Number(service.max) != 0) {
                              if (Number(service.max) <= (Number(service.current) + Number(service.amount))) {
                                let service_credit: debits = new debits;
                                service_credit.amount = (Number(service.max) - Number(service.current)).toFixed(2);
                                service_credit.type = service.name;
                                service_credit.idpayments = py.idpayments;
                                py.debits = (Number(py.debits) + Number(service.amount)).toFixed(2);
                                service.current = (Number(service.current) + Number(service_credit.amount)).toFixed(2);
                                service.status = '0';
                                this.global_credits.push(service_credit);
                              }
                            }
                            if (service.frecuency == "UNIQUE" || service.frecuency.includes("-")) {
                              service.status = '0';
                            }
                            this.global_services.push(service);
                          });

                          judicials.forEach(judicial => {
                            if (!isNullOrUndefined(judicial)) {
                              if (Number(judicial.max) == 0 || Number(judicial.max) > (Number(judicial.current) + ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * (Number(judicial.amount) / 100)))) {
                                let judicial_discount: debits;
                                judicial_discount = new debits();
                                judicial.current = (Number(judicial.current) + (((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount))) * (Number(judicial.amount) / 100))).toFixed(2);
                                judicial_discount.amount = ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * (Number(judicial.amount) / 100)).toFixed(2);
                                judicial_discount.type = "Descuento Judicial";
                                judicial_discount.idpayments = py.idpayments;
                                this.global_debits.push(judicial_discount);
                                this.global_judicials.push(judicial);
                                py.debits = (Number(py.debits) + Number(judicial_discount.amount)).toFixed(2);
                              } else if (Number(judicial.max) < (((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount))) * (Number(judicial.amount) / 100))) {
                                judicial.current = (Number(judicial.current) + ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * (Number(judicial.amount) / 100))).toFixed(2);
                                this.global_judicials.push(judicial);
                              }
                            }
                          })

                          py.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + (new Date().getDate());
                          py.total = (Number(py.credits) - Number(py.debits)).toFixed(2);

                          this.payments.push(py);
                          cnt = cnt + 1;
                          this.progress = cnt;
                          this.loading = false;
                          if (this.base) {
                            this.setPayTime(py);
                          }
                          if (cnt >= (pv.length)) {
                            this.working = false;
                            this.showPayments = true;
                            this.importActive = false;
                            this.setAccount_sh(this.selectedAccount);
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
    }
  }

  searchCloseEmployee() {
    let partial_payments: payments[] = [];
    this.showPayments = false;
    if (!this.searchClosed) {
      this.backUp_payments = this.payments;
    }
    this.payments.forEach(pay => {
      if (pay.employee_name.includes(this.value)) {
        partial_payments.push(pay);
      }
    })
    this.payments = partial_payments;
    this.searchClosed = false;
    this.showPayments = true;
  }


  cancelCloseSearch() {
    this.showPayments = false;
    this.searchClosed = true;
    this.payroll_values = [];
    this.payments = this.backUp_payments;
    this.start();
  }

  closeClose() {
    this.searchClosed = true;
    this.payments = [];
    this.payroll_values = [];
    this.ded = true;
    this.showPayments = false;
    this.start();
  }


  getHome() {
    window.open("./", "_self");
  }

  completePeriod() {
    this.working = true;
    this.completed = false;
    this.importActive = true;
    this.showPayments = false;
    this.max_progress = this.global_services.length + this.global_judicials.length + this.payments.length;
    this.progress = 1;
    try {
      this.payments.forEach(py => {
        this.progress = this.progress + 1;
        this.apiService.setPayment(py).subscribe((str_3: string) => {
          if (String(str_3).split("|")[0] == "0") {
            throw new Error('Error updating Payments');
          }
        })
      })
      this.pushDeductions('credits', this.global_credits);
      this.pushDeductions('debits', this.global_debits);
      this.global_services.forEach(service => {
        this.progress = this.progress + 1;
        if (Number(service.max) === Number(service.current) || service.frecuency == "UNIQUE") {
          service.status = '0';
        }
        this.apiService.updateServices(service).subscribe((str: string) => { });
      });
      this.global_judicials.forEach(judicial => {
        this.progress = this.progress + 1;
        this.apiService.updateJudicials(judicial).subscribe((str_r: string) => {
          if (str_r.split("|")[0] == '0') {
            throw new Error('Error updating Legal Deductions');
          }
        })
      })
      this.apiService.setCloseActualPeriods({ id_period: this.period.idperiods }).subscribe((str: string) => {
        if (str.split("|")[0] == 'Info:') {
          window.alert(String(str).split("|")[0] + "\n" + String(str).split("|")[1]);
          this.start();
        } else {
          throw new Error(String(str).split("|")[0] + "\n" + String(str).split("|")[1] + String(str).split("|")[2] + "\n" + String(str).split("|")[3]);
        }
      })
    } catch (error) {
      let e: Error = error;
      window.alert("An error has occured:\n" + e.message);
    } finally {
      this.progress = 0;
      this.max_progress = 0;
      this.working = false;
      this.importActive = false;
      this.showPayments = true;
      this.completed = true;
    }
  }

  setPayTime(payment: payments) {
    this.selectedPayment = payment;
    this.detailed_attendance = [];
    this.detailed_credits = [];
    this.detailed_debits = [];
    this.apiService.getPaidAttendances(this.period).subscribe((p_att: paid_attendances[]) => {
      this.apiService.getCredits({ id: payment.id_employee, period: this.period.idperiods }).subscribe((cred: credits[]) => {
        this.apiService.getDebits({ id: payment.id_employee, period: this.period.idperiods }).subscribe((deb: debits[]) => {
          this.apiService.getPayroll_values_gt(this.period).subscribe((pv: payroll_values_gt[]) => {

            pv.forEach(p => {
              if (p.idpayroll_values == payment.idpayroll_values) {
                this.selected_payroll_value = p;
                this.detailed_hrs = (Number(p.discounted_hours) + Number(p.ot_hours)).toFixed(2);
              }
            })

            p_att.forEach(paid_att => {
              if (paid_att.id_payroll_value == payment.idpayroll_values) {
                this.detailed_attendance.push(paid_att);
              }
            })

            cred.forEach(credit => {
              if (this.period.status == "3") {
                if (credit.status == "PENDING") {
                  this.detailed_credits.push(credit);
                }
              } else {
                this.detailed_credits.push(credit);
              }
            })

            deb.forEach(debit => {
              if (this.period.status == "3") {
                if (debit.status == "PENDING") {
                  this.detailed_debits.push(debit);
                }
              } else {
                this.detailed_debits.push(debit);
              }
            })

            this.global_credits.forEach(global_cred => {
              if (global_cred.idpayments == payment.idpayments) {
                this.detailed_credits.push(global_cred);
              }
            })

            this.global_debits.forEach(global_deb => {
              if (global_deb.idpayments == payment.idpayments) {
                this.detailed_debits.push(global_deb);
              }
            })
          })
        })
      })
    })
  }

  setPayTimeEmp(emp: employees) {
    this.emp_set = emp.idemployees;
    this.base = true;
    this.closePeriod();
  }

  activeImport() {
    this.importActive = true;
  }

  completeImport() {
    if (this.importType == 'Bonus') {
      this.pushDeductions('credits', this.credits);
    } else {
      this.pushDeductions('debits', this.debits);
    }
    this.completed = false;
    this.importActive = false;
    this.showPayments = true;
  }

  addfile(event: { target: { files: any[]; }; }) {
    this.credits = [];
    this.file = event.target.files[0];
    let partial_credits: credits[] = [];
    let fileReader = new FileReader();
    let found: boolean = false;
    let provitional_period: periods = new periods;
    provitional_period.end = this.period.end;
    provitional_period.idperiods = this.period.idperiods;
    provitional_period.start = "from_close";
    provitional_period.status = this.period.status;
    provitional_period.type_period = this.period.type_period;

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
          let ws: number = 0;
          var first_sheet_name = workbook.SheetNames[ws];
          var worksheet = workbook.Sheets[first_sheet_name];
          let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
          sheetToJson.forEach(element => {
            let cred: credits = new credits;
            cred.iddebits = element['Nearsol ID'];
            cred.amount = Number(element['Amount']).toFixed(2);
            partial_credits.push(cred);
          })
          ws++;
        })
        let count: number = 0;
        this.apiService.getPayments(provitional_period).subscribe((paymnts: payments[]) => {
          partial_credits.forEach(ele => {
            this.apiService.getSearchEmployees({ dp: 'exact', filter: 'nearsol_id', value: ele.iddebits, rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
              ele.type = this.importType;
              if (!isNullOrUndefined(emp[0])) {
                paymnts.forEach(py => {
                  if (py.id_employee == emp[0].idemployees) {
                    ele.idpayments = py.idpayments;
                  }
                });
                ele.notes = emp[0].name;
              } else {
                ele.notes = "ERROR";
              }
              count = count + 1;
              if (this.importType == "Bonus") {
                ele.type = this.importString;
                this.credits.push(ele);
              } else if (this.importType == "Discount") {
                let deb: debits = new debits;
                deb.iddebits = ele.iddebits;
                deb.amount = Number(ele.amount).toFixed(2);
                deb.date = ele.date;
                deb.id_employee = ele.id_employee;
                deb.idpayments = ele.idpayments;
                deb.notes = ele.notes;
                deb.type = this.importString;
                this.debits.push(deb);
              }

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

  pushDeductions(str: string, credits?: credits[], debits?: debits[]) {
    if (str == 'debits') {
      credits.forEach(cred => {
        if (cred.amount != 'NaN') {
          cred.amount = Number(cred.amount).toFixed(2);
          this.apiService.insertDebits(cred).subscribe((str: string) => { });
        }
      });
    } else {
      if (str == 'credits') {
        credits.forEach(cred => {
          if (cred.amount != 'NaN') {
            cred.amount = Number(cred.amount).toFixed(2);
            this.apiService.insertCredits(cred).subscribe((str: string) => { });
          }
        })
      }
    }
  }

  setType() {
    if (this.importType == 'ISR') {
      this.importString = 'Mensual';
    }
  }

  async exportPayrollReport() {
    try {
      window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportNominaReport.php?AID_Period=" + this.period.idperiods, "_self");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  async exportBankReport() {
    try {
      window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportBankReport.php?AID_Period=" + this.period.idperiods, "_self")
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  setAccount(str: string) {
    this.selected_accounts = str;
  }

  saveBilling() {
    let any: any = { "month": this.period.start.split("-")[1], "account": this.selected_accounts };
    let month: string = new Date(this.period.start).getMonth.toString();
    this.apiService.getBilling(month).subscribe((Billing: billing_detail) => {
      if (isNullOrUndefined(Billing)) {
        this.apiService.saveBilling(any).subscribe((str: string) => {
          // por el momento no hacer nada.
        })
      } else {
        this.exportBilling();
      }
    })
  }

  exportBilling() {
    let selectedPeriod: string = null;
    let cnt: number = 0;
    this.apiService.getPeriods().subscribe((pr: periods[]) => {
      pr.forEach(p => {
        if (p.start.split("-")[1] == this.period.start.split("-")[1]) {
          cnt++;
          if (!isNullOrUndefined(selectedPeriod)) {
            selectedPeriod = selectedPeriod + "," + p.idperiods;
          } else {
            selectedPeriod = p.idperiods;
          }
          if (cnt == 2) {
            window.open("http://172.18.2.45/phpscripts/exportBilling.php?period=" + selectedPeriod + "&netsuit=" + this.selected_accounts, "_self")
          }
        }
      })
    })
  }

  async setAccountingPolicy() {
    // Se encarga de generar la póliza contable al período en curso si no existiera.
    try {
      window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportAccountingPolicyReport.php?AID_Period=" + this.period.idperiods, "_blank")
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  setClient(cl: string) {
    this.accounts = [];
    this.selectedClient = cl;
    this.apiService.getAccounts().subscribe((acc: accountsCount[]) => {
      this.apiService.getPayroll_values_gt(this.period).subscribe((pv: payroll_values_gt[]) => {
        acc.filter(a => a.id_client == cl).forEach(account => {
          if (account.id_client == cl) {
            account.payrollCount = 0;
            if (!isNullOrUndefined(pv)) {
              pv.forEach(p => {
                if (account.idaccounts == p.id_account) {
                  account.payrollCount++;
                }
              });
            }
            this.accounts.push(account);
          }
        });
        this.setAccount_sh(this.accounts[0]);
      });
    })
  }

  setAccount_sh(acc: accounts) {
    this.selectedAccount = acc;
    this.show_payments = [];
    this.payments.forEach(p => {
      //if (p.account == acc.name || p.account == acc.idaccounts) {
      this.show_payments.push(p);
      //}
    })
  }

  delPayrollAccount(acc: accounts) {
    if (window.confirm("Are you sure you want to delete?")) {
      this.apiService.delPayrollAccount({ id_period: this.period.idperiods, id_account: acc.idaccounts }).subscribe((str: string) => {
        if (str.trim() === '') {
          window.alert("Payroll account deleted successfully.")
        } else {
          console.log(str);
        }
        this.ngOnInit();
      })
    }
  }

  setPatrono(str: string) {
    this.selected_patrono = str;
  }

  async exportIgss() {
    let user: string = this.authService.getAuthusr().signature;
    let patrono: string = this.selected_patrono;
    let address: string = "20 Calle 5-25";
    let nit_patrono: string = null;
    let patronal_number: string = null;
    if (patrono == 'PRG Recurso Humano, S.A.') {
      nit_patrono = "92956971";
      patronal_number = "145998";
    }
    let t_period: string = this.period.idperiods;
    try {
      window.open("http://172.18.2.45/phpscripts/exportIgss.php?user=" + user + "&patrono=" + patrono + "&address=" + address + "&nit_patrono=" + nit_patrono + "&patronal_number=" + patronal_number + "&period=" + t_period, "_blank");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  cancelImport() {
    this.closing_import = false;
    this.payroll_values = [];
  }

  setPayrollValues(event: { target: { files: any[]; }; }) {
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let pr: periods = new periods;
    let progress: number = 0;
    let progress_2: number = 0;
    let max_1: number = 0;
    let max_2: number = 0;

    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      if (!this.completed) {
        this.arrayBuffer = fileReader.result;
        var data = new Uint8Array(this.arrayBuffer);
        var arr = new Array();
        for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
        var bstr = arr.join("");
        var workbook = XLSX.read(bstr, { type: "binary" });
        var first_sheet_name = workbook.SheetNames[0];
        var first_sheet_name_2 = workbook.SheetNames[1];
        var worksheet = workbook.Sheets[first_sheet_name];
        let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
        max_1 = sheetToJson.length;
        sheetToJson.forEach(element => {
          this.apiService.getSearchEmployees({ dp: 'exact', filter: 'nearsol_id', value: element['ID'], rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
            if (!isNullOrUndefined(emp[0])) {
              pr.start = 'explicit_period';
              pr.status = emp[0].idemployees;
              pr.end = this.period.idperiods;
              this.apiService.getPayments(pr).subscribe((pymnts: payments[]) => {
                progress++;
                if(!isNullOrUndefined(pymnts[0])){
                if (!isNullOrUndefined(pymnts[0].idpayments)) {
                  let paymentValue = new payroll_values_gt;
                  if (pymnts.length == 1) {
                    paymentValue.status = '1';
                    paymentValue.client_id = element['Avaya'];
                    paymentValue.nearsol_id = element['ID'];
                    paymentValue.id_payment = pymnts[0].idpayments;
                    paymentValue.id_employee = emp[0].idemployees;
                    paymentValue.agent_name = element['Full Name'];
                    paymentValue.id_reporter = element['Supervisor'];
                    paymentValue.discounted_days = element['Days to discount'];
                    paymentValue.seventh = element['7th'];
                    paymentValue.total_days = element['Total Days to discount'];
                    paymentValue.discounted_hours = element['Hours to discount'];
                    paymentValue.ot_hours = element['OT'];
                    paymentValue.holidays_hours = element['Holiday Hours'];
                    paymentValue.performance_bonus = element['Performance Bonus'];
                    paymentValue.nearsol_bonus = element['Nearsol Bonus'];
                    paymentValue.treasure_hunt = element['Treasure Hunt'];
                    this.payrollvalues.push(paymentValue);
                  } else {
                    if (pymnts.length == 0) {
                      paymentValue.status = '0';
                      paymentValue.client_id = element['Avaya'];
                      paymentValue.nearsol_id = element['ID'];
                      paymentValue.id_payment = "NULL";
                      paymentValue.id_employee = emp[0].idemployees;
                      paymentValue.agent_name = element['Full Name'];
                      paymentValue.id_reporter = element['Supervisor'];
                      paymentValue.discounted_days = element['Days to discount'];
                      paymentValue.seventh = element['7th'];
                      paymentValue.total_days = element['Total Days to discount'];
                      paymentValue.discounted_hours = element['Hours to discount'];
                      paymentValue.ot_hours = element['OT'];
                      paymentValue.holidays_hours = element['Holiday Hours'];
                      paymentValue.performance_bonus = element['Performance Bonus'];
                      paymentValue.nearsol_bonus = element['Nearsol Bonus'];
                      paymentValue.treasure_hunt = element['Treasure Hunt'];
                      this.payrollvalues.push(paymentValue);
                    } else {
                      this.apiService.getTransfers({ id_employee: emp[0].idemployees, start: pr.start, end: pr.end }).subscribe((hr: hrProcess) => {
                        paymentValue.status = '3';
                        paymentValue.client_id = element['Avaya'];
                        paymentValue.nearsol_id = element['ID'];
                        pymnts.forEach(py => {
                          this.conflictedPeriods.push(py);
                        })
                        if(this.loadAll){
                          if(emp[0].id_account == element['ACCOUNT']){
                            this.conflictedPeriods.filter(a => a.id_employee == emp[0].idemployees).forEach(pys =>{
                              if(pys.id_account_py ==  element['ACCOUNT'] || isNullOrUndefined(pys.id_account_py)){
                                paymentValue.id_payment = pys.idpayments;
                                paymentValue.account_name = this.getAccountName(pys.id_account_py).name;
                              }
                            })
                          }
                        }else{
                          if(emp[0].id_account == this.selectedAccount.idaccounts){
                            this.conflictedPeriods.filter(a => a.id_employee == emp[0].idemployees).forEach(pys =>{
                              if(pys.id_account_py == this.selectedAccount.idaccounts || isNullOrUndefined(pys.id_account_py)){
                                paymentValue.id_payment = pys.idpayments;
                                paymentValue.account_name = this.getAccountName(pys.id_account_py).name;
                              }
                            })
                          }
                        }
                        paymentValue.id_employee = emp[0].idemployees;
                        paymentValue.agent_name = element['Full Name'];
                        paymentValue.id_reporter = element['Supervisor'];
                        paymentValue.discounted_days = element['Days to discount'];
                        paymentValue.seventh = element['7th'];
                        paymentValue.total_days = element['Total Days to discount'];
                        paymentValue.discounted_hours = element['Hours to discount'];
                        paymentValue.ot_hours = element['OT'];
                        paymentValue.holidays_hours = element['Holiday Hours'];
                        paymentValue.performance_bonus = element['Performance Bonus'];
                        paymentValue.nearsol_bonus = element['Nearsol Bonus'];
                        paymentValue.treasure_hunt = element['Treasure Hunt'];
                        this.payrollvalues.push(paymentValue);
                      })
                    }
                  }
                }else{
                  let paymentValue = new payroll_values_gt;
                  paymentValue.status = '0';
                  paymentValue.client_id = element['Avaya'];
                  paymentValue.nearsol_id = element['ID'];
                  paymentValue.id_payment = "NULL";
                  paymentValue.id_employee = emp[0].idemployees;
                  paymentValue.agent_name = element['Full Name'];
                  paymentValue.id_reporter = element['Supervisor'];
                  paymentValue.discounted_days = element['Days to discount'];
                  paymentValue.seventh = element['7th'];
                  paymentValue.total_days = element['Total Days to discount'];
                  paymentValue.discounted_hours = element['Hours to discount'];
                  paymentValue.ot_hours = element['OT'];
                  paymentValue.holidays_hours = element['Holiday Hours'];
                  paymentValue.performance_bonus = element['Performance Bonus'];
                  paymentValue.nearsol_bonus = element['Nearsol Bonus'];
                  paymentValue.treasure_hunt = element['Treasure Hunt'];
                  this.payrollvalues.push(paymentValue);
                }
              }else{
                this.apiService.getTransfers({id_employee:'exp', start:element['ID'] + "|" +this.period.start, end:this.period.end}).subscribe((proc:hrProcess)=>{
                  if(isNullOrUndefined(proc.id_employee)){
                    let paymentValue = new payroll_values_gt;
                    paymentValue.status = '0';
                    paymentValue.client_id = element['Avaya'];
                    paymentValue.nearsol_id = element['ID'];
                    paymentValue.id_payment = "NULL";
                    paymentValue.id_employee = emp[0].idemployees;
                    paymentValue.agent_name = element['Full Name'];
                    paymentValue.id_reporter = element['Supervisor'];
                    paymentValue.discounted_days = element['Days to discount'];
                    paymentValue.seventh = element['7th'];
                    paymentValue.total_days = element['Total Days to discount'];
                    paymentValue.discounted_hours = element['Hours to discount'];
                    paymentValue.ot_hours = element['OT'];
                    paymentValue.holidays_hours = element['Holiday Hours'];
                    paymentValue.performance_bonus = element['Performance Bonus'];
                    paymentValue.nearsol_bonus = element['Nearsol Bonus'];
                    paymentValue.treasure_hunt = element['Treasure Hunt'];
                    this.payrollvalues.push(paymentValue);
                  }else{
                    pr.start = 'explicit_period';
                    pr.status = emp[0].idemployees;
                    pr.end = this.period.idperiods;
                    this.apiService.getPayments(pr).subscribe((pymnts: payments[]) => {
                      pymnts.forEach(payment=>{
                        if(!isNullOrUndefined(payment.id_account_py)){
                          let paymentValue = new payroll_values_gt;
                          paymentValue.status = '1';
                          paymentValue.client_id = element['Avaya'];
                          paymentValue.nearsol_id = element['ID'];
                          paymentValue.id_payment = payment.idpayments;
                          paymentValue.id_employee = emp[0].idemployees;
                          paymentValue.agent_name = element['Full Name'];
                          paymentValue.id_reporter = element['Supervisor'];
                          paymentValue.discounted_days = element['Days to discount'];
                          paymentValue.seventh = element['7th'];
                          paymentValue.total_days = element['Total Days to discount'];
                          paymentValue.discounted_hours = element['Hours to discount'];
                          paymentValue.ot_hours = element['OT'];
                          paymentValue.holidays_hours = element['Holiday Hours'];
                          paymentValue.performance_bonus = element['Performance Bonus'];
                          paymentValue.nearsol_bonus = element['Nearsol Bonus'];
                          paymentValue.treasure_hunt = element['Treasure Hunt'];
                          this.payrollvalues.push(paymentValue);
                        }
                      })
                    })
                  }
                })
              }
                if (progress == sheetToJson.length) {
                  var worksheet_2 = workbook.Sheets[first_sheet_name_2];
                  let sheetToJson_2 = XLSX.utils.sheet_to_json(worksheet_2, { raw: true });
                  max_2 = sheetToJson_2.length;
                  sheetToJson_2.forEach(ele => {
                    this.payrollvalues.forEach(py_val => {
                      if (py_val.client_id == ele['Avaya'] && py_val.nearsol_id == ele['AID']) {
                        py_val.adj_hours = (Number(py_val.adj_hours) + (Number(this.ifNull(ele['Days to Pay'])) * 8) + Number(this.ifNull(ele['Regular Hours']))).toFixed(2);
                        py_val.adj_ot = (Number(py_val.adj_ot) + Number(this.ifNull(ele['OT']))).toFixed(2);
                        py_val.adj_holidays = (Number(py_val.adj_holidays) + Number(this.ifNull(ele['Horas Asueto']))).toFixed(2);
                        if (Number(py_val.adj_hours) > 0 || Number(py_val.adj_ot) > 0 || Number(py_val.adj_holidays) > 0) {
                          let adj: timekeeping_adjustments = new timekeeping_adjustments;
                          adj.amount_holidays = (Number(this.ifNull(ele['Horas Asueto']))).toFixed(2);
                          adj.amount_hrs = ((Number(this.ifNull(ele['Days to Pay'])) * 8) + Number(this.ifNull(ele['Regular Hours']))).toFixed(2);
                          adj.amount_ot = (Number(this.ifNull(ele['OT']))).toFixed(2);
                          adj.id_payment = py_val.id_payment;
                        }
                        py_val.performance_bonus = (Number(py_val.performance_bonus) + Number(this.ifNull(ele['Bono Performance']))).toFixed(2);
                        if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) > 0) {
                          let cred: credits = new credits;
                          cred.amount = Number(this.ifNull(ele['Reintegro de / bus parqueo'])).toFixed(2);
                          cred.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                          cred.id_employee = py_val.id_employee;
                          cred.id_user = this.authService.getAuthusr().iduser;
                          cred.idpayments = py_val.id_payment;
                          cred.status = 'PENDING';
                          cred.type = "Parking"
                          this.global_credits.push(cred);
                        } else if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) < 0) {
                          let deb: debits = new debits;
                          deb.amount = (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) * (-1)).toFixed(2);
                          deb.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                          deb.id_employee = py_val.id_employee;
                          deb.id_user = this.authService.getAuthusr().iduser;
                          deb.idpayments = py_val.id_payment;
                          deb.status = 'PENDING';
                          deb.type = "Parking";
                          this.global_debits.push(deb);
                        }
                        if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) > 0) {
                          let cred: credits = new credits;
                          cred.amount = Number(this.ifNull(ele['Reintegro de / bus parqueo'])).toFixed(2);
                          cred.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                          cred.id_employee = py_val.id_employee;
                          cred.id_user = this.authService.getAuthusr().iduser;
                          cred.idpayments = py_val.id_payment;
                          cred.status = 'PENDING';
                          cred.type = "Parking"
                          this.global_credits.push(cred);
                        } else if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) < 0) {
                          let deb: debits = new debits;
                          deb.amount = (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) * (-1)).toFixed(2);
                          deb.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                          deb.id_employee = py_val.id_employee;
                          deb.id_user = this.authService.getAuthusr().iduser;
                          deb.idpayments = py_val.id_payment;
                          deb.status = 'PENDING';
                          deb.type = "Parking";
                          this.global_debits.push(deb);
                        }
                      }
                    })
                    progress_2++;
                    if ((progress_2 + progress) == (max_1 + max_2)) {
                      this.orderAlerts()
                      this.closing_import = true;
                      this.working = false;
                    }
                  });
                  if ((progress_2 + progress) == (max_1 + max_2)) {
                    this.orderAlerts()
                    this.closing_import = true;
                    this.working = false;
                  }
                }
              })
            }else{
              progress++;
              let paymentValue = new payroll_values_gt;
              paymentValue.status = '0';
              paymentValue.client_id = element['Avaya'];
              paymentValue.nearsol_id = element['ID'];
              paymentValue.id_payment = "NULL";
              paymentValue.agent_name = element['Full Name'];
              paymentValue.id_reporter = element['Supervisor'];
              paymentValue.discounted_days = element['Days to discount'];
              paymentValue.seventh = element['7th'];
              paymentValue.total_days = element['Total Days to discount'];
              paymentValue.discounted_hours = element['Hours to discount'];
              paymentValue.ot_hours = element['OT'];
              paymentValue.holidays_hours = element['Holiday Hours'];
              paymentValue.performance_bonus = element['Performance Bonus'];
              paymentValue.treasure_hunt = element['Treasure Hunt'];
              this.payrollvalues.push(paymentValue);
              if (progress == sheetToJson.length) {
                var worksheet_2 = workbook.Sheets[first_sheet_name_2];
                let sheetToJson_2 = XLSX.utils.sheet_to_json(worksheet_2, { raw: true });
                max_2 = sheetToJson_2.length;
                sheetToJson_2.forEach(ele => {
                  this.payrollvalues.forEach(py_val => {
                    if (py_val.client_id == ele['Avaya'] && py_val.nearsol_id == ele['AID']) {
                      py_val.adj_hours = (Number(py_val.adj_hours) + (Number(this.ifNull(ele['Days to Pay'])) * 8) + Number(this.ifNull(ele['Regular Hours']))).toFixed(2);
                      py_val.adj_ot = (Number(py_val.adj_ot) + Number(this.ifNull(ele['OT']))).toFixed(2);
                      py_val.adj_holidays = (Number(py_val.adj_holidays) + Number(this.ifNull(ele['Horas Asueto']))).toFixed(2);
                      if (Number(py_val.adj_hours) > 0 || Number(py_val.adj_ot) > 0 || Number(py_val.adj_holidays) > 0) {
                        let adj: timekeeping_adjustments = new timekeeping_adjustments;
                        adj.amount_holidays = (Number(this.ifNull(ele['Horas Asueto']))).toFixed(2);
                        adj.amount_hrs = ((Number(this.ifNull(ele['Days to Pay'])) * 8) + Number(this.ifNull(ele['Regular Hours']))).toFixed(2);
                        adj.amount_ot = (Number(this.ifNull(ele['OT']))).toFixed(2);
                        adj.id_payment = py_val.id_payment;
                      }
                      py_val.performance_bonus = (Number(py_val.performance_bonus) + Number(this.ifNull(ele['Bono Performance']))).toFixed(2);
                      if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) > 0) {
                        let cred: credits = new credits;
                        cred.amount = Number(this.ifNull(ele['Reintegro de / bus parqueo'])).toFixed(2);
                        cred.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                        cred.id_employee = py_val.id_employee;
                        cred.id_user = this.authService.getAuthusr().iduser;
                        cred.idpayments = py_val.id_payment;
                        cred.status = 'PENDING';
                        cred.type = "Parking"
                        this.global_credits.push(cred);
                      } else if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) < 0) {
                        let deb: debits = new debits;
                        deb.amount = (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) * (-1)).toFixed(2);
                        deb.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                        deb.id_employee = py_val.id_employee;
                        deb.id_user = this.authService.getAuthusr().iduser;
                        deb.idpayments = py_val.id_payment;
                        deb.status = 'PENDING';
                        deb.type = "Parking";
                        this.global_debits.push(deb);
                      }
                      if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) > 0) {
                        let cred: credits = new credits;
                        cred.amount = Number(this.ifNull(ele['Reintegro de / bus parqueo'])).toFixed(2);
                        cred.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                        cred.id_employee = py_val.id_employee;
                        cred.id_user = this.authService.getAuthusr().iduser;
                        cred.idpayments = py_val.id_payment;
                        cred.status = 'PENDING';
                        cred.type = "Parking"
                        this.global_credits.push(cred);
                      } else if (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) < 0) {
                        let deb: debits = new debits;
                        deb.amount = (Number(this.ifNull(ele['Reintegro de / bus parqueo'])) * (-1)).toFixed(2);
                        deb.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                        deb.id_employee = py_val.id_employee;
                        deb.id_user = this.authService.getAuthusr().iduser;
                        deb.idpayments = py_val.id_payment;
                        deb.status = 'PENDING';
                        deb.type = "Parking";
                        this.global_debits.push(deb);
                      }
                    }
                  })
                  progress_2++;
                  if ((progress_2 + progress) == (max_1 + max_2)) {
                    this.closing_import = true;
                    this.working = false;
                  }
                });
              }
            }
          })
        })
      }
    }
  }

  ifNull(obj: string) {
    if (isNullOrUndefined(obj)) {
      return 0
    } else {
      if (obj == 'NaN') {
        return 0;
      } else {
        return obj;
      }
    }
  }

  sortedPayrollValues() {
    this.payroll_values = this.payrollvalues.sort((a, b) => (Number(a.status) - Number(b.status)));
  }

  getConflicted(pay:payments){
    return this.conflictedPeriods.filter(a => a.id_employee == pay.id_employee);
  }

  getAccountName(str: string){
    if(isNullOrUndefined(str) || str == '0' || str == '' || str == ' ' || str == 'NULL'){
      let a:accounts = new accounts;
      a.idaccounts = '0';
      a.name = this.selectedAccount.name
      return a;
    }else{
      return this.accounts.filter(a => a.idaccounts == str)[0];
    }
  }

  filterUnique(pay:payments){
    let str:string[] = [];
    let add:boolean = true;
    this.conflictedPeriods.filter(a=>a.id_employee == pay.id_employee).forEach(py=>{
      add = true;
      str.forEach(string=>{
        if(string == this.getAccountName(py.id_account_py).name){
          add = false;
        }
      })
      if(add == true){
        str.push(this.getAccountName(py.id_account_py).name);
      }
    })
    return str;
  }

  setPayrollValues_import(){
    let progress:number = 0;
    this.loading_import = true;
    this.payrollvalues.forEach(payroll_val=>{
      if(payroll_val.status == '1' || payroll_val.status == '3'){
        this.apiService.getSearchEmployees({ dp: "exact", filter: "idemployees", value: payroll_val.id_employee, rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
          if(!isNullOrUndefined(emp[0])){
            let pr:periods = new periods;
            pr.start = 'explicit_period';
            pr.status = emp[0].idemployees;
            pr.end = this.period.idperiods;
            this.apiService.getPayments(pr).subscribe((payment:payments[])=>{
              if(!isNullOrUndefined(payment[0])){
                progress++;
                payroll_val.id_account = emp[0].id_account;
                payroll_val.id_period = this.period.idperiods;
                payroll_val.id_reporter = emp[0].reporter;
                payroll_val.next_seventh = 0;
                let found:boolean = false;
                if(payroll_val.status == '3' && !found){
                  payment.forEach(pymnt=>{
                    if(!isNullOrUndefined(pymnt.id_account_py) && !found){
                      payroll_val.id_payment = pymnt.idpayments;
                      payroll_val.id_account = pymnt.id_account_py;
                      found = true;
                    }else{
                      if(isNullOrUndefined(pymnt.id_account_py) && !found){
                        payroll_val.id_payment = pymnt.idpayments;
                        found = true;
                      }
                    }
                  })
                  this.setImport.push(payroll_val);
                }
                if(this.loadAll){
                  if(emp[0].id_account == this.selectedAccount.idaccounts && payroll_val.status != '3'){
                    progress++;
                    this.setImport.push(payroll_val);
                    if(progress >= this.payrollvalues.length){
                      this.save_import = true;
                      this.loading_import = false;
                    }
                  }else{
                    progress++;
                    if(progress >= this.payrollvalues.length){
                      this.save_import = true;
                      this.loading_import = false;
                    }
                  }
                }else{
                  if(emp[0].id_account == this.selectedAccount.idaccounts && payroll_val.status != '3'){
                  progress++;
                  this.setImport.push(payroll_val);
                  if(progress >= this.payrollvalues.length){
                    this.save_import = true;
                    this.loading_import = false;
                  }
                }else{
                  progress++;
                  if(progress >= this.payrollvalues.length){
                    this.save_import = true;
                    this.loading_import = false;
                  }
                }
                }
              }else{
                progress++;
                if(progress >= this.payrollvalues.length){
                  this.save_import = true;
                  this.loading_import = false;
                }
              }
            })
          }else{
            progress++;
            if(progress >= this.payrollvalues.length){
              this.save_import = true;
              this.loading_import = false;
            }
          }
        })
      }else{
        progress++;
        if(progress >= this.payrollvalues.length){
          this.save_import = true;
          this.loading_import = false;
        }
      }
    })
  }

  orderAlerts(){
    this.payrollvalues = this.payrollvalues.sort((a,b) => Number(b.status) - Number(a.status));
  }

  setAccountDis(pys:payments, str:string){
    pys.account = str;
  }

  clearData(){
    this.payrollvalues = [];
    this.payroll_values = [];
    this.global_credits = [];
    this.global_debits = [];
    this.credits = [];
    this.debits = [];
    this.loading_import = false;
    this.loading_save = false;
  }

  saveImportedPayroll(){
    this.loading_save = true;
      this.apiService.insertPayroll_values_gt(this.payrollvalues.filter(a => a.status == '3' || a.status == '1')).subscribe((str:string)=>{
        if(str == '1'){
          window.alert("Payroll Values Successfully Imported")
        }else{
          window.alert("Please share the following error with your Administrator \n " + str );
        }
        this.credits = [];
        this.debits = [];
        this.credits = this.global_credits;
        this.debits = this.global_debits;
        if(!isNullOrUndefined(this.debits)){
          this.pushDeductions("credits",this.debits);
        }
        if(!isNullOrUndefined(this.credits)){
          this.pushDeductions("debits", this.credits);
        }
        window.alert("Process Successfuly Completed");
        this.loading_save = false;
        this.ngOnInit();
    })
  }

  togglePeriod() {
    let newStatus: string = '3';
    if (this.period.status == '1') {
      newStatus = '3'
    } else if (this.period.status == '3') {
      newStatus = '1'
    } else {
      window.alert("No available status to toggle.");
    }

    this.apiService.updatePeriods({ id: this.period.idperiods, status: newStatus }).subscribe((_str: string) => {
      window.alert("Period Updated Success");
      this.ngOnInit();
    })
  }
}
