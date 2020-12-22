import VueRouter from 'vue-router'

// Pages
import NotFound from 'pages/error/not-found';
import PageForbidden from 'pages/error/page-forbidden';

import Register from 'pages/register';
import Login from 'pages/auth/login';
import ForgotPassword from 'pages/auth/forgot-password';
import Dashboard from 'pages/dashboard';

import RoleList from 'pages/role/list';

// Routes
const routes = [
    { path: '/404', component: NotFound },
    { path: '/403', component: PageForbidden },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    {
        path: '/forgot-password',
        name: 'forgot_password',
        component: ForgotPassword,
        meta: {
            auth: false
        }
    },
    {
        path: '/roles',
        name: 'roles_list',
        component: RoleList,
        meta: {
            auth: true
        }
    },
]

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})
export default router