import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { employees_Bonuses } from "../process_templates";
import { employees } from '../fullProcess';

@Component({
  selector: 'app-bonuses',
  templateUrl: './bonuses.component.html',
  styleUrls: ['./bonuses.component.css']
})
export class BonusesComponent implements OnInit {  
  employees: employees[] = [];
  employees_terminations: employees_Bonuses[] = [];
  filter: string = null;
  filterBonuses: string = '1';
  value: string = null;
  searching: boolean = false;

  constructor(private apiService: ApiService, public router: Router, private authSrv: AuthServiceService) { }

  ngOnInit() {
    this.getAllBonuses();
  }

  getAllBonuses() {
    this.apiService.getAllBonuses({ filter: this.filter, value: this.value, department: 'terminations', filterBonuses: this.filterBonuses }).subscribe((emp: employees_Bonuses[]) => {
      this.employees_terminations = emp;
    })
  }

  searchType() {
    if (this.searching) {
      this.searchEmployee();
    } else {
      this.getAllBonuses();
    }
  }

  cancelSearch() {
    this.getAllBonuses();
    this.searching = false;
  }

  searchEmployee() {
    this.searching = true;
    this.apiService.getAllBonuses({ filter: this.filter, value: this.value, department: 'employee', filterBonuses: this.filterBonuses }).subscribe((emp: employees_Bonuses[]) => {
      this.employees_terminations = emp;
    })
  }

  gotoProfile(emp: employees) {
    this.router.navigate(['./accProfile', emp.idemployees]);
  }

}
