<template>
  <div>
    <h1>New Reservation</h1>

    <div v-if="loading" class="alert alert-info" role="status">Loading...</div>
    <div v-if="error" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ error }}</div>

    <ReservationForm :handle-submit="create" :values="item" :errors="violations"></ReservationForm>
    <router-link :to="{ name: 'ReservationList' }" class="btn btn-default">Back to list</router-link>
  </div>
</template>

<script>
  import ReservationForm from './Form.vue';
  import { createNamespacedHelpers } from 'vuex';

  const { mapActions, mapGetters } = createNamespacedHelpers('reservation/create');

  export default {
    components: {
      ReservationForm
    },
    data: function() {
      return {
        item: {}
      }
    },
    computed: mapGetters([
      'error',
      'loading',
      'created',
      'violations'
    ]),
    methods: {
      create: function(item) {
        this.$store.dispatch('reservation/create/create', item);
      }
    },
    watch: {
      created: function (created) {
        if (created) {
          this.$router.push({ name: 'ReservationUpdate', params: { id: created['@id']} });
        }
      }
    }
  }
</script>
