import { Component } from '@angular/core';
import { AuthServiceService } from './auth-service.service';
import {Form} from '@angular/forms'
import { users } from './users';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'main';

  autUsr:users = {
    iduser:'N/A',
    username:'N/A',
    department:'N/A',
    user_name:'N/A',
    valid:'N/A',
    password:'N/A',
    id_role:'N/A'
  };
  selectedOption:string = 'HOME';

  constructor(private authSrv:AuthServiceService){
  }

  getAuth(){
    return this.authSrv.isAuthenticated();
  }

  getSaved(){
    if(this.authSrv.isAuthenticated()){
      return this.authSrv.getAuthusr();
    }
  };
  SetSel(sel:string){
    this.selectedOption = sel;
  }
}
