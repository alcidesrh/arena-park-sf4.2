<template>
  <div>
    <h1>Contact List</h1>

    <div v-if="loading" class="alert alert-info">Loading...</div>
    <div v-if="deletedItem" class="alert alert-success">{{ deletedItem['@id'] }} deleted.</div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <span v-if="view">
      <button
        type="button"
        class="btn btn-basic btn-sm"
        @click="getPage(view['hydra:first'])"
        :disabled="!view['hydra:previous']"
      >First</button>
      &nbsp;
      <button
        type="button"
        class="btn btn-basic btn-sm"
        @click="getPage(view['hydra:previous'])"
        :disabled="!view['hydra:previous']"
      >Previous</button>
      &nbsp;
      <button
        type="button" class="btn btn-basic btn-sm"
        @click="getPage(view['hydra:next'])"
        :disabled="!view['hydra:next']"
      >Next</button>
      &nbsp;
      <button
        type="button" class="btn btn-basic btn-sm"
        @click="getPage(view['hydra:last'])"
        :disabled="view['hydra:last']"
      >Last</button>
      &nbsp;
    </span>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Id</th>
            <th>address</th>
            <th>phone</th>
            <th>movil</th>
            <th>phoneUrgence</th>
            <th>email</th>
            <th>registre</th>
            <th>emails</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items">
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['@id'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['address'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['phone'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['movil'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['phoneUrgence'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['email'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['registre'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'ContactShow', params: { id: item['@id'] }}">{{ item['emails'] }}</router-link></td>
            <td>
              <router-link :to="{name: 'ContactShow', params: { id: item['@id'] }}">
                <span class="glyphicon glyphicon-search" aria-hidden="true"/>
                <span class="sr-only">Show</span>
              </router-link>
            </td>
            <td>
              <router-link :to="{name: 'ContactUpdate', params: { id: item['@id'] }}">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"/>
                <span class="sr-only">Edit</span>
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <router-link :to="{ name: 'ContactCreate' }" class="btn btn-default">Create</router-link>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      deletedItem: 'contact/del/deleted',
      error: 'contact/list/error',
      items: 'contact/list/items',
      loading: 'contact/list/loading',
      view: 'contact/list/view'
    }),
    methods: mapActions({
      getPage: 'contact/list/getItems'
    }),
    created () {
      this.$store.dispatch('contact/list/getItems')
    }
  }
</script>
