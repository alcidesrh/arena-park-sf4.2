<template>
  <div>
    <v-form>
      <v-layout wrap>
        <v-flex xs12 lg6>
          <v-text-field
                  v-model="item.address"
                  label="Dirección"
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs12 lg2>
          <v-text-field
                  v-model="item.phone"
                  label="Teléfono fijo"
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs12 lg2>
          <v-text-field
                  v-model="item.movil"
                  label="Celular"
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs12 lg2>
          <v-text-field
                  v-model="item.phoneUrgence"
                  label="Teléfono de urgencia"
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs12 lg2>
          <v-text-field
                  v-model="item.email"
                  label="Correo electrónico"
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs12 lg2>
          <v-text-field
                  v-model="item.registre"
                  label="Registro comercial"
          ></v-text-field>
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
                return this.initialValues ? this.initialValues : this.values;
            }
        }
    }
</script>
