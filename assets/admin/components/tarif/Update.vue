<template>
    <div>
        <v-layout>
            <h2 class="d-inline">Editar Tarifa</h2>
            <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="updateLoading || retrieveLoading"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
        <TarifForm v-if="item" :handle-submit="update" :values="item" :errors="violations"
                   :initialValues="retrieved"></TarifForm>

    </div>
</template>

<script>
    import TarifForm from './Form.vue';
    import {mapGetters} from 'vuex';

    export default {
        created() {
            this.$store.dispatch('tarif/update/retrieve', '/tarifs/1');
        },
        components: {
            TarifForm
        },
        computed: {
            ...mapGetters({
                retrieveError: 'tarif/update/retrieveError',
                retrieveLoading: 'tarif/update/retrieveLoading',
                updateError: 'tarif/update/updateError',
                updateLoading: 'tarif/update/updateLoading',
                retrieved: 'tarif/update/retrieved',
                updated: 'tarif/update/updated',
                violations: 'tarif/update/violations'
            })
        },
        data: function () {
            return {
                item: {}
            }
        },
        methods: {
            update(values) {
                values.day = parseInt(values.day);
                values.annulation = parseInt(values.annulation);
                values.descount = parseInt(values.descount);
                values.priceCharge = parseInt(values.priceCharge);
                values.tva = parseFloat(values.tva);
                this.$store.dispatch('tarif/update/update', {item: this.retrieved, values: values});
            },
            reset() {
                this.$store.dispatch('tarif/update/reset');

            }
        },
        beforeDestroy() {
            this.reset();
        }
    }
</script>
