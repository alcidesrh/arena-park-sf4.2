<template>
  <div>
    <h1>Show {{ item && item['@id'] }}</h1>

    <div v-if="loading" class="alert alert-info" role="status">Loading...</div>
    <div v-if="error" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ error }}</div>
    <div v-if="deleteError" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ deleteError }}</div>

    <div v-if="item" class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Field</th>
            <th>Value</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>createAt</td>
            <td>{{ item['createAt'] }}</td>
          </tr>
          <tr>
            <td>fromEmail</td>
            <td>{{ item['fromEmail'] }}</td>
          </tr>
          <tr>
            <td>subject</td>
            <td>{{ item['subject'] }}</td>
          </tr>
          <tr>
            <td>message</td>
            <td>{{ item['message'] }}</td>
          </tr>
          <tr>
            <td>checked</td>
            <td>{{ item['checked'] }}</td>
          </tr>
          <tr>
            <td>contact</td>
            <td>{{ item['contact'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <router-link v-if="item" :to="{ name: 'ContactEmailList' }" class="btn btn-default">Back to list</router-link>
    <button @click="deleteItem(item)" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      deleteError: 'contactemail/del/error',
      error: 'contactemail/show/error',
      loading: 'contactemail/show/loading',
      item: 'contactemail/show/item',
    }),
    methods: {
      deleteItem (item) {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('contactemail/del/delete', item).then(response => this.$router.push({ name: 'ContactEmailList' }));
      }
    },
    created () {
      this.$store.dispatch('contactemail/show/retrieve', decodeURIComponent(this.$route.params.id));
    },
    beforeDestroy() {
      this.$store.dispatch('contactemail/show/reset');
    }
  }
</script>
