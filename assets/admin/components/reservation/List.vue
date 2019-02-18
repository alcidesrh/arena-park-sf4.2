<template>
    <div>
        <v-card>
            <v-card-title>
                Reservaciones
                <v-progress-linear style="max-width: 200px; margin-left: 15px"
                                   v-if="loading || deleteLoading || servicesLoading"
                                   :indeterminate="true"></v-progress-linear>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>

                <v-menu
                        ref="menu"
                        :close-on-content-click="false"
                        v-model="menu"
                        :nudge-right="40"
                        persistent
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                >
                    <v-text-field
                            style="max-width: 170px"
                            slot="activator"
                            v-model="dateStart"
                            label="A partir de"
                            prepend-icon="event"
                            readonly
                    ></v-text-field>
                    <v-date-picker v-model="dateStart" @input="menu = false" no-title>
                    </v-date-picker>
                </v-menu>
                <v-menu
                        ref="menu2"
                        :close-on-content-click="false"
                        v-model="menu2"
                        :nudge-right="40"
                        persistent
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                >
                    <v-text-field
                            style="max-width: 170px"
                            slot="activator"
                            v-model="dateEnd"
                            label="Antes de"
                            prepend-icon="event"
                            readonly
                    ></v-text-field>
                    <v-date-picker v-model="dateEnd" @input="menu2 = false" no-title>
                    </v-date-picker>
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
                    <td>{{ getDate(props.item.createAt) }}</td>
                    <td>{{  props.item.user.sex?'Mt ':'Ms '}}{{ props.item.user.name }}</td>
                    <td>{{ getDate(props.item.dateCarIn, true) }}</td>
                    <td>{{ getDate(props.item.dateCarOut, true) }}</td>
                    <td style="text-align: center">{{ props.item.payment }}</td>
                    <td>
                        <v-btn icon class="mx-0"
                               @click="show(props.item)">
                            <v-icon color="orange">visibility</v-icon>
                        </v-btn>
                        <v-btn icon class="mx-0"
                               @click="downloadContract(props.item.contract)">
                            <v-icon color="teal">cloud_download</v-icon>
                        </v-btn>
                        <v-btn icon class="mx-0" @click="del(props.item)">
                            <v-icon color="red">delete</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-if="reservation"
                  v-model="dialog"
                  width="800"
        >
            <v-card>
                <v-card-title
                        class="headline"
                        primary-title
                >
                    Descargar contrato
                    <v-btn icon class="mx-0"
                           @click="downloadContract(reservation.contract)">
                        <v-icon color="teal">cloud_download</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn icon class="mx-0"
                           @click="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex class="pr-3">
                            <h4>Cliente</h4>
                            <v-divider class="mb-2"></v-divider>
                            <p class="my-1">Email: {{reservation.user.email}}</p>
                            <p class="my-1">Teléfono: {{reservation.user.phone}}</p>
                            <p class="my-1">Nombre: {{reservation.user.name}}</p>
                        </v-flex>
                        <v-flex class="pr-3">
                            <h4>Carro</h4>
                            <v-divider class="mb-2"></v-divider>
                            <p class="my-1">Marca: {{reservation.car.mark}}</p>
                            <p class="my-1">Placa: {{reservation.car.plate}}</p>
                            <p class="my-1">Color: {{reservation.car.color}}</p>
                        </v-flex>
                        <v-flex class="pr-3">
                            <h4>Reserva</h4>
                            <v-divider class="mb-2"></v-divider>
                            <p class="my-1">Creada: {{ getDate(reservation.createAt) }}</p>
                            <p class="my-1">Empieza: {{ getDate(reservation.dateCarIn, true) }}</p>
                            <p class="my-1">Termina: {{ getDate(reservation.dateCarOut, true) }}</p>
                            <p class="my-1">Aereopuerto: {{reservation.airport.name}}</p>
                            <p class="my-1">Vuelo: {{reservation.fly}}</p>
                            <p class="my-1">Equipaje: {{reservation.baggage?'Si':'No'}}</p>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap class="mt-4">
                        <v-flex class="pr-3" v-if="reservation.message">
                            <h4>Mensaje</h4>
                            <v-divider class="mb-2"></v-divider>
                            <p class="my-1">{{reservation.message}}</p>
                        </v-flex>
                        <v-flex class="pr-3 ml-3" style="max-width: 300px">
                            <h4>Pago </h4>
                            <v-divider class="mb-2"></v-divider>
                            <p class="my-1">Prise en charge: <span class="right">{{tarif.priceCharge}} CHF</span></p>
                            <p class="my-1">Annulation: <span class="right">{{tarif.annulation}} CHF</span></p>
                            <p v-for="service, index in reservation.services" class="my-1"
                               v-html="getService(service)"></p>
                            <p class="my-1 text-xs-right" v-show="reservation.descount"> -{{reservation.descount}}%</p>
                            <p class="my-1">Total({{getPaymentType(reservation.paymentType)}})<span class="right">{{reservation.payment}} CHF</span>
                            </p>
                        </v-flex>
                    </v-layout>

                </v-card-text>
                <v-card-actions>
                    <v-btn
                            color="primary"
                            flat
                            @click="dialog = false"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import moment from 'moment';
    import {API_HOST} from '../../config/_entrypoint';
    import fetch from '../../utils/fetch';

    export default {
        data: () => ({
            user: {},
            users: [],
            reservation: null,
            dialog: false,
            pagination: {},
            emailLength: 0,
            menu: false,
            dateStart: null,
            menu2: false,
            dateEnd: null,
            headers: [
                {text: 'Creada', sortable: false},
                {text: 'Cliente', sortable: false},
                {text: 'Empieza', sortable: false},
                {text: 'Termina', sortable: false},
                {text: 'Pago', sortable: false},
                {text: '', sortable: false}
            ],
        }),
        computed: mapGetters({
            deletedItem: 'reservation/del/deleted',
            error: 'reservation/list/error',
            items: 'reservation/list/items',
            loading: 'reservation/list/loading',
            view: 'reservation/list/view',
            deleteError: 'reservation/del/error',
            deleteLoading: 'reservation/del/loading',
            deleted: 'reservation/del/deleted',
            services: 'service/list/items',
            servicesLoading: 'service/list/loading',
            tarif: 'tarif/update/retrieved',
            totalItems: 'reservation/list/totalItems',
            userLoading: 'user/list/loading',
        }),
        methods: {
            setUser(user) {
                this.user = user;
                this.emailLength = this.user.email.length;
                this.resetPagination();
                this.$store.dispatch('reservation/list/getItems', this.getQuery());

            },
            downloadContract(contract) {
                window.location.href = API_HOST + '/' + contract.path;

            },
            getPaymentType(type) {
                switch (type) {
                    case 1:
                        return 'cash';
                    case 2:
                        return 'visa';
                    case 3:
                        return 'mastercard';
                    case 4:
                        return 'PostFinance card';
                    case 5:
                        return 'PostFinance e-finance';
                }
            },
            show(item) {
                this.reservation = item;
                ;this.dialog = true;
            },
            getService(data) {
                let service = this.services.filter(item => item.id == data.id);
                return service[0].name + ': <span class="right">' + service[0].prices[data.price].price + ' CHF</span>';
            },
            getDate(date, time = null) {
                return time ? moment(date).format('DD/MM/YYYY hh:mm a') : moment(date).format('DD/MM/YYYY');
            },
            del(item) {
                if (window.confirm('Seguro quiere eliminar la reservación')){
                    this.$vuetify.goTo(0, 3000);
                    this.$store.dispatch('reservation/del/delete', item).then(() => {
                        this.$store.dispatch('reservation/list/getItems');
                    });
                }
            },
            resetSearch() {
                this.user = {};
                this.dateStart = null;
                this.dateEnd = null;
                this.$store.dispatch('reservation/list/setPage', 1);
                this.pagination.page = 1
                this.$store.dispatch('reservation/list/getItems');
            },
            getQuery() {
                let query = null;
                if (typeof this.user.id != typeof undefined) {
                    query = 'user=' + this.user.id;
                }
                if (this.dateEnd) {
                    if (query)
                        query = query + '&dateCarIn[after]=' + this.dateStart + '&dateCarIn[before]=' + this.dateEnd;
                    else
                        query = 'dateCarIn[after]=' + this.dateStart + '&dateCarIn[before]=' + this.dateEnd;
                }
                return query;
            },
            resetPagination() {
                this.$store.dispatch('reservation/list/setPage', 1);
                this.pagination.page = 1;
            }
        },

        created() {
            if(typeof this.$route.params.user != typeof undefined)
                this.user = this.$route.params.user;
            if (!this.items.length)
                this.$store.dispatch('service/list/getItems');
            if (!this.tarif)
                this.$store.dispatch('tarif/update/retrieve', '/tarifs/1');
        },
        watch: {
            dateEnd(val) {
                if (val) {
                    if (this.dateStart && moment(this.dateStart) < moment(this.dateEnd)) {
                        this.resetPagination();
                        this.$store.dispatch('reservation/list/getItems', this.getQuery());
                    }
                }
            },
            dateStart(val) {
                if (val) {
                    if (this.dateEnd && moment(this.dateStart) < moment(this.dateEnd)) {
                        this.resetPagination();
                        this.$store.dispatch('reservation/list/getItems', this.getQuery());
                    }
                }
            },
            pagination: {
                handler() {
                    this.$vuetify.goTo(0, 3000);
                    if (this.pagination.rowsPerPage != -1)
                        this.$store.dispatch('reservation/list/setItemsPerPage', this.pagination.rowsPerPage);
                    else
                        this.$store.dispatch('reservation/list/setItemsPerPage', 1000000);
                    this.$store.dispatch('reservation/list/setPage', this.pagination.page);
                    this.$store.dispatch('reservation/list/getItems', this.getQuery());
                },
                deep: true
            },
            deleteError(val) {
                alert('Error: ' + val)
            }
        }
    }
</script>
