<template>
  <form @submit.prevent="handleSubmit(item)">
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.createAt) }">
      <label for="_createAt" class="control-label">createAt</label>
      <input v-model="item.createAt" type="text"  placeholder=""  id="_createAt" class="form-control" />
      <span v-if="errors && errors.createAt" class="help-block" id="_createAt_helpBlock">{{ errors.createAt }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.fromEmail) }">
      <label for="_fromEmail" class="control-label">fromEmail</label>
      <input v-model="item.fromEmail" type="text"  placeholder=""  id="_fromEmail" class="form-control" />
      <span v-if="errors && errors.fromEmail" class="help-block" id="_fromEmail_helpBlock">{{ errors.fromEmail }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.subject) }">
      <label for="_subject" class="control-label">subject</label>
      <input v-model="item.subject" type="text"  placeholder=""  id="_subject" class="form-control" />
      <span v-if="errors && errors.subject" class="help-block" id="_subject_helpBlock">{{ errors.subject }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.message) }">
      <label for="_message" class="control-label">message</label>
      <input v-model="item.message" type="text"  placeholder=""  id="_message" class="form-control" />
      <span v-if="errors && errors.message" class="help-block" id="_message_helpBlock">{{ errors.message }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.checked) }">
      <label for="_checked" class="control-label">checked</label>
      <input v-model="item.checked" type="checkbox"  placeholder=""  id="_checked" class="form-control" />
      <span v-if="errors && errors.checked" class="help-block" id="_checked_helpBlock">{{ errors.checked }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.contact) }">
      <label for="_contact" class="control-label">contact</label>
      <input v-model="item.contact" type="text"  placeholder=""  id="_contact" class="form-control" />
      <span v-if="errors && errors.contact" class="help-block" id="_contact_helpBlock">{{ errors.contact }}</span>
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
