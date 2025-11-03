
import country from '../../data/country.json'


export  function useFiledStore(){

            const fieldAddOffre = [
                [
                    { type: "text", key: "reference", label: "Post Reference / Référence du poste" },
                ],
                [
                    { type: "text", key: "position_title", label: "Post Title / Titre du poste" },
                    { type: "select", options:[...country], key: "country_duty_station", label: "Country / Pays" },
                ],
                [
                    { type: "select", options:[...country], key: "assign_by", label: "Assign by" },
                ],
                [
                    { type: "text", key: "manager", label: "Manager fullname" },
                ],
                [
                { type: "select", options:[ { label: 'Pending', value: 'pending' },
                        { label: 'Published', value: 'published' },
                        { label: 'In Review', value: 'in_review' },
                        { label: 'On Hold', value: 'on_hold' },
                        { label: 'Closed', value: 'closed' },
                        { label: 'Archived', value: 'archived' },], key: "status", label: "Status" },
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
                    { type: "text", key: "refereason_replacementrence", label: "Replacement fullname" },
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
                                    options: [
                                            { label: "Pending", value: "pending" },               // L’offre vient d’être créée
                                            { label: "In Review", value: "in_review" },          // L’offre est en cours d’examen
                                            { label: "Shortlisted", value: "shortlisted" },      // Candidats présélectionnés
                                            { label: "Interview Scheduled", value: "interview_scheduled" }, // Entretiens programmés
                                            { label: "Interview Completed", value: "interview_completed" }, // Entretiens terminés
                                            { label: "Offer Sent", value: "offer_sent" },        // Offre envoyée aux candidats
                                            { label: "Offer Accepted", value: "offer_accepted" },// Offre acceptée par un candidat
                                            { label: "Closed", value: "closed" },               // L’offre est terminée / postes pourvus
                                            { label: "Archived", value: "archived" },           // Offre archivée pour historique
                                        ]
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
                    { type: "text", key: "diploma", label: "Diploma / Diplôme" },
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
            ]
    
            const cgiarFiled = [
                [
                    { type: "checkbox", key: "current", label: "Check if currently working for CGIAR center / Cocher si vous travaillez actuellement pour un centre du CGIAR" },
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
                    options: country.map(item => ({
                            label: item.code,
                            value: item.code
                        })),
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