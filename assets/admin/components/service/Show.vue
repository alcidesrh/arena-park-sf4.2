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
            <td>name</td>
            <td>{{ item['name'] }}</td>
          </tr>
          <tr>
            <td>description</td>
            <td>{{ item['description'] }}</td>
          </tr>
          <tr>
            <td>prices</td>
            <td>{{ item['prices'] }}</td>
          </tr>
          <tr>
            <td>active</td>
            <td>{{ item['active'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <router-link v-if="item" :to="{ name: 'ServiceList' }" class="btn btn-default">Back to list</router-link>
    <button @click="deleteItem(item)" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      deleteError: 'service/del/error',
      error: 'service/show/error',
      loading: 'service/show/loading',
      item: 'service/show/item',
    }),
    methods: {
      deleteItem (item) {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('service/del/delete', item).then(response => this.$router.push({ name: 'ServiceList' }));
      }
    },
    created () {
      this.$store.dispatch('service/show/retrieve', decodeURIComponent(this.$route.params.id));
    },
    beforeDestroy() {
      this.$store.dispatch('service/show/reset');
    }
  }
</script>
