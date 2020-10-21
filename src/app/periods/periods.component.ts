import { NumberFormatStyle } from '@angular/common';
import { formattedError, ThrowStmt } from '@angular/compiler';
import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AttachSession } from 'protractor/built/driverProviders';
import { parse } from 'querystring';
import { isNull, isNullOrUndefined, isUndefined } from 'util';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { attendences, attendences_adjustment, credits, debits, deductions, disciplinary_processes, leaves, payments, periods, vacations } from '../process_templates';

@Component({
  selector: 'app-periods',
  templateUrl: './periods.component.html',
  styleUrls: ['./periods.component.css']
})


export class PeriodsComponent implements OnInit {

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
  backUp_payments:payments[];
  period: periods = new periods;
  daysOff: number = 0;
  roster: number = 0;
  attended: number = 0;
  diff: number = 0;
  absence: number = 0;
  totalDebits: number = 0;
  totalCredits: number = 0;
  seventh: number = 0;
  filter: string = 'name';
  absence_fixed: string = null;
  value: string = null;
  ded: boolean = true;
  non_show_2: boolean = false;
  showPaymentes: boolean = false;
  searchClosed:boolean = false;
  count_payments: number = 0;

  constructor(public apiService: ApiService, public route: ActivatedRoute) { }

  ngOnInit() {
    this.start();
  }

