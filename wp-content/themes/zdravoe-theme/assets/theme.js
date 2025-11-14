// Simple modal toggler and small UX helpers (+ Turbo re-init)
(function () {
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  function openModalById(id) {
    var modal = document.querySelector('[data-modal="' + id + '"]');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.documentElement.classList.add('overflow-hidden');
  }

  function closeModal(modal) {
    if (!modal) return;
    modal.classList.add('hidden');
    document.documentElement.classList.remove('overflow-hidden');
  }

  function bindOnce(el, key, binder) {
    if (!el) return;
    var k = 'bound' + (key ? '-' + key : '');
    var attr = 'data-' + k;
    if (el.getAttribute(attr) === '1') return;
    el.setAttribute(attr, '1');
    binder(el);
  }

  function init() {
    // Resolve same-origin AJAX URL to avoid CORS when localized URL has a different host
    var AJAX_URL = (function(){
      var rel = '/wp-admin/admin-ajax.php';
      try {
        var loc = window.location;
        var z = (window.ZdravoeTheme && ZdravoeTheme.ajax_url) ? new URL(ZdravoeTheme.ajax_url, loc.href) : new URL(rel, loc.href);
        if (z.origin !== loc.origin) {
          return rel;
        }
        return z.pathname + z.search;
      } catch(e){
        return rel;
      }
    })();
    // Cookie consent
    (function(){
      var bar = document.querySelector('[data-cookie-consent]');
      if (!bar) return;
      bindOnce(bar, 'cookie', function(barEl){
        var accepted = document.cookie.indexOf('cookie_consent=1') !== -1;
        if (!accepted) {
          barEl.classList.remove('hidden');
          document.documentElement.classList.add('has-cookie-banner');
        }
        var btn = barEl.querySelector('[data-cookie-accept]');
        if (btn) {
          btn.addEventListener('click', function(){
            var expires = new Date(Date.now() + 365*24*60*60*1000).toUTCString();
            document.cookie = 'cookie_consent=1; path=/; expires=' + expires + '; SameSite=Lax';
            barEl.classList.add('hidden');
            document.documentElement.classList.remove('has-cookie-banner');
          });
        }
      });
    })();

    // Single book: client-side chapter switching (no full reload)
    (function(){
      var chaptersJsonEl = document.getElementById('book-chapters-json');
      if (!chaptersJsonEl) return;
      // prevent multiple bindings per page instance
      if (chaptersJsonEl.dataset.bound === '1') return; 
      chaptersJsonEl.dataset.bound = '1';

      var chapters;
      try { chapters = JSON.parse(chaptersJsonEl.textContent || '[]'); } catch (e) { chapters = []; }
      if (!Array.isArray(chapters) || chapters.length === 0) return;

      var contentEl = document.querySelector('[data-book-chapter-content]');
      var titleEl = document.querySelector('[data-book-chapter-title]');
      var toc = document.querySelector('[data-book-toc]');
      var nextBtn = document.querySelector('[data-next-chapter]');
      var prevBtn = document.querySelector('[data-prev-chapter]');
      var permalink = window.location.href.split('?')[0];

      function getCurrentFromURL(){
        var m = window.location.search.match(/[?&]ch=(\d+)/);
        var n = m ? parseInt(m[1],10) : 1;
        if (!n || n < 1) n = 1;
        if (n > chapters.length) n = chapters.length;
        return n;
      }

      function setActiveInTOC(current){
        if (!toc) return;
        toc.querySelectorAll('a[data-ch]').forEach(function(a){
          var ch = parseInt(a.getAttribute('data-ch')||'0',10);
          a.classList.remove('bg-[#FAF8F5]','text-[#6B5144]','text-[#8A6A5B]');
          a.classList.remove('hover:bg-[#FAF8F5]','text-gray-700','hover:text-[#6B5144]','hover:text-[#8A6A5B]');
          if (ch === current){
            a.classList.add('bg-[#FAF8F5]','text-[#8A6A5B]');
            a.setAttribute('aria-current','page');
          } else {
            a.classList.add('hover:bg-[#FAF8F5]','text-gray-700','hover:text-[#8A6A5B]');
            a.removeAttribute('aria-current');
          }
        });
      }

      function updateNextBtn(current){
        if (!nextBtn) return;
        if (current >= chapters.length){
          nextBtn.classList.add('hidden');
          nextBtn.setAttribute('aria-hidden','true');
        } else {
          nextBtn.classList.remove('hidden');
          nextBtn.removeAttribute('aria-hidden');
          var nextUrl = permalink + '?ch=' + (current+1);
          nextBtn.setAttribute('href', nextUrl);
        }
      }

      function updatePrevBtn(current){
        if (!prevBtn) return;
        if (current <= 1){
          prevBtn.classList.add('hidden');
          prevBtn.setAttribute('aria-hidden','true');
        } else {
          prevBtn.classList.remove('hidden');
          prevBtn.removeAttribute('aria-hidden');
          var prevUrl = permalink + '?ch=' + (current-1);
          prevBtn.setAttribute('href', prevUrl);
        }
      }

      function renderChapter(current, push){
        var item = chapters[current-1];
        if (!item || !contentEl || !titleEl) return;
        titleEl.textContent = item.title || '';
        titleEl.id = item.id || ('ch-'+current);
        contentEl.innerHTML = item.content_html || '';
        if (push){
          var url = permalink + '?ch=' + current;
          window.history.pushState({ch: current}, '', url);
        }
        setActiveInTOC(current);
        updateNextBtn(current);
        updatePrevBtn(current);
        // scroll to very top of the page
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      var current = getCurrentFromURL();
      setActiveInTOC(current);
      updateNextBtn(current);
      updatePrevBtn(current);

      if (toc && !toc.dataset.bound){
        toc.dataset.bound = '1';
        toc.addEventListener('click', function(e){
          var a = e.target.closest('a[data-ch]');
          if (!a) return;
          e.preventDefault();
          var n = parseInt(a.getAttribute('data-ch')||'1',10);
          if (!n) n = 1;
          renderChapter(n, true);
        });
      }

      if (nextBtn && !nextBtn.dataset.bound){
        nextBtn.dataset.bound = '1';
        nextBtn.addEventListener('click', function(e){
          e.preventDefault();
          var n = getCurrentFromURL();
          if (n < chapters.length) renderChapter(n+1, true);
        });
      }

      if (prevBtn && !prevBtn.dataset.bound){
        prevBtn.dataset.bound = '1';
        prevBtn.addEventListener('click', function(e){
          e.preventDefault();
          var n = getCurrentFromURL();
          if (n > 1) renderChapter(n-1, true);
        });
      }

      if (!window.__zdravoePopstateBound){
        window.__zdravoePopstateBound = true;
        window.addEventListener('popstate', function(e){
          var n = (e.state && e.state.ch) ? e.state.ch : getCurrentFromURL();
          renderChapter(n, false);
        });
      }
    })();

    // Contact form AJAX
    (function(){
      var cf = document.querySelector('[data-contact-form]');
      if (!cf) return;
      if (cf.dataset.bound === '1') return;
      cf.dataset.bound = '1';

      var statusEl = cf.querySelector('[data-contact-status]');
      var submitBtn = cf.querySelector('[data-contact-submit]');
      var setStatus = function (msg, ok) {
        if (!statusEl) return;
        statusEl.textContent = msg || '';
        statusEl.classList.remove('text-red-600', 'text-green-600');
        statusEl.classList.add(ok ? 'text-green-600' : 'text-red-600');
      };

      cf.addEventListener('submit', function (e) {
        e.preventDefault();
        if (statusEl) setStatus('', true);

        var formData = new FormData(cf);
        var name = (formData.get('name') || '').toString().trim();
        var email = (formData.get('email') || '').toString().trim();
        var message = (formData.get('message') || '').toString().trim();
        var consent = cf.querySelector('#contact-consent');
        if (!name || !email || !message) {
          setStatus('Пожалуйста, заполните все поля.', false);
          return;
        }
        if (consent && !consent.checked) {
          setStatus('Подтвердите согласие с политикой конфиденциальности.', false);
          return;
        }

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.dataset.originalText = submitBtn.textContent || '';
          submitBtn.textContent = 'Отправка...';
        }

        formData.append('action', 'zdravoe_contact');
        formData.append('nonce', (window.ZdravoeTheme && ZdravoeTheme.nonce) ? ZdravoeTheme.nonce : '');

        fetch(AJAX_URL, { method: 'POST', body: formData })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data && data.success) {
              setStatus((data.data && data.data.message) || 'Сообщение отправлено', true);
              cf.reset();
            } else {
              var err = (data && data.data && data.data.message) ? data.data.message : 'Ошибка отправки';
              setStatus(err, false);
            }
          })
          .catch(function () {
            setStatus('Сетевая ошибка. Попробуйте позже.', false);
          })
          .finally(function () {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = submitBtn.dataset.originalText || 'Отправить';
            }
          });
      });
    })();

    // Article TOC smooth scroll
    (function(){
      var toc = document.querySelector('[data-article-toc]');
      if (!toc) return;
      if (toc.getAttribute('data-bound-article-toc') === '1') return;
      toc.setAttribute('data-bound-article-toc','1');
      toc.addEventListener('click', function(e){
        var a = e.target.closest('a[href^="#"]');
        if (!a) return;
        e.preventDefault();
        var id = a.getAttribute('href').slice(1);
        var el = document.getElementById(id);
        if (el) {
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    })();

    // Mobile menu toggle
    (function(){
      var menuToggle = document.querySelector('[data-mobile-menu-toggle]');
      var menu = document.querySelector('[data-mobile-menu]');
      if (!(menuToggle && menu)) return;
      if (menuToggle.dataset.bound === '1') return;
      menuToggle.dataset.bound = '1';

      menuToggle.addEventListener('click', function () {
        var isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        menuToggle.setAttribute('aria-expanded', String(isHidden));
        if (isHidden) {
          document.documentElement.classList.add('overflow-y-hidden');
        } else {
          document.documentElement.classList.remove('overflow-y-hidden');
        }
      });

      // Close menu on resize to desktop
      var mql = window.matchMedia('(min-width: 768px)');
      var handleMql = function (e) {
        if (e.matches) {
          menu.classList.add('hidden');
          menuToggle.setAttribute('aria-expanded', 'false');
          document.documentElement.classList.remove('overflow-y-hidden');
        }
      };
      if (mql && mql.addEventListener) {
        mql.addEventListener('change', handleMql);
      } else if (mql && mql.addListener) {
        mql.addListener(handleMql);
      }

      // Close menu on ESC (bind once globally)
      if (!window.__zdravoeMenuEsc){
        window.__zdravoeMenuEsc = true;
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.documentElement.classList.remove('overflow-y-hidden');
          }
        });
      }

      // Close when clicking any link inside
      menu.querySelectorAll('a, button[data-modal-open]')
        .forEach(function (el) {
          if (el.dataset.bound === '1') return;
          el.dataset.bound = '1';
          el.addEventListener('click', function () {
            menu.classList.add('hidden');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.documentElement.classList.remove('overflow-y-hidden');
          });
        });
    })();

    // Open triggers
    document.querySelectorAll('[data-modal-open]')
      .forEach(function (btn) {
        if (btn.dataset.bound === '1') return;
        btn.dataset.bound = '1';
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          var id = btn.getAttribute('data-modal-open');
          if (id) openModalById(id);
        });
      });

    // Close triggers (buttons and overlay)
    document.querySelectorAll('[data-modal]')
      .forEach(function (modal) {
        if (modal.dataset.bound === '1') return;
        modal.dataset.bound = '1';
        modal.addEventListener('click', function (e) {
          var isOverlay = e.target.hasAttribute('data-modal-close');
          if (isOverlay) return closeModal(modal);
        });

        modal.querySelectorAll('[data-modal-close]')
          .forEach(function (closer) {
            if (closer.dataset.bound === '1') return;
            closer.dataset.bound = '1';
            closer.addEventListener('click', function () { closeModal(modal); });
          });
      });

    // ESC to close topmost modal (bind once globally)
    if (!window.__zdravoeEsc){
      window.__zdravoeEsc = true;
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          var open = document.querySelector('[data-modal]:not(.hidden)');
          if (open) closeModal(open);
        }
      });
    }

    // Articles archive: client-side category filter
    (function(){
      var filter = document.querySelector('[data-article-filter]');
      var list = document.querySelector('[data-article-list]');
      var sentinel = document.querySelector('[data-article-sentinel]');
      var emptyMsg = document.querySelector('[data-article-empty]');
      if (!(filter && list)) return;
      if (filter.getAttribute('data-bound-article-filter') === '1') return;
      filter.setAttribute('data-bound-article-filter','1');

      var buttons = Array.prototype.slice.call(filter.querySelectorAll('[data-filter]'));
      var ppp = parseInt(list.getAttribute('data-article-ppp') || '12', 10);
      var offset = parseInt(list.getAttribute('data-article-offset') || String(list.querySelectorAll('[data-article-card]').length || 0), 10);
      var term = list.getAttribute('data-article-term') || 'all';
      var loading = false;
      var done = false;

      var clsActive = ['bg-[#8A6A5B]','text-white','hover:bg-[#7A5C4E]'];
      var clsInactive = ['text-gray-700','hover:bg-gray-100'];

      function setActiveByKey(key){
        var target = null;
        buttons.forEach(function(b){
          var k = b.getAttribute('data-filter') || 'all';
          b.setAttribute('aria-pressed','false');
          clsActive.forEach(function(c){ b.classList.remove(c); });
          clsInactive.forEach(function(c){ if (!b.classList.contains(c)) b.classList.add(c); });
          if (k === String(key)) target = b;
        });
        if (target){
          target.setAttribute('aria-pressed','true');
          clsInactive.forEach(function(c){ target.classList.remove(c); });
          clsActive.forEach(function(c){ if (!target.classList.contains(c)) target.classList.add(c); });
        }
      }

      function appendHTML(html){
        if (!html) return 0;
        var tmp = document.createElement('div');
        tmp.innerHTML = html;
        var nodes = Array.prototype.slice.call(tmp.children);
        nodes.forEach(function(n){ list.appendChild(n); });
        return nodes.length;
      }

      function loadMore(){
        if (loading || done) return;
        loading = true;
        var fd = new FormData();
        fd.append('action','zdravoe_fetch_articles');
        fd.append('nonce', (window.ZdravoeTheme && ZdravoeTheme.articles_nonce) ? ZdravoeTheme.articles_nonce : '');
        fd.append('ppp', String(ppp));
        fd.append('offset', String(offset));
        fd.append('term', term);
        fetch(AJAX_URL, { method:'POST', body: fd })
          .then(function(r){ return r.json(); })
          .then(function(data){
            if (!data || !data.success) throw new Error('bad');
            var added = appendHTML(data.data && data.data.html ? data.data.html : '');
            offset += (data.data && typeof data.data.count==='number') ? data.data.count : added;
            done = !(data.data && data.data.has_more);
            if (emptyMsg) {
              if (offset === 0) emptyMsg.classList.remove('hidden');
              else emptyMsg.classList.add('hidden');
            }
          })
          .catch(function(){ /* noop */ })
          .finally(function(){ loading = false; });
      }

      function loadFirst(){
        if (loading) return;
        loading = true;
        list.classList.add('opacity-50');
        var fd = new FormData();
        fd.append('action','zdravoe_fetch_articles');
        fd.append('nonce', (window.ZdravoeTheme && ZdravoeTheme.articles_nonce) ? ZdravoeTheme.articles_nonce : '');
        fd.append('ppp', String(ppp));
        fd.append('offset', '0');
        fd.append('term', term);
        fetch(AJAX_URL, { method:'POST', body: fd })
          .then(function(r){ return r.json(); })
          .then(function(data){
            if (!data || !data.success) throw new Error('bad');
            var html = (data.data && data.data.html) ? data.data.html : '';
            list.innerHTML = html;
            offset = (data.data && typeof data.data.count==='number') ? data.data.count : list.querySelectorAll('[data-article-card]').length;
            done = !(data.data && data.data.has_more);
            if (emptyMsg) {
              if (offset === 0) emptyMsg.classList.remove('hidden');
              else emptyMsg.classList.add('hidden');
            }
          })
          .catch(function(){ /* noop */ })
          .finally(function(){ loading = false; list.classList.remove('opacity-50'); });
      }

      // IntersectionObserver for infinite scroll
      if (sentinel && 'IntersectionObserver' in window){
        var io = new IntersectionObserver(function(entries){
          entries.forEach(function(en){ if (en.isIntersecting) loadMore(); });
        }, { rootMargin: '200px' });
        io.observe(sentinel);
      }

      // Sync initial active button to current term
      setActiveByKey(term);

      // Filter clicks => restart list and fetch
      filter.addEventListener('click', function(e){
        var btn = e.target.closest('[data-filter]');
        if (!btn) return;
        e.preventDefault();
        term = btn.getAttribute('data-filter') || 'all';
        setActiveByKey(term);
        // reset counters and fetch first page without clearing immediately (to avoid flicker)
        offset = 0; done = false;
        if (emptyMsg) emptyMsg.classList.add('hidden');
        loadFirst();
      });
    })();
  }

  ready(init);
  document.addEventListener('turbo:load', init);
})();
