import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { realTimeTrack } from '../process_templates';

@Component({
  selector: 'app-realtime-track',
  templateUrl: './realtime-track.component.html',
  styleUrls: ['./realtime-track.component.css']
})


export class RealtimeTrackComponent implements OnInit {

  searchOptions:{name:string; value:string}[] = [
    {name:"DATE",
    value:"date"},
    {name:"RECRUITER",
    value:"Recruiter"},
    {name:"ACCOUNT",
    value:"account"},
    {name:"FIRST STATUS",
    value:"firstInterview"},
    {name:"SECOND STATUS",
    value:"secondInterview"},
    {name:"LAST PROCESS",
    value:"lastprocessName"}
  ];

  months:string[] = [];
  years:string[] = [];
  days:string[] = [];

  todayDate:Date = new Date();

  selectedDate:number[] = [this.todayDate.getFullYear(), this.todayDate.getMonth() + 1, this.todayDate.getDate()];
  valFilters:string[] = [null];

  selectedOption:string = "date";
  selectedValue:string = this.selectedDate[0].toString() + "-" + this.selectedDate[1].toString() + "-" + this.selectedDate[2].toString();

  realTimeReport:realTimeTrack[] = [new realTimeTrack];

  constructor(private apiService: ApiService, public router:Router, private authSrv:AuthServiceService) { }

  ngOnInit() {
    this.fillUpRealTime();
    for (let i = 0; i < 12; i++) {
      this.months.push((i + 1).toString());
    };
    for (let i = 0; i < 3; i++) {
      this.years.push((this.todayDate.getUTCFullYear() - i).toString());
    };
    this.pushDays();
  }

  onChangeFilter(){
    if(this.selectedOption != 'date' && this.selectedOption != 'firstInterview' && this.selectedOption != "secondInterview"){
        this.apiService.getFilteredParam({string:this.selectedOption}).subscribe((str:string[]) => {
          this.valFilters = str;
          this.selectedValue = this.valFilters[0];
        });
    }
  }

  gotoProfile(id_prof:number){
    this.router.navigate(['./profiles', id_prof]);
  }

  pushDays(){
    for (let i = 0; i < new Date(this.selectedDate[0], this.selectedDate[1], 0).getDate() ; i++) {
      this.days.push((i+1).toString());
    };
    this.selectedValue = this.selectedDate[0].toString() + "-" + this.selectedDate[1].toString() + "-" + this.selectedDate[2].toString();
  }

  fillUpRealTime(){
    this.realTimeReport.push(new realTimeTrack);
    this.realTimeReport[0].filter = this.selectedOption;
    this.realTimeReport[0].filterValue = this.selectedValue;
    this.apiService.getrealTime(this.realTimeReport[0]).subscribe((rlt:realTimeTrack[])=>{
      this.realTimeReport = rlt;
    });
  }
}
