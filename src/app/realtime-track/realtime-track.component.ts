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

  searchOptions:string[] = [
    "DATE",
    "RECRUITER",
    "ACCOUNT",
    "FIRST STATUS",
    "SECOND STATUS",
    "LAST PROCESS"
  ];

  valFilters:string[] = [null];

  selectedOption:string = null;

  realTimeReport:realTimeTrack[] = [new realTimeTrack];

  constructor(private apiService: ApiService, public router:Router, private authSrv:AuthServiceService) { }

  ngOnInit() {
    this.apiService.getrealTime().subscribe((rlt:realTimeTrack[])=>{
      this.realTimeReport = rlt;
    })
  }

  onChangeFilter(){
    this.apiService.getFilteredParam({string:this.selectedOption}).subscribe((str:string[]) => {
      this.valFilters = str;
    });
  }
}
