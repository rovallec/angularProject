import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { employees } from './fullProcess';
import { profiles, profiles_family, profiles_histories } from './profiles';

export class process_templates {
    idprocess_templates: string;
    name: string;
    department: string;
    id_role: string;
}

export class waves_template {
    idwaves: string;
    id_account: string;
    starting_date: string;
    end_date: string;
    max_recriut: string;
    hires: string;
    name: string;
    trainning_schedule: string;
    prefix: string;
    state: string;
    account_name: string;
    ops_start: string;
    job: string;
    base_payment: string;
    productivity_payment: string;
    show: string;
    constructor() {
        this.idwaves = null;
        this.id_account = null;
        this.starting_date = null;
        this.end_date = null;
        this.max_recriut = null;
        this.hires = null;
        this.name = null;
        this.trainning_schedule = null;
        this.prefix = null;
        this.base_payment = null;
        this.productivity_payment = null;
        this.state = null;
        this.account_name = null;
        this.job = null;
        this.show = '0';
    }
}

export class hires_template {
    idhires: string;
    id_profile: string;
    id_wave: string;
    nearsol_id: string;
    first_name: string;
    second_name: string;
    first_lastname: string;
    second_lastname: string;
    status: string;
    id_schedule: string;
    client_id: string;
    idemployees: string;
    reporter: string;
    day_off1: string;
    day_off2: string;
    bank: string;
    account: string;
    payType: string;
    platform: string;
    bool: boolean;
    society: string;
}

export class accounts {
    idaccounts: string;
    name: string;
    id_client: string;
    correlative: string;
    prefix: string;
    constructor() {
        this.idaccounts = null;
        this.name = null;
        this.id_client = null;
        this.correlative = null;
        this.prefix = null;
    }
}

export class schedules {
    idschedules: string;
    schedule_name: string;
    start_time: string;
    end_time: string;
    days_off: string;
    id_wave: string;
    actual_count: string;
    max_count: string;
    show: string;
    state: string;
    constructor() {
        this.idschedules = null;
        this.schedule_name = null;
        this.start_time = null;
        this.end_time = null;
        this.days_off = null;
        this.id_wave = null;
        this.actual_count = null;
        this.max_count = null;
        this.show = '0';
        this.state = null;
    }
}

export class realTimeTrack {
    date: string;
    first_name: string;
    second_name: string;
    first_lastname: string;
    second_lastname: string;
    id_profile: string;
    lastProcess: string;
    firstInterview: string;
    secondInterview: string;
    lastprocessName: string;
    lastValue: string;
    Recruiter: string;
    wave: string;
    account: string;
    startingDate: string;
    candidate_status: string;
    filter: string;
    filterValue: string;
    comment: string;
    constructor() {
        this.date = null;
        this.first_name = null;
        this.second_name = null;
        this.first_lastname = null;
        this.second_lastname = null;
        this.id_profile = null;
        this.lastProcess = null;
        this.firstInterview = null;
        this.secondInterview = null;
        this.lastprocessName = null;
        this.lastValue = null;
        this.Recruiter = null;
        this.wave = null;
        this.account = null;
        this.startingDate = null;
        this.candidate_status = null;
        this.filter = null;
        this.filterValue = null;
    }
}

export class attendences {
    idattendences: string;
    id_employee: string;
    nearsol_id: string;
    client_id: string;
    first_name: string;
    second_name: string;
    first_lastname: string;
    second_lastname: string;
    date: string;
    scheduled: string;
    worked_time: string;
    day_off1: string;
    day_off2: string;
    status: string;
    id_wave: string;
    balance: string;
    state: string;
    igss:string;
    tk_exp:string;
    tk_imp:string;
    schedule_fix:string;
    time_in_aux0:string;
    time_in_systems_issues:string;
    time_in_lunch:string;
    break_abuse:string;
    exceptions_meeting_feedback:string;
    exceptions_offline_training:string;
    systems_issues_by_sup:string;
    floor_support:string;
    time_training:string;
        constructor() {
        this.igss = '0';
        this.tk_exp = '0';
        this.idattendences = null;
        this.id_employee = null;
        this.date = null;
        this.scheduled = null;
        this.worked_time = null;
        this.nearsol_id = null;
        this.client_id = null;
        this.first_name = null;
        this.second_name = null;
        this.first_lastname = null;
        this.second_lastname = null;
        this.day_off1 = null;
        this.day_off2 = null;
        this.status = '0';
        this.id_wave = null;
        this.balance = null;
        this.state = null;
        this.tk_imp = null;
        this.time_in_aux0 = null;
        this.time_in_systems_issues = null;
        this.time_in_lunch = null;
        this.break_abuse = null;
        this.exceptions_meeting_feedback = null;
        this.exceptions_offline_training = null;
        this.systems_issues_by_sup = null;
        this.floor_support = null;
        this.time_training = null;
        this.schedule_fix = null
    }
}

export class attendences_adjustment {
    //adjustments
    idattendence_adjustemnt: string;
    id_attendence: string;
    id_justification: string;
    attendance_date: string;
    time_before: string;
    time_after: string;
    amount: string;
    state: string;
    start:string;
    end:string;
    dateTime:string;
    name: string;
    //justifications
    id_process: string;
    reason: string;
    //processes
    id_user: string;
    id_employee: string;
    id_type: string;
    id_department: string;
    date: string;
    notes: string;
    status: string;
    id_period:string;
    error:string;
    nearsol_id:string;
    adj_type:string;
    account:string;
    id_import:string;
    constructor() {
        //adjustments
        this.idattendence_adjustemnt = null;
        this.id_attendence = null;
        this.id_justification = null;
        this.attendance_date = null;
        this.time_before = null;;
        this.time_after = null;
        this.amount = null;
        this.state = null;
        this.start = null;
        this.end = null;
        this.name = null;
        //justifications
        this.id_process = null;
        this.reason = null;
        //processes
        this.id_user = null;
        this.id_employee = null;
        this.id_type = null;
        this.id_department = null;
        this.date = null;
        this.notes = null;
        this.status = null;
        this.id_period = null;
        this.error = null;
        this.nearsol_id = null;
        this.adj_type = null;
        this.id_import = '0';
    }
}

export class vacations {
    //process
    id_process;
    id_user: string;
    id_employee: string;
    id_type: string;
    id_department: string;
    date: string;
    notes: string;
    status: string;
    dateTime:string;
    //vacations
    action: string;
    count: string;
    took_date: string;
    year: number;
    constructor() {
        this.id_user = null;
        this.id_employee = null;
        this.id_type = null;
        this.id_department = null;
        this.date = null;
        this.notes = null;
        this.status = null;
        this.action = null;
        this.count = null;
        this.took_date = null;
        this.year = new Date(this.date).getFullYear();
    }

    setYear() {
        this.year = new Date(this.date).getFullYear();
    }
}

export class vacyear {
    year: number;
    selected: boolean
    vacations: vacations[];
    constructor() {
      this.year = null;
      this.selected = false;
      this.vacations = [];
    }

    vacSelected(sel: boolean) {
      this.selected = sel;
    }
  }

