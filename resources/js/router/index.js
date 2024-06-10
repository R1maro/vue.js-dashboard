import {createRouter, createWebHistory} from "vue-router";

 import dashboard from '../components/dashboard.vue';
 import users_form from '../components/users-form.vue';
 import users_list from '../components/users-list.vue';
 import notfound from '../components/notfound.vue';

 const routes = [

     {
         path : '/',
         component: dashboard
     } ,
     {
         path : '/users-list',
         component: users_list
     },
     {
         path : '/users-form',
         component: users_form
     },
     {
         path : '/:pathMatch(.*)*',
         component: notfound
     }
 ];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
