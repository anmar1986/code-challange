import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token'), 
    }),
    getters: {
        isAuthenticated: (state) => !!state.user && !!state.token,
    },
    actions: {
        async login(credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                this.user = response.data.data.user;
                this.token = response.data.data.token;
                localStorage.setItem('auth_token', this.token);
                return response.data;
            } catch (error) {
                throw error.response.data; 
            }
        },
        async register(userData) {
            try {
                await axios.get('/sanctum/csrf-cookie') ; // Ensure CSRF token is set
                const response = await axios.post('api/register', userData);
                this.user = response.data.data.user;
                this.token = response.data.data.token;
                localStorage.setItem('auth_token', this.token);
                return response.data;
            } catch (error) {
                throw error.response.data; 
            }
        },
        async logout() {
            try {
                await axios.post('/api/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    },
                });
                this.user = null;
                this.token = null;
                localStorage.removeItem('auth_token');
            } catch (error) {
                console.error('Logout failed:', error);
            }
        },
        async fetchUser() {
            if (!this.token) {
                this.token = localStorage.getItem('auth_token');
            }
            if (this.token) {
                try {
                    const response = await axios.get('/api/user', {
                        headers: {
                            Authorization: `Bearer ${this.token}`,
                        },
                    });
                    this.user = response.data.data;
                } catch (error) {
                    //If teh  token is invalid or expired then clear user data
                    this.user = null;
                    this.token = null;
                    localStorage.removeItem('auth_token');
                    console.error('Failed to fetch user:', error);
                }
            }
        },
    },
});