export class leaves {
    id_process;
    //process
    id_user: string;
    id_employee: string;
    id_type: string;
    id_department: string;
    date: string;
    notes: string;
    status: string;
    dateTime:string;
    //leaves
    motive: string;
    approved_by: string;
    start: string;
    end: string;
    constructor() {
        this.id_user = null;
        this.id_employee = null;
        this.id_type = null;
        this.id_department = null;
        this.date = null;
        this.notes = null;
        this.status = null;
        this.motive = null;
        this.approved_by = null;
        this.start = null;
        this.end = null;
    }
}

export class leavesAction {
    dates: string;
    action: string;
    constructor(){
        this.dates = null;
        this.action = null;
    }
}

export class disciplinary_processes {
    //Process
    id_processes: string;
    id_user: string;
    id_employee: string;
    id_type: string;
    id_department: string;
    date: string;
    notes: string;
    status: string;
    dateTime:string;
    //Request
    idrequests: string;
    requested_by: string;
    reason: string;
    description: string;
    resolution: string;
    proceed: string;
    //Disciplinary Process
    iddp: string;
    type: string;
    cathegory: string;
    dp_grade: string;
    motive: string;
    imposition_date: string;
    legal_foundament: string;
    consequences: string;
    observations: string
    //Audiences
    audience_date: string;
    time: string;
    comments: string;
    audience_status: string;
    //Suspensions
    day_1: string;
    day_2: string;
    day_3: string;
    day_4: string;
    //Extra
    nearsol_id:string;
    client_id:string;
    constructor() {
        this.id_user = null;
        this.id_employee = null;
        this.id_type = null;
        this.id_department = null;
        this.date = null;
        this.notes = null;
        this.status = null;
        this.idrequests = null;
        this.requested_by = null;
        this.reason = null;
        this.description = null;
        this.resolution = null;
        this.proceed = null;
        this.iddp = null;
        this.type = null;
        this.cathegory = null;
        this.dp_grade = null;
        this.motive = null;
        this.imposition_date = null;
        this.legal_foundament = null;
        this.consequences = null;
        this.observations = null;
        this.audience_date = null;
        this.time = null;
        this.comments = null;
        this.audience_status = null;
        this.day_1 = null;
        this.day_2 = null;
        this.day_3 = null;
        this.day_4 = null;
        this.nearsol_id = null;
        this.client_id = null;
    }
}

export class insurances {
    //Process
    id_processes: string;
    id_user: string;
    id_employee: string;
    id_type: string;
    id_department: string;
    date: string;
    notes: string;
    status: string;
    //Insurances
    idinsurances: string;
    id_process: string;
    plan: string;
    license: string;
    cert: string;
    contractor: string;
    place: string;
    reception: string;
    delivered: string;
    in_status: string;
    constructor() {
        this.id_processes = null;
        this.id_user = null;
        this.id_employee = null;
        this.id_type = null;
        this.id_department = null;
        this.date = null;
        this.notes = null;
        this.status = null;
        this.idinsurances = null;
        this.id_process = null;
        this.plan = null;
        this.license = null;
        this.cert = null;
        this.contractor = null;
        this.place = null;
        this.reception = null;
        this.delivered = null;
        this.status = null;
    }
}

export class beneficiaries {
    idbeneficiaries: string;
    first_name: string;
    second_name: string;
    first_lastname: string;
    second_lastname: string;
    afinity: string;
    constructor() {
        this.idbeneficiaries = null;
        this.first_name = null;
        this.second_name = null;
        this.first_lastname = null;
        this.second_lastname = null;
        this.afinity = null;
    }
}

export class terminations {
    id_process: string;
    motive: string;
    kind: string;
    reason: string;
    rehireable: string;
    nearsol_experience: string;
    valid_from: string;
    comments: string;
    insurance_notification: String;
    access_card: string;
    headsets: string;
    bank_check: string;
    period_to_pay: string;
    supervisor_experience: string;
    base_for_salary: string;
    constructor() {
        this.id_process = null;
        this.motive = null;
        this.kind = null;
        this.reason = null;
        this.rehireable = null;
        this.nearsol_experience = null;
        this.valid_from = null;
        this.comments = null;
        this.insurance_notification = null;
        this.access_card = null;
        this.headsets = null;
        this.bank_check = null;
        this.period_to_pay = null;
        this.supervisor_experience = null;
        this.base_for_salary = null;
    }
}

export class employees_Bonuses {
  idemployees: string;
  nearsol_id: string;
  name: string;
  account: string;
  hiring_date: string;
  days_to_pay: string;
  base_payment: string;
  complement: string;
  base_calc: string;
  average: string;
  advances: string;
  total: string;
  constructor() {
    this.idemployees = null;
    this.nearsol_id = null;
    this.name = null;
    this.account = null;
    this.hiring_date = null;
    this.days_to_pay = null;
    this.base_payment = null;
    this.complement = null;
    this.base_calc = null;
    this.average = null;
    this.advances = null;
    this.total = null;
  }
}

export class reports {
    //reports
    idreports: string;
    id_process: string;
    tittle: string;
    description: string;
    classification: string;
    //solutions
    idsolutions: string;
    id_report: string;
    s_description: string;
    approved_by: string;
    constructor() {
        //reports
        this.idreports = null;
        this.id_process = null;
        this.tittle = null;
        this.description = null;
        this.classification = null;
        //solutions
        this.idsolutions = null;
        this.id_report = null;
        this.s_description = null;
        this.approved_by = null;
    }
}

export class advances {
    idadvances: string;
    id_process: string;
    type: string;
    description: string;
    classification: string;
    amount: string;
    constructor() {
        this.idadvances = null;
        this.id_process = null;
        this.type = null;
        this.description = null;
        this.classification = null;
        this.amount = null;
    }
}

export class activities {
    idactivities: string;
    id_process: string;
    type: string;
    account: string;
    requested_by: string;
    approved_by: string;
    description: string;
    done_date: string;
    amount: string;
    document: string;
    constructor() {
        this.idactivities = null;
        this.id_process = null;
        this.type = null;
        this.account = null;
        this.requested_by = null;
        this.approved_by = null;
        this.description = null;
        this.done_date = null;
        this.amount = null;
        this.document = null;
    }
}

export class rises {
    id_employee: string;
    id_process: string;
    new_position: string;
    new_base_salary: string;
    new_productivity_payment: string;
    approved_by: string;
    approved_date: string;
    effective_date: string;
    trial_start: string;
    trial_end: string;
    old_position: string;
    new_salary: string;
    old_salary: string;
    constructor() {
        this.id_employee = null;
        this.id_process = null;
        this.new_position = null;
        this.new_base_salary = null;
        this.new_productivity_payment = null;
        this.approved_by = null;
        this.approved_date = null;
        this.effective_date = null;
        this.trial_start = null;
        this.trial_end = null;
        this.old_position = null;
        this.new_salary = null;
        this.old_salary = null;
    }
}

