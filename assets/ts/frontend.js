/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/ts/accordion/constants.ts":
/*!*********************************************!*\
  !*** ./resources/ts/accordion/constants.ts ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ACCORDION_BUTTON_SELECTOR: () => (/* binding */ ACCORDION_BUTTON_SELECTOR),
/* harmony export */   ACCORDION_ITEM_SELECTOR: () => (/* binding */ ACCORDION_ITEM_SELECTOR),
/* harmony export */   CONTENT_HEIGHT_VAR: () => (/* binding */ CONTENT_HEIGHT_VAR)
/* harmony export */ });
var ACCORDION_ITEM_SELECTOR = '.c-accordion-item';
var ACCORDION_BUTTON_SELECTOR = '.c-accordion-item__button';
var CONTENT_HEIGHT_VAR = '--content-height';

/***/ }),

/***/ "./resources/ts/accordion/index.ts":
/*!*****************************************!*\
  !*** ./resources/ts/accordion/index.ts ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./constants */ "./resources/ts/accordion/constants.ts");

var open = function (button) {
  var _a;
  if (button.ariaExpanded === 'true') {
    return;
  }
  var content = document.getElementById((_a = button.getAttribute('aria-controls')) !== null && _a !== void 0 ? _a : '');
  if (!(content instanceof HTMLElement)) {
    return;
  }
  if (!content.style.getPropertyValue(_constants__WEBPACK_IMPORTED_MODULE_0__.CONTENT_HEIGHT_VAR)) {
    content.style.setProperty(_constants__WEBPACK_IMPORTED_MODULE_0__.CONTENT_HEIGHT_VAR, "".concat(content.scrollHeight, "px"));
  }
  button.setAttribute('aria-expanded', 'true');
  content.removeAttribute('hidden');
};
var close = function (button) {
  var _a;
  if (button.ariaExpanded === 'false') {
    return;
  }
  var content = document.getElementById((_a = button.getAttribute('aria-controls')) !== null && _a !== void 0 ? _a : '');
  if (!(content instanceof HTMLElement)) {
    return;
  }
  button.setAttribute('aria-expanded', 'false');
  content.setAttribute('hidden', 'hidden');
};
var onClickButton = function (button) {
  var accordionItem = button.closest(_constants__WEBPACK_IMPORTED_MODULE_0__.ACCORDION_ITEM_SELECTOR);
  if (!(accordionItem instanceof HTMLElement)) {
    return;
  }
  var accordion = accordionItem.parentElement;
  if (!(accordion instanceof HTMLElement)) {
    return;
  }
  if (button.getAttribute('aria-expanded') === 'true') {
    close(button);
    return;
  }
  open(button);
  accordion.querySelectorAll("".concat(_constants__WEBPACK_IMPORTED_MODULE_0__.ACCORDION_BUTTON_SELECTOR, ":not(#").concat(button.id, ")")).forEach(close);
};
var initAccordion = function () {
  document.querySelectorAll(_constants__WEBPACK_IMPORTED_MODULE_0__.ACCORDION_BUTTON_SELECTOR).forEach(function (button) {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      onClickButton(button);
    });
  });
};
initAccordion();

/***/ }),

/***/ "./resources/ts/common/browser-detection.ts":
/*!**************************************************!*\
  !*** ./resources/ts/common/browser-detection.ts ***!
  \**************************************************/
/***/ (() => {



var isSafariBrowser = function () {
  return navigator.userAgent.indexOf('Safari') > -1 && navigator.userAgent.indexOf('Chrome') <= -1;
};
if (isSafariBrowser()) {
  document.documentElement.classList.add('is-safari');
}

/***/ }),

/***/ "./resources/ts/common/index.ts":
/*!**************************************!*\
  !*** ./resources/ts/common/index.ts ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _browser_detection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./browser-detection */ "./resources/ts/common/browser-detection.ts");
/* harmony import */ var _browser_detection__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_browser_detection__WEBPACK_IMPORTED_MODULE_0__);


/***/ }),

/***/ "./resources/ts/common/reduced-motion-detection.ts":
/*!*********************************************************!*\
  !*** ./resources/ts/common/reduced-motion-detection.ts ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function () {
  return window.matchMedia('(prefers-reduced-motion)').matches;
});

/***/ }),

/***/ "./resources/ts/degree-program-overview/degree-program-overview.ts":
/*!*************************************************************************!*\
  !*** ./resources/ts/degree-program-overview/degree-program-overview.ts ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   currentLanguage: () => (/* binding */ currentLanguage),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   updateDegreeProgramOverviewDataset: () => (/* binding */ updateDegreeProgramOverviewDataset)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.mjs");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _degree_program__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./degree-program */ "./resources/ts/degree-program-overview/degree-program.ts");
var _a;



var DEGREE_PROGRAMS_SECTION_SELECTOR = '.c-degree-programs-search';
var DEGREE_PROGRAMS_OVERVIEW_SELECTOR = '.c-degree-programs-collection';
var SINGLE_PROGRAM_PREVIEW_SELECTOR = '.c-degree-program-preview';
var NO_SEARCH_RESULT_SELECTOR = '.c-no-search-results';
var degreeProgramsSection = document.querySelector(DEGREE_PROGRAMS_SECTION_SELECTOR);
var degreeProgramsOverview = degreeProgramsSection === null || degreeProgramsSection === void 0 ? void 0 : degreeProgramsSection.querySelector(DEGREE_PROGRAMS_OVERVIEW_SELECTOR);
var currentLanguage = ((_a = degreeProgramsSection === null || degreeProgramsSection === void 0 ? void 0 : degreeProgramsSection.getAttribute('lang')) === null || _a === void 0 ? void 0 : _a.substring(0, 2)) || 'de';
var renderPrograms = function (programs) {
  var output = programs.map(function (program) {
    return program.render();
  }).join('');
  degreeProgramsOverview === null || degreeProgramsOverview === void 0 ? void 0 : degreeProgramsOverview.insertAdjacentHTML('beforeend', output);
};
var renderNoResults = function () {
  var output = "\n\t\t<p class=\"c-no-search-results\">\n\t\t\t".concat((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x)('No degree programs found', 'frontoffice: Search results', 'fau-degree-program-output'), "\n\t\t</p>");
  degreeProgramsSection === null || degreeProgramsSection === void 0 ? void 0 : degreeProgramsSection.insertAdjacentHTML('beforeend', output);
};
var showNoResults = function () {
  var noResults = degreeProgramsSection === null || degreeProgramsSection === void 0 ? void 0 : degreeProgramsSection.querySelector(NO_SEARCH_RESULT_SELECTOR);
  if (!noResults) {
    renderNoResults();
    return;
  }
  noResults.classList.remove('hidden');
};
var hideNoResults = function () {
  var noResults = degreeProgramsSection === null || degreeProgramsSection === void 0 ? void 0 : degreeProgramsSection.querySelector(NO_SEARCH_RESULT_SELECTOR);
  if (!noResults) {
    return;
  }
  noResults.classList.add('hidden');
};
var updateDegreeProgramOverviewDataset = function (dataset) {
  if (!(degreeProgramsOverview instanceof HTMLElement)) {
    return;
  }
  Object.entries(dataset).forEach(function (_a) {
    var _b = (0,tslib__WEBPACK_IMPORTED_MODULE_2__.__read)(_a, 2),
      property = _b[0],
      value = _b[1];
    degreeProgramsOverview.dataset[property] = value;
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  var _a;
  degreeProgramsOverview === null || degreeProgramsOverview === void 0 ? void 0 : degreeProgramsOverview.setAttribute('aria-busy', 'true');
  (_a = degreeProgramsOverview === null || degreeProgramsOverview === void 0 ? void 0 : degreeProgramsOverview.querySelectorAll(SINGLE_PROGRAM_PREVIEW_SELECTOR)) === null || _a === void 0 ? void 0 : _a.forEach(function (element) {
    return element.remove();
  });
  var programs = data.map(function (programData) {
    return _degree_program__WEBPACK_IMPORTED_MODULE_1__["default"].createDegreeProgram(programData);
  });
  if (!programs.length) {
    showNoResults();
    degreeProgramsOverview === null || degreeProgramsOverview === void 0 ? void 0 : degreeProgramsOverview.setAttribute('aria-busy', 'false');
    return;
  }
  hideNoResults();
  renderPrograms(programs);
  degreeProgramsOverview === null || degreeProgramsOverview === void 0 ? void 0 : degreeProgramsOverview.setAttribute('aria-busy', 'false');
});

/***/ }),

