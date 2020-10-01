import { Component, OnInit } from '@angular/core';
import {ApiService} from '../api.service';
import {AuthServiceService} from '../auth-service.service';
import {Router} from '@angular/router';
import {waves_template, schedules, hires_template, periods} from '../process_templates'

@Component({
  selector: 'app-accdashboard',
  templateUrl: './accdashboard.component.html',
  styleUrls: ['./accdashboard.component.css']
})
export class AccdashboardComponent implements OnInit {

  waves:waves_template[] = [new waves_template];
  wave_ToEdit:waves_template = new waves_template;
  schedules:schedules[] = [new schedules];
  schedule_to_edit:schedules = new schedules;
  hires:hires_template[] = [new hires_template];
  sch_hrs_st:string;
  sch_min_st:string;
  sch_hrs_e:string;
  sch_min_e:string;
  includeAll:boolean = false;
  periods:periods[] = [];

  constructor(private apiService: ApiService, public router:Router, private authSrv:AuthServiceService) { }

  ngOnInit() {
    this.getWavesAll();
    this.getPeriods();
  }

  getPeriods(){
    this.apiService.getPeriods().subscribe((prd:periods[])=>{
      this.periods = prd;
    });
  }

  gotoPeriod(id:string){
    this.router.navigate(['./periods', id]);
  }

  getWavesAll(){
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate() + " AND `state` = 0";
    this.apiService.getfilteredWaves({str:date}).subscribe((readWaves:waves_template[])=>{
      this.waves = readWaves;
      this.wave_ToEdit = readWaves[0];
    })
  }

  getSchedules(wv:waves_template){
    this.waves.forEach(wa => {
      wa.show = '0';
    });
    this.apiService.getFilteredHires(wv).subscribe((hires:hires_template[])=>{
      this.hires = hires
    });
    this.waves[this.waves.indexOf(wv)].show = "1";
  }

  set_edit_schedule(){
    this.sch_hrs_st = this.schedule_to_edit.start_time.split(":")[0];
    this.sch_min_st = this.schedule_to_edit.start_time.split(":")[1];
    this.sch_hrs_e = this.schedule_to_edit.end_time.split(":")[0];
    this.sch_min_e = this.schedule_to_edit.end_time.split(":")[1];
  }

  getHires(sch:schedules){
    this.schedules.forEach(sch => {
      sch.show = '0';
    })

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

  includeIn(toInclude:hires_template){
    this.hires[this.hires.indexOf(toInclude)].status = "EMPLOYEE";
  }

  selectAll(){
    this.includeAll = !this.includeAll;
    if(this.includeAll == true){
      this.hires.forEach(el => {
        el.status = "EMPLOYEE";
      });
    }else{
      this.getSchedules(this.wave_ToEdit);
    }
  }
}