export class call_tracker {
    idcall_tracks: string;
    id_process: string;
    type: string;
    reason: string;
    channel: string;
    constructor() {
        this.idcall_tracks = null;
        this.id_process = null;
        this.type = null;
        this.reason = null;
        this.channel = null;
    }
}

export class letters {
    idletters: string;
    id_process: string;
    type: string;
    company: string;
    patronal_number: string;
    emition_date: string;
    language: string;
    position: string;
    department: string;
    base_salary: string;
    productivity_salary: string;
    constructor() {
        this.idletters = null;
        this.id_process = null;
        this.type = null;
        this.company = null;
        this.patronal_number = null;
        this.emition_date = null;
        this.language = null;
        this.position = null;
        this.department = null;
        this.base_salary = null;
        this.productivity_salary = null;
    }
}

export class supervisor_survey {
    idsupervisor_survey: string;
    id_process: string;
    amount: string;
    approved_date: string;
    notification_date: string;
    score: string;
    constructor() {
        this.idsupervisor_survey = null;
        this.id_process = null;
        this.amount = null;
        this.approved_date = null;
        this.notification_date = null;
        this.score = null;
    }
}

export class judicials {
    idjudicials: string;
    id_process: string;
    amount: string;
    current: string;
    max: string;
    constructor() {
        this.idjudicials = null;
        this.id_process = null;
        this.amount = null;
        this.max = null;
    }
}

export class irtra_requests {
    idprocess: string;
    idirtra_requests: string;
    type: string;
    spouse_name: string;
    spouse_lastname: string;
    constructor() {
        this.idirtra_requests = null;
        this.idprocess = null;
        this.type = null;
    }
}

export class messagings {
    idmessagings: string;
    idprocess: string;
    type: string;
    notes: string;
    constructor() {
        this.idmessagings = null;
        this.idprocess = null;
        this.type = null;
        this.notes = null;
    }
}

export class periods {
    idperiods: string;
    start: string;
    end: string;
    status: string;
    type_period: string;
    constructor() {
        this.idperiods = null;
        this.start = null;
        this.end = null;
        this.status = null;
        this.type_period = null;
    }
}

export class deductions {
    iddeductions: string;
    idprofiles: string;
    idemployees: string;
    name: string;
    type: string;
    reason: string;
    amount: string;
}

export class debits {
    iddebits: string;
    idpayments: string;
    type: string;
    amount: string;
    status:string;
    //process
    id_process: string;
    id_user: string;
    id_employee: string;
    id_action: string;
    date: string;
    notes: string;
    constructor() {
        this.iddebits = null;
        this.idpayments = null;
        this.type = null;
        this.amount = '0';
        this.status = null;
        //process
        this.id_process = null;
        this.id_user = null;
        this.id_employee = null;
        this.id_action = null;
        this.date = null;
        this.notes = null;
    }
}

export class credits {
    iddebits: string;
    idpayments: string;
    type: string;
    amount: string;
    status:string;
    //process
    id_process: string;
    id_user: string;
    id_employee: string;
    date: string;
    notes: string;
    constructor() {
        this.iddebits = null;
        this.idpayments = null;
        this.type = null;
        this.amount = '0';
        this.status = null;
        //process
        this.id_process = null;
        this.id_user = null;
        this.id_employee = null;
        this.date = null;
        this.notes = null;
    }
}

export class payments {
    idpayments: string;
    id_employee: string;
    id_paymentmethod: string;
    id_period: string;
    credits: string;
    debits: string;
    date: string;
    start: string;
    end: string;
    last_seventh: string;
    ot: string;
    ot_hours: string;
    base_hours: string;
    productivity_hours: string;
    base: string;
    productivity: string;
    seventh: string;
    holidays: string;
    holidays_hours: string;
    base_complete: string;
    productivity_complete: string;
    employee_name: string;
    total: string;
    status: string;
    nearsol_id: string;
    client_id: string;
    state: string;
    account: string;
    days: string;
    id_account_py:string;
    idpayroll_values:string;
    nearsol_bonus:string;
    client_bonus:string;
    treasure_hunt:string;
    adj_hours:string;
    adj_ot:string;
    adj_holidays:string;
    constructor() {
        this.idpayments = null;
        this.id_employee = null;
        this.id_paymentmethod = null;
        this.id_period = null;
        this.credits = null;
        this.debits = null;
        this.date = null;
        this.start  = null;
        this.end = null;
        this.last_seventh = null;
        this.ot = null;
        this.ot_hours = null;
        this.base_hours = null;
        this.productivity_hours = null;
        this.base = null;
        this.productivity = null;
        this.seventh = null;
        this.holidays = null;
        this.holidays_hours = null;
        this.base_complete = null;
        this.productivity_complete = null;
        this.employee_name = null;
        this.total = null;
        this.status = null;
        this.nearsol_id = null;
        this.client_id = null;
        this.state = null;
        this.account = null;
        this.days = null;
        this.id_account_py = null;
        this.idpayroll_values = null;
    }
}

export class services {
    idservices: string;
    id_process: string;
    id_employee: string;
    name: string;
    amount: string;
    max: string;
    frecuency: string;
    status: string;
    current: string;
    //Process
    idinternal_process: string;
    id_user: string;
    proc_name: string;
    date: string;
    proc_status: string;
    notes: string;
    constructor() {
        this.idservices = null;
        this.id_process = null;
        this.id_employee = null;
        this.name = null;
        this.amount = null;
        this.max = null;
        this.frecuency = null;
        this.status = null;
        this.idinternal_process = null;
        this.id_user = null;
        this.proc_name = null;
        this.date = null;
        this.proc_status = null;
        this.notes = null
    }
}

export class card_assignation {
    idcard_assignations: string;
    id_employee: string;
    id_process: string;
    code: string;
    status: string;
    //processes
    idinternal_process: string;
    id_user: string;
    proc_name: string;
    date: string;
    proc_status: string;
    notes: string;
    constructor() {
        this.idcard_assignations = null;
        this.id_employee = null;
        this.id_process = null;
        this.code = null;
        this.status = null;
        //processes
        this.idinternal_process = null;
        this.id_user = null;
        this.proc_name = null;
        this.date = null;
        this.proc_status = null;
        this.notes = null;
    }
}

export class sup_exception {
    avaya: string;
    name: string;
    date: string;
    reason: string;
    time: string;
    notes: string;
    supervisor: string;
    status: string;
    id_employee:string;
    duplicated:string;
    id_type:string;
    constructor() {
        this.avaya = null;
        this.name = null;
        this.date = null;
        this.reason = null;
        this.time = null;
        this.notes = null;
        this.supervisor = null;
        this.status = "FALSE";
        this.id_employee = null;
        this.duplicated = '0';
        this.id_type = null;
    }
}

export class change_id {
    new_id: string;
    old_id: string;
    id_employee: string;
    //process
    idinternal_process: string;
    id_user: string;
    proc_name: string;
    date: string;
    proc_status: string;
    notes: string;
    constructor() {
        this.new_id = null;
        this.id_employee = null;
        //process
        this.idinternal_process = null;
        this.id_user = null;
        this.proc_name = null;
        this.date = null;
        this.proc_status = null;
        this.notes = null;
    }
}

