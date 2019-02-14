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
            <td>user</td>
            <td>{{ item['user'] }}</td>
          </tr>
          <tr>
            <td>contract</td>
            <td>{{ item['contract'] }}</td>
          </tr>
          <tr>
            <td>car</td>
            <td>{{ item['car'] }}</td>
          </tr>
          <tr>
            <td>dateCarIn</td>
            <td>{{ item['dateCarIn'] }}</td>
          </tr>
          <tr>
            <td>dateCarOut</td>
            <td>{{ item['dateCarOut'] }}</td>
          </tr>
          <tr>
            <td>fly</td>
            <td>{{ item['fly'] }}</td>
          </tr>
          <tr>
            <td>airport</td>
            <td>{{ item['airport'] }}</td>
          </tr>
          <tr>
            <td>services</td>
            <td>{{ item['services'] }}</td>
          </tr>
          <tr>
            <td>paymentType</td>
            <td>{{ item['paymentType'] }}</td>
          </tr>
          <tr>
            <td>payment</td>
            <td>{{ item['payment'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <router-link v-if="item" :to="{ name: 'ReservationList' }" class="btn btn-default">Back to list</router-link>
    <button @click="deleteItem(item)" class="btn btn-danger">Delete</button>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      deleteError: 'reservation/del/error',
      error: 'reservation/show/error',
      loading: 'reservation/show/loading',
      item: 'reservation/show/item',
    }),
    methods: {
      deleteItem (item) {
        if (window.confirm('Are you sure you want to delete this item?'))
          this.$store.dispatch('reservation/del/delete', item).then(response => this.$router.push({ name: 'ReservationList' }));
      }
    },
    created () {
      this.$store.dispatch('reservation/show/retrieve', decodeURIComponent(this.$route.params.id));
    },
    beforeDestroy() {
      this.$store.dispatch('reservation/show/reset');
    }
  }
</script>
