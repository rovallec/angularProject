import { Component, OnInit, RootRenderer } from '@angular/core';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { accounts, clients, periods, roster_times, roster_types } from '../process_templates';

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
  updatePending:boolean[] = [false,false,false,false,false,false,false];

  ngOnInit() {
    this.apiServices.getRoster_tags().subscribe((str: string[]) => {
      this.roster_tags = str;
      this.getRosters_templates(this.roster_tags[0]);
    })
    this.apiServices.getPeriods().subscribe((periods: periods[]) => {
      let dt: number = new Date(periods[periods.length - 1].start).getTime();
      while (dt < new Date(periods[periods.length - 1].end).getTime()) {
        this.period_days.push(new Date(dt).getDate().toFixed(0));
        dt = dt + (1000 * 3600 * 24);
      }
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

  change(any: string, nb:number) {
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
    return this.updatePending[Number(nb) -1];
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

  saveRooster(){
    this.apiServices.updateRosterType(this.selectedType).subscribe((str:roster_types[])=>{
      this.selected_types = str;
      this.selectedType = str[str.length - 1];
    })
  }
}