/***/ "./resources/ts/degree-program-overview/degree-program.ts":
/*!****************************************************************!*\
  !*** ./resources/ts/degree-program-overview/degree-program.ts ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_settings__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/settings */ "./resources/ts/utils/settings.ts");


var DegreeProgram = function () {
  function DegreeProgram(_a) {
    var id = _a.id,
      image = _a.image,
      url = _a.url,
      title = _a.title,
      degree = _a.degree,
      semester = _a.semester,
      location = _a.location,
      admissionRequirements = _a.admissionRequirements,
      germanLanguageSkills = _a.germanLanguageSkills;
    this.id = id;
    this.image = image;
    this.url = url;
    this.title = title;
    this.degree = degree;
    this.semester = semester;
    this.location = location;
    this.admissionRequirements = admissionRequirements;
    this.germanLanguageSkills = germanLanguageSkills;
  }
  DegreeProgram.createDegreeProgram = function (program) {
    var _a;
    return new DegreeProgram({
      id: program.id,
      title: program.title,
      degree: program.degree,
      image: program.teaser_image.rendered || _utils_settings__WEBPACK_IMPORTED_MODULE_1__["default"].icon_degree,
      url: program.link,
      location: program.location,
      semester: program.start,
      admissionRequirements: (_a = program.admission_requirement_link) === null || _a === void 0 ? void 0 : _a.name,
      germanLanguageSkills: program.german_language_skills_for_international_students.name
    });
  };
  DegreeProgram.prototype.render = function () {
    return "\n\t\t\t<li class=\"c-degree-program-preview\">\n\t\t\t\t<div class=\"c-degree-program-preview__teaser-image\">\n\t\t\t\t\t".concat(this.image, "\n\t\t\t\t</div>\n\t\t\t\t<div class=\"c-degree-program-preview__title\">\n\t\t\t\t\t<a class=\"c-degree-program-preview__link\" href=\"").concat(this.url, "\" rel=\"bookmark\" aria-labelledby=\"degree-program-title-").concat(this.id, "\"></a>\n\t\t\t\t\t<div id=\"degree-program-title-").concat(this.id, "\">\n\t\t\t\t\t\t").concat(this.title, " (<abbr title=\"").concat(this.degree.name, "\">").concat(this.degree.abbreviation, "</abbr>)\n\t\t\t\t\t\t<div class=\"c-degree-program-preview__subtitle\"></div>\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t\t<div class=\"c-degree-program-preview__degree\">\n\t\t\t\t\t<span class=\"c-degree-program-preview__label\">\n\t\t\t\t\t\t").concat((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x)('Type', 'frontoffice: degree-programs-overview', 'fau-degree-program-output'), ":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.degree.name, "\n\t\t\t\t</div>\n\t\t\t\t<div class=\"c-degree-program-preview__start\">\n\t\t\t\t\t<span class=\"c-degree-program-preview__label\">\n\t\t\t\t\t\t").concat((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x)('Start', 'frontoffice: degree-programs-overview', 'fau-degree-program-output'), ":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.semester.join(', '), "\n\t\t\t\t</div>\n\t\t\t\t<div class=\"c-degree-program-preview__location\">\n\t\t\t\t\t<span class=\"c-degree-program-preview__label\">\n\t\t\t\t\t\t").concat((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x)('Location', 'frontoffice: degree-programs-overview', 'fau-degree-program-output'), ":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.location.join(', '), "\n\t\t\t\t</div>\n\t\t\t\t<div class=\"c-degree-program-preview__admission-requirement\">\n\t\t\t\t\t<span class=\"c-degree-program-preview__label\">\n\t\t\t\t\t\t").concat((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x)('NC', 'frontoffice: degree-programs-overview', 'fau-degree-program-output'), ":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.admissionRequirements ? this.admissionRequirements : '', "\n\t\t\t\t</div>\n\t\t\t\t<div class=\"c-degree-program-preview__language-certificates\">\n\t\t\t\t\t<span class=\"c-degree-program-preview__label\">\n\t\t\t\t\t\t").concat((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x)('Language certificates', 'frontoffice: degree-programs-overview', 'fau-degree-program-output'), ":\n\t\t\t\t\t</span>\n\t\t\t\t\t").concat(this.germanLanguageSkills, "\n\t\t\t\t</div>\n\t\t\t</li>\n\t\t");
  };
  return DegreeProgram;
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DegreeProgram);

/***/ }),

/***/ "./resources/ts/filters/active-filter-label.ts":
/*!*****************************************************!*\
  !*** ./resources/ts/filters/active-filter-label.ts ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var ACCORDION_ITEM_TITLE_SELECTOR = '.c-accordion-item__title';
var ADVANCED_FILTER_SELECTOR = '.c-advanced-filter-item';
var FILTER_DROPDOWN_SELECTOR = '.c-filter-dropdown';
var FILTER_DROPDOWN_LABEL_SELECTOR = '.c-filter-dropdown__label';
var buildActiveFilterLabel = function (filterControl, checkbox) {
  var _a, _b, _c, _d, _e, _f;
  var labelText = (_b = (_a = filterControl.querySelector('span')) === null || _a === void 0 ? void 0 : _a.textContent) === null || _b === void 0 ? void 0 : _b.trim();
  var filter = checkbox.closest(ADVANCED_FILTER_SELECTOR);
  var filterLabel;
  if (filter) {
    filterLabel = (_d = (_c = filter.querySelector(ACCORDION_ITEM_TITLE_SELECTOR)) === null || _c === void 0 ? void 0 : _c.textContent) === null || _d === void 0 ? void 0 : _d.trim();
    return "".concat(filterLabel, ": ").concat(labelText);
  }
  filter = checkbox.closest(FILTER_DROPDOWN_SELECTOR);
  filterLabel = (_f = (_e = filter === null || filter === void 0 ? void 0 : filter.querySelector(FILTER_DROPDOWN_LABEL_SELECTOR)) === null || _e === void 0 ? void 0 : _e.textContent) === null || _f === void 0 ? void 0 : _f.trim();
  return "".concat(filterLabel, ": ").concat(labelText);
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (buildActiveFilterLabel);

/***/ }),

/***/ "./resources/ts/filters/active-filters.ts":
/*!************************************************!*\
  !*** ./resources/ts/filters/active-filters.ts ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   clearActiveFilters: () => (/* binding */ clearActiveFilters),
