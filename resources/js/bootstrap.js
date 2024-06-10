window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//      authEndpoint:'http://127.0.0.1:8000/api/broadcasting/auth',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: false,
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     encrypted: false,
//     auth: {
//         headers: {
//             Authorization: 'Bearer ' + localStorage.getItem("token"),
//         },
//     },
// });


// Import the functions you need from the SDKs you need
import { initializeApp, getApps } from "firebase/app";
import { getFirestore } from 'firebase/firestore';

const firebaseConfig = {
    apiKey: "AIzaSyBHhSlaWgC0ngS3EhYpG3yB_tYcxtBEhFs",
    authDomain: "chat-app-a5ec7.firebaseapp.com",
    projectId: "chat-app-a5ec7",
    storageBucket: "chat-app-a5ec7.appspot.com",
    messagingSenderId: "127240678157",
    appId: "1:127240678157:web:5831cd4bae76c42e8cde44",
    measurementId: "G-R446GB1541"
  };
// Initialize Firebase
let firebaseApp;

if (!getApps.length) {
  firebaseApp = initializeApp(firebaseConfig)
}
const db = getFirestore(firebaseApp);
export { db };