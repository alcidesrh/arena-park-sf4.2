<template>
    <div>
        <v-layout>
            <h2 class="d-inline">Nueva Página</h2>
            <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="loading"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
        <PageForm :handle-submit="create" :values="item" :errors="violations"></PageForm>
    </div>
</template>

<script>
    import PageForm from './Form.vue';
    import {createNamespacedHelpers} from 'vuex';

    const {mapGetters} = createNamespacedHelpers('page/create');

    export default {
        components: {
            PageForm
        },
        data: function () {
            return {
                item: {sections: []}
            }
        },
        computed: mapGetters([
            'error',
            'loading',
            'created',
            'violations'
        ]),
        methods: {
            create() {
                this.$store.dispatch('page/create/create', this.item);
            }
        },
        watch: {
            created: function (created) {
                this.$router.push({name: 'PageList'});
                this.$store.dispatch('page/list/getItems');
            },
            error(val) {
                alert('Error: ' + val)
            }
        }
    }
</script>
