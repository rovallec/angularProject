import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { hires_template, schedules, waves_template } from '../process_templates';

@Component({
  selector: 'app-pyhome',
  templateUrl: './pyhome.component.html',
  styleUrls: ['./pyhome.component.css']
})
export class PyhomeComponent implements OnInit {


  waves: waves_template[] = [new waves_template];
  wave_ToEdit: waves_template = new waves_template;
  schedules: schedules[] = [new schedules];
  schedule_to_edit: schedules = new schedules;
  hires: hires_template[] = [];
  employees: employees[] = [];
  filter: string = null;
  value: string = null;
  searching:boolean = false;

  constructor(public apiService:ApiService, public route:Router, public authSrv:AuthServiceService) { }

  ngOnInit() {
    this.getWavesAll();
    this.getAllEmployees();
  }

  getAllEmployees() {
    this.apiService.getallEmployees({ department: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
  }

  getWavesAll() {
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate() + " AND (`state` = 0 OR `state` = '2')";
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      this.waves = readWaves;
      this.wave_ToEdit = readWaves[0];
    })
  }

  searchEmployee(){

  }

  cancelSearch(){

  }

  setWave(wave:waves_template){

  }

  getSchedules(wave:waves_template){

  }

  hideSchedules(){

  }


}
