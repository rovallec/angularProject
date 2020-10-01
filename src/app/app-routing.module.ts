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
import { HrdailyComponent } from './hrdaily/hrdaily.component';
import { AttendenceImportComponent } from './attendence-import/attendence-import.component';
import { AttritionReportComponent } from './attrition-report/attrition-report.component';
import { ActiveAnalysisComponent } from './active-analysis/active-analysis.component';
import { DpMaintenanceComponent } from './dp-maintenance/dp-maintenance.component';
import { SupExceptionsComponent } from './sup-exceptions/sup-exceptions.component';

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
    path:'hrdaily',
    component: HrdailyComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'attImport',
    component: AttendenceImportComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'attritionReport',
    component: AttritionReportComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'activeReport',
    component: ActiveAnalysisComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'dpMaintenance',
    component: DpMaintenanceComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'supExceptions',
    component: SupExceptionsComponent,
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
