import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';

@Component({
  selector: 'app-fhome',
  templateUrl: './fhome.component.html',
  styleUrls: ['./fhome.component.css']
})
export class FhomeComponent implements OnInit {

  constructor(public apiService:ApiService, public authService:AuthServiceService) { }

  filter:string = null;
  value:string = null;
  searching:boolean = null;
  allEmployees:employees[] = []


  ngOnInit() {
  }

  start(){
    this.apiService.getallEmployees({nm:this.authService.getAuthusr().department}).subscribe((emp:employees[])=>{
      this.allEmployees = emp;
    })
  }

  searchEmployee(){
    this.apiService.getSearchEmployees({filter:this.filter, value:this.value, dp:this.authService.getAuthusr().department}).subscribe((emp:employees[])=>{
      this.allEmployees = emp;
    });
    this.searching = true;
  }

  cancelSearch(){
    this.searching = false;
    this.start();
  }
}
