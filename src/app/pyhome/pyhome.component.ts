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
  searching: boolean = false;
  editWave: boolean = false;

  constructor(public apiService: ApiService, public route: Router, public authService: AuthServiceService) { }

  ngOnInit() {
    this.getWavesAll();
    this.getAllEmployees();
  }

  getAllEmployees() {
    this.apiService.getallEmployees({ department: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
  }

  gotoProfile(emp:employees){
    this.route.navigate(['./pyprofiles', emp.idemployees]);
  }

  getWavesAll() {
    this.waves = [];
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      readWaves.forEach(ww => {
        ww.state = ww.state.split(",")[2];
        if (ww.state === '0') {
          this.waves.push(ww);
        }
      })
      this.wave_ToEdit = readWaves[0];
    })
  }

  searchEmployee() {
    this.apiService.getSearchEmployees({filter:this.filter, value:this.value, dp:this.authService.getAuthusr().department}).subscribe((emp:employees[])=>{
      this.employees = emp;
    });
    this.searching = true;
  }

  cancelSearch() {

  }

  saveEmployees() {
    this.hires.forEach(hire => {
      if (hire.client_id.length > 0) {
        this.apiService.getSearchEmployees({dp:'all', filter:'nearsol_id', value:hire.nearsol_id}).subscribe((emp:employees[])=>{
          emp[0].client_id = hire.client_id;
          this.apiService.updateEmployee(emp[0]).subscribe((str: string) => { });
        })
      }
    })
  }

  setWave(wave: waves_template) {
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
    this.apiService.getfilteredWaves({ str: date }).subscribe((readWaves: waves_template[]) => {
      readWaves.forEach(ww => {
        if (ww.idwaves == wave.idwaves) {
          wave.state = ww.state.split(",")[0] + "," + ww.state.split(",")[1] + "," + wave.state + "," + ww.state.split(",")[3]
        }
      })
      this.apiService.updateWaveState(wave).subscribe((str: string) => {
        this.getWavesAll();
        this.getAllEmployees();
      })
    })
  }

  getSchedules(wv: waves_template) {
    this.hires = [];
    this.waves.forEach(wa => {
      wa.show = '0';
    });
    this.apiService.getHiresAsEmployees(wv).subscribe((hires: hires_template[]) => {
      this.hires = hires;
    })
    this.waves[this.waves.indexOf(wv)].show = "1";
  }

  hideSchedules() {
    this.waves.forEach(wv => {
      wv.show = '0';
    })
  }


}
