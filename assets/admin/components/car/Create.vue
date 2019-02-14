<template>
  <div>
    <h1>New Car</h1>

    <div v-if="loading" class="alert alert-info" role="status">Loading...</div>
    <div v-if="error" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ error }}</div>

    <CarForm :handle-submit="create" :values="item" :errors="violations"></CarForm>
    <router-link :to="{ name: 'CarList' }" class="btn btn-default">Back to list</router-link>
  </div>
</template>

<script>
  import CarForm from './Form.vue';
  import { createNamespacedHelpers } from 'vuex';

  const { mapActions, mapGetters } = createNamespacedHelpers('car/create');

  export default {
    components: {
      CarForm
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
        this.$store.dispatch('car/create/create', item);
      }
    },
    watch: {
      created: function (created) {
        if (created) {
          this.$router.push({ name: 'CarUpdate', params: { id: created['@id']} });
        }
      }
    }
  }
</script>
