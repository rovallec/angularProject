import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ApiService } from '../api.service'
import { profiles } from '../profiles';
import { process } from '../process';
import { AuthServiceService } from '../auth-service.service';
import { fullPreapproval, fullApplyentcontact, fullSchedulevisit, fullDoc_Proc, testRes, queryDoc_Proc, uploaded_documetns, new_hire, vew_hire_process, coincidences } from '../fullProcess';
import { process_templates, waves_template, hires_template, schedules } from '../process_templates';
import { applyent_contact, preApproval, schedule_visit } from '../addTemplate';
import { stringify, parse } from 'querystring';
import { users } from '../users';
import { Router } from '@angular/router';

@Component({
  selector: 'app-miprofiles',
  templateUrl: './miprofiles.component.html',
  styleUrls: ['./miprofiles.component.css']
})
export class MiprofilesComponent implements OnInit {

  constructor(private apiservice: ApiService, private route: ActivatedRoute, private authSrv: AuthServiceService, public router: Router) { }

  coincidences: coincidences[] = [new coincidences];

  toggle_contact: boolean = true;
  toggle_profile: boolean = false;
  toggle_job: boolean = true;
  toggle_details: boolean = true;
  toggle_emergency: boolean = true;
  toggle_medical: boolean = true;
  toggle_education: boolean = true;

  hires_number: string;
  users_toAssign: users[];
  user_toAssign: users;

  show_newHire: vew_hire_process = {
    user: 'N/A',
    notes: 'N/A',
    prc_date: 'N/A',
    nearsol_id: 'N/A',
    wave: 'N/A',
    reports_to: 'N/A',
  };

  assign_schedules: schedules[] = [new schedules()];
  selected_schedule: schedules = new schedules;

  waves_select: waves_template[];
  filter_hires: hires_template[];
  selected_wave: waves_template = new waves_template;

  fullApplyentContact: fullApplyentcontact[] = [{
    idprocesses: 'N/A',
    role: 'N/A',
    profile: 'N/A',
    processName: 'N/A',
    description: 'N/A',
    prc_date: 'N/A',
    user: 'N/A',
    status: 'N/A',
    notes: 'N/A',
    result: 'N/A',
    method: 'N/A'
  }];

  fullScheduleVisit: fullSchedulevisit[] = [{
    idprocesses: null,
    id_role: null,
    id_profile: null,
    name: null,
    description: null,
    prc_date: null,
    status: null,
    id_user: null,
    vehicle: null,
    plate: null,
    dateTime: null,
    attendance: null,
    color: null,
    brand: null
  }];

  fullTestResult: queryDoc_Proc[] = [{
    idprocesses: 'N/A',
    id_role: 'N/A',
    id_profile: 'N/A',
    name: 'N/A',
    description: 'N/A',
    prc_date: 'N/A',
    username: 'N/A',
    status: 'N/A',
    results: 'N/A',
    notes: 'N/A',
    english_test: 'N/A',
    typing_test: 'N/A',
    psicometric_test: 'N/A',
    IGSS: 'N/A',
    IRTRA: 'N/A',
    source:null,
    post:null,
    referrer:null,
    about:null
  }];

  toggleEdit_Status: boolean = false;

  profile_full_name: string;

  ifProc: string = 'N/A';
  procAdd: boolean = false;
  procAddnew: boolean = false;

  resProcess: fullPreapproval[] = [{
    idprocesses: 'N/A',
    process_name: 'N/A',
    description: 'N/A',
    prc_date: 'N/A',
    role_name: 'N/A',
    user_name: 'N/A',
    id_profile: 'N/A'
  }];

  fullProcess: fullPreapproval = {
    idprocesses: 'N/A',
    process_name: 'N/A',
    description: 'N/A',
    prc_date: 'N/A',
    role_name: 'N/A',
    user_name: 'N/A',
    id_profile: 'N/A',
  };

  processes: process[] = [{
    idprocesses: null,
    id_role: null,
    id_profile: null,
    name: null,
    descritpion: null,
    prc_date: null,
    status: null,
    id_user: null,
    user_name: null
  }];
  templates: process_templates[] = [];

  queryInfo: process = {
    idprocesses: 'N/A',
    id_role: 'N/A',
    id_profile: 'N/A',
    name: 'N/A',
    descritpion: 'N/A',
    prc_date: 'N/A',
    status: 'N/A',
    id_user: 'N/A',
    user_name: null
  };

