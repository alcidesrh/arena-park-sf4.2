<template>
    <div>
        <v-layout>
            <h2 class="d-inline">Editar Aereopuertos</h2>
            <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="updateLoading || createLoading || deleteLoading"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
        <v-layout row wrap>
            <v-flex xs12>
                <v-autocomplete
                        v-model="editing"
                        :items="items"
                        item-text="name"
                        item-value="id"
                        label="Aéroport de provenance"
                        persistent-hint
                        prepend-icon="local_airport"
                >
                    <v-slide-x-reverse-transition
                            slot="append-outer"
                            mode="out-in"
                    >
                    </v-slide-x-reverse-transition>
                </v-autocomplete>
            </v-flex>
        </v-layout>

    </div>
</template>

<script>
    import AirportForm from './Form.vue';
    import {mapGetters} from 'vuex';

    export default {
        data: () => ({
            editing: false
        }),
        created() {
            this.$store.dispatch('airport/update/retrieve', decodeURIComponent(this.$route.params.id));
        },
        components: {
            AirportForm
        },
        computed: {
            ...mapGetters({
                retrieveError: 'airport/update/retrieveError',
                retrieveLoading: 'airport/update/retrieveLoading',
                updateError: 'airport/update/updateError',
                updateLoading: 'airport/update/updateLoading',
                createLoading: 'airport/create/loading',
                deleteError: 'airport/del/error',
                deleteLoading: 'airport/del/loading',
                created: 'airport/create/created',
                deleted: 'airport/del/deleted',
                retrieved: 'airport/update/retrieved',
                updated: 'airport/update/updated',
                violations: 'airport/update/violations'
            })
        },
        data: function () {
            return {
                item: {}
            }
        },
        methods: {
            update(values) {
                this.$store.dispatch('airport/update/update', {item: this.retrieved, values: values});
            },
            del() {
                if (window.confirm('Are you sure you want to delete this item?'))
                    this.$store.dispatch('airport/del/delete', this.retrieved);
            },
            reset() {
                this.$store.dispatch('airport/update/reset');
                this.$store.dispatch('airport/del/reset');
                this.$store.dispatch('airport/create/reset');

            }
        },
        watch: {
            deleted: function (deleted) {
                if (deleted) {
                    this.$router.push({name: 'AirportList'});
                }
            }
        },
        beforeDestroy() {
            this.reset();
        }
    }
</script>
