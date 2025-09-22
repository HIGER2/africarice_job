
import { reactive, ref, toRaw } from "vue"
import ApplyStepDiplomas from '../components/ApplyStepDiplomas.vue';
import ApplyStepExperience from '../components/ApplyStepExperience.vue';
import ApplyStepIdentification from '../components/ApplyStepIdentification.vue';
import ApplyStepOrigin from '../components/ApplyStepOrigin.vue';
import ApplyStepEnd from "../components/ApplyStepEnd.vue";
import ApplyStepCgiar from "../components/ApplyStepCgiar.vue";
import { Inertia } from "@inertiajs/inertia";
import country from '../../data/country.json'

export function useApplyForm(){

    const components=[
        ApplyStepDiplomas,
        ApplyStepCgiar,
        ApplyStepExperience,
        ApplyStepIdentification,
        ApplyStepOrigin,
        ApplyStepEnd
    ]
    const initReference={
        id:null,
        full_name:'',
        function:'',
        phone:'',
        email:'',
    }
    const initDoc={
            id:null,
            file:null
    }
    const initDiploma ={
            id:null,
            diploma: "",
            option: "",
    }
    const initExperience  ={
            id:null,
            company_name: "",
            position: "",
            start_date: "",
            end_date: "",
            current: '',
    }

    const fieldAddOffre= [
        [
            { type: "text", key: "position_title", label: "Titre du post" },
            { type: "select",options:[...country], key: "country_duty_station", label: "Pays" },
        ],
        [
        ],
        // [
        //     { type: "text", key: "reference", label: "ToR" },
        // ],
        [
            { type: "date", key: "published_at", label: "Publié le" },
            { type: "date", key: "expires_at", label: "Date de cloture" },
        ],
        [
            { type: "checkbox", key: "is_published", label: "Cocher pour publier" },
        ],
    ]

    const fieldAddDocument= [
        { type: "file", key: "resume", label: "" },
    ]
    
    const fieldDiploma= [
        [
            { type: "text", key: "diploma", label: "Diplôme " },
            { type: "text", key: "option", label: "Option Diplôme " },
        ],
    ]
    const fieldExperience=[
        [
        { type: "text", key: "company_name", label: "Nom de l'entreprise" },
        { type: "text", key: "position", label: "Fonction" },
        ],
        [
        { type: "date", key: "start_date", label: "Date de début" },
        { type: "date", key: "end_date", label: "Date de fin" },
        ],
        
    ]
    const cgiarFiled =[
        [
        { type: "checkbox", key: "current", label: "Cochez si vous y travaillez actuellement" },
        ],
        [
        { type: "text", key: "cgiar_center", label: "Dans lequel des centres CGIAR avez-vous déjà travaillé ?" },
        ],
        [
            { type: "text", key: "cgiar_email", label: "Indiquez votre email cgiar.org" }
        ]
    ]
    const fieldExperienceOther=[
        [
        { type: "text", key: "company_name", label: "Nom de l'entreprise" },
        { type: "text", key: "position", label: "Fonction" },
        ],
        [
        { type: "date", key: "start_date", label: "Date de début" },
        { type: "date", key: "end_date", label: "Date de fin" },
        ],
    ]
    const fieldIdentification= [
        [
        { type: "date", key: "birth_date", label: "Date de naissance" },
        { type: "text", key: "address", label: "Adresse" },
        ],
        [
        { type: "select", key: "gender", label: "Genre" ,options:[
        {label: 'homme',value: 'male'},
        {label: 'femme',value: 'female'},
    ]}
        ]
    ]
    const fieldOrigin= [
        [
            { type: "select", options:[...country], key: "nationality", label: "Nationalité | Nationality" },
            { type: "select",options:[...country], key: "country", label: "Pays de résidence | Country of residence" },
        ],
        [
            { type: "text", key: "city", label: "Ville de résidence | City of residence" },
            { type: "number", key: "experience_years", label: "Années d'expérience" },
        ],
        [
            { type: "select", key: "french_level", 
                options:[
                {label:'school',value:'Scolaire'},
                {label:'Débutant',value:'beginner'},
                {label:'intermediate',value:'Intermédiaire'},
                {label:'advanced',value:'Avancé'},
                {label:'expert',value:'Expert'},
                {label:'proficient',value:'Maîtrise'},
                {label:'native',value:'Natif'},
                ],
                label: "Niveau français" },
            { type: "select", key: "english_level", 
                options:[
                {label:'school',value:'Scolaire'},
                {label:'Débutant',value:'beginner'},
                {label:'intermediate',value:'Intermédiaire'},
                {label:'advanced',value:'Avancé'},
                {label:'expert',value:'Expert'},
                {label:'proficient',value:'Maîtrise'},
                {label:'native',value:'Natif'},
                ],
                label: "Niveau anglais" }
        ]
    ]
    const fieldDocument= [
        { type: "file", key: "resume", label: "Charger votre CV" },
        { type: "file", key: "cover_letter", label: "Charger votre lettre de motivation" },
    ]
    const fieldReference= [
        [
            { type: "text", key: "full_name", label: "Nom et prénoms" },
            { type: "text", key: "email", label: "Email" },
        ],
        [
            { type: "text", key: "phone", label: "Téléphone" },
            { type: "text", key: "function", label: "Function" },
        ],
    ]
    
    const form = reactive({
        diplomas: [{...initDiploma}],
        cgiar_information:{
            current: true,
            cgiar_center: "",
            cgiar_email: "",
        },
        experience: [{...initExperience}],

        identification: {
            birth_date: "",
            address: "",
            gender: "",
        },

        origin: {
            nationality: "",
            country: "",
            city: "",
            experience_years: '',
            french_level: "",
            english_level: "",
        },
        reference: [{ ...initReference }],
        documents:[{...initDoc},{...initDoc}],
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
        errors.push("Au moins un diplôme est requis.");
    } else {
        data.diplomas.forEach((d, i) => {
        if (!d.diploma || !d.option) {
            errors.push(`Diplôme ${i + 1}: diplôme et option obligatoires.`);
        }
        });
    }

    // Vérification cgiar_information
    // if (!data.cgiar_information || !data.cgiar_information.cgiar_center || !data.cgiar_information.cgiar_email) {
    //     errors.push("Les informations CGIAR (center et email) sont obligatoires.");
    // }

    // Vérification experience
    if (!data.experience || data.experience.length === 0) {
        errors.push("Au moins une expérience est requise.");
    } else {
        data.experience.forEach((exp, i) => {
        if (!exp.company_name || !exp.position || !exp.start_date) {
            errors.push(`Expérience ${i + 1}: nom de l’entreprise, poste et date de début obligatoires.`);
        }
        });
    }

    // Vérification identification
    if (!data.identification || !data.identification.birth_date || !data.identification.gender) {
        errors.push("Les informations d’identification (date de naissance et genre) sont obligatoires.");
    }

    // Vérification origin
    if (!data.origin || !data.origin.nationality || !data.origin.country) {
        errors.push("Nationalité et pays d’origine obligatoires.");
    }

    // Vérification reference
    if (!data.reference || data.reference.length === 0) {
        errors.push("Au moins une référence est requise.");
    } else {
        data.reference.forEach((r, i) => {
        if (!r.full_name || !r.email || !r.phone) {
            errors.push(`Référence ${i + 1}: nom, email et téléphone obligatoires.`);
        }
        });
    }

    // Vérification documents (ex: au moins 1 document requis ?)
    if (
        !data.documents ||
        data.documents.filter(doc => doc.file !== null && doc.file !== "").length < 2
        ) {
        errors.push("Veuillez télécharger au moins deux documents.");
        }

    return errors;
    }

const submitForm = async (uuid:string) => {

  try {
        const errors = validateForm(form);
        if (errors.length > 0) {
        alert("Erreurs trouvées:\n- " + errors.join("\n- "));
         return false
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
                form.experience.forEach((item, index) => {
                Object.keys(item).forEach(key => {
                    formData.append(`experience[${index}][${key}]`, item[key]);
                });
                });

                // Reference (tableau d’objets)
                form.reference.forEach((item, index) => {
                Object.keys(item).forEach(key => {
                    formData.append(`reference[${index}][${key}]`, item[key]);
                });
                });

                // CGIAR Information (objet simple)
                Object.keys(form.cgiar_information).forEach(key => {
                formData.append(`cgiar_information[${key}]`, form.cgiar_information[key]);
                });

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

    alert('Candidature envoyée avec succès ✅');

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
    console.error('Erreur lors de l\'envoi du formulaire :', error.response?.data?.errors || error);
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
    console.log('Offre envoyée ✅', response.data)
  } catch (error: any) {
     let message = error.response?.data?.message ?error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'
    console.error('Erreur lors de l\'envoi du formulaire :', error.response || error);
    alert(message);
    
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
    submitOffre
  }

}