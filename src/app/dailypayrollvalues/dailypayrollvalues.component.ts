import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { accounts, clients, periods } from '../process_templates';

@Component({
  selector: 'app-dailypayrollvalues',
  templateUrl: './dailypayrollvalues.component.html',
  styleUrls: ['./dailypayrollvalues.component.css']
})
export class DailypayrollvaluesComponent implements OnInit {

  periods: periods[] = [];
  selectedPeriod: periods = new periods;
  clients: clients[] = [];
  selectedClient: string = null;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;

  constructor(public apiService: ApiService, public route: ActivatedRoute, public authService: AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.getPeriods();
      this.apiService.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })
  };


  getPeriods() {
    this.apiService.getPeriods().subscribe((prd: periods[]) => {
      prd.forEach(per => {
        if (per.status=='0'){
          per.status = 'CLOSED';
        } else if (per.status=='1') {
          per.status = 'OPEN';
        }else if(per.status == '3'){
          per.status = 'FROZEN';
        }
      })
      this.periods = prd.filter(per => per.type_period == '0');
       this.periods = this.periods.reverse();
    });
  }

  setSelPeriod(Aper: periods) {
    this.selectedPeriod = Aper;
  }

  async exportReport() {
    try {
      window.open(this.apiService.PHP_API_SERVER + "/phpscripts/exportDailyPayrollValues.php?period=" + this.selectedPeriod.idperiods + "&account=" + this.selectedAccount.idaccounts + "&accname=" + this.selectedAccount.name, "_blank")
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc.filter(account => account.id_client == cl );
      this.setAccount(this.accounts[0]);
    })
  }

  setAccount(acc: accounts) {
    this.selectedAccount = acc;
  }

}
