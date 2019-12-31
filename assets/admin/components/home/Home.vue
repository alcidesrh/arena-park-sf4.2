<style>
    .loading-div {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 50%;
        left: 0px;
        z-index: 9999;
    }
</style>
<template>
    <div>
        <v-card>
            <v-card-title>
                <v-layout justify-center aling-center class="loading-div" v-if="loading"
                          style="width: 100%;height: 100%;position: fixed;top: 50%;left:0px;z-index: 9999;">
                    <v-progress-linear style="max-width: 300px"
                                       :indeterminate="true"></v-progress-linear>
                </v-layout>
                <v-container pa-0>
                    <v-layout justify-end row>
                        <v-chip outline>
                            <v-icon left>time_to_leave</v-icon>
                            Reservaciones
                            <v-chip color="green" text-color="white">{{numbers.reservations}}</v-chip>
                            <v-chip color="red" text-color="white">{{numbers.reservationPendent}}</v-chip>
                        </v-chip>
                        <v-chip outline>
                            <v-icon left>attach_money</v-icon>
                            Pago
                            <v-chip color="green" text-color="white">Cash {{numbers.cash}}</v-chip>
                            <v-chip color="green" text-color="white">Visa {{numbers.visa}}</v-chip>
                            <v-chip color="green" text-color="white">MasterCard {{numbers.mastercard}}</v-chip>
                            <v-chip color="green" text-color="white">PostFinance card {{numbers.postFinanceCard}}</v-chip>
                            <v-chip color="green" text-color="white">PostFinance e-finance {{numbers.postFinanceEfinance}}</v-chip>
                        </v-chip>
                    </v-layout>
                    <v-layout row mt-5 wrap>
                        <v-flex xs6 md3 class="pr-2">
                            <v-date-picker
                                    width="230"
                                    no-title
                                    v-model="month"
                                    type="month"
                                    color="teal"
                                    :picker-date.sync="month"
                            ></v-date-picker>
                        </v-flex>
                        <v-flex xs6 md3 style="background: teal; color: white; padding: 5px 0px 0px 10px;">
                            <h4>Datos del mes ({{ month || 'change month...' }})</h4>
                            <v-divider></v-divider>
                            <div class="mt-3">
                                <div>Reservaciones: {{monthData.cant || 0}}</div>
                                <div>Facturación: {{monthData.total || 0}} CHF</div>
                                <div class="mt-2" v-show="monthData.users">
                                    <label class="subheading">Usuarios:</label>
                                    <div style="overflow-y: auto; max-height: 170px">
                                        <div v-for="(item) in monthData.users">{{item.user}} {{item.date}}</div>
                                    </div>
                                </div>
                            </div>
                        </v-flex>
                    </v-layout>
                    <v-layout row mt-5 wrap>
                        <v-flex xs6>
                            <strong>Entrada del carro:</strong>
                        </v-flex>
                        <v-flex xs6>
                            <strong>Salida del carro:</strong>
                        </v-flex>
                    </v-layout>
                    <v-layout row mt-2 wrap>
                        <v-flex xs6 md3>
                            <v-date-picker
                                    width="230"
                                    no-title
                                    v-model="date"
                                    color="teal"
                                    :picker-date.sync="date"
                                    :events="dayReservations"
                                    event-color="green lighten-1"
                            ></v-date-picker>
                        </v-flex>
                        <v-flex xs6 md3 style="background: teal; color: white; padding: 5px 0px 0px 10px;">
                            <h4>Datos del día ({{ date }})</h4>
                            <v-divider></v-divider>
                            <div class="mt-3">
                                <div>Reservaciones: {{dayData.cant || 0}}</div>
                                <div>Facturación: {{dayData.total || 0}} CHF</div>
                                <div class="mt-2" v-show="dayData.users">
                                    <label class="subheading">Usuarios:</label>
                                    <div style="overflow-y: auto; max-height: 170px">
                                        <div v-for="(item) in dayData.users" v-html="item"></div>

                                    </div>
                                </div>
                            </div>
                        </v-flex>
                        <v-flex xs6 md3>
                            <v-date-picker
                                    width="230"
                                    no-title
                                    v-model="date2"
                                    color="teal"
                                    :picker-date.sync="date2"
                                    :events="dayReservations2"
                                    event-color="green lighten-1"
                            ></v-date-picker>
                        </v-flex>
                        <v-flex xs6 md3 style="background: teal; color: white; padding: 5px 0px 0px 10px;">
                            <h4>Datos del día ({{ date2 }})</h4>
                            <v-divider></v-divider>
                            <div class="mt-3">
                                <div>Reservaciones: {{dayData2.cant || 0}}</div>
                                <div>Facturación: {{dayData2.total || 0}} CHF</div>
                                <div class="mt-2" v-show="dayData2.users">
                                    <label class="subheading">Usuarios:</label>
                                    <div style="overflow-y: auto; max-height: 170px">
                                        <div v-for="(item) in dayData2.users" :key="item" v-html="item"></div>

                                    </div>
                                </div>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-title>
            <v-card-text>

            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import fetch from '../../utils/fetch';
    import moment from 'moment';

    export default {
        data: () => ({
            loading: false,
            numbers: false,
            month: false,
            monthData: {},
            dayData: {},
            date: "",
            dayReservations: [],
            dayData2: {},
            date2: "",
            dayReservations2: [],

        }),
        computed: mapGetters({}),
        methods: {},
        watch: {
            month(val) {
                let date = moment(val), $this = this;
                if (date.format('D') != 1) {
                    date.add(1, 'day');
                    this.month = date.format('YYYY-MM');
                    return;
                }
                ;
                this.date = moment(val).format('YYYY-MM-DD');
                this.date2 = moment(val).format('YYYY-MM-DD');
                let page = page = '/reservation-statistics?in=true&month=true&dates[start]=' + date.format('YYYY-MM-DD') + '&dates[end]=' + date.add(1, 'M').format('YYYY-MM-DD');
                this.loading = true;
                fetch(page)
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        $this.dayReservations = [];
                        let total = 0, users = [];
                        data.forEach(item => {
                            total = total + item.payment;
                            users.push({user: item.user.sex? 'Sr '+item.user.name:'Sra '+item.user.name, date: moment(item.date).format('DD')});
                            $this.dayReservations.push(moment(item.dateCarIn).format('YYYY-MM-DD'));
                        });
                        this.monthData = {cant: data.length || 0, total: parseFloat(total).toFixed(2), users: users};
                    })
                    .catch(e => {
                        this.loading = false;
                        console.log(e.message)
                    });
                date = moment(val);
                page = '/reservation-statistics?out=true&month=true&dates[start]=' + date.format('YYYY-MM-DD') + '&dates[end]=' + date.add(1, 'M').format('YYYY-MM-DD');
                this.loading = true;
                fetch(page)
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        $this.dayReservations2 = [];
                        data.forEach(item => {
                            $this.dayReservations2.push(moment(item.dateCarOut).format('YYYY-MM-DD'));
                        });
                    })
                    .catch(e => {
                        this.loading = false;
                        console.log(e.message)
                    });
            },
            date(val) {
                let page = page = '/reservation-statistics?in=true&dates[start]=' + moment(val).format('YYYY-MM-DD') + '&dates[end]=' + moment(val).add(1, 'days').format('YYYY-MM-DD');
                this.loading = true;
                return fetch(page)
                    .then(response => response.json())
                    .then(data => {
                        this.dayData = [];
                        this.loading = false;
                        if (!data.length)
                            return;
                        let total = 0, users = [];
                        data.forEach(item => {
                            total = total + item.payment;
                            users.push(item.user.sex? 'Sr '+item.user.name:'Sra '+item.user.name);
                        });
                        this.dayData = {cant: data.length || 0, total: parseFloat(total).toFixed(2), users: users};
                    })
                    .catch(e => {
                        this.loading = false;
                        console.log(e.message)
                    });
            },
            date2(val) {
                let page = page = '/reservation-statistics?out=true&dates[start]=' + moment(val).format('YYYY-MM-DD') + '&dates[end]=' + moment(val).add(1, 'days').format('YYYY-MM-DD');
                this.loading = true;
                return fetch(page)
                    .then(response => response.json())
                    .then(data => {
                        this.dayData2 = [];
                        this.loading = false;
                        if (!data.length)
                            return;
                        let total = 0, users = [];
                        data.forEach(item => {
                            total = total + item.payment;
                            users.push(item.user.sex? 'Sr '+item.user.name:'Sra '+item.user.name);
                        });
                        this.dayData2 = {cant: data.length || 0, total: parseFloat(total).toFixed(2), users: users};
                    })
                    .catch(e => {
                        this.loading = false;
                        console.log(e.message)
                    });
            }
        },
        created() {
            this.loading = true;
            this.month = moment().format('YYYY-MM');
            fetch('/statistics')
                .then(response => response.json())
                .then(data => {
                    this.numbers = data;
                    this.loading = false;
                })
                .catch(e => {
                    this.loading = false;
                    console.log(e.message)
                });
        },
    }
</script>
