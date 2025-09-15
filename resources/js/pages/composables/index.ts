
import { reactive, ref } from "vue"
import ApplyStepDiplomas from '../components/ApplyStepDiplomas.vue';
import ApplyStepExperience from '../components/ApplyStepExperience.vue';
import ApplyStepIdentification from '../components/ApplyStepIdentification.vue';
import ApplyStepOrigin from '../components/ApplyStepOrigin.vue';
import ApplyStepEnd from "../components/ApplyStepEnd.vue";
import ApplyStepExperienceTwo from "../components/ApplyStepExperienceTwo.vue";
import ApplyStepExperienceTree from "../components/ApplyStepExperienceTree.vue";


export function useApplyForm(){

    const components=[
        ApplyStepDiplomas,
        ApplyStepExperience,
        ApplyStepExperienceTwo,
        ApplyStepExperienceTree,
        ApplyStepIdentification,
        ApplyStepOrigin,
        ApplyStepEnd
    ]
     const initReference={
        full_name:'',
        function:'',
        phone:'',
        email:'',
    }
    const fieldDiploma= [
        [
        { type: "text", key: "recent_diploma", label: "Diplôme récent | Recent diploma" },
        { type: "text", key: "recent_option", label: "Option (Diplôme récent | Recent diploma)" },
        ],
        [
        { type: "text", key: "second_last_diploma", label: "2e dernier diplôme | 2nd last diploma" },
        { type: "text", key: "second_last_option", label: "Option (2e dernier diplôme | 2nd last diploma)" },
        ],
        [
        { type: "text", key: "third_last_diploma", label: "3e dernier diplôme | 3rd last diploma" },
        { type: "text", key: "third_last_option", label: "Option (3e dernier diplôme | 3rd last diploma)" }
        ]
    ]
    const fieldExperience=[
        [
        { type: "text", key: "company_name_1", label: "Nom de l'entreprise" },
        { type: "text", key: "position_1", label: "Fonction" },
        ],
        [
        { type: "date", key: "start_date_1", label: "Date de début" },
        { type: "date", key: "end_date_1", label: "Date de fin" },
        ],
        [
        { type: "checkbox", key: "current_1", label: "Si vous y travaillez actuellement | If current, check" },
        ],
        [
        { type: "text", key: "cgiar_center_1", label: "Dans lequel des centres CGIAR avez-vous déjà travaillé ?" },
        ],
        [
         { type: "text", key: "cgiar_email_1", label: "Indiquez votre email cgiar.org" }
        ]
    ]
    const fieldExperienceOther=[
        [
        { type: "text", key: "company_name_1", label: "Nom de l'entreprise" },
        { type: "text", key: "position_1", label: "Fonction" },
        ],
        [
        { type: "date", key: "start_date_1", label: "Date de début" },
        { type: "date", key: "end_date_1", label: "Date de fin" },
        ],
    ]
    const fieldIdentification= [
        [
        { type: "date", key: "birth_date", label: "Date de naissance" },
        { type: "text", key: "address", label: "Adresse" },
        ],
        [
            { type: "select", key: "gender", label: "Genre" }
        ]
    ]
    const fieldOrigin= [
        [
            { type: "text", key: "nationality", label: "Nationalité | Nationality" },
            { type: "text", key: "country", label: "Pays de résidence | Country of residence" },
        ],
        [
            { type: "text", key: "city", label: "Ville de résidence | City of residence" },
            { type: "number", key: "experience_years", label: "Années d'expérience" },
        ],
        [
            { type: "select", key: "french_level", label: "Niveau français" },
            { type: "select", key: "english_level", label: "Niveau anglais" }
        ]
    ]
    const fieldDocument= [
        { type: "text", key: "resume", label: "Nationalité | Nationality" },
        { type: "text", key: "cover_letter", label: "Pays de résidence | Country of residence" },
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
        diplomas: {
            recent_diploma: "",
            recent_option: "",
            second_last_diploma: "",
            second_last_option: "",
            third_last_diploma: "",
            third_last_option: "",
        },
        experience: [
        {
            company_name_1: "",
            position_1: "",
            start_date_1: "",
            end_date_1: "",
            current_1: false,
            cgiar_center_1: "",
            cgiar_email_1: "",
        },
        {
            company_name_1: "",
            position_1: "",
            start_date_1: "",
            end_date_1: "",
        },
        {
            company_name_1: "",
            position_1: "",
            start_date_1: "",
            end_date_1: "",
        }
        ],
        
        identification: {
            birth_date: "",
            address: "",
            gender: "",
        },
        origin: {
            nationality: "",
            country: "",
            city: "",
            experience_years: "",
            french_level: "",
            english_level: "",
        },
        documents: {
            reference:[{...initReference}],
            resume: null,
            cover_letter: null,
        },
    })

    let currentStep =ref(6)

const nextStep = () => {
    if (currentStep.value < components.length - 1) {
      currentStep.value++
    }
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

  const submitForm = () => {
    console.log("Form envoyé ✅", form)
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
    fieldReference
  }

}