export class ot_manage {
    id_employee: string;
    name: string;
    nearsol_id: string;
    amount: string;
    id_period: string;
    status: string;
    client_id:string;
    balance:string;
    constructor() {
        this.id_employee = null;
        this.amount = null;
        this.id_period = null;
    }
}

export class attendance_accounts {
    idaccounts: string;
    name: string;
    max: string;
    value: string;
    date: string;
    status: string;
    show: string;
    constructor() {
        this.idaccounts = null;
        this.name = null;
        this.max = null;
        this.value = null;
        this.status = null;
        this.show = null;
    }
}

export class clients {
    idclients: string;
    name: string;
    description: string;
    constructor() {
        this.idclients = null;
        this.name = null;
        this.description = null;
    }
}

export class marginalization {
    //Employees
    idemployees:string;
    id_profile:string;
    nearsol_id:string;
    name:string;
    //Master
    idmarginalizations: string;
    id_user: string;
    approved_by: string;
    date: string;
    type: string;
    //Details
    idmarginalization_details: string;
    id_attendance: string;
    id_marginalization: string;
    before: string;
    after: string;
    value: string;
    //Visual Data
    action:string;
    constructor() {
            //Employees
    this.idemployees = null;
    this.id_profile = null;
    this.nearsol_id = null;
    this.name = null;
    //Master
    this.idmarginalizations = null;
    this.id_user = null;
    this.approved_by = null;
    this.date = null;
    this.type = null;
    //Details
    this.idmarginalization_details = null;
    this.id_attendance = null;
    this.id_marginalization = null;
    this.before = null;
    this.after = null;
    this.value = null;
    //Visual Data
    this.action = null;
    }
}

export class isr{
   idemployees:string;
   nit:string;
   idisr:string;
   nearsol_id:string;
   name:string;
   gross_income:string;
   taxable_income:string;
   anual_tax:string;
   other_retentions:string;
   accumulated:string;
   expected:string;
   amount:string;
   constructor(){
    this.idemployees = null;
    this.nit = null;
    this.idisr = null;
    this.nearsol_id = null;
    this.name = null;
    this.gross_income = null;
    this.taxable_income = null;
    this.anual_tax = null;
    this.other_retentions = null;
    this.accumulated = null;
    this.expected = null;
    this.amount = null;
   }
}

export class payroll_values{
    idpayroll_values:string;
    id_employee:string;
    id_period:string;
    client_id:string;
    nearsol_id:string;
    name:string;
    supervisor:string;
    absences:string;
    discount:string;
    night_hours:string;
    ot_regular:string;
    ot_rdot:string;
    ot_holiday:string;
    holiday_regular:string;
    holiday_special:string;
    total_days:string;
    constructor(){
        this.idpayroll_values = null;
        this.id_employee = null;
        this.id_period = null;
        this.client_id = null;
        this.nearsol_id = null;
        this.name = null;
        this.supervisor = null;
        this.absences = null;
        this.discount = null;
        this.night_hours = null;
        this.ot_regular = null;
        this.ot_rdot = null;
        this.ot_holiday = null;
        this.holiday_regular = null;
        this.holiday_special = null;
        this.total_days = null;
    }
}

export class payroll_ph{
    nearsol_id:string;
    client_id:string;
    name:string;
    position:string;
    wave:string;
    status:string;
    tin:string;
    sss:string;
    account_number:string;
    gross_basic_monthly:string;
    gross_basic_semi_monthly:string;
    overtime:string;
    tardi_ness:string;
    holiday:string;
    undertime:string;
    absent:string;
    night_difference:string;
    other_adjustment:string;
    other_taxable:string;
    total:string;
    sss_tax:string;
    phic:string;
    pag_ibig:string;
    total_taxes:string;
    taxable_income:string;
    tax_due:string;
    net_taxable_income:string;
    other_income:string;
    de_minimis:string;
    other_non_taxable:string;
    sss_loan:string;
    hdmf_loan:string;
    hmo:string;
    others_deduction:string;
    net_take_home:string;
    constructor(){
    this.nearsol_id = null;
    this.client_id = null;
    this.name = null;
    this.position = null;
    this.wave = null;
    this.status = null;
    this.tin = null;
    this.sss = null;
    this.account_number = null;
    this.gross_basic_monthly = null;
    this.gross_basic_semi_monthly = null;
    this.overtime = null;
    this.tardi_ness = null;
    this.holiday = null;
    this.undertime = null;
    this.absent = null;
    this.night_difference = null;
    this.other_adjustment = null;
    this.total = null;
    this.sss_tax = null;
    this.phic = null;
    this.pag_ibig = null;
    this.total_taxes = null;
    this.taxable_income = null;
    this.tax_due = null;
    this.net_taxable_income = null;
    this.other_income = null;
    this.de_minimis = null;
    this.other_non_taxable = null;
    this.sss_loan = null;
    this.hdmf_loan = null;
    this.hmo = null;
    this.others_deduction = null;
    this.net_take_home = null;
    }
}

export class advances_acc{
    idadvances:string;
    id_process:string;
    type:string;
    description:string;
    classification:string;
    amount:string;
    idhr_processes:string;
    id_employee:string;
    date:string;
    notes:string;
    status:string;
    username:string;
    user_name:string;
    constructor(){
    this.idadvances = null;
    this.id_process = null;
    this.type = null;
    this.description = null;
    this.classification = null;
    this.amount = null;
    this.idhr_processes = null;
    this.id_employee = null;
    this.date = null;
    this.notes = null;
    this.status = null;
    this.username = null;
    this.user_name = null;
    }
}

export class Fecha{
    today: string;
    year: string;
    month: string;

    getToday(): string {
        let fecha: Date = new Date();
        let dd: string = String(fecha.getDate()).padStart(2,'0');
        let MM: string = String(fecha.getMonth() + 1).padStart(2, '0');
        let yyyy: string = fecha.getFullYear().toString();
        this.year = yyyy;
        this.month = MM;
        return (yyyy + '-' + MM + '-' + dd);
    }

    transform(Adate: Date): string {
        let dd: string = String(Adate.getDate()).padStart(2,'0');
        let MM: string = String(Adate.getMonth() + 1).padStart(2, '0');
        let yyyy: string = Adate.getFullYear().toString();
        let sdate: string = (yyyy + '-' + MM + '-' + dd);
        if (sdate == 'NaN-NaN-NaN') {
            sdate = '1970-01-01';
        }
        return sdate;
    }

    dateExcel(d): Date {
      let date = new Date(Math.round((d - 25569) * 864e5));
      date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
      return date;
    }

    constructor() {
        this.today = this.getToday();
    }
}

