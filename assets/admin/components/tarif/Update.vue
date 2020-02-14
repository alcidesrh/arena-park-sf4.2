<template>
  <div>
    <v-layout>
      <h2 class="d-inline">Editar Tarifa</h2>
      <v-progress-linear
        style="max-width: 200px; margin-left: 15px"
        v-if="updateLoading || retrieveLoading"
        :indeterminate="true"
      ></v-progress-linear>
    </v-layout>
    <v-layout wrap>
      <v-flex xs12 md4>
        <TarifForm
          v-if="item"
          :handle-submit="update"
          :values="item"
          :errors="violations"
          :initialValues="retrieved"
        ></TarifForm>
      </v-flex>
      <v-flex xs12 md6 class="pl-3">
        <v-layout wrap>
          <v-flex xs12>
            Descuento por reservaciones en los últimos 12 meses.
            <v-btn fab dark small color="primary" @click="add">
              <v-icon dark>add</v-icon>
            </v-btn>
          </v-flex>
        </v-layout>
        <v-layout wrap v-for="i, index in discountsList" :key="index">
          <v-flex xs1 style="max-width: 50px">
            <v-text-field v-model="i.min"></v-text-field>
          </v-flex>
          <v-flex xs1 class="ml-1" style="max-width: 50px">
            <v-text-field v-model="i.max"></v-text-field>
          </v-flex>
          <v-flex xs1 class="ml-1" style="max-width: 50px">
            <v-text-field v-model="i.discount" label="%" v-if="index==0"></v-text-field>
            <v-text-field v-model="i.discount" v-else></v-text-field>
          </v-flex>
          <v-flex xs1 class="ml-1" style="max-width: 50px">
            <v-btn small icon class="mx-0 px-0" style="margin-top: 20px;" @click="del(i)">
              <v-icon color="red">delete</v-icon>
            </v-btn>
          </v-flex>
        </v-layout>
        <v-layout wrap v-if="retrieved">
          <v-flex xs12>
            <v-checkbox v-model="discount" label="Activar Descuento"></v-checkbox>
          </v-flex>
        </v-layout>
        <v-layout wrap>
          <v-flex xs12>
            <v-btn small color="teal mx-0" @click="saveDiscount">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import TarifForm from "./Form.vue";
import { mapGetters } from "vuex";

export default {
  created() {
    this.$store
      .dispatch("tarif/update/retrieve", "/tarifs/1")
      .then(() => {this.discount = this.retrieved.discount});

    this.$store.dispatch("tarif/update/getDiscount").then(() => {
      this.discountsList = this.discounts;
    });
  },
  components: {
    TarifForm
  },
  computed: {
    ...mapGetters({
      retrieveError: "tarif/update/retrieveError",
      retrieveLoading: "tarif/update/retrieveLoading",
      updateError: "tarif/update/updateError",
      updateLoading: "tarif/update/updateLoading",
      retrieved: "tarif/update/retrieved",
      updated: "tarif/update/updated",
      violations: "tarif/update/violations",
      discounts: "tarif/update/discounts"
    })
  },
  data: function() {
    return {
      item: {},
      discountsList: [],
      discount: false
    };
  },
  methods: {
    update(values, discount = false) {
      if (discount) {      
        values.discount = this.discount;
      } else {
        values.day = parseInt(values.day);
        values.annulation = parseInt(values.annulation);
        values.descount = parseInt(values.descount);
        values.priceCharge = parseInt(values.priceCharge);
        values.tva = parseFloat(values.tva);
        values.smsConfirmation = parseFloat(values.smsConfirmation);
      }
      this.$store.dispatch("tarif/update/update", {
        item: this.retrieved,
        values: values
      });
    },
    reset() {
      this.$store.dispatch("tarif/update/reset");
    },
    saveDiscount() {
      this.update(this.retrieved, true);
      let values = {};
      this.discountsList.forEach((i, index) => {
        values.min = parseInt(i.min);
        values.max = i.max ? parseInt(i.max) : 0;
        values.discount = parseFloat(i.discount);
        if (i["@id"]) {
          this.$store.dispatch("tarif/update/update", {
            item: i,
            values: values
          });
        } else {
          this.$store.dispatch("tarif/update/create", values).then(response => {
            this.discountsList[index] = response;
          });
        }
      });
    },
    add() {
      this.discountsList.push({ min: null, max: null, discount: null });
    },
    del(item) {
      if (item["@id"]) {
        this.$store.dispatch("tarif/update/delete", item).then(() => {
          this.discountsList.splice(this.discountsList.indexOf(item), 1);
        });
      } else this.discountsList.splice(this.discountsList.indexOf(item), 1);
    }
  },
  beforeDestroy() {
    this.reset();
  }
};
</script>
