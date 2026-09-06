(() => {
  const focusable = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
  let lastFocus = null;
  let toastTimer = null;

  const announce = (message, tone = 'info') => {
    let toast = document.querySelector('.ayru-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'ayru-toast';
      toast.setAttribute('role', 'status');
      toast.setAttribute('aria-live', 'polite');
      document.body.appendChild(toast);
    }
    toast.dataset.tone = tone;
    toast.textContent = message;
    toast.hidden = false;
    window.clearTimeout(toastTimer);
    toastTimer = window.setTimeout(() => { toast.hidden = true; }, 3200);
  };

  const openDrawer = (id, trigger) => {
    const drawer = document.getElementById(id);
    if (!drawer) return;
    lastFocus = trigger || document.activeElement;
    drawer.hidden = false;
    document.body.classList.add('drawer-open');
    if (trigger) trigger.setAttribute('aria-expanded', 'true');
    requestAnimationFrame(() => drawer.querySelector(focusable)?.focus());
  };

  const closeDrawer = (drawer) => {
    if (!drawer) return;
    drawer.hidden = true;
    document.body.classList.remove('drawer-open');
    document.querySelectorAll(`[aria-controls="${drawer.id}"]`).forEach((item) => item.setAttribute('aria-expanded', 'false'));
    lastFocus?.focus?.();
  };

  document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-open]');
    if (opener) {
      event.preventDefault();
      openDrawer(opener.dataset.open, opener);
      return;
    }
    const closer = event.target.closest('[data-close]');
    if (closer) closeDrawer(closer.closest('.drawer'));
  });

  document.addEventListener('keydown', (event) => {
    const drawer = document.querySelector('.drawer:not([hidden])');
    if (!drawer) return;
    if (event.key === 'Escape') closeDrawer(drawer);
    if (event.key !== 'Tab') return;
    const items = [...drawer.querySelectorAll(focusable)];
    if (!items.length) return;
    const first = items[0];
    const last = items[items.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  });

  document.addEventListener('click', async (event) => {
    const favorite = event.target.closest('[data-favorite-product]');
    if (!favorite || !window.ayruSettings) return;
    event.preventDefault();
    favorite.disabled = true;
    favorite.setAttribute('aria-busy', 'true');
    const body = new URLSearchParams({
      action: 'ayru_toggle_favorite',
      nonce: ayruSettings.favoriteNonce,
      product_id: favorite.dataset.favoriteProduct,
    });
    try {
      const response = await fetch(ayruSettings.ajaxUrl, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body });
      const result = await response.json();
      if (!result.success) throw new Error(result.data?.message || 'Erreur');
      favorite.setAttribute('aria-pressed', result.data.active ? 'true' : 'false');
      favorite.setAttribute('aria-label', `${result.data.active ? favorite.dataset.labelRemove : favorite.dataset.labelAdd} : ${favorite.closest('.product-card')?.querySelector('h3')?.textContent?.trim() || 'produit'}`);
      announce(result.data.active ? 'Ajouté à vos favoris.' : 'Retiré de vos favoris.');
    } catch (error) {
      favorite.setAttribute('title', error.message);
      announce(error.message || 'Le favori n’a pas pu être mis à jour.', 'error');
    } finally {
      favorite.disabled = false;
      favorite.removeAttribute('aria-busy');
    }
  });

  document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-ayru-add-list]');
    if (!button) return;
    if (button.dataset.loginUrl) {
      window.location.href = button.dataset.loginUrl;
      return;
    }
    const feedback = document.querySelector('.ayru-list-feedback');
    const body = new URLSearchParams({
      action: 'ayru_add_to_list',
      nonce: button.dataset.nonce,
      product_id: button.dataset.ayruAddList,
    });
    button.disabled = true;
    button.setAttribute('aria-busy', 'true');
    try {
      const response = await fetch(window.ayruSettings.ajaxUrl, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body });
      const result = await response.json();
      if (!result.success) throw new Error(result.data?.message || 'Impossible d’ajouter ce produit.');
      feedback.textContent = result.data.message;
      announce(result.data.message);
    } catch (error) {
      feedback.textContent = error.message;
      announce(error.message || 'Impossible d’ajouter ce produit.', 'error');
    } finally {
      button.disabled = false;
      button.removeAttribute('aria-busy');
    }
  });

  document.addEventListener('submit', async (event) => {
    const form = event.target.closest('[data-ayru-list-create]');
    if (!form) return;
    event.preventDefault();
    const message = form.querySelector('.form-message');
    form.setAttribute('aria-busy', 'true');
    const body = new URLSearchParams(new FormData(form));
    body.set('action', 'ayru_create_list');
    try {
      const response = await fetch(window.ayruSettings.ajaxUrl, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body });
      const result = await response.json();
      if (!result.success) throw new Error(result.data?.message || 'Erreur');
      window.location.reload();
    } catch (error) {
      message.textContent = error.message;
      form.removeAttribute('aria-busy');
      announce(error.message || 'La liste n’a pas pu être créée.', 'error');
    }
  });

  document.addEventListener('click', async (event) => {
    const copy = event.target.closest('[data-copy]');
    if (!copy) return;
    try {
      await navigator.clipboard.writeText(copy.dataset.copy);
      const old = copy.textContent;
      copy.textContent = 'Lien copié !';
      setTimeout(() => { copy.textContent = old; }, 1800);
    } catch (_) {
      window.prompt('Copiez ce lien :', copy.dataset.copy);
    }
  });

  const closeSelects = (except = null) => {
    document.querySelectorAll('.ayru-select__button[aria-expanded="true"]').forEach((button) => {
      if (button === except) return;
      button.setAttribute('aria-expanded', 'false');
      button.nextElementSibling.hidden = true;
    });
  };

  const enhanceSelect = (select, index) => {
    if (select.dataset.ayruEnhanced) return;
    select.dataset.ayruEnhanced = 'true';
    select.classList.add('ayru-native-select');
    select.tabIndex = -1;

    const wrapper = document.createElement('div');
    wrapper.className = 'ayru-select';
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'ayru-select__button';
    button.setAttribute('aria-haspopup', 'listbox');
    button.setAttribute('aria-expanded', 'false');
    const list = document.createElement('div');
    list.className = 'ayru-select__menu';
    list.id = `ayru-select-${index}`;
    list.setAttribute('role', 'listbox');
    list.hidden = true;
    button.setAttribute('aria-controls', list.id);
    wrapper.append(button, list);
    select.insertAdjacentElement('afterend', wrapper);

    const rebuild = () => {
      const selected = select.options[select.selectedIndex] || select.options[0];
      button.textContent = selected?.textContent?.trim() || 'Choisir';
      button.setAttribute('aria-label', `${select.closest('tr')?.querySelector('label')?.textContent?.trim() || 'Choisir'} : ${button.textContent}`);
      list.replaceChildren();
      [...select.options].forEach((option) => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'ayru-select__option';
        item.setAttribute('role', 'option');
        item.setAttribute('aria-selected', option.selected ? 'true' : 'false');
        item.dataset.value = option.value;
        item.disabled = option.disabled;
        item.textContent = option.textContent.trim();
        item.addEventListener('click', () => {
          select.value = option.value;
          select.dispatchEvent(new Event('change', { bubbles: true }));
          rebuild();
          button.setAttribute('aria-expanded', 'false');
          list.hidden = true;
          button.focus();
        });
        list.appendChild(item);
      });
    };

    button.addEventListener('click', () => {
      const opening = button.getAttribute('aria-expanded') !== 'true';
      closeSelects(opening ? button : null);
      button.setAttribute('aria-expanded', opening ? 'true' : 'false');
      list.hidden = !opening;
      if (opening) list.querySelector('[aria-selected="true"]:not(:disabled), .ayru-select__option:not(:disabled)')?.focus();
    });
    button.addEventListener('keydown', (event) => {
      if (!['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(event.key)) return;
      event.preventDefault();
      if (button.getAttribute('aria-expanded') !== 'true') button.click();
    });
    list.addEventListener('keydown', (event) => {
      const items = [...list.querySelectorAll('.ayru-select__option:not(:disabled)')];
      const current = items.indexOf(document.activeElement);
      if (event.key === 'Escape') { event.preventDefault(); button.click(); button.focus(); return; }
      if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) return;
      event.preventDefault();
      const next = event.key === 'Home' ? 0 : event.key === 'End' ? items.length - 1 : (current + (event.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length;
      items[next]?.focus();
    });
    select.addEventListener('change', rebuild);
    rebuild();
  };

  document.querySelectorAll('.woocommerce-ordering select, form.variations_form select').forEach(enhanceSelect);
  document.addEventListener('click', (event) => { if (!event.target.closest('.ayru-select')) closeSelects(); });
})();
