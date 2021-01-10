import Alpine from 'alpinejs';
import Cleave from 'cleave.js';

window.Cleave = Cleave;

document.addEventListener('DOMContentLoaded', function () {
    window.Livewire.on('url-changed', (url) => {
        history.pushState(null, null, url);
    });
});
