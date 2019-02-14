<template>
  <form @submit.prevent="handleSubmit(item)">
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.createAt) }">
      <label for="_createAt" class="control-label">createAt</label>
      <input v-model="item.createAt" type="text"  placeholder=""  id="_createAt" class="form-control" />
      <span v-if="errors && errors.createAt" class="help-block" id="_createAt_helpBlock">{{ errors.createAt }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.user) }">
      <label for="_user" class="control-label">user</label>
      <input v-model="item.user" type="text"  placeholder=""  id="_user" class="form-control" />
      <span v-if="errors && errors.user" class="help-block" id="_user_helpBlock">{{ errors.user }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.contract) }">
      <label for="_contract" class="control-label">contract</label>
      <input v-model="item.contract" type="text"  placeholder=""  id="_contract" class="form-control" />
      <span v-if="errors && errors.contract" class="help-block" id="_contract_helpBlock">{{ errors.contract }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.car) }">
      <label for="_car" class="control-label">car</label>
      <input v-model="item.car" type="text"  placeholder=""  id="_car" class="form-control" />
      <span v-if="errors && errors.car" class="help-block" id="_car_helpBlock">{{ errors.car }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.dateCarIn) }">
      <label for="_dateCarIn" class="control-label">dateCarIn</label>
      <input v-model="item.dateCarIn" type="text"  placeholder=""  id="_dateCarIn" class="form-control" />
      <span v-if="errors && errors.dateCarIn" class="help-block" id="_dateCarIn_helpBlock">{{ errors.dateCarIn }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.dateCarOut) }">
      <label for="_dateCarOut" class="control-label">dateCarOut</label>
      <input v-model="item.dateCarOut" type="text"  placeholder=""  id="_dateCarOut" class="form-control" />
      <span v-if="errors && errors.dateCarOut" class="help-block" id="_dateCarOut_helpBlock">{{ errors.dateCarOut }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.fly) }">
      <label for="_fly" class="control-label">fly</label>
      <input v-model="item.fly" type="text"  placeholder=""  id="_fly" class="form-control" />
      <span v-if="errors && errors.fly" class="help-block" id="_fly_helpBlock">{{ errors.fly }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.airport) }">
      <label for="_airport" class="control-label">airport</label>
      <input v-model="item.airport" type="text"  placeholder=""  id="_airport" class="form-control" />
      <span v-if="errors && errors.airport" class="help-block" id="_airport_helpBlock">{{ errors.airport }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.services) }">
      <label for="_services" class="control-label">services</label>
      <input v-model="item.services" type="text"  placeholder="Many User have Many Phonenumbers."  id="_services" class="form-control" />
      <span v-if="errors && errors.services" class="help-block" id="_services_helpBlock">{{ errors.services }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.paymentType) }">
      <label for="_paymentType" class="control-label">paymentType</label>
      <input v-model="item.paymentType" type="text"  placeholder=""  id="_paymentType" class="form-control" />
      <span v-if="errors && errors.paymentType" class="help-block" id="_paymentType_helpBlock">{{ errors.paymentType }}</span>
    </div>
    <div :class="{ 'form-group': true, 'has-error': (errors && errors.payment) }">
      <label for="_payment" class="control-label">payment</label>
      <input v-model="item.payment" type="number"  step="0.1" placeholder=""  id="_payment" class="form-control" />
      <span v-if="errors && errors.payment" class="help-block" id="_payment_helpBlock">{{ errors.payment }}</span>
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
