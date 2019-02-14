<template>
  <div>
    <h1>Edit {{ item && item['@id'] }}</h1>

    <div v-if="created" class="alert alert-success" role="status">{{ created['@id'] }} created.</div>
    <div v-if="updated" class="alert alert-success" role="status">{{ updated['@id'] }} updated.</div>
    <div v-if="retrieveLoading || updateLoading || deleteLoading"class="alert alert-info" role="status">Loading...</div>
    <div v-if="retrieveError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ retrieveError }}</div>
    <div v-if="updateError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ updateError }}</div>
    <div v-if="deleteError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ deleteError }}</div>

    <ContractForm v-if="item" :handle-submit="update" :values="item" :errors="violations" :initialValues="retrieved"></ContractForm>
    <router-link v-if="item" :to="{ name: 'ContractList' }" class="btn btn-default">Back to list</router-link>
    <button @click="del" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import ContractForm from './Form.vue';
  import { mapGetters } from 'vuex';

  export default {
    created () {
      this.$store.dispatch('contract/update/retrieve', decodeURIComponent(this.$route.params.id));
    },
    components: {
      ContractForm
    },
    computed: {
      ...mapGetters({
        retrieveError: 'contract/update/retrieveError',
        retrieveLoading: 'contract/update/retrieveLoading',
        updateError: 'contract/update/updateError',
        updateLoading: 'contract/update/updateLoading',
        deleteError: 'contract/del/error',
        deleteLoading: 'contract/del/loading',
        created: 'contract/create/created',
        deleted: 'contract/del/deleted',
        retrieved: 'contract/update/retrieved',
        updated: 'contract/update/updated',
        violations: 'contract/update/violations'
      })
    },
    data: function() {
      return {
        item: {}
      }
    },
    methods: {
      update (values) {
        this.$store.dispatch('contract/update/update', {item: this.retrieved, values: values });
      },
      del () {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('contract/del/delete', this.retrieved);
      },
      reset () {
        this.$store.dispatch('contract/update/reset');
        this.$store.dispatch('contract/del/reset');
        this.$store.dispatch('contract/create/reset');

      }
    },
    watch: {
      deleted: function (deleted) {
        if (deleted) {
          this.$router.push({ name: 'ContractList' });
        }
      }
    },
    beforeDestroy() {
      this.reset();
    }
  }
</script>