export class payroll_values_gt{
    idpayroll_values:string = null;
    id_employee:string = null;
    id_reporter:string = null;
    id_account:string = null;
    id_period:string = null;
    id_payment:string = null;
    client_id:string = null;
    nearsol_id:string = null;
    agent_name:string = null;
    account_name:string = null;
    agent_status:string = null;
    total_days:string = null;
    hrs:number = null;
    next_seventh:number = null;
    adjustments:string = null;
    discounted_days:string = null;
    discounted_hours:string = null;
    seventh:string = null;
    ot_hours:string = null;
    holidays_hours:string = null;
    nearsol_bonus:string = null;
    performance_bonus:string = null;
    treasure_hunt:string = null;
    adj_hours:string = null;
    adj_ot:string = null;
    adj_holidays:string = null;
    constructor(){
        this.total_days = null;
        this.idpayroll_values = null;
        this.id_employee = null;
        this.id_reporter = null;
        this.id_account = null;
        this.id_period = null;
        this.id_payment = null;
        this.client_id = null;
        this.nearsol_id = null;
        this.discounted_days = null;
        this.seventh = null;
        this.discounted_hours = null;
        this.ot_hours = null;
        this.holidays_hours = null;
        this.performance_bonus = null;
        this.treasure_hunt = null;
        this.agent_name = null;
        this.account_name = null;
        this.agent_status = null;
        this.hrs = null;
        this.next_seventh = null;
        this.adjustments = null;
    }
}

export class paid_attendances{
    idpaid_attendances:string;
    id_payroll_value:string;
    date:string;
    scheduled:string;
    worked:string;
    balance:string;
    id_employee:string;
    constructor(){
        this.idpaid_attendances = null;
        this.id_payroll_value = null;
        this.date = null;
        this.scheduled = null;
        this.worked = null;
        this.balance = null;
        this.id_employee = null;
    }
}

export class reporters {
    idUser: string;
    username: string;
    signature: string;
    constructor(){
        this.idUser = null;
        this.username = null;
        this.signature = null;
    }
}

class contact_details {
    id_profile: string;
    primary_phone: string;
    secondary_phone: string;
    address: string;
    city: string;
    email: string;
    constructor(){
        this.id_profile = null;
        this.primary_phone = null;
        this.secondary_phone = null;
        this.address = null;
        this.city = null;
        this.email = null;
    }
}

export class job_histories {
    id_profile: string;
    company: string;
    date_joining: string;
    date_end: string;
    position: string;
    reference_name: string;
    reference_lastname: string;
    reference_position: string;
    reference_mail: string;
    reference_phone: string;
    working: string;
    constructor() {
        this.id_profile = null;
        this.company = null;
        this.date_joining = null;
        this.date_end = null;
        this.position = null;
        this.reference_name = null;
        this.reference_lastname = null;
        this.reference_position = null;
        this.reference_mail = null;
        this.reference_phone = null;
        this.working = null;
    }
}

export class full_profiles {
    No: string;
    nearsol_id: string;
    id_user: string;
    wave: waves_template;
    schedule: schedules;
    employee: employees;
    contact_detail: contact_details;
    // profiles
    idprofiles: string;
    tittle: string;
    first_name: string;
    second_name: string;
    first_lastname: string;
    second_lastname: string;
    day_of_birth: string;
    nationality: string;
    gender: string;
    etnia: string;
    bank: string;
    account: string;
    account_type: string;
    marital_status: string;
    dpi: string;
    nit: string;
    igss: string;
    irtra: string;
    idcontact_details: string;
    id_profile: string;
    primary_phone: string;
    secondary_phone: string;
    address: string;
    city: string;
    email: string;
    idjob_histories: string;
    company: string;
    date_joining: string;
    date_end: string;
    profesion: string;
    birth_place: string;
    id_profile_details: string;
    start_date: string;
    unavialable_days: string;
    marketing_campaing: string;
    first_lenguage: string;
    second_lenguage: string;
    third_lenguage: string;
    idemergency_details: string;
    phone: string;
    relationship: string;
    idmedical_details: string;
    ideducation_details: string;
    iddocuments: string;
    doc_type: string;
    doc_path: string;

    // hires
    idhires: string;
    id_wave: string;
    id_schedule: string;
    reports_to: string;
    // emergency_details
    emergency_first_name: string;
    emergency_second_name: string;
    emergency_first_lastname: string;
    emergency_second_lastname: string;
    emergency_phone: string;
    emergency_relationship: string;
    // medical_details
    medical_treatment: string;
    medical_prescription: string;
    family: profiles_family[];
    job_history: job_histories[];
    // education_details
    current_level: string;
    further_education: string;
    currently_studing: string;
    institution_name: string;
    degree: string;
    // marketing_details
    sourse: string;
    post: string;
    refer: string;
    about: string;
    // Profiles_details
    english_level: string;
    transport: string;
    first_language: string;
    second_language: string;
    third_language: string;
    // Process
    name: string;
    description: string;
    id_userpr: string;
    //services
    amount: string;
    state: string;
    error_message: string;
    constructor() {
        this.No = '0';
        this.nearsol_id = '';
        this.id_user = null;
        this.wave = new waves_template;
        this.schedule = new schedules;
        // profiles
        this.idprofiles = null;
        this.tittle = null;
        this.first_name = null;
        this.second_name = null;
        this.first_lastname = null;
        this.second_lastname = null;
        this.day_of_birth = null;
        this.nationality = null;
        this.gender = null;
        this.etnia = null;
        this.bank = null;
        this.account = null;
        this.account_type = null;
        this.marital_status = null;
        this.dpi = null;
        this.nit = null;
        this.igss = null;
        this.irtra = null;
        this.idcontact_details = null;
        this.id_profile = null;
        this.primary_phone = null;
        this.secondary_phone = null;
        this.address = null;
        this.city = null;
        this.email = null;
        this.idjob_histories = null;
        this.company = null;
        this.date_joining = null;
        this.date_end = null;
        this.profesion = null;
        this.birth_place = null;
        this.id_profile_details = null;
        this.english_level = null;
        this.transport = null;
        this.start_date = null;
        this.unavialable_days = null;
        this.marketing_campaing = null;
        this.first_lenguage = null;
        this.second_lenguage = null;
        this.third_lenguage = null;
        this.idemergency_details = null;
        this.emergency_first_name = null;
        this.emergency_second_name = null;
        this.emergency_first_lastname = null;
        this.emergency_second_lastname = null;
        this.phone = null;
        this.relationship = null;
        this.idmedical_details = null;
        this.medical_treatment = null;
        this.medical_prescription = null;
        this.ideducation_details = null;
        this.current_level = null;
        this.further_education = null;
        this.currently_studing = null;
        this.institution_name = null;
        this.degree = null;
        this.iddocuments = null;
        this.doc_type = null;
        this.doc_path = null;
        // hires
        this.idhires = null;
        this.id_wave = null;
        this.id_schedule = null;
        this.reports_to = null;
        this.employee = new employees;
        this.contact_detail = new contact_details;
        this.emergency_first_name = null;
        this.emergency_second_lastname = null;
        this.emergency_first_lastname = null;
        this.emergency_second_lastname = null;
        this.emergency_phone = null;
        this.emergency_relationship = null;
        this.medical_treatment = null;
        this.medical_prescription = null;
        this.family = [];
        this.job_history = [];
        this.current_level = null;
        this.further_education = null;
        this.currently_studing = null;
        this.institution_name = null;
        this.degree = null;
        this.sourse = null;
        this.post = null;
        this.refer = null;
        this.about = null;
        this.english_level = null;
        this.transport = null;
        this.first_language = null;
        this.second_language = null;
        this.third_language = null;
        this.name = null;
        this.description = null;
        this.id_userpr = null;
        this.amount = null;
        this.state = null;
        this.error_message = null;
    }
}