  start(){
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

  setRegE(emp: employees, set?: boolean, payment?: string) {
    let vacs: boolean = false;
    let leavs: boolean = false;
    let non_show: boolean = false;
    let cnt: number = 0;
    let average: number = 0;

    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
    this.absence = 0;
    this.credits = [];
    this.debits = [];

    this.apiService.getVacations({ id: emp.id_profile }).subscribe((vac: vacations[]) => {
      this.vacations = vac;

      this.apiService.getLeaves({ id: emp.id_profile }).subscribe((leave: leaves[]) => {
        this.leaves = leave;


        this.apiService.getAttendences({ id: emp.id_profile, date: "BETWEEN '" + this.period.start + "' AND '" + this.period.end + "'" }).subscribe((att: attendences[]) => {
          this.attendances = att;

          att.forEach(atte => {
            if (atte.scheduled != 'OFF') {
              average = average + parseFloat(atte.scheduled);
              cnt = cnt + 1;
            }
          })

          average = average / cnt;

          att.forEach(attendance => {
            vacs = false;
            leavs = false;

            vac.forEach(vacation => {
              if ((new Date(vacation.took_date)) == (new Date(attendance.date)) || attendance.date == vacation.took_date) {
                this.attended = this.attended + average;
                this.roster = this.roster + average;
                attendance.balance = 'VAC';
                if (attendance.scheduled == 'OFF') {
                  this.daysOff = this.daysOff + 1;
                }
                vacs = true;
              }
            })

            if (!vacs) {
              leave.forEach(leav => {
                if (leav.motive == 'Leave of Absence Unpaid' || leav.motive == 'Others Unpaid') {
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    if (attendance.scheduled == 'OFF') {
                      this.roster = this.roster + average;
                      this.diff = this.diff + average;
                      attendance.balance = 'UNPAID';
                    } else {
                      this.roster = this.roster + parseFloat(attendance.scheduled);
                      this.diff = this.diff + parseFloat(attendance.scheduled);
                      attendance.balance = 'UNPAID';
                    }
                    leavs = true;
                  }
                } else {
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    leavs = true;
                    if (attendance.scheduled = 'OFF') {
                      if (non_show) {
                        this.roster = this.roster + average;
                        this.diff = this.diff + average;
                        attendance.balance = 'NON_SHOW'
                      } else {
                        this.roster = this.roster + average;
                        this.attended = this.attended + average;
                        attendance.balance = '0';
                      }
                      this.daysOff = this.daysOff + 1;

                      let variable: number = this.daysOff;

                      while (variable >= 0) {
                        variable = variable - 2;
                      }

                      if (variable = 0) {
                        non_show = false;
                      }
                    }
                  }
                }
              })

              if (!leavs) {
                if (attendance.scheduled == 'OFF') {
                  if (non_show) {
                    this.roster = this.roster + average;
                    this.diff = this.diff + average;
                    attendance.balance = "NON_SHOW";
                    non_show = false;
                  } else {
                    if (this.non_show_2) {
                      this.roster = this.roster + average;
                      this.diff = this.diff + average;
                      attendance.balance = "NON_SHOW";
                      this.non_show_2 = false;
                    } else {
                      this.roster = this.roster + average;
                      this.attended = this.attended + average;
                      attendance.balance = '0';
                    }
                  }
                  this.daysOff = this.daysOff + 1;

                  let variable: number = this.daysOff;

                  while (variable >= 0) {
                    variable = variable - 2;
                  }

                  if (variable = 0) {
                    non_show = false;
                    this.non_show_2 = false;
                  }
                } else {
                  this.roster = parseFloat((this.roster + parseFloat(attendance.scheduled)).toFixed(2));
                  this.attended = parseFloat((this.attended + parseFloat(attendance.worked_time)).toFixed(2));
                  this.diff = parseFloat((this.roster - this.attended).toFixed(2));
                  attendance.balance = (parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)).toFixed(2);
                  if (parseFloat(attendance.worked_time) == 0) {
                    if (non_show) {
                      this.non_show_2 = true;
                    } else {
                      non_show = true;
                    }
                    this.apiService.getAttAdjustments({ id: "id;" + emp.idemployees }).subscribe((adj: attendences_adjustment[]) => {
                      if (!non_show) {
                        let partial_nonshow: boolean = false;
                        adj.forEach(adjustment => {
                          if (adjustment.id_attendence != attendance.idattendences) {
                            partial_nonshow = true;
                          } else {
                            partial_nonshow = false;
                          }
                        })
                        if (this.non_show_2) {
                          this.non_show_2 = partial_nonshow;
                        } else {
                          non_show = partial_nonshow;
                        }
                      }
                    })
                  }
                }
              }
            }
          })

          att.forEach(attendance => {
            if (attendance.balance != "VAC" && attendance.balance != 'UNPAID' && attendance.balance != "PAID" && attendance.balance != "NON_SHOW") {
              this.absence = this.absence + (parseFloat(attendance.balance));
            } else {
              if (attendance.balance == "UNPAID" || attendance.balance == "NON_SHOW") {
                this.absence = this.absence - 8;
                if (attendance.balance == 'NON_SHOW') {
                  this.seventh = this.seventh + 1;
                }
              }
            }
            this.absence_fixed = this.absence.toFixed(2);
          });


          this.apiService.getDebits({ id: emp.idemployees, period: this.period.idperiods }).subscribe((db: debits[]) => {
            this.apiService.getCredits({ id: emp.idemployees, period: this.period.idperiods }).subscribe((cd: credits[]) => {
              this.credits = cd;
              this.debits = db;
              if (this.period.status == '1') {
                let cred: credits = new credits;
                let deb: debits = new debits;
                let cred2: credits = new credits;
                let cred3: credits = new credits;

                this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: emp.idemployees }).subscribe((emplo: employees[]) => {
                  let hour: number = parseFloat(emplo[0].base_payment) / 240;
                  let p_hour: number = parseFloat(emplo[0].productivity_payment) / 240;

                  cred.amount = (((this.attendances.length * 8) + this.absence) * hour).toFixed(2);
                  cred.type = "Prorrateo Sueldo Base";

                  cred2.amount = (((this.attendances.length * 8) + this.absence) * p_hour).toFixed(2);
                  cred2.type = "Prorrateo Bono De Productividad";

                  deb.amount = (0.0483 * (parseFloat(cred.amount))).toFixed(2);
                  deb.type = "Prorrateo IGSS";

                  deb.iddebits = this.debits.length.toString();
                  cred.iddebits = this.credits.length.toString();
                  cred2.iddebits = (this.credits.length + 1).toString();
                  cred3.iddebits = (this.credits.length + 2).toString();

                  if (parseInt(payment) > 0) {
                    cred.idpayments = payment.toString();
                    cred2.idpayments = payment.toString();
                    cred3.idpayments = payment.toString();
                    deb.idpayments = payment.toString();
                    deb.type = "IGSS";
                    cred.type = "Sueldo Base";
                    cred2.type = "Bonificacion De Productividad";

                    cred3.amount = "250.00";
                    cred3.type = "Bonificacion Incentivo";
                    this.credits.push(cred3);

                    this.global_credits.push(cred);
                    this.global_credits.push(cred2);
                    this.global_credits.push(cred3);

                    this.global_debits.push(deb);
                  }

                  this.credits.push(cred);
                  this.credits.push(cred2);
                  this.debits.push(deb);

                  db.forEach(db_p => {
                    this.totalDebits = this.totalDebits + parseFloat(db_p.amount);
                  });

                  cd.forEach(cd_p => {
                    this.totalCredits = this.totalCredits + parseFloat(cd_p.amount);
                  });
                })
              }
            });
          });
        })
      })
    })
    if (set) {
      this.selectedEmployee = false;
    } else {
      this.selectedEmployee = true;
    }
  }


  setReg(de: deductions) {

    let vacs: boolean = false;
    let leavs: boolean = false;
    let non_show: boolean = false;
    let cnt: number = 0;
    let average: number = 0;

    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
    this.absence = 0;
    this.credits = [];
    this.debits = [];

    this.apiService.getVacations({ id: de.idprofiles }).subscribe((vac: vacations[]) => {
      this.vacations = vac;

      this.apiService.getLeaves({ id: de.idprofiles }).subscribe((leave: leaves[]) => {
        this.leaves = leave;


        this.apiService.getAttendences({ id: de.idprofiles, date: "BETWEEN '" + this.period.start + "' AND '" + this.period.end + "'" }).subscribe((att: attendences[]) => {
          this.attendances = att;

          att.forEach(atte => {
            if (atte.scheduled != 'OFF') {
              average = average + parseFloat(atte.scheduled);
              cnt = cnt + 1;
            }
          })

          average = average / cnt;

          att.forEach(attendance => {
            vacs = false;
            leavs = false;

            vac.forEach(vacation => {
              if ((new Date(vacation.took_date)) == (new Date(attendance.date)) || attendance.date == vacation.took_date) {
                this.attended = this.attended + average;
                this.roster = this.roster + average;
                attendance.balance = 'VAC';
                if (attendance.scheduled == 'OFF') {
                  this.daysOff = this.daysOff + 1;
                }
                vacs = true;
              }
            })

            if (!vacs) {
              leave.forEach(leav => {
                if (leav.motive == 'Leave of Absence Unpaid' || leav.motive == 'Others Unpaid') {
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    if (attendance.scheduled == 'OFF') {
                      this.roster = this.roster + average;
                      this.diff = this.diff + average;
                      attendance.balance = 'UNPAID';
                    } else {
                      this.roster = this.roster + parseFloat(attendance.scheduled);
                      this.diff = this.diff + parseFloat(attendance.scheduled);
                      attendance.balance = 'UNPAID';
                    }
                    leavs = true;
                  }
                } else {
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    leavs = true;
                    if (attendance.scheduled = 'OFF') {
                      if (non_show) {
                        this.roster = this.roster + average;
                        this.diff = this.diff + average;
                        attendance.balance = 'NON_SHOW'
                      } else {
                        this.roster = this.roster + average;
                        this.attended = this.attended + average;
                        attendance.balance = '0';
                      }
                      this.daysOff = this.daysOff + 1;

                      let variable: number = this.daysOff;

                      while (variable >= 0) {
                        variable = variable - 2;
                      }

                      if (variable = 0) {
                        non_show = false;
                      }
                    }
                  }
                }
              })

              if (!leavs) {
                if (attendance.scheduled == 'OFF') {
                  if (non_show) {
                    this.roster = this.roster + average;
                    this.diff = this.diff + average;
                    attendance.balance = "NON_SHOW";
                    non_show = false;
                  } else {
                    if (this.non_show_2) {
                      this.roster = this.roster + average;
                      this.diff = this.diff + average;
                      attendance.balance = "NON_SHOW";
                      this.non_show_2 = false;
                    } else {
                      this.roster = this.roster + average;
                      this.attended = this.attended + average;
                      attendance.balance = '0';
                    }
                  }
                  this.daysOff = this.daysOff + 1;

                  let variable: number = this.daysOff;

                  while (variable >= 0) {
                    variable = variable - 2;
                  }

                  if (variable = 0) {
                    non_show = false;
                    this.non_show_2 = false;
                  }
                } else {
                  this.roster = parseFloat((this.roster + parseFloat(attendance.scheduled)).toFixed(2));
                  this.attended = parseFloat((this.attended + parseFloat(attendance.worked_time)).toFixed(2));
                  this.diff = parseFloat((this.roster - this.attended).toFixed(2));
                  attendance.balance = (parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)).toFixed(2);
                  if (parseFloat(attendance.worked_time) == 0) {
                    if (non_show) {
                      this.non_show_2 = true;
                    } else {
                      non_show = true;
                    }
                    this.apiService.getAttAdjustments({ id: "id;" + de.idemployees }).subscribe((adj: attendences_adjustment[]) => {
                      if (!non_show) {
                        let partial_nonshow: boolean = false;
                        adj.forEach(adjustment => {
                          if (adjustment.id_attendence != attendance.idattendences) {
                            partial_nonshow = true;
                          } else {
                            partial_nonshow = false;
                          }
                        })
                        if (this.non_show_2) {
                          this.non_show_2 = partial_nonshow;
                        } else {
                          non_show = partial_nonshow;
                        }
                      }
                    })
                  }
                }
              }
            }
          })

          att.forEach(attendance => {
            if (attendance.balance != "VAC" && attendance.balance != 'UNPAID' && attendance.balance != "PAID" && attendance.balance != "NON_SHOW") {
              this.absence = this.absence + (parseFloat(attendance.balance));
            } else {
              if (attendance.balance == "UNPAID" || attendance.balance == "NON_SHOW") {
                this.absence = this.absence - 8;
                if (attendance.balance == 'NON_SHOW') {
                  this.seventh = this.seventh + 1;
                }
              }
            }
            this.absence_fixed = this.absence.toFixed(2);
          });


          this.apiService.getDebits({ id: de.idemployees, period: this.period.idperiods }).subscribe((db: debits[]) => {
            this.apiService.getCredits({ id: de.idemployees, period: this.period.idperiods }).subscribe((cd: credits[]) => {

              this.credits = cd;
              this.debits = db;

              if (this.period.status == '1') {
                let cred: credits = new credits;
                let cred2: credits = new credits;
                let deb: debits = new debits;

                this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: de.idemployees }).subscribe((emplo: employees[]) => {
                  let hour: number = parseFloat(emplo[0].base_payment) / 240;
                  let p_hour: number = parseFloat(emplo[0].productivity_payment) / 240;

                  cred.amount = (((this.attendances.length * 8) + this.absence) * hour).toFixed(2);
                  cred.type = "Prorrateo Sueldo Base";

                  cred2.amount = (((this.attendances.length * 8) + this.absence) * p_hour).toFixed(2);
                  cred2.type = "Prorrateo Bono De Productividad";

                  deb.amount = (0.0483 * (parseFloat(cred.amount))).toFixed(2);
                  deb.type = "Prorrateo IGSS";

                  this.credits.push(cred);
                  this.credits.push(cred2);
                  this.debits.push(deb);

                  this.debits.forEach(db_p => {
                    this.totalDebits = this.totalDebits + parseFloat(db_p.amount);
                  });

                  this.credits.forEach(cd_p => {
                    this.totalCredits = this.totalCredits + parseFloat(cd_p.amount);
                  });
                })
              }
            });
          });
        })
      })
    })
    this.selectedEmployee = true;
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
    let pushCredits:credits[] = [];
    let pusDebits:debits[] = [];
    
    this.global_debits = [];
    this.global_credits = [];

    let end:number = 0;

    this.apiService.getPayments(this.period).subscribe((payments: payments[]) => {

      payments.forEach(pay => {
        let totalCred:number = 0;
        let totalDeb:number = 0;
        let discounted: number = 0;
        let offCount: number = 0;
        let activeVac: boolean = false;
        let activeLeav: boolean = false;
        let activeDp:boolean = false;

        let non_show1: boolean = false;
        let non_show2: boolean = false;

        
        pushCredits = [];
        pusDebits = [];

        this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: pay.id_employee }).subscribe((emp: employees[]) => {
          this.apiService.getVacations({ id: emp[0].id_profile }).subscribe((vac: vacations[]) => {
            this.apiService.getLeaves({ id: emp[0].id_profile }).subscribe((leave: leaves[]) => {
              this.apiService.getDisciplinaryProcesses({ id: emp[0].id_profile }).subscribe((dp: disciplinary_processes[]) => {
                this.apiService.getAttendences({ id: emp[0].id_profile, date: "BETWEEN '" + this.period.start + "' AND '" + this.period.end + "'" }).subscribe((att: attendences[]) => {
                  this.apiService.getAttAdjustments({ id: "id;" + emp[0].idemployees }).subscribe((ad: attendences_adjustment[]) => {
                    att.forEach(attendance => {
                      vac.forEach(vacation => {
                        if (vacation.took_date == attendance.date) {
                          activeVac = true;
                        }
                      })

                      leave.forEach(leav => {
                        if (leav.date == attendance.date) {
                          activeLeav = true;
                          if (leav.motive == 'UNPAID' || leav.motive == 'Leave of Absence Unpaid') {
                            discounted = discounted - 8;
                          }
                        }
                      })

                      dp.forEach(disciplinary => {
                        if(disciplinary.day_1 == attendance.date || disciplinary.day_2 == attendance.date || disciplinary.day_3 == attendance.date || disciplinary.day_4 == attendance.date){
                          discounted = discounted - 8;
                          activeDp = true;
                        }
                      });

                      if (!activeLeav && !activeVac && !activeDp) {
                        let partial_non_show:boolean = false;
                        if (attendance.scheduled == 'OFF') {
                          offCount = offCount + 1;
                          while (offCount > 0) {
                            offCount = offCount - 2;
                          }
                          if (non_show1) {
                            discounted = discounted - 8;
                            non_show1 = false;
                          } else {
                            if (non_show2 = true && offCount == 0) {
                              discounted = discounted - 8;
                              non_show2 = false;
                            }
                          }
                        } else {
                          if (parseFloat(attendance.worked_time)  == 0) {
                            if (non_show1) {
                              ad.forEach(adjustment => {
                                if (adjustment.date == attendance.date) {
                                  partial_non_show = true;
                                }
                              });
                              if(!partial_non_show){
                                non_show2 = true;
                              }
                            } else {
                              ad.forEach(adjustment => {
                                if(adjustment.date == attendance.date){
                                  partial_non_show = true;
                                }
                              })
                              if(!partial_non_show){
                                non_show1 = true;
                              }
                            }
                          }
                          discounted = discounted + (parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled))
                        }
                      }
                    });
                    
                    let base_hour:number = parseFloat(emp[0].base_payment) / 240;
                    let productivity_hour:number = parseFloat(emp[0].productivity_payment) / 240;
                    let base_credit:credits = new credits;
                    let productivity_credit:credits = new credits;
                    let decreto_credit:credits = new credits;
                    let ot_credit:credits = new credits;
                    let igss_debit:debits = new debits;

                    base_credit.type = "Salario Base";
                    productivity_credit.type = "Bonificacion Productividad";
                    decreto_credit.type = "Bonificacion Decreto";

                    if(discounted < 0){
                      base_credit.amount = (((att.length*8) + (discounted)) * base_hour).toFixed(2);
                      productivity_credit.amount = (((att.length * 8) + (discounted) ) * productivity_hour).toFixed(2);
                      ot_credit.amount = '0';
                    }else{
                      productivity_credit.amount = (120 * productivity_hour).toFixed(2);
                      base_credit.amount = (120 * base_hour).toFixed(2);
                      productivity_credit.amount = (120*productivity_hour).toFixed(2);
                      ot_credit.type = "Horas Extra Laboradas: " + discounted;
                      if(emp[0].id_account != '13' && emp[0].id_account != '25' && emp[0].id_account != '23' && emp[0].id_account != '26' && emp[0].id_account != '12'){
                        ot_credit.amount = ((base_hour + productivity_hour)*2*discounted).toFixed(2);
                      }else{
                        ot_credit.amount = ((base_hour + productivity_hour)*1.5*discounted).toFixed(2);
                      }
                      ot_credit.idpayments = pay.idpayments;
                      pushCredits.push(ot_credit);
                    }
                    decreto_credit.amount = '250.00';
                    igss_debit.amount = (parseFloat(base_credit.amount) * 0.0483).toFixed(2);

                    base_credit.idpayments = pay.idpayments;
                    productivity_credit.idpayments = pay.idpayments;
                    decreto_credit.idpayments = pay.idpayments;
                    igss_debit.idpayments = pay.idpayments;

                    pushCredits.push(base_credit);
                    pushCredits.push(productivity_credit);
                    pushCredits.push(decreto_credit);
                    pusDebits.push(igss_debit);

                    this.apiService.getCredits({id:emp[0].idemployees, period:this.period.idperiods}).subscribe((cd:credits[])=>{
                      cd.forEach(credit => {
                        totalCred = totalCred + parseFloat(credit.amount)
                      });
                    })

                    this.apiService.getDebits({id:emp[0].idemployees, period:this.period.idperiods}).subscribe((db:debits[])=>{
                      db.forEach(debit=>{
                        totalDeb = totalDeb + parseFloat(debit.amount);
                      })
                    })

                    pushCredits.forEach(cred=>{
                      this.global_credits.push(cred);
                    })

                    pusDebits.forEach(deb=>{
                      this.global_debits.push(deb);
                    })

                    totalCred = totalCred + parseFloat(base_credit.amount) + parseFloat(productivity_credit.amount) + parseFloat(decreto_credit.amount) + parseFloat(ot_credit.amount);
                    totalDeb = totalDeb + parseFloat(igss_debit.amount);

                    pay.credits = (totalCred).toFixed(2);
                    pay.debits = (totalDeb).toFixed(2);
                    pay.date = new Date().getFullYear().toString() + "-" + new Date().getMonth().toString() + "-" + new Date().getDate().toString();
                    pay.employee_name = emp[0].name;
                    pay.total = (totalCred - totalDeb).toFixed(2);
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

  searchCloseEmployee(){
    let partial_payments:payments[] = [];
    this.showPaymentes = false;
    if(!this.searchClosed){
      this.backUp_payments = this.payments;
    }
    this.payments.forEach(pay=>{
      if(pay.employee_name.includes(this.value)){
        partial_payments.push(pay);
      }
    })
    this.payments = partial_payments;
    this.searchClosed = false;
    this.showPaymentes = true;
  }


  cancelCloseSearch(){
    this.showPaymentes = false;
    this.searchClosed = true;
    this.payments = this.backUp_payments;
    this.showPaymentes = true;
  }

  closeClose(){
    this.searchClosed = true;
    this.start();
    this.ded = true;
    this.showPaymentes = false;
  }

  completePeriod(){
    console.log(this.global_credits);
    console.log(this.global_debits);
    console.log(this.payments);
  }
}
