(()=>{"use strict";var e={9601:(e,t,r)=>{Object.defineProperty(t,"__esModule",{value:!0}),r(9571)},3112:(e,t)=>{Object.defineProperty(t,"__esModule",{value:!0});t.default=()=>window.fauDegreeProgramData.degreePrograms.length?window.fauDegreeProgramData.degreePrograms.map((e=>({value:e.id,text:`${e.title.de} (${e.title.en})`}))):[]},9571:(e,t,r)=>{Object.defineProperty(t,"__esModule",{value:!0}),r(1751)},4184:(e,t)=>{Object.defineProperty(t,"__esModule",{value:!0}),t.buildShortcode=t.PREFIX_EXCLUDE=t.PREFIX_INCLUDE=void 0,t.PREFIX_INCLUDE="include.",t.PREFIX_EXCLUDE="exclude.";t.buildShortcode=e=>{const r=(e=>{const r={display:"degree-program",id:0,lang:"de",format:"full",include:[],exclude:[]};return Object.entries(e).forEach((e=>{let[n,o]=e;n.startsWith(t.PREFIX_INCLUDE)?!0===o&&r.include.push(n.replace(t.PREFIX_INCLUDE,"")):n.startsWith(t.PREFIX_EXCLUDE)?!0===o&&r.exclude.push(n.replace(t.PREFIX_EXCLUDE,"")):n in r&&(r[n]=o)})),r})(e),n=[`display="${r.display}"`,`id="${r.id}"`,`lang="${r.lang}"`,`format="${r.format}"`];if(0===r.id)throw new Error("Select a degree program.");return"short"!==r.format&&(r.include.length>0&&n.push(`include="${r.include.join(",")}"`),0===r.include.length&&r.exclude.length>0&&n.push(`exclude="${r.exclude.join(",")}"`)),`[fau-studium ${n.join(" ")}]`}},2004:(e,t,r)=>{Object.defineProperty(t,"__esModule",{value:!0});const n=r(655).__importDefault(r(3112)),o=r(4184),a=e=>{const{degreeProgramFields:t}=window.fauDegreeProgramData.i18n;return Object.entries(t).map((t=>{let[r,n]=t;return{name:`${e}${r}`,type:"checkbox",text:n}}))},i=(e,t)=>{const r=window.fauDegreeProgramData.i18n.formFields;e.windowManager.open({title:r.title,body:{type:"container",layout:"flex",style:"max-height: 85vh; overflow-x: hidden; overflow-y: auto;",padding:"10 10 10 10",items:[{type:"form",layout:"flex",direction:"column",align:"stretch",items:[{name:"id",type:"listbox",values:t,label:r.degreeProgram},{name:"lang",type:"listbox",values:[{value:"de",text:"Deutsch"},{value:"en",text:"English"}],label:r.language},{name:"format",type:"listbox",values:[{value:"short",text:"Short"},{value:"full",text:"Full"}],label:r.format},{type:"label",text:r.includeExcludeIgnoredNotice,style:"font-style: italic"},{type:"container",layout:"grid",columns:2,spacing:5,items:[{type:"container",layout:"flex",direction:"column",spacing:5,items:[{type:"label",text:r.include},...a(o.PREFIX_INCLUDE)]},{type:"container",layout:"flex",direction:"column",spacing:5,items:[{type:"label",text:r.exclude},{type:"label",text:r.excludeIgnoredNotice,style:"font-style: italic"},...a(o.PREFIX_EXCLUDE)]}]}]}]},onSubmit:r=>{try{e.insertContent((0,o.buildShortcode)(r.data))}catch(r){e.windowManager.alert(r.message,(()=>{i(e,t)}))}}},{})};t.default=e=>{const t=(0,n.default)();t.unshift({value:0,text:""}),i(e,t)}},1751:(e,t,r)=>{Object.defineProperty(t,"__esModule",{value:!0});const n=r(655).__importDefault(r(2004));var o;(o=window.tinymce).create("tinymce.plugins.fau_degree_program_output_shortcodes_plugin",{init:e=>{e.addButton("fau_degree_program_output_shortcodes",{title:"FAU Degree Program Shortcodes",icon:"is-dashicon dashicons dashicons-shortcode",onclick:()=>(0,n.default)(e),onPostRender:()=>{jQuery(".mce-i-is-dashicon").css("font-family","dashicons")}})}}),o.PluginManager.add("fau_degree_program_output_shortcodes_plugin",o.plugins.fau_degree_program_output_shortcodes_plugin)},655:(e,t,r)=>{r.r(t),r.d(t,{__assign:()=>a,__asyncDelegator:()=>j,__asyncGenerator:()=>O,__asyncValues:()=>E,__await:()=>x,__awaiter:()=>y,__classPrivateFieldGet:()=>R,__classPrivateFieldIn:()=>C,__classPrivateFieldSet:()=>T,__createBinding:()=>_,__decorate:()=>l,__esDecorate:()=>u,__exportStar:()=>b,__extends:()=>o,__generator:()=>h,__importDefault:()=>F,__importStar:()=>I,__makeTemplateObject:()=>S,__metadata:()=>p,__param:()=>c,__propKey:()=>s,__read:()=>v,__rest:()=>i,__runInitializers:()=>f,__setFunctionName:()=>d,__spread:()=>m,__spreadArray:()=>P,__spreadArrays:()=>w,__values:()=>g});var n=function(e,t){return n=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(e,t){e.__proto__=t}||function(e,t){for(var r in t)Object.prototype.hasOwnProperty.call(t,r)&&(e[r]=t[r])},n(e,t)};function o(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Class extends value "+String(t)+" is not a constructor or null");function r(){this.constructor=e}n(e,t),e.prototype=null===t?Object.create(t):(r.prototype=t.prototype,new r)}var a=function(){return a=Object.assign||function(e){for(var t,r=1,n=arguments.length;r<n;r++)for(var o in t=arguments[r])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e},a.apply(this,arguments)};function i(e,t){var r={};for(var n in e)Object.prototype.hasOwnProperty.call(e,n)&&t.indexOf(n)<0&&(r[n]=e[n]);if(null!=e&&"function"==typeof Object.getOwnPropertySymbols){var o=0;for(n=Object.getOwnPropertySymbols(e);o<n.length;o++)t.indexOf(n[o])<0&&Object.prototype.propertyIsEnumerable.call(e,n[o])&&(r[n[o]]=e[n[o]])}return r}function l(e,t,r,n){var o,a=arguments.length,i=a<3?t:null===n?n=Object.getOwnPropertyDescriptor(t,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)i=Reflect.decorate(e,t,r,n);else for(var l=e.length-1;l>=0;l--)(o=e[l])&&(i=(a<3?o(i):a>3?o(t,r,i):o(t,r))||i);return a>3&&i&&Object.defineProperty(t,r,i),i}function c(e,t){return function(r,n){t(r,n,e)}}function u(e,t,r,n,o,a){function i(e){if(void 0!==e&&"function"!=typeof e)throw new TypeError("Function expected");return e}for(var l,c=n.kind,u="getter"===c?"get":"setter"===c?"set":"value",f=!t&&e?n.static?e:e.prototype:null,s=t||(f?Object.getOwnPropertyDescriptor(f,n.name):{}),d=!1,p=r.length-1;p>=0;p--){var y={};for(var h in n)y[h]="access"===h?{}:n[h];for(var h in n.access)y.access[h]=n.access[h];y.addInitializer=function(e){if(d)throw new TypeError("Cannot add initializers after decoration has completed");a.push(i(e||null))};var _=(0,r[p])("accessor"===c?{get:s.get,set:s.set}:s[u],y);if("accessor"===c){if(void 0===_)continue;if(null===_||"object"!=typeof _)throw new TypeError("Object expected");(l=i(_.get))&&(s.get=l),(l=i(_.set))&&(s.set=l),(l=i(_.init))&&o.push(l)}else(l=i(_))&&("field"===c?o.push(l):s[u]=l)}f&&Object.defineProperty(f,n.name,s),d=!0}function f(e,t,r){for(var n=arguments.length>2,o=0;o<t.length;o++)r=n?t[o].call(e,r):t[o].call(e);return n?r:void 0}function s(e){return"symbol"==typeof e?e:"".concat(e)}function d(e,t,r){return"symbol"==typeof t&&(t=t.description?"[".concat(t.description,"]"):""),Object.defineProperty(e,"name",{configurable:!0,value:r?"".concat(r," ",t):t})}function p(e,t){if("object"==typeof Reflect&&"function"==typeof Reflect.metadata)return Reflect.metadata(e,t)}function y(e,t,r,n){return new(r||(r=Promise))((function(o,a){function i(e){try{c(n.next(e))}catch(e){a(e)}}function l(e){try{c(n.throw(e))}catch(e){a(e)}}function c(e){var t;e.done?o(e.value):(t=e.value,t instanceof r?t:new r((function(e){e(t)}))).then(i,l)}c((n=n.apply(e,t||[])).next())}))}function h(e,t){var r,n,o,a,i={label:0,sent:function(){if(1&o[0])throw o[1];return o[1]},trys:[],ops:[]};return a={next:l(0),throw:l(1),return:l(2)},"function"==typeof Symbol&&(a[Symbol.iterator]=function(){return this}),a;function l(l){return function(c){return function(l){if(r)throw new TypeError("Generator is already executing.");for(;a&&(a=0,l[0]&&(i=0)),i;)try{if(r=1,n&&(o=2&l[0]?n.return:l[0]?n.throw||((o=n.return)&&o.call(n),0):n.next)&&!(o=o.call(n,l[1])).done)return o;switch(n=0,o&&(l=[2&l[0],o.value]),l[0]){case 0:case 1:o=l;break;case 4:return i.label++,{value:l[1],done:!1};case 5:i.label++,n=l[1],l=[0];continue;case 7:l=i.ops.pop(),i.trys.pop();continue;default:if(!(o=i.trys,(o=o.length>0&&o[o.length-1])||6!==l[0]&&2!==l[0])){i=0;continue}if(3===l[0]&&(!o||l[1]>o[0]&&l[1]<o[3])){i.label=l[1];break}if(6===l[0]&&i.label<o[1]){i.label=o[1],o=l;break}if(o&&i.label<o[2]){i.label=o[2],i.ops.push(l);break}o[2]&&i.ops.pop(),i.trys.pop();continue}l=t.call(e,i)}catch(e){l=[6,e],n=0}finally{r=o=0}if(5&l[0])throw l[1];return{value:l[0]?l[1]:void 0,done:!0}}([l,c])}}}var _=Object.create?function(e,t,r,n){void 0===n&&(n=r);var o=Object.getOwnPropertyDescriptor(t,r);o&&!("get"in o?!t.__esModule:o.writable||o.configurable)||(o={enumerable:!0,get:function(){return t[r]}}),Object.defineProperty(e,n,o)}:function(e,t,r,n){void 0===n&&(n=r),e[n]=t[r]};function b(e,t){for(var r in e)"default"===r||Object.prototype.hasOwnProperty.call(t,r)||_(t,e,r)}function g(e){var t="function"==typeof Symbol&&Symbol.iterator,r=t&&e[t],n=0;if(r)return r.call(e);if(e&&"number"==typeof e.length)return{next:function(){return e&&n>=e.length&&(e=void 0),{value:e&&e[n++],done:!e}}};throw new TypeError(t?"Object is not iterable.":"Symbol.iterator is not defined.")}function v(e,t){var r="function"==typeof Symbol&&e[Symbol.iterator];if(!r)return e;var n,o,a=r.call(e),i=[];try{for(;(void 0===t||t-- >0)&&!(n=a.next()).done;)i.push(n.value)}catch(e){o={error:e}}finally{try{n&&!n.done&&(r=a.return)&&r.call(a)}finally{if(o)throw o.error}}return i}function m(){for(var e=[],t=0;t<arguments.length;t++)e=e.concat(v(arguments[t]));return e}function w(){for(var e=0,t=0,r=arguments.length;t<r;t++)e+=arguments[t].length;var n=Array(e),o=0;for(t=0;t<r;t++)for(var a=arguments[t],i=0,l=a.length;i<l;i++,o++)n[o]=a[i];return n}function P(e,t,r){if(r||2===arguments.length)for(var n,o=0,a=t.length;o<a;o++)!n&&o in t||(n||(n=Array.prototype.slice.call(t,0,o)),n[o]=t[o]);return e.concat(n||Array.prototype.slice.call(t))}function x(e){return this instanceof x?(this.v=e,this):new x(e)}function O(e,t,r){if(!Symbol.asyncIterator)throw new TypeError("Symbol.asyncIterator is not defined.");var n,o=r.apply(e,t||[]),a=[];return n={},i("next"),i("throw"),i("return"),n[Symbol.asyncIterator]=function(){return this},n;function i(e){o[e]&&(n[e]=function(t){return new Promise((function(r,n){a.push([e,t,r,n])>1||l(e,t)}))})}function l(e,t){try{(r=o[e](t)).value instanceof x?Promise.resolve(r.value.v).then(c,u):f(a[0][2],r)}catch(e){f(a[0][3],e)}var r}function c(e){l("next",e)}function u(e){l("throw",e)}function f(e,t){e(t),a.shift(),a.length&&l(a[0][0],a[0][1])}}function j(e){var t,r;return t={},n("next"),n("throw",(function(e){throw e})),n("return"),t[Symbol.iterator]=function(){return this},t;function n(n,o){t[n]=e[n]?function(t){return(r=!r)?{value:x(e[n](t)),done:!1}:o?o(t):t}:o}}function E(e){if(!Symbol.asyncIterator)throw new TypeError("Symbol.asyncIterator is not defined.");var t,r=e[Symbol.asyncIterator];return r?r.call(e):(e=g(e),t={},n("next"),n("throw"),n("return"),t[Symbol.asyncIterator]=function(){return this},t);function n(r){t[r]=e[r]&&function(t){return new Promise((function(n,o){(function(e,t,r,n){Promise.resolve(n).then((function(t){e({value:t,done:r})}),t)})(n,o,(t=e[r](t)).done,t.value)}))}}}function S(e,t){return Object.defineProperty?Object.defineProperty(e,"raw",{value:t}):e.raw=t,e}var D=Object.create?function(e,t){Object.defineProperty(e,"default",{enumerable:!0,value:t})}:function(e,t){e.default=t};function I(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var r in e)"default"!==r&&Object.prototype.hasOwnProperty.call(e,r)&&_(t,e,r);return D(t,e),t}function F(e){return e&&e.__esModule?e:{default:e}}function R(e,t,r,n){if("a"===r&&!n)throw new TypeError("Private accessor was defined without a getter");if("function"==typeof t?e!==t||!n:!t.has(e))throw new TypeError("Cannot read private member from an object whose class did not declare it");return"m"===r?n:"a"===r?n.call(e):n?n.value:t.get(e)}function T(e,t,r,n,o){if("m"===n)throw new TypeError("Private method is not writable");if("a"===n&&!o)throw new TypeError("Private accessor was defined without a setter");if("function"==typeof t?e!==t||!o:!t.has(e))throw new TypeError("Cannot write private member to an object whose class did not declare it");return"a"===n?o.call(e,r):o?o.value=r:t.set(e,r),r}function C(e,t){if(null===t||"object"!=typeof t&&"function"!=typeof t)throw new TypeError("Cannot use 'in' operator on non-object");return"function"==typeof e?t===e:e.has(t)}}},t={};function r(n){var o=t[n];if(void 0!==o)return o.exports;var a=t[n]={exports:{}};return e[n](a,a.exports,r),a.exports}r.d=(e,t)=>{for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};r(9601)})();