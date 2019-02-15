<template>
  <div>
    <v-card>
      <v-card-title>
        Servicios
        <router-link :to="{name: 'ServiceCreate'}">
          <v-btn fab dark small color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
        </router-link>
        <v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="loading || deleteLoading"
                           :indeterminate="true"></v-progress-linear>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-data-table
              :headers="headers"
              :items="items"
              hide-actions
      >
        <template slot="items" slot-scope="props">
          <td>{{ props.item.name }}</td>
          <td>
            <div v-for="item,index in props.item.prices" :key="index">
              {{getCarType(item.type).text }}: {{item.price }}
            </div>
          </td>
          <td>{{ props.item.description }}</td>
          <td><v-icon :color="props.item.active?'teal':'red'">check</v-icon></td>
          <td>
            <v-btn icon class="mx-0" @click="$router.push({name: 'ServiceUpdate', params: {id: props.item['id']}})">
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
                    text: 'Nombre', sortable: false
                },
                { text: 'Tipos de carros & precios', sortable: false },
                { text: 'Descripción', sortable: false },
                { text: 'Activado', sortable: false },
                { text: '', sortable: false }
            ],
            cars: [{text: 'Todos los carros', value: 0}, {text: 'Pequeño', value: 1, height: 35}, {
                text: 'Medianos',
                value: 2,
                height: 40
            }, {text: 'Grandes', value: 3, height: 45}]
        }),
        computed: mapGetters({
            deletedItem: 'service/del/deleted',
            error: 'service/list/error',
            items: 'service/list/items',
            loading: 'service/list/loading',
            view: 'service/list/view',
            deleteError: 'service/del/error',
            deleteLoading: 'service/del/loading',
            deleted: 'service/del/deleted',
        }),
        methods: {
            getCarType(value) {
                let type = this.cars.filter(item => value == item.value);
                return type[0];
            },
            del (item) {
                if (window.confirm('Seguro quiere eliminar el servicio'))
                    this.$store.dispatch('service/del/delete', item);
            },
        },

        created() {
                this.$store.dispatch('service/list/getItems');

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
                    this.$store.dispatch('service/list/getItems');
                }
            }
        }
    }
</script>
