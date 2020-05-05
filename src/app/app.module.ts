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
import { HrhomeComponent } from './hrhome/hrhome.component'

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
    HrhomeComponent
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
