<template>
  <div>
    <h1>Car List</h1>

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
            <th>mark</th>
            <th>plate</th>
            <th>color</th>
            <th>user</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items">
            <td><router-link v-if="item" :to="{name: 'CarShow', params: { id: item['@id'] }}">{{ item['@id'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'CarShow', params: { id: item['@id'] }}">{{ item['mark'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'CarShow', params: { id: item['@id'] }}">{{ item['plate'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'CarShow', params: { id: item['@id'] }}">{{ item['color'] }}</router-link></td>
            <td><router-link v-if="item" :to="{name: 'CarShow', params: { id: item['@id'] }}">{{ item['user'] }}</router-link></td>
            <td>
              <router-link :to="{name: 'CarShow', params: { id: item['@id'] }}">
                <span class="glyphicon glyphicon-search" aria-hidden="true"/>
                <span class="sr-only">Show</span>
              </router-link>
            </td>
            <td>
              <router-link :to="{name: 'CarUpdate', params: { id: item['@id'] }}">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"/>
                <span class="sr-only">Edit</span>
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <router-link :to="{ name: 'CarCreate' }" class="btn btn-default">Create</router-link>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      deletedItem: 'car/del/deleted',
      error: 'car/list/error',
      items: 'car/list/items',
      loading: 'car/list/loading',
      view: 'car/list/view'
    }),
    methods: mapActions({
      getPage: 'car/list/getItems'
    }),
    created () {
      this.$store.dispatch('car/list/getItems')
    }
  }
</script>
