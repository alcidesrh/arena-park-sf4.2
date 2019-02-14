<template>
    <div>
        <v-form>
            <v-layout wrap>
                <v-flex xs6 md3>
                    <v-text-field
                            v-model="item.name"
                            :rules="fieldRule"
                            label="Nombre de la página"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex xs6 md2 ml-2>
                    <v-select
                            v-model="item.slug"
                            :items="items"
                            label="Tipo de página"
                    ></v-select>
                </v-flex>
                <v-flex xs12 md6>
                    <v-btn small color="teal" @click="dialog = true">Agregar bloque de texto</v-btn>
                    <v-btn small color="teal" @click="handleSubmit(item)">Guardar página</v-btn>
                </v-flex>
            </v-layout>
        </v-form>
        <v-layout row wrap>
            <v-flex xs12>
                <v-expansion-panel popout>
                    <v-expansion-panel-content
                            v-for="item,index in item.sections"
                            :key="index"
                    >
                        <div slot="header">{{item.title?item.title:'Sin título'}}
                            <v-icon color="teal" class="ml-2" @click.stop="editSection(item)">edit</v-icon>
                            <v-icon color="red" class="ml-2" @click.stop="deleteSection(item)">delete</v-icon></div>
                        <v-card>
                            <v-card-text v-html="item.content"></v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-flex>
        </v-layout>

        <v-dialog
                v-model="dialog"
                width="800"
        >

            <v-card>
                <v-card-title
                        class="headline grey lighten-2"
                >
                    Nuevo bloque de texto
                </v-card-title>

                <v-card-text>
                    <v-layout wrap>
                        <v-flex xs8 md5>
                            <v-text-field
                                    v-model="sectionTitle"
                                    label="Nombre de la sección(no obligatorio)"
                                    required
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <ckeditor :editor="editor" v-model="sectionContent"></ckeditor>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            color="primary"
                            flat
                            @click="dialog = false"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                            color="primary"
                            flat
                            @click="saveSection"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>

</template>

<script>
    import Editor from '@ckeditor/ckeditor5-build-classic/build/ckeditor';

    export default {
        data: () => ({
            items: [{text: 'Inicio', value: 'home'}, {text: 'Servicios', value: 'services'}, {text: 'Tarifas', value: 'tarif'}, {text: 'F.A.Q', value: 'faq'}, {text: 'Contacto', value: 'contact'}, {text: 'Reservación', value: 'reservation'}, {text: 'Condiciones generales', value: 'generals_conditions'}, {text: 'Protección', value: 'protection'}],
            dialog: false,
            editor: Editor,
            sectionContent: "",
            sectionTitle: "",
            indexEditing: false,
            fieldRule: [
                v => !!v || 'Este campo es requerido'
            ],
        }),
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
        },
        methods: {
            deleteSection(item){
                event.preventDefault()
                this.item.sections.splice(this.item.sections.indexOf(item), 1);
            },
            editSection(item){
                this.indexEditing = this.item.sections.indexOf(item);
                this.sectionTitle = item.title;
                this.sectionContent = item.content;
                this.dialog = true;
            },
            saveSection(){
                if(typeof this.indexEditing != typeof false){
                    this.item.sections[this.indexEditing].title = this.sectionTitle;
                    this.item.sections[this.indexEditing].content = this.sectionContent;
                }
                else
                this.item.sections.push({title: this.sectionTitle, content: this.sectionContent});
                this.sectionContent = "";
                this.sectionTitle = "";
                this.dialog = false;
            }
        },
        watch:{
            dialog(show){
                if(!show){
                  this.indexEditing = false;
                }
            }
        }
    }
</script>
