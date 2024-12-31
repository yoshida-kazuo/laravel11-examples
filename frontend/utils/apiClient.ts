import axios from 'axios';

const apiClient = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

// リクエストインターセプタ
apiClient.interceptors.request.use(
    config => {
        const token = localStorage.getItem('token');

        if (token && config.headers) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        return config;
    },
    error => Promise.reject(error)
);

// レスポンスインターセプタ
apiClient.interceptors.response.use(
    response => response,
    error => {
        console.error(error);

        return Promise.reject(error);
    }
);

export default apiClient;
