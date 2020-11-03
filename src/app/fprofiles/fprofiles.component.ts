import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { services } from '../process_templates';
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
  services:services[] = [];
  activeService:services = new services;
  bus:boolean;
  parking:boolean;
  todayDate:string = null;

  ngOnInit() {
    this.todayDate = (new Date().getFullYear().toString()) + "-" + ((new Date().getMonth() + 1).toString()) + "-" + (new Date().getDate().toString())
    this.start();
  }

  activeBus(){
    this.bus = true;
    this.activeService = new services;
    this.activeService.id_user = this.authUser.getAuthusr().user_name;
    this.activeService.date = (new Date().getFullYear().toString()) + "-" + ((new Date().getMonth()+1).toString()) + "-" + (new Date().getDate().toString());
    this.activeService.proc_status = "PENDING";
    this.activeService.status = '1';
  }

  activeParking(){
    this.parking = true;
    this.activeService = new services;
    this.activeService = new services;
    this.activeService.id_user = this.authUser.getAuthusr().user_name;
    this.activeService.date = (new Date().getFullYear().toString()) + "-" + ((new Date().getMonth()+1).toString()) + "-" + (new Date().getDate().toString());
    this.activeService.proc_status = "PENDING";
    this.activeService.status = "1";
  }

  insertService(str:string){
    if(str == 'bus'){
      this.activeService.proc_name = "Active Bus";
      if(this.activeService.name != "Monthly Bus"){
        this.activeService.max = this.activeService.amount;
      }else{
        this.activeService.max = "0";
      }
    }else{
      if(str == 'parking'){
        this.activeService.proc_name = "Active Parking";
        this.activeService.max = "0";
      }
    }
    this.activeService.id_user = this.authUser.getAuthusr().iduser;
    this.apiService.insertService(this.activeService).subscribe((str:string)=>{
      this.start();
    })
  }

  start(){
    this.bus = false;
    this.parking = false;
    this.apiService.getSearchEmployees({dp:this.authUser.getAuthusr().department, filter:'idemployees', value:this.route.snapshot.paramMap.get('id')}).subscribe((emp:employees[])=>{
      this.employee = emp[0]
      let prof:profiles = new profiles;
      prof.idprofiles = emp[0].id_profile;
      this.apiService.getProfile(prof).subscribe((profile:profiles[])=>{
        this.profile = profile[0];
      })
      this.apiService.getServices({id:this.employee.idemployees}).subscribe((srv:services[])=>{
        this.services = srv;
        this.services.forEach(service=>{
          if((service.name == "Monthly Bus" || service.name == "Daily Bus " + (new Date().getFullYear().toString()) + "-" + ((new Date().getMonth()+1).toString()) + "-" + (new Date().getDate().toString())) && service.status == '1'){
            this.bus = true;
          }
          if((service.name == "Car Parking" || service.name == "Motorcycle Parking") && service.status == "1"){
            this.parking = true;
          }
        })
      })
    })
  }

}
