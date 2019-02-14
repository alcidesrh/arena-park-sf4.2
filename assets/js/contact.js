
import "materialize-css/js/buttons";
import "materialize-css/js/forms";
import "materialize-css/js/toasts";

import Vue from 'vue';

import VeeValidate, { Validator } from 'vee-validate';
import fr from 'vee-validate/dist/locale/fr';

Vue.use(VeeValidate,{
    classes: true,
    events: ''
});
Validator.localize('fr', fr);

var contact = new Vue({

    delimiters: ['${', '}'],
    el: '#contact',
    data: {
        contact: {email: null, subject: null, message: null},
    },
    methods: {
        send(){
            this.$validator.validate().then(result => {
                if (result) {
                    fetch('/send-contact', {
                            method: 'POST',
                            headers: new Headers({'Content-Type': 'application/ld+json'}),
                            body: JSON.stringify(this.contact),
                        }
                    )
                        .then(response => response.json())
                        .then(data => {
                        })
                        .catch(e => {
                            M.toast({html: e.message});
                        });
                }
            });
            // if(!this.contact.email){
            //     M.toast({html: 'Votre email obligatoire'});return;
            // }
            //
            // if(!/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(this.contact.email)){
            //     M.toast({html: 'Votre email no valid'}); return;
            // }
            // if(!this.contact.message){
            //     M.toast({html: 'Votre message obligatoire'});return;
            // }
        }
    }
})


