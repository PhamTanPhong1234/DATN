import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start(); 

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

import Echo from "laravel-echo";
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

window.Echo.private('chat')
    .listen('MessageSent', (e) => {
        console.log('Tin nhắn mới:', e.message);
    });
