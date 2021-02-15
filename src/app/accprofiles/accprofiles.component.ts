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
import { advances_acc, attendences, attendences_adjustment, credits, debits, disciplinary_processes, judicials, leaves, ot_manage, payments, periods, services, terminations, vacations } from '../process_templates';
import { profiles } from '../profiles';
import { Observable } from "rxjs";
import { async } from '@angular/core/testing';
import { Agent } from 'http';

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
  advances:advances_acc[] = [];

  constructor(public apiService: ApiService, public route: ActivatedRoute, public authUser: AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    let peridos: periods = new periods;
    let isChrome: boolean = false;
    let a_date:string = null;
    let b_date:string = null;
    let todayDate:string = new Date().getFullYear().toString() + "-" + (new Date().getMonth()) + "-" + new Date().getDate().toString();
    console.log(todayDate);

    if (window.navigator.userAgent.toLowerCase().indexOf('chrome') > -1 && (<any>window).chrome) {
      isChrome = true;
    }


    this.employe_id = this.route.snapshot.paramMap.get('id');
    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: this.employe_id }).subscribe((emp: employees[]) => {

      if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(todayDate.split("-")[0]) - 1).toString() + "-12-01").getTime()) >= 0)) {
        a_date = this.employee.hiring_date;
      } else {
        a_date = (new Date(todayDate).getFullYear() - 1) + '-12-01'
      }
      this.acumulated_ag = (((Number(emp[0].base_payment) + Number(emp[0].productivity_payment)) / 365) * (Number(((new Date(a_date).getTime() - (new Date(todayDate).getTime())))) / (1000 * 3600 * 24))).toFixed(2);
      if (isChrome) {
        this.acumulated_ag = (Number(this.acumulated_ag) - ((21600000 / 1000 / 3600 / 24) * ((Number(emp[0].base_payment) + Number(emp[0].productivity_payment)) / 365))).toFixed(2);
      }

      if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(todayDate.split("-")[0]) - 1).toString() + "-07-01").getTime()) >= 0)) {
        b_date = this.employee.hiring_date;
      } else {
        b_date = (new Date(todayDate).getFullYear() - 1) + "-07-01";
      }
      this.acumulated_b14 = (((Number(emp[0].base_payment) + Number(emp[0].productivity_payment)) / 365) * (Number((new Date(b_date).getTime() - new Date(todayDate).getTime())) / (1000 * 3600 * 24))).toFixed(2);
      if (isChrome) {
        this.acumulated_b14 = (Number(this.acumulated_b14) - ((21600000 / 1000 / 3600 / 24) * (((Number(emp[0].base_payment)+ Number(emp[0].productivity_payment))) / 365))).toFixed(2);
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
        this.active_payment = pym[0];
        this.setPayment();
      });

      this.apiService.getAdvancesAcc(emp[0]).subscribe((adv:advances_acc[])=>{
        this.advances = adv;
      })
      
    })
  }

  setPayment() {
    this.apiService.getDebits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((deb: debits[]) => {
      this.apiService.getCredits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((cred: credits[]) => {
        this.credits = cred;
        this.debits = deb;
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
    this.activeCred.date = new Date().getFullYear().toString() + "-" + new Date().getMonth().toString() + "-" + new Date().getDate().toString();
    this.insertN = str;
    this.insertNew = true;
  }

  setPaymentMethod(paymentMethod: payment_methods) {
    this.activePaymentMethod = paymentMethod;
    this.recordPaymentMethod = true;
  }

  insertDeduction() {
    this.activeCred.id_user = this.authUser.getAuthusr().iduser;
    this.activeCred.idpayments = this.active_payment.idpayments;
    this.activeCred.id_employee = this.employe_id;
    if (this.insertN == 'Debit') {
      this.apiService.insertDebits(this.activeCred).subscribe((str: string) => {
        this.activeCred.iddebits = str;
        this.apiService.insertPushedDebit(this.activeCred).subscribe((str: string) => {
          this.setPayment();
        })
      })
    } else {
      this.apiService.insertCredits(this.activeCred).subscribe((str: string) => {
        this.activeCred.iddebits = str;
        this.apiService.insertPushedCredit(this.activeCred).subscribe((str: string) => {
          this.setPayment();
        })
      })
    }
    this.insertNew = false;
  }

  cancelDeduction() {
    this.insertNew = false;
    this.setPayment();
  }

  setCredit(cred: credits) {
    this.apiService.getPushedCredits(cred).subscribe((de: credits) => {
      this.activeCred = de;
      this.insertNew = true;
      this.insertN = 'Credit';
      this.record = true;
    })
  }

  setDeduction(deb: debits) {
    this.apiService.getPushedDebits(deb).subscribe((de: credits) => {
      this.activeCred = de;
      this.insertNew = true;
      this.insertN = 'Debit';
      this.record = true;
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
    let cred_aguinaldo: credits = new credits;
    let cred_bono14: credits = new credits;
    let cred_vacations: credits = new credits;
    let cred_pendings: credits = new credits;
    let base_salary = function Salary(Aterm: terminations, Aempl: employees): string {
      if (Aterm.base_for_salary == 'Complete') {
        return (Number(Aempl.base_payment) + Number(Aempl.productivity_payment)).toString();
      } else {
        return Number(Aempl.base_payment).toString();
      };
    };

    let average_payment = function salaryAverage(Aterm: terminations, Apay: payments): string {
      if (Aterm.base_for_salary == 'Complete') {
        return (Number(Apay.base_complete) + Number(Apay.productivity_complete)).toString();
      } else {
        return Number(Apay.base_complete).toString();
      };
    }

    this.apiService.getProcRecorded({ id: this.employee.idemployees }).subscribe((pr: process[]) => {

      pr.forEach(proc => {
        if (alert) {
          window.alert('There is a duplicity on "Termination", system will take first instance');
        }
        if (!alert) {
          if (proc.name === "Termination") {
            alert = true;
            this.apiService.getHr_Processes(proc.idprocesses).subscribe((hrproc: hrProcess) => {
              this.process = hrproc;
            })

            this.apiService.getTerm(proc).subscribe((term: terminations) => {
              this.termination = term;
              if (term.access_card != "YES") {
                let deb_access: debits = new debits;
                deb_access.type = "(-)Access Card Replacement";
                deb_access.amount = '125.00';
                this.deb_benefits.push(deb_access);
              }
              if (term.headsets != "YES") {
                let deb_headsets: debits = new debits;
                deb_headsets.type = "(-)Headsets Replacement";
                deb_headsets.amount = "400.00";
                this.deb_benefits.push(deb_headsets);
              }
              this.tvalid_Form = term.valid_from;

              this.apiService.getPayments(per).subscribe((pay: payments[]) => {
                let count: number = 0;
                pay.forEach(element => {
                  if (Number(element.base_complete) > 0) {
                    sum_payment = sum_payment + Number(average_payment(this.termination, element));
                    count++;
                  }
                })
                average_salary = (sum_payment / count).toFixed(2);

                let isChrome: boolean;

                if (window.navigator.userAgent.toLowerCase().indexOf('chrome') > -1 && (<any>window).chrome) {
                  isChrome = true;
                }

                end_date = this.tvalid_Form;
                end_date_plus_one = String(String(new Date(end_date).getFullYear()) + "-" + String(new Date(end_date).getMonth() + 1) + "-" + Number(new Date(end_date).getDate() + 2).toString());


                cred_indemnization.type = "Indemnizacion Periodo del " + this.employee.hiring_date + " al " + end_date;
                cred_indemnization.amount = ((((Number(average_salary) / 12) * 14) / 365) * (((new Date(end_date_plus_one).getTime() - new Date(this.employee.hiring_date).getTime()) / (1000 * 3600 * 24)))).toFixed(2);
                if (isChrome) {
                  cred_indemnization.amount = (Number(cred_indemnization.amount) - ((21600000 / 1000 / 3600 / 24) * ((Number(average_salary) / 12) * 14) / 365)).toFixed(2);
                }
                this.cred_benefits.push(cred_indemnization);
                this.total = this.total + Number(cred_indemnization.amount);

                if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(end_date_plus_one.split("-")[0]) - 1).toString() + "-12-01").getTime()) >= 0)) {
                  a_date = this.employee.hiring_date;
                } else {
                  a_date = (new Date(end_date).getFullYear() - 1) + '-12-01'
                }

                cred_aguinaldo.type = "Aguinaldo Periodo del " + a_date + " al " + end_date;
                cred_aguinaldo.amount = (((Number(base_salary(this.termination, this.employee))) / 365) * (Number(((new Date(end_date_plus_one).getTime() - (new Date(a_date).getTime())))) / (1000 * 3600 * 24))).toFixed(2);
                if (isChrome) {
                  cred_aguinaldo.amount = (Number(cred_aguinaldo.amount) - ((21600000 / 1000 / 3600 / 24) * ((Number(base_salary(this.termination, this.employee))) / 365))).toFixed(2);
                }
                this.cred_benefits.push(cred_aguinaldo);
                this.total = this.total + Number(cred_aguinaldo.amount);

                if ((new Date(this.employee.hiring_date).getTime() - (new Date((Number(end_date_plus_one.split("-")[0]) - 1).toString() + "-07-01").getTime()) >= 0)) {
                  b_date = this.employee.hiring_date;
                } else {
                  b_date = (new Date(end_date).getFullYear() - 1) + "-07-01";
                }
                cred_bono14.type = "Bono 14 Periodo del " + b_date + " al " + end_date;
                cred_bono14.amount = (((Number(base_salary(this.termination, this.employee))) / 365) * (Number((new Date(end_date_plus_one).getTime() - new Date(b_date).getTime())) / (1000 * 3600 * 24))).toFixed(2);
                if (isChrome) {
                  cred_bono14.amount = (Number(cred_bono14.amount) - ((21600000 / 1000 / 3600 / 24) * ((Number(base_salary(this.termination, this.employee))) / 365))).toFixed(2);
                }
                this.cred_benefits.push(cred_bono14);
                this.total = this.total + Number(cred_bono14.amount);

                this.apiService.getVacations({ id: this.employee.id_profile }).subscribe((vacs: vacations[]) => {
                  vacs.forEach(vacation => {
                    v_amount = v_amount + (1 * Number(vacation.count));
                  })
                  cred_vacations.type = "Vacaciones Periodo del " + this.employee.hiring_date + " al " + end_date + " habiendo gozado: " + v_amount;
                  cred_vacations.amount = (((Number(base_salary(this.termination, this.employee))) / 30) * (Number((((new Date(end_date_plus_one).getTime() - new Date(this.employee.hiring_date).getTime()) / (1000 * 3600 * 24)))) / (1 / (15 / 365)) - v_amount)).toFixed(2);
                  if (isChrome) {
                    cred_vacations.amount = (Number(cred_vacations.amount) - (((21600000 / 1000 / 3600 / 24) * (((Number(base_salary(this.termination, this.employee))) / 30))) / (1 / (15 / 365)))).toFixed(2);
                  }
                  this.cred_benefits.push(cred_vacations);
                  this.total = this.total + Number(cred_vacations.amount);
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
                          this.total = this.total + Number(credit.amount);
                        })
                      }
                    });
                    this.apiService.getDebits({ id: this.employee.idemployees, period: pay[0].id_period }).subscribe((deb: debits[]) => {
                      if (!isNullOrUndefined(deb)) {
                        deb.forEach(debit => {
                          this.deb_benefits.push(debit);
                          this.total = this.total - Number(debit.amount);
                        })
                      }
                    })
                  }
                })
              });
            }) // en promise
            end_date = proc.prc_date;
            difference = (((new Date(proc.prc_date).getFullYear()) - (new Date(this.employee.hiring_date).getFullYear())) * 12) + ((new Date(proc.prc_date).getMonth()) - (new Date(this.employee.hiring_date).getMonth()) + 1);
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
          cred.idpayments = pay[0].idpayments;
          this.apiService.insertCredits(cred);
        })

        this.deb_benefits.forEach(deb => {
          deb.idpayments = pay[0].idpayments;
          this.apiService.insertDebits(deb);
        })

        this.apiService.updatehr_process(this.process).subscribe((_str: string) => {
          // no hace nada.

        });
      });
    });
  }

  completePayment() {
    this.process.status = 'PAID';
    this.apiService.updatehr_process(this.process).subscribe((_str: string) => {
      // no hace nada.
    });
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
      this.apiService.updateCredits(db).subscribe((str: string) => { });
    })
    this.setPayment();
    this.acrediting = false;
  }


  selectedCredit(event) {
    if (event.target.checked) {
      this.setAcreditCredits = this.setAcreditCredits + "," + event.target.value;
    }
    this.acrediting = true;
  }

  selectedDebit(event) {
    if (event.target.checked) {
      this.setAcreditDebits = this.setAcreditDebits + "," + event.target.value;
    }
    this.acrediting = true;
  }
}
