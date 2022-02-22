import { Component, OnInit } from '@angular/core';
import { waves_template } from '../process_templates';
import { ApiService } from '../api.service';
import { isUndefined } from 'util';

@Component({
  selector: 'app-calllistreport',
  templateUrl: './calllistreport.component.html',
  styleUrls: ['./calllistreport.component.css']
})
export class CalllistreportComponent implements OnInit {

  days:string[] = [];
  selectedDate:number[] = [new Date().getDate(), new Date().getMonth() + 1, new Date().getFullYear()];
  selectedValueDate:string = null;
  years:string[] = [];
  months:string[] = [];
  waves:waves_template[] = [];
  selectedWave:waves_template = null;
  errorHandle:boolean = false;

  constructor(private apiService:ApiService) { }

  ngOnInit() {
    for (let i = 0; i < 12; i++) {
      this.months.push((i+1).toString());
    }
    for (let i = 0; i < 3; i++) {
      this.years.push((new Date().getFullYear() - i).toString());
    }
    this.pushDays();
    this.getWaves();
  }

  pushDays(){
    this.days = [null];
    this.errorHandle = false;
    for (let i = 0; i < new Date(this.selectedDate[2], this.selectedDate[1], 0).getDate() ; i++) {
      if(i == 0){
        this.days[0] = (i+1).toString();
      }else{
        this.days.push((i+1).toString());
      }
    };
    this.selectedValueDate = "'" + this.selectedDate[2] + "-" + this.selectedDate[1] + "-" + this.selectedDate[0] + "'";
    this.getWaves();
  }

  getWaves(){
    const str:{str:string} = {
      str:this.selectedValueDate
    }
    this.apiService.getfilteredWaves(str).subscribe((readWaves:waves_template[])=>{
      this.waves = readWaves;
      this.selectedWave = this.waves[0];
    })
  }

  changedWave(){
    this.errorHandle = false;
  }

  async downloadReport(){
    if(isUndefined(this.selectedWave)){
      this.errorHandle = true;
    }else{
      try {
        window.open("http://172.18.2.45/phpscripts/exportClasslist.php?wave=" + this.selectedWave.idwaves);
      } catch (error) {
        alert("It could not to generate the report.");
      } finally {
        await new Promise( resolve => setTimeout(resolve, 1000));
        window.confirm("The report has been generated.");
      }
    }
  }
}
