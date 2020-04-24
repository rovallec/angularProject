import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HomeComponent } from './home/home.component';
import { MiprofilesComponent } from "./miprofiles/miprofiles.component";
import { LoginComponent } from "./login/login.component";
import { AuthGuard } from './guard/auth-guard.service';
import { AccdashboardComponent } from './accdashboard/accdashboard.component';

const routes:Routes = [
  {
    path:'rehome',
    component:HomeComponent,
    canActivate: [AuthGuard]
  },
  {
    path:'achome',
    component:AccdashboardComponent,
    canActivate: [AuthGuard]
  },
  {
    path:'login',
    component:LoginComponent
  },
  {
    path:'profiles/:id',
    component: MiprofilesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'',
    redirectTo:'/login',
    pathMatch:'full'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
