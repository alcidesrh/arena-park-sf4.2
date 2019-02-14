<template>
  <div>
    <h1>Edit {{ item && item['@id'] }}</h1>

    <div v-if="created" class="alert alert-success" role="status">{{ created['@id'] }} created.</div>
    <div v-if="updated" class="alert alert-success" role="status">{{ updated['@id'] }} updated.</div>
    <div v-if="retrieveLoading || updateLoading || deleteLoading"class="alert alert-info" role="status">Loading...</div>
    <div v-if="retrieveError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ retrieveError }}</div>
    <div v-if="updateError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ updateError }}</div>
    <div v-if="deleteError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ deleteError }}</div>

    <CarForm v-if="item" :handle-submit="update" :values="item" :errors="violations" :initialValues="retrieved"></CarForm>
    <router-link v-if="item" :to="{ name: 'CarList' }" class="btn btn-default">Back to list</router-link>
    <button @click="del" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import CarForm from './Form.vue';
  import { mapGetters } from 'vuex';

  export default {
    created () {
      this.$store.dispatch('car/update/retrieve', decodeURIComponent(this.$route.params.id));
    },
    components: {
      CarForm
    },
    computed: {
      ...mapGetters({
        retrieveError: 'car/update/retrieveError',
        retrieveLoading: 'car/update/retrieveLoading',
        updateError: 'car/update/updateError',
        updateLoading: 'car/update/updateLoading',
        deleteError: 'car/del/error',
        deleteLoading: 'car/del/loading',
        created: 'car/create/created',
        deleted: 'car/del/deleted',
        retrieved: 'car/update/retrieved',
        updated: 'car/update/updated',
        violations: 'car/update/violations'
      })
    },
    data: function() {
      return {
        item: {}
      }
    },
    methods: {
      update (values) {
        this.$store.dispatch('car/update/update', {item: this.retrieved, values: values });
      },
      del () {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('car/del/delete', this.retrieved);
      },
      reset () {
        this.$store.dispatch('car/update/reset');
        this.$store.dispatch('car/del/reset');
        this.$store.dispatch('car/create/reset');

      }
    },
    watch: {
      deleted: function (deleted) {
        if (deleted) {
          this.$router.push({ name: 'CarList' });
        }
      }
    },
    beforeDestroy() {
      this.reset();
    }
  }
</script>
