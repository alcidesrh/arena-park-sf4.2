<template>
  <div>
    <v-layout>
      <h2 class="d-inline">Editar Contacto</h2>
      <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="updateLoading || retrieveLoading"
                         :indeterminate="true"></v-progress-linear>
    </v-layout>
    <ContactForm v-if="item" :handle-submit="update" :values="item" :errors="violations"
               :initialValues="retrieved"></ContactForm>

  </div>
</template>

<script>
    import ContactForm from './Form.vue';
    import {mapGetters} from 'vuex';

    export default {
        created() {
            this.$store.dispatch('contact/update/retrieve', '/contacts/1');
        },
        components: {
            ContactForm
        },
        computed: {
            ...mapGetters({
                retrieveError: 'contact/update/retrieveError',
                retrieveLoading: 'contact/update/retrieveLoading',
                updateError: 'contact/update/updateError',
                updateLoading: 'contact/update/updateLoading',
                retrieved: 'contact/update/retrieved',
                updated: 'contact/update/updated',
                violations: 'contact/update/violations'
            })
        },
        data: function () {
            return {
                item: {}
            }
        },
        methods: {
            update(values) {
                this.$store.dispatch('contact/update/update', {item: this.retrieved, values: values});
            },
            reset() {
                this.$store.dispatch('contact/update/reset');

            }
        },
        beforeDestroy() {
            this.reset();
        }
    }
</script>
