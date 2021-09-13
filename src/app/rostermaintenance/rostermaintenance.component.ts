import { Component, OnInit, RootRenderer } from '@angular/core';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { accounts, attendences, clients, periods, rosters, roster_times, roster_types, roster_views, roster_weeks } from '../process_templates';
import * as XLSX from 'xlsx';
import { AuthServiceService } from '../auth-service.service';

@Component({
  selector: 'app-rostermaintenance',
  templateUrl: './rostermaintenance.component.html',
  styleUrls: ['./rostermaintenance.component.css']
})
export class RostermaintenanceComponent implements OnInit {

  constructor(public apiServices: ApiService, public authService: AuthServiceService) { }

  selectedClient: string = null;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;
  clients: clients[] = [];
  isSearching: boolean = false;
  period_days: string[] = [];
  roster_tags: string[] = [];
  selected_types: roster_types[] = [];
  selectedType: roster_types = new roster_types;
  editingRosterTemplate: boolean = false;
  schedule_types: roster_times[] = [];
  selectedSchedule: roster_times = new roster_times;
  useds: roster_types[] = [];
  editingTimes: boolean = false;
  selectedDay: number = 0;
  updatePending: boolean[] = [false, false, false, false, false, false, false];
  rosters: rosters[] = [];
  selectedRosters: roster_views[] = [];
  activePeriod: periods = new periods;
  roster_view: number = 0;
  rosters_show: roster_views[] = [];
  roster_modifications: string[] = [];
  working: boolean = false;
  week_value: string = '1';
  completed: boolean = false;
  searchString: string;
  searching: boolean = false;
  employeeRoster: rosters[] = [];
  selectedEmployeeRoster: rosters = new rosters;
  createAttendances: boolean = false;
  isAttendance: boolean = false;
  file: any;
  arrayBuffer: any;
  filelist: any;

