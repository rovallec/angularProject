import { Component, OnInit, RootRenderer } from '@angular/core';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { accounts, attendences, clients, periods, rosters, roster_times, roster_types, roster_views } from '../process_templates';

@Component({
  selector: 'app-rostermaintenance',
  templateUrl: './rostermaintenance.component.html',
  styleUrls: ['./rostermaintenance.component.css']
})
export class RostermaintenanceComponent implements OnInit {

  constructor(public apiServices: ApiService) { }

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
  roster_modifications:string[] = [];
  working:boolean = false;
  week_value:string = null;

  ngOnInit() {
    this.apiServices.getRoster_tags().subscribe((str: string[]) => {
      this.roster_tags = str;
      this.getRosters_templates(this.roster_tags[0]);
    })
    this.apiServices.getPeriods().subscribe((periods: periods[]) => {
      this.activePeriod = periods[periods.length - 1];
      this.apiServices.getRosters("'" + this.activePeriod.idperiods + "'").subscribe((rsts: rosters[]) => {
        this.rosters = rsts;
        let dt: number = new Date(periods[periods.length - 1].start).getTime();
        while (dt <= new Date(periods[periods.length - 1].end).getTime()) {
          dt = dt + (1000 * 3600 * 24);
          this.period_days.push(new Date(dt).getDate().toFixed(0));
        }
        this.rosters.forEach((setRost: rosters) => {

        })
        this.rosters.forEach((roster_toShow:rosters)=>{
          let activeShow:boolean = false;
          this.rosters_show.forEach((showed:roster_views)=>{
            if(showed.nearsol_id == roster_toShow.nearsol_id){
              activeShow = true;
            }
          })
          if(!activeShow){
            let toShow:roster_views = new roster_views;
          toShow.nearsol_id = roster_toShow.nearsol_id;
          toShow.client_id = roster_toShow.client_id;
          toShow.name = roster_toShow.name;
          toShow.id_employee = roster_toShow.id_employee;
            if(this.dayExist(0)){
              toShow.day_1 = this.getRosterDay(0, roster_toShow);
            }
            if(this.dayExist(1)){
              toShow.day_2 = this.getRosterDay(1, roster_toShow);
            }
            if(this.dayExist(2)){
              toShow.day_3 = this.getRosterDay(2, roster_toShow);
            }
            if(this.dayExist(3)){
              toShow.day_4 = this.getRosterDay(3, roster_toShow);
            }
            if(this.dayExist(4)){
              toShow.day_5 = this.getRosterDay(4, roster_toShow);
            }
            if(this.dayExist(5)){
              toShow.day_6 = this.getRosterDay(5, roster_toShow);
            }
            if(this.dayExist(6)){
              toShow.day_7 = this.getRosterDay(6, roster_toShow);
            }
            if(this.dayExist(7)){
              toShow.day_8 = this.getRosterDay(7, roster_toShow);
            }
            if(this.dayExist(8)){
              toShow.day_9 = this.getRosterDay(8, roster_toShow);
            }
            if(this.dayExist(9)){
              toShow.day_10 = this.getRosterDay(9, roster_toShow);
            }
            if(this.dayExist(10)){
              toShow.day_11 = this.getRosterDay(10, roster_toShow);
            }
            if(this.dayExist(11)){
              toShow.day_12 = this.getRosterDay(11, roster_toShow);
            }
            if(this.dayExist(12)){
              toShow.day_13 = this.getRosterDay(12, roster_toShow);
            }
            if(this.dayExist(13)){
              toShow.day_14 = this.getRosterDay(13, roster_toShow);
            }
            if(this.dayExist(14)){
              toShow.day_15 = this.getRosterDay(14, roster_toShow);
            }
            if(this.dayExist(15)){
              toShow.day_16 = this.getRosterDay(15, roster_toShow);
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
    })
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

    if (Number(rt.count) > 1) {
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
      switch (dt.getDay()) {
        case 1:
          rtn = ((mixed_schedules[week].mon_start + " - " + (mixed_schedules[week].mon_end)));
          break;
        case 2:
          rtn = ((mixed_schedules[week].tue_start + " - " + mixed_schedules[week].tue_end));
          break;
        case 3:
          rtn = (mixed_schedules[week].wed_start + " - " + mixed_schedules[week].wed_end);
          break;
        case 4:
          rtn = ((mixed_schedules[week].thur_start + " - " + mixed_schedules[week].thur_end));
          break;
        case 5:
          rtn = ((mixed_schedules[week].fri_start + " - " + mixed_schedules[week].fri_end));
          break;
        case 6:
          rtn = (mixed_schedules[week].sat_start + " - " + mixed_schedules[week].sat_end);
          break;
        case 0:
          rtn = (mixed_schedules[week].sun_start + " - " + mixed_schedules[week].sun_end);
          break;
      }
    } else {
      switch (dt.getDay()) {
        case 1:
          rtn = (rt.mon_start + " - " + rt.mon_end);
          break;
        case 2:
          rtn = (rt.tue_start + " - " + rt.tue_end);
          break;
        case 3:
          rtn = (rt.wed_start + " - " + rt.wed_end);
          break;
        case 4:
          rtn = (rt.thur_start + " - " + rt.thur_end);
          break;
        case 5:
          rtn = (rt.fri_start + " - " + rt.fri_end);
          break;
        case 6:
          rtn = (rt.sat_start + " - " + rt.sat_end);
          break;
        case 0:
          rtn = (rt.sun_start + " - " + rt.sun_end);
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


  saveRosters(){
    this.working = true;
    let count:number = 0;
    let rosrters_result:rosters[] = [];
    this.selectedRosters.forEach(selection=>{
      let roster:rosters = new rosters;
      roster.id_employee = selection.id_employee;
      roster.id_schedule = this.selectedType.idroster_types;
      roster.week_value = this.week_value;
      roster.id_period = this.activePeriod.idperiods;
      this.apiServices.updateRosters(roster).subscribe((str:string)=>{
        roster.status = str;
        count++;
        rosrters_result.push(roster);
        if(count >= this.selectedRosters.length){
          this.working = false;
        }
      })
    })
  }
}