/* harmony export */   toggleActiveFilter: () => (/* binding */ toggleActiveFilter),
/* harmony export */   toggleSingleActiveFilter: () => (/* binding */ toggleSingleActiveFilter)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.mjs");
/* harmony import */ var _filters_handler__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./filters-handler */ "./resources/ts/filters/filters-handler.ts");
/* harmony import */ var _utils_settings__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/settings */ "./resources/ts/utils/settings.ts");
/* harmony import */ var _active_filter_label__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./active-filter-label */ "./resources/ts/filters/active-filter-label.ts");




var ACTIVE_FILTERS_SECTION_SELECTOR = '.c-active-search-filters';
var ACTIVE_FILTERS_LIST_SELECTOR = '.c-active-search-filters__list';
var ACTIVE_FILTER_CLASS = 'c-active-search-filters__item';
var ACTIVE_FILTER_SELECTOR = ".".concat(ACTIVE_FILTER_CLASS);
var activeFiltersSection = document.querySelector(ACTIVE_FILTERS_SECTION_SELECTOR);
var activeFiltersList = document.querySelector(ACTIVE_FILTERS_LIST_SELECTOR);
var activeFilters = function () {
  return document.querySelectorAll(ACTIVE_FILTER_SELECTOR);
};
var maybeHideFiltersSection = function () {
  if (activeFilters().length !== 0) {
    return;
  }
  activeFiltersSection === null || activeFiltersSection === void 0 ? void 0 : activeFiltersSection.classList.add('hidden');
};
var filterEventListener = function (e) {
  var _a;
  e.preventDefault();
  var filter = e.target;
  (0,_filters_handler__WEBPACK_IMPORTED_MODULE_0__.resetRelatedInput)(((_a = filter.textContent) === null || _a === void 0 ? void 0 : _a.trim()) || '');
  filter.remove();
  maybeHideFiltersSection();
};
activeFilters().forEach(function (filter) {
  filter.addEventListener('click', filterEventListener);
});
var addActiveFilter = function (label) {
  var filter = document.createElement('a');
  filter.insertAdjacentHTML('beforeend', "\n\t\t\t".concat(_utils_settings__WEBPACK_IMPORTED_MODULE_1__["default"].icon_close, "\n\t\t\t").concat(label, "\n\t\t"));
  filter.className = ACTIVE_FILTER_CLASS;
  filter.addEventListener('click', filterEventListener);
  activeFiltersList === null || activeFiltersList === void 0 ? void 0 : activeFiltersList.insertAdjacentElement('beforeend', filter);
  activeFiltersSection === null || activeFiltersSection === void 0 ? void 0 : activeFiltersSection.classList.remove('hidden');
};
var removeActiveFilter = function (label) {
  activeFilters().forEach(function (filter) {
    var _a;
    if (((_a = filter.textContent) === null || _a === void 0 ? void 0 : _a.trim()) === label) {
      filter.remove();
    }
  });
  maybeHideFiltersSection();
};
var toggleActiveFilter = function (filterControl, checkbox) {
  var label = (0,_active_filter_label__WEBPACK_IMPORTED_MODULE_2__["default"])(filterControl, checkbox);
  if (checkbox.checked) {
    addActiveFilter(label);
    return;
  }
  removeActiveFilter(label);
};
var toggleSingleActiveFilter = function (key, value) {
  var needCreateNewFilter = true;
  activeFilters().forEach(function (filter) {
    var _a;
    var content = (((_a = filter.textContent) === null || _a === void 0 ? void 0 : _a.trim()) || '').split(':').map(function (item) {
      return item.trim();
    });
    var _b = (0,tslib__WEBPACK_IMPORTED_MODULE_3__.__read)(content, 1),
      filterKey = _b[0];
    if (filterKey !== key) {
      return;
    }
    var _c = (0,tslib__WEBPACK_IMPORTED_MODULE_3__.__read)(content, 2),
      filterValue = _c[1];
    if (value !== filterValue) {
      filter.remove();
      maybeHideFiltersSection();
      return;
    }
    needCreateNewFilter = false;
  });
  if (value && needCreateNewFilter) {
    addActiveFilter("".concat(key, ": ").concat(value));
  }
};
var clearActiveFilters = function () {
  activeFilters().forEach(function (filter) {
    filter.dispatchEvent(new Event('click'));
  });
  maybeHideFiltersSection();
};

/***/ }),

/***/ "./resources/ts/filters/filter-dropdown.ts":
/*!*************************************************!*\
  !*** ./resources/ts/filters/filter-dropdown.ts ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _form_form_handler__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../form/form-handler */ "./resources/ts/form/form-handler.ts");

var DROPDOWN_SELECTOR = '.fau-dropdown';
var DROPDOWN_TOGGLE_SELECTOR = '.fau-dropdown__toggle';
var DROPDOWN_CONTENT_SELECTOR = '.fau-dropdown__content';
var CLICKAWAY_WINDOW_WIDTH_THRESHOLD = 768;
var closeDropDown = function (dropdown) {
  dropdown.setAttribute('aria-expanded', 'false');
  (0,_form_form_handler__WEBPACK_IMPORTED_MODULE_0__["default"])();
};
var toggleDropdown = function (dropdown) {
  var isAriaExpanded = dropdown.getAttribute('aria-expanded') === 'true';
  dropdown.setAttribute('aria-expanded', isAriaExpanded ? 'false' : 'true');
  if (isAriaExpanded) {
    (0,_form_form_handler__WEBPACK_IMPORTED_MODULE_0__["default"])();
  }
};
var registerClickListeners = function () {
  document.querySelectorAll(DROPDOWN_SELECTOR).forEach(function (dropdown) {
    var toggle = dropdown.querySelector(DROPDOWN_TOGGLE_SELECTOR);
    var content = dropdown.querySelector(DROPDOWN_CONTENT_SELECTOR);
    if (!toggle || !content) {
      return;
    }
    document.body.addEventListener('click', function (event) {
      if (toggle.contains(event.target)) {
        toggleDropdown(dropdown);
        return;
      }
      if (dropdown.getAttribute('aria-expanded') === 'true' && !dropdown.contains(event.target) && window.innerWidth > CLICKAWAY_WINDOW_WIDTH_THRESHOLD) {
        closeDropDown(dropdown);
      }
    });
  });
};
registerClickListeners();

/***/ }),

/***/ "./resources/ts/filters/filters-count.ts":
/*!***********************************************!*\
  !*** ./resources/ts/filters/filters-count.ts ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var FILTER_DROPDOWN_SELECTOR = '.c-filter-dropdown';
var FILTER_DROPDOWN_LABEL_SELECTOR = '.c-filter-dropdown__label';
var FILTER_COUNT_CLASS = 'c-filter-dropdown__count';
var FILTER_COUNT_SELECTOR = ".".concat(FILTER_COUNT_CLASS);
var updateFiltersCount = function (element) {
  var _a;
  var filter = element.closest(FILTER_DROPDOWN_SELECTOR);
  var filterLabel = filter === null || filter === void 0 ? void 0 : filter.querySelector(FILTER_DROPDOWN_LABEL_SELECTOR);
  if (!filter || !filterLabel) {
    return;
  }
  var count = filter.querySelector(FILTER_COUNT_SELECTOR);
  if (!count) {
    count = document.createElement('span');
    count.className = FILTER_COUNT_CLASS;
    filterLabel.insertAdjacentElement('afterend', count);
  }
  var value = Number(((_a = count.textContent) === null || _a === void 0 ? void 0 : _a.trim()) || '0');
  value += element.checked ? 1 : -1;
  if (value < 1) {
    count.remove();
    return;
  }
  count.textContent = String(value);
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (updateFiltersCount);

/***/ }),

