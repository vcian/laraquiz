
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// Vue.component("user-component", require("./components/UsersComponent.vue"));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        quiz: {
            id: quizId,
            name: quizName,
            slug: quizSlug,
            users: [],
            result:[]
        }
    },
    created() {
        axios.post(window.location.origin+'/quiz/'+ this.quiz.slug+'/dashboard')
            .then(response => {
                if (response.data) {
                    this.quiz.users = response.data.userDetails;
                    this.quiz.result = response.data.quizResult;
                }
            })
            .catch(e => {
                console.log(e);
            });

        this.$socket.on('quizStart'+ this.quiz.id, (data) => {
            this.quiz.users.push(data);
        });

        this.$socket.on('quizResult'+ this.quiz.id, (data) => {
            this.quiz.result = data.quizResult;
            console.log(data);
            this.quiz.users.map(function(value, key) {
                if(value.id === data.id) {
                    value.quiz_complete = data.quiz_complete;
                    value.end_time = data.end_time;
                }
            });
        });
    },
    sockets:{
        connect: function(){
            console.log('socket connect')
        }
    },
});
