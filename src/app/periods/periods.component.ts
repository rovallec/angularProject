import { NumberFormatStyle } from '@angular/common';
import { ThrowStmt } from '@angular/compiler';
import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AttachSession } from 'protractor/built/driverProviders';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { attendences, attendences_adjustment, credits, debits, deductions, leaves, periods, vacations } from '../process_templates';

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
  period: periods = new periods;
  daysOff: number = 0;
  roster: number = 0;
  attended: number = 0;
  diff: number = 0;
  absence: number = 0;
  totalDebits: number = 0;
  totalCredits: number = 0;
  filter: string = 'name';
  value: string = null;
  ded: boolean = true;

  constructor(public apiService: ApiService, public route: ActivatedRoute) { }

  ngOnInit() {
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

  setRegE(emp: employees) {

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
                } else {
                  this.roster = parseFloat((this.roster + parseFloat(attendance.scheduled)).toFixed(2));
                  this.attended = parseFloat((this.attended + parseFloat(attendance.worked_time)).toFixed(2));
                  this.diff = parseFloat((this.roster - this.attended).toFixed(2));
                  attendance.balance = (parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)).toFixed(2);
                  if (parseFloat(attendance.worked_time) == 0) {
                    non_show = true;
                    this.apiService.getAttAdjustments({id:"id;"+emp.idemployees}).subscribe((adj: attendences_adjustment[]) => {
                      if (!non_show) {
                        let partial_nonshow: boolean = false;
                        adj.forEach(adjustment => {
                          if (adjustment.id_attendence != attendance.idattendences) {
                            partial_nonshow = true;
                          } else {
                            partial_nonshow = false;
                          }
                        })
                        non_show = partial_nonshow;
                      }
                    })
                  }
                }
              }
            }
          })

        })
      })
    })

    
    this.attendances.forEach(attendance => {
      if(attendance.balance != "VAC" && attendance.balance != 'UNPAID' && attendance.balance != "PAID" && attendance.balance != "NON_SHOW"){
        this.absence = this.absence + (parseFloat(attendance.balance));
      }else{
        if(attendance.balance == "UNPAID" || attendance.balance == "NON_SHOW"){
          this.absence = this.absence + 8;
        }
      }
      console.log(this.absence);
    });

    this.apiService.getDebits({ id: emp.idemployees, period: this.period.idperiods }).subscribe((db: debits[]) => {
      this.debits = db;
      this.debits.forEach(element => {
        this.totalDebits = this.totalDebits + parseFloat(element.amount)
      });
    });

    this.apiService.getCredits({ id: emp.idemployees, period: this.period.idperiods }).subscribe((cd: credits[]) => {
      this.credits = cd;
      this.credits.forEach(el => {
        this.totalCredits = this.totalCredits + parseFloat(el.amount);
      })
    })

    if (this.period.status == '1') {
      let cred: credits = new credits;
      let deb: debits = new debits;

      this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: emp.idemployees }).subscribe((emplo: employees[]) => {
        let hour: number = parseFloat(emplo[0].base_payment) / 240;
        cred.amount = ((120 - this.absence) * hour).toFixed(2);
        cred.type = "Apportionment Base Payment";

        deb.amount = (0.0483 * (parseFloat(cred.amount))).toFixed(2);
        deb.type = "Apportioment IGSS";

        this.credits.push(cred);
        this.debits.push(deb);
      })
    }

    this.selectedEmployee = true;
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
                        this.roster = this.roster + 8;
                        this.diff = this.diff + 8;
                        attendance.balance = 'NON_SHOW'
                      } else {
                        this.roster = this.roster + 8;
                        this.attended = this.attended + 8;
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
                    }else{
                      this.roster = this.roster + parseFloat(attendance.scheduled);
                      this.attended = this.attended + parseFloat(attendance.scheduled);
                      this.diff = this.diff + parseFloat(attendance.scheduled);
                      attendance.balance = "PAID";
                    }
                  }
                }
              })

              if (!leavs) {
                if (attendance.scheduled == 'OFF') {
                  if (non_show) {
                    this.roster = this.roster + 8;
                    this.diff = this.diff + 8;
                    attendance.balance = "NON_SHOW";
                  } else {
                    this.roster = this.roster + 8;
                    this.attended = this.attended + 8;
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
                } else {
                  this.roster = parseFloat((this.roster + parseFloat(attendance.scheduled)).toFixed(2));
                  this.attended = parseFloat((this.attended + parseFloat(attendance.worked_time)).toFixed(2));
                  this.diff = parseFloat((this.roster - this.attended).toFixed(2));
                  attendance.balance = (parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)).toFixed(2);
                  if (parseFloat(attendance.worked_time) == 0) {
                    non_show = true;
                    this.apiService.getAttAdjustments({id:"id;"+de.idemployees}).subscribe((adj: attendences_adjustment[]) => {
                      if (!non_show) {
                        let partial_nonshow: boolean = false;
                        adj.forEach(adjustment => {
                          if (adjustment.id_attendence != attendance.idattendences) {
                            partial_nonshow = true;
                          } else {
                            partial_nonshow = false;
                          }
                        })
                        non_show = partial_nonshow;
                      }
                    })
                  }
                }
              }
            }
          })

        })
      })
    })

    this.attendances.forEach(attendance => {
      if(attendance.balance != "VAC" && attendance.balance != 'UNPAID' && attendance.balance != "PAID" && attendance.balance != "NON_SHOW"){
        this.absence = this.absence + (parseFloat(attendance.balance) * -1);
      }else{
        if(attendance.balance == "UNPAID" || attendance.balance == "NON_SHOW"){
          this.absence = this.absence + 8;
        }else
        this.absence = parseFloat(attendance.balance) 
      }
    });

    this.apiService.getDebits({ id: de.idemployees, period: this.period.idperiods }).subscribe((db: debits[]) => {
      this.debits = db;
      this.debits.forEach(element => {
        this.totalDebits = this.totalDebits + parseFloat(element.amount)
      });
    });

    this.apiService.getCredits({ id: de.idemployees, period: this.period.idperiods }).subscribe((cd: credits[]) => {
      this.credits = cd;
      this.credits.forEach(el => {
        this.totalCredits = this.totalCredits + parseFloat(el.amount);
      })
    })

    if (this.period.status == '1') {
      let cred: credits = new credits;
      let deb: debits = new debits;

      this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: de.idemployees }).subscribe((emplo: employees[]) => {
        let hour: number = parseFloat(emplo[0].base_payment) / ((this.roster / this.attendances.length) * 15);
        cred.amount = (this.attended * hour).toFixed(2);
        cred.type = "Apportionment Payment";

        deb.amount = (0.0483 * (parseFloat(cred.amount))).toFixed(2);
        deb.type = "Apportioment IGSS";

        this.credits.push(cred);
        this.debits.push(deb);
      })
    }

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
}
