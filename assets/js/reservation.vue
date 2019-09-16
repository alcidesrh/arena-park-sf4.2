<template>
    <v-app light>
        <v-card :bind-style="{opacity: loading?0.7:1}">
            <div id="validationError">
                <v-alert
                        v-if="validationError && !confirm"
                        :value="true"
                        type="warning"
                >
                    Remplissez toutes les informations requises
                </v-alert>
            </div>
            <v-alert class="white-text reservation-success"
                    v-if="success"
                    :value="true"
                    type="success"
            >
                Votre réservation a été bien traitée. Vous recevrez un e-mail avec votre confirmation. Le document doit
                être imprimé et présenté au voiturier le jour de la prise en charge.
            </v-alert>
            <v-alert
                    v-if="errorMessage"
                    :value="true"
                    type="error"
            >
                {{errorMessage}}
            </v-alert>
            <v-form id="form" v-model="valid" ref="form" v-show="!confirm && !success && !errorMessage">
                <v-layout row wrap>
                    <v-flex xs12 md6 offset-md3>
                        <div id="info-person" class="section">
                            <h6>Informations personnelles</h6>
                            <v-divider></v-divider>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-menu v-model="menuSearchUser" bottom offset-y :open-on-click="false"
                                            style="width: 100%">
                                        <v-text-field
                                                slot="activator"
                                                label="Votre email"
                                                prepend-icon="email"
                                                v-model="user.email"
                                                v-on:keyup="searchUser"
                                                :rules="emailRules"
                                                required
                                        ></v-text-field>
                                        <v-list class="user-list">
                                            <v-list-tile v-for="(item, index) in users" :key="index"
                                                         @click="setUser(item)">
                                                <v-list-tile-title>{{item.email}}</v-list-tile-title>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="user.phone"
                                            label="Téléphone"
                                            prepend-icon="smartphone"
                                            :rules="requireRules"
                                            required
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="user.name"
                                            label="Prénom et Nom"
                                            prepend-icon="person"
                                            :rules="requireRules"
                                            required
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-radio-group v-model="user.sex" row>
                                        <v-radio label="Mme"
                                                 :value="false"></v-radio>
                                        <v-radio label="Mr"
                                                 :value="true"></v-radio>
                                    </v-radio-group>
                                </v-flex>
                            </v-layout>
                        </div>

                        <div id="info-car" class="section">
                            <h6>Véhicule</h6>
                            <v-divider></v-divider>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="car.mark"
                                            label="Marque du véhicule"
                                            prepend-icon="directions_car"
                                            :rules="requireRules"
                                            required
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="car.plate"
                                            label="Numéro de plaques"
                                            prepend-icon="more"
                                            :rules="requireRules"
                                            required
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="car.color"
                                            label="Couleur"
                                            prepend-icon="color_lens"
                                            :rules="requireRules"
                                            required
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                        </div>

                        <div class="section">
                            <h6>Départ</h6>
                            <v-divider></v-divider>

                            <v-layout row wrap>
                                <v-flex xs12 md9>
                                    <v-menu
                                            ref="dateFlyOutDialog"
                                            v-model="dateFlyOutDialog"
                                            :return-value.sync="dateFlyOutDialog"
                                            lazy
                                            full-width
                                            width="290px"
                                            :close-on-content-click="true"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.dateFlyOut"
                                                label="Date et heure de décollage"
                                                prepend-icon="event"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-date-picker v-model="reservation.dateFlyOut" no-title scrollable @change="reservation.dateCarIn = reservation.dateFlyOut"
                                                       locale="Fr-fr" :min="currentDate">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-dialog
                                            ref="hourFlyOutDialog"
                                            v-model="hourFlyOutDialog"
                                            :return-value.sync="hourFlyOutDialog"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.hourFlyOut"
                                                label="Heure"
                                                prepend-icon="access_time"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-time-picker
                                                v-if="hourFlyOutDialog"
                                                v-model="reservation.hourFlyOut"
                                                format="24hr"
                                                full-width
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="hourFlyOutDialog = false">Cancel</v-btn>
                                            <v-btn flat color="primary"
                                                   @click="$refs.hourFlyOutDialog.save(reservation.hourFlyOut)">
                                                OK
                                            </v-btn>
                                        </v-time-picker>
                                    </v-dialog>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12 md9>
                                    <v-menu
                                            ref="dateCarInDialog"
                                            v-model="dateCarInDialog"
                                            :return-value.sync="dateCarInDialog"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.dateCarIn"
                                                label="Date et heure de prise en charge"
                                                prepend-icon="event"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-date-picker v-model="reservation.dateCarIn" no-title scrollable disabled
                                                       :max="reservation.dateFlyOut" locale="Fr-fr">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-dialog
                                            ref="hourCarInDialog"
                                            v-model="hourCarInDialog"
                                            :return-value.sync="hourCarInDialog"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.hourCarIn"
                                                label="Heure"
                                                prepend-icon="access_time"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-time-picker
                                                v-if="hourCarInDialog"
                                                v-model="reservation.hourCarIn"
                                                :allowed-hours="allowedHours"
                                                :max="reservation.dateCarIn == reservation.dateFlyOut ? reservation.hourFlyOut : undefined"
                                                :allowed-minutes="(val) => !(val%5)"
                                                format="24hr"
                                                full-width
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="hourCarInDialog = false">Cancel</v-btn>
                                            <v-btn flat color="primary"
                                                   @click="$refs.hourCarInDialog.save(reservation.hourCarIn)">OK
                                            </v-btn>
                                        </v-time-picker>
                                    </v-dialog>
                                </v-flex>
                            </v-layout>
                        </div>

                        <div id="info-reservation" class="section">
                            <h6>Arrivée</h6>
                            <v-divider></v-divider>
                            <v-layout row wrap>
                                <v-flex xs12 md9>
                                    <v-menu
                                            ref="dateFlyInDialog"
                                            v-model="dateFlyInDialog"
                                            :return-value.sync="dateFlyInDialog"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.dateFlyIn"
                                                label="Date et heure d’arrivée"
                                                prepend-icon="event"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-date-picker v-model="reservation.dateFlyIn" no-title scrollable  @change="reservation.dateCarOut = reservation.dateFlyIn"
                                                       locale="Fr-fr" :min="reservation.dateFlyOut">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-dialog
                                            ref="hourFlyInDialog"
                                            v-model="hourFlyInDialog"
                                            :return-value.sync="hourFlyInDialog"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.hourFlyIn"
                                                label="Heure"
                                                prepend-icon="access_time"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-time-picker
                                                v-if="hourFlyInDialog"
                                                v-model="reservation.hourFlyIn"
                                                format="24hr"
                                                full-width
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="hourFlyInDialog = false">Cancel</v-btn>
                                            <v-btn flat color="primary"
                                                   @click="$refs.hourFlyInDialog.save(reservation.hourFlyIn)">OK
                                            </v-btn>
                                        </v-time-picker>
                                    </v-dialog>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-autocomplete
                                            v-model="reservation.airport"
                                            :items="airports"
                                            item-text="name"
                                            item-value="id"
                                            label="Aéroport de provenance"
                                            persistent-hint
                                            prepend-icon="local_airport"
                                            :rules="requireRules"
                                            required
                                    >
                                        <v-slide-x-reverse-transition
                                                slot="append-outer"
                                                mode="out-in"
                                        >
                                        </v-slide-x-reverse-transition>
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="reservation.fly"
                                            label="Numéro de vol"
                                            prepend-icon="more"
                                            :rules="requireRules"
                                            required
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12 md9>
                                    <v-menu
                                            ref="dateCarOutDialog"
                                            v-model="dateCarOutDialog"
                                            :return-value.sync="dateCarOutDialog"
                                            lazy
                                            full-width
                                            width="290px"
                                            :close-on-content-click="true"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.dateCarOut"
                                                label="Date et heure de restitution"
                                                prepend-icon="event"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-date-picker v-model="reservation.dateCarOut" no-title scrollable
                                                       locale="Fr-fr" :min="reservation.dateFlyIn">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-dialog
                                            ref="hourCarOutDialog"
                                            v-model="hourCarOutDialog"
                                            :return-value.sync="hourCarOutDialog"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                v-model="reservation.hourCarOut"
                                                label="Heure"
                                                prepend-icon="access_time"
                                                readonly
                                                :rules="requireRules"
                                                required
                                        ></v-text-field>
                                        <v-time-picker
                                                v-if="hourCarOutDialog"
                                                v-model="reservation.hourCarOut"
                                                :allowed-hours="allowedHours"
                                                :allowed-minutes="(val) => !(val%5)"
                                                :min="reservation.dateCarOut == reservation.dateFlyIn ? reservation.hourFlyIn : undefined"
                                                format="24hr"
                                                full-width
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="hourCarOutDialog = false">Cancel</v-btn>
                                            <v-btn flat color="primary"
                                                   @click="$refs.hourCarOutDialog.save(reservation.hourCarOut)">
                                                OK
                                            </v-btn>
                                        </v-time-picker>
                                    </v-dialog>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-layout row wrap>
                                        <v-flex>Bagages en soute?</v-flex>
                                        <v-flex>
                                            <v-radio-group v-model="reservation.baggage" row class="d-inline">
                                                <v-radio label="Oui"
                                                         :value="true"></v-radio>
                                                <v-radio label="No"
                                                         :value="false"></v-radio>
                                            </v-radio-group>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                        </div>
                    </v-flex>
                </v-layout>

                <v-layout row wrap>
                    <v-flex xs12 md10 offset-md1>
                        <div class="section" v-if="services1.length || services2.length">
                            <h6>Services supplémentaires</h6>
                            <v-divider></v-divider>
                            <div v-if="services1.length">
                                <v-layout row wrap>
                                    <v-flex xs12 md6>

                                    </v-flex>
                                    <v-flex xs4 md2>
                                        <v-layout align-end justify-start row fill-height>
                                            <i class="small material-icons small_car">local_car_wash</i>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs4 md2>
                                        <v-layout align-end justify-start row fill-height>
                                            <i class="small material-icons middle_car">local_car_wash</i></v-layout>
                                    </v-flex>
                                    <v-flex xs4 md2>
                                        <v-layout align-end justify-start row fill-height>
                                            <i class="small material-icons big_car">local_car_wash</i></v-layout>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap v-if="services1.length>1">
                                    <v-flex xs12 md6>{{services1[0].name}}</v-flex>
                                    <v-flex xs12 md6>
                                        <v-radio-group v-model="serviceRadio1" row @change="validateService(0)">
                                            <v-radio :label="services1[0].prices[0].price+' CHF'"
                                                     :value="0"></v-radio>
                                            <v-radio :label="services1[0].prices[1].price+' CHF'"
                                                     :value="1"></v-radio>
                                            <v-radio :label="services1[0].prices[2].price+' CHF'"
                                                     :value="2"></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap v-if="services1.length>2">
                                    <v-flex xs12 md6>{{services1[1].name}}</v-flex>
                                    <v-flex xs12 md6>
                                        <v-radio-group v-model="serviceRadio2" row @change="validateService(1)">
                                            <v-radio :label="services1[1].prices[0].price+' CHF'"
                                                     :value="0"></v-radio>
                                            <v-radio :label="services1[1].prices[1].price+' CHF'"
                                                     :value="1"></v-radio>
                                            <v-radio :label="services1[1].prices[2].price+' CHF'"
                                                     :value="2"></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap v-if="services1.length==3">
                                    <v-flex xs12 md6>{{services1[2].name}}</v-flex>
                                    <v-flex xs12 md6>
                                        <v-radio-group v-model="serviceRadio3" row @change="validateService(2)">
                                            <v-radio :label="services1[2].prices[0].price+' CHF'"
                                                     :value="0"></v-radio>
                                            <v-radio :label="services1[2].prices[1].price+' CHF'"
                                                     :value="1"></v-radio>
                                            <v-radio :label="services1[2].prices[2].price+' CHF'"
                                                     :value="2"></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                </v-layout>
                            </div>
                            <v-divider></v-divider>
                            <div v-if="services2.length" class="mt-5">
                                <v-layout row wrap v-for="service,index in services2" :key="index">
                                    <v-flex xs12 md6>{{service.name}}</v-flex>
                                    <v-flex xs12 md6 v-if="service.id == 7">
                                        <v-radio-group v-model="serviceSelected[index]"
                                                       :rules="requireRules"
                                                       required>
                                            <v-radio
                                                    :label="'Oui, '+service.prices[0].price+' CHF'"
                                                    :value="service.id"
                                            ></v-radio>
                                            <v-radio
                                                    label="Non"
                                                    value="Nom">
                                            ></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <v-flex xs12 md6 v-else>
                                        <v-checkbox v-model="serviceSelected[index]"
                                                    :label="service.prices[0].price+' CHF'"
                                                    :value="service.id"></v-checkbox>
                                    </v-flex>
                                        <v-flex v-if="service.id == 7" xs12 style="font-weight: bold">{{service.description}}</v-flex>
                                </v-layout>
                            </div>

                        </div>
                    </v-flex>
                </v-layout>

                <v-layout row wrap>
                    <v-flex xs12 md6 offset-md3>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea
                                        v-model="reservation.message"
                                        label="Remarques"
                                        prepend-icon="message"
                                        auto-grow
                                        rows="1"
                                ></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>

                <v-layout row wrap>
                    <v-flex xs12 md6 offset-md3>
                        <div id="info-payment" class="section">
                            <h6>Mode de paiement</h6>
                            <v-divider></v-divider>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-radio-group v-model="payment" row>
                                        <v-flex xs12 mb-2>
                                        <v-radio label="Cash" :value="1"></v-radio>
                                        </v-flex>
                                        <v-flex xs12 mb-2>
                                        <v-radio label="Visa" :value="2"></v-radio>
                                        </v-flex>
                                        <v-flex xs12 mb-2>
                                        <v-radio label="MasterCard" :value="3"></v-radio>
                                        </v-flex>
                                        <v-flex xs12 mb-2>
                                        <v-radio label="PostFinance card" :value="4"></v-radio>
                                        </v-flex>
                                        <v-flex xs12    >
                                        <v-radio label="PostFinance e-finance" :value="5"></v-radio>
                                        </v-flex>
                                    </v-radio-group>
                                </v-flex>
                            </v-layout>
                        </div>
                    </v-flex>
                </v-layout>

                <v-layout row wrap>
                    <v-flex xs12 md6 offset-md3>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-checkbox :rules="requireRules" required :value="payment">
                                    <div slot="label">
                                        Oui, j’accepte les <a href="conditions" target="_blank">Conditions Générales</a>
                                        ainsi que la <a href="protection" target="_blank">Protection des Données</a>
                                        d’Arena-Park Sàrl.
                                    </div>
                                </v-checkbox>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>

                <v-layout row wrap>
                    <v-flex xs12 md6 offset-md3>
                        <v-layout justify-center row fill-height>
                            <v-btn small color="teal" class="text-xs-center" style="color: white"
                                   @click="validateFields">
                                Ok
                            </v-btn>
                        </v-layout>
                    </v-flex>
                </v-layout>

            </v-form>
            <div v-if="confirm && !success">
                <h6>S'il vous plaît vérifier que les données sont correctes</h6>
                <v-divider></v-divider>
                <v-layout row wrap>
                    <v-flex xs12 md6>
                        <v-card>
                            <v-card-title primary-title>
                                <div>
                                    <h6>Informations personnelles</h6>
                                    <v-divider></v-divider>
                                    <p>Nom: {{user.sex?'Mr ':+'Mme '}}{{user.name}}</p>
                                    <p>Email: {{user.email}}</p>
                                    <p>Téléphone: {{user.phone}}</p>
                                </div>
                            </v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn icon>
                                    <v-icon @click="editInfo()">edit</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 md6>
                        <v-card>
                            <v-card-title primary-title>
                                <div>
                                    <h6>Véhicule</h6>
                                    <v-divider></v-divider>
                                    <p>Marque du véhicule: {{car.mark}}</p>
                                    <p>Numéro de plaques: {{car.plate}}</p>
                                    <p>Couleur: {{car.color}}</p>
                                </div>
                            </v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn icon>
                                    <v-icon @click="editInfo()">edit</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex xs12 md6>
                        <v-card>
                            <v-card-title primary-title>
                                <div>
                                    <h6>Réservation</h6>
                                    <v-divider></v-divider>
                                    <p>Date et heure de décollage: {{reservation.dateFlyOut +' '+
                                        reservation.hourFlyOut}}</p>
                                    <p>Date et heure de prise en charge: {{reservation.dateCarIn +' '+
                                        reservation.hourCarIn}}</p>
                                    <p>Date et heure d’arrivée: {{reservation.dateFlyIn +' '+
                                        reservation.hourFlyIn}}</p>
                                    <p>Restitution du véhicule: {{reservation.dateCarOut +' '+
                                        reservation.hourCarOut}}</p>
                                    <p>Aéroport de provenance: {{getAirportName()}}</p>
                                    <p>Numéro de vol: {{reservation.fly}}</p>
                                    <p>Bagages: {{reservation.baggage?'Qui':'Non'}}</p>
                                </div>
                            </v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn icon>
                                    <v-icon @click="editInfo()">edit</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 md6>
                        <v-card>
                            <v-card-title primary-title>
                                <div style="min-width: 100%">
                                    <h6>Paiement</h6>
                                    <v-divider></v-divider>
                                    <div>Prise en charge:<span class="right">{{tarif.priceCharge}} CHF</span></div>
                                    <div>Assurance annulation de vol:<span class="right">{{tarif.annulation}} CHF</span>
                                    </div>
                                    <div v-for="service in reservation.services" v-if="service.id != 7 || (service.id == 7 && service.price != 'Nom')">{{getServiceName(service.id)}}:<span
                                            class="right">{{getServicePrice(service.id, service.price)}} CHF</span>
                                    </div>
                                    <div>Gardiennage:<span class="right">{{tarif.gardiennage}} CHF</span></div>
                                    <div v-if="tarif.activeDescount" class="text-xs-right" style="color: red;">
                                        {{'-'+tarif.descount+'%'}}
                                    </div>
                                    <div v-if="payment > 1" class="text-xs-right" style="color: red;">
                                        TVA +{{tarif.tva+'%'}}
                                    </div>
                                    <div>TOTAL À PAYER:<span class="right">{{reservation.total}} CHF</span></div>
                                </div>
                            </v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn icon>
                                    <v-icon @click="editInfo()">edit</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-layout align-center justify-center row fill-height>
                    <v-btn small color="teal" class="text-xs-center" style="color: white"
                           @click="sendReservation">
                        Ok
                    </v-btn>
                </v-layout>
            </div>


        </v-card>
        <v-layout justify-center aling-center class="loading-div" v-if="loading">
            <v-progress-linear style="max-width: 300px"
                               :indeterminate="true"></v-progress-linear>
        </v-layout>
    </v-app>