/***/ "./resources/ts/filters/filters-handler.ts":
/*!*************************************************!*\
  !*** ./resources/ts/filters/filters-handler.ts ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   LANGUAGE_SKILLS_INPUT: () => (/* binding */ LANGUAGE_SKILLS_INPUT),
/* harmony export */   resetRelatedInput: () => (/* binding */ resetRelatedInput)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.mjs");
/* harmony import */ var _common_reduced_motion_detection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/reduced-motion-detection */ "./resources/ts/common/reduced-motion-detection.ts");
/* harmony import */ var _form_input_handler__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../form/input-handler */ "./resources/ts/form/input-handler.ts");
/* harmony import */ var _active_filters__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./active-filters */ "./resources/ts/filters/active-filters.ts");
/* harmony import */ var _filters_count__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./filters-count */ "./resources/ts/filters/filters-count.ts");
/* harmony import */ var _form_form_handler__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../form/form-handler */ "./resources/ts/form/form-handler.ts");
/* harmony import */ var _degree_program_overview_degree_program_overview__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../degree-program-overview/degree-program-overview */ "./resources/ts/degree-program-overview/degree-program-overview.ts");







var FILTER_SELECTOR = '.c-filter-checkbox';
var LANGUAGE_SKILLS_INPUT = 'german-language-skills-for-international-students';
var filters = document.querySelectorAll(FILTER_SELECTOR);
var resetRelatedInput = function (label) {
  if (!label) {
    return;
  }
  var content = label.split(':').map(function (item) {
    return item.trim();
  });
  var _a = (0,tslib__WEBPACK_IMPORTED_MODULE_6__.__read)(content, 1),
    filter = _a[0];
  if (filter === _form_input_handler__WEBPACK_IMPORTED_MODULE_1__.SEARCH_ACTIVE_FILTER_LABEL) {
    (0,_form_input_handler__WEBPACK_IMPORTED_MODULE_1__.clearInput)();
    if ((0,_common_reduced_motion_detection__WEBPACK_IMPORTED_MODULE_0__["default"])()) {
      (0,_form_form_handler__WEBPACK_IMPORTED_MODULE_4__["default"])();
    }
    return;
  }
  var _b = (0,tslib__WEBPACK_IMPORTED_MODULE_6__.__read)(content, 2),
    filterValue = _b[1];
  filters.forEach(function (filterControl) {
    var _a, _b;
    var labelText = (_b = (_a = filterControl.querySelector('span')) === null || _a === void 0 ? void 0 : _a.textContent) === null || _b === void 0 ? void 0 : _b.trim();
    if (filterValue !== labelText) {
      return;
    }
    var checkbox = filterControl.querySelector('input[type=checkbox]');
    checkbox.checked = false;
    checkbox.dispatchEvent(new Event('change'));
    if ((0,_common_reduced_motion_detection__WEBPACK_IMPORTED_MODULE_0__["default"])()) {
      (0,_form_form_handler__WEBPACK_IMPORTED_MODULE_4__["default"])();
    }
  });
};
var languageCertificateCheckedCheckboxes = 0;
filters.forEach(function (filterControl) {
  var checkbox = filterControl.querySelector('input[type=checkbox]');
  if ((checkbox === null || checkbox === void 0 ? void 0 : checkbox.name.startsWith(LANGUAGE_SKILLS_INPUT)) && (checkbox === null || checkbox === void 0 ? void 0 : checkbox.checked)) {
    languageCertificateCheckedCheckboxes++;
  }
  checkbox === null || checkbox === void 0 ? void 0 : checkbox.addEventListener('change', function (e) {
    (0,_active_filters__WEBPACK_IMPORTED_MODULE_2__.toggleActiveFilter)(filterControl, checkbox);
    (0,_filters_count__WEBPACK_IMPORTED_MODULE_3__["default"])(checkbox);
    if (checkbox.name.startsWith(LANGUAGE_SKILLS_INPUT)) {
      languageCertificateCheckedCheckboxes += checkbox.checked ? 1 : -1;
      (0,_degree_program_overview_degree_program_overview__WEBPACK_IMPORTED_MODULE_5__.updateDegreeProgramOverviewDataset)({
        activeFilters: languageCertificateCheckedCheckboxes >= 1 ? LANGUAGE_SKILLS_INPUT : ''
      });
    }
    if ((0,_common_reduced_motion_detection__WEBPACK_IMPORTED_MODULE_0__["default"])()) {
      return;
    }
    (0,_form_form_handler__WEBPACK_IMPORTED_MODULE_4__["default"])();
  });
});

/***/ }),

/***/ "./resources/ts/filters/filters-reset.ts":
/*!***********************************************!*\
  !*** ./resources/ts/filters/filters-reset.ts ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _form_form_handler__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../form/form-handler */ "./resources/ts/form/form-handler.ts");
/* harmony import */ var _active_filters__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./active-filters */ "./resources/ts/filters/active-filters.ts");


var CLEAR_FILTERS_SELECTOR = '.c-active-search-filters__clear-all-button';
var clearFilters = _form_form_handler__WEBPACK_IMPORTED_MODULE_0__.form.querySelector(CLEAR_FILTERS_SELECTOR);
clearFilters === null || clearFilters === void 0 ? void 0 : clearFilters.addEventListener('click', function (e) {
  e.preventDefault();
  (0,_active_filters__WEBPACK_IMPORTED_MODULE_1__.clearActiveFilters)();
});

/***/ }),

/***/ "./resources/ts/filters/filters-sticky-container.ts":
/*!**********************************************************!*\
  !*** ./resources/ts/filters/filters-sticky-container.ts ***!
  \**********************************************************/
/***/ (() => {



var SELECTORS = ['#wpadminbar', '#headerwrapper'];
var CSS_VAR = '--fau-top-fixed-height';
var SEARCH_FILTERS_SELECTOR = '.c-search-filters';
var SHADOW_CLASSNAME = '-shadow';
var updateCssVar = function () {
  var fixedElementsHeight = SELECTORS.reduce(function (accumulator, selector) {
    var element = document.querySelector(selector);
    if (!element) {
      return accumulator;
    }
    return accumulator + element.clientHeight;
  }, 0);
  document.body.style.setProperty(CSS_VAR, "".concat(fixedElementsHeight, "px"));
};
var toggleShadow = function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) {
      entry.target.classList.add(SHADOW_CLASSNAME);
      return;
    }
    entry.target.classList.remove(SHADOW_CLASSNAME);
  });
};
var watchForFilterChanges = function () {
  document.querySelectorAll(SEARCH_FILTERS_SELECTOR).forEach(function (filtersStickyContainer) {
    var offset = document.body.style.getPropertyValue(CSS_VAR);
    var offsetValue = parseInt(offset.replace('px', ''), 10);
    if (!offsetValue) {
      return;
    }
    var rootMargin = "0px 0px -".concat(window.outerHeight - offsetValue, "px");
    var observer = new IntersectionObserver(toggleShadow, {
      rootMargin: rootMargin
    });
    observer.observe(filtersStickyContainer);
  });
};
window.addEventListener('resize', updateCssVar);
window.addEventListener('DOMContentLoaded', updateCssVar);
window.addEventListener('DOMContentLoaded', watchForFilterChanges);

/***/ }),

/***/ "./resources/ts/filters/index.ts":
/*!***************************************!*\
  !*** ./resources/ts/filters/index.ts ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _filter_dropdown__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./filter-dropdown */ "./resources/ts/filters/filter-dropdown.ts");
