<template>
  <div>
    <h1>New Contract</h1>

    <div v-if="loading" class="alert alert-info" role="status">Loading...</div>
    <div v-if="error" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ error }}</div>

    <ContractForm :handle-submit="create" :values="item" :errors="violations"></ContractForm>
    <router-link :to="{ name: 'ContractList' }" class="btn btn-default">Back to list</router-link>
  </div>
</template>

<script>
  import ContractForm from './Form.vue';
  import { createNamespacedHelpers } from 'vuex';

  const { mapActions, mapGetters } = createNamespacedHelpers('contract/create');

  export default {
    components: {
      ContractForm
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
        this.$store.dispatch('contract/create/create', item);
      }
    },
    watch: {
      created: function (created) {
        if (created) {
          this.$router.push({ name: 'ContractUpdate', params: { id: created['@id']} });
        }
      }
    }
  }
</script>
