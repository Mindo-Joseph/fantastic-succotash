importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyBtE2uCaikxgUDbn5SqmzW2fGcGOpUlkqc",
    projectId: "royo-order-version2",
    messagingSenderId: "1073948422654",
    appId: "1:1073948422654:web:4dd137a854484fa3c410af"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});