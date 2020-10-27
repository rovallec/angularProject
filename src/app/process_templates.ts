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
    bank:string;
    account:string;
    payType:string;
}

export class accounts {
    idaccounts: string;
    name: string;

    constructor() {
        this.idaccounts = null;
        this.name = null;
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
    constructor() {
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
        this.status = null;
        this.id_wave = null;
        this.balance = null;
    }
}

export class attendences_adjustment {
    //adjustments
    idattendence_adjustemnt: string;
    id_attendence: string;
    id_justification: string;
    time_before: string;
    time_after: string;
    amount: string;
    state: string;
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
    constructor() {
        //adjustments
        this.idattendence_adjustemnt = null;
        this.id_attendence = null;
        this.id_justification = null;
        this.time_before = null;;
        this.time_after = null;
        this.amount = null;
        this.state = null;
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
    }
}

export class vacations {
    //process
    id_user: string;
    id_employee: string;
    id_type: string;
    id_department: string;
    date: string;
    notes: string;
    status: string;
    //vacations
    action: string;
    count: string;
    took_date: string;
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
    }
}

export class leaves {
    //process
    id_user: string;
    id_employee: string;
    id_type: string;
    id_department: string;
    date: string;
    notes: string;
    status: string;
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
    constructor() {
        this.idadvances = null;
        this.id_process = null;
        this.type = null;
        this.description = null;
        this.classification = null;
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
    constructor() {
        this.idperiods = null;
        this.start = null;
        this.end = null;
        this.status = null;
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
        this.amount = null;
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
        this.amount = null;
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
    employee_name: string;
    total: string;
    status: string;
    constructor() {
        this.idpayments = null;
        this.id_employee = null;
        this.id_paymentmethod = null;
        this.id_period = null;
        this.credits = null;
        this.debits = null;
        this.date = null;
        this.employee_name = null;
        this.total = null;
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
    constructor() {
        this.idservices = null;
        this.id_process = null;
        this.id_employee = null;
        this.name = null;
        this.amount = null;
        this.max = null;
        this.frecuency = null;
        this.status = null;
    }
}

export class payment_methods{
    idpayment_methods:string;
    id_employee:string;
    type:string
    number:string
    bank:string;
    predeterm:string;
    constructor(){
        this.idpayment_methods = null;
        this.id_employee = null;
        this.type = null;
        this.number = null;
        this.bank = null;
        this.predeterm = null;
    }
}