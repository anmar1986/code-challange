import axios from 'axios';
window.axios = axios;

axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.baseURL = ''; // this makes use /api/ prefix
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
