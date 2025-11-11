
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
import {useFiledStore } from "./useField";
import { useRepository } from "./useRepository";
import { usePayloadStore} from "./usePayload";


export function useApplyForm(){

    const useFiled = useFiledStore()
    const repository=useRepository()
    const usePayload=usePayloadStore()
    
    const components=[
        ApplyStepIdentification,
        ApplyStepDiplomas,
        ApplyStepCgiar,
        ApplyStepExperience,
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
        country_code:"+225",
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
            current: false,
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

    const initialPayload={
        identification:{...initIdentification},
        diplomas:{...initDiploma},
        cgiar_information:{...initCgiarInformation},
        experiences:{...initExperience},
        origin:{...initOrigin},
        reference:{...initReference},
        documents:{...initDoc},
    }

    const form = reactive({
        diplomas: [],
        cgiar_information:{},
        experiences: [],
        identification: {},
        origin: {},
        references: [],
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
        name:"",
    })
    
    let currentStep =ref(0)
    function isEmpty(value) {
        return (
            value === null ||
            value === undefined ||
            value === false ||
            (typeof value === 'string' && value.trim() === '') ||
            (typeof value === 'number' && isNaN(value)) ||
            (Array.isArray(value) && value.length === 0) ||
            (typeof value === 'object' && !Array.isArray(value) && Object.keys(value).length === 0)
        )
        }

    function validateObjectById(obj, prefix) {
        let valid = true

        Object.keys(obj).forEach(key => {
            const el = document.getElementById(`${key}`)
            if (el) {
            const value = obj[key]
            if (isEmpty(value)) {
                el.classList.add('is-invalid')
                valid = false
            } else {
                el.classList.remove('is-invalid')
            }
            }
        })

        return valid
    }

    function validateArrayById(array, prefix) {
            let valid = true
            array.forEach((item, index) => {
                Object.keys(item).forEach(key => {
                const el = document.getElementById(`${index+key}`)
                if (el) {
                    const value = item[key]
                    if (isEmpty(value)) {
                    el.classList.add('is-invalid')
                    valid = false
                    } else {
                    el.classList.remove('is-invalid')
                    }
                }
                })
            })

            return valid
    }

    function validateStep(current, form) {
            let isValid = true

            switch (current) {
                case 0: // Identification
                isValid = validateObjectById(form.identification, 'identification')
                break
                case 1: // Diplomas
                isValid = validateArrayById(form.diplomas, 'diplomas')
                break
                case 2: // CGIAR information (exemple d’objet simple)
                if (form.cgiar_information.current) isValid = validateObjectById(form.cgiar_information, 'cgiar_information')
                break
                case 3: // Experiences 
                isValid = validateArrayById(form.experiences, 'experiences')
                break
                case 4: // Origin (objet simple) 
                isValid = validateObjectById(form.origin, 'origin')
                break
                case 5:  // References
                isValid = validateObjectById(form.reference, 'reference')
                break
                default:
                isValid = true
                break
            }

            if (!isValid) {
                console.warn("Certains champs sont vides")
                return false
            }

            // Passe à l’étape suivante si tout est valide
            if (currentStep.value < components.length - 1) {
                currentStep.value++
            }
            return true
    }

    const nextStep = (current:any,form) => {
        // console.log('====================================');
        // console.log();
        // console.log('====================================');
        let valid =  validateStep(current,form)
        if (!valid) return
        if (currentStep.value < components.length - 1) {
            currentStep.value++
        }
    }

    const setStep = (current:any) => {
        if (currentStep.value>current) currentStep.value = current
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
            if (!confirm("Do you confirm this operation?\nConfirmez-vous cette opération ?")) {
                return false; // Annule l'envoi si l'utilisateur clique sur "Annuler"
            }
        const formData = new FormData();
        formData.append('uuid',uuid ? uuid : '');
                        
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

        // console.log('Réponse du serveur :', response.data?.data?.message);

        
        const message = !uuid
            ? 'Vos informations ont été mises à jour avec succès. ✅ | Your information has been successfully updated. ✅'
            : 'Votre candidature a été soumise avec succès. ✅ | Your application has been successfully submitted. ✅';

        if (uuid) {
                alert(message);
        }else{
            Inertia.visit('/profile');
        }

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

    const submitCompleted = async () => {
    try {
            const errors = validateForm(form);
            if (errors.length > 0) {
            alert(errors.join("\n- "));
            return false
            } 
            
             if (!confirm("Do you confirm this operation?\nConfirmez-vous cette opération ?")) {
                return false; // Annule l'envoi si l'utilisateur clique sur "Annuler"
            }
            const formData = new FormData();

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
        const response = await window.axios.post('/completed/save', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
        });


        const message = 'Vos informations ont été mises à jour avec succès. ✅ | Your information has been successfully updated. ✅'
            Inertia.visit('/apply-spontaneous');

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

    async function submitOffre(data: any,callback:()=>void) {
    try {
        // Convertir reactive en objet natif
            if (!confirm("Would you like to confirm your application?")) {
                    return false; // Annule l'envoi si l'utilisateur clique sur "Annuler"
            }
                
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
        
        if (callback) callback()
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

        // console.log(documentPreview.value)
    }

    const downloadZip = async (data) => {
        const zip = new JSZip();
        
        for (const file of data) {
            
         try {
                const fileUrl = `http://127.0.0.1:8001${file.path}`;
                const response = await fetch(file.path);
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

    async function uploadCv(file:Blob) {
       
        const formData = new FormData()
        formData.append('cv', file)

        try {
            const response = await axios.post('/upload-cv', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: (progressEvent) => {
                const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                console.log(`Upload progress: ${percent}%`)
            },
            })

            alert('✅ CV uploaded successfully!')
            Inertia.reload()
            console.log('Upload response:', response.data)
        } catch (error) {

            let message = error.response?.data?.message ? error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'
            if (error.response && error.response.data && error.response.data.errors) {
            // Récupère tous les messages d'erreur et les transforme en texte
             message = Object.values(error.response.data.errors)
                .flat() // aplatit les tableaux
                .join('\n');
            } 
            alert(message);

        } finally {
          
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
    addReference,
    removeReference,
    initReference,
    initDiploma,
    initExperience,
    submitOffre,
    exportToExcel,
    payLoad,
    documentPreview,
    updateOffres,
    newEmail,
    AddEmail,
    newUser,
    AddUser,
    downloadZip,
    uploadCv,
    submitCompleted,
    setStep,
    // fieldAddOffreTracker,
    ...repository,
    ...useFiled,
    ...usePayload
    }

}