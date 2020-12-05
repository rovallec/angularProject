import { NumberFormatStyle } from '@angular/common';
import { formattedError, ThrowStmt } from '@angular/compiler';
import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { timeStamp } from 'console';
import { AttachSession } from 'protractor/built/driverProviders';
import { parse } from 'querystring';
import { isNull, isNullOrUndefined, isUndefined } from 'util';
import { schedule_visit } from '../addTemplate';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { attendences, attendences_adjustment, credits, debits, deductions, disciplinary_processes, judicials, leaves, ot_manage, payments, periods, services, vacations } from '../process_templates';
import * as XLSX from 'xlsx';
import { Observable, of } from 'rxjs';
import { promise } from 'protractor';
import { resolve } from 'url';
import { DH_NOT_SUITABLE_GENERATOR } from 'constants';
import { runInThisContext } from 'vm';

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
  backUp_payments: payments[];
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
  showPaymentes: boolean = false;
  searchClosed: boolean = false;
  importActive: boolean = false;
  working: boolean = false;
  count_payments: number = 0;
  importType: string = null;
  importString: string = null;

  constructor(public apiService: ApiService, public route: ActivatedRoute) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.getDeductions();
    this.apiService.getFilteredPeriods({ id: this.route.snapshot.paramMap.get('id') }).subscribe((p: periods) => {
      this.period = p;
    });
    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
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
    this.getDeductions();
    this.selectedEmployee = false;
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
    this.attended = 0;
    this.working = true;
    let pushCredits: credits[] = [];
    let pusDebits: debits[] = [];

    this.global_debits = [];
    this.global_credits = [];
    this.global_judicials = [];
    this.global_services = [];

    this.credits = [];
    this.debits = [];

    let end: number = 0;
    this.apiService.getPayments(this.period).subscribe((payments: payments[]) => {
      if (payments.length === 0) {
        this.working = false;
      }
      if (payments.length == 0) {
        this.working = false;
      }
      if (payments.length < 1) {
        this.working = false;
      }
      payments.forEach(pay => {
        this.seventh = 0;
        let totalCred: number = 0;
        let totalDeb: number = 0;
        let discounted: number = 0;
        let activeVac: boolean = false;
        let activeLeav: boolean = false;
        let activeDp: boolean = false;
        let janp_sequence: number = 0;
        let nonShowCount: number = 0;


        pushCredits = [];
        pusDebits = [];

        this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: pay.id_employee }).subscribe((emp: employees[]) => {
          this.apiService.getVacations({ id: emp[0].id_profile }).subscribe((vac: vacations[]) => {
            this.apiService.getLeaves({ id: emp[0].id_profile }).subscribe((leave: leaves[]) => {
              this.apiService.getDPAtt({ id: emp[0].idemployees, date_1: this.period.start, date_2: this.period.end }).subscribe((dp: disciplinary_processes[]) => {
                this.apiService.getAttPeriod({ id: emp[0].idemployees, date_1: this.period.start, date_2: this.period.end }).subscribe((att: attendences[]) => {
                  this.apiService.getAttAdjustments({ id: emp[0].idemployees }).subscribe((ad: attendences_adjustment[]) => {
                    this.apiService.getCredits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((cd: credits[]) => {
                      this.apiService.getDebits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((db: debits[]) => {
                        this.apiService.getJudicialDiscounts({ id: emp[0].idemployees }).subscribe((judicials: judicials[]) => {
                          this.apiService.getServicesDiscounts({ id: emp[0].idemployees, date: this.period.start }).subscribe((services: services[]) => {
                            if (this.period.status == '1') {
                              if (att.length != 0) {
                                att.forEach(attendance => {

                                  let dt: Date = new Date(attendance.date);

                                  if (dt.getDay() === 0) {
                                    this.non_show_2 = true;
                                    if (nonShowCount > 3) {
                                      discounted = discounted - 8;
                                      this.absence = this.absence - 8;
                                      this.seventh = this.seventh + 1;
                                    }
      
                                    if (janp_sequence >= 5) {
                                      discounted = discounted - 16;
                                      this.absence = this.absence - 16;
                                      this.seventh = this.seventh + 2;
                                    }
      
                                    janp_sequence = 0;
                                    nonShowCount = 0;
                                  }

                                  activeDp = false;
                                  activeVac = false;
                                  activeLeav = false;

                                  vac.forEach(vacation => {
                                    if (vacation.took_date == attendance.date) {
                                      if (attendance.scheduled != "OFF") {
                                        this.roster = this.roster + Number(attendance.scheduled);
                                        this.attended = this.attended + Number(attendance.scheduled);
                                        attendance.balance = 'VAC';
                                      } else {
                                        this.daysOff = this.daysOff + 1;
                                        attendance.balance = "OFF";
                                      }
                                      activeVac = true;
                                    }
                                  })

                                  leave.forEach(leav => {
                                    if (attendance.scheduled != 'OFF') {
                                      if ((new Date(leav.start)) <= (new Date(attendance.date)) && (new Date(leav.end)) >= (new Date(attendance.date))) {
                                        activeLeav = true;
                                        if (leav.motive == 'Others Unpaid' || leav.motive == 'Leave of Absence Unpaid') {
                                          discounted = discounted - 8;
                                          this.absence = this.absence - 8;
                                          attendance.balance = 'JANP';
                                          janp_sequence = janp_sequence + 1;
                                        } else {
                                          if (leav.motive == 'Maternity' || leav.motive == 'Others Paid') {
                                            this.attended = this.attended + 8
                                            attendance.balance = 'JAP';
                                          }
                                        }
                                      }
                                    }
                                  })

                                  dp.forEach(disciplinary => {
                                    if (disciplinary.day_1 == attendance.date || disciplinary.day_2 == attendance.date || disciplinary.day_3 == attendance.date || disciplinary.day_4 == attendance.date) {
                                      discounted = discounted - 8;
                                      attendance.balance = "SUSPENSION"
                                      activeDp = true;
                                    }
                                  });

                                  if (!activeLeav && !activeVac && !activeDp) {
                                    if (attendance.scheduled == 'OFF') {
                                      this.daysOff = this.daysOff + 1;
                                      attendance.balance = "OFF";
                                    } else {
                                      this.roster = this.roster + Number(attendance.scheduled);
                                      if (Number(attendance.worked_time) == 0) {
                                        if (this.non_show_2) {
                                          this.absence = this.absence - 16;
                                          discounted = discounted - 16;
                                          this.seventh = this.seventh + 1;
                                          this.non_show_2 = false;
                                          attendance.balance = "NS";
                                          nonShowCount = nonShowCount + 1;
                                        } else {
                                          attendance.balance = "NS"
                                          nonShowCount = nonShowCount + 1;
                                          this.absence = this.absence - 8;
                                          discounted = discounted - 8;
                                        }
                                      } else {
                                        this.attended = this.attended + Number(attendance.worked_time);
                                        this.absence = this.absence + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                        attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(2);
                                        discounted = discounted + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                      }
                                    }
                                  }
                                });

                                if (this.attended == 0) {
                                  this.absence = (att.length * 8) * (-1);
                                  discounted = (att.length * 8) * (-1);
                                }

                                this.attended = 0;
                                
                                let base_hour: number = Number(emp[0].base_payment) / 240;
                                let productivity_hour: number = (Number(emp[0].productivity_payment) - 250) / 240;
                                let base_credit: credits = new credits;
                                let productivity_credit: credits = new credits;
                                let decreto_credit: credits = new credits;
                                let ot_credit: credits = new credits;
                                let igss_debit: debits = new debits;

                                base_credit.type = "Salario Base";
                                productivity_credit.type = "Bonificacion Productividad";
                                decreto_credit.type = "Bonificacion Decreto";

                                if (discounted <= 0) {
                                  base_credit.amount = (((att.length * 8) + (discounted)) * base_hour).toFixed(2);
                                  productivity_credit.amount = (((att.length * 8) + (discounted)) * productivity_hour).toFixed(2);
                                  ot_credit.amount = '0';
                                  decreto_credit.amount = (((att.length * 8) + (discounted)) * (125 / 120)).toFixed(2);
                                  pay.days = (((att.length * 8) + (discounted)) / 8).toFixed(2);
                                  pay.ot = '0';
                                } else {
                                  productivity_credit.amount = ((att.length * 8) * productivity_hour).toFixed(2);
                                  base_credit.amount = ((att.length * 8) * base_hour).toFixed(2);
                                  pay.days = '15';
                                  pay.ot = discounted.toFixed(2);
                                  let ot: ot_manage = new ot_manage;
                                  ot.id_period = this.period.idperiods;
                                  ot.id_employee = emp[0].idemployees;
                                  ot.name = emp[0].name;
                                  ot.nearsol_id = emp[0].nearsol_id;
                                  ot_credit.type = "Horas Extra Laboradas: " + discounted;
                                  if (emp[0].id_account != '13' && emp[0].id_account != '25' && emp[0].id_account != '23' && emp[0].id_account != '26' && emp[0].id_account != '12' && emp[0].id_account != '20') {
                                    ot_credit.amount = ((base_hour + productivity_hour) * 2 * discounted).toFixed(2);
                                  } else {
                                    ot_credit.amount = ((base_hour + productivity_hour) * 1.5 * discounted).toFixed(2);
                                  }
                                  pushCredits.push(ot_credit);
                                  this.global_credits.push(ot_credit);
                                  decreto_credit.amount = '125.00';
                                }
                                igss_debit.amount = (Number(base_credit.amount) * 0.0483).toFixed(2);
                                igss_debit.type = "Descuento IGSS";

                                base_credit.idpayments = pay.idpayments;
                                productivity_credit.idpayments = pay.idpayments;
                                decreto_credit.idpayments = pay.idpayments;
                                igss_debit.idpayments = pay.idpayments;

                                this.apiService.getAutoAdjustments({ id: emp[0].idemployees, date: this.period.start }).subscribe((adjustments: attendences_adjustment[]) => {


                                  pushCredits.push(base_credit);
                                  pushCredits.push(productivity_credit);
                                  pushCredits.push(decreto_credit);
                                  pusDebits.push(igss_debit);
                                  this.global_credits.push(base_credit);
                                  this.global_credits.push(productivity_credit);
                                  this.global_credits.push(decreto_credit);
                                  this.global_debits.push(igss_debit);

                                  db.forEach(debit => {
                                    totalDeb = totalDeb + Number(debit.amount);
                                  })
                                  cd.forEach(credit => {
                                    totalCred = totalCred + Number(credit.amount)
                                  });


                                  totalCred = totalCred + Number(base_credit.amount) + Number(productivity_credit.amount) + Number(decreto_credit.amount) + Number(ot_credit.amount);
                                  totalDeb = totalDeb + Number(igss_debit.amount);

                                  adjustments.forEach(adjustment => {
                                    let new_credit: credits = new credits;
                                    let new_debit: debits = new debits;
                                    new_credit.amount = (((Number(adjustment.time_after) - Number(adjustment.time_before)) * base_hour) + ((Number(adjustment.time_after) - Number(adjustment.time_before)) * productivity_hour)).toFixed(2);
                                    new_credit.idpayments = pay.idpayments;
                                    new_credit.type = "Auto Ajuste " + adjustment.date;

                                    new_debit.amount = (((Number(adjustment.time_after) - Number(adjustment.time_before)) * base_hour) * 0.0483).toFixed(2);
                                    new_debit.idpayments = pay.idpayments;
                                    new_debit.type = "Auto Ajuste IGSS";

                                    this.global_debits.push(new_debit);
                                    this.global_credits.push(new_credit);
                                    totalCred = totalCred + Number(new_credit.amount);
                                    totalDeb = totalDeb + Number(new_debit.amount);
                                  });

                                  vac.forEach(vacat => {
                                    if (new Date(vacat.took_date) < new Date(this.period.start) && vacat.status == 'PENDING') {
                                      let new_credit2: credits = new credits;
                                      let new_debit2: debits = new debits;
                                      new_credit2.amount = ((8 * base_hour) + (8 * productivity_hour)).toFixed(2);
                                      new_credit2.idpayments = pay.idpayments;
                                      new_credit2.type = "Auto Ajuste Vacaciones " + vacat.took_date;

                                      new_debit2.amount = ((8 * base_hour) * 0.0483).toFixed(2);
                                      new_debit2.idpayments = pay.idpayments;
                                      new_debit2.type = "Auto Ajuste IGSS";

                                      this.credits.push(new_credit2);
                                      this.debits.push(new_debit2);
                                      this.global_credits.push(new_credit2);
                                      this.global_debits.push(new_debit2);
                                      totalCred = totalCred + Number(new_credit2.amount);
                                      totalDeb = totalDeb + Number(new_debit2.amount);
                                    }
                                  })


                                  services.forEach(service => {
                                    if (service.status == '1') {
                                      let partial_service: debits = new debits;
                                      if (service.max == '0') {
                                        partial_service.amount = service.amount;
                                      } else {
                                        if ((Number(service.max) - (Number(service.current) + Number(service.amount))) < 0) {
                                          partial_service.amount = service.amount;
                                          service.current = (Number(service.current) + Number(service.amount)).toFixed(2);
                                        } else {
                                          partial_service.amount = (Number(service.max) - Number(service.current)).toFixed(2);
                                          service.current = service.max;
                                          service.status = '0';
                                        }
                                      }
                                      partial_service.idpayments = pay.idpayments;
                                      partial_service.type = "Descuento Por Servicio de " + service.name;
                                      this.debits.push(partial_service);
                                      this.global_debits.push(partial_service);
                                      this.global_services.push(service);
                                      totalDeb = totalDeb + Number(partial_service.amount);
                                    }
                                  })

                                  judicials.forEach(judicial => {
                                    if (judicial.max != judicial.current) {
                                      let partial_debit: debits = new debits;
                                      if (Number(judicial.max) - (((Number(judicial.amount) / 100) * (totalCred - totalDeb)) + Number(judicial.current)) > 0) {
                                        partial_debit.amount = ((Number(judicial.amount) / 100) * (totalCred - totalDeb)).toFixed(2);
                                        judicial.current = (Number(judicial.max) + ((Number(judicial.amount) / 100) * totalCred)).toFixed(2);
                                      } else {
                                        partial_debit.amount = (Number(judicial.max) - Number(judicial.current)).toFixed(2);
                                        judicial.current = judicial.max;
                                      }
                                      partial_debit.idpayments = pay.idpayments;
                                      partial_debit.type = "Acuerdo Judicial";
                                      this.global_debits.push(partial_debit);
                                      this.debits.push(partial_debit);
                                      this.global_judicials.push(judicial);
                                      totalDeb = totalDeb + Number(partial_debit.amount);
                                    }
                                  })


                                  pay.credits = (totalCred).toFixed(2);
                                  pay.debits = (totalDeb).toFixed(2);
                                  pay.date = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString() + "-" + new Date().getDate().toString();
                                  pay.employee_name = emp[0].name;
                                  pay.total = (totalCred - totalDeb).toFixed(2);
                                  pay.nearsol_id = emp[0].nearsol_id;
                                  pay.client_id = emp[0].client_id;
                                  pay.state = emp[0].state;
                                  pay.account = emp[0].account;
                                  pay.seventh = this.seventh.toString();
                                })
                              } else {
                                pay.date = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString() + "-" + new Date().getDate().toString();;
                                pay.credits = "0.00";
                                pay.debits = "0.00";
                                pay.total = "0.00";
                              }
                            } else {
                              payments.forEach((py) => {
                                py.total = (Number(py.credits) - Number(py.debits)).toFixed(2);
                              })
                            }
                            this.progress = this.progress + 1;
                            if (this.progress == payments.length) {
                              this.working = false;
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
      })
      this.payments = payments;
      this.ded = false;
      this.showPaymentes = true;
    })
  }

  searchCloseEmployee() {
    let partial_payments: payments[] = [];
    this.showPaymentes = false;
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
    this.showPaymentes = true;
  }


  cancelCloseSearch() {
    this.showPaymentes = false;
    this.searchClosed = true;
    this.payments = this.backUp_payments;
    this.showPaymentes = true;
  }

  closeClose() {
    this.searchClosed = true;
    this.start();
    this.ded = true;
    this.showPaymentes = false;
  }

  completePeriod() {
    this.pushDeductions('credits', this.global_credits);
    this.pushDeductions('debits', this.global_debits);
    this.global_services.forEach(service => {
      if (Number(service.max) === Number(service.current)) {
        service.status = '0';
      }
      this.apiService.updateServices(service).subscribe((str: string) => { });
    });
    this.apiService.closePeriod(this.period).subscribe((str: string) => {
      this.payments.forEach(pay => {
        this.apiService.insertPayment(pay).subscribe((str: string) => { })
      })
      this.start();
      this.closePeriod();
    });
  }

  setPayTime(id_employee: string, id_profile: string) {
    this.vacations = [];
    let totalCred: number = 0;
    let totalDeb: number = 0;
    let discounted: number = 0;
    let nonShowCount: number = 0;
    let activeVac: boolean = false;
    let activeLeav: boolean = false;
    let activeDp: boolean = false;
    let janp_sequence: number = 0;

    let non_show1: boolean = false;
    let non_show2: boolean = false;
    this.non_show_2 = true;

    this.seventh = 0;
    this.absence = 0;
    this.diff = 0;
    this.attended = 0;
    this.roster = 0;
    this.daysOff = 0;

    this.credits = [];
    this.debits = [];

    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: id_employee }).subscribe((emp: employees[]) => {
      this.apiService.getVacations({ id: emp[0].id_profile }).subscribe((vac: vacations[]) => {
        this.apiService.getLeaves({ id: emp[0].id_profile }).subscribe((leave: leaves[]) => {
          this.apiService.getDPAtt({ id: emp[0].idemployees, date_1: this.period.start, date_2: this.period.end }).subscribe((dp: disciplinary_processes[]) => {
            this.apiService.getAttPeriod({ id: emp[0].idemployees, date_1: this.period.start, date_2: this.period.end }).subscribe((att: attendences[]) => {
              this.apiService.getAttAdjustments({ id: emp[0].idemployees }).subscribe((ad: attendences_adjustment[]) => {
                this.apiService.getCredits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((cd: credits[]) => {
                  this.apiService.getDebits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((db: debits[]) => {
                    this.apiService.getJudicialDiscounts({ id: emp[0].idemployees }).subscribe((judicials: judicials[]) => {
                      this.apiService.getServicesDiscounts({ id: emp[0].idemployees, date: this.period.start }).subscribe((services: services[]) => {
                        vac.forEach(vacc => {
                          if (vacc.status === 'PENDING') {
                            this.vacations.push(vacc);
                          }
                        })

                        this.leaves = leave;
                        non_show1 = false;
                        non_show2 = false;

                        if (att.length != 0) {
                          att.forEach(attendance => {

                            let dt: Date = new Date(attendance.date);

                            if (dt.getDay() === 0) {
                              this.non_show_2 = true;
                              if (nonShowCount > 3) {
                                discounted = discounted - 8;
                                this.absence = this.absence - 8;
                                this.seventh = this.seventh + 1;
                              }

                              if (janp_sequence >= 5) {
                                discounted = discounted - 16;
                                this.absence = this.absence - 16;
                                this.seventh = this.seventh + 2;
                              }

                              janp_sequence = 0;
                              nonShowCount = 0;
                            }

                            activeDp = false;
                            activeVac = false;
                            activeLeav = false;

                            vac.forEach(vacation => {
                              if (vacation.took_date == attendance.date) {
                                if (attendance.scheduled != "OFF") {
                                  this.roster = this.roster + Number(attendance.scheduled);
                                  this.attended = this.attended + Number(attendance.scheduled);
                                  attendance.balance = 'VAC';
                                } else {
                                  this.daysOff = this.daysOff + 1;
                                  attendance.balance = "OFF";
                                }
                                activeVac = true;
                              }
                            })

                            leave.forEach(leav => {
                              if (attendance.scheduled != 'OFF') {
                                if ((new Date(leav.start)) <= (new Date(attendance.date)) && (new Date(leav.end)) >= (new Date(attendance.date))) {
                                  this.roster = this.roster + Number(attendance.scheduled);
                                  activeLeav = true;
                                  if (leav.motive == 'Others Unpaid' || leav.motive == 'Leave of Absence Unpaid') {
                                    discounted = discounted - 8;
                                    this.absence = this.absence - 8;
                                    attendance.balance = 'JANP';
                                    janp_sequence = janp_sequence + 1;
                                  } else {
                                    if (leav.motive == 'Maternity' || leav.motive == 'Others Paid') {
                                      this.attended = this.attended + 8;
                                      attendance.balance = 'JAP';
                                    }
                                  }
                                }
                              }
                            })

                            dp.forEach(disciplinary => {
                              if (disciplinary.day_1 == attendance.date || disciplinary.day_2 == attendance.date || disciplinary.day_3 == attendance.date || disciplinary.day_4 == attendance.date) {
                                discounted = discounted - 8;
                                attendance.balance = "SUSPENSION"
                                activeDp = true;
                              }
                            });

                            if (!activeLeav && !activeVac && !activeDp) {
                              if (attendance.scheduled == 'OFF') {
                                if (Number(attendance.worked_time) == 0) {
                                  this.daysOff = this.daysOff + 1;
                                  attendance.balance = "OFF";
                                } else {
                                  this.attended = this.attended + Number(attendance.worked_time);
                                  this.absence = this.absence + Number(attendance.worked_time);
                                  discounted = discounted + Number(attendance.worked_time);
                                }
                              } else {
                                this.roster = this.roster + Number(attendance.scheduled);
                                if (Number(attendance.worked_time) == 0) {
                                  if (this.non_show_2) {
                                    this.absence = this.absence - 16;
                                    discounted = discounted - 16;
                                    this.seventh = this.seventh + 1;
                                    this.non_show_2 = false;
                                    attendance.balance = "NS";
                                    nonShowCount = nonShowCount + 1;
                                  } else {
                                    attendance.balance = "NS"
                                    this.absence = this.absence - 8;
                                    discounted = discounted - 8;
                                    nonShowCount = nonShowCount + 1;
                                  }
                                } else {
                                  this.attended = this.attended + Number(attendance.worked_time);
                                  this.absence = this.absence + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                  attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(2);
                                  discounted = discounted + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                }
                              }
                            }
                          });

                          if (this.attended == 0) {
                            this.absence = (this.attendances.length * 8) * (-1);
                            discounted = (this.attendances.length * 8) * (-1);
                          }

                          this.attendances = att;
                          if (this.period.status == '1') {
                            let base_hour: number = Number(emp[0].base_payment) / 240;
                            let productivity_hour: number = (Number(emp[0].productivity_payment) - 250) / 240;
                            let base_credit: credits = new credits;
                            let productivity_credit: credits = new credits;
                            let decreto_credit: credits = new credits;
                            let ot_credit: credits = new credits;
                            let igss_debit: debits = new debits;

                            base_credit.type = "Salario Base";
                            productivity_credit.type = "Bonificacion Productividad";
                            decreto_credit.type = "Bonificacion Decreto";
                            igss_debit.type = "IGSS";

                            if (this.absence <= 0) {
                              base_credit.amount = (((att.length * 8) + (this.absence)) * base_hour).toFixed(2);
                              productivity_credit.amount = (((att.length * 8) + (this.absence)) * productivity_hour).toFixed(2);
                              ot_credit.amount = '0';
                              decreto_credit.amount = ((125 / 120) * ((att.length * 8) + (this.absence))).toFixed(2);
                            } else {
                              productivity_credit.amount = ((att.length * 8) * productivity_hour).toFixed(2);
                              base_credit.amount = ((att.length * 8) * base_hour).toFixed(2);
                              decreto_credit.amount = '125.00';
                              let ot: ot_manage = new ot_manage;
                              ot.id_period = this.period.idperiods;
                              ot.id_employee = emp[0].idemployees;
                              ot_credit.type = "Horas Extra Laboradas: " + this.absence;
                              if (emp[0].id_account != '13' && emp[0].id_account != '25' && emp[0].id_account != '23' && emp[0].id_account != '26' && emp[0].id_account != '12' && emp[0].id_account != '20') {
                                ot_credit.amount = ((base_hour + productivity_hour) * 2 * this.absence).toFixed(2);
                              } else {
                                ot_credit.amount = ((base_hour + productivity_hour) * 1.5 * this.absence).toFixed(2);
                              }
                              this.credits.push(ot_credit);
                            }
                            igss_debit.amount = ((Number(base_credit.amount) + Number(ot_credit.amount)) * 0.0483).toFixed(2);

                            if (base_credit.amount != 'NaN') {
                              this.credits.push(base_credit);
                              this.credits.push(productivity_credit);
                              this.credits.push(decreto_credit);
                              this.debits.push(igss_debit);
                            }

                            db.forEach(debit => {
                              totalDeb = totalDeb + Number(debit.amount);
                              this.debits.push(debit);
                            })
                            cd.forEach(credit => {
                              totalCred = totalCred + Number(credit.amount)
                              this.credits.push(credit);
                            });

                            totalCred = totalCred + Number(base_credit.amount) + Number(productivity_credit.amount) + Number(decreto_credit.amount) + Number(ot_credit.amount);
                            totalDeb = totalDeb + Number(igss_debit.amount);

                            this.apiService.getAutoAdjustments({ id: emp[0].idemployees, date: this.period.start }).subscribe((adjustments: attendences_adjustment[]) => {
                              adjustments.forEach(adjustment => {
                                let new_credit: credits = new credits;
                                new_credit.amount = (((Number(adjustment.time_after) - Number(adjustment.time_before)) * base_hour) + ((Number(adjustment.time_after) - Number(adjustment.time_before)) * productivity_hour)).toFixed(2);
                                new_credit.type = "Auto Ajuste " + adjustment.date;

                                this.credits.push(new_credit);
                                totalCred = totalCred + Number(new_credit.amount);
                              });

                              vac.forEach(vacat => {
                                if (new Date(vacat.took_date) < new Date(this.period.start) && vacat.status == 'PENDING') {
                                  let new_credit2: credits = new credits;
                                  new_credit2.amount = ((8 * base_hour) + (8 * productivity_hour)).toFixed(2);
                                  new_credit2.type = "Auto Ajuste Vacaciones " + vacat.took_date;

                                  this.credits.push(new_credit2);
                                  totalCred = totalCred + Number(new_credit2.amount);
                                }
                              })

                              services.forEach(service => {
                                if (service.status == '1') {
                                  let partial_service: debits = new debits;
                                  if (service.max == '0') {
                                    partial_service.amount = service.amount;
                                  } else {
                                    if ((Number(service.max) - (Number(service.current) + Number(service.amount))) > 0) {
                                      partial_service.amount = service.amount;
                                      service.current = (Number(service.current) + Number(service.amount)).toFixed(2);
                                    } else {
                                      partial_service.amount = (Number(service.max) - Number(service.current)).toFixed(2);
                                      service.current = service.max;
                                    }
                                  }

                                  partial_service.type = "Descuento Por Servicio " + service.name;
                                  this.debits.push(partial_service);
                                  totalDeb = totalDeb + Number(partial_service.amount);
                                }
                              })

                              judicials.forEach(judicial => {
                                if (judicial.max != judicial.current) {
                                  let partial_debit: debits = new debits;
                                  if (Number(judicial.max) - (((Number(judicial.amount) / 100) * (totalCred - totalDeb)) + Number(judicial.current)) > 0) {
                                    partial_debit.amount = ((Number(judicial.amount) / 100) * (totalCred - totalDeb)).toFixed(2);
                                    judicial.current = (Number(judicial.max) + ((Number(judicial.amount) / 100) * totalCred)).toFixed(2);
                                  } else {
                                    partial_debit.amount = (Number(judicial.max) - Number(judicial.current)).toFixed(2);
                                    judicial.current = judicial.max;
                                  }

                                  partial_debit.type = "Acuerdo Judicial";
                                  this.debits.push(partial_debit);
                                  totalDeb = totalDeb + Number(partial_debit.amount);
                                }
                              });



                              this.totalCredits = Number((totalCred).toFixed(2));
                              this.totalDebits = Number((totalDeb).toFixed(2));
                              this.absence_fixed = (this.absence).toFixed(2);
                              this.roster = Number((this.roster).toFixed(2));
                              this.attended = Number((this.attended).toFixed(2));
                              this.diff = Number((this.roster - this.attended).toFixed(2));
                            })
                          }
                        } else {
                          db.forEach(debit => {
                            totalDeb = totalDeb + Number(debit.amount);
                            this.debits.push(debit);
                          })
                          cd.forEach(credit => {
                            totalCred = totalCred + Number(credit.amount)
                            this.credits.push(credit);
                          });

                          this.totalCredits = Number((totalCred).toFixed(2));
                          this.totalDebits = Number((totalDeb).toFixed(2));
                          this.absence_fixed = (this.absence).toFixed(2);
                          this.roster = Number((this.roster).toFixed(2));
                          this.attended = Number((this.attended).toFixed(2));
                          this.diff = Number((this.roster - this.attended).toFixed(2));
                        }
                        this.selectedEmployee = true;
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

  activeImport() {
    this.importActive = true;
  }

  completeImport() {
    if (this.importType == 'Bono') {
      this.pushDeductions('credits', this.credits);
    } else {
      this.pushDeductions('debits', this.credits);
    }
    this.completed = false;
    this.importActive = false;
    this.closePeriod();
    this.showPaymentes = true;
  }

  addfile(event) {
    this.credits = [];
    this.file = event.target.files[0];
    let partial_credits: credits[] = [];
    let fileReader = new FileReader();
    let found: boolean = false;

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
        var worksheet = workbook.Sheets[first_sheet_name];
        let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
        sheetToJson.forEach(element => {
          let cred: credits = new credits;
          cred.iddebits = element['Nearsol ID'];
          cred.amount = element['Amount'];
          partial_credits.push(cred);
        })
        let count: number = 0;
        this.apiService.getPayments(this.period).subscribe((paymnts: payments[]) => {
          partial_credits.forEach(ele => {
            this.apiService.getSearchEmployees({ dp: 'all', filter: 'nearsol_id', value: ele.iddebits }).subscribe((emp: employees[]) => {
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
              this.credits.push(ele);
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
}
