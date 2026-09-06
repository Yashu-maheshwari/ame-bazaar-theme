(function(){'use strict';
function svg(path,viewBox){return '<svg class="ame-social-icon" viewBox="'+(viewBox||'0 0 24 24')+'" fill="none" aria-hidden="true">'+path+'</svg>';}
var icons={
 facebook:svg('<path d="M14 8h3V4h-3c-3.3 0-5 1.9-5 5v3H6v4h3v5h4v-5h3l1-4h-4V9c0-.7.3-1 1-1z" fill="currentColor"/>'),
 instagram:svg('<rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4.2" stroke="currentColor" stroke-width="2"/><circle cx="17.3" cy="6.8" r="1.2" fill="currentColor"/>'),
 threads:svg('<path d="M16.9 11.1c-.2-3.4-2.1-5.2-5.2-5.2-3.2 0-5.3 1.9-5.3 4.7 0 2.7 1.8 4.3 4.7 4.3 2.2 0 3.8-1 4.7-2.7.2 2.8-1.2 4.4-4 4.4-1.9 0-3.2-.7-4.1-2.1l-1.8 1.1c1.2 2 3.2 3 5.9 3 4.4 0 6.9-2.5 6.9-6.8 0-4.6-2.4-7.5-6.6-7.5-4.4 0-7.1 2.4-7.1 6.4 0 4 2.7 6.7 6.9 6.7 3.7 0 5.8-2.1 5.8-5.5 0-2.5-1.5-4.1-3.8-4.1-1.9 0-3.1 1-3.1 2.4 0 1.2.8 1.9 2.1 1.9 1.1 0 2-.5 2.6-1.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'),
 youtube:svg('<path d="M21.6 7.1a2.8 2.8 0 0 0-2-2C17.9 4.6 12 4.6 12 4.6s-5.9 0-7.6.5a2.8 2.8 0 0 0-2 2C1.9 8.8 1.9 12 1.9 12s0 3.2.5 4.9a2.8 2.8 0 0 0 2 2c1.7.5 7.6.5 7.6.5s5.9 0 7.6-.5a2.8 2.8 0 0 0 2-2c.5-1.7.5-4.9.5-4.9s0-3.2-.5-4.9Z" fill="currentColor"/><path d="m10 9 5 3-5 3V9Z" fill="#001a35"/>')
};
function setup(){var wrap=document.querySelector('.ame-footer-socials');if(!wrap)return;
var links=wrap.querySelectorAll('.ame-footer-social-link');
links.forEach(function(a){var label=(a.getAttribute('aria-label')||'').toLowerCase();if(label.indexOf('facebook')>-1){a.href='https://www.facebook.com/AMETTBAZAAR';a.innerHTML=icons.facebook;}else if(label.indexOf('instagram')>-1){a.href='https://www.instagram.com/ame_bazaar/';a.innerHTML=icons.instagram;}});
if(!wrap.querySelector('.ame-footer-social-threads')){var a=document.createElement('a');a.href='https://www.threads.com/@ame_bazaar';a.className='ame-footer-social-link ame-footer-social-threads';a.target='_blank';a.rel='noopener noreferrer';a.setAttribute('aria-label','Visit AME Bazaar on Threads');a.innerHTML=icons.threads;wrap.appendChild(a);}
if(!wrap.querySelector('.ame-footer-social-youtube')){var a=document.createElement('a');a.href='https://www.youtube.com/channel/UCWvZ6Oa8KkullmhxB2oEp7A';a.className='ame-footer-social-link ame-footer-social-youtube';a.target='_blank';a.rel='noopener noreferrer';a.setAttribute('aria-label','Visit AME Bazaar on YouTube');a.innerHTML=icons.youtube;wrap.appendChild(a);}
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',setup);else setup();})();