  prof_to_get: profiles = {
    idprofiles: 'N/A',
    tittle: 'N/A',
    first_name: 'N/A',
    second_name: 'N/A',
    first_lastname: 'N/A',
    second_lastname: 'N/A',
    day_of_birth: 'N/A',
    nationality: 'N/A',
    marital_status: 'N/A',
    dpi: 'N/A',
    nit: 'N/A',
    iggs: 'N/A',
    irtra: 'N/A',
    status: 'N/A',
    idcontact_details: 'N/A',
    id_profile: 'N/A',
    primary_phone: 'N/A',
    secondary_phone: 'N/A',
    address: 'N/A',
    city: 'N/A',
    email: 'N/A',
    idjob_histories: 'N/A',
    company: 'N/A',
    date_joining: 'N/A',
    position: 'N/A',
    reference_name: 'N/A',
    reference_lastname: 'N/A',
    reference_position: 'N/A',
    reference_email: 'N/A',
    reference_phone: 'N/A',
    working: 'N/A',
    idprofile_details: 'N/A',
    english_level: 'N/A',
    transport: 'N/A',
    start_date: 'N/A',
    unavialable_days: 'N/A',
    marketing_campaing: 'N/A',
    first_lenguage: 'N/A',
    second_lenguage: 'N/A',
    third_lenguage: 'N/A',
    idemergency_details: 'N/A',
    emergency_first_name: 'N/A',
    emergency_second_name: 'N/A',
    emergency_first_lastname: 'N/A',
    emergency_second_lastname: 'N/A',
    phone: 'N/A',
    relationship: 'N/A',
    idmedical_details: 'N/A',
    medical_treatment: 'N/A',
    medical_prescription: 'N/A',
    ideducation_details: 'N/A',
    current_level: 'N/A',
    further_education: 'N/A',
    currently_studing: 'N/A',
    institution_name: 'N/A',
    degree: 'N/A',
    iddocuments: 'N/A',
    doc_type: 'N/A',
    doc_path: 'N/A'
  };

  prof_getted: profiles[] = [{
    idprofiles: 'N/A',
    tittle: 'N/A',
    first_name: 'N/A',
    second_name: 'N/A',
    first_lastname: 'N/A',
    second_lastname: 'N/A',
    day_of_birth: 'N/A',
    nationality: 'N/A',
    marital_status: 'N/A',
    dpi: 'N/A',
    nit: 'N/A',
    iggs: 'N/A',
    irtra: 'N/A',
    status: 'N/A',
    idcontact_details: 'N/A',
    id_profile: 'N/A',
    primary_phone: 'N/A',
    secondary_phone: 'N/A',
    address: 'N/A',
    city: 'N/A',
    email: 'N/A',
    idjob_histories: 'N/A',
    company: 'N/A',
    date_joining: 'N/A',
    position: 'N/A',
    reference_name: 'N/A',
    reference_lastname: 'N/A',
    reference_position: 'N/A',
    reference_email: 'N/A',
    reference_phone: 'N/A',
    working: 'N/A',
    idprofile_details: 'N/A',
    english_level: 'N/A',
    transport: 'N/A',
    start_date: 'N/A',
    unavialable_days: 'N/A',
    marketing_campaing: 'N/A',
    first_lenguage: 'N/A',
    second_lenguage: 'N/A',
    third_lenguage: 'N/A',
    idemergency_details: 'N/A',
    emergency_first_name: 'N/A',
    emergency_second_name: 'N/A',
    emergency_first_lastname: 'N/A',
    emergency_second_lastname: 'N/A',
    phone: 'N/A',
    relationship: 'N/A',
    idmedical_details: 'N/A',
    medical_treatment: 'N/A',
    medical_prescription: 'N/A',
    ideducation_details: 'N/A',
    current_level: 'N/A',
    further_education: 'N/A',
    currently_studing: 'N/A',
    institution_name: 'N/A',
    degree: 'N/A',
    iddocuments: 'N/A',
    doc_type: 'N/A',
    doc_path: 'N/A'
  }];

  documents_view: string[][];

  procAddition: string;

  description_value: string = '';

  todayDate = new Date;

  addApplyentContact: applyent_contact = {
    idprocesses: 'N/A',
    id_role: 'N/A',
    id_profile: 'N/A',
    processName: 'N/A',
    description: 'N/A',
    prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
    id_user: 'N/A',
    status: 'N/A',
    notes: ' ',
    result: ' ',
    method: ' ',
  };

  addScheduleVisit: schedule_visit = {
    idprocesses: null,
    id_role: null,
    id_profile: null,
    name: null,
    descritpion: null,
    prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
    status: null,
    id_user: null,
    vehicle: null,
    plate: null,
    dateTime: null,
    color: null,
    brand: null
  }

