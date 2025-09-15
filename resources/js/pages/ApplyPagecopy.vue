<script setup>
import { reactive, ref } from 'vue'

const optionReference ={
        key: "diplomas",
        title: "Vos 3 derniers diplômes",
        field: [
            [
            { type: "text", key: "full_name", label: "Nom et Prenoms | Name" },
            { type: "text", key: "function", label: "Fonction" },
            ],
            [
            { type: "text", key: "phone", label: "Téléphone" },
            { type: "text", key: "email", label: "email" },
            ],
            [
            { type: "text", key: "company", label: "Entreprise" }
            ]
        ],
    }
const fields = [
  {
    key: "diplomas",
    title: "Vos 3 derniers diplômes",
    field: [
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
    ],
  },
  {
    key: "experience",
    title: "Votre plus récente expérience",
    field: [
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
    ],
  },
  {
    key: "identification",
    title: "Identification",
    field: [
        [
        { type: "date", key: "birth_date", label: "Date de naissance" },
        { type: "text", key: "address", label: "Adresse" },
        ],
        [
            { type: "select", key: "gender", label: "Genre" }
        ]
    ],
  },
  {
    key: "origin",
    title: "Origine, expériences et langues",
    field: [
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
     
    ],
  },
{
    key: "",
    title: "CV and Cover letter",
    option:true,
    optionKey:'reference',
    dataOption:[{...optionReference}],
    field: [
        [
            { type: "file", key: "resume", label: "CV | Resume" },
            { type: "file", key: "cover_letter", label: "LM | Cover letter" },
        ],
    ],
  },
]

const form = reactive({});
const file = ref(null)
const currentStep = ref(0)

// fields.forEach(section => {
//   form[section.key] = {};
//   section.field.forEach((f,index) => {
//     // pour checkbox -> valeur bool par défaut\
//     f[index].forEach(v => {
//         form[section.key][v.key] = v.type === "checkbox" ? false : "";
        
//     });
//   });
// });

fields.forEach(section => {
  form[section.key] = {};
  section.field.forEach(group => {      // group = tableau de champs
    group.forEach(field => {            // field = chaque input
      form[section.key][field.key] = field.type === "checkbox" ? false : "";
    });
  });
});

const handleFile = (event, sectionKey, fieldKey) => {
  const selected = event.target.files[0];
  if (selected) {
    // stocke le fichier dans le form directement
    form[sectionKey][fieldKey] = selected;
  }
}

const nextStep = () => {
console.log(form);

  if (currentStep.value < fields.length - 1) {
    currentStep.value++
  }
}
const prevStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--
  }
}
const submitForm = () => {
  console.log("Form envoyé ✅", form)
}


</script>

<template>
  <div class="max-w-xl mx-auto p-6  rounded-lg ">
    <!-- Stepper navigation -->
     <h1 class="text-2xl font-bold mb-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</h1>
    <div class="flex items-center w-full mb-6">
        <template v-for="(section, index) in fields" :key="section.title">
                <div 
                class="w-10 h-10 flex items-center justify-center rounded-full font-semibold"
                :class="index <= currentStep ? 'bg-secondary text-white' : 'bg-gray-300 text-gray-700'"
                >
                {{ index + 1 }}
                </div>

                <div 
                v-if="index < fields.length - 1" 
                class="flex-1 h-1"
                :class="index < currentStep ? 'bg-secondary' : 'bg-gray-300'"
                ></div>
        </template>
    </div>



    <!-- Contenu du step actuel -->
    <div class="bg-white p-4 rounded-lg ">
      <h2 class="text-xl font-bold mb-4">{{ fields[currentStep].title }}</h2>

        <div v-for="field in fields[currentStep].field" :key="field.key" class="mb-3">
        <div class="w-full p-2">
             
        </div>
            <div class=" gap-2 w-full" >
                <div :class="value.type=='checkbox' ? 'flex-row items-center':'flex-col items-start justify-end' " 
                    class="flex gap-1 w-full cursor-pointer mb-2" v-for="(value,index) in field">
                        <label :for="value.key" class="block text-[13px]  cursor-pointer  text-primary font-medium mb-1">
                            {{ value.label }} {{ value.type }}
                        </label>
                        <!-- Champs dynamiques -->

                            <select v-if="value.type === 'select'" class="w-full border p-2 rounded" v-model="form[fields[currentStep].key[value.key]]">
                            <option value="">Sélectionnez</option>
                            <option value="option1">Option 1</option>
                            </select>

                            <input v-else-if="value.type === 'checkbox'" type="checkbox"   v-model="form[fields[currentStep].key][value.key]" />

                            <div v-else-if="value.type === 'file'" class="w-full">
                            <label class="cursor-pointer">
                                    <input type="file" class="hidden"   accept=".pdf,.doc,.docx,.txt"
                                    @change="(e) => handleFile(e, fields[currentStep].key, value.key)" 
                                    />
                                    <div class="border border-dashed p-5 flex flex-col w-full text-center cursor-pointer">
                                        <span  class="text-gray-500 ">Cliquez  fichier ici</span>
                                        <span 
                                        v-if="form[fields[currentStep].key][value.key]"  
                                        class="flex items-center justify-center space-x-2 bg-green-100 text-green-800 px-3 py-2 rounded border border-green-300"
                                        >
                                        <!-- Icône fichier -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M7 7h10M7 11h10M7 15h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>

                                        <!-- Nom du fichier -->
                                        <span class="font-medium truncate max-w-xs truncate">
                                            {{ form[fields[currentStep].key][value.key].name }}
                                        </span>
                                        </span>
                                    </div>
                            </label>
                            </div>
                            <template v-else>
                                <input :id="value.key" v-if="value.type !== 'select' && field.type !== 'checkbox'" 
                                :type="value.type" 
                                :class="value.type=='checkbox' ? '':'w-full' "
                                class=" border border-gray-300 p-2 rounded placeholder:text-[11px] placeholder:font-medium" 
                                v-model="form[fields[currentStep].key][value.key]" 
                                :placeholder="value.label"
                                />
                            </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons navigation -->
    <div class="flex justify-between mt-6">
      <button 
        @click="prevStep" 
        class="px-4 py-2 bg-gray-400 text-white rounded" 
        :disabled="currentStep === 0"
      >
        Précédent
      </button>

      <button 
        v-if="currentStep < fields.length - 1" 
        @click="nextStep" 
        class="px-4 py-2 bg-blue-600 text-white rounded"
      >
        Suivant
      </button>

      <button 
        v-else 
        @click="submitForm" 
        class="px-4 py-2 bg-green-600 text-white rounded"
      >
        Envoyer
      </button>
    </div>
  </div>
</template>
