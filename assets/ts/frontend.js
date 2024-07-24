(()=>{"use strict";var e={61:()=>{navigator.userAgent.indexOf("Safari")>-1&&navigator.userAgent.indexOf("Chrome")<=-1&&document.documentElement.classList.add("is-safari")},498:()=>{var e=["#wpadminbar","#headerwrapper"],t="--fau-top-fixed-height",n="-shadow",r=function(){var n=e.reduce((function(e,t){var n=document.querySelector(t);return n?e+n.clientHeight:e}),0);document.body.style.setProperty(t,"".concat(n,"px"))},o=function(e){e.forEach((function(e){e.isIntersecting?e.target.classList.add(n):e.target.classList.remove(n)}))};window.addEventListener("resize",r),window.addEventListener("DOMContentLoaded",r),window.addEventListener("DOMContentLoaded",(function(){document.querySelectorAll(".c-search-filters").forEach((function(e){var n=document.body.style.getPropertyValue(t),r=parseInt(n.replace("px",""),10);if(r){var a="0px 0px -".concat(window.outerHeight-r,"px");new IntersectionObserver(o,{rootMargin:a}).observe(e)}}))}))},835:()=>{document.addEventListener("DOMContentLoaded",(function(){document.querySelectorAll(".c-sort-selector select").forEach((function(e){e.addEventListener("change",(function(){window.location.href=e.value}))}))}))},508:()=>{var e=".search-filter__output_modes .search-filter__output-mode-option",t="-active",n="-list",r="-tiles",o=function(o){var a=document.querySelectorAll(".c-degree-programs-collection");a.length&&(document.querySelectorAll(e).forEach((function(e){var n=e;n.classList.remove(t),n.dataset.mode===o&&n.classList.add(t)})),a.forEach((function(e){e.classList.remove(n,r),e.classList.add("list"===o?n:r)})))};document.querySelectorAll(e).forEach((function(e){e.addEventListener("click",(function(t){t.preventDefault();var n=e.dataset.mode;n&&(o(n),function(e){var t=new URL(window.location.href);t.searchParams.set("output",e),window.history.pushState({outputMode:e},"",t)}(n))}))})),window.addEventListener("popstate",(function(e){var t=e.state.outputMode;t&&o(t)}))}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var a=t[r]={exports:{}};return e[r](a,a.exports,n),a.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e=".c-accordion-item__button",t="--content-height",r=function(e){var t;if("false"!==e.ariaExpanded){var n=document.getElementById(null!==(t=e.getAttribute("aria-controls"))&&void 0!==t?t:"");n instanceof HTMLElement&&(e.setAttribute("aria-expanded","false"),n.setAttribute("hidden","hidden"))}};document.querySelectorAll(e).forEach((function(n){n.addEventListener("click",(function(o){o.preventDefault(),function(n){var o=n.closest(".c-accordion-item");if(o instanceof HTMLElement){var a=o.parentElement;a instanceof HTMLElement&&("true"!==n.getAttribute("aria-expanded")?(function(e){var n;if("true"!==e.ariaExpanded){var r=document.getElementById(null!==(n=e.getAttribute("aria-controls"))&&void 0!==n?n:"");r instanceof HTMLElement&&(r.style.getPropertyValue(t)||r.style.setProperty(t,"".concat(r.scrollHeight,"px")),e.setAttribute("aria-expanded","true"),r.removeAttribute("hidden"))}}(n),a.querySelectorAll("".concat(e,":not(#").concat(n.id,")")).forEach(r)):r(n))}}(n)}))}));var o=function(){return o=Object.assign||function(e){for(var t,n=1,r=arguments.length;n<r;n++)for(var o in t=arguments[n])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e},o.apply(this,arguments)};function a(e,t){var n="function"==typeof Symbol&&e[Symbol.iterator];if(!n)return e;var r,o,a=n.call(e),i=[];try{for(;(void 0===t||t-- >0)&&!(r=a.next()).done;)i.push(r.value)}catch(e){o={error:e}}finally{try{r&&!r.done&&(n=a.return)&&n.call(a)}finally{if(o)throw o.error}}return i}function i(e,t,n){if(n||2===arguments.length)for(var r,o=0,a=t.length;o<a;o++)!r&&o in t||(r||(r=Array.prototype.slice.call(t,0,o)),r[o]=t[o]);return e.concat(r||Array.prototype.slice.call(t))}Object.create,Object.create,"function"==typeof SuppressedError&&SuppressedError;const c=window.wp.i18n,l=o({},window.degreeProgramsOverviewSettings);var u=function(){function e(e){var t=e.id,n=e.image,r=e.url,o=e.title,a=e.degree,i=e.semester,c=e.location,l=e.admissionRequirements,u=e.germanLanguageSkills;this.id=t,this.image=n,this.url=r,this.title=o,this.degree=a,this.semester=i,this.location=c,this.admissionRequirements=l,this.germanLanguageSkills=u}return e.createDegreeProgram=function(t){var n;return new e({id:t.id,title:t.title,degree:t.degree,image:t.teaser_image.rendered||l.icon_degree,url:t.link,location:t.location,semester:t.start,admissionRequirements:null===(n=t.admission_requirement_link)||void 0===n?void 0:n.name,germanLanguageSkills:t.german_language_skills_for_international_students.name})},e.prototype.render=function(){return'\n\t\t\t<li class="c-degree-program-preview">\n\t\t\t\t<div class="c-degree-program-preview__teaser-image">\n\t\t\t\t\t'.concat(this.image,'\n\t\t\t\t</div>\n\t\t\t\t<div class="c-degree-program-preview__title">\n\t\t\t\t\t<a class="c-degree-program-preview__link" href="').concat(this.url,'" rel="bookmark" aria-labelledby="degree-program-title-').concat(this.id,'"></a>\n\t\t\t\t\t<div id="degree-program-title-').concat(this.id,'">\n\t\t\t\t\t\t').concat(this.title,' (<abbr title="').concat(this.degree.name,'">').concat(this.degree.abbreviation,'</abbr>)\n\t\t\t\t\t\t<div class="c-degree-program-preview__subtitle"></div>\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t\t<div class="c-degree-program-preview__degree">\n\t\t\t\t\t<span class="c-degree-program-preview__label">\n\t\t\t\t\t\t').concat((0,c._x)("Type","frontoffice: degree-programs-overview","fau-degree-program-output"),":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.degree.name,'\n\t\t\t\t</div>\n\t\t\t\t<div class="c-degree-program-preview__start">\n\t\t\t\t\t<span class="c-degree-program-preview__label">\n\t\t\t\t\t\t').concat((0,c._x)("Start","frontoffice: degree-programs-overview","fau-degree-program-output"),":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.semester.join(", "),'\n\t\t\t\t</div>\n\t\t\t\t<div class="c-degree-program-preview__location">\n\t\t\t\t\t<span class="c-degree-program-preview__label">\n\t\t\t\t\t\t').concat((0,c._x)("Location","frontoffice: degree-programs-overview","fau-degree-program-output"),":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.location.join(", "),'\n\t\t\t\t</div>\n\t\t\t\t<div class="c-degree-program-preview__admission-requirement">\n\t\t\t\t\t<span class="c-degree-program-preview__label">\n\t\t\t\t\t\t').concat((0,c._x)("NC","frontoffice: degree-programs-overview","fau-degree-program-output"),":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.admissionRequirements?this.admissionRequirements:"",'\n\t\t\t\t</div>\n\t\t\t\t<div class="c-degree-program-preview__language-certificates">\n\t\t\t\t\t<span class="c-degree-program-preview__label">\n\t\t\t\t\t\t').concat((0,c._x)("Language certificates","frontoffice: degree-programs-overview","fau-degree-program-output"),":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.germanLanguageSkills,"\n\t\t\t\t</div>\n\t\t\t</li>\n\t\t")},e}();const s=u;var d,v=".c-no-search-results",f=document.querySelector(".c-degree-programs-search"),p=null==f?void 0:f.querySelector(".c-degree-programs-collection"),m=(null===(d=null==f?void 0:f.getAttribute("lang"))||void 0===d?void 0:d.substring(0,2))||"de";const g=function(e){var t;null==p||p.setAttribute("aria-busy","true"),null===(t=null==p?void 0:p.querySelectorAll(".c-degree-program-preview"))||void 0===t||t.forEach((function(e){return e.remove()}));var n,r=e.map((function(e){return s.createDegreeProgram(e)}));if(!r.length)return function(){var e,t=null==f?void 0:f.querySelector(v);if(!t)return e='\n\t\t<p class="c-no-search-results">\n\t\t\t'.concat((0,c._x)("No degree programs found","frontoffice: Search results","fau-degree-program-output"),"\n\t\t</p>"),void(null==f||f.insertAdjacentHTML("beforeend",e));t.classList.remove("hidden")}(),void(null==p||p.setAttribute("aria-busy","false"));(n=null==f?void 0:f.querySelector(v))&&n.classList.add("hidden"),function(e){var t=e.map((function(e){return e.render()})).join("");null==p||p.insertAdjacentHTML("beforeend",t)}(r),null==p||p.setAttribute("aria-busy","false")},h=function(){return window.matchMedia("(prefers-reduced-motion)").matches};var y="c-filter-dropdown__count",w=".".concat(y);var b="german-language-skills-for-international-students",_=document.querySelectorAll(".c-filter-checkbox"),E=0;_.forEach((function(e){var t=e.querySelector("input[type=checkbox]");(null==t?void 0:t.name.startsWith(b))&&(null==t?void 0:t.checked)&&E++,null==t||t.addEventListener("change",(function(n){var r;M(e,t),function(e){var t,n=e.closest(".c-filter-dropdown"),r=null==n?void 0:n.querySelector(".c-filter-dropdown__label");if(n&&r){var o=n.querySelector(w);o||((o=document.createElement("span")).className=y,r.insertAdjacentElement("afterend",o));var a=Number((null===(t=o.textContent)||void 0===t?void 0:t.trim())||"0");(a+=e.checked?1:-1)<1?o.remove():o.textContent=String(a)}}(t),t.name.startsWith(b)&&(r={activeFilters:(E+=t.checked?1:-1)>=1?b:""},p instanceof HTMLElement&&Object.entries(r).forEach((function(e){var t=a(e,2),n=t[0],r=t[1];p.dataset[n]=r}))),h()||J()}))}));var S="c-active-search-filters__item",L=".".concat(S),x=document.querySelector(".c-active-search-filters"),q=document.querySelector(".c-active-search-filters__list"),A=function(){return document.querySelectorAll(L)},k=function(){0===A().length&&(null==x||x.classList.add("hidden"))},P=function(e){var t;e.preventDefault();var n=e.target;!function(e){if(e){var t=e.split(":").map((function(e){return e.trim()}));if(a(t,1)[0]===O)return H(),void(h()&&J());var n=a(t,2)[1];_.forEach((function(e){var t,r,o=null===(r=null===(t=e.querySelector("span"))||void 0===t?void 0:t.textContent)||void 0===r?void 0:r.trim();if(n===o){var a=e.querySelector("input[type=checkbox]");a.checked=!1,a.dispatchEvent(new Event("change")),h()&&J()}}))}}((null===(t=n.textContent)||void 0===t?void 0:t.trim())||""),n.remove(),k()};A().forEach((function(e){e.addEventListener("click",P)}));var j=function(e){var t=document.createElement("a");t.insertAdjacentHTML("beforeend","\n\t\t\t".concat(l.icon_close,"\n\t\t\t").concat(e,"\n\t\t")),t.className=S,t.addEventListener("click",P),null==q||q.insertAdjacentElement("beforeend",t),null==x||x.classList.remove("hidden")},M=function(e,t){var n=function(e,t){var n,r,o,a,i,c,l,u=null===(r=null===(n=e.querySelector("span"))||void 0===n?void 0:n.textContent)||void 0===r?void 0:r.trim(),s=t.closest(".c-advanced-filter-item");return s?(l=null===(a=null===(o=s.querySelector(".c-accordion-item__title"))||void 0===o?void 0:o.textContent)||void 0===a?void 0:a.trim(),"".concat(l,": ").concat(u)):(l=null===(c=null===(i=null==(s=t.closest(".c-filter-dropdown"))?void 0:s.querySelector(".c-filter-dropdown__label"))||void 0===i?void 0:i.textContent)||void 0===c?void 0:c.trim(),"".concat(l,": ").concat(u))}(e,t);t.checked?j(n):function(e){A().forEach((function(t){var n;(null===(n=t.textContent)||void 0===n?void 0:n.trim())===e&&t.remove()})),k()}(n)},O=(0,c._x)("Keyword","frontoffice: degree-programs-overview","fau-degree-program-output"),T=document.querySelector(".c-degree-programs-searchform__input"),C="";h()||function(){var e=null;null==T||T.addEventListener("input",(function(){e&&clearTimeout(e),((null==T?void 0:T.value.trim())||"").length>3&&(e=setTimeout((function(){J(),e=null}),1500))}))}(),null==T||T.addEventListener("focus",(function(){C=(null==T?void 0:T.value.trim())||""})),null==T||T.addEventListener("blur",(function(){var e=(null==T?void 0:T.value.trim())||"";e.length<=3&&C!==e&&J()})),null==T||T.addEventListener("search",(function(){J()}));var H=function(){T&&(T.value="",T.dispatchEvent(new Event("search")))};const D=window.wp.apiFetch;var R=n.n(D);const I=window.wp.url;var N=function(e,t,n,r){return void 0===n&&(n=1),void 0===r&&(r=100),o=void 0,c=void 0,u=function(){var o,c,l,u,s;return function(e,t){var n,r,o,a,i={label:0,sent:function(){if(1&o[0])throw o[1];return o[1]},trys:[],ops:[]};return a={next:c(0),throw:c(1),return:c(2)},"function"==typeof Symbol&&(a[Symbol.iterator]=function(){return this}),a;function c(c){return function(l){return function(c){if(n)throw new TypeError("Generator is already executing.");for(;a&&(a=0,c[0]&&(i=0)),i;)try{if(n=1,r&&(o=2&c[0]?r.return:c[0]?r.throw||((o=r.return)&&o.call(r),0):r.next)&&!(o=o.call(r,c[1])).done)return o;switch(r=0,o&&(c=[2&c[0],o.value]),c[0]){case 0:case 1:o=c;break;case 4:return i.label++,{value:c[1],done:!1};case 5:i.label++,r=c[1],c=[0];continue;case 7:c=i.ops.pop(),i.trys.pop();continue;default:if(!((o=(o=i.trys).length>0&&o[o.length-1])||6!==c[0]&&2!==c[0])){i=0;continue}if(3===c[0]&&(!o||c[1]>o[0]&&c[1]<o[3])){i.label=c[1];break}if(6===c[0]&&i.label<o[1]){i.label=o[1],o=c;break}if(o&&i.label<o[2]){i.label=o[2],i.ops.push(c);break}o[2]&&i.ops.pop(),i.trys.pop();continue}c=t.call(e,i)}catch(e){c=[6,e],r=0}finally{n=o=0}if(5&c[0])throw c[1];return{value:c[0]?c[1]:void 0,done:!0}}([c,l])}}}(this,(function(d){switch(d.label){case 0:return[4,R()({path:(0,I.addQueryArgs)(e,{page:n,per_page:r,lang:t}),parse:!1})];case 1:return[4,(o=d.sent()).json()];case 2:return c=d.sent(),l=parseInt(null!==(s=o.headers.get("X-WP-TotalPages"))&&void 0!==s?s:"1"),n<l?(u=[i([],a(c),!1)],[4,N(e,t,++n)]):[3,4];case 3:c=i.apply(void 0,u.concat([a.apply(void 0,[d.sent()]),!1])),d.label=4;case 4:return[2,c]}}))},new((l=void 0)||(l=Promise))((function(e,t){function n(e){try{a(u.next(e))}catch(e){t(e)}}function r(e){try{a(u.throw(e))}catch(e){t(e)}}function a(t){var o;t.done?e(t.value):(o=t.value,o instanceof l?o:new l((function(e){e(o)}))).then(n,r)}a((u=u.apply(o,c||[])).next())}));var o,c,l,u};const U=N;var W=["order","order_by"],F=document.querySelectorAll("a.c-degree-programs-collection__header-item");var B=".loader",V=document.querySelector(B),z=function(){return V||(document.body.insertAdjacentHTML("afterbegin",'\n        <div class="loader">\n            <div class="loader-icon"></div>\n        </div>\n    '),V=document.querySelector(B)),V},G=document.querySelector(".c-degree-programs-search form");null==G||G.addEventListener("submit",(function(e){e.preventDefault(),K()}));var K=function(){var e=new FormData(G),t=new URLSearchParams(e),n=new URLSearchParams(window.location.search);W.forEach((function(e){var r=n.get(e);r&&t.append(e,r)})),X("?".concat(t.toString()))},Q=null,X=function(e){void 0===e&&(e=""),z().classList.remove("hidden"),Q&&clearTimeout(Q),Q=setTimeout((function(){var t;(function(){if(T){var e,t,n,r=T.value.trim();e=O,t=r,n=!0,A().forEach((function(r){var o,i=((null===(o=r.textContent)||void 0===o?void 0:o.trim())||"").split(":").map((function(e){return e.trim()}));if(a(i,1)[0]===e){var c=a(i,2)[1];if(t!==c)return r.remove(),void k();n=!1}})),t&&n&&j("".concat(e,": ").concat(t))}})(),history.replaceState(null,"",e||window.location.pathname),t=e,F.forEach((function(e){var n=new URLSearchParams(t),r=a(e.href.split("?"),2)[1];if(r&&t){var o=a(e.href.split("?"),1)[0];new URLSearchParams(r).forEach((function(e,t){W.includes(t)&&n.set(t,e)})),e.href="".concat(o,"?").concat(n.toString())}})),U("/fau/v1/degree-program".concat(e),m).then((function(e){g(e)})).finally((function(){z().classList.add("hidden")})),Q=null}),500)};const J=K;document.querySelectorAll(".fau-dropdown").forEach((function(e){var t=e.querySelector(".fau-dropdown__toggle"),n=e.querySelector(".fau-dropdown__content");t&&n&&document.body.addEventListener("click",(function(n){t.contains(n.target)?function(e){var t="true"===e.getAttribute("aria-expanded");e.setAttribute("aria-expanded",t?"false":"true"),t&&J()}(e):"true"===e.getAttribute("aria-expanded")&&!e.contains(n.target)&&window.innerWidth>768&&function(e){e.setAttribute("aria-expanded","false"),J()}(e)}))})),n(498);var Y=G.querySelector(".c-active-search-filters__clear-all-button");null==Y||Y.addEventListener("click",(function(e){e.preventDefault(),A().forEach((function(e){e.dispatchEvent(new Event("click"))})),k()})),n(835),n(508),n(61)})()})();