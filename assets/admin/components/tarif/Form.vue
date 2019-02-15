<template>
  <div>
    <v-form>
      <v-layout wrap>
        <v-flex xs6 md2>
          <v-text-field
                  v-model="item.day"
                  type="number"
                  label="Jour"
                  required
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs6 md2>
          <v-text-field
                  v-model="item.priceCharge"
                  label="Prise en charge du véhicule"
                  type="number"
                  required
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs6 md2>
          <v-text-field
                  v-model="item.annulation"
                  label="Assurance annulation vol"
                  type="number"
                  required
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs6 md2>
          <v-text-field
                  v-model="item.tva"
                  label="TVA"
                  type="number"
                  required
          ></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout wrap>
        <v-flex xs6 md2>
          <v-text-field
                  v-model="item.descount"
                  label="Descuento en %"
                  type="number"
                  required
          ></v-text-field>
        </v-flex>
        <v-flex xs6 md2>
          <v-checkbox
                  v-model="item.activeDescount"
                  label="Activar Descuento"
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
