<template>
    <div>
        <v-form>
            <v-layout wrap>
                <v-flex xs6 md4>
                    <v-text-field
                            v-model="item.name"
                            label="Nombre"
                            required
                    ></v-text-field>
                </v-flex>
            </v-layout>
            <v-layout wrap>
                <v-flex xs2 md1 d-flex>
                    <v-text-field
                            v-model="priceService"
                            label="Precio"
                            type="number"
                            :disabled="disabledPrice"
                    ></v-text-field>
                </v-flex>
                <v-flex xs6 md3 ml-1>
                    <v-select
                            v-model="carType"
                            :items="cars"
                            label="Tipos de carros"
                            :disabled="disabledPrice"
                    >
                        <template slot="selection" slot-scope="data">
                            <img v-if="data.item.height" :height="data.item.height - 10" src="/img/auto.png"
                                 class="py-1"/>
                            <label class="pl-2 d-inline">{{ data.item.text }}</label>
                        </template>
                        <template slot="item" slot-scope="data">
                            <template v-if="typeof data.item !== 'object'">
                                No hay datos disponibles
                            </template>
                            <template v-else>
                                <div slot="label"
                                     style="display: flex;align-items: center; justify-content: center">
                                    <div class="d-inline">
                                        <img v-if="data.item.height" :height="data.item.height" src="/img/auto.png"
                                             class="py-2"/>
                                    </div>
                                    <div class="d-inline ml-2">{{data.item.text}}</div>
                                </div>
                            </template>
                        </template>
                    </v-select>
                </v-flex>
                <v-flex xs2 md1>
                    <v-btn fab dark small color="primary" @click="addService">
                        <v-icon dark>add</v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>
            <div v-if="services.length">
                <v-layout wrap>
                    <v-flex xs4 md1>
                        <label>Precio</label>
                    </v-flex>
                    <v-flex xs8 md3>
                        <label>Tipo de carro</label>
                    </v-flex>
                </v-layout>
                <v-layout wrap v-for="price,index in services" :key="index">
                    <v-flex xs4 md1 class="valing">
                        {{price.price}}
                    </v-flex>
                    <v-flex xs8 md3>
                        <label>{{textCarType(price.type)}}</label>
                        <v-btn icon class="mx-0" @click="deletePrice(price)">
                            <v-icon color="red">delete</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
            </div>
            <v-layout wrap>
                <v-flex xs6 md4>
                    <v-textarea
                            v-model="item.description"
                            label="Descripción"
                            auto-grow
                            rows="1"
                    ></v-textarea>
                </v-flex>
            </v-layout>
            <v-layout wrap>
                <v-flex xs6 md2>
                    <v-checkbox
                            v-model="item.active"
                            label="Activar servicio"
                    ></v-checkbox>
                </v-flex>
            </v-layout>
            <v-flex xs12 md6>
                <v-btn small color="teal mx-0" @click="handleSubmit(item)">Guardar</v-btn>
            </v-flex>
        </v-form>
    </div>
</template>

<script>
    export default {
        data: () => ({
            services: [],
            disabledPrice: false,
            carType: null,
            priceService: null,
            cars: [{text: 'Todos los carros', value: 0}, {text: 'Pequeño', value: 1, height: 35}, {
                text: 'Medianos',
                value: 2,
                height: 40
            }, {text: 'Grandes', value: 3, height: 45}]
        }),
        methods: {
            deletePrice(item) {
                this.services.splice(this.services.indexOf(item), 1);
            },
            textCarType(value) {
                let type = this.cars.filter(item => value == item.value);
                return type[0].text;
            },
            addService() {
                if (typeof this.item.prices == typeof undefined)
                    this.item.prices = [];
                this.services.push({price: this.priceService, type: this.carType});
                // this.item.prices.push({price: this.priceService, type: this.carType});
                if (this.carType == 0) {
                    this.disabledPrice = true;
                }
                this.priceService = null;
                this.carType = null;
            }
        },
        props: {
            handleSubmit: {
                type: Function,
                required: true,
            },
            values: {
                type: Object,
                required: true
            },
            errors: {
                type: Object
            },
            initialValues: {
                type: Object
            }
        },
        computed: {
            item: function () {
                if(this.initialValues && typeof this.initialValues.prices != typeof undefined){
                    this.services = this.initialValues.prices;
                    // Object.assign(this.services, this.initialValues.prices);
                }
                return this.initialValues ? this.initialValues : this.values;
            }
        }
    }
</script>
