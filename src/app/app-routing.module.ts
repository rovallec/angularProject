import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HomeComponent } from './home/home.component';
import { MiprofilesComponent } from "./miprofiles/miprofiles.component";
import { LoginComponent } from "./login/login.component";
import { AuthGuard } from './guard/auth-guard.service';
import { AccdashboardComponent } from './accdashboard/accdashboard.component';
import { RealtimeTrackComponent } from './realtime-track/realtime-track.component';
import { RecDailyconvergentReportComponent } from './rec-dailyconvergent-report/rec-dailyconvergent-report.component';
import { CalllistreportComponent } from './calllistreport/calllistreport.component';
import { HrhomeComponent } from './hrhome/hrhome.component';
import { HrprofilesComponent } from './hrprofiles/hrprofiles.component';

const routes:Routes = [
  {
    path:'rehome',
    component:HomeComponent,
    canActivate: [AuthGuard]
  },
  {  path:'hrhome',
  component:HrhomeComponent,
  canActivate: [AuthGuard]
  },
  {
    path:'realtime',
    component:RealtimeTrackComponent,
    canActivate: [AuthGuard]
  },
  {
    path:'dilyconvergent',
    component:RecDailyconvergentReportComponent,
    canActivate: [AuthGuard]
  },
  {
    path:'classlist',
    component:CalllistreportComponent,
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
    path:'hrprofiles/:id',
    component: HrprofilesComponent,
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
