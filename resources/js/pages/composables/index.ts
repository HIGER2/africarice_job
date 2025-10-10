
import { reactive, ref, toRaw } from "vue"
import ApplyStepDiplomas from '../components/ApplyStepDiplomas.vue';
import ApplyStepExperience from '../components/ApplyStepExperience.vue';
import ApplyStepIdentification from '../components/ApplyStepIdentification.vue';
import ApplyStepOrigin from '../components/ApplyStepOrigin.vue';
import ApplyStepEnd from "../components/ApplyStepEnd.vue";
import ApplyStepCgiar from "../components/ApplyStepCgiar.vue";
import { Inertia } from "@inertiajs/inertia";
import country from '../../data/country.json'
import * as XLSX from 'xlsx';
import { usePage } from '@inertiajs/vue3'
import JSZip from "jszip";
import { saveAs } from "file-saver";


export function useApplyForm(){

    
    const components=[
        ApplyStepDiplomas,
        ApplyStepCgiar,
        ApplyStepExperience,
        ApplyStepIdentification,
        ApplyStepOrigin,
        ApplyStepEnd
    ]

    // initial form values
    const initReference={
        uuid:null,
        full_name:'',
        function:'',
        phone:'',
        email:'',
    }
    const initDoc={
            uuid:null,
            file:null
    }
    const initDiploma ={
            uuid:null,
            diploma: "",
            option: "",
    }
    const initExperience  ={
            uuid:null,
            company_name: "",
            position: "",
            start_date: "",
            end_date: "",
            current: '',
    }
    const initCgiarInformation={
        uuid:null,
        current: false,
        cgiar_center: "",
        cgiar_email: "",
    }

    const initIdentification={
        uuid:null,
        birth_date: "",
        address: "",
        gender: "",
    }
    const initOrigin={
            uuid:null,
            nationality: "",
            country: "",
            city: "",
            experience_years: '',
            french_level: "",
            english_level: "",
        }

    // form fields 

    // const fieldAddOffre= [
    //     [
    //         { type: "text", key: "reference", label: "Reference du post" },
    //     ],
    //     [
    //         { type: "text", key: "position_title", label: "Titre du post" },
    //         { type: "select",options:[...country], key: "country_duty_station", label: "Pays" },
    //     ],
    //     [
    //     ],
    //     // [
    //     //     { type: "text", key: "reference", label: "ToR" },
    //     // ],
    //     [
    //         { type: "date", key: "published_at", label: "Publié le" },
    //         { type: "date", key: "expires_at", label: "Date de cloture" },
    //     ],
    //     [
    //         { type: "checkbox", key: "is_published", label: "Cocher pour publier" },
    //     ],
    // ]

    // const fieldAddDocument= [
    //     { type: "file", key: "resume", label: "" },
    // ]
    
    // const fieldDiploma= [
    //     [
    //         { type: "text", key: "diploma", label: "Diplôme " },
    //         { type: "text", key: "option", label: "Option Diplôme " },
    //     ],
    // ]
    // const fieldExperience=[
    //     [
    //     { type: "text", key: "company_name", label: "Nom de l'entreprise" },
    //     { type: "text", key: "position", label: "Fonction" },
    //     ],
    //     [
    //     { type: "date", key: "start_date", label: "Date de début" },
    //     { type: "date", key: "end_date", label: "Date de fin" },
    //     ],
        
    // ]
    // const cgiarFiled =[
    //     [
    //     { type: "checkbox", key: "current", label: "Cochez si vous y travaillez actuellement" },
    //     ],
    //     [
    //     { type: "text", key: "cgiar_center", label: "Dans lequel des centres CGIAR avez-vous déjà travaillé ?" },
    //     ],
    //     [
    //         { type: "text", key: "cgiar_email", label: "Indiquez votre email cgiar.org" }
    //     ]
    // ]
    // const fieldExperienceOther=[
    //     [
    //     { type: "text", key: "company_name", label: "Nom de l'entreprise" },
    //     { type: "text", key: "position", label: "Fonction" },
    //     ],
    //     [
    //     { type: "date", key: "start_date", label: "Date de début" },
    //     { type: "date", key: "end_date", label: "Date de fin" },
    //     ],
    // ]
    // const fieldIdentification= [
    //     [
    //     { type: "date", key: "birth_date", label: "Date de naissance" },
    //     { type: "text", key: "address", label: "Adresse" },
    //     ],
    //     [
    //     { type: "select", key: "gender", label: "Genre" ,options:[
    //     {label: 'homme',value: 'male'},
    //     {label: 'femme',value: 'female'},
    // ]}
    //     ]
    // ]
    // const fieldOrigin= [
    //     [
    //         { type: "select", options:[...country], key: "nationality", label: "Nationalité | Nationality" },
    //         { type: "select",options:[...country], key: "country", label: "Pays de résidence | Country of residence" },
    //     ],
    //     [
    //         { type: "text", key: "city", label: "Ville de résidence | City of residence" },
    //         { type: "number", key: "experience_years", label: "Années d'expérience" },
    //     ],
    //     [
    //         { type: "select", key: "french_level", 
    //             options:[
    //             {label:'school',value:'Scolaire'},
    //             {label:'Débutant',value:'beginner'},
    //             {label:'intermediate',value:'Intermédiaire'},
    //             {label:'advanced',value:'Avancé'},
    //             {label:'expert',value:'Expert'},
    //             {label:'proficient',value:'Maîtrise'},
    //             {label:'native',value:'Natif'},
    //             ],
    //             label: "Niveau français" },
    //         { type: "select", key: "english_level", 
    //             options:[
    //             {label:'school',value:'Scolaire'},
    //             {label:'Débutant',value:'beginner'},
    //             {label:'intermediate',value:'Intermédiaire'},
    //             {label:'advanced',value:'Avancé'},
    //             {label:'expert',value:'Expert'},
    //             {label:'proficient',value:'Maîtrise'},
    //             {label:'native',value:'Natif'},
    //             ],
    //             label: "Niveau anglais" }
    //     ]
    // ]
    // const fieldDocument= [
    //     { type: "file", key: "resume", label: "Charger votre CV",accept:".pdf,.doc,.docx,.jpg,.jpeg,.png" },
    //     // { type: "file", key: "cover/_letter", label: "Charger votre lettre de motivation" },
    // ]
    // const fieldReference= [
    //     [
    //         { type: "text", key: "full_name", label: "Nom et prénoms" },
    //         { type: "text", key: "email", label: "Email" },
    //     ],
    //     [
    //         { type: "text", key: "phone", label: "Téléphone" },
    //         { type: "text", key: "function", label: "Function" },
    //     ],
    // ]
        const fieldAddOffre= [
            [
                { type: "text", key: "reference", label: "Post Reference" },
            ],
            [
                { type: "text", key: "position_title", label: "Post Title" },
                { type: "select", options:[...country], key: "country_duty_station", label: "Country" },
            ],
            [
            ],
            [
                { type: "date", key: "published_at", label: "Published On" },
                { type: "date", key: "expires_at", label: "Closing Date" },
            ],
            [
                { type: "checkbox", key: "is_published", label: "Check to Publish" },
            ],
        ]

        const fieldAddDocument= [
            { type: "file", key: "resume", label: "" },
        ]

        const fieldDiploma= [
            [
                { type: "text", key: "diploma", label: "Diploma" },
                { type: "text", key: "option", label: "Diploma Option" },
            ],
        ]

        const fieldExperience=[
            [
                { type: "text", key: "company_name", label: "Company Name" },
                { type: "text", key: "position", label: "Position" },
            ],
            [
                { type: "date", key: "start_date", label: "Start Date" },
                { type: "date", key: "end_date", label: "End Date" },
            ],
        ]

        const cgiarFiled =[
            [
                { type: "checkbox", key: "current", label: "Check if currently working for CGIAR center" },
            ],
            [
                { type: "text", key: "cgiar_center", label: "Provide Name of the centers" },
            ],
            [
                { type: "text", key: "cgiar_email", label: "Provide your cgiar.org email" }
            ]
        ]

        const fieldExperienceOther=[
            [
                { type: "text", key: "company_name", label: "Company Name" },
                { type: "text", key: "position", label: "Position" },
            ],
            [
                { type: "date", key: "start_date", label: "Start Date" },
                { type: "date", key: "end_date", label: "End Date" },
            ],
        ]

        const fieldIdentification= [
            [
                { type: "date", key: "birth_date", label: "Date of Birth" },
                { type: "text", key: "address", label: "Address" },
            ],
            [
                { type: "select", key: "gender", label: "Gender", options:[
                    {label: 'Male', value: 'male'},
                    {label: 'Female', value: 'female'},
                ]}
            ]
        ]

        const fieldOrigin= [
            [
                { type: "select", options:[...country], key: "nationality", label: "Nationality" },
                { type: "select",options:[...country], key: "country", label: "Country of Residence" },
            ],
            [
                { type: "text", key: "city", label: "City of Residence" },
                { type: "number", key: "experience_years", label: "Years of Experience" },
            ],
            [
                { type: "select", key: "french_level", 
                    options:[
                        {label:'School', value:'school'},
                        {label:'Beginner', value:'beginner'},
                        {label:'Intermediate', value:'intermediate'},
                        {label:'Advanced', value:'advanced'},
                        {label:'Expert', value:'expert'},
                        {label:'Proficient', value:'proficient'},
                        {label:'Native', value:'native'},
                    ],
                    label: "French Level" 
                },
                { type: "select", key: "english_level", 
                    options:[
                        {label:'School', value:'school'},
                        {label:'Beginner', value:'beginner'},
                        {label:'Intermediate', value:'intermediate'},
                        {label:'Advanced', value:'advanced'},
                        {label:'Expert', value:'expert'},
                        {label:'Proficient', value:'proficient'},
                        {label:'Native', value:'native'},
                    ],
                    label: "English Level" 
                }
            ]
        ]

        const fieldDocument= [
            { type: "file", key: "resume", label: "Upload your CV", accept:".pdf,.doc,.docx,.jpg,.jpeg,.png" },
        ]

        const fieldReference= [
            [
                { type: "text", key: "full_name", label: "Full Name" },
                { type: "text", key: "email", label: "Email" },
            ],
            [
                { type: "text", key: "phone", label: "Phone" },
                { type: "text", key: "function", label: "Function" },
            ],
        ]

    const initialPayload={
        diplomas:{...initDiploma},
        cgiar_information:{...initCgiarInformation},
        experiences:{...initExperience},
        identification:{...initIdentification},
        origin:{...initOrigin},
        reference:{...initReference},
        documents:{...initDoc},
    }
    



    const form = reactive({
        diplomas: [{...initDiploma}],
        cgiar_information:{},
        experiences: [{...initExperience}],
        identification: {},
        origin: {},
        references: [{...fieldExperience}],
        documents:[{...initDoc},{...initDoc}],
    })
    const documentPreview = ref([]);
    const newEmail=reactive({
        email:'',
        is_primary: false
    })
    const newUser=reactive({
        email:'',
        last_name:"",
        name:""
    })
    // const form = reactive({
    //     diplomas: [{...initDiploma}],
    //     cgiar_information:{
    //         current: true,
    //         cgiar_center: "ICRAF",
    //         cgiar_email: "exemple@cgiar.org",
    //     },
    //     experience: [{...initExperience}],

    //     identification: {
    //         birth_date: "1995-05-20",
    //         address: "Abidjan, Cocody Riviera",
    //         gender: "male",
    //     },

    //     origin: {
    //         nationality: "Ivoirienne",
    //         country: "Côte d'Ivoire",
    //         city: "Abidjan",
    //         experience_years: 3,
    //         french_level: "Avancé",
    //         english_level: "Intermédiaire",
    //     },
    //     reference: [{ ...initReference }],
    //     documents:[{...initDoc},{...initDoc}],
    // })

    const newOffre=reactive({
        offre:{
            uuid:null,
            reference:null,
            position_title:"",
            country_duty_station:"france",
            published_at:"",
            expires_at:"",
            is_published:true,
        },
        document:[]
    })
    // const form = reactive({
    //     diplomas: {
    //         recent_diploma: "dqwdwq",
    //         recent_option: "dwqd",
    //         second_last_diploma: "wqdwq",
    //         second_last_option: "dwqdw",
    //         third_last_diploma: "dwqd",
    //         third_last_option: "qdwwqd",
    //     },
    //     experience: [
    //     {
    //         company_name_1: "",
    //         position_1: "",
    //         start_date_1: "",
    //         end_date_1: "",
    //         current_1: false,
    //         cgiar_center_1: "",
    //         cgiar_email_1: "",
    //     },
    //     {
    //         company_name_1: "",
    //         position_1: "",
    //         start_date_1: "",
    //         end_date_1: "",
    //     },
    //     {
    //         company_name_1: "",
    //         position_1: "",
    //         start_date_1: "",
    //         end_date_1: "",
    //     }
    //     ],
        
    //     identification: {
    //         birth_date: "",
    //         address: "",
    //         gender: "",
    //     },
    //     origin: {
    //         nationality: "",
    //         country: "",
    //         city: "",
    //         experience_years: "",
    //         french_level: "",
    //         english_level: "",
    //     },
    //     documents: {
    //         reference:[{...initReference}],
    //         resume: null,
    //         cover_letter: null,
    //     },
    // })

    let currentStep =ref(0)

    const nextStep = () => {
        if (currentStep.value < components.length - 1) {
        currentStep.value++
        }
    }

    
    const addReference = () => {
        form.reference.push({ ...initReference }); // crée un nouvel objet
        console.log(form.reference);
    }

    const removeReference = (index) => {
            form.reference.splice(index, 1)
    }

    const prevStep = () => {
        if (currentStep.value > 0) {
        currentStep.value--
        }
    }

   // file handler
    const handleFile = (event, sectionKey, fieldKey) => {
        const selected = event.target.files[0]
        if (selected) {
        form[sectionKey][fieldKey] = selected
        }
    }

    function validateForm(data) {
    let errors = [];

    // Vérification diplomas
    if (!data.diplomas || data.diplomas.length === 0) {
        errors.push("Au moins un diplôme est requis. | At least one diploma is required.");
    } else {
        data.diplomas.forEach((d, i) => {
            if (!d.diploma || !d.option) {
                errors.push(`Diplôme ${i + 1}: diplôme et option obligatoires. | Diploma ${i + 1}: diploma and option are required.`);
            }
        });
    }

    // Vérification cgiar_information
    if (data.cgiar_information.current && (!data.cgiar_information.cgiar_center || !data.cgiar_information.cgiar_email)) {
        errors.push("Les informations CGIAR (centre et email) sont obligatoires. | CGIAR information (center and email) is required.");
    }

    // Vérification expérience
    if (!data.experiences || data.experiences.length === 0) {
        errors.push("Au moins une expérience est requise. | At least one experience is required.");
    } else {
        data.experiences.forEach((exp, i) => {
            if (!exp.company_name || !exp.position || !exp.start_date) {
                errors.push(`Expérience ${i + 1}: nom de l’entreprise, poste et date de début obligatoires. | Experience ${i + 1}: company name, position, and start date are required.`);
            }
        });
    }

    // Vérification identification
    if (!data.identification || !data.identification.birth_date || !data.identification.gender) {
        errors.push("Les informations d’identification (date de naissance et genre) sont obligatoires. | Identification information (birth date and gender) is required.");
    }

    // Vérification origin
    if (!data.origin || !data.origin.nationality || !data.origin.country) {
        errors.push("Nationalité et pays d’origine obligatoires. | Nationality and country of origin are required.");
    }

    // Vérification références
    if (!data.references || data.references.length === 0) {
        errors.push("Au moins une référence est requise. | At least one reference is required.");
    } else {
        data.references.forEach((r, i) => {
            if (!r.full_name || !r.email || !r.phone) {
                errors.push(`Référence ${i + 1}: nom, email et téléphone obligatoires. | Reference ${i + 1}: full name, email, and phone are required.`);
            }
        });
    }

    // Vérification documents
    if (
        !data.documents ||
        data.documents.filter(doc => doc.file !== null && doc.file !== "").length < 1 &&
        documentPreview.value.length == 0
    ) {
        errors.push("Veuillez télécharger votre CV. | Please upload your CV.");
    }

    return errors;
}


    const submitForm = async (uuid:string) => {
    try {
            const errors = validateForm(form);
            if (errors.length > 0) {
            alert(errors.join("\n- "));
            return false
            } 
            
            if (!confirm("Would you like to confirm your application? n\ Voulez vous confirmer votre candidature ?")) {
                return false; // Annule l'envoi si l'utilisateur clique sur "Annuler"
            }
        const formData = new FormData();

        // Ajouter les données JSON
        formData.append('uuid',uuid );
        // formData.append('identification', JSON.stringify(form.identification));
        // formData.append('origin', JSON.stringify(form.origin));
        // formData.append('diplomas', JSON.stringify(form.diplomas));
        // formData.append('experience', JSON.stringify(form.experience));
        // formData.append('reference', JSON.stringify(form.reference));
        // formData.append('cgiar_information', JSON.stringify(form.cgiar_information));

        // // Ajouter les fichiers
        // form.documents.forEach((doc, index) => {
        //    if (doc.id) {
        //         formData.append(`documents[${index}][id]`, doc.id);
        //     }
        //     // Ajouter le fichier seulement s'il est valide
        //     if (doc.file instanceof File) {
        //         formData.append(`documents[${index}][file]`, doc.file);
        //     }
        // });

                        
        // Identification (objet simple)
        Object.keys(form.identification).forEach(key => {
        formData.append(`identification[${key}]`, form.identification[key]);
        });

        // Origin (objet simple)
        Object.keys(form.origin).forEach(key => {
        formData.append(`origin[${key}]`, form.origin[key]);
        });

        // Diplomas (tableau d’objets)
        form.diplomas.forEach((item, index) => {
        Object.keys(item).forEach(key => {
            formData.append(`diplomas[${index}][${key}]`, item[key]);
        });
        });

        // Experience (tableau d’objets)
        form.experiences.forEach((item, index) => {
        Object.keys(item).forEach(key => {
            formData.append(`experiences[${index}][${key}]`, item[key]);
        });
        });

        // Reference (tableau d’objets)
        form.references.forEach((item, index) => {
            Object.keys(item).forEach(key => {
                formData.append(`references[${index}][${key}]`, item[key]);
            });
        });

        // CGIAR Information (objet simple)
        if (form.cgiar_information.current) {
             Object.keys(form.cgiar_information).forEach(key => {
            formData.append(`cgiar_information[${key}]`, form.cgiar_information[key] || '');
            });
        }
       

        // Documents (fichiers déjà bien gérés)
        form.documents.forEach((doc, index) => {
        if (doc.id) formData.append(`documents[${index}][id]`, doc.id);
        if (doc.file) formData.append(`documents[${index}][file]`, doc.file);
        });
        // Ajouter l'UUID de la publication/job
        // formData.append('publication_uuid', publicationUuid.value); // mettre la valeur réelle

        // Envoi de la requête
        const response = await window.axios.post('/apply-job/save', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
        });

        console.log('Réponse du serveur :', response.data?.data?.message);

        alert('Votre candidature a été soumis avec succès. ✅ | Your application has been successfully submitted. ✅');

    } catch (error) {
        let message = error.response?.data?.message ? error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'

        if (error.response && error.response.data && error.response.data.errors) {
        // Récupère tous les messages d'erreur et les transforme en texte
        const messages = Object.values(error.response.data.errors)
            .flat() // aplatit les tableaux
            .join('\n');
        alert(messages);
        } else {
        alert(message);
        }
        // console.error('Erreur lors de l\'envoi du formulaire :', error.response?.data?.errors || error);
        // alert(message);
    }
    };

    async function submitOffre(data: any) {
    try {
        // Convertir reactive en objet natif
        const rawData = toRaw(data)

        // Créer FormData
        const formData = new FormData()

        // Ajouter les champs texte
        for (const key in rawData.offre) {
        formData.append(`offre[${key}]`, rawData.offre[key])
        }

        // Ajouter les fichiers
        rawData.document.forEach((file: File, index: number) => {
        formData.append(`documents[${index}]`, file)
        })

        // Envoyer via Axios
        const response = await axios.post('/manager/offre/add', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        })
        Inertia.reload()
    } catch (error: any) {
        let message = error.response?.data?.message ? error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'
        console.error('Erreur lors de l\'envoi du formulaire :', error.response || error);
         alert("❌ " + message);
        
    }
    }   

    const payLoad = (data: any) => {
        if (!data || typeof data !== 'object') {
            console.warn("payLoad appelé avec une donnée invalide :", data)
            return
        }

        Object.keys(data).forEach(key => {
            if (Array.isArray(data[key])) {
            if (key === 'documents') {
                documentPreview.value = data[key]
                return
            }
            data[key].length > 0
                ? form[key] = data[key]
                : form[key].push({ ...initialPayload[key] })
            }

            if (typeof data[key] === 'object' && !Array.isArray(data[key])) {
            form[key] = Object.assign({}, initialPayload[key], data[key])
            }
        })

        console.log(documentPreview.value)
    }

    const downloadZip = async (data) => {
        const zip = new JSZip();
        
        for (const file of data) {
            
         try {
                const fileUrl = `http://127.0.0.1:8001${file.path}`;
                const response = await fetch(file.path);
                console.log(response);
                if (!response.ok) throw new Error("Fichier introuvable");
                const blob = await response.blob();
                zip.file(file.name, blob);
            } catch (err) {
                console.error(`Impossible de récupérer le fichier ${file.name}:`, err);
            }
        }

        zip.generateAsync({ type: "blob" }).then(content => {
            saveAs(content, "job_cv.zip");
        });
    };
    // const payLoad = (data) => {
        
    //     Object.keys(data).forEach(key => {
    //         if (Array.isArray(data[key])) {
    //             if (key== 'documents') {
    //                 documentPreview.value = data[key]
    //                 return
    //             }
    //             data[key].length > 0 ?  form[key] =  data[key] : form[key].push({...initialPayload[key]})
    //         }
    //         if (typeof data[key] === 'object'  && !Array.isArray(data[key])) {
    //             form[key] = Object.assign({}, initialPayload[key], data[key]);
    //         }
    //     });

    //     // console.log(form);
        
    //     // if (data.) {
            
    //     // }
    //     console.log(documentPreview.value);
        
    // }
    function exportToExcel(columns:Array<any>, data:Array<any>, fileName:string = 'export.xlsx') {
    // Créer un tableau avec les labels comme première ligne
    const headers = columns.map(col => col.label);

    // Mapper les données en prenant les valeurs correspondantes aux keys
    // const rows = data.map(row =>
    //     columns.map(col => row[col.key] != null ? row[col.key] : '')
    // );

     const rows = data.map(row =>
        columns.map(col => {
        const value = row[col.key];

        if (Array.isArray(value)) {
            // Si c'est un tableau, on le transforme en string séparée par des virgules
                return value
                .map(item => {
                if (typeof item === 'object' && item !== null) {
                    // On peut choisir quels champs afficher, par ex name et path
                return Object.entries(item)
                .map(([k, v]) => `${k}: ${v}`)
                .join('; ');
                }
                return item;
                })
                .join(', ');
        } else if (value && typeof value === 'object') {
            // Si c'est un objet, on peut le transformer en JSON
            return JSON.stringify(value);
        } else if (value != null) {
            return value;
        } else {
            return '';
        }
        })
    );


    // Créer la feuille Excel avec header + lignes
    const worksheetData = [headers, ...rows];
    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);

    worksheet['!cols'] = headers.map((h, i) => ({
    wch: Math.max(
        h.length,
        ...rows.map(r => (r[i] ? r[i].toString().length : 0))
    ) + 2 // un peu de marge
    }));

    // Créer le classeur et ajouter la feuille
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

    // Exporter le fichier
    XLSX.writeFile(workbook, fileName);
    }

    function updateOffres(status, uuid, type){
        Inertia.post('/manager/offre/update-status', {status, uuid, type}, {
            onSuccess: (data) => {
                console.log(data);
                
                Inertia.reload()
            },
            onError: (errors) => {
                console.error("Erreur :", errors);
            },
            onFinish: () => {

                console.log("Requête terminée");
            }
        });
    }


    async function AddEmail(){
        console.log(newEmail);
         try {
                const response = await axios.post("/manager/parametre/email", { ...newEmail });
                Inertia.reload();
            } catch (error) {
                    let message = error.response?.data?.message ? error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'
                    if (error.response && error.response.data && error.response.data.errors) {
                    // Récupère tous les messages d'erreur et les transforme en texte
                         message = Object.values(error.response.data.errors)
                        .flat() // aplatit les tableaux
                        .join('\n');
                    alert(message);
                    } else {
                    alert(message);
                    }
            } finally {
                console.log("Requête terminée");
            }

    }

    async function AddUser() {
            try {
                const response = await axios.post("/manager/parametre/user", { ...newUser });
                Inertia.reload();
            } catch (error) {
                    let message = error.response?.data?.message ? error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'
                    if (error.response && error.response.data && error.response.data.errors) {
                    // Récupère tous les messages d'erreur et les transforme en texte
                         message = Object.values(error.response.data.errors)
                        .flat() // aplatit les tableaux
                        .join('\n');
                    alert(message);
                    } else {
                    alert(message);
                    }
            } finally {
                console.log("Requête terminée");
            }
            }

   return {
    form,
    currentStep,
    handleFile,
    nextStep,
    prevStep,
    submitForm,
    components,
    fieldDiploma,
    fieldExperienceOther,
    fieldExperience,
    fieldIdentification,
    fieldOrigin,
    fieldDocument,
    fieldReference,
    addReference,
    removeReference,
    initReference,
    initDiploma,
    initExperience,
    cgiarFiled,
    fieldAddOffre,
    newOffre,
    fieldAddDocument,
    submitOffre,
    exportToExcel,
    payLoad,
    documentPreview,
    updateOffres,
    newEmail,
    AddEmail,
    newUser,
    AddUser,
    downloadZip
  }

}