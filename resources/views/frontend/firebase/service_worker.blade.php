importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

var firebaseCredentials = {!!json_encode(Session::get('preferences')) !!};
var firebaseConfig = {
    apiKey: firebaseCredentials.fcm_api_key,
    authDomain: firebaseCredentials.fcm_auth_domain,
    projectId: firebaseCredentials.fcm_project_id,
    storageBucket: firebaseCredentials.fcm_storage_bucket,
    messagingSenderId: firebaseCredentials.fcm_messaging_sender_id,
    appId: firebaseCredentials.fcm_app_id,
    measurementId: firebaseCredentials.fcm_measurement_id
};


const messaging = firebase.messaging();