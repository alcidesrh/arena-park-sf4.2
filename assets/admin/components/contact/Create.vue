<template>
  <div>
    <h1>New Contact</h1>

    <div v-if="loading" class="alert alert-info" role="status">Loading...</div>
    <div v-if="error" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ error }}</div>

    <ContactForm :handle-submit="create" :values="item" :errors="violations"></ContactForm>
    <router-link :to="{ name: 'ContactList' }" class="btn btn-default">Back to list</router-link>
  </div>
</template>

<script>
  import ContactForm from './Form.vue';
  import { createNamespacedHelpers } from 'vuex';

  const { mapActions, mapGetters } = createNamespacedHelpers('contact/create');

  export default {
    components: {
      ContactForm
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
        this.$store.dispatch('contact/create/create', item);
      }
    },
    watch: {
      created: function (created) {
        if (created) {
          this.$router.push({ name: 'ContactUpdate', params: { id: created['@id']} });
        }
      }
    }
  }
</script>
