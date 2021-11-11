import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { accounts, clients, holiday, Fecha, StringType, selectedOption } from '../process_templates';
import { lodash } from 'node_modules/lodash';


@Component({
  selector: 'app-holidays',
  templateUrl: './holidays.component.html',
  styleUrls: ['./holidays.component.css']
})
export class HolidaysComponent implements OnInit {

  constructor(public apiService: ApiService, public route: ActivatedRoute, public authService:AuthServiceService) { }
  holidays: holiday[] = [];
  selectedHoliday: holiday = new holiday;
  year: number[] = [];
  selectedYear: number = 0;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;
  clients: clients[] = [];
  selectedClient: string = null;
  state: StringType = 'Browse';
  typeHoliday: selectedOption[] = [];


  ngOnInit() {
    this.start();
  }

  start() {
    let actualYear: number = new Date().getFullYear();
    this.addTypes();
    this.year = [];
    for (let i = 2000; i <= 2030; i++) {
      this.year.push(i);
    }
    this.setYear(actualYear);

    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc;
    });
    this.getClients();
    this.getHolidays();
  }

  setYear(aYear: number) {
    this.selectedYear = aYear;
    this.getHolidays();
  }

  addTypes() {
    let type: selectedOption = new selectedOption(1, 'Feriados Nacionales');
    this.typeHoliday.push(type);
    type = new selectedOption(2, 'Asuetos Nacionales');
    this.typeHoliday.push(type);
    type = new selectedOption(3, 'Asuetos de la cuenta');
    this.typeHoliday.push(type);
    type = new selectedOption(4, 'Feriados Internacionales');
    this.typeHoliday.push(type);
    type = new selectedOption(5, 'Asuetos Internacionales');
    this.typeHoliday.push(type);
    type = new selectedOption(6, 'Otros');
    this.typeHoliday.push(type);
  };

  getClients() {
    this.apiService.getClients().subscribe((cl: clients[]) => {
      this.clients = cl;
      this.setClient(cl[0].idclients);
    })
  }

  getHolidays() {
    this.holidays = [];
    this.apiService.getHolidays(this.selectedAccount).subscribe((holi: holiday[]) => {
      holi.forEach(h => {
        if ((h.year == this.selectedYear) && (h.id_account == this.selectedAccount.idaccounts)) {
          h.state = 'Browse';
          this.holidays.push(h);
        }
      })
      // Ordena el objeto por campos
      this.holidays = this.holidays.sort((a, b) => a.date.localeCompare(b.date));
    });
  }

  editHoliday(AHoliday: holiday) {
    this.state = 'Edit';
    this.selectedHoliday = AHoliday;
    this.selectedHoliday.state = this.state;
  }

  deleteHoliday(aHoliday: holiday) {
    this.selectedHoliday = aHoliday;
    try {
      this.apiService.deleteHolidays(this.selectedHoliday).subscribe((str: string) => {
        if (str !== '') {
          console.log(str);
        }
      });
    } finally {
      this.state = 'Browse';
      this.start();
    }
  }

  cancelHoliday(aHoliday: holiday) {
    this.selectedHoliday = aHoliday;
    try {
      if (aHoliday.idholidays == null) {
        this.holidays.slice(this.holidays.indexOf(aHoliday), 1);
      }
    } finally {
      this.state = 'Browse';
      this.selectedHoliday.state = this.state;
    }
  }

  saveHoliday(event) {
    this.selectedHoliday = event;
    this.selectedHoliday.year = new Date(this.selectedHoliday.date).getFullYear();
    try {
      if (this.state == 'Insert') {
        this.apiService.insertHolidays(this.selectedHoliday).subscribe((str: string) => {
          if (str !== '') {
            console.log(str);
          }
        })
      } else if (this.state == 'Edit') {
        this.apiService.updateHolidays(this.selectedHoliday).subscribe((str: string) => {
          if (str !== '') {
            console.log(str);
          }
        })
      }
    } finally {
      this.state = 'Browse';
      this.selectedHoliday.state = this.state;
      this.start();
    }
  }

  addHoliday() {
    let today: Fecha = new Fecha;
    this.selectedHoliday = new holiday;
    this.state = 'Insert';
    this.selectedHoliday.state = this.state;
    this.selectedHoliday.id_account = this.selectedAccount.idaccounts;
    this.selectedHoliday.name = '';
    this.selectedHoliday.type = '';
    this.selectedHoliday.date = today.today;
    this.selectedHoliday.year = Number(today.year);
    this.holidays.push(this.selectedHoliday);
  }

  setClient(cl: string) {
    this.accounts = [];
    this.selectedClient = cl;
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.setAccount(this.accounts[0]);
    })
  }

  setAccount(acc: accounts) {
    this.selectedAccount = acc;
    this.getHolidays();
  }

  setType(event) {

  }

  onKeyUpEvent($event: KeyboardEvent) {
    let charCode = String.fromCharCode($event.which).toLowerCase();

    if ($event.ctrlKey && charCode === 'g') {
      this.saveHoliday(this.selectedHoliday);
    } else if ($event.ctrlKey && charCode === 'e') {
      this.editHoliday(this.selectedHoliday);
    } else if ($event.ctrlKey && charCode === 'd') {
      this.deleteHoliday(this.selectedHoliday);
    }
  }
}
