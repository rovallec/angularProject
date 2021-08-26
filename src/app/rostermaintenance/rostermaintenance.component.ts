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
  schedule_types:roster_times[] = [];
  selectedSchedule:roster_times = new roster_times;

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

  getTemplate(idTemplate:roster_types) {
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

  change(any:string) {
    this.editingRosterTemplate = true;
    this.apiServices.getRosterTimes().subscribe((rst:roster_times[])=>{
      this.schedule_types = rst;
      if(!isNullOrUndefined(any)){
        this.selectedSchedule = rst.find(v => v.idroster_times == any);
      }else{
        this.selectedSchedule = rst[0];
      }
      this.apiServices.getRosterTemplates(" id_time_mon = " + this.selectedSchedule.idroster_times + " OR id_time_tue = " + this.selectedSchedule.idroster_times + " OR id_time_wed = "
      + this.selectedSchedule.idroster_times + " OR id_time_thur = " + this.selectedSchedule.idroster_times + " OR id_time_fri = " + this.selectedSchedule.idroster_times + " OR id_time_sat = " + this.selectedSchedule.idroster_times
      + " OR id_time_sun = " + this.selectedSchedule.idroster_times).subscribe((ss:roster_types[])=>{
        console.log(ss);
      })
    })
  }

  needUpdate(nb: number) {
    switch (nb) {
      case 1:
        return (this.selectedType.mon_start != this.getTemplate(this.selectedType).mon_start);
        break;
      case 2:
        return (this.selectedType.tue_start != this.getTemplate(this.selectedType).tue_start);
        break;
      case 3:
        return (this.selectedType.wed_start != this.getTemplate(this.selectedType).wed_start);
        break;
      case 4:
        return (this.selectedType.thur_start != this.getTemplate(this.selectedType).thur_start);
        break;
      case 5:
        return (this.selectedType.fri_start != this.getTemplate(this.selectedType).fri_start);
        break;
      case 6:
        return (this.selectedType.sat_start != this.getTemplate(this.selectedType).sat_start);
        break;
      case 7:
        return (this.selectedType.sun_start != this.getTemplate(this.selectedType).sun_start);
        break;
      case 8:
        return (this.selectedType.mon_end != this.getTemplate(this.selectedType).mon_end);
        break;
      case 9:
        return (this.selectedType.tue_end != this.getTemplate(this.selectedType).tue_end);
        break;
      case 10:
        return (this.selectedType.wed_end != this.getTemplate(this.selectedType).wed_end);
        break;
      case 11:
        return (this.selectedType.thur_start != this.getTemplate(this.selectedType).thur_start);
        break;
      case 12:
        return (this.selectedType.fri_start != this.getTemplate(this.selectedType).fri_start);
        break;
      case 13:
        return (this.selectedType.sat_start != this.getTemplate(this.selectedType).sat_start);
        break;
      case 14:
        return (this.selectedType.sun_end != this.getTemplate(this.selectedType).sun_end);
        break;
    }
  }

  setSchedule_type(idRosterTime){
    this.selectedSchedule = this.schedule_types.find(val => val.idroster_times == idRosterTime);
  }
}