/* harmony import */ var _filters_handler__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./filters-handler */ "./resources/ts/filters/filters-handler.ts");
/* harmony import */ var _filters_sticky_container__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./filters-sticky-container */ "./resources/ts/filters/filters-sticky-container.ts");
/* harmony import */ var _filters_sticky_container__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_filters_sticky_container__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _filters_reset__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./filters-reset */ "./resources/ts/filters/filters-reset.ts");
/* harmony import */ var _sort_selector__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./sort-selector */ "./resources/ts/filters/sort-selector.ts");
/* harmony import */ var _sort_selector__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_sort_selector__WEBPACK_IMPORTED_MODULE_4__);






/***/ }),

/***/ "./resources/ts/filters/sort-selector.ts":
/*!***********************************************!*\
  !*** ./resources/ts/filters/sort-selector.ts ***!
  \***********************************************/
/***/ (() => {



var SELECTOR = '.c-sort-selector select';
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll(SELECTOR).forEach(function (selectElement) {
    selectElement.addEventListener('change', function () {
      window.location.href = selectElement.value;
    });
  });
});

/***/ }),

/***/ "./resources/ts/form/form-handler.ts":
/*!*******************************************!*\
  !*** ./resources/ts/form/form-handler.ts ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   form: () => (/* binding */ form)
/* harmony export */ });
/* harmony import */ var _degree_program_overview_degree_program_overview__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../degree-program-overview/degree-program-overview */ "./resources/ts/degree-program-overview/degree-program-overview.ts");
/* harmony import */ var _input_handler__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./input-handler */ "./resources/ts/form/input-handler.ts");
/* harmony import */ var _utils_data_fetcher__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/data-fetcher */ "./resources/ts/utils/data-fetcher.ts");
/* harmony import */ var _order_order_updater__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../order/order-updater */ "./resources/ts/order/order-updater.ts");
/* harmony import */ var _loader__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./loader */ "./resources/ts/form/loader.ts");





var DEGREE_PROGRAMS_FORM_SELECTOR = '.c-degree-programs-search form';
var form = document.querySelector(DEGREE_PROGRAMS_FORM_SELECTOR);
form === null || form === void 0 ? void 0 : form.addEventListener('submit', function (e) {
  e.preventDefault();
  submitForm();
});
var submitForm = function () {
  var formData = new FormData(form);
  var formSearchParams = new URLSearchParams(formData);
  var currentSearchParams = new URLSearchParams(window.location.search);
  _order_order_updater__WEBPACK_IMPORTED_MODULE_3__.ORDER_PARAMS.forEach(function (param) {
    var value = currentSearchParams.get(param);
    if (value) {
      formSearchParams.append(param, value);
    }
  });
  sendForm("?".concat(formSearchParams.toString()));
};
var timeout = null;
var sendForm = function (urlSearchParams) {
  if (urlSearchParams === void 0) {
    urlSearchParams = '';
  }
  (0,_loader__WEBPACK_IMPORTED_MODULE_4__.startLoader)();
  if (timeout) {
    clearTimeout(timeout);
  }
  timeout = setTimeout(function () {
    (0,_input_handler__WEBPACK_IMPORTED_MODULE_1__.toggleSearchActiveFilter)();
    history.replaceState(null, '', urlSearchParams || window.location.pathname);
    (0,_order_order_updater__WEBPACK_IMPORTED_MODULE_3__["default"])(urlSearchParams);
    (0,_utils_data_fetcher__WEBPACK_IMPORTED_MODULE_2__["default"])("/fau/v1/degree-program".concat(urlSearchParams), _degree_program_overview_degree_program_overview__WEBPACK_IMPORTED_MODULE_0__.currentLanguage).then(function (data) {
      (0,_degree_program_overview_degree_program_overview__WEBPACK_IMPORTED_MODULE_0__["default"])(data);
    }).finally(function () {
      (0,_loader__WEBPACK_IMPORTED_MODULE_4__.stopLoader)();
    });
    timeout = null;
  }, 500);
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (submitForm);

/***/ }),

/***/ "./resources/ts/form/index.ts":
/*!************************************!*\
  !*** ./resources/ts/form/index.ts ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _input_handler__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./input-handler */ "./resources/ts/form/input-handler.ts");


/***/ }),

/***/ "./resources/ts/form/input-handler.ts":
/*!********************************************!*\
  !*** ./resources/ts/form/input-handler.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   SEARCH_ACTIVE_FILTER_LABEL: () => (/* binding */ SEARCH_ACTIVE_FILTER_LABEL),
/* harmony export */   clearInput: () => (/* binding */ clearInput),
/* harmony export */   toggleSearchActiveFilter: () => (/* binding */ toggleSearchActiveFilter)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _common_reduced_motion_detection__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../common/reduced-motion-detection */ "./resources/ts/common/reduced-motion-detection.ts");
/* harmony import */ var _form_handler__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./form-handler */ "./resources/ts/form/form-handler.ts");
/* harmony import */ var _filters_active_filters__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../filters/active-filters */ "./resources/ts/filters/active-filters.ts");




var INPUT_SELECTOR = '.c-degree-programs-sarchform__input';
var MIN_CHARACTERS = 3;
var SEARCH_ACTIVE_FILTER_LABEL = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x)('Keyword', 'frontoffice: degree-programs-overview', 'fau-degree-program-output');
var input = document.querySelector(INPUT_SELECTOR);
var initLiveSearching = function () {
  var INPUT_DELAY = 1500;
  var timeout = null;
  var handleInput = function () {
    if (timeout) {
      clearTimeout(timeout);
    }
    var inputValue = (input === null || input === void 0 ? void 0 : input.value.trim()) || '';
    if (inputValue.length > MIN_CHARACTERS) {
      timeout = setTimeout(function () {
        (0,_form_handler__WEBPACK_IMPORTED_MODULE_2__["default"])();
        timeout = null;
      }, INPUT_DELAY);
    }
  };
  input === null || input === void 0 ? void 0 : input.addEventListener('input', handleInput);
};
var valueOnFocusEvent = '';
var handleFocus = function () {
  valueOnFocusEvent = (input === null || input === void 0 ? void 0 : input.value.trim()) || '';
};
var handleBlur = function () {
  var inputValue = (input === null || input === void 0 ? void 0 : input.value.trim()) || '';
  if (inputValue.length <= MIN_CHARACTERS && valueOnFocusEvent !== inputValue) {
    (0,_form_handler__WEBPACK_IMPORTED_MODULE_2__["default"])();
  }
};
if (!(0,_common_reduced_motion_detection__WEBPACK_IMPORTED_MODULE_1__["default"])()) {
  initLiveSearching();
}
input === null || input === void 0 ? void 0 : input.addEventListener('focus', handleFocus);
input === null || input === void 0 ? void 0 : input.addEventListener('blur', handleBlur);
input === null || input === void 0 ? void 0 : input.addEventListener('search', function () {
  (0,_form_handler__WEBPACK_IMPORTED_MODULE_2__["default"])();
});
var toggleSearchActiveFilter = function () {
  if (!input) {
    return;
  }
  var inputValue = input.value.trim();
  (0,_filters_active_filters__WEBPACK_IMPORTED_MODULE_3__.toggleSingleActiveFilter)(SEARCH_ACTIVE_FILTER_LABEL, inputValue);
};
var clearInput = function () {
  if (!input) {
    return;
  }
  input.value = '';
  input.dispatchEvent(new Event('search'));
};

/***/ }),

