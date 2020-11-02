import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { profiles } from '../profiles';

@Component({
  selector: 'app-fprofiles',
  templateUrl: './fprofiles.component.html',
  styleUrls: ['./fprofiles.component.css']
})
export class FprofilesComponent implements OnInit {

  constructor(public apiService:ApiService, public route:ActivatedRoute, public authUser:AuthServiceService) { }
  
  employee:employees = new employees;
  profile:profiles = new profiles;

  ngOnInit() {
    this.start();
  }

  start(){
    this.apiService.getSearchEmployees({dp:this.authUser.getAuthusr().department, filter:'idemployees', value:this.route.snapshot.paramMap.get('id')}).subscribe((emp:employees[])=>{
      this.employee = emp[0]
      let prof:profiles = new profiles;
      prof.id_profile = emp[0].id_profile;
      this.apiService.getProfile(prof).subscribe((profile:profiles[])=>{
        this.profile = profile[0];
      })
    })
  }

}
