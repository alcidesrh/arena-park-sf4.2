<template>
    <v-app light>
        <v-navigation-drawer
                fixed
                :mini-variant="miniVariant"
                v-model="drawer"
                app
        >
            <v-list>
                <v-list-tile id="home" class="admin-link" @click="$router.push({name: 'Home'})">
                    <v-list-tile-action>
                        <v-icon>equalizer</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Estadísticas</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="reservations" class="admin-link" @click="$router.push({name: 'ReservationList'})">
                    <v-list-tile-action>
                        <v-icon>time_to_leave</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Reservaciones</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="users" class="admin-link" @click="$router.push({name: 'UserList'})">
                    <v-list-tile-action>
                        <v-icon>people</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Usuarios</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="pages-list" class="admin-link" @click="$router.push({name: 'PageList'})">
                    <v-list-tile-action>
                        <v-icon>list</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Páginas</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="service" class="admin-link" @click="$router.push({name: 'ServiceList'})">
                    <v-list-tile-action>
                        <v-icon>grain</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Servicios</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="tarif" class="admin-link" @click="$router.push({name: 'TarifUpdate'})">
                    <v-list-tile-action>
                        <v-icon>attach_money</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Tarifa</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="airport" class="admin-link" @click="$router.push({name: 'AirportList'})">
                    <v-list-tile-action>
                        <v-icon>local_airport</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Aereopuertos</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="contact" class="admin-link" @click="$router.push({name: 'ContactUpdate'})">
                    <v-list-tile-action>
                        <v-icon>email</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Contacto</v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile id="email" class="admin-link" @click="$router.push({name: 'EmailList'})">
                    <v-list-tile-action>
                        <v-icon>send</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Enviar Email</v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar app>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title v-text="title"></v-toolbar-title>
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
                        v-model="date"
                        label="Reservaciones para"
                        prepend-icon="event"
                        readonly
                ></v-text-field>
                <v-date-picker v-model="date" @input="menu = false" no-title>
                </v-date-picker>
            </v-menu>
            <v-btn small color="primary" @click="getReport" v-if="!loading">Descargar PDF</v-btn>
            <v-progress-circular indeterminate color="primary" v-else></v-progress-circular>
        </v-toolbar>
        <v-content>
            <v-container>
                <v-layout>
                    <v-flex>
                        <transition name="fade">
                            <router-view></router-view>
                        </transition>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    import fetch from './utils/fetch';
    import $ from 'jquery';
    import moment from 'moment';
    import {API_HOST} from './config/_entrypoint';
    export default {
        data: () => ({
            drawer: true,
            miniVariant: false,
            right: true,
            title: 'Arena Park',
            loading: false,
            menu: false,
            modal: false,
            menu2: false,
            date: null
        }),

        methods: {
            getReport(){
                this.loading = true;
                fetch('/pdf-report', {method: 'POST', body: JSON.stringify(this.date)})
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        window.open(API_HOST+'/'+data);
                    })
                    .catch(e => {
                        console.log(e.message);
                    });
            }
        },
        created(){
            this.date = moment().add(1, 'days').format('YYYY-MM-DD');
        },
        mounted() {
            $('body').on('click', '.admin-link', function(){
                $('.section-focus').removeClass('section-focus');
                $(this).addClass('section-focus');
            });
            this.$nextTick(function () {
                if (typeof route_reload != typeof undefined) {

                    if (route_reload.length == 1) {
                        $('#home').click();
                    }
                    else if (route_reload.length == 2){
                        $('#'+route_reload[1]).click();
                    }
                    else {
                        if (route_reload[1] == 'page')
                            document.getElementById('pages-list').firstChild.click();
                        else if (route_reload[1] == 'service')
                            document.getElementById('service').firstChild.click();
                        else
                            document.getElementById('gestion_producto').firstChild.click();
                        let path = "";
                        route_reload.forEach(function (item) {
                            path = path + '/' + item;
                        });
                        this.$router.push({path: path});
                    }
                }
            })
        }
    }
</script>