/***/ "./resources/ts/form/loader.ts":
/*!*************************************!*\
  !*** ./resources/ts/form/loader.ts ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   startLoader: () => (/* binding */ startLoader),
/* harmony export */   stopLoader: () => (/* binding */ stopLoader)
/* harmony export */ });
var LOADER_SELECTOR = '.loader';
var loader = document.querySelector(LOADER_SELECTOR);
var createLoader = function () {
  var loaderHTML = "\n        <div class=\"loader\">\n            <div class=\"loader-icon\"></div>\n        </div>\n    ";
  document.body.insertAdjacentHTML('afterbegin', loaderHTML);
  return document.querySelector(LOADER_SELECTOR);
};
var getLoader = function () {
  if (!loader) {
    loader = createLoader();
  }
  return loader;
};
var startLoader = function () {
  getLoader().classList.remove('hidden');
};
var stopLoader = function () {
  getLoader().classList.add('hidden');
};

/***/ }),

/***/ "./resources/ts/order/order-updater.ts":
/*!*********************************************!*\
  !*** ./resources/ts/order/order-updater.ts ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ORDER_PARAMS: () => (/* binding */ ORDER_PARAMS),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.mjs");

var DEGREE_PROGRAMS_HEADER_SELECTOR = 'a.c-degree-programs-collection__header-item';
var ORDER_PARAMS = ['order', 'order_by'];
var headers = document.querySelectorAll(DEGREE_PROGRAMS_HEADER_SELECTOR);
var updateHeadersUrls = function (urlParams) {
  headers.forEach(function (header) {
    var formParams = new URLSearchParams(urlParams);
    var _a = (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__read)(header.href.split('?'), 2),
      params = _a[1];
    if (!params || !urlParams) {
      return;
    }
    var _b = (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__read)(header.href.split('?'), 1),
      path = _b[0];
    var headerParams = new URLSearchParams(params);
    headerParams.forEach(function (value, key) {
      if (ORDER_PARAMS.includes(key)) {
        formParams.set(key, value);
      }
    });
    header.href = "".concat(path, "?").concat(formParams.toString());
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (updateHeadersUrls);

/***/ }),

/***/ "./resources/ts/utils/data-fetcher.ts":
/*!********************************************!*\
  !*** ./resources/ts/utils/data-fetcher.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.mjs");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_1__);



var loadData = function (url, lang, page, perPage) {
  if (page === void 0) {
    page = 1;
  }
  if (perPage === void 0) {
    perPage = 100;
  }
  return (0,tslib__WEBPACK_IMPORTED_MODULE_2__.__awaiter)(void 0, void 0, void 0, function () {
    var response, data, totalPages, _a;
    var _b;
    return (0,tslib__WEBPACK_IMPORTED_MODULE_2__.__generator)(this, function (_c) {
      switch (_c.label) {
        case 0:
          return [4, _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
            path: (0,_wordpress_url__WEBPACK_IMPORTED_MODULE_1__.addQueryArgs)(url, {
              page: page,
              per_page: perPage,
              lang: lang
            }),
            parse: false
          })];
        case 1:
          response = _c.sent();
          return [4, response.json()];
        case 2:
          data = _c.sent();
          totalPages = parseInt((_b = response.headers.get('X-WP-TotalPages')) !== null && _b !== void 0 ? _b : '1');
          if (!(page < totalPages)) return [3, 4];
          _a = [(0,tslib__WEBPACK_IMPORTED_MODULE_2__.__spreadArray)([], (0,tslib__WEBPACK_IMPORTED_MODULE_2__.__read)(data), false)];
          return [4, loadData(url, lang, ++page)];
        case 3:
          data = tslib__WEBPACK_IMPORTED_MODULE_2__.__spreadArray.apply(void 0, _a.concat([tslib__WEBPACK_IMPORTED_MODULE_2__.__read.apply(void 0, [_c.sent()]), false]));
          _c.label = 4;
        case 4:
          return [2, data];
      }
    });
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (loadData);

/***/ }),

/***/ "./resources/ts/utils/settings.ts":
/*!****************************************!*\
  !*** ./resources/ts/utils/settings.ts ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.mjs");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, window.degreeProgramsOverviewSettings));

/***/ }),

/***/ "./resources/ts/viewmode/index.ts":
/*!****************************************!*\
  !*** ./resources/ts/viewmode/index.ts ***!
  \****************************************/
/***/ (() => {



var TOGGLES_SELECTOR = '.search-filter__output_modes .search-filter__output-mode-option';
var TOGGLE_ACTIVE_CLASSNAME = '-active';
var COLLECTION_SELECTOR = '.c-degree-programs-collection';
var LIST_VIEW_CLASSNAME = '-list';
var TILES_VIEW_CLASSNAME = '-tiles';
var updateUrl = function (outputMode) {
  var url = new URL(window.location.href);
  url.searchParams.set('output', outputMode);
  window.history.pushState({
    outputMode: outputMode
  }, '', url);
};
var switchOutputMode = function (outputMode) {
  var collectionElements = document.querySelectorAll(COLLECTION_SELECTOR);
  if (!collectionElements.length) {
    return;
  }
  document.querySelectorAll(TOGGLES_SELECTOR).forEach(function (toggleElement) {
    var element = toggleElement;
    element.classList.remove(TOGGLE_ACTIVE_CLASSNAME);
    if (element.dataset.mode === outputMode) {
      element.classList.add(TOGGLE_ACTIVE_CLASSNAME);
    }
  });
  collectionElements.forEach(function (collectionElement) {
    collectionElement.classList.remove(LIST_VIEW_CLASSNAME, TILES_VIEW_CLASSNAME);
    collectionElement.classList.add(outputMode === 'list' ? LIST_VIEW_CLASSNAME : TILES_VIEW_CLASSNAME);
  });
};
document.querySelectorAll(TOGGLES_SELECTOR).forEach(function (element) {
  element.addEventListener('click', function (event) {
    event.preventDefault();
    var outputMode = element.dataset.mode;
    if (!outputMode) {
      return;
    }
    switchOutputMode(outputMode);
    updateUrl(outputMode);
  });
});
window.addEventListener('popstate', function (event) {
  var outputMode = event.state.outputMode;
  if (!outputMode) {
    return;
  }
  switchOutputMode(outputMode);
});

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/***/ ((module) => {

module.exports = window["wp"]["url"];

/***/ }),

/***/ "./node_modules/tslib/tslib.es6.mjs":
/*!******************************************!*\
  !*** ./node_modules/tslib/tslib.es6.mjs ***!
  \******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   __addDisposableResource: () => (/* binding */ __addDisposableResource),
/* harmony export */   __assign: () => (/* binding */ __assign),
/* harmony export */   __asyncDelegator: () => (/* binding */ __asyncDelegator),
/* harmony export */   __asyncGenerator: () => (/* binding */ __asyncGenerator),
/* harmony export */   __asyncValues: () => (/* binding */ __asyncValues),
/* harmony export */   __await: () => (/* binding */ __await),
/* harmony export */   __awaiter: () => (/* binding */ __awaiter),
/* harmony export */   __classPrivateFieldGet: () => (/* binding */ __classPrivateFieldGet),
/* harmony export */   __classPrivateFieldIn: () => (/* binding */ __classPrivateFieldIn),
/* harmony export */   __classPrivateFieldSet: () => (/* binding */ __classPrivateFieldSet),
/* harmony export */   __createBinding: () => (/* binding */ __createBinding),
/* harmony export */   __decorate: () => (/* binding */ __decorate),
/* harmony export */   __disposeResources: () => (/* binding */ __disposeResources),
/* harmony export */   __esDecorate: () => (/* binding */ __esDecorate),
/* harmony export */   __exportStar: () => (/* binding */ __exportStar),
/* harmony export */   __extends: () => (/* binding */ __extends),
/* harmony export */   __generator: () => (/* binding */ __generator),
/* harmony export */   __importDefault: () => (/* binding */ __importDefault),
/* harmony export */   __importStar: () => (/* binding */ __importStar),
/* harmony export */   __makeTemplateObject: () => (/* binding */ __makeTemplateObject),
/* harmony export */   __metadata: () => (/* binding */ __metadata),
/* harmony export */   __param: () => (/* binding */ __param),
/* harmony export */   __propKey: () => (/* binding */ __propKey),
/* harmony export */   __read: () => (/* binding */ __read),
/* harmony export */   __rest: () => (/* binding */ __rest),
/* harmony export */   __runInitializers: () => (/* binding */ __runInitializers),
/* harmony export */   __setFunctionName: () => (/* binding */ __setFunctionName),
/* harmony export */   __spread: () => (/* binding */ __spread),
/* harmony export */   __spreadArray: () => (/* binding */ __spreadArray),
/* harmony export */   __spreadArrays: () => (/* binding */ __spreadArrays),
/* harmony export */   __values: () => (/* binding */ __values),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/******************************************************************************
Copyright (c) Microsoft Corporation.

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
PERFORMANCE OF THIS SOFTWARE.
***************************************************************************** */
/* global Reflect, Promise, SuppressedError, Symbol */

var extendStatics = function(d, b) {
  extendStatics = Object.setPrototypeOf ||
      ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
      function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
  return extendStatics(d, b);
};

function __extends(d, b) {
  if (typeof b !== "function" && b !== null)
      throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
  extendStatics(d, b);
  function __() { this.constructor = d; }
  d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
}

var __assign = function() {
  __assign = Object.assign || function __assign(t) {
      for (var s, i = 1, n = arguments.length; i < n; i++) {
          s = arguments[i];
          for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p)) t[p] = s[p];
      }
      return t;
  }
  return __assign.apply(this, arguments);
}

