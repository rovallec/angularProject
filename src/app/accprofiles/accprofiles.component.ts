import { isNull } from '@angular/compiler/src/output/output_ast';
import { splitAtColon } from '@angular/compiler/src/util';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouteConfigLoadEnd } from '@angular/router';
import { promise } from 'protractor';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, payment_methods, hrProcess } from '../fullProcess';
import { AuthGuard } from '../guard/auth-guard.service';
import { process } from '../process';
import { advances_acc, attendences, attendences_adjustment, credits, debits, disciplinary_processes, judicials, leaves, ot_manage, payments, periods, services, terminations, vacations, Fecha, accounts } from '../process_templates';
import { profiles } from '../profiles';
import { Observable } from "rxjs";
import { async } from '@angular/core/testing';
import { Agent } from 'http';
import { SSL_OP_SSLEAY_080_CLIENT_DH_BUG } from 'constants';

@Component({
  selector: 'app-accprofiles',
  templateUrl: './accprofiles.component.html',
  styleUrls: ['./accprofiles.component.css']
})
export class AccprofilesComponent implements OnInit {

  employee: employees = new employees;
  activePaymentMethod: payment_methods = new payment_methods;
  employe_id: string = null;
  payment: string = null;
  payments: payments[] = [];
  credits: credits[] = [];
  debits: debits[] = [];
  paymentMethods: payment_methods[] = [];
  payment_methods: payment_methods[] = [];
  cred_benefits: credits[] = [];
  vacations: vacations[] = [];
  deb_benefits: debits[] = [];
  active_payment: payments = new payments;
  newProc: boolean = false;
  insertN: string = null;
  insertNew: boolean = false;
  activeCred: credits = new credits;
  totalPayment: string = null;
  record: boolean = false;
  newPaymentMethod: boolean = false;
  recordPaymentMethod: boolean = false;
  non_show_2: boolean = false;
  seventh: number = 0;
  absence: number = 0;
  diff: number = 0;
  attended: number = 0;
  roster: number = 0;
  daysOff: number = 0;
  totalCredits: number = 0;
  totalDebits: number = 0;
  absence_fixed: string = null;
  period: periods = new periods;
  actualPeriod: periods = new periods;
  term_date: string = null;
  leaves: leaves[] = [];
  attendances: attendences[] = [];
  total: number;
  termination: terminations = new terminations;
  tvalid_Form: string = null;
  term_valid_from: string = null;
  setAcreditCredits: string = "0";
  setAcreditDebits: string = "0";
  acrediting: boolean = false;
  process: hrProcess = new hrProcess;
  printDate: string = null;
  acumulated_b14: string = null;
  acumulated_ag: string = null;
  advances: advances_acc[] = [];
  adv: advances_acc = new advances_acc;
  selected_detail: credits = new credits;
  hover: string = null;
  years: string[] = [];
  selectedYear: string = null;
  months: string[] = [];
  selectedMonth: string = null;
  activeAccountpy: string = null;
  activePaymentMethodpy: string = null;
  deductionsType: string[] = [];
  acreditType: string = '0';
  activeNewPayment:payments = new payments;
  allAccounts:accounts[] = [];
  allPeriods:periods[] = [];

