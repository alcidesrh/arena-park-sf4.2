<template>
  <div>
    <v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step
          :complete="e1 > 1"
          step="1"
          @click="setpOneClick"
          style="cursor: pointer"
        >Seleccionar usuarios</v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step
          :complete="e1 > 2"
          step="2"
          @click="setpTwoClick"
          style="cursor: pointer"
        >Mensaje</v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card>
            <v-card-title>
              <v-checkbox v-model="all" label="Todos" primary hide-details></v-checkbox>
              <v-progress-linear
                style="max-width: 200px; margin-left: 15px"
                v-if="loading || deleteLoading || updateLoading || updateCarLoading || deleteLoading"
                :indeterminate="true"
              ></v-progress-linear>
              <v-spacer></v-spacer>
              <v-spacer></v-spacer>
              <v-menu
                v-model="menuSearchUserPhone"
                bottom
                offset-y
                :open-on-click="false"
                style="max-width: 350px"
              >
                <v-text-field
                  slot="activator"
                  label="Buscar por teléfono"
                  prepend-icon="phone"
                  v-model="user.phone"
                  v-on:keyup="searchUserPhone"
                ></v-text-field>
                <v-list>
                  <v-list-tile v-for="(item, index) in users" :key="index" @click="setUser(item)">
                    <v-list-tile-title>{{item.phone}}: {{item.name}}, {{item.email}}</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>
              <v-menu
                v-model="menuSearchUserName"
                bottom
                offset-y
                :open-on-click="false"
                style="max-width: 350px"
              >
                <v-text-field
                  slot="activator"
                  label="Buscar por nombre"
                  prepend-icon="person"
                  v-model="user.name"
                  v-on:keyup="searchUserName"
                ></v-text-field>
                <v-list>
                  <v-list-tile v-for="(item, index) in users" :key="index" @click="setUser(item)">
                    <v-list-tile-title>{{item.name}}: {{item.email}}, {{item.phone}}</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>
              <v-menu
                v-model="menuSearchUser"
                bottom
                offset-y
                :open-on-click="false"
                style="max-width: 350px"
              >
                <v-text-field
                  slot="activator"
                  label="Buscar por email"
                  prepend-icon="email"
                  v-model="user.email"
                  v-on:keyup="searchUser"
                ></v-text-field>
                <v-list>
                  <v-list-tile v-for="(item, index) in users" :key="index" @click="setUser(item)">
                    <v-list-tile-title>{{item.email}}: {{item.name}}, {{item.phone}}</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>
              <v-icon class="ml-0 mr-2" @click="resetSearch">refresh</v-icon>
            </v-card-title>
            <v-data-table
              item-key="email"
              :headers="headers"
              :items="items"
              :pagination.sync="pagination"
              :total-items="totalItems"
              :rows-per-page-items="[20,50,100,{'text':'$vuetify.dataIterator.rowsPerPageAll','value':-1}]"
            >
              <template slot="items" slot-scope="props">
                <td>
                  <v-checkbox
                    v-model="props.item.selected"
                    :input-value="props.selected"
                    primary
                    hide-details
                  ></v-checkbox>
                </td>
                <td>{{ props.item.sex?' Sr ':' Sra ' }}{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
                <td>{{ props.item.phone }}</td>
                <td v-if="props.item.car">
                  <p class="mt-2 mb-0">Marca: {{ props.item.car.mark}}</p>
                  <p class="mb-0">Placa: {{ props.item.car.plate }}</p>
                  <p class="mb-0">Color: {{ props.item.car.color }}</p>
                </td>
                <td v-else>
                  <p class="mt-2 mb-0">------</p>
                </td>
                <td class="px-0 text-xs-center">{{ props.item.reservations.length }}</td>
                <td class="px-0">
                  <v-btn small icon class="mx-0 px-0" @click="userReservations(props.item)">
                    <v-icon color="orange">visibility</v-icon>
                  </v-btn>
                  <v-btn small icon class="mx-0 px-0" @click="edit(props.item)">
                    <v-icon color="teal">edit</v-icon>
                  </v-btn>
                  <v-btn small icon class="mx-0 px-0" @click="del(props.item)">
                    <v-icon color="red">delete</v-icon>
                  </v-btn>
                </td>
              </template>
            </v-data-table>
          </v-card>

          <v-btn color="primary" @click="setpTwoClick">Siguiente</v-btn>
        </v-stepper-content>

        <v-stepper-content step="2">
          <v-layout align-center wrap class="mb-5">
            <v-flex xs12 v-if="all">Para todos</v-flex>
            <v-flex xs12 v-else>
              Para:
              <v-chip v-for="i,index in selectedUsers" :key="index">{{i.email}}</v-chip>
              <v-progress-linear
                style="max-width: 200px; margin-left: 15px"
                v-if="loading"
                :indeterminate="true"
              ></v-progress-linear>
            </v-flex>
          </v-layout>
          <v-layout align-center wrap class="mb-5">
            <v-flex xs12>
              <v-text-field v-model="subject" label="Asunto" single-line outline></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout align-center wrap class="mb-5">
            <v-flex xs12>
              <v-textarea outline name="input-7-4" label="Mensaje" v-model="message"></v-textarea>
            </v-flex>
          </v-layout>

          <v-btn color="primary" @click="e1 = 1">Atras</v-btn>

          <v-btn color="primary" @click="sendEmail">Enviar</v-btn>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>

    <v-dialog v-if="item" v-model="dialog" width="800">
      <v-card>
        <v-card-title class="headline" primary-title>
          Editar usuario
          <v-spacer></v-spacer>
          <v-btn icon class="mx-0" @click="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-layout row wrap>
              <v-flex class="pr-3">
                <h4>Datos personales</h4>
                <v-divider class="mb-2"></v-divider>
                <v-text-field v-model="item.name" label="Nombre" :rules="requireRules" required></v-text-field>
                <v-text-field v-model="item.email" label="Email" :rules="emailRules" required></v-text-field>
                <v-text-field v-model="item.phone" label="Movil" :rules="requireRules" required></v-text-field>
              </v-flex>
              <v-flex class="pr-3" v-if="item.car">
                <h4>Carro</h4>
                <v-divider class="mb-2"></v-divider>
                <v-text-field v-model="item.car.mark" label="Marca" :rules="requireRules" required></v-text-field>
                <v-text-field v-model="item.car.plate" label="Placa" :rules="requireRules" required></v-text-field>
                <v-text-field v-model="item.car.color" label="Color" :rules="requireRules" required></v-text-field>
              </v-flex>
            </v-layout>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" flat @click="dialog = false">Cerrar</v-btn>
          <v-btn color="primary" flat @click="save">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-snackbar v-model="snackbar" color="success" top>
      Se han enviado los correos.
      <v-btn flat @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import fetch from "../../utils/fetch";

export default {
  data: () => ({
    snackbar: false,
    subject: null,
    message: null,
    exclude: [],
    all: false,
    e1: 0,
    selected: [],
    user: {},
    users: [],
    menuSearchUser: false,
    menuSearchUserName: false,
    menuSearchUserPhone: false,
    searchUserLoading: false,
    emailLength: 0,
    valid: false,
    item: false,
    dialog: false,
    pagination: {},
    headers: [
      { text: "", sortable: false, align: "left" },
      { text: "Nombre", sortable: false, align: "left" },
      { text: "Email", sortable: false, align: "left" },
      { text: "Movil", sortable: false, align: "left" },
      { text: "Carro", sortable: false, align: "left" },
      { text: "# Res", sortable: false, align: "left" },
      { text: "", sortable: false }
    ],
    requireRules: [v => !!v || "Este campo es obligatorio"],
    emailRules: [
      v => !!v || "E-mail es obligatorio",
      v => /.+@.+/.test(v) || "E-mail no es válido"
    ]
  }),
  computed: mapGetters({
    deletedItem: "user/del/deleted",
    error: "user/list/error",
    items: "user/list/items",
    selectedUsers: "user/list/all",
    loading: "user/list/loading",
    view: "user/list/view",
    totalItems: "user/list/totalItems",
    updateLoading: "user/update/updateLoading",
    updateCarLoading: "car/update/updateLoading",
    deleteError: "user/del/error",
    deleteLoading: "user/del/loading"
  }),
  methods: {
    sendEmail() {
      if (!this.message || !this.subject) return;
      let param = {};
      if (this.all) {
        param.all = true;
        param.ids = this.exclude;
      } else param.ids = this.selected;
      param.message = this.message;
      param.subject = this.subject;
      this.$store.dispatch("user/list/sendEmail", param).then(() => {
        this.snackbar = true;
      });
    },
    setpOneClick() {
      if (this.selected.length == 0) return;
      this.e1 = 1;
    },
    setpTwoClick() {
      if (this.all) {
        this.items
          .filter(i => !i.selected)
          .map(i => i.id)
          .forEach(id => {
            if (!this.exclude.includes(i)) this.exclude.push(i);
          });
      } else
        this.items
          .filter(i => i.selected)
          .map(i => i.id)
          .forEach(id => {
            if (!this.selected.includes(id)) this.selected.push(id);
          });

      if (this.selected.length == 0 && !this.all) return;
      this.e1 = 2;
      if (this.selected) {
        this.$store
          .dispatch("user/list/getUsersByIds", this.selected)
          .then(() => console.log(this.selectedUsers));
      }
    },
    resetSearch() {
      this.user = {};
      this.$store.dispatch("user/list/setPage", 1);
      this.pagination.page = 1;
      this.$store.dispatch("user/list/getItems");
    },
    userReservations(item) {
      this.$router.push({ name: "ReservationList", params: { user: item } });
      document.getElementById("reservations").click();
    },
    setUser(user) {
      this.user = user;
      this.emailLength = this.user.email.length;
      this.resetPagination();
      this.$store.dispatch("user/list/getItems", "email=" + this.user.email);
    },
    searchUser() {
      if (!this.searchUserLoading && this.user.email.length >= 4) {
        this.searchUserLoading = true;
        fetch("/users?email=" + this.user.email)
          .then(response => response.json())
          .then(data => {
            this.searchUserLoading = false;
            if (
              data["hydra:member"].length &&
              typeof this.user.id == typeof undefined
            ) {
              this.users = data["hydra:member"];
              this.menuSearchUser = true;
            }
          })
          .catch(e => {
            this.searchUserLoading = false;
            console.log(e);
          });
      }
      this.menuSearchUser = false;
      this.emailLength = this.user.email.length;
    },
    searchUserName() {
      if (!this.searchUserLoading && this.user.name.length >= 4) {
        this.searchUserLoading = true;
        fetch("/users?name=" + this.user.name)
          .then(response => response.json())
          .then(data => {
            this.searchUserLoading = false;
            if (
              data["hydra:member"].length &&
              typeof this.user.id == typeof undefined
            ) {
              this.users = data["hydra:member"];
              this.menuSearchUserName = true;
            }
          })
          .catch(e => {
            this.searchUserLoading = false;
            console.log(e);
          });
      }
      this.menuSearchUserName = false;
      this.emailLength = this.user.name.length;
    },
    searchUserPhone() {
      if (!this.searchUserLoading && this.user.phone.length >= 4) {
        this.searchUserLoading = true;
        fetch("/users?phone=" + this.user.phone)
          .then(response => response.json())
          .then(data => {
            this.searchUserLoading = false;
            if (
              data["hydra:member"].length &&
              typeof this.user.id == typeof undefined
            ) {
              this.users = data["hydra:member"];
              this.menuSearchUserPhone = true;
            }
          })
          .catch(e => {
            this.searchUserLoading = false;
            console.log(e);
          });
      }
      // else if (this.emailLength > this.user.phone.length && typeof this.user.id != typeof undefined) {
      //     this.user = {phone: this.user.phone};
      //     this.car = {};
      // }
      this.menuSearchUserPhone = false;
      this.emailLength = this.user.phone.length;
    },
    resetPagination() {
      this.$store.dispatch("user/list/setPage", 1);
      this.pagination.page = 1;
    },
    edit(item) {
      this.item = item;
      this.dialog = true;
    },
    save() {
      if (this.$refs.form.validate()) {
        this.dialog = false;
        this.$store.dispatch("user/update/update", {
          item: this.item,
          values: {
            name: this.item.name,
            email: this.item.email,
            phone: this.item.phone
          }
        });
        if (this.item.car)
          this.$store.dispatch("car/update/update", {
            item: this.item.car,
            values: {
              mark: this.item.car.mark,
              plate: this.item.car.plate,
              color: this.item.car.color
            }
          });
      }
    },
    del(item) {
      if (window.confirm("Seguro quiere eliminar este usuario")) {
        this.$vuetify.goTo(0, 3000);
        this.$store.dispatch("user/del/delete", item).then(() => {
          this.$store.dispatch("user/list/getItems");
          this.user = {};
        });
      }
    }
  },

  watch: {
    all(val) {
      this.selected = [];
      this.items.forEach(element => {
        element.selected = val;
      });
      if (!val) {
        this.exclude = [];
      }
    },
    pagination: {
      handler() {
        if (this.all) {
          this.items
            .filter(i => !i.selected)
            .forEach(i => {
              if (!this.exclude.includes(i.id)) this.exclude.push(i.id);
            });
        } else
          this.items
            .filter(i => i.selected)
            .forEach(i => {
              if (!this.selected.includes(i.id)) this.selected.push(i.id);
            });

        this.$vuetify.goTo(0, 3000);
        if (this.pagination.rowsPerPage != -1)
          this.$store.dispatch(
            "user/list/setItemsPerPage",
            this.pagination.rowsPerPage
          );
        else this.$store.dispatch("user/list/setItemsPerPage", 1000000);
        this.$store.dispatch("user/list/setPage", this.pagination.page);
        this.$store.dispatch("user/list/getItems").then(() => {
          this.items.forEach(i => {
            if (this.selected.includes(i.id) || this.all) {
              if (this.all && this.exclude.length) {
                if (!this.exclude.includes(i.id)) i.selected = true;
              } else i.selected = true;
            }
          });
        });
      },
      deep: true
    },
    deleteError(val) {
      alert("Error: " + val);
    }
  }
};
</script>
