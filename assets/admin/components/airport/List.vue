<template>
    <div>
        <v-layout>
            <h2 class="d-inline">Editar Aereopuertos
                <v-btn fab dark small color="primary" @click="editing = false; airport = {name: ''}; dialog = true">
                    <v-icon dark>add</v-icon>
                </v-btn>
            </h2>
            <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="updateLoading || loading || createLoading || deleteLoading"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
        <v-layout row wrap>
            <v-flex xs6 md4>
                <v-autocomplete
                        v-model="editing"
                        :items="items"
                        item-text="name"
                        item-value="id"
                        label="Editar aereopuerto"
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
            <v-flex xs6 md4 v-show="editing">
                <v-btn icon class="mx-0" @click="dialog = true">
                    <v-icon color="teal">edit</v-icon>
                </v-btn>
                <v-btn icon class="mx-0" @click="del()">
                    <v-icon color="red">delete</v-icon>
                </v-btn>
            </v-flex>
        </v-layout>
        <v-dialog
                v-model="dialog"
                width="400"
        >

            <v-card>
                <v-card-title
                        class="headline grey lighten-2"
                >
                    <label v-show="!editing">Crear aereopuerto</label>
                    <label v-show="editing">Editar aereopuerto</label>
                </v-card-title>

                <v-card-text>
                    <v-layout wrap>
                        <v-flex xs12 md7>
                            <v-text-field
                                    v-model="airport.name"
                                    label="Nombre"
                                    required
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            color="primary"
                            flat
                            @click="dialog = false"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                            color="primary"
                            flat
                            @click="saveAirport"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        data: () => ({
            editing: false,
            dialog: false,
            airport: {name: ''}
        }),
        computed: mapGetters({
            deletedItem: 'airport/del/deleted',
            error: 'airport/list/error',
            items: 'airport/list/items',
            loading: 'airport/list/loading',
            view: 'airport/list/view',
            updateLoading: 'airport/update/updateLoading',
            createLoading: 'airport/create/loading',
            deleteLoading: 'airport/del/loading',
        }),
        methods: {
            saveAirport(){
                this.dialog = false;
                if(this.editing)
                    this.$store.dispatch('airport/update/update', {item: this.airport, values: this.airport }).then(() => {
                        this.editing = this.airport.id
                    });
                else
                    this.$store.dispatch('airport/create/create', this.airport).then(() => {
                        this.$store.dispatch('airport/list/getItems')
                    });
            },
            del(){
                let airport = this.items.filter(item => item.id == this.editing)
                this.airport = airport[0];
                if (window.confirm('Vas a eliminar el aereopuerto: ' + this.airport.name))
                    this.$store.dispatch('airport/del/delete', this.airport).then(() => {
                        this.airport = {name: ''};
                        this.$store.dispatch('airport/list/getItems');
                    });
            }
        },
        watch:{
          dialog(val){
              if(!val){
                  this.editing = false;
              }
              else{
                  if(this.editing){
                      let airport = this.items.filter(item => item.id == this.editing)
                      this.airport = airport[0];
                  }
              }
          }
        },
        created() {
            this.$store.dispatch('airport/list/getItems')
        }
    }
</script>
