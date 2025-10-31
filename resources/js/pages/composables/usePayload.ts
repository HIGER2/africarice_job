import { reactive } from 'vue';

export function usePayloadStore(){

    const newOffreTrakinck=reactive({
                uuid:null,
                status:'',
                comment:"",
                date:"",
            })
    const newOffre=reactive({
        offre:{
            uuid:null,
            reference:null,
            position_title:"",
            country_duty_station:"france",
            published_at:"",
            type:"public",
            expires_at:"",
            assign_by:"",
            reason:"new",
            manager:'',
            center:'',
            status:'',
            reason_replacement:"",
        },
        document:[]
    })
    return{
        newOffreTrakinck,
        newOffre
    }
}