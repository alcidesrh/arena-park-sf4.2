<template>
  <div>
    <v-layout>
      <h2 class="d-inline">Editar Página</h2><v-progress-linear style="max-width: 200px; margin-left: 15px" v-if="!retrieved || updateLoading" :indeterminate="true"></v-progress-linear>
    </v-layout>
    <PageForm v-if="retrieved" :handle-submit="update" :values="item" :errors="violations" :initialValues="retrieved" ></PageForm>
  </div>
</template>

<script>
  import PageForm from './Form.vue';
  import { mapGetters } from 'vuex';

  export default {
    created () {
      this.$store.dispatch('page/update/retrieve', decodeURIComponent('/pages/'+this.$route.params.id));
    },
    components: {
      PageForm
    },
    computed: {
      ...mapGetters({
        retrieveError: 'page/update/retrieveError',
        retrieveLoading: 'page/update/retrieveLoading',
        updateError: 'page/update/updateError',
        updateLoading: 'page/update/updateLoading',
        retrieved: 'page/update/retrieved',
        updated: 'page/update/updated',
        violations: 'page/update/violations'
      })
    },
    data: function() {
      return {
        item: {}
      }
    },
    methods: {
      update (values) {
        this.$store.dispatch('page/update/update', {item: this.retrieved, values: values });
      },
      reset () {
        this.$store.dispatch('page/update/reset');
        this.$store.dispatch('page/del/reset');
        this.$store.dispatch('page/create/reset');

      }
    },
    watch: {
        updated(){
            this.$router.push({ name: 'PageList'});
            this.$store.dispatch('page/list/getItems');
        },
        retrieveError(val){
            alert('Error: '+val)
        },
        updateError(val){
            alert('Error: '+val)
        }
    },
    beforeDestroy() {
      this.reset();
    }
  }
</script>
