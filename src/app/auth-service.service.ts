import { Injectable } from '@angular/core';
import { users } from './users';

@Injectable({
  providedIn: 'root'
})
export class AuthServiceService {

  constructor() { }

  authState:boolean = false;
  autUser:users = {
    iduser:'N/A',
    username:'N/A',
    department:'N/A',
    user_name:'N/A',
    valid:'N/A',
    password:'N/A',
    id_role:'N/A'
  }

  changeAuth(state:boolean){
    this.authState = state;
  }

  isAuthenticated(){
    return this.authState;
  }

  saveUsr(usr:users){
    this.autUser = usr;
  }

  getAuthusr(){
    return this.autUser;
  }
}
