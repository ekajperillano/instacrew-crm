import VueRouter from 'vue-router'

// Pages
import NotFound from 'pages/error/not-found';
import PageForbidden from 'pages/error/page-forbidden';

import Register from 'pages/register';
import Login from 'pages/auth/login';
import ForgotPassword from 'pages/auth/forgot-password';
import Dashboard from 'pages/dashboard';

import RoleList from 'pages/role/list';

import UserList from 'pages/user/list';
import UserProfile from 'pages/user/profile';

import ClientList from 'pages/client/list';
import ClientDetail from 'pages/client/detail';

import NotificationList from 'pages/notification/list';
import NotificationDetail from 'pages/notification/detail';

const forbiddenAttr = {
    redirect: {name: 'login'}, 
    forbiddenRedirect: '/403'
};

// Routes
const routes = [
    { path: '/404', component: NotFound },
    { path: '/403', component: PageForbidden },
    { path: '*', redirect: '/404' },
    { path: '/', redirect: '/dashboard' },
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
        path: '/role',
        name: 'role_list',
        component: RoleList,
        meta: {
            auth: true,
            ...forbiddenAttr
        }
    },
    {
        path: '/user',
        name: 'user_list',
        component: UserList,
        meta: {
            auth: true,
            ...forbiddenAttr
        }
    },
    {
        path: '/profile',
        name: 'user_profile',
        component: UserProfile,
        meta: {
            auth: true,
            ...forbiddenAttr
        }
    },
    {
        path: '/client',
        name: 'client_list',
        component: ClientList,
        meta: {
            auth: true,
            ...forbiddenAttr
        }
    },
    {
        path: '/client/:id',
        name: 'client_detail',
        component: ClientDetail,
        meta: {
            auth: true,
            ...forbiddenAttr
        }
    },
    {
        path: '/notifications',
        name: 'notification',
        component: NotificationList,
        meta: {
            auth: true,
            ...forbiddenAttr
        }
    },
    {
        path: '/notifications/:id',
        name: 'notification_detail',
        component: NotificationDetail,
        meta: {
            auth: true,
            ...forbiddenAttr
        }
    }

]

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
});

router.beforeEach((to, from, next) => {
    const { name: fromName, params: fromParams, meta: fromMeta } = from
    const { name: toName, params: toParams, meta: toMeta } = to

    if(toName == 'login') {
        localStorage.clear();
    }

    next(); 
    
});
export default router