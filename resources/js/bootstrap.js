window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { initializeApp, getApps } from "firebase/app";
import { getFirestore } from 'firebase/firestore';

const firebaseConfig = {
  apiKey: "AIzaSyBsyK46v5kyLJWy4Cd6aQiCxKKQ_4ybt8A",
  authDomain: "self-ship-label.firebaseapp.com",
  projectId: "self-ship-label",
  storageBucket: "self-ship-label.appspot.com",
  messagingSenderId: "742964714477",
  appId: "1:742964714477:web:8a80a1774a5cd5364525e4",
  measurementId: "G-ZVWJM4FHKC"
};

let firebaseApp;

if (!getApps.length) {
  firebaseApp = initializeApp(firebaseConfig)
}

const db = getFirestore(firebaseApp);
export { db };