</template>
<script>
    import axios from 'axios';
    import moment from 'moment';
    import {API_HOST, API_PATH} from "../admin/config/_entrypoint";

    export default {
        data: () => ({
            user: {},
            email: null,
            users: [],
            reservation: {agree: true},
            car: {},
            menuSearchUser: false,
            dateFlyInDialog: false,
            hourFlyInDialog: null,
            dateFlyOutDialog: false,
            hourFlyOutDialog: null,
            dateCarInDialog: false,
            hourCarInDialog: null,
            dateCarOutDialog: null,
            hourCarOutDialog: null,

            currentDate: moment().format('YYYY-MM-DD'),

            valid: null,
            payment: null,

            airports: [],
            services: [],
            services1: [],
            services2: [],
            serviceRadio1: null,
            serviceRadio2: null,
            serviceRadio3: null,
            serviceSelected: [],
            validationError: false,
            emailLength: 0,
            loading: false,
            searchUserLoading: false,
            confirm: false,
            success: false,
            errorMessage: false,
            requireRules: [
                v => !!v || 'Ce champ est obligatoire'
            ],
            emailRules: [
                v => !!v || 'Ce champ est obligatoire',
                v => /.+@.+/.test(v) || "L'email doit être valide"
            ]
        }),

        methods: {
            allowedHours: val => [4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24].includes(val),
            sendReservation() {
                this.reservation.payment = this.payment;
                let data = new FormData(), $this = this;
                data.append('user', JSON.stringify(this.user));
                data.append('car', JSON.stringify(this.car));
                data.append('reservation', JSON.stringify(this.reservation));
                this.loading = true;
                axios.post('/save-reservation', data).then(function (response) {
                    $this.loading = false;
                    if(typeof response.data.urlRedirect != typeof undefined)
                        window.location.href = response.data.urlRedirect;
                    else{
                        $this.success = true;
                        $this.$vuetify.goTo(0, 3000);
                    }
                }).catch(function (error) {
                    console.log(error);
                    $this.loading = false;
                });
            },
            validateFields() {

                if (this.$refs.form.validate()) {
                    let services = [];
                    if (this.serviceRadio3 || this.serviceRadio3 === 0) {
                        services.push({id: this.services1[2].id, price: this.serviceRadio3});
                    }
                    else {
                        if (this.serviceRadio1 || this.serviceRadio1 === 0) {
                            services.push({id: this.services1[0].id, price: this.serviceRadio1});
                        }
                        if (this.serviceRadio2 || this.serviceRadio2 === 0) {
                            services.push({id: this.services1[1].id, price: this.serviceRadio2});
                        }
                    }
                    if (this.serviceSelected.length) {
                        for (let i = 0, service; i < this.serviceSelected.length; i++) {
                            service = this.services2.filter(item => item.id == this.serviceSelected[i])
                            if (service.length)
                                services.push({id: service[0].id, price: 0})
                        }
                    }
                    if (services.length)
                        this.reservation.services = services;
                    this.calculePrice();

                    this.confirm = true;
                    this.$vuetify.goTo(0, 3000);
                    return;

                }
                else {
                    this.validationError = true;
                    this.$vuetify.goTo(0, 3000);
                }
            },
            validateService(index) {
                if (index == 2) {
                    this.serviceRadio1 = this.serviceRadio2 = null;
                }
                else
                    this.serviceRadio3 = null;
            },
            searchUser(val) {
                if (!this.searchUserLoading && this.user.email.length >= 6 && this.emailLength < this.user.email.length) {
                    let $this = this;
                    this.searchUserLoading = true;
                    axios.get(this.getEndPoint('/users?email=' + this.user.email))
                        .then(function (response) {
                            $this.searchUserLoading = false;
                            if (response.data['hydra:member'].length && typeof $this.user.id == typeof undefined) {
                                $this.users = response.data['hydra:member'];
                                $this.menuSearchUser = true;
                            }
                        })
                        .catch(function (error) {
                            $this.searchUserLoading = false;
                            console.log(error);
                        });
                }
                else if (this.emailLength > this.user.email.length && typeof this.user.id != typeof undefined) {
                    this.user = {email: this.user.email};
                    this.car = {};
                }
                this.menuSearchUser = false;
                this.emailLength = this.user.email.length;

            },
            setUser(user) {
                this.user = user;
                this.emailLength = this.user.email.length;
                this.car = user.car;
            },
            calculePrice() {
                let days = moment(this.reservation.dateCarOut).diff(moment(this.reservation.dateCarIn), 'days') + 1;
                this.tarif.gardiennage = days * this.tarif.day;
                this.reservation.total = this.tarif.gardiennage + this.tarif.priceCharge + this.tarif.annulation;

                if (typeof this.reservation.services != typeof undefined) {
                    for (let i = 0, service; i < this.reservation.services.length; i++) {
                        service = this.services.filter(item => item.id == this.reservation.services[i].id);
                        this.reservation.total = this.reservation.total + parseFloat(service[0].prices[this.reservation.services[i].price].price);
                    }
                }

                if (this.tarif.activeDescount)
                    this.reservation.total = this.reservation.total - (this.reservation.total * (this.tarif.descount / 100));
                if(this.payment > 1)
                    this.reservation.total = parseFloat(this.reservation.total + (this.reservation.total * (parseFloat(this.tarif.tva) / 100))).toFixed(2);



            },
            getEndPoint(resource) {
                return API_HOST + API_PATH + resource;
            },
            getAirportName() {
                let airport = this.airports.filter(item => item.id == this.reservation.airport);
                return airport[0].name
                    ;
            },
            getServiceName(id) {
                let service = this.services.filter(item => item.id == id);
                return service[0].id == 7?'Contrôle visuel':service[0].name;
            },
            getServicePrice(id, index) {
                let service = this.services.filter(item => item.id == id);
                return service[0].prices[index].price;
            },
            editInfo() {
                this.confirm = false;
                this.$vuetify.goTo(0, 3000);
            }
        },
        created() {
            if( typeof reservationConfirmed != typeof undefined){
                this.success = true;
                return;
            }
            if( typeof errorPayment != typeof undefined){
                this.errorMessage = errorPayment;
                return;
            }
            let $this = this;
            this.loading = true;
            axios.get(this.getEndPoint('/airports'))
                .then(function (response) {
                    $this.airports = response.data['hydra:member'];
                    $this.loading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    $this.loading = false;
                });
            axios.get(this.getEndPoint('/services'))
                .then(function (response) {
                    $this.services = response.data['hydra:member'];
                    $this.services1 = $this.services.filter(item => item.prices.length == 3)
                    $this.services2 = $this.services.filter(item => item.prices.length == 1);
                    $this.loading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    $this.loading = false;
                });
            axios.get(this.getEndPoint('/tarifs/1'))
                .then(function (response) {
                    $this.tarif = response.data;
                    $this.loading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    $this.loading = false;
                });
        },
        mounted() {
            this.payment = 1;
        }
    }
</script>