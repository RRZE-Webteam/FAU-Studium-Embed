(()=>{"use strict";var e={564:(e,t)=>{Object.defineProperty(t,"__esModule",{value:!0}),t.CONTENT_HEIGHT_VAR=t.ACCORDION_BUTTON_SELECTOR=t.ACCORDION_ITEM_SELECTOR=void 0,t.ACCORDION_ITEM_SELECTOR=".c-accordion-item",t.ACCORDION_BUTTON_SELECTOR=".c-accordion-item__button",t.CONTENT_HEIGHT_VAR="--content-height"},99:(e,t,n)=>{Object.defineProperty(t,"__esModule",{value:!0});var o=n(564),r=function(e){var t;if("false"!==e.ariaExpanded){var n=document.getElementById(null!==(t=e.getAttribute("aria-controls"))&&void 0!==t?t:"");n instanceof HTMLElement&&(e.setAttribute("aria-expanded","false"),n.setAttribute("hidden","hidden"))}},a=function(e){var t=e.closest(o.ACCORDION_ITEM_SELECTOR);if(t instanceof HTMLElement){var n=t.parentElement;n instanceof HTMLElement&&("true"!==e.getAttribute("aria-expanded")?(!function(e){var t;if("true"!==e.ariaExpanded){var n=document.getElementById(null!==(t=e.getAttribute("aria-controls"))&&void 0!==t?t:"");n instanceof HTMLElement&&(n.style.getPropertyValue(o.CONTENT_HEIGHT_VAR)||n.style.setProperty(o.CONTENT_HEIGHT_VAR,"".concat(n.scrollHeight,"px")),e.setAttribute("aria-expanded","true"),n.removeAttribute("hidden"))}}(e),n.querySelectorAll("".concat(o.ACCORDION_BUTTON_SELECTOR,":not(#").concat(e.id,")")).forEach(r)):r(e))}};document.querySelectorAll(o.ACCORDION_BUTTON_SELECTOR).forEach((function(e){e.addEventListener("click",(function(){return a(e)}))}))},80:()=>{document.querySelectorAll(".fau-dropdown").forEach((function(e){var t=e.querySelector(".fau-dropdown__toggle"),n=e.querySelector(".fau-dropdown__content");t&&n&&document.body.addEventListener("click",(function(n){t.contains(n.target)?function(e){e.setAttribute("aria-expanded","true"===e.getAttribute("aria-expanded")?"false":"true")}(e):"true"===e.getAttribute("aria-expanded")&&!e.contains(n.target)&&window.innerWidth>768&&function(e){e.setAttribute("aria-expanded","false")}(e)}))}))},743:()=>{var e=function(e){var t=e.target,n=t.name;t.checked?function(e,t){var n=new URLSearchParams(window.location.search),o=n.get(e);if(o){var r=o.split(",");return r.push(t),n.set(e,r.join(",")),void(window.location.search=n.toString())}n.set(e,t),window.location.search=n.toString()}(n,t.value):function(e,t){var n=new URLSearchParams(window.location.search.toString()),o=n.get(e);if(o){var r=o.split(",").filter((function(e){return e!==t}));if(!r.length)return n.delete(e),void(window.location.search=n.toString());n.set(e,r.join(",")),window.location.search=n.toString()}}(n,t.value)};document.querySelectorAll(".c-filter-checkbox").forEach((function(t){t.querySelectorAll("input[type=checkbox]").forEach((function(t){t.addEventListener("change",e)}))}))},854:(e,t,n)=>{Object.defineProperty(t,"__esModule",{value:!0}),n(80),n(743),n(483)},483:()=>{document.addEventListener("DOMContentLoaded",(function(){document.querySelectorAll(".c-sort-selector select").forEach((function(e){e.addEventListener("change",(function(){window.location.href=e.value}))}))}))},401:()=>{var e=".search-filter__output_modes a",t="-active",n="-list",o="-tiles",r=function(r){var a=document.querySelector(".c-degree-programs-collection");a&&(document.querySelectorAll(e).forEach((function(e){var n=e;n.classList.remove(t),n.dataset.mode===r&&n.classList.add(t)})),a.classList.remove(n,o),a.classList.add("list"===r?n:o))};document.querySelectorAll(e).forEach((function(e){e.addEventListener("click",(function(t){t.preventDefault();var n=e.dataset.mode;n&&(r(n),function(e){var t=new URL(window.location.href);t.searchParams.set("output",e),window.history.pushState({outputMode:e},"",t)}(n))}))})),window.addEventListener("popstate",(function(e){var t=e.state.outputMode;t&&r(t)}))}},t={};function n(o){var r=t[o];if(void 0!==r)return r.exports;var a=t[o]={exports:{}};return e[o](a,a.exports,n),a.exports}n(99),n(854),n(401)})();