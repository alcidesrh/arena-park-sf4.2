<template>
  <form @submit.prevent="handleSubmit(item)">
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.name) }">
      <label for="_name" class="control-label">name</label>
      <input v-model="item.name" type="text"  placeholder=""  id="_name" class="form-control" />
      <span v-if="errors && errors.name" class="help-block" id="_name_helpBlock">{{ errors.name }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.email) }">
      <label for="_email" class="control-label">email</label>
      <input v-model="item.email" type="text"  placeholder=""  id="_email" class="form-control" />
      <span v-if="errors && errors.email" class="help-block" id="_email_helpBlock">{{ errors.email }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.phone) }">
      <label for="_phone" class="control-label">phone</label>
      <input v-model="item.phone" type="text"  placeholder=""  id="_phone" class="form-control" />
      <span v-if="errors && errors.phone" class="help-block" id="_phone_helpBlock">{{ errors.phone }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.reservations) }">
      <label for="_reservations" class="control-label">reservations</label>
      <input v-model="item.reservations" type="text"  placeholder=""  id="_reservations" class="form-control" />
      <span v-if="errors && errors.reservations" class="help-block" id="_reservations_helpBlock">{{ errors.reservations }}</span>
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