  constructor(public apiService: ApiService, public route: ActivatedRoute, public authUser: AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    let peridos: periods = new periods;
    let isChrome: boolean = false;
    let a_date: string = null;
    let b_date: string = null;
    let todayDate: string = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate().toString();

    if (window.navigator.userAgent.toLowerCase().indexOf('chrome') > -1 && (<any>window).chrome) {
      isChrome = true;
    }

    this.apiService.getAccounts().subscribe((acc:accounts[])=>{
      this.allAccounts = acc;
    })

    this.employe_id = this.route.snapshot.paramMap.get('id');
    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: this.employe_id, rol: this.authUser.getAuthusr().id_role }).subscribe((emp: employees[]) => {

      if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(todayDate.split("-")[0]) - 1).toString() + "-12-01").getTime()) <= 0)) {
        a_date = (new Date(todayDate).getFullYear() - 1) + '-12-01';
      } else {
        a_date = this.employee.hiring_date;
      }
      this.acumulated_ag = (((Number(emp[0].base_payment) + Number(emp[0].productivity_payment)) / 365) * (Number(((new Date().getTime() - (new Date(a_date).getTime())))) / (1000 * 3600 * 24))).toFixed(2);
      if (isChrome) {
        this.acumulated_ag = (Number(this.acumulated_ag) - ((21600000 / 1000 / 3600 / 24) * ((Number(emp[0].base_payment) + Number(emp[0].productivity_payment)) / 365))).toFixed(2);
      }

      if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(todayDate.split("-")[0]) - 1).toString() + "-07-01").getTime()) <= 0)) {
        b_date = (new Date(todayDate).getFullYear() - 1) + "-07-01";
      } else {
        b_date = this.employee.hiring_date;
      }
      this.acumulated_b14 = (((Number(emp[0].base_payment) + Number(emp[0].productivity_payment)) / 365) * (Number((new Date().getTime() - new Date(b_date).getTime())) / (1000 * 3600 * 24))).toFixed(2);
      if (isChrome) {
        this.acumulated_b14 = (Number(this.acumulated_b14) - ((21600000 / 1000 / 3600 / 24) * (((Number(emp[0].base_payment) + Number(emp[0].productivity_payment))) / 365))).toFixed(2);
      }

      this.apiService.getPaymentMethods(emp[0]).subscribe((pymM: payment_methods[]) => {
        this.paymentMethods = pymM;
        this.getTerm();
      })
      this.employee = emp[0];
      peridos.idperiods = 'all';
      peridos.status = this.employe_id;
      this.totalPayment = (parseFloat(emp[0].productivity_payment) + parseFloat(emp[0].base_payment)).toFixed(2);

      this.apiService.getPayments(peridos).subscribe((pym: payments[]) => {
        this.payments = pym;
        this.active_payment = pym[pym.length - 1];
        this.years = [];
        pym.forEach(element => {
          let addthis: boolean = true;
          let addthisMonth: boolean = true;
          let addYear = element.start.split("-")[0];
          let addMonth = element.start.split("-")[1];
          this.years.forEach(year => {
            if (year == addYear) {
              addthis = false;
            }
          })
          if (addthis) {
            this.years.push(addYear);
            this.selectedYear = this.years[0];
          }
          this.months.forEach(month => {
            if (month == addMonth && this.years[0] == element.start.split('-')[0]) {
              addthisMonth = false;
            }
          })
          if (addthisMonth) {
            this.months.push(addMonth);
            this.selectedMonth = this.months[this.months.length - 1];
          }
        })
        this.setPayment();
      });

      this.apiService.getAdvancesAcc(emp[0]).subscribe((adv: advances_acc[]) => {
        this.advances = adv;
      })

      this.apiService.getPeriods().subscribe((per: periods[]) => {
        this.allPeriods = per;
        per.forEach(p => {
          if ((p.type_period == '0') && (p.status == '1')) {
            this.actualPeriod = p;
          }
        })
      })
    })
  }

  setPayment() {
    this.activeAccountpy = this.employee.account;
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.idaccounts == this.active_payment.id_account_py) {
          this.activeAccountpy = account.name
        }
      })
    })
    this.paymentMethods.forEach(pyM => {
      if (pyM.idpayment_methods == this.active_payment.id_paymentmethod) {
        this.activePaymentMethodpy = pyM.type + " | " + pyM.bank
      }
    })
    this.apiService.setPayment(this.active_payment).subscribe((_str) => {
      this.apiService.getDebits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((deb: debits[]) => {
        this.apiService.getCredits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((cred: credits[]) => {
          this.credits = cred;
          this.debits = deb;
          if (this.process.status != "CLOSED") {
            this.cred_benefits = cred;
            this.deb_benefits = deb;
            this.total = 0;
            cred.forEach(c => {
              this.total = this.total + Number(c.amount);
            })
            deb.forEach(d => {
              this.total = this.total - Number(d.amount);
            })
          }
        })
      })
    })
  }

  cancelNewpaymentMethod() {
    this.newPaymentMethod = false;
  }

  insertNewPaymentMethod() {
    this.activePaymentMethod.id_user = this.authUser.getAuthusr().iduser;
    this.apiService.insertPaymentMethod(this.activePaymentMethod).subscribe((str: string) => {
      this.newPaymentMethod = false;
      this.start();
    })
  }


  setNewpaymentMethod() {
    this.activePaymentMethod = new payment_methods;
    this.activePaymentMethod.id_employee = this.employee.idemployees;
    this.activePaymentMethod.predeterm = "1";
    this.activePaymentMethod.id_user = this.authUser.getAuthusr().user_name;
    this.activePaymentMethod.date = (new Date().getFullYear().toString()) + "-" + (new Date().getMonth().toString()) + "-" + (new Date().getDate().toString());
    this.newPaymentMethod = true;
  }

  newDeduction(str: string) {
    let date: Fecha = new Fecha;
    this.activeCred = new credits;
    this.activeCred.id_user = this.authUser.getAuthusr().user_name;
    this.activeCred.date = date.today;
    this.insertN = str;
    this.insertNew = true;
    this.record = false;
    if (str == 'Credit') {
      this.deductionsType = [
        'Ajuste de periodos anteriores',
        'Ajuste periodos anteriores',
        'Ajustes Periodos Anteriores',
        'Anticipo Sobre Bono 14',
        'Bonificacion Decreto',
        'Bonificacion Productividad',
        'Bonos Diversos',
        'Bonos Diversos Cliente TK',
        'Bonos Diversos Nearsol TK',
        'Hiring Bonus',
        'RAF Bonus',
        'Salario Base',
        'Treasure Hunt'
      ]
    } else {
      this.deductionsType = [
        'Ajuste de periodos anteriores',
        'Anticipo Sobre Sueldo',
        'Boleto De Ornato',
        'Bus',
        'Car Parking',
        'Descuento IGSS',
        'Descuento Judicial',
        'Headset',
        'ISR',
        'Monedero',
        'Motorcycle Parking',
        'Parqueo',
        'Prestamo Personal',
        'Seguro',
        'TARJETA DE ACCESO/PARQUEO',
      ]
    }
    this.activeCred.type = this.deductionsType[0];
  }

  setPaymentMethod(paymentMethod: payment_methods) {
    this.activePaymentMethod = paymentMethod;
    this.recordPaymentMethod = true;
  }

  insertDeduction() {

    this.apiService.getPeriods().subscribe((period: periods[]) => {
      period.forEach(element => {
        if (element.idperiods == this.active_payment.id_period) {
          if (element.status != '0') {
            this.activeCred.id_user = this.authUser.getAuthusr().iduser;
            this.activeCred.idpayments = this.active_payment.idpayments;
            this.activeCred.id_employee = this.employe_id;
            if (this.insertN == 'Debit') {
              this.active_payment.debits = (Number(this.active_payment.debits) + Number(this.activeCred.amount)).toFixed(2);
              this.apiService.insertDebits(this.activeCred).subscribe((str: string) => {
                this.activeCred.iddebits = str;
                this.apiService.insertPushedDebit(this.activeCred).subscribe((str: string) => {
                  this.setPayment();
                })
              })
            } else {
              this.active_payment.credits = (Number(this.active_payment.credits) + Number(this.activeCred.amount)).toFixed(2);
              this.apiService.insertCredits(this.activeCred).subscribe((str: string) => {
                this.activeCred.iddebits = str;
                this.apiService.insertPushedCredit(this.activeCred).subscribe((str: string) => {
                  this.setPayment();
                })
              })
            }
            this.insertNew = false;
          }else{
            window.alert('Insert records is only available for open periods');
          }
        }
      })
    })

  }

  cancelDeduction() {
    this.insertNew = false;
    this.setPayment();
  }

  setCredit(cred: credits) {
    this.apiService.getPushedCredits(cred).subscribe((de: credits) => {
      this.deductionsType = [
        'Ajustes Periodos Anteriores',
        'Anticipo Sobre Bono 14',
        'Bonificacion Decreto',
        'Bonificacion Productividad',
        'Bonos Diversos',
        'Bonos Diversos Cliente TK',
        'Bonos Diversos Nearsol TK',
        'Hiring Bonus',
        'RAF Bonus',
        'Salario Base',
        'Treasure Hunt',
        'Anticipo Sobre Sueldo',
        'Anticipo Sobre Aguinaldo',
        'Boleto De Ornato',
        'Bus',
        'Car Parking',
        'Descuento IGSS',
        'Descuento Judicial',
        'Headset',
        'ISR',
        'Monedero',
        'Motorcycle Parking',
        'Parqueo',
        'Prestamo Personal',
        'Seguro',
        'TARJETA DE ACCESO/PARQUEO',
      ]
      if (isNullOrUndefined(de.iddebits)) {
        this.activeCred.type = cred.type;
        this.activeCred.amount = cred.amount;
        this.activeCred.date = this.active_payment.date;
        this.activeCred.iddebits = cred.iddebits;
        this.activeCred.idpayments = cred.idpayments;
        this.activeCred.id_user = 'Admin';
        this.activeCred.notes = 'Inherent Credit By Close Period';
        this.record = true;
      } else {
        this.activeCred = de;
        this.insertNew = true;
        this.insertN = 'Credit';
        this.record = true;
      }
    })
  }

  setDeduction(deb: debits) {
    this.apiService.getPushedDebits(deb).subscribe((de: credits) => {
      if (isNullOrUndefined(de.iddebits)) {
        this.activeCred.type = deb.type;
        this.activeCred.amount = deb.amount;
        this.activeCred.date = deb.date;
        this.activeCred.iddebits = deb.iddebits;
        this.activeCred.idpayments = deb.idpayments;
        this.activeCred.id_user = 'Admin';
        this.activeCred.notes = 'Inherent Debit By Close Period';
        this.record = true;
      } else {
        this.activeCred = de;
        this.insertNew = true;
        this.insertN = 'Debit';
        this.record = true;
      }
    })
  }

  getTerm() {
    let end_date: string = null;
    let end_date_plus_one: string = null;
    let difference: number = null;
    let a_date: string = null;
    let b_date: string = null;
    let v_amount: number = 0;
    let sum_payment: number = 0;
    let average_salary: string = null;
    let per: periods = new periods;
    per.idperiods = 'all';
    per.start = 'explicit_Termination';
    per.status = this.employee.idemployees;
    this.total = 0;
    let alert: boolean = false;
    let cred_indemnization: credits = new credits;
    let cred_aguinaldo_base: credits = new credits;
    let cred_aguinaldo_productivity: credits = new credits;
    let cred_bono14_base: credits = new credits;
    let cred_bono14_productivity: credits = new credits;
    let cred_vacations_base: credits = new credits;
    let cred_vacations_productivity: credits = new credits;
    let cred_pendings: credits = new credits;

    let avg_base: number = 0;
    let avg_productivity: number = 0


    let average_payment = function salaryAverage(Aterm: terminations, Apay: payments): string {
      if (Aterm.base_for_salary == 'Complete') {
        return (Number(Apay.base_complete) + Number(Apay.productivity_complete)).toString();
      } else {
        return Number(Apay.base_complete).toString();
      };
    }

    let fix: boolean = false;

    this.apiService.getProcRecorded({ id: this.employee.idemployees }).subscribe((pr: process[]) => {
      pr.forEach(proc => {
        if (alert && !fix) {
          fix = true;
          window.alert('There is a duplicity on "Termination", system will take first instance');
        }
        if (!alert) {
          if (proc.name === "Termination") {
            alert = true;
            this.apiService.getHr_Processes(proc.idprocesses).subscribe((hrproc: hrProcess) => {
              this.process = hrproc;
              this.apiService.getTerm(proc).subscribe((term: terminations) => {
                this.termination = term;
                this.tvalid_Form = term.valid_from;
                if (this.process.status == "CLOSED") {
                  if (term.access_card != "YES") {
                    let deb_access: debits = new debits;
                    deb_access.type = "(-)Access Card Replacement";
                    deb_access.amount = '125.00';
                    this.deb_benefits.push(deb_access);
                    this.total = this.total - 125;
                  }
                  if (term.headsets != "YES") {
                    let deb_headsets: debits = new debits;
                    deb_headsets.type = "(-)Headsets Replacement";
                    deb_headsets.amount = "400.00";
                    this.deb_benefits.push(deb_headsets);
                    this.total = this.total - 400;
                  }

                  this.apiService.getPayments(per).subscribe((pay: payments[]) => {
                    let count: number = 0;
                    if (!isNullOrUndefined(pay)) {
                      pay.forEach(element => {
                        if (Number(element.base_complete) > 0) {
                          avg_base = avg_base + (Number(element.base_complete));
                          avg_productivity = avg_productivity + (Number(element.productivity_complete)) + 250;
                          count++;
                        }
                      })
                      avg_base = (avg_base / count);
                      avg_productivity = (avg_productivity / count);
                    } else {
                      avg_base = Number(this.employee.base_payment);
                      avg_productivity = Number(this.employee.productivity_payment);
                    }

                    if (term.base_for_salary == "Complete") {
                      average_salary = (avg_base + avg_productivity).toFixed(2);
                    } else {
                      average_salary = (avg_base).toFixed(2);
                    }

                    let isChrome: boolean;

                    if (window.navigator.userAgent.toLowerCase().indexOf('chrome') > -1 && (<any>window).chrome) {
                      isChrome = true;
                    }

                    end_date = this.tvalid_Form;
                    let d: Date = new Date(new Date(end_date).getTime() + (1000 * 3600 * 24));
                    end_date_plus_one = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + (d.getDate() + 1);

                    if (term.motive == "Reestructuracion") {
                      cred_indemnization.type = "Indemnizacion: Periodo del " + this.employee.hiring_date + " al " + end_date;
                      cred_indemnization.amount = ((((Number(average_salary) / 12) * 14) / 365) * (((new Date(end_date_plus_one).getTime() -
                        new Date(this.employee.hiring_date).getTime()) / (1000 * 3600 * 24)))).toFixed(2);
                      cred_indemnization.notes = Number(average_salary) + "/ 12) * 14) / 365) *" +
                        (((new Date(end_date_plus_one).getTime() - new Date(this.employee.hiring_date).getTime()) / (1000 * 3600 * 24))).toFixed(2)
                        + " = " + cred_indemnization.amount;
                      if (isChrome) {
                        cred_indemnization.amount = ((((Number(average_salary) / 12) * 14) / 365) * (((new Date(end_date_plus_one).getTime() -
                          new Date(this.employee.hiring_date).getTime() - 21600000) / (1000 * 3600 * 24)))).toFixed(2)
                          + " = " + cred_indemnization.amount;
                      }


                      this.cred_benefits.push(cred_indemnization);
                    }


                    if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(end_date_plus_one.split("-")[0]) - 1).toString() + "-12-01").getTime()) >= 0)) {
                      a_date = this.employee.hiring_date;
                    } else {
                      a_date = (new Date(end_date).getFullYear() - 1) + '-12-01'
                    }

                    cred_aguinaldo_base.type = "Aguinaldo Base: Periodo del " + a_date + " al " + end_date;
                    cred_aguinaldo_productivity.type = "Aguinaldo Productividad: Periodo del " + a_date + " al " + end_date;
                    cred_aguinaldo_base.amount = (((avg_base) / 365) * (Number(((new Date(end_date_plus_one).getTime() - (new Date(a_date).getTime())))) / (1000 * 3600 * 24))).toFixed(2);
                    cred_aguinaldo_productivity.amount = (((avg_productivity) / 365) * (Number(((new Date(end_date_plus_one).getTime() - (new Date(a_date).getTime())))) / (1000 * 3600 * 24))).toFixed(2);

                    cred_aguinaldo_base.notes = avg_base + "/ 365 *" + (Number(((new Date(end_date_plus_one).getTime() - (new Date(a_date).getTime())))) / (1000 * 3600 * 24)).toFixed(2) + " = " + cred_aguinaldo_base.amount;
                    cred_aguinaldo_productivity.notes = avg_productivity + "/ 365 *" + (Number(((new Date(end_date_plus_one).getTime() - (new Date(a_date).getTime())))) / (1000 * 3600 * 24)).toFixed(2) + " = " + cred_aguinaldo_productivity.amount;

                    if (isChrome) {
                      cred_aguinaldo_base.amount = (((avg_base) / 365) * (Number((((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(a_date).getTime())))) / (1000 * 3600 * 24))).toFixed(2);
                      cred_aguinaldo_productivity.amount = (((avg_productivity) / 365) * (Number((((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(a_date).getTime())))) / (1000 * 3600 * 24))).toFixed(2);

                      cred_aguinaldo_base.notes = avg_base + "/ 365 *" + (Number((((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(a_date).getTime())))) / (1000 * 3600 * 24)).toFixed(2) + " = " + cred_aguinaldo_base.amount;
                      cred_aguinaldo_productivity.notes = avg_productivity + "/ 365 *" + (Number((((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(a_date).getTime())))) / (1000 * 3600 * 24)).toFixed(2) + " = " + cred_aguinaldo_productivity.amount;
                    }
                    this.cred_benefits.push(cred_aguinaldo_base);
                    this.cred_benefits.push(cred_aguinaldo_productivity);

                    if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(end_date_plus_one.split("-")[0]) - 1).toString() + "-07-01").getTime()) >= 0)) {
                      b_date = this.employee.hiring_date;
                    } else {
                      b_date = (new Date(end_date).getFullYear() - 1) + "-07-01";
                    }
                    cred_bono14_base.type = "Bono 14 Base: Periodo del " + b_date + " al " + end_date;
                    cred_bono14_productivity.type = "Bono 14 Productividad: Periodo del " + b_date + " al " + end_date;
                    cred_bono14_base.amount = (((avg_base) / 365) *
                      (Number((new Date(end_date_plus_one).getTime() - new Date(b_date).getTime())) / (1000 * 3600 * 24))).toFixed(2);
                    cred_bono14_productivity.amount = (((avg_productivity) / 365) *
                      (Number((new Date(end_date_plus_one).getTime() - new Date(b_date).getTime())) / (1000 * 3600 * 24))).toFixed(2);

                    cred_bono14_base.notes = avg_base + "/ 365 * " +
                      (Number((new Date(end_date_plus_one).getTime() - new Date(b_date).getTime())) / (1000 * 3600 * 24)).toFixed(2)
                      + " = " + cred_bono14_base.amount;
                    cred_bono14_productivity.notes = avg_productivity + "/ 365 * " +
                      (Number((new Date(end_date_plus_one).getTime() - new Date(b_date).getTime())) / (1000 * 3600 * 24)).toFixed(2)
                      + " = " + cred_bono14_productivity.amount;
                    if (isChrome) {
                      cred_bono14_base.amount = (((avg_base) / 365) *
                        (Number(((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(b_date).getTime()))) / (1000 * 3600 * 24))).toFixed(2);
                      cred_bono14_productivity.amount = (((avg_productivity) / 365) *
                        (Number(((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(b_date).getTime()))) / (1000 * 3600 * 24))).toFixed(2);

                      cred_bono14_base.notes = avg_base + "/ 365 * " +
                        (Number(((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(b_date).getTime()))) / (1000 * 3600 * 24)).toFixed(2)
                        + " = " + cred_bono14_base.amount;
                      cred_bono14_productivity.notes = avg_productivity + "/ 365 * " +
                        (Number(((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(b_date).getTime()))) / (1000 * 3600 * 24)).toFixed(2)
                        + " = " + cred_bono14_productivity.amount;
                    }

                    this.cred_benefits.push(cred_bono14_base);
                    this.cred_benefits.push(cred_bono14_productivity);

                    this.apiService.getVacations({ id: this.employee.id_profile }).subscribe((vacs: vacations[]) => {
                      vacs.forEach(vacation => {
                        if (vacation.status != "DISMISSED" && vacation.action == "Take") {
                          v_amount = v_amount + (1 * Number(vacation.count));
                        }
                      })

                      cred_vacations_base.type = "Vacaciones Base: Periodo del " + this.employee.hiring_date + " al " + end_date + " habiendo gozado: " + v_amount;
                      cred_vacations_productivity.type = "Vacaciones Productividad: Periodo del " + this.employee.hiring_date + " al " + end_date + " habiendo gozado: " + v_amount;
                      cred_vacations_base.amount = (((avg_base) / 30) * (Number((((new Date(end_date_plus_one).getTime() - new Date(this.employee.hiring_date).getTime()) /
                        (1000 * 3600 * 24)))) / (1 / (15 / 365)) - v_amount)).toFixed(2);
                      cred_vacations_productivity.amount = (((avg_productivity) / 30) * (Number((((new Date(end_date_plus_one).getTime() - new Date(this.employee.hiring_date).getTime()) /
                        (1000 * 3600 * 24)))) / (1 / (15 / 365)) - v_amount)).toFixed(2);

                      cred_vacations_base.notes = avg_base + "/ 30 *" + (Number(new Date(end_date_plus_one).getTime()) - new Date(this.employee.hiring_date).getTime()) /
                        (1000 * 3600 * 24) + "/" + (1 / (15 / 365)).toFixed(3) + "-" + v_amount + " = " + cred_vacations_base.amount;;

                      cred_vacations_productivity.notes = avg_productivity + "/ 30 *" + (Number(new Date(end_date_plus_one).getTime()) - new Date(this.employee.hiring_date).getTime()) /
                        (1000 * 3600 * 24) + "/" + (1 / (15 / 365)).toFixed(3) + "-" + v_amount + " = " + cred_vacations_productivity.amount;
                      if (isChrome) {
                        cred_vacations_productivity.amount = (((avg_productivity) / 30) * (Number(((((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(this.employee.hiring_date).getTime())) /
                          (1000 * 3600 * 24)))) / (1 / (15 / 365)) - v_amount)).toFixed(2);
                        cred_vacations_base.amount = (((avg_base) / 30) * (Number(((((new Date(end_date_plus_one).getTime() - 21600000) - (new Date(this.employee.hiring_date).getTime())) /
                          (1000 * 3600 * 24)))) / (1 / (15 / 365)) - v_amount)).toFixed(2);

                        cred_vacations_base.notes = avg_base + "/ 30 *" + ((new Date(end_date_plus_one).getTime() - 21600000) - new Date(this.employee.hiring_date).getTime()) /
                          (1000 * 3600 * 24) + "/" + (1 / (15 / 365)).toFixed(3) + "-" + v_amount + " = " + cred_vacations_base.amount;;
                        cred_vacations_productivity.notes = avg_productivity + "/ 30 *" + ((new Date(end_date_plus_one).getTime() - 21600000) - new Date(this.employee.hiring_date).getTime()) /
                          (1000 * 3600 * 24) + "/" + (1 / (15 / 365)).toFixed(3) + "-" + v_amount + " = " + cred_vacations_productivity.amount;;
                      }
                      this.cred_benefits.push(cred_vacations_base);
                      this.cred_benefits.push(cred_vacations_productivity);
                    })

                    let p: periods = new periods;
                    p.start = 'explicit_employee';
                    p.status = this.employe_id + " ORDER BY idpayments DESC LIMIT 1";
                    p.idperiods = "all";

                    this.apiService.getPayments(p).subscribe((pay: payments[]) => {
                      if (!isNullOrUndefined(pay)) {
                        this.apiService.getCredits({ id: this.employee.idemployees, period: pay[0].id_period }).subscribe((cred: credits[]) => {
                          if (!isNullOrUndefined(cred)) {
                            cred.forEach(credit => {
                              this.cred_benefits.push(credit);
                            })
                          }
                          this.apiService.getDebits({ id: this.employee.idemployees, period: pay[0].id_period }).subscribe((deb: debits[]) => {
                            if (!isNullOrUndefined(deb)) {
                              deb.forEach(debit => {
                                this.deb_benefits.push(debit);
                              })
                            }
                            this.cred_benefits.forEach((crd: credits) => {
                              this.total = this.total + Number(crd.amount);
                            });
                            this.deb_benefits.forEach((dbd: credits) => {
                              this.total = this.total - Number(dbd.amount);
                            })
                          });
                        })
                      }
                    })
                  });
                } else {
                  this.active_payment = this.payments[this.payments.length - 1];
                  this.setPayment();
                }
              }) // en promise
              end_date = proc.prc_date;
              difference = (((new Date(proc.prc_date).getFullYear()) - (new Date(this.employee.hiring_date).getFullYear())) * 12) + ((new Date(proc.prc_date).getMonth()) - (new Date(this.employee.hiring_date).getMonth()) + 1);
            })
          }
        }
      })
    })
  }

  acreditPayment() {
    let p: periods = new periods;
    p.idperiods = 'all';
    p.start = 'explicit';
    p.status = this.employee.idemployees;
    this.process.status = 'ACREDIT';
    this.employee.state = "PAID";
    this.employee.platform = "NONE";

    this.apiService.updateEmployee(this.employee).subscribe((_str: string) => {
      this.apiService.getPayments(p).subscribe((pay: payments[]) => {

        this.cred_benefits.forEach(cred => {
          if (cred.type != "Salario Base" && cred.type != "Bonificacion Productividad" && cred.type != "Bonificacion Decreto") {
            cred.idpayments = pay[pay.length - 1].idpayments;
            this.apiService.insertCredits(cred).subscribe((str3: string) => {
              if (Number(str3) > 0) {
                cred.iddebits = str3;
                cred.id_user = this.authUser.getAuthusr().iduser;
                cred.id_employee = this.employee.idemployees;
                cred.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                this.apiService.insertPushedCredit(cred).subscribe((str: string) => {
                })
              }
            })
          }
        })

        this.deb_benefits.forEach(deb => {
          if (deb.type != "ISR" && deb.type != "Descuento IGSS") {
            deb.idpayments = pay[pay.length - 1].idpayments;
            this.apiService.insertDebits(deb).subscribe((str4: string) => {
              this.apiService.insertPushedDebit(deb).subscribe((str2: string) => {
                if (Number(str4) > 0) {
                  deb.iddebits = str4;
                  deb.id_user = this.authUser.getAuthusr().iduser;
                  deb.id_employee = this.employee.idemployees;
                  deb.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
                  this.apiService.insertPushedDebit(deb).subscribe((str: string) => {
                  })
                }
              })
            })
          }
        })

        this.apiService.updatehr_process(this.process).subscribe((str: string) => { });
      });
    });
  }

  completePayment() {
    this.process.status = 'PAID';
    this.apiService.updatehr_process(this.process).subscribe((str: string) => { });
  }

  acreditSelection() {
    this.setAcreditCredits.split(",").forEach(cred => {
      let cr: credits = new credits;
      cr.status = "PAID";
      cr.iddebits = cred;
      this.apiService.updateCredits(cr).subscribe((str: string) => { });
    });
    this.setAcreditDebits.split(",").forEach(debit => {
      let db: debits = new debits;
      db.status = "PAID";
      db.iddebits = debit;
      this.apiService.updateDebits(db).subscribe((str: string) => { });
    })
    window.alert("Record Updated");
    this.acrediting = false;
    this.acreditType = '0';
    this.setPayment();
  }

  CancelAcreditSelection() {
    this.setAcreditCredits = "0";
    this.setAcreditDebits = "0";
    this.acreditType = '0';
    this.acrediting = false;
    this.setPayment();
  }

  selectedCredit(event, str: string) {
    let val: string = "," + event.target.value;
    let temp: string = '0';
    this.acreditType = str;
    if (event.target.checked) {
      this.setAcreditCredits = this.setAcreditCredits + val;
      this.acrediting = true;
    } else {
      this.setAcreditCredits.split(',').forEach(element => {
        if (element != event.target.value && element != '0') {
          temp = temp + "," + element;
        }
        this.setAcreditCredits = temp;
      });
      if (this.setAcreditCredits == '0') {
        this.CancelAcreditSelection();
      }
    }
  }

  selectedDebit(event, str: string) {
    let val: string = "," + event.target.value;
    let temp: string = '0';
    this.acreditType = str;
    if (event.target.checked) {
      this.setAcreditDebits = this.setAcreditDebits + val;
      this.acrediting = true;
    } else {
      this.setAcreditDebits.split(',').forEach(element => {
        if (element != event.target.value && element != '0') {
          temp = temp + "," + element;
        }
        this.setAcreditDebits = temp;
      });
      if (this.setAcreditDebits == '0') {
        this.CancelAcreditSelection();
      }
    }
  }

  setPayments(adv: advances_acc) {
    let per: periods = this.actualPeriod;
    adv.status = 'APPROVED';
    per.start = 'explicit_employee';
    per.status = this.employee.idemployees;
    this.apiService.getPayments(per).subscribe((payment: payments[]) => {
      let cred: credits = new credits;
      let date: Fecha = new Fecha;
      cred.amount = adv.amount;
      cred.id_employee = this.employee.idemployees;
      cred.id_user = this.authUser.getAuthusr().iduser;
      cred.date = date.today;
      cred.idpayments = this.payments[this.payments.length - 1].idpayments;
      payment.forEach(py => {
        if ((py.id_employee == this.employee.idemployees) && (py.id_period == this.actualPeriod.idperiods)) {
          cred.idpayments = py.idpayments;
        }
      });
      cred.notes = adv.notes;
      cred.type = "Anticipo Sobre " + adv.type;
      this.apiService.getHr_Processes(adv.id_process).subscribe((proc: hrProcess) => {
        proc.status = adv.status;
        proc.idhr_process = proc.idhr_processes;
        if (isNullOrUndefined(proc.notes)) {
          proc.notes = adv.notes;
        } else {
          proc.notes = proc.notes + '|' + adv.notes;
        }
        this.apiService.updatehr_process(proc).subscribe((_str: string) => {
          this.apiService.updateAdvances(adv).subscribe((str: string) => {
            if (String(str).split("|")[0] != '0') {
              this.apiService.insertCredits(cred).subscribe((str: string) => {
                if (String(str).split("|")[0] != '0') {
                  cred.iddebits = str;
                  cred.status = adv.status;
                  this.apiService.insertPushedCredit(cred).subscribe((str: string) => {
                    if (String(str).split("|")[0] != '0') {
                      this.start();
                    } else {
                      window.alert("An error has occured:\n" + str.split("|")[0] + "\n" + str.split("|")[1]);
                    }
                  })
                } else {
                  window.alert("An error has occured:\n" + str.split("|")[0] + "\n" + str.split("|")[1]);
                }
              })
            } else {
              window.alert("An error has occured:\n" + String(str).split("|")[0] + "\n" + String(str).split("|")[1]);
            }
          })
        })
      })
    })
  }

  cancelPayments(adv: advances_acc) {
    adv.status = 'REJECTED';
    this.apiService.getHr_Processes(adv.id_process).subscribe((proc: hrProcess) => {
      proc.status = adv.status;
      if (isNullOrUndefined(proc.notes)) {
        proc.notes = adv.notes;
      } else {
        proc.notes = proc.notes + '|' + adv.notes;
      }
      this.apiService.updatehr_process(proc).subscribe((_str: string) => {
        // no hace nada por el momento.
      })
    })
  }

  assignAdvance(adv) {
    this.adv = adv;
    this.adv.notes = '';
  }

  setSelectedDetail(cred: credits) {
    this.selected_detail.type = cred.type;
    let i: number = 0;
    if (this.termination.motive == "Restructuracion") {
      i = 0;
    } else {
      i = 1;
    }
    this.selected_detail.status = this.selected_detail.type.split(":")[1]
    if (this.selected_detail.type.includes("Aguinaldo")) {
      this.selected_detail.notes = this.cred_benefits[1 - i].notes;
      this.selected_detail.type = "Aguinaldo";
      this.selected_detail.amount = (Number(this.cred_benefits[1 - i].amount) + Number(this.cred_benefits[2 - i].amount)).toFixed(2);
    } else if (this.selected_detail.type.includes("Bono 14")) {
      this.selected_detail.notes = this.cred_benefits[3 - i].notes;
      this.selected_detail.type = "Bono 14";
      this.selected_detail.amount = (Number(this.cred_benefits[3 - i].amount) + Number(this.cred_benefits[4 - i].amount)).toFixed(2);
    } else if (this.selected_detail.type.includes("Vacaciones")) {
      this.selected_detail.notes = this.cred_benefits[5 - i].notes;
      this.selected_detail.type = "Vacaciones";
      this.selected_detail.amount = (Number(this.cred_benefits[5 - i].amount) + Number(this.cred_benefits[6 - i].amount)).toFixed(2);
    }
  }

  setHover(x: string) {
    this.hover = x;
  }

  printSettlement() {
    this.apiService.getCredits({ period: this.payments[this.payments.length - 1].id_period, id: this.employee.idemployees }).subscribe((cred: credits[]) => {
      this.apiService.getDebits({ period: this.payments[this.payments.length - 1].id_period, id: this.employee.idemployees }).subscribe((deb: debits[]) => {
        this.apiService.getFilteredPeriods({ id: this.payments[this.payments.length - 1].id_period }).subscribe((period: periods) => {

          let indemnizacion: number = 0;
          let aguinaldo: number = 0;
          let bono_14: number = 0;
          let vacaciones: number = 0;
          let sueldos: number = 0;
          let bonificacion: number = 0;
          let isr: number = 0;
          let compensacion: number = 0;
          let descuentos: number = 0;

          cred.forEach(credit => {
            if (!isNullOrUndefined(credit.type.split(":")[0])) {
              if (credit.type.split(":")[0] == 'Indemnizacion') {
                indemnizacion = Number(credit.amount);
              } else if (credit.type.split(":")[0] == 'Aguinaldo Base') {
                aguinaldo = Number(credit.amount);
              } else if (credit.type.split(":")[0] == 'Bono 14 Base') {
                bono_14 = Number(credit.amount);
              } else if (credit.type.split(":")[0] == 'Vacaciones Base') {
                vacaciones = Number(credit.amount);
              } else {
                bonificacion = bonificacion + Number(credit.amount);
              }
            } else if (credit.type.split(":")[0] == 'Salario Base') {
              sueldos = Number(credit.amount);
            } else {
              bonificacion = bonificacion + Number(credit.amount);
            }
          })

          deb.forEach(debit => {
            if (debit.type == 'ISR') {
              isr = Number(debit.amount);
            } else {
              descuentos = descuentos + Number(debit.amount);
            }
          })

          window.open("http://172.18.2.45/phpscripts/finiquito_laboral.php?company=" + this.employee.society
            + "&hiring_date=" + this.employee.hiring_date + "&term_date=" + this.tvalid_Form + "&name=" + this.employee.name +
            "&dpi=" + this.employee.dpi + "&indemnizacion=" + indemnizacion.toFixed(2) +
            "&aguinaldo=" + aguinaldo.toFixed(2) + "&bono_14=" + bono_14.toFixed(2) +
            "&vacaciones=" + vacaciones.toFixed(2) + "&acumulados=0&end=" + period.end + "&start=" + period.start +
            "&isr=" + isr.toFixed(2) + "&credits=" + compensacion.toFixed(2) + "&debits=" + descuentos.toFixed(2) + "&bonuses=" + bonificacion.toFixed(2), "_blank");
        })
      })
    })
  }

  showPayments() {
    let pys: payments[] = [];
    this.payments.forEach(element => {
      if (element.start.split('-')[0] == this.selectedYear && element.start.split('-')[1] == this.selectedMonth) {
        pys.push(element);
      }
    })
    return pys;
  }

  clickSetPayment(py: payments) {
    this.active_payment = py;
    this.setPayment();
  }

  setMonth() {
    this.clickSetPayment(this.showPayments()[0]);
  }

  setActiveCredType(str: string) {
    this.activeCred.type = str;
  }

  deleteDeduction() {
    this.apiService.getPeriods().subscribe((period: periods[]) => {
      period.forEach(element => {
        if (element.idperiods == this.active_payment.id_period) {
          if (element.status != '0') {
            this.activeCred.status = this.insertN;
            this.apiService.deleteDeduction(this.activeCred).subscribe((str: string) => {
              if (str == '1') {
                window.alert('Record Successfuly Deleted');
                this.cancelDeduction();
              }
            })
          } else {
            window.alert('Delete records is only available for open periods')
          }
        }
      })
    })
    this.cancelDeduction();
  }

  setNewPayment(){
    this.activeNewPayment = new payments;
    this.activeNewPayment.idpayments = this.authUser.getAuthusr().iduser;
    this.activeNewPayment.id_employee = this.employe_id;
  }

  createPayment(){
    let create:boolean = true;
    this.payments.forEach(pay=>{
      if((pay.id_period == this.activeNewPayment.id_period && pay.id_account_py == this.activeNewPayment.id_account_py) || (isNullOrUndefined(pay.id_account_py) && this.activeNewPayment.id_account_py == this.employee.id_account) && create){
        window.alert('An assiociated payment for this employee on that period already exist');
        create = false;
      }
    })
    console.log(this.activeNewPayment);
    if(create){
      if(this.activeNewPayment.id_account_py == this.employee.id_account){
        this.activeNewPayment.id_account_py = 'NULL';
      }
      this.apiService.insertManualPayment(this.activeNewPayment).subscribe((str:string)=>{
        if(str == '1'){
          window.alert("Payment successfuly created");
          this.start();
        }else{
          window.alert(str);
        }
      })
    }
  }

  deletePayment(){
    let str:string;
    str = this.active_payment.id_period;
    this.active_payment.id_period = this.authUser.getAuthusr().iduser;
    this.apiService.deleteManualPayment(this.active_payment).subscribe((str:string)=>{
      if(str == '1'){
        window.alert("Payment and dependencies successfuly deleted");
      }else{
        window.alert(str);
      }
    })
  }

}