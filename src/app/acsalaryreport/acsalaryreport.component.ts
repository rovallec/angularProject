import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, salaryReport } from '../fullProcess';
import { accounts } from '../process_templates';

@Component({
  selector: 'app-acsalaryreport',
  templateUrl: './acsalaryreport.component.html',
  styleUrls: ['./acsalaryreport.component.css']
})
export class AcsalaryreportComponent implements OnInit {

  filter: string = 'All';
  value: string = '';
  searching: boolean = false;
  salaryReport: salaryReport[] = [];
  accounts: accounts[] = [];

  constructor(private authService: AuthServiceService, private apiService: ApiService, private route: Router) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.getAllaccounts();
    this.getSalaryReport();
  }

  getSalaryReport() {
    this.salaryReport = [];
    this.apiService.getSalaryReport({ filter: this.filter, value: this.value }).subscribe((salrep: salaryReport[]) => {
      this.salaryReport = salrep;
    })
  }

  async exportSalaryReport() {
    var url = this.apiService.PHP_API_SERVER + "/phpscripts/exportSalaryReport.php?filter=" + this.filter + "&value=" + this.value;
    try {
      window.open(url, "_blank");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  clearFilter() {
    this.value = '';
    this.filter = 'All';
  }

  getAllaccounts() {
    this.apiService.getAccounts().subscribe((ac: accounts[]) => {
      this.accounts = ac;
    })
  }
}
