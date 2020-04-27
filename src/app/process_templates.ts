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
    date:string;
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
    filter:string;
    filterValue:string;
    constructor(){
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