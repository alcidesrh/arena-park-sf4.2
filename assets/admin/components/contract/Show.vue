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
            <td>path</td>
            <td>{{ item['path'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <router-link v-if="item" :to="{ name: 'ContractList' }" class="btn btn-default">Back to list</router-link>
    <button @click="deleteItem(item)" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      deleteError: 'contract/del/error',
      error: 'contract/show/error',
      loading: 'contract/show/loading',
      item: 'contract/show/item',
    }),
    methods: {
      deleteItem (item) {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('contract/del/delete', item).then(response => this.$router.push({ name: 'ContractList' }));
      }
    },
    created () {
      this.$store.dispatch('contract/show/retrieve', decodeURIComponent(this.$route.params.id));
    },
    beforeDestroy() {
      this.$store.dispatch('contract/show/reset');
    }
  }
</script>