  addDoc_proc: fullDoc_Proc[] =
    [{
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'English Test',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'Typing Test',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'Pscicometric Test',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'Listening Test',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'Criminal Records',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'Utility Bill',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'City Tax',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'IGSS',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'IRTRA',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'References',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    },
    {
      idprocesses: ' ',
      id_role: ' ',
      id_profile: ' ',
      name: ' ',
      description: ' ',
      prc_date: String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status: ' ',
      id_user: ' ',
      iddocuments: ' ',
      doc_res: ' ',
      doc_type: 'Infonet Authorization',
      doc_path: ' ',
      result: ' ',
      notes: ' ',
      source:null,
      post:null,
      referrer:null,
      about:null
    }
  ];

  prof_doc: uploaded_documetns[] = [{
    iddocuments: 'N/A',
    id_profile: 'N/A',
    id_process: 'N/A',
    doc_type: 'N/A',
    doc_path: 'N/A',
    active: 'N/A',
  }];

  toggleAddend: boolean = true;

  loggUser: string;

  todayDays: string[] = [
    'Day',
  ];

  todayMonths: string[] = [
    'Month',
  ];

  todayYears: string[] = [
    'Year',
  ];

  todayHour: string[] = [
    'hh',
  ];

  todayMin: string[] = [
    'mm',
  ];

  pross: boolean[] = [
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false
  ];

  selDay: string = 'Day';
  selMonth: string = 'Month';
  selYear: string = 'Year';
  selHour: string = 'hh';
  selMin: string = 'mm';

  ngOnInit() {
    this.getId();
    this.queryInfo.id_profile = this.route.snapshot.paramMap.get('id');
    this.queryInfo.id_role = this.authSrv.getAuthusr().id_role;
    this.loggUser = this.authSrv.getAuthusr().user_name;
    this.getProcesses();
    this.ifProc == "N/A";

    for (let index = 1; index < 32; index++) {
      this.todayDays[index] = index.toString();
    };

    for (let i = 1; i < 13; i++) {
      this.todayMonths[i] = i.toString();
    };

    for (let index = 1; index < 10; index++) {
      this.todayYears[index] = String(this.todayDate.getFullYear() - index + 1);
    };

    for (let index = 0; index < 25; index++) {
      this.todayHour[index + 1] = String(index).padStart(2, '0');
    };

    for (let index = 0; index < 61; index++) {
      this.todayMin[index + 1] = String(index).padStart(2, '0');
    };

    /**this.documents_view = [
      ["Second Interview",
        "0",
        "N/A"],
      [
        "Frist Interview",
        "0",
        "N/A"
      ],
      [
        "Application",
        "0",
        "N/A"
      ],
      [
        "Job Offer",
        "0",
        "N/A"
      ],
      [
        "Resume",
        "0",
        "N/A"
      ],
      [
        "High School Diploma",
        "0",
        "N/A"
      ],
      [
        "References Letters",
        "0",
        "N/A"
      ],
      [
        "DPI",
        "0",
        "N/A"
      ],
      [
        "NIT",
        "0",
        "N/A"
      ],
      [
        "Police Records",
        "0",
        "N/A"
      ],
      [
        "Police Verification",
        "0",
        "N/A"
      ],
      [
        "Criminal Records",
        "0",
        "N/A"
      ],
      [
        "Criminal Verification",
        "0",
        "N/A"
      ],
      [
        "Utility Bill",
        "0",
        "N/A"
      ],
      [
        "City Tax",
        "0",
        "N/A"
      ],
      [
        "Tests",
        "0",
        "N/A"
      ],
      [
        "Disc",
        "0",
        "N/A"
      ],
      [
        "Infonet Authorization",
        "0",
        "N/A"
      ],
      [
        "Infonet",
        "0",
        "N/A"
      ],
      [
        "Background Check",
        "0",
        "N/A"
      ],
      [
        "IGSS",
        "0",
        "N/A"
      ],
      [
        "IRTRA",
        "0",
        "N/A"
      ]]; */
  }

  toggle_contact_edit() {
    this.toggle_contact = !this.toggle_contact;
  }

  toggle_education_edit() {
    this.toggle_education = !this.toggle_education;
  }

  toggle_profile_edit() {
    this.toggle_profile = true;
  }

  end_editProfile(){
    this.prof_getted[0].first_name = this.profile_full_name.split(' ')[0];
    this.prof_getted[0].second_name = this.profile_full_name.split(' ')[1];
    this.prof_getted[0].first_lastname = this.profile_full_name.split(' ')[2];
    this.prof_getted[0].second_lastname = this.profile_full_name.split(' ')[3];
    
    this.apiservice.updateProfile(this.prof_getted[0]).subscribe((prof:profiles)=>{
      this.prof_getted[0] = prof;
    });

    this.toggle_profile = false;
  }

  toggle_job_edit() {
    this.toggle_job = !this.toggle_job;
  }

