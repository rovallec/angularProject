import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouteConfigLoadEnd } from '@angular/router';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';

@Component({
  selector: 'app-accprofiles',
  templateUrl: './accprofiles.component.html',
  styleUrls: ['./accprofiles.component.css']
})
export class AccprofilesComponent implements OnInit {

  employee:employees = new employees;
  employe_id:string = null;
  profile_id:string = null;
  payment:string = null;

  constructor(public apiService:ApiService, public route: ActivatedRoute) { }

  ngOnInit() {
    this.employe_id = this.route.snapshot.paramMap.get('id').split(";")[0];
    this.profile_id = this.route.snapshot.paramMap.get('id').split(";")[1];
    this.apiService.getSearchEmployees({dp:'all', filter:'idemployees', value:this.employe_id}).subscribe((emp:employees[])=>{
      this.employee = emp[0];
    })
  }

}
