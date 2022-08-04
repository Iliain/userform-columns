let thisLevel = 0;
let depth = 0;

const columnDecorator = () => {
  const fields = document.querySelectorAll('.ss-gridfield-item');
  Array.prototype.forEach.call(fields, (el) => {
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
      el.classList.add('incolumn');
    }

    for (let i = 1; i <= 5; i += 1) {
      if (thisLevel >= i) {
        el.classList.add(`incolumn-level-${i}`);
      }
    }
  });
};
columnDecorator();

const targetNode = document.querySelector('#Form_EditForm');
const mutationObserver = new MutationObserver(columnDecorator);
mutationObserver.observe(targetNode, { childList: true, subtree: true });
