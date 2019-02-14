<template>
  <div>
    <h1>New Airport</h1>

    <div v-if="loading" class="alert alert-info" role="status">Loading...</div>
    <div v-if="error" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ error }}</div>

    <AirportForm :handle-submit="create" :values="item" :errors="violations"></AirportForm>
    <router-link :to="{ name: 'AirportList' }" class="btn btn-default">Back to list</router-link>
  </div>
</template>

<script>
  import AirportForm from './Form.vue';
  import { createNamespacedHelpers } from 'vuex';

  const { mapActions, mapGetters } = createNamespacedHelpers('airport/create');

  export default {
    components: {
      AirportForm
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
        this.$store.dispatch('airport/create/create', item);
      }
    },
    watch: {
      created: function (created) {
        if (created) {
          this.$router.push({ name: 'AirportUpdate', params: { id: created['@id']} });
        }
      }
    }
  }
</script>
