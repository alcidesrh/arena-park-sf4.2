<template>
    <div>
        <v-layout>
            <h2 class="d-inline">Crear Servicio</h2>
            <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="loading"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
        <ServiceForm :handle-submit="create" :values="item" :errors="violations"></ServiceForm>
    </div>
</template>

<script>
    import ServiceForm from './Form.vue';
    import {createNamespacedHelpers} from 'vuex';

    const {mapActions, mapGetters} = createNamespacedHelpers('service/create');

    export default {
        components: {
            ServiceForm
        },
        data: function () {
            return {
                item: {}
            }
        },
        computed: mapGetters([
            'error',
            'loading',
            'created',
            'violations'
        ]),
        methods: {
            create: function (item) {console.log(item);
                item.priority = parseInt(item.priority);
                this.$store.dispatch('service/create/create', item);
            }
        },
        watch: {
            created: function (created) {
                this.$router.push({name: 'ServiceList'});
            }
        }
    }
</script>
