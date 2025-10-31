import { Inertia } from "@inertiajs/inertia";

export function useRepository(){
    

    const addTrackingOffer = async (formData:any) => {
        try {
                // const errors = validateForm(form);
                // if (errors.length > 0) {
                // alert(errors.join("\n- "));
                // return false
                // } 
                if (!confirm("Would you like to confirm your application?")) {
                    return false; // Annule l'envoi si l'utilisateur clique sur "Annuler"
                }
                
            // Envoi de la requête
            const response = await window.axios.post('/manager/offre/tracking', formData, {
            });
    
            const message = 'Tracker has been successfully add. ✅'
                Inertia.reload()
               
    
        } catch (error) {
            let message = error.response?.data?.message ? error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'
            if (error.response && error.response.data && error.response.data.errors) {
            // Récupère tous les messages d'erreur et les transforme en texte
            const messages = Object.values(error.response.data.errors)
                .flat() // aplatit les tableaux
                .join('\n');
            } else {
            alert(message);
            }
            // console.error('Erreur lors de l\'envoi du formulaire :', error.response?.data?.errors || error);
            // alert(message);
        }
        };

        return{
            addTrackingOffer
        }
}