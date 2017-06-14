// Will hold previously focused element
var focusedElementBeforeModal;

// Find the modal and its overlay
var modal = document.querySelector('.modal');
var modalOverlay = document.querySelector('.modal-overlay');

var modalToggle1 = document.querySelector('#ttlupload');
var modalToggle2 = document.querySelector('#bgupload');
modalToggle1.addEventListener('click', function(){ openModal('ttlupload');});
modalToggle2.addEventListener('click', function(){ openModal('bgupload');});

function openModal(m) {
	var modal = document.querySelector('#'+m+'-form');
  // Save current focus
  focusedElementBeforeModal = document.activeElement;

  // Listen for and trap the keyboard
  	modal.addEventListener('keydown', trapTabKey);
  

  // Listen for indicators to close the modal
  modalOverlay.addEventListener('click', closeModal);
  
  // Sign-Up button
  var Btn = modal.querySelector('#'+m+'-button');
  Btn.addEventListener('click', closeModal);
  var cBtn = modal.querySelector('#cncl-'+m+'-button');
  cBtn.addEventListener('click', closeModal);
 
  // Find all focusable children
  var focusableElementsString = 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex="0"], [contenteditable]';
  var focusableElements = modal.querySelectorAll(focusableElementsString);
  // Convert NodeList to Array
  focusableElements = Array.prototype.slice.call(focusableElements);

  var firstTabStop = focusableElements[0];
  var lastTabStop = focusableElements[focusableElements.length - 1];

  // Show the modal and overlay
  modal.style.display = 'block';
  modalOverlay.style.display = 'block';

  // Focus first child
  firstTabStop.focus();

  function trapTabKey(e) {
    // Check for TAB key press
    if (e.keyCode === 9) {

      // SHIFT + TAB
      if (e.shiftKey) {
        if (document.activeElement === firstTabStop) {
          e.preventDefault();
          lastTabStop.focus();
        }

      // TAB
      } else {
        if (document.activeElement === lastTabStop) {
          e.preventDefault();
          firstTabStop.focus();
        }
      }
    }

    // ESCAPE
    if (e.keyCode === 27) {
      closeModal();
    }
  }
}

function closeModal() {
	var modal = document.querySelectorAll('.modal');
  // Hide the modal and overlay
  for (i=0;i<modal.length;i++) {	modal[i].style.display = 'none';}
  modalOverlay.style.display = 'none';

 

  // Set focus back to element that had it before the modal was opened
  focusedElementBeforeModal.focus();
}