import { Injectable } from '@angular/core';
import { ApiService } from './api.service';
import { users } from './users';
import { tap } from 'rxjs/operators';
import { observable, Observable, BehaviorSubject } from 'rxjs';
import { isNullOrUndefined } from 'util';

@Injectable({
  providedIn: 'root'
})
export class AuthServiceService {

  constructor(private apiServices:ApiService) { }

  authState:boolean = false;
  autUser:users = {
    iduser:'N/A',
    username:'N/A',
    department:'N/A',
    user_name:'N/A',
    valid:'N/A',
    password:'N/A',
    id_role:'N/A',
    signature:'N/A'
  }

  user = new BehaviorSubject<any>(null);

  changeAuth(state:boolean){
    this.authState = state;
  }

  isAuthenticated(){
    if(this.autUser.iduser == 'N/A'){
      this.autUser = {
        iduser: localStorage.getItem('iduser'),
        username:localStorage.getItem('username'),
        department:localStorage.getItem('department'),
        user_name:localStorage.getItem('user_name'),
        valid:localStorage.getItem('valid'),
        password:'N/A',
        id_role:localStorage.getItem('id_role'),
        signature:localStorage.getItem('signature')
      }
      if(this.autUser.iduser != 'N/A'){
        this.authState = true;
      }
    }
    return this.authState;
  }

  saveUsr(usr:users){
    this.autUser = usr;
  }

  getAuthusr(){
    if(this.autUser.iduser == 'N/A'){
      this.autUser = {
        iduser: localStorage.getItem('iduser'),
        username:localStorage.getItem('username'),
        department:localStorage.getItem('department'),
        user_name:localStorage.getItem('user_name'),
        valid:localStorage.getItem('valid'),
        password:'N/A',
        id_role:localStorage.getItem('id_role'),
        signature:localStorage.getItem('signature')
      }
    }
    return this.autUser;
  }
}
