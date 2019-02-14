<template>
  <div>
    <h1>New ContactEmail</h1>

    <div v-if="loading" class="alert alert-info" role="status">Loading...</div>
    <div v-if="error" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ error }}</div>

    <ContactEmailForm :handle-submit="create" :values="item" :errors="violations"></ContactEmailForm>
    <router-link :to="{ name: 'ContactEmailList' }" class="btn btn-default">Back to list</router-link>
  </div>
</template>

<script>
  import ContactEmailForm from './Form.vue';
  import { createNamespacedHelpers } from 'vuex';

  const { mapActions, mapGetters } = createNamespacedHelpers('contactemail/create');

  export default {
    components: {
      ContactEmailForm
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
        this.$store.dispatch('contactemail/create/create', item);
      }
    },
    watch: {
      created: function (created) {
        if (created) {
          this.$router.push({ name: 'ContactEmailUpdate', params: { id: created['@id']} });
        }
      }
    }
  }
</script>
