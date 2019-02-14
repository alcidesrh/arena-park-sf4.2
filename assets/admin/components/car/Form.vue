<template>
  <form @submit.prevent="handleSubmit(item)">
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.mark) }">
      <label for="_mark" class="control-label">mark</label>
      <input v-model="item.mark" type="text"  placeholder=""  id="_mark" class="form-control" />
      <span v-if="errors && errors.mark" class="help-block" id="_mark_helpBlock">{{ errors.mark }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.plate) }">
      <label for="_plate" class="control-label">plate</label>
      <input v-model="item.plate" type="text"  placeholder=""  id="_plate" class="form-control" />
      <span v-if="errors && errors.plate" class="help-block" id="_plate_helpBlock">{{ errors.plate }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.color) }">
      <label for="_color" class="control-label">color</label>
      <input v-model="item.color" type="text"  placeholder=""  id="_color" class="form-control" />
      <span v-if="errors && errors.color" class="help-block" id="_color_helpBlock">{{ errors.color }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.user) }">
      <label for="_user" class="control-label">user</label>
      <input v-model="item.user" type="text"  placeholder=""  id="_user" class="form-control" />
      <span v-if="errors && errors.user" class="help-block" id="_user_helpBlock">{{ errors.user }}</span>
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
