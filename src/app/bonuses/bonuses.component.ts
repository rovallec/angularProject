import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { employees } from '../fullProcess';

@Component({
  selector: 'app-bonuses',
  templateUrl: './bonuses.component.html',
  styleUrls: ['./bonuses.component.css']
})
export class BonusesComponent implements OnInit {
  employees: employees[] = [];
  filter: string = null;
  value: string = null;
  searching: boolean = false;

  constructor(private apiService: ApiService, public router: Router, private authSrv: AuthServiceService) { }

  ngOnInit() {
    this.getAllEmployees();
  }

  getAllEmployees() {
    this.apiService.getallEmployees({ department: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
  }

  cancelSearch() {
    this.getAllEmployees();
    this.searching = false;
  }

  searchEmployee() {
    this.searching = true;
    this.apiService.getSearchEmployees({ filter: this.filter, value: this.value, dp: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
  }

  gotoProfile(emp: employees) {
    this.router.navigate(['./accProfile', emp.idemployees]);
  }

}
