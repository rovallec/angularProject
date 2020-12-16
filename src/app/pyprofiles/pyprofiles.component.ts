import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, RouterLinkActive } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { process } from '../process';
import { attendences, attendences_adjustment, change_id, disciplinary_processes, leaves, payments, periods, process_templates, vacations } from '../process_templates';
import { profiles } from '../profiles';

@Component({
  selector: 'app-pyprofiles',
  templateUrl: './pyprofiles.component.html',
  styleUrls: ['./pyprofiles.component.css']
})
export class PyprofilesComponent implements OnInit {

  employee: employees = new employees;
  profile: profiles = new profiles;
  processes_template: process_templates[] = [];
  processRecord: process[] = [];
  activeProc: process_templates = new process_templates;
  newProc: boolean = false;
  newProcess: boolean = false;
  viewRecProd: boolean = false;
  activeChangeID: change_id = new change_id;
  todayDate: string = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString() + "-" + new Date().getDate().toString();

  vacations: vacations[] = [];
  leaves: leaves[] = [];
  attendances:attendences[] = [];
  non_show_2: boolean = false;
  seventh: number = 0;
  absence: number = 0;
  diff: number = 0;
  attended: number = 0;
  roster: number = 0;
  daysOff: number = 0;
  absence_fixed:string = null;
  showRegs:boolean = false;

  constructor(public apiService: ApiService, public route: ActivatedRoute, public authUser: AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  setProcess(proc: process_templates) {
    switch (proc.name) {
      case 'Client ID Change':
        this.activeChangeID.proc_name = proc.name;
        this.activeChangeID.proc_status = 'PENDING';
        this.activeChangeID.date = this.todayDate;
        this.activeChangeID.id_user = this.authUser.getAuthusr().user_name;
        this.activeChangeID.old_id = this.employee.client_id;
        this.activeChangeID.id_employee = this.employee.idemployees;
      default:
        break;
    }
    this.activeProc = proc;
    this.newProc = true;
  }

  addProcess() {
    this.newProcess = true
  }

  insertProc() {
    switch (this.activeProc.name) {
      case 'Client ID Change':
        this.activeChangeID.id_user = this.authUser.getAuthusr().iduser;
        this.activeChangeID.proc_status = 'COMPLETED';
        this.apiService.insertChangeClientID(this.activeChangeID).subscribe((str: string) => {
          this.newProcess = false;
          this.activeChangeID = new change_id;
        })
        break;

      default:
        break;
    }
  }

  start() {
    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: this.route.snapshot.paramMap.get('id') }).subscribe((emp: employees[]) => {
      this.employee = emp[0]
      this.setPayTime(this.employee.idemployees, this.employee.id_profile);
      let prof: profiles = new profiles;
      prof.idprofiles = emp[0].id_profile;
      this.apiService.getProfile(prof).subscribe((profile: profiles[]) => {
        this.profile = profile[0];
      })
    })

