import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, RouterLinkActive } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { process } from '../process';
import { attendences, attendences_adjustment, change_id, disciplinary_processes, leaves, paid_attendances, payments, periods, process_templates, terminations, vacations } from '../process_templates';
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
  dps: disciplinary_processes[] = [];
  attendances: attendences[] = [];
  non_show_2: boolean = false;
  seventh: number = 0;
  absence: number = 0;
  diff: number = 0;
  attended: number = 0;
  roster: number = 0;
  daysOff: number = 0;
  absence_fixed: string = null;
  showRegs: boolean = false;
  termination: terminations = new terminations;
  edit: boolean = false;

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
    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: this.route.snapshot.paramMap.get('id'), rol: this.authUser.getAuthusr().id_role }).subscribe((emp: employees[]) => {
      this.employee = emp[0];
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
    this.showRegs = false;
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
      strt = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + "1";
      dt2 = new Date(dt.getFullYear(), (dt.getMonth() + 2), 0);
      nd = dt2.getFullYear() + "-" + (dt2.getMonth() + 1) + "-" + dt2.getDate();
    } else {
      strt = dt.getFullYear() + "-" + (dt.getMonth()) + "-" + "16";
      nd = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + "15";
    }

    this.vacations = [];
    this.leaves = [];
    this.dps = [];
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

    let periods_pr: periods[] = []

    this.seventh = 0;
    this.absence = 0;
    this.diff = 0;
    this.attended = 0;
    this.roster = 0;
    this.daysOff = 0;

    this.apiService.getPeriods().subscribe((pr: periods[]) => {
      periods_pr = pr;
      this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: id_employee, rol: this.authUser.getAuthusr().id_role }).subscribe((emp: employees[]) => {
        this.apiService.getVacations({ id: emp[0].id_profile }).subscribe((vac: vacations[]) => {
          this.apiService.getLeaves({ id: emp[0].id_profile }).subscribe((leave: leaves[]) => {
            this.apiService.getDPAtt({ id: emp[0].idemployees, date_1: strt, date_2: nd }).subscribe((dp: disciplinary_processes[]) => {
              this.apiService.getAttPeriod({ id: emp[0].idemployees, date_1: strt, date_2: nd }).subscribe((att: attendences[]) => {
                this.apiService.getAttAdjustments({ id: emp[0].idemployees }).subscribe((ad: attendences_adjustment[]) => {
                  this.apiService.getTermdt(emp[0]).subscribe((trm: terminations) => {
                    this.vacations = vac;
                    this.dps = dp;
                    this.leaves = leave;
                    let prov_period: periods = new periods;
                    let dt: Date = new Date(periods_pr[periods_pr.length - 2].start);
                    dt.setDate((dt.getDate() + 1) - (dt.getDay()));
                    prov_period.idperiods = emp[0].idemployees;
                    prov_period.end = "'" + dt.getFullYear() + "-" +
                      (dt.getMonth() + 1).toString().padStart(2, "0") + "-" +
                      (dt.getDate()).toString().padStart(2, "0") + "' AND '" +
                      periods_pr[periods_pr.length - 1].start + "' AND `balance` = 'NS'";
                    prov_period.start = "explicit";
                    this.apiService.getPaidAttendances(prov_period).subscribe((p_at: paid_attendances[]) => {
                      let pp:periods = new periods;
                      pp.idperiods = emp[0].idemployees;
                      pp.end =  "'" + dt.getFullYear() + "-" +
                      (dt.getMonth() + 1).toString().padStart(2, "0") + "-" +
                      (dt.getDate()).toString().padStart(2, "0") + "' AND '" +
                      periods_pr[periods_pr.length - 1].start + "'";
                      pp.start = "explicit";
                      console.log(pp.end);
                      this.apiService.getPaidAttendances(pp).subscribe((p_balance:paid_attendances[])=>{
                        att.forEach(p_balance_at=>{
                          p_balance.forEach(element => {
                            if(p_balance_at.date == element.date){
                              p_balance_at.balance = element.balance;
                            }
                          });
                        })
                      
                      if (att.length != 0) {
                        this.attendances = att;

                        let activeVacation: boolean = false;
                        let activeLeave: boolean = false;
                        let activeSuspension: boolean = false;
                        let non_show: boolean = false;
                        let sevenths: number = 0;
                        let discounted_days: number = 0;
                        let discounted_hours: number = 0;
                        let ot_hours: number = 0;
                        let hld_hours: number = 0;
                        let performance_bonus: number = 0;
                        let treasure_hunt: number = 0;
                        let janp_sequence: number = 0;
                        let janp_on_off: number = 0;
                        let non_show_sequence: number = 0;
                        let days_off: number = 0;
                        let off_on_week: number = 0;
                        let cnt_days: number = 0;
                        let valid_trm: boolean = false;
                        let valid_transfer: boolean = false;
                        let ult_seventh: number = 0;
                        let worked_days: number = 0;
                        let ns_count: number = 0;
                        let janp_on_off_2: number = 0;
                        let carry_seventh: boolean = null;
                        let trm_count: number = 0;
                        let hld: number = 0;
                        let hldT: number = 0;
                        let week_work: number = 0;
                        let mother_father_day: boolean = false;
                        let disc_on_week: number = 0;

                        if (!isNullOrUndefined(p_at)) {
                          if (p_at.length > 0) {
                            carry_seventh = true;
                            non_show = true;
                            non_show_sequence = p_at.length;
                          }
                        }


                        att.forEach(attendance => {
                          if (new Date(attendance.date).getTime() >= new Date(periods_pr[periods_pr.length - 1].start).getTime()) {
                            let dt: Date = new Date(attendance.date);
                            activeVacation = false;
                            activeLeave = false;
                            activeSuspension = false;
                            mother_father_day = false;


                            if (!isNullOrUndefined(trm.valid_from)) {
                              if (new Date(trm.valid_from).getTime() <= new Date(attendance.date).getTime()) {
                                valid_trm = true;
                              }
                            }

                            if (!valid_trm) {
                              dp.forEach(disciplinary_process => {
                                if (!activeSuspension && disciplinary_process.day_1 == attendance.date || disciplinary_process.day_2 == attendance.date || disciplinary_process.day_3 == attendance.date || disciplinary_process.day_4 == attendance.date) {
                                  activeSuspension = true;
                                  attendance.balance = 'JANP';
                                  discounted_days = discounted_days + 1;
                                  janp_sequence = janp_sequence + 1;
                                  disc_on_week = disc_on_week + 1;
                                }
                              })

                              if (!activeSuspension) {
                                vac.forEach(vacation => {
                                  if (!activeVacation && vacation.status != "COMPLETED" && vacation.status != 'DISMISSED' && vacation.took_date == attendance.date && vacation.action == "Take") {
                                    activeVacation = true;
                                    week_work++;
                                    worked_days++;
                                    if (attendance.scheduled == 'OFF') {
                                      days_off = days_off + 1;
                                    }
                                    attendance.balance = 'VAC';
                                    if (Number(vacation.count) < 1) {
                                      if (attendance.scheduled != "OFF") {
                                        attendance.worked_time = (Number(attendance.worked_time) + (Number(attendance.scheduled) * Number(vacation.count))).toFixed(5);
                                        attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(3);
                                        discounted_hours = discounted_hours + Number(attendance.worked_time) - Number(attendance.scheduled);
                                      }
                                    }
                                  }
                                })
                              }

                              if (!activeSuspension && !activeVacation) {
                                leave.forEach(lv => {
                                  if (!activeLeave && lv.status != "COMPLETED" && lv.status != 'DISMISSED' && (new Date(lv.start).getTime()) <= (new Date(attendance.date).getTime()) && (new Date(lv.end).getTime()) >= (new Date(attendance.date).getTime())) {
                                    activeLeave = true;
                                    if (lv.motive == 'Leave of Absence Unpaid') {
                                      attendance.balance = 'LOA';
                                      discounted_days = discounted_days + 1;
                                      disc_on_week = disc_on_week + 1
                                      janp_sequence = janp_sequence + 1;
                                      if (attendance.scheduled == 'OFF') {
                                        days_off = days_off + 1;
                                        janp_on_off = janp_on_off + 1;
                                      }
                                    }
                                    if (lv.motive == 'Others Unpaid' || lv.motive == "IGSS Unpaid" || lv.motive == "VTO Unpaid" || lv.motive == "COVID Unpaid") {
                                      attendance.balance = 'JANP';
                                      janp_sequence = janp_sequence + 1;
                                      discounted_days = discounted_days + 1;
                                      disc_on_week = disc_on_week + 1
                                      if (attendance.scheduled == 'OFF') {
                                        days_off = days_off + 1;
                                        janp_on_off = janp_on_off + 1;
                                      }
                                    }
                                    if (lv.motive == 'Maternity' || lv.motive == 'Others Paid' || lv.motive == "COVID Paid" || lv.motive == "IGSS Paid") {
                                      attendance.balance = 'JAP';
                                      worked_days++;
                                      week_work++;
                                    }
                                  }
                                })
                              }

                              if (attendance.scheduled == "OFF") {
                                attendance.balance = "OFF";
                                days_off = days_off + 1;
                                if (Number(attendance.worked_time) > 0) {
                                  attendance.balance = (Number(attendance.worked_time)).toFixed(3);
                                  discounted_hours = discounted_hours + Number(attendance.worked_time);
                                }
                              } else if(!activeSuspension && !activeLeave && !activeVacation){
                                if (!isNullOrUndefined(emp[0].children) && !isNullOrUndefined(emp[0].gender)) {
                                  if (Number(emp[0].children) > 0) {
                                    if (emp[0].gender == 'Femenino') {
                                      if (attendance.date == (new Date().getFullYear() + "-05-10")) {
                                        mother_father_day = true;
                                      }
                                    }
                                  }
                                }
                                if (attendance.date != (new Date().getFullYear() + "-01-01") && attendance.date != (new Date().getFullYear() + "-06-28") && attendance.date != (new Date().getFullYear() + "-04-01") && attendance.date != (new Date().getFullYear() + "-04-02") && attendance.date != (new Date().getFullYear() + "-04-03") && attendance.date != (new Date().getFullYear() + "-05-01") && !mother_father_day) {
                                  if (Number(attendance.scheduled) > 0) {
                                    if (Number(attendance.worked_time) == 0) {
                                      attendance.balance = "NS";
                                      ns_count++;
                                      if (!non_show) {
                                        non_show = true;
                                        discounted_days = discounted_days + 1;
                                        sevenths = sevenths + 1;
                                        non_show_sequence = non_show_sequence + 1;
                                        ult_seventh = 1;
                                        disc_on_week = disc_on_week + 2;
                                      } else {
                                        discounted_days = discounted_days + 1;
                                        disc_on_week = disc_on_week + 1;
                                        non_show_sequence = non_show_sequence + 1;
                                      }
                                    } else {
                                      worked_days++;
                                      week_work++;
                                      attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toString();
                                      discounted_hours = discounted_hours + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                    }
                                  } else {
                                    discounted_days = discounted_days + 1;
                                  }
                                } else {
                                  hld = hld + 1;
                                  hldT = hldT + 1;
                                  if (attendance.scheduled != "OFF") {
                                    if (Number(attendance.worked_time) > Number(attendance.scheduled)) {
                                      hld_hours = hld_hours + Number(attendance.scheduled);
                                      discounted_hours = discounted_hours + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                      attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(2);
                                    } else {
                                      if (Number(attendance.worked_time) > 0) {
                                        hld_hours = hld_hours + (Number(attendance.worked_time));
                                        attendance.balance = 'HLD';
                                      } else {
                                        attendance.balance = 'HLD';
                                      }
                                    }
                                  }
                                }
                              }
                              if (new Date(attendance.date).getDay() == 6) {
                                off_on_week = days_off - off_on_week;
                                let disc: boolean = false;

                                if (janp_sequence >= 5) {
                                  disc = true;
                                  discounted_days = discounted_days + (off_on_week - janp_on_off);
                                  disc_on_week = disc_on_week + (off_on_week - janp_on_off);
                                }

                                if (non_show_sequence == 5) {
                                  if (carry_seventh) {
                                    discounted_days = discounted_days + 1;
                                    disc_on_week = disc_on_week + 1;
                                  }
                                  disc = true;
                                  discounted_days = discounted_days + 1
                                  disc_on_week = disc_on_week + 1;

                                }

                                if ((janp_sequence + non_show_sequence) == 5 && !disc) {
                                  discounted_days = discounted_days + 1;
                                  disc_on_week = disc_on_week + 1;
                                }

                                if (disc_on_week >= 7) {
                                  discounted_days = discounted_days - disc_on_week;
                                  discounted_days = discounted_days + 7;
                                  disc_on_week = 7;
                                }

                                disc_on_week = 0;
                                janp_on_off_2 = janp_on_off_2 + janp_on_off;
                                janp_on_off = 0;
                                non_show_sequence = 0;
                                non_show = false;
                                ult_seventh = 0;
                                janp_sequence = 0;
                                hld = 0;
                                week_work = 0;
                              }
                            } else if (valid_trm) {
                              attendance.balance = "TERM";
                              trm_count++;
                            }
                          }
                        })

                        if (!isNullOrUndefined(trm.valid_from) && worked_days == 0 && discounted_days > 0) {
                          if (new Date(trm.valid_from).getTime() > new Date(periods_pr[periods_pr.length - 1].start).getTime()) {
                            sevenths = (((new Date(trm.valid_from).getTime() - new Date(periods_pr[periods_pr.length - 1].start).getTime()) / (1000 * 3600 * 24)) + sevenths - 1 - (ns_count + janp_sequence + hldT));
                          }
                        } else if (isNullOrUndefined(trm.valid_from)) {
                          if (discounted_days + sevenths > att.length) {
                            sevenths = days_off;
                            discounted_days = att.length - days_off + worked_days;
                          }
                        }

                        if ((discounted_days + sevenths) >= 15) {
                          let max_days: number = 0;
                          max_days = ((new Date(periods_pr[periods_pr.length - 1].end).getTime()) - (new Date(periods_pr[periods_pr.length - 1].start).getTime())) / (1000 * 3600 * 24);
                          if (discounted_hours != 0) {
                            discounted_days = 15 - (discounted_days + (max_days - 15));
                          }
                          discounted_days = (15 - max_days) + max_days - worked_days;
                          sevenths = 0;
                        }

                        if ((((new Date(periods_pr[periods_pr.length - 1].end).getTime() - new Date(periods_pr[periods_pr.length - 1].start).getTime()) / (1000 * 3600 * 24)) + 1) <= (discounted_days + sevenths)) {
                          discounted_days = (15 - sevenths);
                        }

                        if ((ns_count + days_off >= ((new Date(periods_pr[periods_pr.length - 1].end).getTime()) - (new Date(periods_pr[periods_pr.length - 1].start).getTime())) / (1000 * 3600 * 24)) && ns_count > 0) {
                          discounted_days = 15;
                          sevenths = 0;
                        }

                        if (att.length == 0) {
                          discounted_days = 15;
                        }

                        this.daysOff = days_off;
                        this.attendances.forEach(at => {
                          if (at.scheduled != 'OFF' && new Date((periods_pr[periods_pr.length - 1].start)).getTime() <= new Date(at.date).getTime()) {
                            this.roster = Number((this.roster + Number(at.scheduled)).toFixed(2));
                            this.attended = Number((this.attended + Number(at.worked_time)).toFixed(2));
                          }
                        })
                        this.diff = Number(discounted_hours.toFixed(2));
                        this.absence_fixed = discounted_days.toFixed(2);
                        this.seventh = sevenths;
                      }
                      this.attendances.forEach(attend => {
                        let actualDate: Date = new Date(attend.date);
                        this.vacations.forEach(vac => {
                          if ((vac.took_date == attend.date && (vac.status != "DISMISSED" && vac.status != "COMPLETED") && Number(attend.worked_time) > 0)) {
                            attend.status = 'overlap';
                          }
                        })
                    
                        this.leaves.forEach(leave => {
                          let startDate: Date = new Date(leave.start);
                          let endDate: Date = new Date(leave.end);
                          if (Number(attend.worked_time) > 0 && ((startDate.getTime() <= actualDate.getTime() && endDate.getTime() >= actualDate.getTime()) && (leave.status != "DISMISSED" && leave.status != "COMPLETED"))){
                            attend.status = 'overlap';
                          }
                        })
                    
                        this.dps.forEach(dp => {
                          if(Number(attend.worked_time) > 0 && ((dp.day_1 == attend.date || dp.day_2 == attend.date || dp.day_3 == attend.date || dp.day_4 == attend.date) && (dp.status != "DISMISSED" && dp.status != "COMPLETED"))){
                            attend.status = 'overlap';
                          }
                        })
                      });
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

  showReg(att: attendences) {
    let actualDate: Date = new Date(att.date);

    this.vacations.forEach(vac => {
      if (vac.took_date == att.date && (vac.status != "DISMISSED" && vac.status != "COMPLETED")) {
        vac.action = 'overlap';
      }
    })

    this.leaves.forEach(leave => {
      let startDate: Date = new Date(leave.start);
      let endDate: Date = new Date(leave.end);
      if ((startDate.getTime() <= actualDate.getTime() && endDate.getTime() >= actualDate.getTime()) && (leave.status != "DISMISSED" && leave.status != "COMPLETED")) {
        leave.approved_by = 'overlap';
      }
    })

    this.dps.forEach(dp => {
      if ((dp.day_1 == att.date || dp.day_2 == att.date || dp.day_3 == att.date || dp.day_4 == att.date) && (dp.status != "DISMISSED" && dp.status != "COMPLETED")) {
        dp.requested_by = 'overlap';
      }
    })
    this.showRegs = true;
  }

  setState_vac(vac: vacations) {
    if (vac.status == 'DISMISSED') {
      this.apiService.updateVacations(vac).subscribe((str: string) => {

      });
    }
  }

  setState_suspension(suspension: disciplinary_processes) {
    if (suspension.status == 'DISMISSED') {
      this.apiService.updateSuspensions(suspension).subscribe((str: string) => {

      });
    }
  }

  setState_leave(leave: leaves) {
    if (leave.status == 'DISMISSED') {
      leave.notes = leave.notes + ' | DISMISSED By attendance overlap';
      this.apiService.updateLeaves(leave).subscribe((str: string) => {

      });
    }
  }

  editRoster(att: attendences) {
    this.cancelView();
    this.edit = true;
    this.attendances.forEach(element => {
      if ((element === att)) {
        element.state = 'edit';
      } else {
        element.state = null;
      }
    });
  }

  SaveRoaster(att: attendences) {
    this.edit = false;
    // proceso para grabar los cambios.
    this.apiService.updateAttendances(att).subscribe((_str: string) => {
      this.attendances.forEach(element => {
        if ((element === att)) {
          element.state = null;
        }
      });
      window.alert('Attendences updated.');
    })
    this.start();
  }

  isPeriod(dt: string) {
    return (new Date(this.todayDate).getTime() <= new Date(dt).getTime());
  }
}
