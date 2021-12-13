import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { ActivatedRoute } from '@angular/router';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { profiles } from '../profiles';
import { asset_movements } from '../process_templates';

@Component({
  selector: 'app-itprofiles',
  templateUrl: './itprofiles.component.html',
  styleUrls: ['./itprofiles.component.css']
})
export class ItprofilesComponent implements OnInit {

  constructor(private apiService:ApiService, private authUser:AuthServiceService, private route:ActivatedRoute) { }

  employee:employees = new employees;
  profile:profiles = new profiles;
  assigned_assets:asset_movements[] = [];


  ngOnInit() {
    this.start();
  }

  start(){
    this.apiService.getSearchEmployees({dp:'all', filter:'idemployees', value:this.route.snapshot.paramMap.get('id'), rol:this.authUser.getAuthusr().id_role}).subscribe((emp:employees[])=>{
      this.employee = emp[0]
      let prof:profiles = new profiles;
      prof.idprofiles = emp[0].id_profile;
      this.apiService.getAssignedAssets({id:emp[0].idemployees}).subscribe((ass:asset_movements[])=>{
        this.assigned_assets = ass;
      })
      this.apiService.getProfile(prof).subscribe((profile:profiles[])=>{
        this.profile = profile[0];
      })
    })
  }
}
