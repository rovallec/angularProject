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
import { periods } from './process_templates';
import { PeriodsComponent } from './periods/periods.component';
import { PyhomeComponent } from './pyhome/pyhome.component';
import { AccprofilesComponent } from './accprofiles/accprofiles.component';
import { FhomeComponent } from './fhome/fhome.component';
import { FprofilesComponent } from './fprofiles/fprofiles.component';
import { WfmhomeComponent } from './wfmhome/wfmhome.component';
import { PyprofilesComponent } from './pyprofiles/pyprofiles.component';
import { ImportOtComponent } from './import-ot/import-ot.component';
import { OttrackerComponent } from './ottracker/ottracker.component';
import { AttendanceReportComponent } from './attendance-report/attendance-report.component';
import { IsrmanagerComponent } from './isrmanager/isrmanager.component';
import { AcphhomeComponent } from './acphhome/acphhome.component';
import { PeriodsPhComponent } from './periods-ph/periods-ph.component';
import { AccprofilesPhComponent } from './accprofiles-ph/accprofiles-ph.component';
import { BonusesComponent } from './bonuses/bonuses.component';
import { ClosingTkComponent } from './closing-tk/closing-tk.component';
import { ImportWavesComponent } from './import-waves/import-waves.component';
import { AccountingPoliciesComponent } from './accounting-policies/accounting-policies.component';
import { VacationsRepComponent } from "./vacations-rep/vacations-rep.component";
import { ItdashboardComponent } from './itdashboard/itdashboard.component';
import { ItprofilesComponent } from './itprofiles/itprofiles.component';
import { PaystubSendmailComponent } from './paystub-sendmail/paystub-sendmail.component';
import { WaveMaintenanceComponent } from './wave-maintenance/wave-maintenance.component';

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
    path:'periods/:id',
    component: PeriodsComponent,
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
    path:'pyhome',
    component: PyhomeComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'accProfile/:id',
    component: AccprofilesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'fhome',
    component: FhomeComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'pyprofiles/:id',
    component: PyprofilesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'fProfile/:id',
    component: FprofilesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'impoOt',
    component: ImportOtComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'timetracker',
    component: OttrackerComponent,
    canActivate:[AuthGuard]
  },
  {
    path: 'exportatt',
    component:AttendanceReportComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'isrManager',
    component: IsrmanagerComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'acphhome',
    component: AcphhomeComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'periods_ph/:id',
    component:PeriodsPhComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'accprofiles_ph/:id',
    component:AccprofilesPhComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'bonuses',
    component:BonusesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'accounting',
    component:AccountingPoliciesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'closing',
    component:ClosingTkComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'vacationsrep',
    component:VacationsRepComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'import-waves',
    component:ImportWavesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'ithome',
    component:ItdashboardComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'itprofiles/:id',
    component:ItprofilesComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'paystubs',
    component:PaystubSendmailComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'wavemaintenance',
    component:WaveMaintenanceComponent,
    canActivate:[AuthGuard]
  },
  {
    path:'',
    redirectTo:'/login',
    pathMatch:'full'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes),
            RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