  toggle_details_edit() {
    this.toggle_details = !this.toggle_details;
  }

  toggle_emergency_edit() {
    this.toggle_emergency = !this.toggle_emergency;
  }

  toggle_medical_edit() {
    this.toggle_medical = !this.toggle_medical;
  }

  getProcesses() {
    this.apiservice.getProcesses(this.queryInfo).subscribe((processes_g: process[]) => {
      this.processes = processes_g;
      processes_g.forEach(pro => {
        if (pro.name === "Candidate Contact") { this.pross[0] = true };
        if (pro.name === "Schedule Visit") { this.pross[1] = true };
        if (pro.name === "Test Results") { this.pross[2] = true };
        if (pro.name === "First Interview") { this.pross[3] = true };
        if (pro.name === "Second Interview") { this.pross[4] = true };
        if (pro.name === "Documentation") { this.pross[5] = true };
        if (pro.name === "Background Check") { this.pross[6] = true };
        if (pro.name === "Drug Test") { this.pross[7] = true };
        if (pro.name === "Campaign Assignation") { this.pross[8] = true };
        if (pro.name === "New Hire") { this.pross[9] = true };
      });
    });
  }

  getProcess(proc: any) {
    this.toggleAddend = true;
    this.procAddnew = false;
    this.ifProc = proc.name;
    this.fullProcess.process_name = proc.name;
    this.fullProcess.idprocesses = proc.idprocesses;
    if (proc.name == "Pre-Approval") {
      this.apiservice.getFullprocess(this.fullProcess).subscribe((fullProc: any[]) => {
        this.resProcess = fullProc;
      });
      this.description_value = this.resProcess[0].description;
    };

    if (proc.name == "Candidate Contact") {
      this.apiservice.getFullApplyentContact(this.fullProcess).subscribe((fullProcCont: fullApplyentcontact[]) => {
        this.fullApplyentContact = fullProcCont;
      });
    };

    if (proc.name == "Reception Notes") {
      this.apiservice.getFullApplyentContact(this.fullProcess).subscribe((fullProcCont: fullApplyentcontact[]) => {
        this.fullApplyentContact = fullProcCont;
      });
    };

    if (proc.name == "Candidate Rejection") {
      this.fullProcess.id_profile = this.prof_to_get.idprofiles
      this.apiservice.getFullApplyentContact(this.fullProcess).subscribe((fullProcCont: fullApplyentcontact[]) => {
        this.fullApplyentContact = fullProcCont;
      });
    };

    if (proc.name == "New Hire") {
      this.fullProcess.id_profile = this.prof_to_get.idprofiles
      this.apiservice.getFullApplyentContact(this.fullProcess).subscribe((fullProcCont: fullApplyentcontact[]) => {
        this.fullApplyentContact = fullProcCont;
      });
    };

    if (proc.name == "Schedule Visit") {
      this.apiservice.getFullScheduleVisit(this.fullProcess).subscribe((fullProcCont: fullSchedulevisit[]) => {
        this.fullScheduleVisit = fullProcCont;
      });
    };

    if (proc.name == "Test Results") {
      this.apiservice.getFullTestResults(this.fullProcess).subscribe((fullprocCont: queryDoc_Proc[]) => {
        this.fullTestResult = fullprocCont;
        this.apiservice.getDocProc(this.fullTestResult[0]).subscribe((docProc: uploaded_documetns[]) => {
          this.prof_doc = docProc;
        })
      });
    }

    if (proc.name == "Drug Test") {
      this.apiservice.getFullTestResults(this.fullProcess).subscribe((fullprocCont: queryDoc_Proc[]) => {
        this.fullTestResult = fullprocCont;
        this.apiservice.getDocProc(this.fullTestResult[0]).subscribe((docProc: uploaded_documetns[]) => {
          this.prof_doc = docProc;
        })
      });
    }

    if (proc.name == "First Interview") {
      this.apiservice.getFullTestResults(this.fullProcess).subscribe((fullprocCont: queryDoc_Proc[]) => {
        this.fullTestResult = fullprocCont;
        this.apiservice.getDocProc(this.fullTestResult[0]).subscribe((docProc: uploaded_documetns[]) => {
          this.prof_doc = docProc;
        })
      });
    }

    if (proc.name == "Second Interview") {
      this.apiservice.getFullTestResults(this.fullProcess).subscribe((fullprocCont: queryDoc_Proc[]) => {
        this.fullTestResult = fullprocCont;
        this.apiservice.getDocProc(this.fullTestResult[0]).subscribe((docProc: uploaded_documetns[]) => {
          this.prof_doc = docProc;
        })
      })
    }

    if (proc.name == "Documentation") {
      this.apiservice.getFullTestResults(this.fullProcess).subscribe((fullprocCont: queryDoc_Proc[]) => {
        this.fullTestResult = fullprocCont;
        this.apiservice.getDocProc(this.fullTestResult[0]).subscribe((docProc: uploaded_documetns[]) => {
          this.prof_doc = docProc;
        })
      });
    }

    if (proc.name == "Background Check") {
      this.apiservice.getFullTestResults(this.fullProcess).subscribe((fullprocCont: queryDoc_Proc[]) => {
        this.fullTestResult = fullprocCont;
        this.apiservice.getDocProc(this.fullTestResult[0]).subscribe((docProc: uploaded_documetns[]) => {
          this.prof_doc = docProc;
        })
      });
    }

    if (proc.name == "Legal Documentation") {
      this.apiservice.getFullTestResults(this.fullProcess).subscribe((fullprocCont: queryDoc_Proc[]) => {
        this.fullTestResult = fullprocCont;
        this.apiservice.getDocProc(this.fullTestResult[0]).subscribe((docProc: uploaded_documetns[]) => {
          this.prof_doc = docProc;
        })
      });
    }

    if (proc.name == "Campaign Assignation") {
      this.apiservice.getNewHireProc(this.fullProcess).subscribe((newHire: vew_hire_process) => {
        this.show_newHire = newHire;
      })
    }
  };

