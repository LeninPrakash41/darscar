document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  // Prefill the booking form's car dropdown from a ?car= query param
  var carSelect = document.getElementById('car');
  if (carSelect) {
    var params = new URLSearchParams(window.location.search);
    var carParam = params.get('car');
    if (carParam) {
      for (var i = 0; i < carSelect.options.length; i++) {
        if (carSelect.options[i].value === carParam) {
          carSelect.value = carParam;
          break;
        }
      }
    }
  }

  // Toggle return trip date/time fields
  var returnCheckbox = document.getElementById('return_trip');
  var returnFields = document.getElementById('returnFields');
  if (returnCheckbox && returnFields) {
    var syncReturnFields = function () {
      returnFields.style.display = returnCheckbox.checked ? 'grid' : 'none';
    };
    returnCheckbox.addEventListener('change', syncReturnFields);
    syncReturnFields();
  }
});
