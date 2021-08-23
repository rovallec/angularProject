import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts, clients, periods, roster_types } from '../process_templates';

@Component({
  selector: 'app-rostermaintenance',
  templateUrl: './rostermaintenance.component.html',
  styleUrls: ['./rostermaintenance.component.css']
})
export class RostermaintenanceComponent implements OnInit {

  constructor(public apiServices:ApiService) { }

  selectedClient: string = null;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;
  clients: clients[] = [];
  isSearching:boolean = false;
  period_days:string[] = [];
  roster_tags:string[] = [];
  selected_types:roster_types[] = [];

  ngOnInit() {
    this.apiServices.getRoster_tags().subscribe((str:string[])=>{
      this.roster_tags = str;
      this.getRosters_templates(this.roster_tags[0]);
    })
    this.apiServices.getPeriods().subscribe((periods:periods[])=>{
      let dt:number = new Date(periods[periods.length - 1].start).getTime();
      while (dt < new Date(periods[periods.length - 1].end).getTime()){
        this.period_days.push(new Date(dt).getDate().toFixed(0));
        dt = dt + (1000*3600*24);
      }
    })
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.selectedAccount = this.accounts[0];
    })
  }


  setAccount(acc: accounts) {
    this.isSearching = false;
    this.selectedAccount = acc;
  }

  getRosters_templates(str){
    this.apiServices.getRosterTemplates(str).subscribe((rosts:roster_types[])=>{
      this.selected_types = rosts;
    })
  }
}