  view_doc(doc_no: number) {
    window.open("http://168.194.75.13/phpscripts/uploads/" + this.prof_doc[doc_no].doc_path, '_blank');
  }

  getTemplates() {
    this.procAdd = !this.procAdd
    if (this.procAdd) {
      this.apiservice.getProctemplates(this.queryInfo).subscribe((temp: process_templates[]) => {
        this.templates = temp;
      });
    } else {
      this.getProcesses();
    }

  }

  newProctemplate(procName: string) {
    if(procName == "Test Results"){
      this.addDoc_proc[0].doc_type = "English Test";
      this.addDoc_proc[1].doc_type = "Listening Test";
      this.addDoc_proc[2].doc_type = "Typing Test";
      this.addDoc_proc[3].doc_type = "Psicometric Test";
    }
    if (procName == 'Drug Test') {
      this.addDoc_proc[0].doc_type = "Drug Test";
    };
    if (procName == 'First Interview') {
      this.addDoc_proc[0].doc_type = "First Interview";
      this.addDoc_proc[1].doc_type = "Application";
      this.addDoc_proc[2].doc_type = "Resume";
    };
    if (procName == 'Second Interview') {
      this.addDoc_proc[0].doc_type = "Second Interview";
      this.addDoc_proc[1].doc_type = "Job Offer Letter";
      this.addDoc_proc[2].doc_type = "Commitment Letter";
    };
    if (procName == 'Documentation') {
      this.addDoc_proc[0].doc_type = "DPI";
      this.addDoc_proc[1].doc_type = "NIT";
      this.addDoc_proc[2].doc_type = "Police Records";
      this.addDoc_proc[3].doc_type = "Criminal Records";
      this.addDoc_proc[4].doc_type = "Utility Bill";
      this.addDoc_proc[5].doc_type = "City Tax";
      this.addDoc_proc[6].doc_type = "IGSS";
      this.addDoc_proc[7].doc_type = "IRTRA";
      this.addDoc_proc[8].doc_type = "High School Diploma";
      this.addDoc_proc[9].doc_type = "References";
      this.addDoc_proc[10].doc_type = "Infonet Authorization";
    };
    if (procName == 'Background Check') {
      this.addDoc_proc[0].doc_type = "Police Records Check";
      this.addDoc_proc[1].doc_type = "Criminal Records Check";
      this.addDoc_proc[2].doc_type = "Infonet";
      this.addDoc_proc[3].doc_type = "Infonet Authorization";
      this.addDoc_proc[4].doc_type = "Background Check";
      this.addDoc_proc[5].doc_type = "References Check";
    };

    if (procName == 'New Hire') {
      this.addApplyentContact.method = "HIRED";
    }

    if (procName == 'Campaign Assignation') {
      this.apiservice.getWaves().subscribe((wv: waves_template[]) => {
        this.waves_select = wv;
        this.selected_wave = this.waves_select[0];
        this.getNsId();
        this.apiservice.getUsers(this.selected_wave).subscribe((usr: users[]) => {
          this.users_toAssign = usr;
          this.user_toAssign = usr[0];
          this.apiservice.getSchedules(this.selected_wave).subscribe((sch: schedules[]) => {
            this.assign_schedules = sch;
            this.selected_schedule = sch[0];
          });
        });
      })
    };

    this.toggleAddend = true
    this.procAddition = procName;
    this.procAddnew = true;
  }


