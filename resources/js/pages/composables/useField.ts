
import country from '../../data/country.json'
import phoneCode from '../../data/phoneCodes.json'


export  function useFiledStore(){
const statusOptions = [
    { label: "0/9 - Handled by Hosted Center", value: "0_handled_by_hosted_center" },

    // 1/9 - Recruitment form validation
    { label: "1/9 - Recruitment Form Validation", value: "1_recruitment_form_validation" },

    // 2/9 - Job announcement process
    { label: "2/9 - Job Announcement Process", value: "2_job_announcement_process" },
    { label: "2/9-a - Translation Ongoing", value: "2a_translation_ongoing" },
    { label: "2/9-b - Translation Received", value: "2b_translation_received" },
    { label: "2/9-c - Advertising", value: "2c_advertising" },
    { label: "2/9-d - Selection Criteria Request", value: "2d_selection_criteria_request" },
    { label: "2/9-e - Selection Criteria Transmission", value: "2e_selection_criteria_transmission" },

    // 3/9 - Longlist process
    { label: "3/9 - Longlist Process", value: "3_longlist_process" },
    { label: "3/9-a - Application Downloading", value: "3a_application_downloading" },
    { label: "3/9-b - Longlist Matrix", value: "3b_longlist_matrix" },

    // 4/9 - Shortlist process
    { label: "4/9 - Shortlist Process", value: "4_shortlist_process" },
    { label: "4/9-a - Shortlist Matrix Ongoing", value: "4a_shortlist_matrix_ongoing" },
    { label: "4/9-b - Shortlist Matrix Consolidation", value: "4b_shortlist_matrix_consolidation" },
    { label: "4/9-c - Shortlist Matrix Approval", value: "4c_shortlist_matrix_approval" },

    // 5/9 - Interview process
    { label: "5/9 - Interview Process", value: "5_interview_process" },
    { label: "5/9-a - Interview Date Plan", value: "5a_interview_date_plan" },
    { label: "5/9-b - Rating Sheet Transmission", value: "5b_rating_sheet_transmission" },

    // 6/9 - Recruitment report
    { label: "6/9 - Recruitment Report", value: "6_recruitment_report" },
    { label: "6/9-a - Report To Send", value: "6a_report_to_send" },
    { label: "6/9-b - Report Sent", value: "6b_report_sent" },
    { label: "6/9-c - Report Signature Process", value: "6c_report_signature_process" },
    { label: "6/9-d - Report Approved Process", value: "6d_report_approved_process" },

    // 7/9 - Offer process
    { label: "7/9 - Offer Process", value: "7_offer_process" },
    { label: "7/9-a - Offer Drafted", value: "7a_offer_drafted" },
    { label: "7/9-b - Offer Sent", value: "7b_offer_sent" },
    { label: "7/9-c - Offer Negotiation", value: "7c_offer_negotiation" },
    { label: "7/9-d - Offer Accepted", value: "7d_offer_accepted" },
    { label: "7/9-e - Offer Rejected", value: "7e_offer_rejected" },

    // 8/9 - Reference Check
    { label: "8/9 - Reference Check", value: "8_reference_check" },
    { label: "8/9-a - Reference Details Acknowledge", value: "8a_reference_details_acknowledge" },
    { label: "8/9-b - Emails To Send", value: "8b_emails_to_send" },
    { label: "8/9-c - References To Receive", value: "8c_references_to_receive" },

    // 9/9 - Contract Drafting
    { label: "9/9 - Contract Drafting", value: "9_contract_drafting" },
    { label: "9/9-a - TORS Signature", value: "9a_tors_signature" },
    { label: "9/9-b - Contract To Send", value: "9b_contract_to_send" },
    { label: "9/9-c - Contract Sent", value: "9c_contract_sent" },
    { label: "9/9-d - Countersigned Contract To Send", value: "9d_countersigned_contract_to_send" },
    { label: "9/9-e - Process Finalized", value: "9e_process_finalized" }
];

            const fieldAddOffre = [
                [
                    { type: "text", key: "reference", label: "Post Reference" },
                ],
                
                [
                    { type: "text", key: "position_title", label: "Post Title" },
                ],
                [
                    { type: "text", key: "program", label: "Program",
                        
                    },
                ],
                [
                    { type: "select", options:[...country], key: "country_duty_station", label: "Country" },
                    { type: "text", key: "city_duty_station", label: "City duty station" },
                ],
                [
                    { type: "select", options:[
                        {label:"GSS1",value:"GSS1"},
                        {label:"GSS2",value:"GSS2"},
                        {label:"GSS3",value:"GSS3"},
                        {label:"GSS4",value:"GSS4"},
                        {label:"GSS5",value:"GSS5"},
                        {label:"GSS6",value:"GSS6"},
                        {label:"GSS7",value:"GSS7"},
                        {label:"GSS8",value:"GSS8"},
                        {label:"GSS9",value:"GSS9"},
                        {label:"IRSPDF",value:"IRSPDF"},
                        {label:"IRSAPS",value:"IRSAPS"},
                        {label:"IRSPS",value:"IRSPPS"},
                        {label:"IRSEPS",value:"IRSPEPS"},
                        {label:"Consultant Local",value:"consultant_local"},
                        {label:"Consultant International",value:"consultant_international"},
                    ], key: "grade", label: "Grade" },
                    { type: "select",options:[
                        {label:"Corporate",value:'corporate'},
                        {label:"Recherche",value:'recherche'},
                        {label:"DGO",value:'DGO'},
                        {label:"Finance",value:'finance'},
                    ], key: "division", label: "Division" },
                ],
                [
                    { type: "select", options:[], key: "assign_by", label: "Assign to" },
                ],
                [
                    { type: "text", key: "manager", label: "Manager fullname" },
                ],
                [
                { type: "select", options:[ 
                        { label: 'Pending', value: 'pending' },
                        { label: 'Open', value: 'open' },
                        { label: 'On Hold', value: 'on_hold' },
                        { label: 'Closed', value: 'closed' },
                        { label: 'Cancelled', value: 'cancelled' },
                        ],
                        key: "status", label: "Status" },
                    { type: "select",  options: [
                            { label: 'AfricaRice', value: 'africarice' },
                            { label: 'Alliance Bioversity & CIAT', value: 'alliance_bioversity_ciat' },
                            { label: 'CIFOR', value: 'cifor' },
                            { label: 'CIMMYT', value: 'cimmyt' },
                            { label: 'CIP', value: 'cip' },
                            { label: 'ICARDA', value: 'icarda' },
                            { label: 'ICRAF', value: 'icraf' },
                            { label: 'ICRISAT', value: 'icrisat' },
                            { label: 'IFPRI', value: 'ifpri' },
                            { label: 'IITA', value: 'iita' },
                            { label: 'ILRI', value: 'ilri' },
                            { label: 'IRRI', value: 'irri' },
                            { label: 'IWMI', value: 'iwmi' },
                            { label: 'WorldFish', value: 'worldfish' },
                        ], key: "center", label: "Center" },
                ],
                [
                    { type: "select", options:[
                        {label:'New', value:'new'},
                        {label:'Replacement', value:'replacement'},
                    ], key: "reason", label: "Reason" },

                    { type: "select", options:[
                        {label:'public', value:'public'},
                        {label:'internal', value:'internal'},
                    ], key: "type", label: "Publication type" },
                ],
                [
                    { type: "text", key: "reason_replacement", label: "Replacement fullname" },
                ],
                [
                    { type: "date", key: "published_at", label: "Published On / Publié le" },
                    { type: "date", key: "expires_at", label: "Closing Date / Date de clôture" },
                ],
            ]
    
            const fieldAddOffreTracker =[
                                    [
                                        {
                                        type: "select",
                                        key: "status",
                                        label: "Status",
                                        placeholder: "Select status",
                                    options: [...statusOptions]
                                        },
                                    ],

                                    [
                                        {
                                        type: "text",
                                        key: "comment",
                                        label: "Comment",
                                        placeholder: "Add a comment",
                                        },
                                    ],

                                    [
                                        {
                                        type: "date",
                                        key: "date",
                                        label: "Date",
                                        placeholder: "Select date",
                                        },
                                    ],
                                    ]
    
            const fieldAddDocument = [
                { type: "file", key: "resume", label: "Upload File / Importer un fichier" },
            ]
    
            const fieldDiploma = [
                [
                    { type: "select",options:[
                        {label:"Doctorat/Doctorate", value:"doctorate"},
                        {label:"Maîtrise/Master", value:"Maîtrise/Master"},
                        {label:"Licence/ Bachelor’s  degree", value:"Licence/ Bachelor’s  degree"},
                        {label:"BTS", value:"BTS"},
                        {label:"Baccalauréat/ Baccalaureate", value:"baccalaureate"},
                        {label:"BEPC", value:"BEPC"},
                        {label:"CEPE", value:"CEPE"},
                        {label:"Autre", value:"autre"},
                    ], key: "diploma", label: "Diploma / Diplôme" },
                    { type: "text", key: "option", label: "Diploma Option / Option du diplôme" },
                ],
            ]
    
            const fieldExperience = [
                [
                    { type: "text", key: "company_name", label: "Company Name / Nom de l’entreprise" },
                    { type: "text", key: "position", label: "Position / Poste" },
                ],
                [
                    { type: "date", key: "start_date", label: "Start Date / Date de début" },
                    { type: "date", key: "end_date", label: "End Date / Date de fin" },
                ],
                [
                    { type: "checkbox", key: "current", label: "Are you still currently working in this position ? / Travaillez-vous encore actuellement à ce poste ? :" },
                ],
            ]
    
            const cgiarFiled = [
                [
                    { type: "checkbox", key: "current", label: "Check if you have previously worked, or are currently working, for a CGIAR center. / Travaillez-vous encore actuellement à ce poste ? :" },
                ],
                [
                    { 
                        type: "select", 
                        key: "cgiar_center",
                        options: [
                            { label: 'AfricaRice', value: 'africarice' },
                            { label: 'Alliance Bioversity & CIAT', value: 'alliance_bioversity_ciat' },
                            { label: 'CIFOR', value: 'cifor' },
                            { label: 'CIMMYT', value: 'cimmyt' },
                            { label: 'CIP', value: 'cip' },
                            { label: 'ICARDA', value: 'icarda' },
                            { label: 'ICRAF', value: 'icraf' },
                            { label: 'ICRISAT', value: 'icrisat' },
                            { label: 'IFPRI', value: 'ifpri' },
                            { label: 'IITA', value: 'iita' },
                            { label: 'ILRI', value: 'ilri' },
                            { label: 'IRRI', value: 'irri' },
                            { label: 'IWMI', value: 'iwmi' },
                            { label: 'WorldFish', value: 'worldfish' },
                        ],
                        label: "Provide Name of the centers / Indiquez le nom du centre" 
                    },
                ],
                [
                    { type: "text", key: "cgiar_email", label: "Provide your cgiar.org email / Indiquez votre adresse e-mail cgiar.org" }
                ]
            ]
    
            const fieldExperienceOther = [
                [
                    { type: "text", key: "company_name", label: "Company Name / Nom de l’entreprise" },
                    { type: "text", key: "position", label: "Position / Poste" },
                ],
                [
                    { type: "date", key: "start_date", label: "Start Date / Date de début" },
                    { type: "date", key: "end_date", label: "End Date / Date de fin" },
                ],
            ]
    
            const fieldIdentification = [
                [
                    { type: "date", key: "birth_date", label: "Date of Birth / Date de naissance" },
                    { type: "text", key: "address", label: "Address / Adresse" },
                ],
                [
                    { 
                        type: "select", 
                        key: "gender", 
                        label: "Gender / Genre", 
                        options:[
                            {label: 'Male / Homme', value: 'male'},
                            {label: 'Female / Femme', value: 'female'},
                            {label: 'Other / Autre', value: 'other'},
                        ]
                    }
                ]
            ]
    
            const fieldOrigin = [
                [
                    { type: "select", options:[...country], key: "nationality", label: "Nationality / Nationalité" },
                    { type: "select", options:[...country], key: "second_nationality", label: "Second nationality / Deuxième nationalité" },
                    { type: "select", options:[...country], key: "country", label: "Country of Residence / Pays de résidence" },
                ],
                [
                    { type: "text", key: "city", label: "City of Residence / Ville de résidence" },
                    { type: "number", key: "experience_years", label: "Years of Experience / Années d’expérience" },
                ],
                [
                    { 
                        type: "select", 
                        key: "french_level", 
                        options:[
                            {label:'School / Scolaire', value:'school'},
                            {label:'Beginner / Débutant', value:'beginner'},
                            {label:'Intermediate / Intermédiaire', value:'intermediate'},
                            {label:'Advanced / Avancé', value:'advanced'},
                            {label:'Expert / Expert', value:'expert'},
                            {label:'Proficient / Compétent', value:'proficient'},
                            {label:'Native / Langue maternelle', value:'native'},
                        ],
                        label: "French Level / Niveau de français" 
                    },
                    { 
                        type: "select", 
                        key: "english_level", 
                        options:[
                            {label:'School / Scolaire', value:'school'},
                            {label:'Beginner / Débutant', value:'beginner'},
                            {label:'Intermediate / Intermédiaire', value:'intermediate'},
                            {label:'Advanced / Avancé', value:'advanced'},
                            {label:'Expert / Expert', value:'expert'},
                            {label:'Proficient / Compétent', value:'proficient'},
                            {label:'Native / Langue maternelle', value:'native'},
                        ],
                        label: "English Level / Niveau d’anglais" 
                    }
                ]
            ]
    
            const fieldDocument = [
                { type: "file", key: "resume", label: "Upload your CV / Téléversez votre CV", accept:".pdf,.doc,.docx,.jpg,.jpeg,.png" },
            ]
    
            const fieldReference = [
                [
                    { type: "text", key: "full_name", label: "Full Name / Nom complet" },
                    { type: "text", key: "email", label: "Email / Courriel" },
                ],
                [
                { type: "select-input",
                    options: [...phoneCode],
                    label: "Phone / Téléphone" ,
                    keySelect: "country_code", 
                    keyInput: "phone",
                },
                    // { type: "text", key: "phone", label: "Phone / Téléphone" },
                ],
                [
                    { type: "text", key: "function", label: "Function / Fonction" },
                ],
                [
                    { type: "text", key: "company", label: "Company / Entreprise" },
                ],
            ]


            return {
                fieldAddOffre,
                fieldAddOffreTracker,
                fieldAddDocument,
                fieldDiploma,
                fieldExperience,
                cgiarFiled,
                fieldExperienceOther,
                fieldIdentification,
                fieldOrigin,
                fieldDocument,
                fieldReference
            }
    
}