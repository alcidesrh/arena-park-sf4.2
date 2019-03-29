<template>
    <v-app light>
            <v-form id="form" v-model="valid" ref="form" :bind-style="{opacity: loading?0.7:1}">
                <v-layout row wrap>
                    <v-flex xs12 md10 offset-md1>
                        <v-text-field
                                v-model="contact.email"
                                label="Votre email"
                                prepend-icon="email"
                                :rules="emailRules"
                                required
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex xs12 md10 offset-md1>
                        <v-text-field
                                v-model="contact.subject"
                                label="Sujet"
                                prepend-icon="subject"
                                :rules="requireRules"
                                required
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex xs12 md10 offset-md1>
                        <v-textarea
                                v-model="contact.message"
                                label="Message"
                                prepend-icon="message"
                                auto-grow
                                rows="3"
                        ></v-textarea>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex xs12 md10 offset-md1>
                        <v-btn flat color="primary"
                               @click="sendContact">
                            Envoyer <v-icon class="ml-2">send</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-form>

        <v-layout justify-center aling-center class="loading-div" v-if="loading">
            <v-progress-linear style="max-width: 300px"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
        <v-alert class="white-text reservation-success"
                 v-if="success"
                 :value="true"
                 type="success"
        >
            Le message a été envoyé
        </v-alert>
    </v-app>
</template>
<script>
    import axios from 'axios';
    import moment from 'moment';
    import {API_HOST, API_PATH} from "../admin/config/_entrypoint";

    export default {
        data: () => ({
            contact: {},

            currentDate: moment().format('YYYY-MM-DD'),

            valid: null,
            validationError: false,
            loading: false,
            success: false,
            errorMessage: false,
            requireRules: [
                v => !!v || 'Ce champ est obligatoire'
            ],
            emailRules: [
                v => !!v || 'Ce champ est obligatoire',
                v => /.+@.+/.test(v) || "L'email doit être valide"
            ]
        }),

        methods: {
            sendContact() {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    let $this = this;
                    axios.post('/send-contact', JSON.stringify(this.contact)).then(function (response) {
                        $this.loading = false;
                        $this.success = true;
                        $this.$refs.form.reset()
                    }).catch(function (error) {
                        console.log(error);
                        $this.loading = false;
                    });
                }
            },
            getEndPoint(resource) {
                return API_HOST + API_PATH + resource;
            },
        }
    }
</script>