<template>
  <form @submit.prevent="handleSubmit(item)">
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.path) }">
      <label for="_path" class="control-label">path</label>
      <input v-model="item.path" type="text"  placeholder=""  id="_path" class="form-control" />
      <span v-if="errors && errors.path" class="help-block" id="_path_helpBlock">{{ errors.path }}</span>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script>
  export default {
    props: {
      handleSubmit: {
        type: Function,
        required: true,
      },
      values: {
        type: Object,
        required: true
      },
      errors: {
        type: Object
      },
      initialValues: {
        type: Object
      }
    },
    computed: {
      item: function () {
        return this.initialValues ? this.initialValues : this.values;
      }
    }
  }
</script>
