import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { periods } from '../process_templates';

@Component({
  selector: 'app-isrmanager',
  templateUrl: './isrmanager.component.html',
  styleUrls: ['./isrmanager.component.css']
})
export class IsrmanagerComponent implements OnInit {

  periods:periods[] = [];
  selectedPeriod:periods = new periods;

  constructor(public apiServices:ApiService) { }

  ngOnInit() {
    this.apiServices.getPeriods().subscribe((per:periods[])=>{
      this.periods = per;
    })
  }

  setSelection(){
    
  }

}
