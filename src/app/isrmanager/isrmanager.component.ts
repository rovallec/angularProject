import { Component, OnInit } from '@angular/core';
import { Z_DATA_ERROR } from 'zlib';
import { ApiService } from '../api.service';
import { isr, periods } from '../process_templates';

@Component({
  selector: 'app-isrmanager',
  templateUrl: './isrmanager.component.html',
  styleUrls: ['./isrmanager.component.css']
})
export class IsrmanagerComponent implements OnInit {

  periods: periods[] = [];
  selectedPeriod: periods = new periods;
  years: string[] = [new Date().getFullYear().toString()];
  selectedYear: string = new Date().getFullYear().toString();
  isrs:isr[] = [];

  constructor(public apiServices: ApiService) { }

  ngOnInit() {
    this.setPeriods();
  }

  setPeriods() {
    this.apiServices.getPeriods().subscribe((per: periods[]) => {
      per.forEach(period => {
        if (this.years[0] != period.start.split("-")[0]) {
          this.years.push(period.start.split("-")[0]);
        }

        if (this.selectedYear == period.start.split("-")[0]) {
          this.periods.push(period);
          this.selectedPeriod = this.periods[0];
          this.getIsr();
        }
      })
    });
  }

  getIsr(){
    this.apiServices.getIsr(this.selectedPeriod).subscribe((isrs:isr[])=>{
      this.isrs = isrs;
    })
  }

  setSelection(period: periods) {
    this.getIsr();
    this.selectedPeriod = period;
  }

}