    this.apiService.getPayrollTemplates().subscribe((temp: process_templates[]) => {
      this.processes_template = temp;
    })
  }


  cancelView() {
    this.newProc = false;
    this.activeProc = new process_templates;
  }

  return() {
    this.newProc = false;
  }


  setPayTime(id_employee: string, id_profile: string) {

    let dt = new Date();
    let nd: string = null;
    let dt2: Date = new Date();
    let strt: string = null;

    if (dt.getDate() > 16) {
      strt = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + "16";
      dt2 = new Date(dt.getFullYear(), (dt.getMonth() + 2), 0);
      nd = dt2.getFullYear() + "-" + (dt2.getMonth() + 1) + "-" + dt2.getDate();
    } else {
      strt = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + "01";
      nd = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + "15";
    }

    this.vacations = [];
    let totalCred: number = 0;
    let totalDeb: number = 0;
    let discounted: number = 0;
    let nonShowCount: number = 0;
    let activeVac: boolean = false;
    let activeLeav: boolean = false;
    let activeDp: boolean = false;
    let janp_sequence: number = 0;
    let last_seventh: boolean = true;

    let non_show1: boolean = false;
    let non_show2: boolean = false;
    this.non_show_2 = true;

    let periods: periods[] = []

    this.seventh = 0;
    this.absence = 0;
    this.diff = 0;
    this.attended = 0;
    this.roster = 0;
    this.daysOff = 0;

    this.apiService.getPeriods().subscribe((pr: periods[]) => {
      periods = pr;
    })

    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: id_employee }).subscribe((emp: employees[]) => {
      this.apiService.getVacations({ id: emp[0].id_profile }).subscribe((vac: vacations[]) => {
        this.apiService.getLeaves({ id: emp[0].id_profile }).subscribe((leave: leaves[]) => {
          this.apiService.getDPAtt({ id: emp[0].idemployees, date_1: strt, date_2: nd }).subscribe((dp: disciplinary_processes[]) => {
            this.apiService.getAttPeriod({ id: emp[0].idemployees, date_1: strt, date_2: nd }).subscribe((att: attendences[]) => {
              this.apiService.getAttAdjustments({ id: emp[0].idemployees }).subscribe((ad: attendences_adjustment[]) => {
                let py: payments = new payments;
                py.id_employee = emp[0].idemployees;
                py.id_period = periods[periods.length - 1].idperiods;
                this.apiService.getLastSeventh(py).subscribe((pyment: payments) => {
                  if (pyment.last_seventh == '1') {
                    last_seventh = false;
                  }
                })
                vac.forEach(vacc => {
                  if (vacc.status === 'PENDING') {
                    this.vacations.push(vacc);
                  }
                })

                this.leaves = leave;
                non_show1 = false;
                non_show2 = false;

                if (att.length != 0) {
                  this.attendances = att;
                  att.forEach(attendance => {

                    let dt: Date = new Date(attendance.date);

                    if (dt.getDay() === 0) {
                      this.non_show_2 = true;
                      if (nonShowCount == 5) {
                        discounted = discounted - 8;
                        this.absence = this.absence - 8;
                      }

                      if (janp_sequence == 5) {
                        discounted = discounted - 8;
                        this.absence = this.absence - 8;
                      }

                      janp_sequence = 0;
                      nonShowCount = 0;
                    }

                    activeDp = false;
                    activeVac = false;
                    activeLeav = false;

                    dp.forEach(disciplinary => {
                      if (disciplinary.day_1 == attendance.date || disciplinary.day_2 == attendance.date || disciplinary.day_3 == attendance.date || disciplinary.day_4 == attendance.date) {
                        this.absence = this.absence - 8;
                        discounted = discounted - 8;
                        attendance.balance = "SUSPENSION"
                        activeDp = true;
                      }
                    });

                    if (!activeDp) {
                      leave.forEach(leav => {
                        if ((new Date(leav.start)) <= (new Date(attendance.date)) && (new Date(leav.end)) >= (new Date(attendance.date))) {
                          if (attendance.scheduled != 'OFF') {
                            this.roster = this.roster + Number(attendance.scheduled);
                          }
                          activeLeav = true;
                          if (leav.motive == 'Others Unpaid' || leav.motive == 'Leave of Absence Unpaid') {
                            discounted = discounted - 8;
                            this.absence = this.absence - 8;
                            attendance.balance = 'JANP';
                            if (attendance.scheduled != 'OFF') {
                              janp_sequence = janp_sequence + 1;
                            }
                          } else {
                            if (leav.motive == 'Maternity' || leav.motive == 'Others Paid') {
                              this.attended = this.attended + 8;
                              attendance.balance = 'JAP';
                            }
                          }
                        }
                      })
                    }

                    if (!activeDp && !activeLeav) {
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
                    }

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
                          if (this.non_show_2 && last_seventh) {
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
                          this.absence_fixed = (this.absence).toFixed(2);
                          this.roster = Number((this.roster).toFixed(2));
                          this.attended = Number((this.attended).toFixed(2));
                          this.diff = Number((this.roster - this.attended).toFixed(2));
                        }
                      }
                    }
                  })
                }

                this.attendances.forEach(attend => {
                  if((Number(attend.worked_time) > 0) && attend.balance == 'VAC'){
                    console.log(attend);
                    attend.status = 'overlap';
                  }
                });
              })
            })
          })
        })
      })
    })
  }

  showReg(){
    this.showRegs = true;
  }
}