export class formerEmployer {
    idformer_employes: string;
    id_employee: string;
    name: string;
    indemnization: string;
    aguinaldo: string;
    bono14: string;
    igss: string;
    taxpendingpayment: string;
    state: string;
    constructor() {
        this.idformer_employes = null;
        this.id_employee = null;
        this.name = null;
        this.indemnization = null;
        this.aguinaldo = null;
        this.bono14 = null;
        this.igss = null;
        this.taxpendingpayment = null;
        this.state = null;
    }
}

export class ids_profiles {
    id_profile: string;
    id_hire: string;
    id_employees: string;
    idemergency_Details: string;
    idmedical_details: string;
    ideducation_details: string;
    id_process: string;
    idmarketing_details: string;
    idprocess_details: string;
    idinternal_processes: string;
    idservices: string;
    desarrollo
}

export class payroll_resume{
    idpayroll_resume:string;
    id_employee:string;
    id_period:string;
    account:string;
    nearsol_id:string;
    client_id:string;
    name:string;
    vacations:string;
    janp:string;
    jap:string;
    igss:string;
    igss_hrs:string;
    insurance:string;
    other_hrs:string;
    ns:string;
    constructor(){
        this.nearsol_id = null;
        this.client_id = null;
        this.name = null;
        this.vacations = '0';
        this.janp = '0';
        this.jap = '0';
        this.igss = '0';
        this.igss_hrs = '0';
        this.insurance = '0';
        this.other_hrs = '0';
        this.ns = '0';
    }
}

export class policies {
    id_client: string;
    idaccounts: string;
    idperiod: string;
    month: string;
    type: string; // si es mensual o por período.
    constructor() {
        this.id_client = null;
        this.idaccounts = null;
        this.idperiod = null;
        this.month = null;
        this.type = null;
    }
}

export class policyHeader {
    idpolicie: string;
    correlative: string;
    date: string;
    type: string;
    description: string;
    id_period: string;
    detail: accountingPolicies[];
    constructor() {
        this.idpolicie = null;
        this.correlative = null;
        this.date = null;
        this.type = null;
        this.description = null;
        this.id_period = null;
        this.detail = [];
    }
}

export class selectedOption {
    id: number
    description: string;
    constructor(Aid: number, Adesc: string) {
        this.id = Aid;
        this.description = Adesc;
    }
}

export class accountingPolicies {
    external_id: string;
    amount: string;
    id_account_py: string;
    department: string;
    class: string;
    site: string;
    clientNetSuite: string;
    id_client: string;
    idaccounts: string;
    clasif: string;
    name: string;
    idaccounting_accounts: string;
    constructor() {
        this.external_id = null;
        this.amount = null;
        this.id_account_py = null;
        this.department = null;
        this.class = null;
        this.site = null;
        this.clientNetSuite = null;
        this.id_client = null;
        this.idaccounts = null;
        this.clasif = null;
        this.name = null;
        this.idaccounting_accounts = null;
    }
}

export class AccountingAccounts {
    idaccounting_accounts: string;
    external_id: string;
    name: string;
    clasif: string;
    idperiod: string;
    constructor() {
        this.idaccounting_accounts = null;
        this.external_id = null;
        this.name = null;
        this.clasif = null;
        this.idperiod = null;
    }
}

export class timekeeping_adjustments{
    idtimekeeping_adjustments:string;
    id_payment:string;
    amount_hrs:string;
    amount_ot:string;
    amount_holidays:string;
    nearsol_id:string;
    client_id:string;
    name:string;
    constructor(){
        this.idtimekeeping_adjustments = null;
        this.id_payment = null;
        this.amount_hrs = null;
        this.amount_ot = null;
        this.amount_holidays = null;
        this.nearsol_id = null;
        this.client_id = null;
        this.name = null;
    }
}

export class billing {
    month: string;
    account: string;
    detail: billing_detail[];
    constructor() {
        this.month = null;
        this.account = null;
        this.detail = [];
    }
}

export class billing_detail {
    avaya:string;
    name:string;
    account:string;
    nearsol_id:string;
    minimum_wage:string;
    incentive:string;
    days_discounted:string;
    deduction_7th:string;
    discounted_hours:string;
    minimum_wage_deductions:string;
    incentive_deductions:string;
    minimum_wage_with_deductions:string;
    incentive_with_deductions:string;
    overtime_hours:string;
    overtime:string;
    holiday_hours:string;
    holiday:string;
    bonuses:string;
    treasure_hunt:string;
    adjustments:string;
    total_income:string;
    bus:string;
    parking_car:string;
    parking_motorcycle_bicycle:string;
    igss:string;
    isr:string;
    equipment:string;
    total_deductions:string;
    total_payment:string;
    bonus_13:string;
    bonus_13_bonif:string;
    bonus_14:string;
    bonus_14_bonif:string;
    vacation_reserves:string;
    vacation_reserves_bonif:string;
    severance_reserves:string;
    employer_igss:string;
    health_insurance:string;
    parking:string;
    bus_client:string;
    total_reserves_and_fees:string;
    total_cost:string;
    constructor() {
        this.avaya = null;
        this.name = null;
        this.account = null;
        this.nearsol_id = null;
        this.minimum_wage = null;
        this.incentive = null;
        this.days_discounted = null;
        this.deduction_7th = null;
        this.discounted_hours = null;
        this.minimum_wage_deductions = null;
        this.incentive_deductions = null;
        this.minimum_wage_with_deductions = null;
        this.incentive_with_deductions = null;
        this.overtime_hours = null;
        this.overtime = null;
        this.holiday_hours = null;
        this.holiday = null;
        this.bonuses = null;
        this.treasure_hunt = null;
        this.adjustments = null;
        this.total_income = null;
        this.bus = null;
        this.parking_car = null;
        this.parking_motorcycle_bicycle = null;
        this.igss = null;
        this.isr = null;
        this.equipment = null;
        this.total_deductions = null;
        this.total_payment = null;
        this.bonus_13 = null;
        this.bonus_13_bonif = null;
        this.bonus_14 = null;
        this.bonus_14_bonif = null;
        this.vacation_reserves = null;
        this.vacation_reserves_bonif = null;
        this.severance_reserves = null;
        this.employer_igss = null;
        this.health_insurance = null;
        this.parking = null;
        this.bus_client = null;
        this.total_reserves_and_fees = null;
        this.total_cost = null;
    }
}

export class sendmailRes{
    retunr_text:string;
    constructor(){
        this.retunr_text = null;
    }
}

