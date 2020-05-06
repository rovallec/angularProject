import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { waves_template, hires_template } from '../process_templates';

@Component({
  selector: 'app-hrhome',
  templateUrl: './hrhome.component.html',
  styleUrls: ['./hrhome.component.css']
})
export class HrhomeComponent implements OnInit {

  showWaveDetails:boolean = false;
  showEmployeeDetails:boolean[] = [];
  showAttendenceDetails:boolean = false;
  makeEmployee:boolean = false;

  wavesToShow:waves_template[] = [new waves_template];
  hiresToShow:hires_template[] = [new hires_template];

  constructor(private apiService:ApiService) { }

  ngOnInit() {
    const date = ">=" + new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate() + " AND `state` = 0";
    this.apiService.getfilteredWaves({str: date}).subscribe((wv:waves_template[])=>{
      this.wavesToShow = wv;
    })
    for (let i = 0; i < this.wavesToShow.length; i++) {
      this.showEmployeeDetails.push(false);
    }
  }

  showWave(wv:waves_template){
    this.wavesToShow.forEach(element => {
      element.show = '0';
    });
    this.wavesToShow[this.wavesToShow.indexOf(wv)].show = '1';
  }

  hideWave(){
    this.wavesToShow.forEach(element => {
      element.show = '0';
    });
    this.hideEmployees()
    this.showAttendenceDetails = false;
  }

  showEmployees(wv:waves_template){
    this.showEmployeeDetails[this.wavesToShow.indexOf(wv)] = true;

    this.apiService.getHiresAsEmployees(wv).subscribe((res:hires_template[])=>{
      this.hiresToShow = res;
    })
  }

  hideEmployees(){
    for (let i = 0; i < this.showEmployeeDetails.length; i++) {
      this.showEmployeeDetails[i] = false;
    }
  }

  getShow(wv:waves_template){
    return this.showEmployeeDetails[this.wavesToShow.indexOf(wv)]
  }

  showAttendences(){
    this.showAttendenceDetails = !this.showAttendenceDetails;
  }

  editEmployee(){
    this.makeEmployee = true;
  }

  cancelEmployeeEdit(wv:waves_template){
    this.makeEmployee = false;
    this.showEmployees(wv);
  }

}
