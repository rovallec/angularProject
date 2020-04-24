import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';

import { profiles } from '../profiles';
import { Router } from '@angular/router';

import { interval } from 'rxjs';
import { Observable } from 'rxjs';
import { AuthServiceService } from '../auth-service.service';
import { process } from '../process';
import { stringify } from '@angular/compiler/src/util';
import { search_parameters } from '../fullProcess';
import { filter } from 'minimatch';
import {waves_template, hires_template, schedules, accounts} from '../process_templates';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  global_search:{
    filter:string;
    text:string;
  } = {
    filter:null,
    text:null
  }
  search_result_global:boolean = false;
  updateOngoing:string = 'false';
  waveView:boolean = false;
  edit_waves:boolean = false;
  main_view:boolean = true;
  stateValues:boolean[] = [
    false,
    true
  ]

  schedule_to_edit:schedules = new schedules;
  nw_schedule:schedules = new schedules;
  add_new_schedule:boolean = false;
  add_new_wave:boolean = false;
  nw_wave = new waves_template;
  editing_wave:boolean = true;
  wave_ToEdit:waves_template = new waves_template;
  waves:waves_template[];
  hires:hires_template[];

  end_wave_edit:boolean;

  schedules:schedules[] = [new schedules];

  prof_ps:profiles[];
  prof_a:profiles[];
  prof_r:profiles[];

  trainning_schedule_hr_st:string = null;
  trainning_schedule_mn_st:string = null;
  trainning_schedule_hr_end:string = null;
  trainning_schedule_mn_end:string = null;
  days_off:string[] = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday"
  ];
  day1_off:string = null;
  day2_off:string = null;

  procAdd:process = {
    idprocesses:'N/A',
    id_role:'N/A',
    id_profile:'N/A',
    name:'N/A',
    descritpion:'N/A',
    prc_date:'N/A',
    status:'N/A',
    id_user:'N/A',
    user_name:null
  };

  searchOn_App:boolean;
  searchOn_Apr:boolean;
  searchOn_Den:boolean;

  searchParameters_App:search_parameters[] = [
    {
      filter:'ID',
      value:'N/A'
    }
  ];

  searchParameters_Apr:search_parameters[] = [
    {
      filter:'ID',
      value:'N/A'
    }
  ];

  searchParameters_Den:search_parameters[] = [
    {
      filter:'ID',
      value:'N/A'
    }
  ];

  id_inProc:number;

  nowDat = new Date;
  today_day:string[] = [null];
  today_month: string[] = [null];
  today_year:string[] = [null];

  min_t:string[] = [null];
  hrs_t:string[] = [null];

  accounts:accounts[] = [new accounts];
  day_toEdit_st:string = this.nowDat.getDate().toString();
  month_toEdit_st:string = this.nowDat.getMonth().toString();
  year_toEdit_st:string = this.nowDat.getFullYear().toString();
  day_toEdit_e:string = (this.nowDat.getDate() + 1).toString();
  month_toEdit_e:string = this.nowDat.getMonth().toString();
  year_toEdit_e:string = this.nowDat.getFullYear().toString();

  sch_hrs_st:string = null;
  sch_min_st:string = null;
  sch_hrs_e:string = null;
  sch_min_e:string = null;

  constructor(private apiService: ApiService, public router:Router, private authSrv:AuthServiceService) { }

  ngOnInit() {
    
    this.apiService.getAcconts().subscribe((acc:accounts[])=>{
      this.accounts = acc;
    })

    for (let i = 1; i < 32; i++) {
      this.today_day[i-1] = i.toString().padStart(2,"0");
    }
    for (let i = 1; i < 13; i++) {
      this.today_month[i-1] = i.toString().padStart(2,"0");
    }
    for (let i = 0; i < 2; i++) {
      this.today_year[i] = (this.nowDat.getFullYear()+i).toString();
      
    }
    for(let i = 0; i < 24; i++){
      this.hrs_t[i] = (i).toString().padStart(2,"0");
    }
    for(let i = 0; i < 60; i++){
      this.min_t[i] = (i).toString().padStart(2,"0");
    }

    this.apiService.readProfiles().subscribe((prof_ps:profiles[])=>{
      this.prof_ps = prof_ps;
    });

    this.apiService.readApproved().subscribe((prof_a:profiles[])=>{
      this.prof_a = prof_a;
    });

    this.apiService.readRejected().subscribe((prof_r:profiles[])=>{
      this.prof_r = prof_r;
    });
    
    this.getWavesAll();
    this.edit_waves = false;
    this.waveView = false;
  }

  getWavesAll(){
    this.apiService.getWaves().subscribe((readWaves:waves_template[])=>{
      this.waves = readWaves;
      this.wave_ToEdit = readWaves[0];
      this.getSchedules(this.wave_ToEdit);
    })
  }


  updateApproved(profil:profiles){
    this.updateOngoing = 'true';
    profil['status']='approved';

    this.procAdd.id_user = this.authSrv.getAuthusr().iduser;
    this.procAdd.id_role = this.authSrv.getAuthusr().id_role;
    this.procAdd.id_profile = profil.idprofiles;
    this.procAdd.idprocesses = '';
    this.procAdd.name = 'Pre-Approval';
    this.procAdd.status = 'CLOSED';
    this.procAdd.prc_date = String(this.nowDat.getFullYear()).padStart(4) + '-' + String(this.nowDat.getMonth()+1).padStart(2, '0') + '-' + String(this.nowDat.getDate()).padStart(2, '0');
    this.procAdd.descritpion = 'New Approved Candidate';

    this.apiService.changeStatus(profil).subscribe((record:number)=>{

      this.apiService.insProcess(this.procAdd).subscribe((id_ins:number)=>{
        this.id_inProc = id_ins;
      });

      this.apiService.readProfiles().subscribe((prof_ps:profiles[])=>{
        this.prof_ps = prof_ps;
      });
  
      this.apiService.readApproved().subscribe((prof_a:profiles[])=>{
        this.prof_a = prof_a;
      });
  
      this.apiService.readRejected().subscribe((prof_r:profiles[])=>{
        this.prof_r = prof_r;
      });
    });
    this.updateOngoing = 'false';
  }

  gotoProfile(id_prof:number){
    this.router.navigate(['./profiles', id_prof]);
  }

  updateRejected(profil:profiles){
    this.updateOngoing = 'true';
    profil['status']='rejected';

    this.apiService.changeStatus(profil).subscribe((record:number)=>{
      this.apiService.readProfiles().subscribe((prof_ps:profiles[])=>{
        this.prof_ps = prof_ps;
      });
  
      this.apiService.readApproved().subscribe((prof_a:profiles[])=>{
        this.prof_a = prof_a;
      });
  
      this.apiService.readRejected().subscribe((prof_r:profiles[])=>{
        this.prof_r = prof_r;
      });
    });
    this.updateOngoing = 'false';
  }

  refresh(){ 
    this.apiService.readProfiles().subscribe((prof_ps:profiles[])=>{
      this.prof_ps = prof_ps;
    });

    this.apiService.readApproved().subscribe((prof_a:profiles[])=>{
      this.prof_a = prof_a;
    });

    this.apiService.readRejected().subscribe((prof_r:profiles[])=>{
      this.prof_r = prof_r;
    });
  }

  searchMode(srchToggle:boolean){
    this.searchOn_App = srchToggle;
  }

  paramAdd_App(){
    var addAs:search_parameters = {
      filter:'ID',
      value:'N/A'
    };
    this.searchParameters_App.push(addAs);
  }
  
  paramRemove_App(parameterToDel){
    if(this.searchParameters_App.length > 1){
      this.searchParameters_App.splice(this.searchParameters_App.indexOf(parameterToDel),1);
    }
  }

  filterByParam(){
    this.apiService.readFilteredProfiles(this.searchParameters_App).subscribe((prof_ps:profiles[])=>{
      this.prof_ps = prof_ps;
    })
  }

  searchMode_Apr(srchToggle:boolean){
    this.searchOn_Apr = srchToggle;
  }

  paramAdd_Apr(){
    var addAs:search_parameters = {
      filter:'ID',
      value:'N/A'
    };
    this.searchParameters_Apr.push(addAs);
  }
  
  paramRemove_Apr(parameterToDel){
    if(this.searchParameters_Apr.length > 1){
      this.searchParameters_Apr.splice(this.searchParameters_Apr.indexOf(parameterToDel),1);
    }
  }

  filterByParam_Apr(){
    this.apiService.readFilteredProfiles_Apr(this.searchParameters_Apr).subscribe((prof_a:profiles[])=>{
      this.prof_a = prof_a;
    })
  }

  searchMode_Den(srchToggle:boolean){
    this.searchOn_Den = srchToggle;
  }

  paramAdd_Den(){
    var addAs:search_parameters = {
      filter:'ID',
      value:'N/A'
    };
    this.searchParameters_Den.push(addAs);
  }
  
  paramRemove_Den(parameterToDel){
    if(this.searchParameters_Den.length > 1){
      this.searchParameters_Den.splice(this.searchParameters_Den.indexOf(parameterToDel),1);
    }
  }

  filterByParam_Den(){
    this.apiService.readFilteredProfiles_Den(this.searchParameters_Den).subscribe((prof_r:profiles[])=>{
      this.prof_r = prof_r;
    })
  }

  getWave(){
    this.main_view = false;
    this.edit_waves = false;
    this.waveView = true;
  }

  returnHome(){
    this.main_view = true;
    this.waveView = false;
    this.edit_waves = false;
  }

  getSchedules(wv:waves_template){
    this.waves.forEach(wa => {
      wa.show = '0';
    });
    this.apiService.getSchedules(wv).subscribe((sch:schedules[])=>{
      this.schedules = sch;
      this.schedule_to_edit = this.schedules[0];
      this.set_edit_schedule();
    });
    this.waves[this.waves.indexOf(wv)].show = '1';
    this.schedules.forEach(sch => {
      sch.show = '0';
    });
  }

  getHires(sch:schedules){
    this.schedules.forEach(sch => {
      sch.show = '0';
    })
    this.apiService.getFilterSchHires(sch).subscribe((hires:hires_template[])=>{
      this.hires = hires
    });
    this.schedules[this.schedules.indexOf(sch)].show = '1';
  }

  hideSchedules(){
    this.waves.forEach(w => {
      w.show = '0';
    })
  }

  hideHires(){
   this.schedules.forEach(sch => {
     sch.show = '0';
   })
  }

  waves_config(){
    this.end_wave_edit = false;
    try {
      this.wave_ToEdit = this.waves[0];
      this.set_edit_wave();
      this.set_edit_schedule(); 
    } catch (error) {
      
    }
    this.waveView = false;
    this.main_view = false;
    this.edit_waves = true;
  }

  set_edit_wave(){
    this.day_toEdit_st = this.wave_ToEdit.starting_date.split("-")[2]
    this.month_toEdit_st =  this.wave_ToEdit.starting_date.split("-")[1];
    this.year_toEdit_st = this.wave_ToEdit.starting_date.split("-")[0];
    this.day_toEdit_e = this.wave_ToEdit.end_date.split("-")[2];
    this.month_toEdit_e = this.wave_ToEdit.end_date.split("-")[1];
    this.year_toEdit_e = this.wave_ToEdit.end_date.split("-")[0];

    this.trainning_schedule_hr_st = this.wave_ToEdit.trainning_schedule.split("-")[0].split(":")[0];
    this.trainning_schedule_mn_st = this.wave_ToEdit.trainning_schedule.split("-")[0].split(":")[1].split(" ")[0];
    this.trainning_schedule_hr_end = this.wave_ToEdit.trainning_schedule.split("-")[1].split(":")[0].split(" ")[1];
    this.trainning_schedule_mn_end = this.wave_ToEdit.trainning_schedule.split("-")[1].split(":")[1].split(" ")[0]; 


    this.getSchedules(this.wave_ToEdit);
    this.sch_hrs_st = this.schedule_to_edit.start_time.split(":")[0];
    this.sch_min_st = this.schedule_to_edit.start_time.split(":")[1];
    this.sch_hrs_e = this.schedule_to_edit.end_time.split(":")[0];
    this.sch_min_e = this.schedule_to_edit.end_time.split(":")[1];
    if(this.schedules.length>0){
      this.add_new_schedule=false;
    }else{
      this.add_new_schedule=true;
    }
  }

  set_edit_schedule(){
      this.sch_hrs_st = this.schedule_to_edit.start_time.split(":")[0];
      this.sch_min_st = this.schedule_to_edit.start_time.split(":")[1];
      this.sch_hrs_e = this.schedule_to_edit.end_time.split(":")[0];
      this.sch_min_e = this.schedule_to_edit.end_time.split(":")[1];
      this.day1_off = this.schedule_to_edit.days_off.split(",")[0];
      this.day2_off = this.schedule_to_edit.days_off.split(",")[1]; 
  }
  
  updateSchedule(){
    this.schedule_to_edit.start_time = this.sch_hrs_st + ":" + this.sch_min_st + ":00";
    this.schedule_to_edit.end_time = this.sch_hrs_e + ":" + this.sch_min_e + ":00";
    this.apiService.updateSchedules(this.schedule_to_edit).subscribe((st:string)=>{});
  }

  updateWave(){
    this.wave_ToEdit.starting_date = this.year_toEdit_st + this.month_toEdit_st + this.day_toEdit_st;
    this.wave_ToEdit.end_date = this.year_toEdit_e + this.month_toEdit_e + this.day_toEdit_e;
    this.apiService.updateWave(this.wave_ToEdit).subscribe((st:string)=>{});
    this.getWavesAll();
  }

  add_schedule(id_wave:string){
    this.add_new_schedule = true;
    this.nw_schedule = new schedules;
    this.nw_schedule.actual_count = '0';
    this.nw_schedule.state = '1';
    this.nw_schedule.id_wave = id_wave;
    this.schedules.push(this.nw_schedule);
    this.schedule_to_edit = this.schedules[this.schedules.indexOf(this.nw_schedule)];
    this.add_new_schedule = true;
  }

  add_wave(){
    this.nw_wave = new waves_template;
    this.nw_wave.state = '1';
    this.waves.push(this.nw_wave);
    this.wave_ToEdit = this.waves[this.waves.indexOf(this.nw_wave)];
    this.add_new_wave = true;
  }

  insertSchedule(){
    this.schedule_to_edit.days_off = this.day1_off + "," + this.day2_off;
    this.schedule_to_edit.start_time = this.sch_hrs_st + ":" + this.sch_min_st + ":00";
    this.schedule_to_edit.end_time = this.sch_hrs_e + ":" + this.sch_min_e + ":00";
    this.apiService.insertNewSchedule(this.schedule_to_edit).subscribe((st:string)=>{});
    this.add_new_schedule = false;
  }

  insertWave(){
    this.wave_ToEdit.starting_date = this.year_toEdit_st + "/" + this.month_toEdit_st +  "/" + this.day_toEdit_st;
    this.wave_ToEdit.end_date = this.year_toEdit_e +  "/" + this.month_toEdit_e +  "/" + this.day_toEdit_e;
    this.wave_ToEdit.trainning_schedule = this.trainning_schedule_hr_st + ":" + this.trainning_schedule_mn_st + " - " + this.trainning_schedule_hr_end + ":" + this.trainning_schedule_mn_end;
    this.apiService.insertNewWave(this.wave_ToEdit).subscribe((st:string)=>{
      this.schedules = [new schedules];
      this.add_schedule(st);
    });
    this.add_new_wave = false;
    this.add_new_schedule = true;
    this.end_wave_edit = true;
  }

  global_search_sch(){
    this.apiService.getSearchProfile(this.global_search).subscribe((prof:profiles[])=>{
      this.prof_ps = prof;
      this.search_result_global = true;
    });
  }

  clear_globalSearch(){
    this.refresh()
    this.search_result_global = false;
  }
}
