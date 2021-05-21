import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

import { HttpClientModule } from '@angular/common/http';
import { HomeComponent } from './home/home.component';
import { MiprofilesComponent } from './miprofiles/miprofiles.component';

import { FormsModule } from '@angular/forms';
import { LoginComponent } from './login/login.component';

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
import { VacationsRepComponent } from './vacations-rep/vacations-rep.component';
import { OpsViewComponent } from './ops-view/ops-view.component'

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    MiprofilesComponent,
    LoginComponent,
    AccdashboardComponent,
    RealtimeTrackComponent,
    RecDailyconvergentReportComponent,
    CalllistreportComponent,
    HrhomeComponent,
    HrprofilesComponent,
    HrdailyComponent,
    AttendenceImportComponent,
    AttritionReportComponent,
    ActiveAnalysisComponent,
    DpMaintenanceComponent,
    SupExceptionsComponent,
    PeriodsComponent,
    PyhomeComponent,
    AccprofilesComponent,
    FhomeComponent,
    FprofilesComponent,
    WfmhomeComponent,
    PyprofilesComponent,
    ImportOtComponent,
    OttrackerComponent,
    AttendanceReportComponent,
    IsrmanagerComponent,
    AcphhomeComponent,
    PeriodsPhComponent,
    AccprofilesPhComponent,
    BonusesComponent,
    ClosingTkComponent,
    ImportWavesComponent,
    AccountingPoliciesComponent,
    VacationsRepComponent,
    OpsViewComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [AuthGuard],
  bootstrap: [AppComponent]
})
export class AppModule { }
