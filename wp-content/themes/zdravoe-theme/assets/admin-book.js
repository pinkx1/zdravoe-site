;(function(){
  function ready(fn){ document.readyState!=='loading'?fn():document.addEventListener('DOMContentLoaded',fn) }
  ready(function(){
    var wrap = document.getElementById('zdravoe-book-chapters');
    var addBtn = document.getElementById('zdravoe-book-add');
    var tpl = document.getElementById('zdravoe-book-chapter-tpl');
    if(!wrap || !addBtn || !tpl) return;

    function initEditorFor(item){
      var ta = item.querySelector('textarea.zdravoe-editor, textarea[id^="zdravoe_book_chapter_"]');
      if (!ta) return;
      if (ta.dataset.inited === '1') return;
      ta.dataset.inited = '1';
      if (window.wp && wp.editor && typeof wp.editor.initialize === 'function'){
        wp.editor.initialize(ta.id, {
          tinymce: { wpautop: true, toolbar1: 'formatselect,bold,italic,underline,blockquote,link,unlink,bullist,numlist,alignleft,aligncenter,alignright,undo,redo', toolbar2: '', height: 280 },
          quicktags: true,
          mediaButtons: true
        });
      }
    }

    function bindItem(item){
      // init editor for newly added block
      initEditorFor(item);

      var remove = item.querySelector('.zdravoe-chapter-remove');
      if(remove){
        remove.addEventListener('click', function(){
          var items = wrap.querySelectorAll('.zdravoe-chapter-item');
          var ta = item.querySelector('textarea');
          if (ta && window.wp && wp.editor && typeof wp.editor.remove === 'function') {
            try { wp.editor.remove(ta.id); } catch(e){}
          }
          if(items.length>1){ item.remove(); }
          else {
            // if single item: just clear fields and editor
            var i1=item.querySelector('input[type="text"]'); if(i1) i1.value='';
            if (ta){ ta.value=''; if (window.tinymce) { var ed = tinymce.get(ta.id); if (ed) ed.setContent(''); } }
          }
        });
      }
    }

    // bind existing ones
    Array.prototype.forEach.call(wrap.querySelectorAll('.zdravoe-chapter-item'), bindItem);

    addBtn.addEventListener('click', function(){
      var idx = parseInt(wrap.getAttribute('data-index')||'0',10);
      var html = tpl.innerHTML.replace(/__INDEX__/g, String(idx));
      var temp = document.createElement('div');
      temp.innerHTML = html.trim();
      var item = temp.firstChild;
      wrap.appendChild(item);
      wrap.setAttribute('data-index', String(idx+1));
      bindItem(item);
    });
  });
})();