  getNsId() {
    var n: number;
    this.apiservice.getUsers(this.selected_wave).subscribe((usr: users[]) => {
      this.users_toAssign = usr;
      this.user_toAssign = usr[0];
      this.apiservice.getSchedules(this.selected_wave).subscribe((sch: schedules[]) => {
        this.assign_schedules = sch;
        this.selected_schedule = sch[0];
      });
    });

    n = parseInt(this.waves_select[this.waves_select.indexOf(this.selected_wave)].hires);
    n = n + 1;
    this.hires_number = this.selected_wave.prefix + (0 + n.toString()).slice(-2);
  }

  getId() {
    var id: string = this.route.snapshot.paramMap.get('id');
    this.prof_to_get.idprofiles = id;
    this.apiservice.getProfile(this.prof_to_get).subscribe((profs_getted: profiles[]) => {
      this.prof_getted = profs_getted;
      this.profile_full_name = this.prof_getted[0].first_name + ' ' + this.prof_getted[0].second_name + ' ' + this.prof_getted[0].first_lastname + ' ' + this.prof_getted[0].second_lastname;
      this.apiservice.getCoincidences(profs_getted[0]).subscribe((coin: coincidences[]) => {
        this.coincidences = coin;
      })
    });
  }

  insertNewHire() {
    const to_insertNewHire = {} as new_hire;
    const to_insertProc = {} as process;

    to_insertProc.descritpion = 'Campaign Assignation';
    to_insertProc.id_profile = this.route.snapshot.paramMap.get('id');
    to_insertProc.id_role = this.queryInfo.id_role;
    to_insertProc.id_user = this.authSrv.getAuthusr().iduser;
    to_insertProc.name = this.procAddition;
    to_insertProc.prc_date = this.addDoc_proc[0].prc_date;
    to_insertProc.status = 'CLOSED';

    to_insertNewHire.schedule_count = (parseInt(this.selected_schedule.actual_count) + 1).toString();
    if (to_insertNewHire.schedule_count == this.selected_schedule.max_count) {
      to_insertNewHire.next_state = '0';
    } else {
      to_insertNewHire.next_state = '1';
    }
    to_insertNewHire.id_schedule = this.selected_schedule.idschedules;
    to_insertNewHire.notes = this.addDoc_proc[0].notes;
    to_insertNewHire.reporter = this.user_toAssign;
    to_insertNewHire.wave = this.selected_wave;
    to_insertNewHire.nearsol_id = this.hires_number;
    to_insertNewHire.process = to_insertProc;

    this.apiservice.insNewHireProc(to_insertNewHire).subscribe((stst: string) => {
      this.toggleAddend = false;
    });
  }

  insertApplayentcontact() {
    if (this.procAddition == "Candidate Contact") {
      this.addApplyentContact.id_profile = this.queryInfo.id_profile;
      this.addApplyentContact.id_user = this.authSrv.getAuthusr().iduser;
      this.addApplyentContact.id_role = this.queryInfo.id_role;
      this.addApplyentContact.processName = this.procAddition;
      this.addApplyentContact.description = 'Personal contact with the apllyent';
      this.addApplyentContact.status = 'CLOSED';
      this.apiservice.insApplyentcontact(this.addApplyentContact).subscribe((stst: string) => { });
      this.toggleAddend = false;
    } else {
      if (this.procAddition == "Candidate Rejection") {
        this.addApplyentContact.id_profile = this.queryInfo.id_profile;
        this.addApplyentContact.id_user = this.authSrv.getAuthusr().iduser;
        this.addApplyentContact.id_role = this.queryInfo.id_role;
        this.addApplyentContact.processName = this.procAddition;
        this.addApplyentContact.description = "Candidate Rejection";
        this.addApplyentContact.status = 'CLOSED';
        this.apiservice.insApplyentcontact(this.addApplyentContact).subscribe((stst: string) => { });
        this.toggleAddend = false;
      } else {
        if (this.procAddition == 'New Hire') {
          this.addApplyentContact.id_profile = this.queryInfo.id_profile;
          this.addApplyentContact.id_user = this.authSrv.getAuthusr().iduser;
          this.addApplyentContact.id_role = this.queryInfo.id_role;
          this.addApplyentContact.processName = this.procAddition;
          this.addApplyentContact.description = "Candidate Hire";
          this.addApplyentContact.status = 'CLOSED';
          this.apiservice.insApplyentcontact(this.addApplyentContact).subscribe((stst: string) => { });
          this.toggleAddend = false;
        } else {
          if (this.procAddition == 'Reception Notes') {
            this.addApplyentContact.id_profile = this.queryInfo.id_profile;
            this.addApplyentContact.id_user = this.authSrv.getAuthusr().iduser;
            this.addApplyentContact.id_role = this.queryInfo.id_role;
            this.addApplyentContact.processName = this.procAddition;
            this.addApplyentContact.description = "Receptionist Notes";
            this.addApplyentContact.status = 'CLOSED';
            this.apiservice.insApplyentcontact(this.addApplyentContact).subscribe((stst: string) => { });
            this.toggleAddend = false;
          }
        }
      }
    }
  };