  ngOnInit() {
    this.roster_modifications = [];
    this.rosters_show = [];
    this.selectedRosters = [];
    this.rosters = [];
    this.updatePending = [false, false, false, false, false, false, false];
    this.useds = [];
    this.schedule_types = [];
    this.selected_types = [];
    this.period_days = [];
    this.roster_tags = [];
    this.clients = [];
    this.accounts = [];
    this.searching = false;
    this.isAttendance = false;

    this.apiServices.getRoster_tags().subscribe((str: string[]) => {
      this.roster_tags = str;
      this.getRosters_templates(this.roster_tags[0]);
    })
    this.apiServices.getPeriods().subscribe((periods: periods[]) => {
      this.activePeriod = periods[periods.length - 1];
      this.apiServices.getRosters("'" + this.activePeriod.idperiods + "'").subscribe((rsts: rosters[]) => {
        rsts.forEach(rs => {
          let cnt: number = 0;
          let temp = rsts.filter(f => f.id_employee == rs.id_employee);
          temp.forEach(tmp => {
            cnt = cnt + Number(tmp.week_value);
          })
          if ((cnt > this.getMaxWeek() || cnt < this.getMaxWeek()) && !isNullOrUndefined(rs.mon_end)) {
            rs.status = "3";
          } else if (isNullOrUndefined(rs.mon_end)) {
            rs.status = "0";
          } else if (cnt == this.getMaxWeek()) {
            rs.status = "1";
          }
        })
        this.rosters = rsts;
        let dt: number = new Date(periods[periods.length - 1].start).getTime();
        while (dt <= new Date(periods[periods.length - 1].end).getTime()) {
          dt = dt + (1000 * 3600 * 24);
          this.period_days.push(new Date(dt).getDate().toFixed(0));
        }
        this.rosters.forEach((roster_toShow: rosters) => {
          let activeShow: boolean = false;
          this.rosters_show.forEach((showed: roster_views) => {
            if (showed.nearsol_id == roster_toShow.nearsol_id) {
              activeShow = true;
            }
          })
          if (!activeShow) {
            let toShow: roster_views = new roster_views;
            toShow.id_account = roster_toShow.id_account;
            toShow.nearsol_id = roster_toShow.nearsol_id;
            toShow.client_id = roster_toShow.client_id;
            toShow.name = roster_toShow.name;
            toShow.id_employee = roster_toShow.id_employee;
            toShow.status = roster_toShow.status;
            toShow.idrosters = roster_toShow.idrosters;
            if (this.dayExist(0)) {
              toShow.day_1 = this.getRosterDay(0, roster_toShow).split("|")[0];
              toShow.fixed_1 = this.getRosterDay(0, roster_toShow).split("|")[1];
            }
            if (this.dayExist(1)) {
              toShow.day_2 = this.getRosterDay(1, roster_toShow).split("|")[0];
              toShow.fixed_2 = this.getRosterDay(1, roster_toShow).split("|")[1];
            }
            if (this.dayExist(2)) {
              toShow.day_3 = this.getRosterDay(2, roster_toShow).split("|")[0];
              toShow.fixed_3 = this.getRosterDay(2, roster_toShow).split("|")[1];
            }
            if (this.dayExist(3)) {
              toShow.day_4 = this.getRosterDay(3, roster_toShow).split("|")[0];
              toShow.fixed_4 = this.getRosterDay(3, roster_toShow).split("|")[1];
            }
            if (this.dayExist(4)) {
              toShow.day_5 = this.getRosterDay(4, roster_toShow).split("|")[0];
              toShow.fixed_5 = this.getRosterDay(4, roster_toShow).split("|")[1];
            }
            if (this.dayExist(5)) {
              toShow.day_6 = this.getRosterDay(5, roster_toShow).split("|")[0];
              toShow.fixed_6 = this.getRosterDay(5, roster_toShow).split("|")[1];
            }
            if (this.dayExist(6)) {
              toShow.day_7 = this.getRosterDay(6, roster_toShow).split("|")[0];
              toShow.fixed_7 = this.getRosterDay(6, roster_toShow).split("|")[1];
            }
            if (this.dayExist(7)) {
              toShow.day_8 = this.getRosterDay(7, roster_toShow).split("|")[0];
              toShow.fixed_8 = this.getRosterDay(7, roster_toShow).split("|")[1];
            }
            if (this.dayExist(8)) {
              toShow.day_9 = this.getRosterDay(8, roster_toShow).split("|")[0];
              toShow.fixed_9 = this.getRosterDay(8, roster_toShow).split("|")[1];
            }
            if (this.dayExist(9)) {
              toShow.day_10 = this.getRosterDay(9, roster_toShow).split("|")[0];
              toShow.fixed_10 = this.getRosterDay(9, roster_toShow).split("|")[1];
            }
            if (this.dayExist(10)) {
              toShow.day_11 = this.getRosterDay(10, roster_toShow).split("|")[0];
              toShow.fixed_11 = this.getRosterDay(10, roster_toShow).split("|")[1];
            }
            if (this.dayExist(11)) {
              toShow.day_12 = this.getRosterDay(11, roster_toShow).split("|")[0];
              toShow.fixed_12 = this.getRosterDay(11, roster_toShow).split("|")[1];
            }
            if (this.dayExist(12)) {
              toShow.day_13 = this.getRosterDay(12, roster_toShow).split("|")[0];
              toShow.fixed_13 = this.getRosterDay(12, roster_toShow).split("|")[1];
            }
            if (this.dayExist(13)) {
              toShow.day_14 = this.getRosterDay(13, roster_toShow).split("|")[0];
              toShow.fixed_14 = this.getRosterDay(13, roster_toShow).split("|")[1];
            }
            if (this.dayExist(14)) {
              toShow.day_15 = this.getRosterDay(14, roster_toShow).split("|")[0];
              toShow.fixed_15 = this.getRosterDay(14, roster_toShow).split("|")[1];
            }
            if (this.dayExist(15)) {
              toShow.day_16 = this.getRosterDay(15, roster_toShow).split("|")[0];
              toShow.fixed_16 = this.getRosterDay(15, roster_toShow).split("|")[1];
            }
            this.rosters_show.push(toShow);
          }
        })
      })
    })
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
      this.reset_value();
    })
  }

  sortRosters() {
    if (this.searching) {
      return this.rosters_show.sort((a, b) => Number(b.status) - Number(a.status)).filter(f => f.id_account === this.selectedAccount.idaccounts).filter(t => t.name.includes(this.searchString.toUpperCase()) || t.nearsol_id.includes(this.searchString.toUpperCase()) || t.client_id.includes(this.searchString.toUpperCase()));
    } else if (!this.isAttendance) {
      return this.rosters_show.sort((a, b) => Number(b.status) - Number(a.status)).filter(f => f.id_account === this.selectedAccount.idaccounts);
    } else if (this.isAttendance) {
      return this.rosters_show.sort((a, b) => Number(b.status) - Number(a.status)).filter(f => f.id_account === this.selectedAccount.idaccounts).sort((ata, atb) => (Number(ata.att_status_1) + Number(ata.att_status_2) + Number(ata.att_status_3) + Number(ata.att_status_4) + Number(ata.att_status_5) + Number(ata.att_status_6) + Number(ata.att_status_7) + Number(ata.att_status_8) + Number(ata.att_status_9) + Number(ata.att_status_10) + Number(ata.att_status_11) + Number(ata.att_status_12) + Number(ata.att_status_13) + Number(ata.att_status_14) + Number(ata.att_status_15) + Number(ata.att_status_16)) - (Number(atb.att_status_1) + Number(atb.att_status_2) + Number(atb.att_status_3) + Number(atb.att_status_4) + Number(atb.att_status_5) + Number(atb.att_status_6) + Number(atb.att_status_7) + Number(atb.att_status_8) + Number(atb.att_status_9) + Number(atb.att_status_10) + Number(atb.att_status_11) + Number(atb.att_status_12) + Number(atb.att_status_13) + Number(atb.att_status_14) + Number(atb.att_status_15) + Number(atb.att_status_16)));
    }
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.selectedAccount = this.accounts[0];
    })
  }


  setAccount(acc: accounts) {
    this.selectedRosters = [];
    this.isSearching = false;
    this.selectedAccount = acc;
  }

  getRosters_templates(str) {
    this.apiServices.getRosterTemplates(str).subscribe((rosts: roster_types[]) => {
      this.selected_types = rosts;
      this.selectedType = rosts[0];
    })
  }

  setRosterType(str) {
    this.selected_types.forEach(tps => {
      if (tps.idroster_types == str) {
        this.selectedType = tps;
      }
    })
  }

  getTemplate(idTemplate: roster_types) {
    if (isNullOrUndefined(this.selected_types.find(sel => sel.idroster_types == idTemplate.idroster_types))) {
      let r: roster_types = new roster_types;
      r.mon_start = "00:00";
      r.mon_end = "00:00";
      r.tue_start = "00:00";
      r.tue_end = "00:00";
      r.wed_start = "00:00";
      r.wed_end = "00:00";
      r.thur_start = "00:00";
      r.thur_end = "00:00";
      r.fri_start = "00:00";
      r.fri_end = "00:00";
      r.sat_start = "00:00";
      r.sat_end = "00:00";
      r.sun_start = "00:00";
      r.sun_end = "00:00";
      return r;
    } else {
      return this.selected_types.find(sel => sel.idroster_types == idTemplate.idroster_types);
    }
  }

  change(any: string, nb: number) {
    this.selectedDay = Number(nb);
    this.editingRosterTemplate = true;
    this.apiServices.getRosterTimes().subscribe((rst: roster_times[]) => {
      this.schedule_types = rst;
      if (!isNullOrUndefined(any)) {
        this.selectedSchedule = rst.find(v => v.idroster_times == any);
      } else {
        this.selectedSchedule = rst[0];
      }
      this.apiServices.getRosterTemplates(" id_time_mon = " + this.selectedSchedule.idroster_times + " OR id_time_tue = " + this.selectedSchedule.idroster_times + " OR id_time_wed = "
        + this.selectedSchedule.idroster_times + " OR id_time_thur = " + this.selectedSchedule.idroster_times + " OR id_time_fri = " + this.selectedSchedule.idroster_times + " OR id_time_sat = " + this.selectedSchedule.idroster_times
        + " OR id_time_sun = " + this.selectedSchedule.idroster_times).subscribe((ss: roster_types[]) => {
          this.useds = ss;
        })
    })
  }

  needUpdate(nb: number) {
    return this.updatePending[Number(nb) - 1];
  }

  setSchedule_type(idRosterTime) {
    this.selectedSchedule = this.schedule_types.find(val => val.idroster_times == idRosterTime);
    this.apiServices.getRosterTemplates(" id_time_mon = " + this.selectedSchedule.idroster_times + " OR id_time_tue = " + this.selectedSchedule.idroster_times + " OR id_time_wed = "
      + this.selectedSchedule.idroster_times + " OR id_time_thur = " + this.selectedSchedule.idroster_times + " OR id_time_fri = " + this.selectedSchedule.idroster_times + " OR id_time_sat = " + this.selectedSchedule.idroster_times
      + " OR id_time_sun = " + this.selectedSchedule.idroster_times).subscribe((ss: roster_types[]) => {
        this.useds = ss;
      })
  }

  addTime() {
    this.editingTimes = true
    let sch: roster_times = new roster_times;
    this.schedule_types.push(sch);
    this.selectedSchedule = this.schedule_types[this.schedule_types.length - 1];
  }

  saveTime() {
    this.apiServices.insertTimes(this.selectedSchedule).subscribe((str: string) => {
      this.editingTimes = false;
      this.editingRosterTemplate = false;
    })
  }

  clearSet() {
    this.editingRosterTemplate = false;
    this.editingTimes = false;
  }

  setTime() {
    this.updatePending[Number(this.selectedDay) - 1] = true;
    switch (this.selectedDay) {
      case 1:
        this.selectedType.id_time_mon = this.selectedSchedule.idroster_times;
        this.selectedType.mon_start = this.selectedSchedule.start;
        this.selectedType.mon_end = this.selectedSchedule.end;
        break;
      case 2:
        this.selectedType.id_time_tue = this.selectedSchedule.idroster_times;
        this.selectedType.tue_start = this.selectedSchedule.start;
        this.selectedType.tue_end = this.selectedSchedule.end;
        break;
      case 3:
        this.selectedType.id_time_wed = this.selectedSchedule.idroster_times;
        this.selectedType.wed_start = this.selectedSchedule.start;
        this.selectedType.wed_end = this.selectedSchedule.end;
        break;
      case 4:
        this.selectedType.id_time_thur = this.selectedSchedule.idroster_times;
        this.selectedType.thur_start = this.selectedSchedule.start;
        this.selectedType.thur_end = this.selectedSchedule.end;
        break;
      case 5:
        this.selectedType.id_time_fri = this.selectedSchedule.idroster_times;
        this.selectedType.fri_start = this.selectedSchedule.start;
        this.selectedType.fri_end = this.selectedSchedule.end;
        break;
      case 6:
        this.selectedType.id_time_sat = this.selectedSchedule.idroster_times;
        this.selectedType.sat_start = this.selectedSchedule.start;
        this.selectedType.sat_end = this.selectedSchedule.end;
        break;
      case 7:
        this.selectedType.id_time_sun = this.selectedSchedule.idroster_times;
        this.selectedType.sun_start = this.selectedSchedule.start;
        this.selectedType.sun_end = this.selectedSchedule.end;
        break;
    }
    this.clearSet();
  }

  saveRooster() {
    this.apiServices.updateRosterType(this.selectedType).subscribe((str: roster_types[]) => {
      this.selected_types = str;
      this.selectedType = str[str.length - 1];
    })
  }

  dayExist(nmb) {
    return !isNullOrUndefined(this.period_days[nmb]);
  }

  getRosterDay(nmb: number, rt: rosters) {
    let dt: Date = new Date(new Date().getFullYear(), new Date().getMonth(), Number(this.period_days[nmb]));
    let dt_1: Date = new Date(this.activePeriod.start);
    let rtn: string = '';
    this.roster_view++;

    if (Number(rt.count) >= 1) {
      let mixed_schedules: rosters[] = this.rosters.filter(f => f.nearsol_id == rt.nearsol_id);
      let week: number = 0;

      while (dt_1.getTime() <= dt.getTime()) {
        dt_1 = new Date(dt_1.getTime() + (1000 * 3600 * 24));
        if (dt_1.getDay() == 1) {
          week++;
        }
      }
      let ac_week: number = 0;
      let counter: number = 0;
      let found: boolean = false;
      mixed_schedules.forEach((mixed: rosters) => {
        ac_week = ac_week + Number(mixed.week_value);
        if (ac_week >= (week + 1) && !found) {
          week = counter;
          found = true;
        }
        counter++;
      })
      if (mixed_schedules.length > (week)) {
        switch (dt.getDay()) {
          case 1:
            rtn = ((mixed_schedules[week].mon_start + " - " + (mixed_schedules[week].mon_end)) + "|" + mixed_schedules[week].mon_fixed);
            break;
          case 2:
            rtn = ((mixed_schedules[week].tue_start + " - " + mixed_schedules[week].tue_end) + "|" + mixed_schedules[week].tue_fixed);
            break;
          case 3:
            rtn = (mixed_schedules[week].wed_start + " - " + mixed_schedules[week].wed_end) + "|" + mixed_schedules[week].wed_fixed;
            break;
          case 4:
            rtn = ((mixed_schedules[week].thur_start + " - " + mixed_schedules[week].thur_end) + "|" + mixed_schedules[week].thur_fixed);
            break;
          case 5:
            rtn = ((mixed_schedules[week].fri_start + " - " + mixed_schedules[week].fri_end) + "|" + mixed_schedules[week].fri_fixed);
            break;
          case 6:
            rtn = (mixed_schedules[week].sat_start + " - " + mixed_schedules[week].sat_end) + "|" + mixed_schedules[week].sat_fixed;
            break;
          case 0:
            rtn = (mixed_schedules[week].sun_start + " - " + mixed_schedules[week].sun_end) + "|" + mixed_schedules[week].sun_fixed;
            break;
        }
      } else {
        if (week <= this.getMaxWeek()) {
          if (mixed_schedules[0].status == '1') {
            this.ngOnInit();
          }
          rtn = 'null - null|null';
        }
      }
    } else {
      switch (dt.getDay()) {
        case 1:
          rtn = (rt.mon_start + " - " + rt.mon_end) + "|" + rt.mon_fixed;
          break;
        case 2:
          rtn = (rt.tue_start + " - " + rt.tue_end) + "|" + rt.tue_fixed;
          break;
        case 3:
          rtn = (rt.wed_start + " - " + rt.wed_end) + "|" + rt.wed_fixed;
          break;
        case 4:
          rtn = (rt.thur_start + " - " + rt.thur_end) + "|" + rt.thur_fixed;
          break;
        case 5:
          rtn = (rt.fri_start + " - " + rt.fri_end) + "|" + rt.fri_fixed;
          break;
        case 6:
          rtn = (rt.sat_start + " - " + rt.sat_end) + "|" + rt.sat_fixed;
          break;
        case 0:
          rtn = (rt.sun_start + " - " + rt.sun_end) + "|" + rt.sun_fixed;
          break;
      }
    }
    if ((this.rosters.length * this.period_days.length) == this.roster_view) {
      this.rosters.forEach((mixed_roster: rosters) => {
        if (Number(mixed_roster.count) > 1) {
          for (let f: number = 1; f < Number(mixed_roster.count); f++) {
            this.rosters.splice(this.rosters.indexOf(mixed_roster), 1);
          }
        }
      })
    }
    return rtn;
  }

  addSelection(rost: roster_views) {
    let exist: boolean = false;
    this.selectedRosters.forEach(element => {
      if (element.nearsol_id == rost.nearsol_id) {
        exist = true;
        this.selectedRosters.splice(this.selectedRosters.indexOf(element), 1);
      }
    })
    if (!exist) {
      this.selectedRosters.push(rost);
    }
  }

  isSelected(rost: roster_views) {
    let exist: boolean = false;
    this.selectedRosters.forEach(element => {
      if (element.nearsol_id == rost.nearsol_id) {
        exist = true;
      }
    })
    return exist;
  }

  getMaxWeek() {
    let week: number = 1;
    let dt: Date = new Date(this.activePeriod.start);
    while (dt.getTime() <= new Date(this.activePeriod.end).getTime()) {
      dt = new Date(dt.getTime() + (1000 * 3600 * 24));
      if (dt.getDay() == 1) {
        week++;
      }
    }
    return week;
  }

  maxSelection() {
    let max_size = 2;
    if (this.selectedRosters.length >= 2) {
      max_size = 5;
    }
    return max_size;
  }


  saveRosters() {
    this.working = true;
    let count: number = 0;
    let rosters_result: rosters[] = [];
    this.selectedRosters.forEach(selection => {
      let roster: rosters = new rosters;
      roster.id_employee = selection.id_employee;
      roster.id_schedule = this.selectedType.idroster_types;
      roster.week_value = this.week_value;
      roster.id_period = this.activePeriod.idperiods;
      this.apiServices.updateRosters(roster).subscribe((str: string) => {
        roster.status = str;
        count++;
        rosters_result.push(roster);
        if (count >= this.selectedRosters.length) {
          this.working = false;
          this.completed = true;
          let strs: string = null;
          rosters_result.forEach(rst => {
            if (rst.status != '1') {
              strs = strs + rst.status;
            }
          })
          if (isNullOrUndefined(strs)) {
            strs = "Success";
          }
        }
      })
    })
  }

  reload() {
    this.ngOnInit();
  }

  reset_value() {
    this.employeeRoster = [];
    this.selectedEmployeeRoster = new rosters;
    this.completed = false;
    this.working = false;
  }

  searchRoster() {
    this.searching = !this.searching;
  }

  setEmployee(rst: string) {
    this.apiServices.getRosterFilter({ id_employee: rst, id_period: this.activePeriod.idperiods }).subscribe((roster: rosters[]) => {
      this.employeeRoster = roster;
    })
  }

  setEmployeeRoster(str) {
    let stss: string = this.employeeRoster.find(f => f.idrosters == str).id_employee + " AND idrosters = " + str;
    this.apiServices.getRosterFilter({ id_employee: stss, id_period: this.activePeriod.idperiods }).subscribe((res: rosters[]) => {
      this.selectedEmployeeRoster = res[0];
    })
  }

  changeRoster(type: number) {
    let old_id_1: string;
    let old_id_2: string;
    old_id_1 = this.employeeRoster.find(f => f.idrosters == this.selectedEmployeeRoster.idrosters).idrosters;
    old_id_2 = this.employeeRoster[this.employeeRoster.indexOf(this.employeeRoster.find(f => f.idrosters == this.selectedEmployeeRoster.idrosters)) - (type)].idrosters;
    this.apiServices.updateRosterOrder({ id_old_1: old_id_1, id_old_2: old_id_2 }).subscribe((str: string) => {
      if (str != '1') {
        window.alert("ERROR \n" + str);
      } else {
        this.setEmployeeRoster(this.selectedEmployeeRoster.idrosters);
      }
    })
  }

  setAttendances() {
    this.isAttendance = true;
    this.createAttendances = !this.createAttendances;
    this.working = true;
    let count: number = 0;
    this.apiServices.getAttendences({ id: 'ALL', date: "'" + this.activePeriod.start + "' AND '" + this.activePeriod.end + "'" }).subscribe((att: attendences[]) => {
      att.forEach(attendance => {
        if (!isNullOrUndefined(this.rosters_show.find(f => f.id_employee == attendance.id_employee))) {
          switch (attendance.date.split("-")[2]) {
            case this.period_days[0].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_1 = '0';
              break;
            case this.period_days[1].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_2 = '0';
              break;
            case this.period_days[2].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_3 = '0';
              break;
            case this.period_days[3].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_4 = '0';
              break;
            case this.period_days[4].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_5 = '0';
              break;
            case this.period_days[5].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_6 = '0';
              break;
            case this.period_days[6].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_7 = '0';
              break;
            case this.period_days[7].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_8 = '0';
              break;
            case this.period_days[8].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_9 = '0';
              break;
            case this.period_days[9].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_10 = '0';
              break;
            case this.period_days[10].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_11 = '0';
              break;
            case this.period_days[11].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_12 = '0';
              break;
            case this.period_days[12].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_13 = '0';
              break;
            case this.period_days[13].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_14 = '0';
              break;
            case this.period_days[14].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_15 = '0';
              break;
            case this.period_days[15].padStart(2, "0"):
              this.rosters_show.find(f => f.id_employee == attendance.id_employee).att_status_16 = '0';
              break;
          }
        }
        count++;
        if (count >= att.length) {
          this.working = false;
        }
      })
    })
  }

  isPending(day: string, roster: roster_views) {
    switch (day) {
      case '1':
        if (roster.fixed_1 == 'null') {
          return '2';
        } else {
          return roster.att_status_1;
        }
        break;
      case '2':
        if (roster.fixed_2 == 'null') {
          return '2';
        } else {
          return roster.att_status_2;
        }
        break;
      case '3':
        if (roster.fixed_3 == 'null') {
          return '2';
        } else {
          return roster.att_status_3;
        }
        break;
      case '4':
        if (roster.fixed_4 == 'null') {
          return '2';
        } else {
          return roster.att_status_4;
        }
        break;
      case '5':
        if (roster.fixed_5 == 'null') {
          return '2';
        } else {
          return roster.att_status_5;
        }
        break;
      case '6':
        if (roster.fixed_6 == 'null') {
          return '2';
        } else {
          return roster.att_status_6;
        }
        break;
      case '7':
        if (roster.fixed_7 == 'null') {
          return '2';
        } else {
          return roster.att_status_7;
        }
        break;
      case '8':
        if (roster.fixed_8 == 'null') {
          return '2';
        } else {
          return roster.att_status_8;
        }
        break;
      case '9':
        if (roster.fixed_9 == 'null') {
          return '2';
        } else {
          return roster.att_status_9;
        }
        break;
      case '10':
        if (roster.fixed_10 == 'null') {
          return '2';
        } else {
          return roster.att_status_10;
        }
        break;
      case '11':
        if (roster.fixed_11 == 'null') {
          return '2';
        } else {
          return roster.att_status_11;
        }
        break;
      case '12':
        if (roster.fixed_12 == 'null') {
          return '2';
        } else {
          return roster.att_status_12;
        }
        break;
      case '13':
        if (roster.fixed_13 == 'null') {
          return '2';
        } else {
          return roster.att_status_13;
        }
        break;
      case '14':
        if (roster.fixed_14 == 'null') {
          return '2';
        } else {
          return roster.att_status_14;
        }
        break;
      case '15':
        if (roster.fixed_15 == 'null') {
          return '2';
        } else {
          return roster.att_status_15;
        }
        break;
      case '16':
        if (roster.fixed_16 == 'null') {
          return '2';
        } else {
          return roster.att_status_16;
        }
        break;
    }
  }

  insertAttendances() {
    let toPush: attendences[] = [];
    let dt_toPush: string = '';
    this.sortRosters().forEach(insRost => {
      for (let i = 0; i < this.period_days.length; i++) {
        let add: boolean = false;
        let sch: string = '';
        dt_toPush = this.activePeriod.start.split('-')[0] + "-" + this.activePeriod.start.split('-')[1] + "-" + this.period_days[i].padStart(2, '0');
        switch (i + 1) {
          case 1:
            if (insRost.att_status_1 == '1' && insRost.fixed_1 != 'null') {
              sch = insRost.fixed_1;
              add = true;
            }
            break;
          case 2:
            if (insRost.att_status_2 == '1' && insRost.fixed_2 != 'null') {
              add = true;
              sch = insRost.fixed_2;
            }
            break;
          case 3:
            if (insRost.att_status_3 == '1' && insRost.fixed_3 != 'null') {
              add = true;
              sch = insRost.fixed_3;
            }
            break;
          case 4:
            if (insRost.att_status_4 == '1' && insRost.fixed_4 != 'null') {
              add = true;
              sch = insRost.fixed_4;
            }
            break;
          case 5:
            if (insRost.att_status_5 == '1' && insRost.fixed_5 != 'null') {
              add = true;
              sch = insRost.fixed_5;
            }
            break;
          case 6:
            if (insRost.att_status_6 == '1' && insRost.fixed_6 != 'null') {
              add = true;
              sch = insRost.fixed_6;
            }
            break;
          case 7:
            if (insRost.att_status_7 == '1' && insRost.fixed_7 != 'null') {
              add = true;
              sch = insRost.fixed_7;
            }
            break;
          case 8:
            if (insRost.att_status_8 == '1' && insRost.fixed_8 != 'null') {
              add = true;
              sch = insRost.fixed_8;
            }
            break;
          case 9:
            if (insRost.att_status_9 == '1' && insRost.fixed_9 != 'null') {
              add = true;
              sch = insRost.fixed_9;
            }
            break;
          case 10:
            if (insRost.att_status_10 == '1' && insRost.fixed_10 != 'null') {
              add = true;
              sch = insRost.fixed_10;
            }
            break;
          case 11:
            if (insRost.att_status_11 == '1' && insRost.fixed_11 != 'null') {
              add = true;
              sch = insRost.fixed_11;
            }
            break;
          case 12:
            if (insRost.att_status_12 == '1' && insRost.fixed_12 != 'null') {
              add = true;
              sch = insRost.fixed_12;
            }
            break;
          case 13:
            if (insRost.att_status_13 == '1' && insRost.fixed_13 != 'null') {
              add = true;
              sch = insRost.fixed_13;
            }
            break;
          case 14:
            if (insRost.att_status_14 == '1' && insRost.fixed_14 != 'null') {
              add = true;
              sch = insRost.fixed_14;
            }
            break;
          case 15:
            if (insRost.att_status_15 == '1' && insRost.fixed_15 != 'null') {
              add = true;
              sch = insRost.fixed_15;
            }
            break;
          case 16:
            if (insRost.att_status_16 == '1' && insRost.fixed_16 != 'null') {
              add = true;
              sch = insRost.fixed_16;
            }
            break;
        }
        if (add) {
          let att: attendences = new attendences;
          att.id_employee = insRost.id_employee;
          att.scheduled = sch;
          att.worked_time = '0';
          att.date = dt_toPush;
          att.day_off1 = "CORRECT";
          toPush.push(att);
        }
      }
    })
    if (toPush.length > 0) {
      this.apiServices.insertAttendences(toPush).subscribe((att: attendences[]) => {
        window.alert("Successfuly created " + toPush.length + " attendances for " + this.selectedAccount.name + "'s Roster");
      })
    } else {
      window.alert("No records available to be inserted");
    }
    this.isAttendance = false;
    this.createAttendances = false;
    this.ngOnInit();
  }

  addFile(event) {
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let num: number = 0;
    let push: roster_weeks[] = [];
    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
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
        this.apiServices.getSearchEmployees({ filter: 'nearsol_id', value: element['NEARSOL ID'], dp: this.authService.getAuthusr().department, rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
          num++;
          if (emp.length > 0) {
            let toInsert: roster_weeks = new roster_weeks;
            toInsert.id_employee = emp.sort((a, b) => Number(a.active) - Number(b.active))[0].idemployees;
            toInsert.id_period = this.activePeriod.idperiods
            for (let i = 0; i < this.period_days.length; i++) {
              switch (new Date(this.activePeriod.start.split('-')[0] + "-" + this.activePeriod.start.split('-')[1] + "-" + this.period_days[i + 1]).getDay()) {
                case 0:
                  toInsert.sun_start = element[(i + 1).toString()].split('-')[0];
                  toInsert.sun_end = element[(i + 1).toString()].split('-')[1];
                  toInsert.sun_fixed = element['Fixed ' + (i + 1).toString()];
                  break;
                case 1:
                  toInsert.mon_start = element[(i + 1).toString()].split('-')[0];
                  toInsert.mon_end = element[(i + 1).toString()].split('-')[1];
                  toInsert.mon_fixed = element['Fixed ' + (i + 1).toString()];
                  break;
                case 2:
                  toInsert.tue_start = element[(i + 1).toString()].split('-')[0];
                  toInsert.tue_end = element[(i + 1).toString()].split('-')[1];
                  toInsert.tue_fixed = element['Fixed ' + (i + 1).toString()];
                  break;
                case 3:
                  toInsert.wed_start = element[(i + 1).toString()].split('-')[0];
                  toInsert.wed_end = element[(i + 1).toString()].split('-')[1];
                  toInsert.wed_fixed = element['Fixed ' + (i + 1).toString()];
                  break;
                case 4:
                  toInsert.thur_start = element[(i + 1).toString()].split('-')[0];
                  toInsert.thur_end = element[(i + 1).toString()].split('-')[1];
                  toInsert.thur_fixed = element['Fixed ' + (i + 1).toString()];
                  break;
                case 5:
                  toInsert.fri_start = element[(i + 1).toString()].split('-')[0];
                  toInsert.fri_end = element[(i + 1).toString()].split('-')[1];
                  toInsert.fri_fixed = element['Fixed ' + (i + 1).toString()];
                  break;
                case 6:
                  toInsert.sat_start = element[(i + 1).toString()].split('-')[0];
                  toInsert.sat_end = element[(i + 1).toString()].split('-')[1];
                  toInsert.sat_fixed = element['Fixed ' + (i + 1).toString()];
                  break;
              }
              if (new Date(this.activePeriod.start.split('-')[0] + "-" + this.activePeriod.start.split('-')[1] + "-" + this.period_days[i + 1]).getDay() == 0) {
                push.push(toInsert);
                toInsert = new roster_weeks;
                toInsert.id_employee = emp.sort((a, b) => Number(a.active) - Number(b.active))[0].idemployees;
                toInsert.id_period = this.activePeriod.idperiods
              }else if(i == this.period_days.length - 1){
                push.push(toInsert);
              }
            }
          }
          if(num >= sheetToJson.length){
            this.apiServices.insertImportedRosters(push).subscribe((str:string)=>{
              if(Number(str) > 0){
                window.alert("Roster Inserted: " + str);
              }else{
                window.alert("Please contact your administrator\n" + str);
              }
            })
          }
        })
      })
    }
  }

  deleteRoster(){
    this.apiServices.deleteRoster(this.selectedEmployeeRoster).subscribe((str:string)=>{
      this.employeeRoster.splice(this.employeeRoster.indexOf(this.selectedEmployeeRoster), 1);
      if(str == '1'){
        window.alert("Record successfully deleted");
      }else{
        window.alert("Please contact your administrator\n" + str);
      }
    })
  }
}
