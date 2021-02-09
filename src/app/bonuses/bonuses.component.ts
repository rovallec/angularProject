import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { employees_terminations } from "../process_templates";
import { employees } from '../fullProcess';

@Component({
  selector: 'app-bonuses',
  templateUrl: './bonuses.component.html',
  styleUrls: ['./bonuses.component.css']
})
export class BonusesComponent implements OnInit {  
  employees: employees[] = [];
  employees_terminations: employees_terminations[] = [];
  filter: string = null;
  value: string = null;
  searching: boolean = false;

  constructor(private apiService: ApiService, public router: Router, private authSrv: AuthServiceService) { }

  ngOnInit() {
    this.getAllTerminations();
  }

  getAllTerminations() {
    this.apiService.getAllTerminations({ filter: this.filter, value: this.value, department: 'terminations' }).subscribe((emp: employees_terminations[]) => {
      this.employees_terminations = emp;
    })
  }

  cancelSearch() {
    this.getAllTerminations();
    this.searching = false;
  }

  searchEmployee() {
    this.searching = true;
    this.apiService.getAllTerminations({ filter: this.filter, value: this.value, department: 'employee' }).subscribe((emp: employees_terminations[]) => {
      this.employees_terminations = emp;
    })
  }

  gotoProfile(emp: employees) {
    this.router.navigate(['./accProfile', emp.idemployees]);
  }

}
