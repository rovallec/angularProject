import { NumberFormatStyle } from '@angular/common';
import { ThrowStmt } from '@angular/compiler';
import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
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

    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;

    this.apiService.getVacations({ id: emp.id_profile }).subscribe((vac: vacations[]) => {
      this.vacations = vac;
    })

    this.apiService.getLeaves({ id: emp.id_profile }).subscribe((leave: leaves[]) => {
      this.leaves = leave;
    })


    this.apiService.getAttendences({ id: emp.id_profile, date: "BETWEEN '" + this.period.start + "' AND '" + this.period.end + "'" }).subscribe((att: attendences[]) => {
      this.attendances = att;
      this.attendances.forEach(element => {
        this.vacations.forEach(el => {
          if (element.date == el.date) {
            this.attended = this.attended + 8;
            this.roster = this.roster + parseFloat(element.scheduled);
            vacs = true;
          } else {
            vacs = false;
          }
        })

        this.leaves.forEach(e => {
          if (element.date == e.date) {
            if (e.motive == "Leave of Absence Unpaid" || e.motive == "Others Unpaid") {
              this.roster = this.roster + parseFloat(element.scheduled);
            } else {
              if (e.motive == "Maternity" || e.motive == "Others Paid") {
                this.attended = this.attended + 8;
                this.roster = this.roster + parseFloat(element.scheduled);
              }
            }
            vacs = true;
          } else {
            vacs = false;
          }
        })

        if (vacs == false) {
          if (element.scheduled == 'OFF') {
            element.balance = '0';
            this.roster = this.roster + 8;
            this.attended = this.attended + 8;
            this.daysOff = this.daysOff + 1;
          } else {
            element.balance = ((parseFloat(element.worked_time) - parseFloat(element.scheduled))).toString()
            this.attended = this.attended + parseFloat(element.worked_time);
            this.roster = this.roster + parseFloat(element.scheduled);
            this.diff = this.diff + parseFloat(element.balance)
          }
        }
      });

      this.apiService.getDebits({ id: emp.id_profile, period: this.period.idperiods }).subscribe((db: debits[]) => {
        this.debits = db;
        this.debits.forEach(element => {
          this.totalDebits = this.totalDebits + parseFloat(element.amount)
        });
      });

      this.apiService.getCredits({ id: emp.id_profile, period: this.period.idperiods }).subscribe((cd: credits[]) => {
        this.credits = cd;
        this.credits.forEach(el => {
          this.totalCredits = this.totalCredits + parseFloat(el.amount);
        })
      })

      this.selectedEmployee = true;
    })
  }

  setReg(de: deductions) {

    let vacs: boolean = false;
    let non_show: boolean = false;

    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;

    this.apiService.getVacations({ id: de.idprofiles }).subscribe((vac: vacations[]) => {
      this.vacations = vac;
    })

    this.apiService.getLeaves({ id: de.idprofiles }).subscribe((leave: leaves[]) => {
      this.leaves = leave;


      this.apiService.getAttendences({ id: de.idprofiles, date: "BETWEEN '" + this.period.start + "' AND '" + this.period.end + "'" }).subscribe((att: attendences[]) => {
        this.attendances = att;
        this.attendances.forEach(element => {

          this.vacations.forEach(el => {
            if (element.date == el.took_date) {
              this.attended = this.attended + 8;
              this.roster = this.roster + parseFloat(element.scheduled);
              vacs = true;
            } else {
              vacs = false;
            }
          })

          this.leaves.forEach(e => {
            console.log((new Date(element.date)) + "<=" + (new Date(e.end)) + "&&" + (new Date(element.date)) + ">=" + (new Date(e.start)))
            if ((new Date(element.date)) <= (new Date(e.end)) && (new Date(element.date)) >= (new Date(e.start))) {
              if (!vacs) {
                if (e.motive == "Leave of Absence Unpaid" || e.motive == "Others Unpaid") {
                  this.roster = this.roster + parseFloat(element.scheduled);
                } else {
                  if (e.motive == "Maternity" || e.motive == "Others Paid") {
                    this.attended = this.attended + 8;
                    this.roster = this.roster + parseFloat(element.scheduled);
                  }
                }
                vacs = true;
              }
            } else {
              vacs = false;
            }
          })

          if (vacs == false) {
            if (element.scheduled == 'OFF') {
              if (non_show) {
                non_show = !non_show;
                this.roster = this.roster + 8;
                element.balance = '-8';
                this.daysOff = this.daysOff + 1;
              } else {
                element.balance = '0';
                this.roster = this.roster + 8;
                this.attended = this.attended + 8;
                this.daysOff = this.daysOff + 1;
              }
            } else {
              element.balance = ((parseFloat(element.worked_time) - parseFloat(element.scheduled))).toString()
              this.attended = this.attended + parseFloat(element.worked_time);
              this.roster = this.roster + parseFloat(element.scheduled);
              this.diff = this.diff + parseFloat(element.balance);
              if (element.worked_time == '0.00' || element.worked_time == '0') {
                this.apiService.getAttAdjustments({ id: de.idemployees }).subscribe((ad: attendences_adjustment[]) => {
                  ad.forEach(adjustment => {
                    if (adjustment.id_attendence != element.idattendences) {
                      non_show = true
                    }
                  })
                })
              }
            }
          }
        })
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

      this.selectedEmployee = true;
    })
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
