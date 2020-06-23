import { Component, OnInit } from '@angular/core';
import { profiles } from '../profiles';
import { ApiService } from '../api.service';
import { ActivatedRoute } from '@angular/router';
import { attendences, attendences_adjustment, vacations, leaves } from '../process_templates';
import { AuthServiceService } from '../auth-service.service';

@Component({
  selector: 'app-hrprofiles',
  templateUrl: './hrprofiles.component.html',
  styleUrls: ['./hrprofiles.component.css']
})
export class HrprofilesComponent implements OnInit {

  profile:profiles[] = [new profiles()];

  attAdjudjment:attendences_adjustment = new attendences_adjustment;
  activeVacation:vacations = new vacations;

  todayDate:string = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");
  
  showAttAdjustments:attendences_adjustment[] = [];
  showVacations:vacations[] = [];
  showAttendences:attendences[] = [];
  leaves:leaves[] = [];

  activeEmp:string = null;
  editAdj:boolean = false;
  vacationAdd:boolean = false;
  addJ:boolean = false;
  editVac:boolean = true;
  
  earnVacations:number = 0;
  tookVacations:number = 0;
  availableVacations:number = 0;

  constructor(private apiService:ApiService, private route:ActivatedRoute, public authUser:AuthServiceService) { }

  ngOnInit() {
    this.todayDate = new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString().padStart(2, "0") + "-" + (new Date().getDate()).toString().padStart(2, "0");
    this.profile[0].idprofiles = this.route.snapshot.paramMap.get('id')
    this.apiService.getProfile(this.profile[0]).subscribe((prof:profiles[])=>{
      this.profile = prof;
    });
    this.getAttendences(this.todayDate);
    this.attAdjudjment.id_user = this.authUser.getAuthusr().iduser;
    this.attAdjudjment.date = this.todayDate;
    this.attAdjudjment.state = 'PENDING';
    this.attAdjudjment.status = 'PENDING';
    this.editAdj = false;
    
    this.vacationAdd = false;
    this.activeVacation.date = this.todayDate;
    this.activeVacation.id_department = this.authUser.getAuthusr().department;
    this.activeVacation.id_employee = this.route.snapshot.paramMap.get('id');
    this.activeVacation.id_user = this.authUser.getAuthusr().iduser;
    this.activeVacation.status = 'PENDING';
    this.getVacations();
  }

  getAttendences(dt:string){
      this.apiService.getAttendences({id:this.route.snapshot.paramMap.get('id'), date:"<= '" + dt + "'"}).subscribe((att:attendences[])=>{
        this.showAttendences = att;
        this.activeEmp = att[0].id_employee;
        this.showAttendences.forEach(att => {
          att.balance = (Number.parseFloat(att.worked_time) - Number.parseFloat(att.scheduled)).toString();
        })
        this.getAttAdjustemt();
      });
      this.editAdj = false;
  }

  getAttAdjustemt(){
    this.editAdj = false;
    this.apiService.getAttAdjustments({id:this.activeEmp}).subscribe((adj:attendences_adjustment[])=>{
      this.showAttAdjustments = adj;
    })
  }

  addJustification(att:attendences){
    this.editAdj = false;
    this.attAdjudjment.time_before = att.worked_time;
    this.attAdjudjment.id_attendence = att.idattendences;
    this.attAdjudjment.id_type = '2';
    this.attAdjudjment.id_employee = att.id_employee;
    this.attAdjudjment.id_department = this.authUser.getAuthusr().department;
    this.addJ = true;
  }

  insertAdjustment(){
    this.apiService.insertAttJustification(this.attAdjudjment).subscribe((str:string)=>{
      this.getAttAdjustemt();
    });
  }

  getRecordAdjustment(id_justification:string){
    this.apiService.getAttAdjustment({justify:id_justification}).subscribe((requested:attendences_adjustment)=>{
      this.attAdjudjment = requested;
    })
    this.addJ = true;
    this.editAdj = true;
  }

  cancelAdjustment(){
    this.addJ = false;
  }

  getVacations(){
    this.earnVacations = 0;
    this.tookVacations = 0;
    this.availableVacations = 0;
    this.apiService.getVacations({id:this.route.snapshot.paramMap.get('id')}).subscribe((res:vacations[])=>{
      this.showVacations = res;
      res.forEach(vac =>{
        if(vac.action == "Add"){
          this.earnVacations++;
        }
        if(vac.action == "Take"){
          this.tookVacations++;
        }
      })
      this.availableVacations = this.earnVacations - this.tookVacations;
    })
  }

  addVacation(action:string, type:string){
    this.vacationAdd = true;
    this.activeVacation.count = '1';
    this.activeVacation.action = action;
    this.activeVacation.id_type = type;
  }
  
  pushVacationDate(str:string){
    this.activeVacation.took_date = str;
  }

  cancelVacation(){
    this.vacationAdd = false;
    this.editVac = true;
  }

  insertVacation(){
    this.apiService.insertVacations(this.activeVacation).subscribe((str:any)=>{
      this.getVacations();
    })
    this.vacationAdd = false;
  }

  getVacation(vac:vacations){
    this.activeVacation = vac;
    this.vacationAdd = true;
    this.editVac = false;
  }

  getLeaves(){
    this.apiService.getLeaves({id:this.route.snapshot.paramMap.get('id')}).subscribe((leaves:leaves[])=>{
      this.leaves = leaves;
    })
  }
}