function __rest(s, e) {
  var t = {};
  for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0)
      t[p] = s[p];
  if (s != null && typeof Object.getOwnPropertySymbols === "function")
      for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
          if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i]))
              t[p[i]] = s[p[i]];
      }
  return t;
}

function __decorate(decorators, target, key, desc) {
  var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
  if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
  else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
  return c > 3 && r && Object.defineProperty(target, key, r), r;
}

function __param(paramIndex, decorator) {
  return function (target, key) { decorator(target, key, paramIndex); }
}

function __esDecorate(ctor, descriptorIn, decorators, contextIn, initializers, extraInitializers) {
  function accept(f) { if (f !== void 0 && typeof f !== "function") throw new TypeError("Function expected"); return f; }
  var kind = contextIn.kind, key = kind === "getter" ? "get" : kind === "setter" ? "set" : "value";
  var target = !descriptorIn && ctor ? contextIn["static"] ? ctor : ctor.prototype : null;
  var descriptor = descriptorIn || (target ? Object.getOwnPropertyDescriptor(target, contextIn.name) : {});
  var _, done = false;
  for (var i = decorators.length - 1; i >= 0; i--) {
      var context = {};
      for (var p in contextIn) context[p] = p === "access" ? {} : contextIn[p];
      for (var p in contextIn.access) context.access[p] = contextIn.access[p];
      context.addInitializer = function (f) { if (done) throw new TypeError("Cannot add initializers after decoration has completed"); extraInitializers.push(accept(f || null)); };
      var result = (0, decorators[i])(kind === "accessor" ? { get: descriptor.get, set: descriptor.set } : descriptor[key], context);
      if (kind === "accessor") {
          if (result === void 0) continue;
          if (result === null || typeof result !== "object") throw new TypeError("Object expected");
          if (_ = accept(result.get)) descriptor.get = _;
          if (_ = accept(result.set)) descriptor.set = _;
          if (_ = accept(result.init)) initializers.unshift(_);
      }
      else if (_ = accept(result)) {
          if (kind === "field") initializers.unshift(_);
          else descriptor[key] = _;
      }
  }
  if (target) Object.defineProperty(target, contextIn.name, descriptor);
  done = true;
};

function __runInitializers(thisArg, initializers, value) {
  var useValue = arguments.length > 2;
  for (var i = 0; i < initializers.length; i++) {
      value = useValue ? initializers[i].call(thisArg, value) : initializers[i].call(thisArg);
  }
  return useValue ? value : void 0;
};

function __propKey(x) {
  return typeof x === "symbol" ? x : "".concat(x);
};

function __setFunctionName(f, name, prefix) {
  if (typeof name === "symbol") name = name.description ? "[".concat(name.description, "]") : "";
  return Object.defineProperty(f, "name", { configurable: true, value: prefix ? "".concat(prefix, " ", name) : name });
};

function __metadata(metadataKey, metadataValue) {
  if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(metadataKey, metadataValue);
}

function __awaiter(thisArg, _arguments, P, generator) {
  function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
  return new (P || (P = Promise))(function (resolve, reject) {
      function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
      function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
      function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
      step((generator = generator.apply(thisArg, _arguments || [])).next());
  });
}

function __generator(thisArg, body) {
  var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
  return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
  function verb(n) { return function (v) { return step([n, v]); }; }
  function step(op) {
      if (f) throw new TypeError("Generator is already executing.");
      while (g && (g = 0, op[0] && (_ = 0)), _) try {
          if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
          if (y = 0, t) op = [op[0] & 2, t.value];
          switch (op[0]) {
              case 0: case 1: t = op; break;
              case 4: _.label++; return { value: op[1], done: false };
              case 5: _.label++; y = op[1]; op = [0]; continue;
              case 7: op = _.ops.pop(); _.trys.pop(); continue;
              default:
                  if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                  if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                  if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                  if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                  if (t[2]) _.ops.pop();
                  _.trys.pop(); continue;
          }
          op = body.call(thisArg, _);
      } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
      if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
  }
}

var __createBinding = Object.create ? (function(o, m, k, k2) {
  if (k2 === undefined) k2 = k;
  var desc = Object.getOwnPropertyDescriptor(m, k);
  if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
  }
  Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
  if (k2 === undefined) k2 = k;
  o[k2] = m[k];
});

function __exportStar(m, o) {
  for (var p in m) if (p !== "default" && !Object.prototype.hasOwnProperty.call(o, p)) __createBinding(o, m, p);
}

function __values(o) {
  var s = typeof Symbol === "function" && Symbol.iterator, m = s && o[s], i = 0;
  if (m) return m.call(o);
  if (o && typeof o.length === "number") return {
      next: function () {
          if (o && i >= o.length) o = void 0;
          return { value: o && o[i++], done: !o };
      }
  };
  throw new TypeError(s ? "Object is not iterable." : "Symbol.iterator is not defined.");
}

function __read(o, n) {
  var m = typeof Symbol === "function" && o[Symbol.iterator];
  if (!m) return o;
  var i = m.call(o), r, ar = [], e;
  try {
      while ((n === void 0 || n-- > 0) && !(r = i.next()).done) ar.push(r.value);
  }
  catch (error) { e = { error: error }; }
  finally {
      try {
          if (r && !r.done && (m = i["return"])) m.call(i);
      }
      finally { if (e) throw e.error; }
  }
  return ar;
}

