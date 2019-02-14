<template>
  <div>
    <h1>Edit {{ item && item['@id'] }}</h1>

    <div v-if="created" class="alert alert-success" role="status">{{ created['@id'] }} created.</div>
    <div v-if="updated" class="alert alert-success" role="status">{{ updated['@id'] }} updated.</div>
    <div v-if="retrieveLoading || updateLoading || deleteLoading"class="alert alert-info" role="status">Loading...</div>
    <div v-if="retrieveError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ retrieveError }}</div>
    <div v-if="updateError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ updateError }}</div>
    <div v-if="deleteError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ deleteError }}</div>

    <ContactEmailForm v-if="item" :handle-submit="update" :values="item" :errors="violations" :initialValues="retrieved"></ContactEmailForm>
    <router-link v-if="item" :to="{ name: 'ContactEmailList' }" class="btn btn-default">Back to list</router-link>
    <button @click="del" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import ContactEmailForm from './Form.vue';
  import { mapGetters } from 'vuex';

  export default {
    created () {
      this.$store.dispatch('contactemail/update/retrieve', decodeURIComponent(this.$route.params.id));
    },
    components: {
      ContactEmailForm
    },
    computed: {
      ...mapGetters({
        retrieveError: 'contactemail/update/retrieveError',
        retrieveLoading: 'contactemail/update/retrieveLoading',
        updateError: 'contactemail/update/updateError',
        updateLoading: 'contactemail/update/updateLoading',
        deleteError: 'contactemail/del/error',
        deleteLoading: 'contactemail/del/loading',
        created: 'contactemail/create/created',
        deleted: 'contactemail/del/deleted',
        retrieved: 'contactemail/update/retrieved',
        updated: 'contactemail/update/updated',
        violations: 'contactemail/update/violations'
      })
    },
    data: function() {
      return {
        item: {}
      }
    },
    methods: {
      update (values) {
        this.$store.dispatch('contactemail/update/update', {item: this.retrieved, values: values });
      },
      del () {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('contactemail/del/delete', this.retrieved);
      },
      reset () {
        this.$store.dispatch('contactemail/update/reset');
        this.$store.dispatch('contactemail/del/reset');
        this.$store.dispatch('contactemail/create/reset');

      }
    },
    watch: {
      deleted: function (deleted) {
        if (deleted) {
          this.$router.push({ name: 'ContactEmailList' });
        }
      }
    },
    beforeDestroy() {
      this.reset();
    }
  }
</script>