export class paystubview{
    result:string;
    client_id:string;
    content:string;
    idpaystub_deatils:string;
    email:string;
    society:string;
    account:string;
    employeer_nit:string;
    idpayments:string;
    start:string;
    end:string;
    nit:string;
    type:string;
    number:string;
    iggs:string;
    user_name:string;
    bank:string;
    days_of_period:string;
    discounted_days:string;
    ot_hours:string;
    discounted_hours:string;
    holidays_hours:string;
    base:string;
    ot:string;
    holidays:string;
    decreto:string;
    bonificaciones:string;
    eficiencia:string;
    ajustes:string;
    igss_amount:string;
    otras_deducciones:string;
    anticipos:string;
    isr:string;
    employee_name:string;
    nearsol_id:string;
    parqueo:string;
    total_cred:string;
    total_deb:string;
    liquido:string;
    select:boolean;
    ignore:boolean;
    constructor(){
        this.result = null;
        this.client_id = null;
        this.content = null;
        this.idpaystub_deatils = null;
        this.email = null;
        this.society = null;
        this.account = null;
        this.employeer_nit = null;
        this.idpayments = null;
        this.start = null;
        this.end = null;
        this.nit = null;
        this.type = null;
        this.number = null;
        this.iggs = null;
        this.user_name = null;
        this.bank = null;
        this.days_of_period = null;
        this.discounted_days = null;
        this.ot_hours = null;
        this.discounted_hours = null;
        this.holidays_hours = null;
        this.base = null;
        this.ot = null;
        this.holidays = null;
        this.decreto = null;
        this.bonificaciones = null;
        this.eficiencia = null;
        this.ajustes = null;
        this.igss_amount = null;
        this.otras_deducciones = null;
        this.anticipos = null;
        this.isr = null;
        this.employee_name = null;
        this.nearsol_id = null;
        this.parqueo = null;
        this.total_cred = null;
        this.total_deb = null;
        this.liquido = null;
        this.select = false;
        this.ignore = false;
    }
}
export class contractCheck{
    name:string;
    birthday:string;
    year:string;
    today:string;
    now:string;
    age:string;
    platform:string;
    gender:string;
    address:string;
    municipio:string;
    dpi_n:string;
    hiring_date:string;
    job:string;
    base_n_n:string;
    incentivo_n_n:string;
    total_n_n:string;
    base_n:string;
    incentivo_exp:string;
    incentivo_n:string;
    total_n:string;
    dpi_1:string;
    dpi_2:string;
    dpi_3:string;
    dpi_4:string;
    t:string;
    base_n_init:string;
    base_n_int_l:string;
    base_n_cent_l:string;
    incentivo_n_init:string;
    incentivo_n_int_l:string;
    incentivo_n_cent_l:string;
    total_n_init:string;
    total_n_int_l:string;
    total_n_cent_l:string;
    dt:string;
    date_letters:string;
    today_date:string;
    dt_end:string;
    day:string;
    mn:string;
    yr:string;
    constructor(){
        this.name = null;
        this.birthday = null;
        this.year = null;
        this.today = null;
        this.now = null;
        this.age = null;
        this.platform = null;
        this.gender = null;
        this.address = null;
        this.municipio = null;
        this.dpi_n = null;
        this.hiring_date = null;
        this.job = null;
        this.base_n_n = null;
        this.incentivo_n_n = null;
        this.total_n_n = null;
        this.base_n = null;
        this.incentivo_exp = null;
        this.incentivo_n = null;
        this.total_n = null;
        this.dpi_1 = null;
        this.dpi_2 = null;
        this.dpi_3 = null;
        this.dpi_4 = null;
        this.t = null;
        this.base_n_init = null;
        this.base_n_int_l = null;
        this.base_n_cent_l = null;
        this.incentivo_n_init = null;
        this.incentivo_n_int_l = null;
        this.incentivo_n_cent_l = null;
        this.total_n_init = null;
        this.total_n_int_l = null;
        this.total_n_cent_l = null;
        this.dt = null;
        this.date_letters = null;
        this.today_date = null;
        this.dt_end = null;
        this.day = null;
        this.mn = null;
        this.yr = null;
    }
}


export class employeesByWaves {
    id_profile: string;
    idemployees: string;
    id_account: string;
    account: string;
    job: string;
    name: string;
    day_of_birth: string;
    client_id: string;
    nearsol_id: string;
    hiring_date: string;
    state: string;
    active: string;
    action: string;
    constructor(){
        this.id_profile = null;
        this.idemployees = null;
        this.id_account = null;
        this.account = null;
        this.job = null;
        this.name = null;
        this.day_of_birth = null;
        this.client_id = null;
        this.nearsol_id = null;
        this.hiring_date = null;
        this.state = null;
        this.active = null;
        this.action = null;
    }
}

export class clauses {
  idclauses: string;
  name: string;
  description: string
  constructor() {
    this.idclauses = null;
    this.name = null;
    this.description = null;
  }
}

export class contract_templates {
  idtemplates: string;
  name: string;
  constructor() {
    this.idtemplates = null;
    this.name = null;
  }

  public set _idtemplates(value : string) {
    this.idtemplates = value;
  }

  public get _idtemplates() : string {
    return this.idtemplates;
  }

  public set _name(value : string) {
    this.name = value;
  }

  public get _name() : string {
    return this.name;
  }

}

export class clauses_templates {
  idclauses_templates: string;
  id_clause: string;
  id_template: string;
  ordernum: string;
  tag: string;
  selected: boolean;
  private oldordernum: string;
  private newordernum: string;
  constructor() {
    this.idclauses_templates = null;
    this.id_clause = null;
    this.id_template = null;
    this.ordernum = null;
    this.tag = null;
    this.selected = false;
    this.oldordernum = null;
    this.newordernum = null;
  }

  setnewordernum(val: string) {
    this.oldordernum = this.ordernum;
    this.newordernum = val;
    this.ordernum = val;
  }

  getoldordernum(): string {
    return this.oldordernum;
  }
 }

export class all_templates extends clauses_templates {
  nameTemplate: string;
  clauses: clauses[];
  constructor() {
    super();
    this.nameTemplate = null;
    this.clauses = [];
  }
}

export class tk_import{
    idpayments:string;
    client_bonus:string;
    nearsol_bonus:string;
    treasure_hunt:string;
    adjust_hrs:string;
    adjust_ot:string;
    adjust_hld:string;
    constructor(){
        this.idpayments = null;
        this.client_bonus = null;
        this.nearsol_bonus = null;
        this.treasure_hunt = null;
        this.adjust_hrs = null;
        this.adjust_ot = null;
        this.adjust_hld = null;
    }
}

