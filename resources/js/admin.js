/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('vue-moment'));
import { VueMaskDirective } from 'v-mask'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('messages', require('./components/admin/Messages.vue').default);
Vue.component('info-box', require('./components/admin/InfoBox.vue').default);
Vue.component('sub-items', require('./components/admin/SubItems.vue').default);
Vue.component('pagination', require('./components/admin/Pagination.vue').default);
Vue.component('select-action', require('./components/admin/SelectAction.vue').default);
Vue.component('multiple-upload', require('./components/admin/MultipleUpload.vue').default);

/**
 * Tranlate function
 */
Vue.prototype.trans = (key, attributes) => {
    let term = _.get(window.translation, key, key);
    if(attributes && attributes.length > 0)
        for(let [key, value] of attributes.entries())
            term = term.replace(':'+key, _.get(window.translation, value, value));
    
    return term;
};


/**
 * Directives
 */
Vue.directive('select2', {
    inserted(el) {
        $(el).on('select2:select', () => {
            const event = new Event('change', { bubbles: true, cancelable: true });
            el.dispatchEvent(event);
        });

        $(el).on('select2:unselect', () => {
            const event = new Event('change', {bubbles: true, cancelable: true})
            el.dispatchEvent(event)
        })
    },
});
Vue.directive('focus', {
    inserted(el) {
        el.focus();
    }
});
Vue.directive('mask', VueMaskDirective);

 /**
  * Filters
  */
Vue.filter('cpf', (number) => {
    number = typeof number != 'string' ? number.toString() : number;
    number = number.padStart(11, '0');
    number = number.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    return number;
});
Vue.filter('cnpj', (number) => {
    number = typeof number != 'string' ? number.toString() : number;
    number = number.padStart(14, '0');
    number = number.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    return number;
});
Vue.filter('cpfcnpj', (number) => {
    number = typeof number != 'string' ? number.toString() : number;
    if(number.length > 11){
        number = number.padStart(14, '0');
        number = number.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }else{
        number = number.padStart(11, '0');
        number = number.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');    
    }
    return number;
});
Vue.filter('date', function (value) {
    if (!value) return new Date();
    return new Date(value.replace(/\.(.*)/g, ""));
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
