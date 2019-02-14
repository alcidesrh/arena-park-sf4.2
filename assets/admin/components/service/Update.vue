<template>
    <div>
        <v-layout>
            <h2 class="d-inline">Editar Servicio</h2>
            <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="retrieveLoading || updateLoading"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
        <ServiceForm v-if="item" :handle-submit="update" :values="item" :errors="violations"
                     :initialValues="retrieved"></ServiceForm>
    </div>
</template>

<script>
    import ServiceForm from './Form.vue';
    import {mapGetters} from 'vuex';

    export default {
        created() {
            this.$store.dispatch('service/update/retrieve', decodeURIComponent('/services/' + this.$route.params.id));
        },
        components: {
            ServiceForm
        },
        computed: {
            ...mapGetters({
                retrieveError: 'service/update/retrieveError',
                retrieveLoading: 'service/update/retrieveLoading',
                updateError: 'service/update/updateError',
                updateLoading: 'service/update/updateLoading',
                created: 'service/create/created',
                retrieved: 'service/update/retrieved',
                updated: 'service/update/updated',
                violations: 'service/update/violations'
            })
        },
        data: function () {
            return {
                item: {}
            }
        },
        methods: {
            update(values) {
                this.$store.dispatch('service/update/update', {item: this.retrieved, values: values});
            },
            reset() {
                this.$store.dispatch('service/update/reset');
                this.$store.dispatch('service/create/reset');

            }
        },
        watch: {
            updated() {
                this.$router.push({name: 'ServiceList'});
                this.$store.dispatch('service/list/getItems');
            },
            retrieveError(val) {
                alert('Error: ' + val)
            },
            updateError(val) {
                alert('Error: ' + val)
            }
        },
        beforeDestroy() {
            this.reset();
        }
    }
</script>
