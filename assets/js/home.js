import "materialize-css/js/buttons";
import "materialize-css/js/forms";
import "materialize-css/js/dropdown";
import "materialize-css/js/modal";
import "materialize-css/js/select";
import "materialize-css/js/datepicker";
import "materialize-css/js/toasts";

import Vue from 'vue';

import moment from 'moment';



var tarifTest = new Vue({
    el: '#tarif-card',
    data: {
        startDate: null,
        endDate: null,
        descount: null,
        total: null,
        realTotal: null
    },
    methods: {
        testTarif(event) {
            if(typeof this.startDate.date == typeof undefined || document.getElementById('end-date').value == ""){
                event.stopPropagation();
                M.toast({html: 'Champ est obligatoire'});return;
            }
            let diff = moment(this.endDate.date).diff(moment(this.startDate.date), 'days') + 1;
            this.realTotal = parseInt(day)*parseInt(diff) + parseInt(price) + parseInt(annulation);
            if(this.descount){
                this.total = this.realTotal;
                this.realTotal -= this.realTotal * (parseInt(this.descount)/100);
            }
        },
        setEndDate(){
            document.getElementById('end-date').value = "";
            this.endDate.options.minDate = moment(this.startDate.date);
        }
    },
    created() {
        let $this = this;
        let i18n = {
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            cancel: 'Annuler'
        }
        document.addEventListener('DOMContentLoaded', function () {
            let picker = M.Datepicker.init(cash('.start-date'), {autoClose: true, format: 'dd/mm/yyyy', minDate: new Date(), onSelect: $this.setEndDate, i18n: i18n});
            $this.startDate = picker[0];
            let picker2 = M.Datepicker.init(cash('.end-date'), {autoClose: true, format: 'dd/mm/yyyy', minDate: new Date(), i18n: i18n});
            $this.endDate = picker2[0];
        });
        if(typeof descount != typeof undefined){
            this.descount = descount;
        }
    }
})

var tarifTestMovil = new Vue({
    el: '#tarif-card-movil',
    data: {
        startDate: null,
        endDate: null,
        descount: null,
        total: null,
        realTotal: null
    },
    methods: {
        testTarif(event) {
            if(typeof this.startDate.date == typeof undefined || document.getElementById('end-date-movil').value == ""){
                event.stopPropagation();
                M.toast({html: 'Champ est obligatoire'});return;
            }
            let diff = moment(this.endDate.date).diff(moment(this.startDate.date), 'days') + 1;
            this.realTotal = parseInt(day)*parseInt(diff) + parseInt(price) + parseInt(annulation);
            if(this.descount){
                this.total = this.realTotal;
                this.realTotal -= this.realTotal * (parseInt(this.descount)/100);
            }
        },
        setEndDate(){
            document.getElementById('end-date-movil').value = "";
            this.endDate.options.minDate = moment(this.startDate.date);
        }
    },
    created() {
        let $this = this;
        let i18n = {
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            cancel: 'Annuler'
        }
        document.addEventListener('DOMContentLoaded', function () {
            let picker = M.Datepicker.init(cash('.start-date-movil'), {autoClose: true, format: 'dd/mm/yyyy', minDate: new Date(), onSelect: $this.setEndDate, i18n: i18n});
            $this.startDate = picker[0];
            let picker2 = M.Datepicker.init(cash('.end-date-movil'), {autoClose: true, format: 'dd/mm/yyyy', minDate: new Date(), i18n: i18n});
            $this.endDate = picker2[0];
        });
        if(typeof descount != typeof undefined){
            this.descount = descount;
        }
    }
})