  insertScheduleVisit() {
    this.addScheduleVisit.id_profile = this.queryInfo.id_profile;
    this.addScheduleVisit.id_user = this.authSrv.getAuthusr().iduser;
    this.addScheduleVisit.id_role = this.queryInfo.id_role;
    this.addScheduleVisit.name = this.procAddition;
    this.addScheduleVisit.descritpion = 'Schedule visit for more information';
    this.addScheduleVisit.status = 'In Progress';
    this.addScheduleVisit.dateTime = this.selDay + '/' + this.selMonth + '/' + this.selYear + ' ' + this.selHour + ':' + this.selMin;
    this.apiservice.insSchedulevisit(this.addScheduleVisit).subscribe((rst: string) => { });
    this.toggleAddend = false;
  };

  insProcDoc() {

    this.addDoc_proc[0].id_role = this.queryInfo.id_role;
    this.addDoc_proc[0].id_profile = this.queryInfo.id_profile;
    this.addDoc_proc[0].name = this.procAddition;
    this.addDoc_proc[0].status = 'CLOSED';
    this.addDoc_proc[0].id_user = this.authSrv.getAuthusr().iduser;
    let formData = new FormData;


    formData.append('profile', this.queryInfo.id_profile);
    formData.append('user', this.authSrv.getAuthusr().iduser);

    if (this.procAddition == 'Test Results') {
      this.addDoc_proc[0].description = 'Results for the tests passed';
      formData.append('process', 'testResult');
      formData.append('file1', this.addDoc_proc[0].doc_path);
      formData.append('file2', this.addDoc_proc[1].doc_path);
      formData.append('file3', this.addDoc_proc[2].doc_path);
      formData.append('file4', this.addDoc_proc[3].doc_path);
      formData.append('number', '3');
    } else {
      if (this.procAddition == 'First Interview') {
        this.addDoc_proc[0].description = 'First Interview Review';
        formData.append('process', 'firstInterview');
        formData.append('file1', this.addDoc_proc[0].doc_path);
        formData.append('file2', this.addDoc_proc[1].doc_path);
        formData.append('file3', this.addDoc_proc[2].doc_path);
        formData.append('number', '3');
      } else {
        if (this.procAddition == 'Second Interview') {
          this.addDoc_proc[0].description = 'Second Interview Review';
          formData.append('process', 'secondInterview');
          formData.append('file1', this.addDoc_proc[0].doc_path);
          formData.append('file2', this.addDoc_proc[1].doc_path);
          formData.append('file3', this.addDoc_proc[2].doc_path);
        } else {
          if (this.procAddition == 'Documentation') {
            this.addDoc_proc[0].description = 'Documentation Review';
            formData.append('process', 'statalDocumentation');
            formData.append('file1', this.addDoc_proc[0].doc_path);
            formData.append('file2', this.addDoc_proc[1].doc_path);
            formData.append('file3', this.addDoc_proc[2].doc_path);
            formData.append('file4', this.addDoc_proc[3].doc_path);
            formData.append('file5', this.addDoc_proc[4].doc_path);
            formData.append('file6', this.addDoc_proc[5].doc_path);
            formData.append('file7', this.addDoc_proc[6].doc_path);
            formData.append('file8', this.addDoc_proc[7].doc_path);
            formData.append('file9', this.addDoc_proc[8].doc_path);
            formData.append('file10', this.addDoc_proc[9].doc_path);
            formData.append('file11', this.addDoc_proc[10].doc_path);
            
          } else {
            if (this.procAddition == 'Background Check') {
              this.addDoc_proc[0].description = 'Bakctround Check';
              formData.append('process', 'backgroundCheck');
              formData.append('file1', this.addDoc_proc[0].doc_path);
              formData.append('file2', this.addDoc_proc[1].doc_path);
              formData.append('file3', this.addDoc_proc[2].doc_path);
              formData.append('file4', this.addDoc_proc[3].doc_path);
              formData.append('file5', this.addDoc_proc[4].doc_path);
              formData.append('file5', this.addDoc_proc[5].doc_path);
            } else {
              if (this.procAddition == 'Drug Test') {
                this.addDoc_proc[0].description = 'Drug Test';
                formData.append('process', 'drugTest');
                formData.append('file1', this.addDoc_proc[0].doc_path);
              }
            }
          }
        }
      }
    }
    this.apiservice.insDocProc_doc(formData).subscribe((data: testRes) => {
      if (this.procAddition == "Test Results") {
        this.addDoc_proc[0].doc_path = data.EnglishTest;
        this.addDoc_proc[1].doc_path = data.TypingTest;
        this.addDoc_proc[2].doc_path = data.PsicometricTest;
        this.addDoc_proc[3].doc_path = data.PoliceRecrods;
      } else {
        if (this.procAddition == "First Interview") {
          this.addDoc_proc[0].doc_path = data.EnglishTest;
          this.addDoc_proc[1].doc_path = data.TypingTest;
          this.addDoc_proc[2].doc_path = data.PsicometricTest;
        } else {
          if (this.procAddition == "Second Interview") {
            this.addDoc_proc[0].doc_path = data.EnglishTest;
            this.addDoc_proc[1].doc_path = data.TypingTest;
            this.addDoc_proc[2].doc_path = data.PsicometricTest;
          } else {
            if (this.procAddition == "Documentation") {
              this.addDoc_proc[0].doc_path = data.EnglishTest;
              this.addDoc_proc[1].doc_path = data.TypingTest;
              this.addDoc_proc[2].doc_path = data.PsicometricTest;
              this.addDoc_proc[3].doc_path = data.PoliceRecrods;
              this.addDoc_proc[4].doc_path = data.CriminalRecords;
              this.addDoc_proc[5].doc_path = data.UtilityBill;
              this.addDoc_proc[6].doc_path = data.CityTax;
              this.addDoc_proc[7].doc_path = data.IGSS;
              this.addDoc_proc[8].doc_path = data.doc1;
              this.addDoc_proc[9].doc_path = data.doc2;
              this.addDoc_proc[10].doc_path = data.doc3;
            } else {
              if (this.procAddition == "Background Check") {
                this.addDoc_proc[0].doc_path = data.EnglishTest;
                this.addDoc_proc[1].doc_path = data.TypingTest;
                this.addDoc_proc[2].doc_path = data.PsicometricTest;
                this.addDoc_proc[3].doc_path = data.PoliceRecrods;
                this.addDoc_proc[4].doc_path = data.CriminalRecords;
                this.addDoc_proc[5].doc_path = data.UtilityBill;
              } else {
                if (this.procAddition == "Legal Documentation") {
                  this.addDoc_proc[0].doc_path = data.EnglishTest;
                } else {
                  if (this.procAddition == "Drug Test") {
                    this.addDoc_proc[0].doc_path = data.EnglishTest;
                  }
                }
              }
            }
          }
        }
      }
      this.apiservice.insDocProc(this.addDoc_proc).subscribe((resStr: string) => {
        this.toggleAddend = false;
        this.addDoc_proc[0] = {
          idprocesses:null,
          id_role:this.authSrv.getAuthusr().id_role,
          id_profile:null,
          name:null,
          description:null,
          prc_date:String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
          status:null,
          id_user:this.authSrv.getAuthusr().iduser,
          iddocuments:null,
          doc_res:null,
          doc_type:null,
          doc_path:null,
          result:null,
          notes:null,
          source:null,
          post:null,
          referrer:null,
          about:null
        };
      });
    });
  }

