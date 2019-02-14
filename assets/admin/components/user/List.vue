<template>
    <div>
        <v-card>
            <v-card-title>
                Usuarios
                <v-progress-linear style="max-width: 200px; margin-left: 15px"
                                   v-if="loading || deleteLoading || updateLoading || updateCarLoading || deleteLoading"
                                   :indeterminate="true"></v-progress-linear>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-menu v-model="menuSearchUser" bottom offset-y :open-on-click="false"
                        style="max-width: 350px">
                    <v-text-field
                            slot="activator"
                            label="Buscar por email"
                            prepend-icon="person"
                            v-model="user.email"
                            v-on:keyup="searchUser"
                    ></v-text-field>
                    <v-list>
                        <v-list-tile v-for="(item, index) in users" :key="index"
                                     @click="setUser(item)">
                            <v-list-tile-title>{{item.email}}</v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-menu>
                <v-icon
                        class="ml-0 mr-2"
                        @click="resetSearch"
                >refresh
                </v-icon>
            </v-card-title>
            <v-data-table
                    :headers="headers"
                    :items="items"
                    :pagination.sync="pagination"
                    :total-items="totalItems"
                    :rows-per-page-items="[20,50,100,{'text':'$vuetify.dataIterator.rowsPerPageAll','value':-1}]"
            >
                <template slot="items" slot-scope="props">
                    <td>{{  props.item.sex?' Sr ':' Sra ' }}{{ props.item.name }}</td>
                    <td>{{ props.item.email }}</td>
                    <td>{{ props.item.phone }}</td>
                    <td v-if="props.item.car">
                        <p class="mt-2 mb-0">Marca: {{ props.item.car.mark}}</p>
                        <p class="mb-0">Placa: {{ props.item.car.plate }}</p>
                        <p class="mb-0">Color: {{ props.item.car.color }}</p>
                    </td>
                    <td v-else>
                        <p class="mt-2 mb-0">------</p>
                    </td>
                    <td class="px-0 text-xs-center">{{ props.item.reservations.length }}</td>
                    <td class="px-0">
                        <v-btn small icon class="mx-0 px-0"
                               @click="userReservations(props.item)">
                            <v-icon color="orange">visibility</v-icon>
                        </v-btn>
                        <v-btn small icon class="mx-0 px-0"
                               @click="edit(props.item)">
                            <v-icon color="teal">edit</v-icon>
                        </v-btn>
                        <v-btn small icon class="mx-0 px-0" @click="del(props.item)">
                            <v-icon color="red">delete</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog
                v-if="item"
                v-model="dialog"
                width="800"
        >
            <v-card>
                <v-card-title
                        class="headline"
                        primary-title
                >
                    Editar usuario
                    <v-spacer></v-spacer>
                    <v-btn icon class="mx-0"
                           @click="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-form
                            ref="form"
                            v-model="valid"
                            lazy-validation
                    >
                        <v-layout row wrap>
                            <v-flex class="pr-3">
                                <h4>Datos personales</h4>
                                <v-divider class="mb-2"></v-divider>
                                <v-text-field
                                        v-model="item.name"
                                        label="Nombre"
                                        :rules="requireRules"
                                        required
                                ></v-text-field>
                                <v-text-field
                                        v-model="item.email"
                                        label="Email"
                                        :rules="emailRules"
                                        required
                                ></v-text-field>
                                <v-text-field
                                        v-model="item.phone"
                                        label="Movil"
                                        :rules="requireRules"
                                        required
                                ></v-text-field>
                            </v-flex>
                            <v-flex class="pr-3" v-if="item.car">
                                <h4>Carro</h4>
                                <v-divider class="mb-2"></v-divider>
                                <v-text-field
                                        v-model="item.car.mark"
                                        label="Marca"
                                        :rules="requireRules"
                                        required
                                ></v-text-field>
                                <v-text-field
                                        v-model="item.car.plate"
                                        label="Placa"
                                        :rules="requireRules"
                                        required
                                ></v-text-field>
                                <v-text-field
                                        v-model="item.car.color"
                                        label="Color"
                                        :rules="requireRules"
                                        required
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            color="primary"
                            flat
                            @click="dialog = false"
                    >
                        Cerrar
                    </v-btn>
                    <v-btn
                            color="primary"
                            flat
                            @click="save"
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
    import fetch from '../../utils/fetch';

    export default {
        data: () => ({
            user: {},
            users: [],
            menuSearchUser: false,
            searchUserLoading: false,
            emailLength: 0,
            valid: false,
            item: false,
            dialog: false,
            pagination: {},
            headers: [
                {text: 'Nombre', sortable: false, align: 'left'},
                {text: 'Email', sortable: false, align: 'left'},
                {text: 'Movil', sortable: false, align: 'left'},
                {text: 'Carro', sortable: false, align: 'left'},
                {text: '# Res', sortable: false, align: 'left'},
                {text: '', sortable: false}
            ],
            requireRules: [
                v => !!v || 'Este campo es obligatorio'
            ],
            emailRules: [
                v => !!v || 'E-mail es obligatorio',
                v => /.+@.+/.test(v) || 'E-mail no es válido'
            ],
        }),
        computed: mapGetters({
            deletedItem: 'user/del/deleted',
            error: 'user/list/error',
            items: 'user/list/items',
            loading: 'user/list/loading',
            view: 'user/list/view',
            totalItems: 'user/list/totalItems',
            updateLoading: 'user/update/updateLoading',
            updateCarLoading: 'car/update/updateLoading',
            deleteError: 'user/del/error',
            deleteLoading: 'user/del/loading'
        }),
        methods: {
            resetSearch() {
                this.user = {};
                this.$store.dispatch('user/list/setPage', 1);
                this.pagination.page = 1
                this.$store.dispatch('user/list/getItems');
            },
            userReservations(item){
                this.$router.push({name: 'ReservationList', params: {user: item}})
                document.getElementById('reservations').click();;
            },
            setUser(user) {
                this.user = user;
                this.emailLength = this.user.email.length;
                this.resetPagination();
                this.$store.dispatch('user/list/getItems', 'email=' + this.user.email);
            },
            searchUser() {
                if (!this.searchUserLoading && this.user.email.length >= 4 && this.emailLength < this.user.email.length) {
                    this.searchUserLoading = true;
                    fetch('/users?email=' + this.user.email)
                        .then(response => response.json())
                        .then(data => {
                            if (data['hydra:member'].length && typeof this.user.id == typeof undefined) {
                                this.users = data['hydra:member'];
                                this.menuSearchUser = true;
                                this.searchUserLoading = false;
                            }
                        })
                        .catch(e => {
                            this.searchUserLoading = false;
                            console.log(e);
                        });
                }
                else if (this.emailLength > this.user.email.length && typeof this.user.id != typeof undefined) {
                    this.user = {email: this.user.email};
                    this.car = {};
                }
                this.menuSearchUser = false;
                this.emailLength = this.user.email.length;

            },
            resetPagination() {
                this.$store.dispatch('user/list/setPage', 1);
                this.pagination.page = 1;
            },
            edit(item) {
                this.item = item;
                this.dialog = true;
            },
            save() {
                if (this.$refs.form.validate()) {
                    this.dialog = false;
                    this.$store.dispatch('user/update/update', {
                        item: this.item,
                        values: {name: this.item.name, email: this.item.email, phone: this.item.phone}
                    });
                    if(this.item.car)
                    this.$store.dispatch('car/update/update', {
                        item: this.item.car,
                        values: {mark: this.item.car.mark, plate: this.item.car.plate, color: this.item.car.color}
                    });

                }
            },
            del (item) {
                if (window.confirm('Seguro quiere eliminar este usuario')){
                    this.$vuetify.goTo(0, 3000);
                    this.$store.dispatch('user/del/delete', item).then(() => {
                        this.$store.dispatch('user/list/getItems');
                        this.user = {};
                    })
                }
            },
        },
        created() {
            this.$store.dispatch('user/list/getItems');
        },

        watch: {
            pagination: {
                handler() {
                    this.$vuetify.goTo(0, 3000);
                    if (this.pagination.rowsPerPage != -1)
                        this.$store.dispatch('user/list/setItemsPerPage', this.pagination.rowsPerPage);
                    else
                        this.$store.dispatch('user/list/setItemsPerPage', 1000000);
                    this.$store.dispatch('user/list/setPage', this.pagination.page);
                    this.$store.dispatch('user/list/getItems');
                },
                deep: true
            },
            deleteError(val) {
                alert('Error: ' + val)
            }
        }
    }
</script>
