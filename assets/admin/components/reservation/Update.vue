<template>
  <div>
    <h1>Edit {{ item && item['@id'] }}</h1>

    <div v-if="created" class="alert alert-success" role="status">{{ created['@id'] }} created.</div>
    <div v-if="updated" class="alert alert-success" role="status">{{ updated['@id'] }} updated.</div>
    <div v-if="retrieveLoading || updateLoading || deleteLoading"class="alert alert-info" role="status">Loading...</div>
    <div v-if="retrieveError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ retrieveError }}</div>
    <div v-if="updateError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ updateError }}</div>
    <div v-if="deleteError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ deleteError }}</div>

    <ReservationForm v-if="item" :handle-submit="update" :values="item" :errors="violations" :initialValues="retrieved"></ReservationForm>
    <router-link v-if="item" :to="{ name: 'ReservationList' }" class="btn btn-default">Back to list</router-link>
    <button @click="del" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import ReservationForm from './Form.vue';
  import { mapGetters } from 'vuex';

  export default {
    created () {
      this.$store.dispatch('reservation/update/retrieve', decodeURIComponent(this.$route.params.id));
    },
    components: {
      ReservationForm
    },
    computed: {
      ...mapGetters({
        retrieveError: 'reservation/update/retrieveError',
        retrieveLoading: 'reservation/update/retrieveLoading',
        updateError: 'reservation/update/updateError',
        updateLoading: 'reservation/update/updateLoading',
        deleteError: 'reservation/del/error',
        deleteLoading: 'reservation/del/loading',
        created: 'reservation/create/created',
        deleted: 'reservation/del/deleted',
        retrieved: 'reservation/update/retrieved',
        updated: 'reservation/update/updated',
        violations: 'reservation/update/violations'
      })
    },
    data: function() {
      return {
        item: {}
      }
    },
    methods: {
      update (values) {
        this.$store.dispatch('reservation/update/update', {item: this.retrieved, values: values });
      },
      del () {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('reservation/del/delete', this.retrieved);
      },
      reset () {
        this.$store.dispatch('reservation/update/reset');
        this.$store.dispatch('reservation/del/reset');
        this.$store.dispatch('reservation/create/reset');

      }
    },
    watch: {
      deleted: function (deleted) {
        if (deleted) {
          this.$router.push({ name: 'ReservationList' });
        }
      }
    },
    beforeDestroy() {
      this.reset();
    }
  }
</script>
