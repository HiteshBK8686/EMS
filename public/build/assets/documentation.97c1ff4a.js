import{C as g}from"./clipboard.bfbdb4ae.js";import{L as c}from"./app.6cf530fe.js";const f=c(()=>({}).VITE_APP_NAME),d=c(()=>({}).VITE_APP_VERSION);c(()=>({}).VITE_APP_DEMO);const m=()=>{const h=r=>{let e=r;if(typeof e>"u"&&(e=document.querySelectorAll(".highlight")),e&&e.length>0)for(let n=0;n<e.length;++n){const l=e[n].querySelector(".highlight-copy");l&&new g(l,{target:t=>{const i=t.closest(".highlight");if(i){let o=i.querySelector(".tab-pane.active");return o==null&&(o=i.querySelector(".highlight-code")),o}return i}}).on("success",t=>{const i=t.trigger.innerHTML;t.trigger.innerHTML="copied",t.clearSelection(),setTimeout(function(){t.trigger.innerHTML=i},2e3)})}};return{init:r=>{h(r)}}};export{f as t,m as u,d as v};