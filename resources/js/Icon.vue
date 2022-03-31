<template>
    <svg v-if="name === 'bar-chart'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size" :height="size"
         fill="currentColor" stroke="none">
        <path class="fill-current text-gray-500"
              d="M4 18h2a1 1 0 001-1V8a1 1 0 00-1-1H4a1 1 0 00-1 1v9a1 1 0 001 1zM11 18h2a1 1 0 001-1V3a1 1 0 00-1-1h-2a1 1 0 00-1 1v14a1 1 0 001 1zM17 12v5a1 1 0 001 1h2a1 1 0 001-1v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1z"></path>
    </svg>
    <svg v-else-if="name === 'bell'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size" :height="size"
         stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
    </svg>
    <svg v-else-if="name === 'chats'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size"
         :height="size"
         stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z"></path>
        <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10"></path>
        <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2"></path>
    </svg>
    <svg v-else-if="name === 'close'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" :width="size"
         :height="size">
        <path
            d="M12.72 3.293a1 1 0 00-1.415 0L8.012 6.586 4.72 3.293a1 1 0 00-1.414 1.414L6.598 8l-3.293 3.293a1 1 0 101.414 1.414l3.293-3.293 3.293 3.293a1 1 0 001.414-1.414L9.426 8l3.293-3.293a1 1 0 000-1.414z"></path>
    </svg>
    <svg v-else-if="name === 'delete'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size"
         :height="size">
        <path fill="none" d="M0 0h24v24H0z"/>
        <path
            d="M17 6h5v2h-2v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H2V6h5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3zm1 2H6v12h12V8zm-4.586 6l1.768 1.768-1.414 1.414L12 15.414l-1.768 1.768-1.414-1.414L10.586 14l-1.768-1.768 1.414-1.414L12 12.586l1.768-1.768 1.414 1.414L13.414 14zM9 4v2h6V4H9z"/>
    </svg>
    <svg v-else-if="name === 'hamburger'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size"
         :height="size"
         fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
    <svg v-else-if="name === 'home-smile'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size"
         :height="size"
         fill="currentColor" stroke="none">
        <path fill="none" d="M0 0h24v24H0z"/>
        <path
            d="M6 19h12V9.157l-6-5.454-6 5.454V19zm13 2H5a1 1 0 0 1-1-1v-9H1l10.327-9.388a1 1 0 0 1 1.346 0L23 11h-3v9a1 1 0 0 1-1 1zM7.5 13h2a2.5 2.5 0 1 0 5 0h2a4.5 4.5 0 1 1-9 0z"/>
    </svg>
    <svg v-else-if="name === 'lock'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size" :height="size"
         stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path
            d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/>
    </svg>
    <svg v-else-if="name === 'moon'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size" :height="size"
         fill="currentColor" stroke="none">
        <path fill="none" d="M0 0h24v24H0z"/>
        <path
            d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/>
    </svg>
    <svg v-else-if="name === 'page'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" :width="size"
         :height="size">
        <path d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h8l5-5V1c0-.6-.4-1-1-1zM3 2h10v8H9v4H3V2z"/>
    </svg>
    <svg v-else-if="name === 'sear-fresh'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" :width="size"
         :height="size">
        <path
            d="M15.707 14.293v.001a1 1 0 01-1.414 1.414L11.185 12.6A6.935 6.935 0 017 14a7.016 7.016 0 01-5.173-2.308l-1.537 1.3L0 8l4.873 1.12-1.521 1.285a4.971 4.971 0 008.59-2.835l1.979.454a6.971 6.971 0 01-1.321 3.157l3.107 3.112zM14 6L9.127 4.88l1.521-1.28a4.971 4.971 0 00-8.59 2.83L.084 5.976a6.977 6.977 0 0112.089-3.668l1.537-1.3L14 6z"/>
    </svg>
    <svg v-else-if="name === 'search'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size"
         :height="size" fill="currentColor" stroke="none">
        <path
            d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
        </path>
    </svg>
    <svg v-else-if="name === 'settings'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size"
         :height="size"
         stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z"></path>
        <path
            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
        <circle cx="12" cy="12" r="3"></circle>
    </svg>
    <svg v-else-if="name === 'sun'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size" :height="size"
         fill="currentColor" stroke="none">
        <path fill="none" d="M0 0h24v24H0z"/>
        <path
            d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/>
    </svg>
    <svg v-else-if="name === 'utilities'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :width="size"
         :height="size"
         fill="currentColor" stroke="currentColor">
        <circle cx="18" cy="5" r="3.5"></circle>
        <circle cx="5" cy="5" r="3.5"></circle>
        <circle cx="18" cy="18" r="3.5"></circle>
        <circle cx="5" cy="18" r="3.5"></circle>
    </svg>
</template>

<script>
export default {
    name: "Icon",
    props: {
        name: String,
        size: {
            type: Number,
            default: 20,
        }
    },
}
</script>
