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
    payment: string;
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
        this.state = null;
        this.account_name = null;
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
    id_processes:string;
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
        this.start = null;
        this.end = null;
    }
}

export class insurances{
    idinsurances:string;
    id_process:string;
    plan:string;
    license:string;
    cert:string;
    contractor:string;
    place:string;
    reception:string;
    delivered:string;
    status:string;
    constructor(){
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

export class beneficiaries{
        idbeneficiaries:string;
        first_name:string;
        second_name:string;
        first_lastname:string;
        second_lastname:string;
        afinity:string;
        constructor(){
            this.idbeneficiaries = null;
            this.first_name = null;
            this.second_name = null;
            this.first_lastname = null;
            this.second_lastname = null;
            this.afinity = null;
        }
}