export class roster_types{
    idroster_types:string;
    tag:string;
    name:string;
    id_time_mon:string;
    id_time_tue:string;
    id_time_wed:string;
    id_time_thur:string;
    id_time_fri:string;
    id_time_sat:string;
    id_time_sun:string;
    mon_start:string;
    mon_end:string;
    tue_start:string;
    tue_end:string;
    wed_start:string;
    wed_end:string;
    thur_start:string;
    thur_end:string;
    fri_start:string;
    fri_end:string;
    sat_start:string;
    sat_end:string;
    sun_start:string;
    sun_end:string;
    constructor(){
        this.idroster_types = null;
        this.tag = null;
        this.name = null;
        this.id_time_mon = null;
        this.id_time_tue = null;
        this.id_time_wed = null;
        this.id_time_thur = null;
        this.id_time_fri = null;
        this.id_time_sat = null;
        this.id_time_sun = null;
        this.mon_start = null;
        this.mon_end = null;
        this.tue_start = null;
        this.tue_end = null;
        this.wed_start = null;
        this.wed_end = null;
        this.thur_start = null;
        this.thur_end = null;
        this.fri_start = null;
        this.fri_end = null;
        this.sat_start = null;
        this.sat_end = null;
        this.sun_start = null;
        this.sun_end = null;
    }
}

export class roster_times{
    idroster_times:string;
    start:string;
    end:string;
    fixed:string;
    constructor(){
        this.idroster_times = null;
        this.start = null;
        this.end = null;
        this.fixed = null;
    }
}

export class rosters{
    idrosters:string;
    name:string;
    nearsol_id:string;
    client_id:string;
    mon_start:string;
    mon_end:string;
    tue_start:string;
    tue_end:string;
    wed_start:string;
    wed_end:string;
    thur_start:string;
    thur_end:string;
    fri_start:string;
    fri_end:string;
    sat_start:string;
    sat_end:string;
    sun_start:string;
    sun_end:string;
    week_value:string;
    count:string;
    showed:string;
    id_employee:string;
    id_schedule:string;
    status:string;
    id_period:string;
    id_account:string;
    tag:string;
    roster_name:string;
    mon_fixed:string;
    tue_fixed:string;
    wed_fixed:string;
    thur_fixed:string;
    fri_fixed:string;
    sat_fixed:string;
    sun_fixed:string;
    constructor(){
        this.idrosters = null;
        this.name = null;
        this.nearsol_id = null;
        this.client_id = null;
        this.mon_start = null;
        this.mon_end = null;
        this.tue_start = null;
        this.tue_end = null;
        this.wed_start = null;
        this.wed_end = null;
        this.fri_start = null;
        this.fri_end = null;
        this.sat_start = null;
        this.sun_start = null;
        this.week_value = null;
        this.count = null;
        this.showed = null;
        this.id_employee = null;
        this.status = null;
        this.id_period = null;
        this.id_account = null;
        this.tag = null;
        this.roster_name = null;
        this.mon_fixed = null;
        this.tue_fixed = null;
        this.wed_fixed = null;
        this.thur_fixed = null;
        this.fri_fixed = null;
        this.sat_fixed = null;
        this.sun_fixed = null;
    }
}

export class roster_views{
    idrosters:string;
    id_account:string;
    id_employee:string;
    nearsol_id:string;
    client_id:string;
    name:string;
    day_1:string;
    day_2:string;
    day_3:string;
    day_4:string;
    day_5:string;
    day_6:string;
    day_7:string;
    day_8:string;
    day_9:string;
    day_10:string;
    day_11:string;
    day_12:string;
    day_13:string;
    day_14:string;
    day_15:string;
    day_16:string;
    fixed_1:string;
    fixed_2:string;
    fixed_3:string;
    fixed_4:string;
    fixed_5:string;
    fixed_6:string;
    fixed_7:string;
    fixed_8:string;
    fixed_9:string;
    fixed_10:string;
    fixed_11:string;
    fixed_12:string;
    fixed_13:string;
    fixed_14:string;
    fixed_15:string;
    fixed_16:string;
    att_status_1:string;
    att_status_2:string;
    att_status_3:string;
    att_status_4:string;
    att_status_5:string;
    att_status_6:string;
    att_status_7:string;
    att_status_8:string;
    att_status_9:string;
    att_status_10:string;
    att_status_11:string;
    att_status_12:string;
    att_status_13:string;
    att_status_14:string;
    att_status_15:string;
    att_status_16:string;
    id_period:string;
    status:string;
    constructor(){
        this.idrosters = null;
        this.id_account = null;
        this.id_employee = null;
        this.nearsol_id = null;
        this.client_id = null;
        this.name = null;
        this.day_1 = null;
        this.day_2 = null;
        this.day_3 = null;
        this.day_4 = null;
        this.day_5 = null;
        this.day_6 = null;
        this.day_7 = null;
        this.day_8 = null;
        this.day_9 = null;
        this.day_10 = null;
        this.day_11 = null;
        this.day_12 = null;
        this.day_13 = null;
        this.day_14 = null;
        this.day_15 = null;
        this.day_16 = null;
        this.fixed_1 = null;
        this.fixed_2 = null;
        this.fixed_3 = null;
        this.fixed_4 = null;
        this.fixed_5 = null;
        this.fixed_6 = null;
        this.fixed_7 = null;
        this.fixed_8 = null;
        this.fixed_9 = null;
        this.fixed_10 = null;
        this.fixed_11 = null;
        this.fixed_12 = null;
        this.fixed_13 = null;
        this.fixed_14 = null;
        this.fixed_15 = null;
        this.fixed_16 = null;
        this.att_status_1 = '1';
        this.att_status_2 = '1';
        this.att_status_3 = '1';
        this.att_status_4 = '1';
        this.att_status_5 = '1';
        this.att_status_6 = '1';
        this.att_status_7 = '1';
        this.att_status_8 = '1';
        this.att_status_9 = '1';
        this.att_status_10 = '1';
        this.att_status_11 = '1';
        this.att_status_12 = '1';
        this.att_status_13 = '1';
        this.att_status_14 = '1';
        this.att_status_15 = '1';
        this.att_status_16 = '1';
        this.id_period = null;
        this.status = null;
    }
}

export class tk_upload{
    idtk_import:string;
    date:string;
    path:string;
    constructor(){
        this.idtk_import = null;
        this.date = null;
        this.path = null;
    }
}

export class roster_weeks{
    id_employee:string;
    id_period:string;
    mon_start:string;
    mon_end:string;
    mon_fixed:string;
    tue_start:string;
    tue_end:string;
    tue_fixed:string;
    wed_start:string;
    wed_end:string;
    wed_fixed:string;
    thur_start:string;
    thur_end:string;
    thur_fixed:string;
    fri_start:string;
    fri_end:string;
    fri_fixed:string;
    sat_start:string;
    sat_end:string;
    sat_fixed:string;
    sun_start:string;
    sun_end:string;
    sun_fixed:string;
    constructor(){
        this.id_employee = null;
        this.id_period = null;
        this.mon_start = null;
        this.mon_end = null;
        this.mon_fixed = null;
        this.tue_start = null;
        this.tue_end = null;
        this.tue_fixed = null;
        this.wed_start = null;
        this.wed_end = null;
        this.wed_fixed = null;
        this.thur_start = null;
        this.thur_end = null;
        this.thur_fixed = null;
        this.fri_start = null;
        this.fri_end = null;
        this.fri_fixed = null;
        this.sat_start = null;
        this.sat_end = null;
        this.sat_fixed = null;
        this.sun_start = null;
        this.sun_end = null;
        this.sun_fixed = null;
    }
}
