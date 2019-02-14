<template>
    <div>
        <v-card>
            <v-card-title>
                Páginas
                <router-link :to="{name: 'PageCreate'}">
                    <v-btn fab dark small color="primary">
                        <v-icon dark>add</v-icon>
                    </v-btn>
                </router-link>
                <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="loading || deleteLoading"
                                   :indeterminate="true"></v-progress-linear>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-data-table
                    :items="items"
                    hide-actions
            >
                <template slot="items" slot-scope="props">
                    <td style="max-width: 10px; padding-right: 0px">{{ props.item.name }}</td>
                    <td>
                        <v-btn icon class="mx-0" @click="$router.push({name: 'PageUpdate', params: {id: props.item['id']}})">
                            <v-icon color="teal">edit</v-icon>
                        </v-btn>
                        <v-btn icon class="mx-0" @click="del(props.item)">
                            <v-icon color="red">delete</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        data: () => ({
            headers: [
                {
                    text: '', sortable: false
                },
                { text: '', sortable: false }
            ],
        }),
        computed: mapGetters({
            deletedItem: 'page/del/deleted',
            error: 'page/list/error',
            items: 'page/list/items',
            loading: 'page/list/loading',
            view: 'page/list/view',
            deleteError: 'page/del/error',
            deleteLoading: 'page/del/loading',
            deleted: 'page/del/deleted',
        }),
        methods: {
            del (item) {
                if (window.confirm('Seguro quiere eliminar la página'))
                    this.$store.dispatch('page/del/delete', item);
            },
        },

        created() {
            if (!this.items.length)
                this.$store.dispatch('page/list/getItems');

        },
        watch:{
            deleteError(val){
                alert('Error: '+val)
            },
            deleteError(val){
                alert('Error: '+val)
            },
            deleted: function (deleted) {
                if (deleted) {
                    this.$store.dispatch('page/list/getItems');
                }
            }
        }
    }
</script>
