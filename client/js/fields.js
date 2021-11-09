let thisLevel = 0;
let depth = 0;

const fields = document.querySelectorAll('.ss-gridfield-item');
Array.prototype.forEach.call(fields, function(el) {
  switch (el.getAttribute('data-class')) {
    case 'Iliain\\UserformColumns\\FormFields\\EditableColumnStartField': {
      depth += 1;
      thisLevel = depth;
      break;
    }
    case 'Iliain\\UserformColumns\\FormFields\\EditableColumnEndField': {
      thisLevel = depth;
      depth -= 1;
      break;
    }
    default: {
      thisLevel = depth;
    }
  }

  if (thisLevel > 0) {
    el.classList.toggle("incolumn");
  }

  for (let i = 1; i <= 5; i++) {
    if (thisLevel >= i) {
      el.classList.toggle(`incolumn-level-${i}`);
    }
  }
});

