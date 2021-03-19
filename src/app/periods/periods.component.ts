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
import { attendences, attendences_adjustment, credits, debits, deductions, disciplinary_processes, judicials, leaves, ot_manage, payments, periods, services, vacations, isr, accounts, payroll_values, payroll_values_gt, terminations, rises, paid_attendances, clients } from '../process_templates';
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
  accounts: accounts[] = [];
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
  base: boolean = false;;
  emp_set: string = null;

  constructor(public apiService: ApiService, public route: ActivatedRoute) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc;
    })
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
      this.apiService.getSearchEmployees({ dp: 'all', filter: this.filter, value: this.value }).subscribe((emp: employees[]) => {
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
          this.apiService.getSearchEmployees({ dp: "exact", filter: "idemployees", value: payroll_value.id_employee }).subscribe((emp: employees[]) => {
            this.apiService.getTermdt(emp[0]).subscribe((trm: terminations) => {
              this.apiService.getClosingRise({ id_employee: emp[0].idemployees, start: this.period.start, end: this.period.end }).subscribe((rises: rises) => {
                this.apiService.getTransfers({ id_employee: emp[0].idemployees, start: this.period.start, end: this.period.end }).subscribe((trns: hrProcess) => {
                  this.apiService.getCredits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((cred: credits[]) => {
                    this.apiService.getDebits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((deb: debits[]) => {
                      this.apiService.getJudicialDiscounts({ id: emp[0].idemployees }).subscribe((judicials: judicials[]) => {
                        this.apiService.getServicesDiscounts({ id: emp[0].idemployees, date: this.period.end }).subscribe((services: services[]) => {

                          if (!isNullOrUndefined(trm.valid_from) && (new Date(trm.valid_from).getTime() >= new Date(this.period.start).getTime()) && (new Date(trm.valid_from).getTime() <= new Date(this.period.end).getTime())) {
                            is_trm = true;
                            py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) + (((((new Date(this.period.end).getTime() - new Date(this.period.start).getTime())) / (1000 * 3600 * 24)) + 1) - 15) - ((((new Date(this.period.end).getTime()) - (new Date(trm.valid_from).getTime())) / (1000 * 3600 * 24)) + 1)).toFixed(2);
                            console.log((Number(payroll_value.discounted_hours) + 120) / 8) + "|" + Number(payroll_value.discounted_days + "|" + (((((new Date(this.period.end).getTime() - new Date(this.period.start).getTime())) / (1000 * 3600 * 24)) + 1) - 15) + "|" + ((((new Date(this.period.end).getTime()) - (new Date(trm.valid_from).getTime())) / (1000 * 3600 * 24)) + 1));
                          } else {
                            py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - Number(payroll_value.seventh)).toFixed(2);
                            if (new Date(trm.valid_from).getTime() <= new Date(this.period.start).getTime()) {
                              py.days = '0';
                              console.log(emp[0].nearsol_id);
                            }
                          }

                          let base_salary: number = Number(emp[0].base_payment) / (240);
                          let productivity_salary: number = 0;


                          if (!isNullOrUndefined(rises.effective_date)) {
                            productivity_salary = ((Number(rises.old_salary) - Number(emp[0].base_payment) - 250) / 30) * (((new Date(rises.effective_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24)));
                            productivity_salary = productivity_salary + ((Number(rises.new_salary) - Number(emp[0].base_payment) - 250) / 30) * (15 - (((new Date(rises.effective_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24))));
                            productivity_salary = productivity_salary / 120;
                          } else {
                            productivity_salary = ((Number(emp[0].productivity_payment) - 250) / 240);
                          }

                          if (new Date(emp[0].hiring_date).getTime() > new Date(this.period.start).getTime()) {
                            py.days = (Number(py.days) - ((new Date(emp[0].hiring_date).getTime() - new Date(this.period.start).getTime()) / (1000 * 3600 * 24))).toFixed(2);
                          }


                          if (!isNullOrUndefined(trns)) {
                            if (new Date(trns.date).getTime() >= new Date(this.period.start).getTime()) {
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
                          py.productivity_complete = emp[0].productivity_payment;
                          py.productivity_hours = (Number(py.days) * 8).toFixed(2);
                          py.seventh = payroll_value.seventh;
                          py.account = payroll_value.account_name;
                          py.base_complete = emp[0].base_payment;
                          py.base_hours = (Number(py.days) * 8).toFixed(2);
                          py.client_id = payroll_value.client_id;
                          py.employee_name = emp[0].name;
                          py.holidays_hours = payroll_value.holidays_hours;
                          py.idpayroll_values = payroll_value.idpayroll_values;
                          py.holidays = (Number(payroll_value.holidays_hours) * (base_salary + productivity_salary) * 2).toFixed(2);
                          py.base = (Number(base_salary) * Number(py.base_hours)).toFixed(2);
                          py.productivity = (Number(productivity_salary) * Number(py.productivity_hours)).toFixed(2);


                          if (emp[0].id_account != '13' && emp[0].id_account != '25' && emp[0].id_account != '22' && emp[0].id_account != '23' && emp[0].id_account != '26' && emp[0].id_account != '12' && emp[0].id_account != '20' && emp[0].id_account != '38') {
                            py.ot = ((Number(base_salary) + Number(productivity_salary) + (250 / 240)) * Number(py.ot_hours) * 2).toFixed(2)
                          } else {
                            py.ot = ((Number(base_salary) + Number(productivity_salary) + (250 / 240)) * Number(py.ot_hours) * 1.5).toFixed(2)
                          }

                          let base_credit: credits = new credits;
                          let productivity_credit: credits = new credits;
                          let decreot_credit: credits = new credits;
                          let ot_credit: credits = new credits;
                          let holiday_credit: credits = new credits;
                          let isr: number = 0;

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

                          if (Number(holiday_credit.amount) > 0) {
                            holiday_credit.amount = py.ot;
                            holiday_credit.idpayments = py.idpayments;
                            holiday_credit.type = "Horas De Asueto: " + py.holidays_hours;
                            this.global_credits.push(holiday_credit);
                          }

                          igss_debit.amount = ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * 0.0483).toFixed(2);
                          igss_debit.idpayments = py.idpayments;
                          igss_debit.type = "Descuento IGSS";

                          this.global_credits.push(base_credit);
                          this.global_credits.push(productivity_credit);
                          this.global_debits.push(igss_debit);


                          let sum_cred: number = 0
                          cred.forEach(credit => {
                            if (credit.status == "PENDING") {
                              sum_cred = sum_cred + Number(credit.amount);
                            }
                          })
                          py.credits = (sum_cred + Number(base_credit.amount) + Number(productivity_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount) + Number(decreot_credit.amount)).toFixed(2);



                          let sum_deb: number = 0
                          deb.forEach(debit => {
                            if (debit.status == "PENDING") {
                              sum_deb = sum_deb + Number(debit.amount);
                              if (debit.type = "ISR") {
                                isr = Number(debit.amount);
                              }
                            }
                          })
                          py.debits = (sum_deb + Number(igss_debit.amount)).toFixed(2);



                          services.forEach(service => {
                            if (Number(service.max) == 0 || Number(service.max) > (Number(service.current) + Number(service.amount))) {
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
                            if (service.frecuency == "UNIQUE") {
                              service.status = '0';
                            }
                            this.global_services.push(service);
                          })


                          console.log(judicials);
                          judicials.forEach(judicial => {
                            if (Number(judicial.max) == 0 || Number(judicial.max) < (Number(judicial.current) + ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * (Number(judicial.amount) / 100)))) {
                              judicial.current = (Number(judicial.current) + (((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount))) * (Number(judicial.amount) / 100))).toFixed(2);
                              let judicial_discount: debits = new debits;
                              judicial_discount.amount = ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * (Number(judicial.amount) / 100)).toFixed(2);
                              judicial_discount.type = "Descuento Judicial";
                              judicial_discount.idpayments = py.idpayments;
                              this.global_debits.push(judicial_discount);
                              this.global_judicials.push(judicial);
                            } else if (Number(judicial.max) < (((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount))) * (Number(judicial.amount) / 100))) {
                              judicial.current = (Number(judicial.current) + ((Number(base_credit.amount) + Number(ot_credit.amount) + Number(holiday_credit.amount)) * (Number(judicial.amount) / 100))).toFixed(2);
                              this.global_judicials.push(judicial);
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
    this.loading = true;
    this.pushDeductions('credits', this.global_credits);
    this.pushDeductions('debits', this.global_debits);
    this.global_services.forEach(service => {
      if (Number(service.max) === Number(service.current) || service.frecuency == "UNIQUE") {
        service.status = '0';
      }
      this.apiService.updateServices(service).subscribe((str: string) => { });
    });
    this.global_judicials.forEach(judicial => {
      this.apiService.updateJudicials(judicial).subscribe((str_r: string) => {
        if (str_r.split("|")[0] == '0') {
          window.alert("Error updating Legal Deductions");
        }
      })
    })
    this.payments.forEach(py => {
      this.apiService.setPayment(py).subscribe((str_3: string) => {
        if (str_3.split("|")[0] == "0") {
          window.alert("Error updating Payments");
        }
      })
    })
    this.start();
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
              if (credit.status == "PENDING") {
                this.detailed_credits.push(credit);
              }
            })

            deb.forEach(debit => {
              if (debit.status == "PENDING") {
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

  addfile(event) {
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
        console.log(partial_credits);
        this.apiService.getPayments(provitional_period).subscribe((paymnts: payments[]) => {
          partial_credits.forEach(ele => {
            this.apiService.getSearchEmployees({ dp: 'exact', filter: 'nearsol_id', value: ele.iddebits }).subscribe((emp: employees[]) => {
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
                deb.amount = ele.amount;
                deb.date = ele.date;
                deb.id_employee = ele.id_employee;
                deb.idpayments = ele.idpayments;
                deb.notes = ele.notes;
                deb.type = this.importString;
                this.debits.push(deb);
              }

              if (count == (partial_credits.length - 1)) {
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
        this.apiService.insertDebits(cred).subscribe((str: string) => { });
      });
    } else {
      if (str == 'credits') {
        credits.forEach(cred => {
          this.apiService.insertCredits(cred).subscribe((str: string) => { });
        })
      }
    }
  }

  setType() {
    if (this.importType == 'ISR') {
      this.importString = 'Mensual';
    }
  }

  exportPayrollReport() {
    window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportNominaReport.php?AID_Period=" + this.period.idperiods, "_self");
  }

  exportBankReport() {
    window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportBankReport.php?AID_Period=" + this.period.idperiods, "_self")
  }

  setAccount(str: string) {
    this.selected_accounts = str;
  }

  exportBilling() {
    if (this.selected_accounts == "GET ALL") {
      this.selected_accounts = this.accounts[0].idaccounts;
      this.accounts.forEach((acc) => {
        this.selected_accounts = this.selected_accounts + "," + acc.idaccounts;
      })
    }
    let end: Date = new Date(Number(this.period.start.split("-")[0]), Number(this.period.start.split("-")[1]), 0);
    window.open("./../phpscripts/exportBilling.php?start=" + (this.period.start.split("-")[0] + "-" + (Number(this.period.start.split("-")[1])).toString().padStart(2, "0") + "-" + "01") + "&end=" + (end.getFullYear().toString() + "-" + (end.getMonth() + 1).toString().padStart(2, "0") + "-" + end.getDate().toString()) + "&account=" + this.selected_accounts, "_self")
  }

  setAccountingPolicy() {
    // Se encarga de generar la póliza contable al período en curso si no existiera.
    window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportAccountingPolicyReport.php?AID_Period=" + this.period.idperiods, "_blank")
  }

  setClient(cl: string) {
    this.accounts = [];
    this.selectedClient = cl;
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.setAccount_sh(this.accounts[0]);
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
}
