import axios from 'axios';

let instance = axios.create({
    baseURL: '/new/user-panel/',
    // headers: {
    //     'content-type': 'multipart/form-data',
    // }
});

export default instance