/** @deprecated */
function __spread() {
  for (var ar = [], i = 0; i < arguments.length; i++)
      ar = ar.concat(__read(arguments[i]));
  return ar;
}

/** @deprecated */
function __spreadArrays() {
  for (var s = 0, i = 0, il = arguments.length; i < il; i++) s += arguments[i].length;
  for (var r = Array(s), k = 0, i = 0; i < il; i++)
      for (var a = arguments[i], j = 0, jl = a.length; j < jl; j++, k++)
          r[k] = a[j];
  return r;
}

function __spreadArray(to, from, pack) {
  if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
      if (ar || !(i in from)) {
          if (!ar) ar = Array.prototype.slice.call(from, 0, i);
          ar[i] = from[i];
      }
  }
  return to.concat(ar || Array.prototype.slice.call(from));
}

function __await(v) {
  return this instanceof __await ? (this.v = v, this) : new __await(v);
}

function __asyncGenerator(thisArg, _arguments, generator) {
  if (!Symbol.asyncIterator) throw new TypeError("Symbol.asyncIterator is not defined.");
  var g = generator.apply(thisArg, _arguments || []), i, q = [];
  return i = {}, verb("next"), verb("throw"), verb("return"), i[Symbol.asyncIterator] = function () { return this; }, i;
  function verb(n) { if (g[n]) i[n] = function (v) { return new Promise(function (a, b) { q.push([n, v, a, b]) > 1 || resume(n, v); }); }; }
  function resume(n, v) { try { step(g[n](v)); } catch (e) { settle(q[0][3], e); } }
  function step(r) { r.value instanceof __await ? Promise.resolve(r.value.v).then(fulfill, reject) : settle(q[0][2], r); }
  function fulfill(value) { resume("next", value); }
  function reject(value) { resume("throw", value); }
  function settle(f, v) { if (f(v), q.shift(), q.length) resume(q[0][0], q[0][1]); }
}

function __asyncDelegator(o) {
  var i, p;
  return i = {}, verb("next"), verb("throw", function (e) { throw e; }), verb("return"), i[Symbol.iterator] = function () { return this; }, i;
  function verb(n, f) { i[n] = o[n] ? function (v) { return (p = !p) ? { value: __await(o[n](v)), done: false } : f ? f(v) : v; } : f; }
}

function __asyncValues(o) {
  if (!Symbol.asyncIterator) throw new TypeError("Symbol.asyncIterator is not defined.");
  var m = o[Symbol.asyncIterator], i;
  return m ? m.call(o) : (o = typeof __values === "function" ? __values(o) : o[Symbol.iterator](), i = {}, verb("next"), verb("throw"), verb("return"), i[Symbol.asyncIterator] = function () { return this; }, i);
  function verb(n) { i[n] = o[n] && function (v) { return new Promise(function (resolve, reject) { v = o[n](v), settle(resolve, reject, v.done, v.value); }); }; }
  function settle(resolve, reject, d, v) { Promise.resolve(v).then(function(v) { resolve({ value: v, done: d }); }, reject); }
}

function __makeTemplateObject(cooked, raw) {
  if (Object.defineProperty) { Object.defineProperty(cooked, "raw", { value: raw }); } else { cooked.raw = raw; }
  return cooked;
};

var __setModuleDefault = Object.create ? (function(o, v) {
  Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
  o["default"] = v;
};

function __importStar(mod) {
  if (mod && mod.__esModule) return mod;
  var result = {};
  if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
  __setModuleDefault(result, mod);
  return result;
}

function __importDefault(mod) {
  return (mod && mod.__esModule) ? mod : { default: mod };
}

function __classPrivateFieldGet(receiver, state, kind, f) {
  if (kind === "a" && !f) throw new TypeError("Private accessor was defined without a getter");
  if (typeof state === "function" ? receiver !== state || !f : !state.has(receiver)) throw new TypeError("Cannot read private member from an object whose class did not declare it");
  return kind === "m" ? f : kind === "a" ? f.call(receiver) : f ? f.value : state.get(receiver);
}

function __classPrivateFieldSet(receiver, state, value, kind, f) {
  if (kind === "m") throw new TypeError("Private method is not writable");
  if (kind === "a" && !f) throw new TypeError("Private accessor was defined without a setter");
  if (typeof state === "function" ? receiver !== state || !f : !state.has(receiver)) throw new TypeError("Cannot write private member to an object whose class did not declare it");
  return (kind === "a" ? f.call(receiver, value) : f ? f.value = value : state.set(receiver, value)), value;
}

function __classPrivateFieldIn(state, receiver) {
  if (receiver === null || (typeof receiver !== "object" && typeof receiver !== "function")) throw new TypeError("Cannot use 'in' operator on non-object");
  return typeof state === "function" ? receiver === state : state.has(receiver);
}

function __addDisposableResource(env, value, async) {
  if (value !== null && value !== void 0) {
    if (typeof value !== "object" && typeof value !== "function") throw new TypeError("Object expected.");
    var dispose;
    if (async) {
        if (!Symbol.asyncDispose) throw new TypeError("Symbol.asyncDispose is not defined.");
        dispose = value[Symbol.asyncDispose];
    }
    if (dispose === void 0) {
        if (!Symbol.dispose) throw new TypeError("Symbol.dispose is not defined.");
        dispose = value[Symbol.dispose];
    }
    if (typeof dispose !== "function") throw new TypeError("Object not disposable.");
    env.stack.push({ value: value, dispose: dispose, async: async });
  }
  else if (async) {
    env.stack.push({ async: true });
  }
  return value;
}

var _SuppressedError = typeof SuppressedError === "function" ? SuppressedError : function (error, suppressed, message) {
  var e = new Error(message);
  return e.name = "SuppressedError", e.error = error, e.suppressed = suppressed, e;
};

function __disposeResources(env) {
  function fail(e) {
    env.error = env.hasError ? new _SuppressedError(e, env.error, "An error was suppressed during disposal.") : e;
    env.hasError = true;
  }
  function next() {
    while (env.stack.length) {
      var rec = env.stack.pop();
      try {
        var result = rec.dispose && rec.dispose.call(rec.value);
        if (rec.async) return Promise.resolve(result).then(next, function(e) { fail(e); return next(); });
      }
      catch (e) {
          fail(e);
      }
    }
    if (env.hasError) throw env.error;
  }
  return next();
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  __extends,
  __assign,
  __rest,
  __decorate,
  __param,
  __metadata,
  __awaiter,
  __generator,
  __createBinding,
  __exportStar,
  __values,
  __read,
  __spread,
  __spreadArrays,
  __spreadArray,
  __await,
  __asyncGenerator,
  __asyncDelegator,
  __asyncValues,
  __makeTemplateObject,
  __importStar,
  __importDefault,
  __classPrivateFieldGet,
  __classPrivateFieldSet,
  __classPrivateFieldIn,
  __addDisposableResource,
  __disposeResources,
});


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************************!*\
  !*** ./resources/ts/frontend.ts ***!
  \**********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _accordion__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./accordion */ "./resources/ts/accordion/index.ts");
/* harmony import */ var _filters__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./filters */ "./resources/ts/filters/index.ts");
/* harmony import */ var _form__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./form */ "./resources/ts/form/index.ts");
/* harmony import */ var _viewmode__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./viewmode */ "./resources/ts/viewmode/index.ts");
/* harmony import */ var _viewmode__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_viewmode__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _common__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./common */ "./resources/ts/common/index.ts");





})();

/******/ })()
;
//# sourceMappingURL=frontend.js.map