  returnHome() {
    this.ifProc = 'N/A';
    this.toggleAddend = false;
    this.procAddnew = false;
    this.addDoc_proc[0] = {
      idprocesses:null,
      id_role:this.authSrv.getAuthusr().id_role,
      id_profile:null,
      name:null,
      description:null,
      prc_date:String(this.todayDate.getFullYear()).padStart(4) + '-' + String(this.todayDate.getMonth() + 1).padStart(2, '0') + '-' + String(this.todayDate.getDate()).padStart(2, '0'),
      status:null,
      id_user:null,
      iddocuments:null,
      doc_res:null,
      doc_type:null,
      doc_path:null,
      result:null,
      notes:null,
      source:null,
      post:null,
      referrer:null,
      about:null
    };
  };

  toggle_Status() {
    if (this.toggleEdit_Status) {
      var qry = {
        query: "UPDATE `process_details` SET `value`= '" + this.fullScheduleVisit[0].attendance + "' WHERE `id_process` = '" + this.fullScheduleVisit[0].idprocesses + "' AND `name` = 'Result';"
      };
      this.apiservice.updateInformation(qry).subscribe((sts: string) => { });
    }
    this.toggleEdit_Status = !this.toggleEdit_Status;

  }

  onChange(event, i: number) {
    this.addDoc_proc[i].doc_path = event.target.files[0];
  };


}
