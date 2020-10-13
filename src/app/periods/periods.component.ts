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
    let leavs: boolean = false;
    let non_show: boolean = false;
    let cnt:number = 0;

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

          att.forEach(attendance => {
            vacs = false;
            leavs = false;
            cnt = cnt + 1;

            vac.forEach(vacation => {
              if ((new Date(vacation.took_date)) == (new Date(attendance.date)) || attendance.date == vacation.took_date) {
                this.attended = this.attended + 8;
                this.roster = this.roster + 8;
                attendance.balance = 'VAC';
                vacs = true;
              }
            })

            if (!vacs) {
              leave.forEach(leav => {
                if (leav.motive == 'Leave of Absence Unpaid' || leav.motive == 'Others Unpaid') {
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    if (attendance.scheduled == 'OFF') {
                      this.roster = this.roster + (this.roster/cnt);
                      this.diff = this.diff + (this.roster/cnt);
                      attendance.balance = 'UNPAID';
                    } else {
                      this.roster = this.roster + parseFloat(attendance.scheduled);
                      this.diff = this.diff + parseFloat(attendance.scheduled);
                      attendance.balance = 'UNPAID';
                    }
                    leavs = true;
                  }
                }else{
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    leavs = true;
                    if(attendance.scheduled = 'OFF'){
                      if(non_show){
                        this.roster = this.roster + (this.roster/cnt);
                        this.diff = this.diff + (this.roster/cnt);
                        attendance.balance = 'NON_SHOW'
                      }else{
                        this.roster = this.roster + (this.roster/cnt);
                        this.attended = this.attended + (this.roster/cnt);
                        attendance.balance = '0';
                      }
                      this.daysOff = this.daysOff + 1;
                      
                      let variable:number = this.daysOff;

                      while(variable >= 0){
                        variable = variable - 2;
                      }

                      if(variable = 0){
                        non_show = false;
                      }
                    }
                  }
                }
              })

              if(!leavs){
                if(attendance.scheduled == 'OFF'){
                  if(non_show){
                    this.roster = this.roster + (this.roster/cnt);
                    this.diff = this.diff + (this.roster/cnt);
                    attendance.balance = "NON_SHOW";
                  }else{
                    this.roster = this.roster + (this.roster/cnt);
                    this.attended = this.attended + (this.roster/cnt);
                    attendance.balance = '0';
                  }

                  this.daysOff = this.daysOff + 1;
                 
                  let variable:number = this.daysOff;

                  while(variable >= 0){
                    variable = variable - 2;
                  }

                  if(variable = 0){
                    non_show = false;
                  }
                }else{
                  this.roster = parseFloat((this.roster + parseFloat(attendance.scheduled)).toFixed(2));
                  this.attended = parseFloat((this.attended + parseFloat(attendance.worked_time)).toFixed(2));
                  this.diff = this.roster - this.attended;
                  attendance.balance = (parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)).toFixed(2);
                  if((parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)) < 0){
                    non_show = true;
                  }
                }
              }
            }
          })

        })
      })
    })

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

    this.selectedEmployee = true;
  }


  setReg(de: deductions) {

    let vacs: boolean = false;
    let leavs: boolean = false;
    let non_show: boolean = false;
    let cnt:number = 0;

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

          att.forEach(attendance => {
            vacs = false;
            leavs = false;
            cnt = cnt + 1;

            vac.forEach(vacation => {
              if ((new Date(vacation.took_date)) == (new Date(attendance.date)) || attendance.date == vacation.took_date) {
                this.attended = this.attended + (this.roster/cnt);
                this.roster = this.roster + (this.roster/cnt);
                attendance.balance = 'VAC';
                vacs = true;
              }
            })

            if (!vacs) {
              leave.forEach(leav => {
                if (leav.motive == 'Leave of Absence Unpaid' || leav.motive == 'Others Unpaid') {
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    if (attendance.scheduled == 'OFF') {
                      this.roster = this.roster + (this.roster/cnt);
                      this.diff = this.diff + (this.roster/cnt);
                      attendance.balance = 'UNPAID';
                    } else {
                      this.roster = this.roster + parseFloat(attendance.scheduled);
                      this.diff = this.diff + parseFloat(attendance.scheduled);
                      attendance.balance = 'UNPAID';
                    }
                    leavs = true;
                  }
                }else{
                  if ((new Date(attendance.date)) >= (new Date(leav.start)) && (new Date(attendance.date)) <= (new Date(leav.end))) {
                    leavs = true;
                    if(attendance.scheduled = 'OFF'){
                      if(non_show){
                        this.roster = this.roster + (this.roster/cnt);
                        this.diff = this.diff + (this.roster/cnt);
                        attendance.balance = 'NON_SHOW'
                      }else{
                        this.roster = this.roster + (this.roster/cnt);
                        this.attended = this.attended + (this.roster/cnt);
                        attendance.balance = '0';
                      }
                      this.daysOff = this.daysOff + 1;
                      
                      let variable:number = this.daysOff;

                      while(variable >= 0){
                        variable = variable - 2;
                      }

                      if(variable = 0){
                        non_show = false;
                      }
                    }
                  }
                }
              })

              if(!leavs){
                if(attendance.scheduled == 'OFF'){
                  if(non_show){
                    this.roster = this.roster + (this.roster/cnt);
                    this.diff = this.diff + (this.roster/cnt);
                    attendance.balance = "NON_SHOW";
                  }else{
                    this.roster = this.roster + (this.roster/cnt);
                    this.attended = this.attended + (this.roster/cnt);
                    attendance.balance = '0';
                  }

                  this.daysOff = this.daysOff + 1;
                 
                  let variable:number = this.daysOff;

                  while(variable >= 0){
                    variable = variable - 2;
                  }

                  if(variable = 0){
                    non_show = false;
                  }
                }else{
                  this.roster = parseFloat((this.roster + parseFloat(attendance.scheduled)).toFixed(2));
                  this.attended = parseFloat((this.attended + parseFloat(attendance.worked_time)).toFixed(2));
                  this.diff = this.roster - this.attended;
                  attendance.balance = (parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)).toFixed(2);
                  if((parseFloat(attendance.worked_time) - parseFloat(attendance.scheduled)) < 0){
                    non_show = true;
                  }
                }
              }
            }
          })

        })
      })
    })

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
