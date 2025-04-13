import { boot } from 'quasar/wrappers';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter()

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    console.warn('Axios interceptor triggered');
    console.warn('Error object:', error);

    const status = error.response?.status;

    if (status === 401) {
      console.warn('Unauthorized! Redirecting to login.');

      localStorage.removeItem('token');
      delete api.defaults.headers.common.Authorization;

      if (router.currentRoute.value.path !== '/login') {
        void router.push('/login');
      }
    }

    return Promise.reject(
      error instanceof Error
        ? error
        : new Error(error.message || 'Unknown error')
    );
  }
);

export default boot(() => {
  const token = localStorage.getItem('token');
  if (token) {
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
  }
});

export { axios, api };