/* Grammar index: all assigned grammar_tax relationships, compact navigation. */
(function () {
  'use strict';
  const root = document.getElementById('sn-grammar-navigator');
  if (!root) return;
  let data;
  try { data = JSON.parse(root.querySelector('#sn-grammar-data').textContent); }
  catch (_) { return; }
  const input = root.querySelector('#sn-grammar-search');
  const clear = root.querySelector('#sn-grammar-clear');
  const nav = root.querySelector('#sn-grammar-sections');
  const crumbs = root.querySelector('#sn-grammar-breadcrumbs');
  const content = root.querySelector('#sn-grammar-content');
  const terms = new Map((data.terms || []).map(t => [t.id, t]));
  const lessons = data.lessons || [];
  const priority = ['tenses','verbs','moods','parts-of-speech','sentence-structure'];
  const aliases = {objeto:'object',indirecto:'indirect',directo:'direct',presente:'present',pasado:'past',futuro:'future',pronombres:'pronouns',subjuntivo:'subjunctive'};
  const norm = v => String(v || '').normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,' ').trim();
  const tokenize = v => norm(v).split(/\s+/).filter(Boolean).map(t => aliases[t] || t);
  const node = (tag,cls,txt) => { const e=document.createElement(tag);if(cls)e.className=cls;if(txt!==undefined)e.textContent=txt;return e; };
  const button = (txt,cls,fn) => {const e=node('button',cls,txt);e.type='button';e.addEventListener('click',fn);return e;};
  const sortTerms = (a,b) => {
    const ai=priority.indexOf(a.slug), bi=priority.indexOf(b.slug);
    if (ai!==-1 || bi!==-1) return ai===-1?1:bi===-1?-1:ai-bi;
    return a.name.localeCompare(b.name);
  };
  const ancestorPath = id => {
    const p=[], seen=new Set();
    while(id && terms.has(id) && !seen.has(id)){
      seen.add(id);p.unshift(id);id=terms.get(id).parent;
    }
    return p;
  };
  const lessonPaths=new Map();
  const under=new Map();
  const direct=new Map();
  const missing=[];
  lessons.forEach(l=>{
    const ids=[...new Set((l.terms||[]).filter(id=>terms.has(id)))];
    if (!ids.length) missing.push(l);
    const fullPaths=ids.map(ancestorPath);
    lessonPaths.set(l.id,fullPaths);
    ids.forEach(id=>{
      if(!direct.has(id))direct.set(id,new Set());
      direct.get(id).add(l.id);
    });
    fullPaths.forEach(path=>path.forEach(id=>{
      if(!under.has(id))under.set(id,new Set());
      under.get(id).add(l.id);
    }));
  });
  const roots=[...terms.values()].filter(t=>!t.parent && under.has(t.id)).sort(sortTerms);
  let path=[], page=0;
  const pageSize=12;
  const current = () => path.length ? terms.get(path[path.length-1]) : null;
  const children = id => [...terms.values()].filter(t=>t.parent===id && under.has(t.id)).sort(sortTerms);
  const count = id => (under.get(id)||new Set()).size;
  const total = (list,word) => list.length+' '+(list.length===1?word:word+'s');
  function go(ids){path=ids;page=0;input.value='';render();root.scrollIntoView({behavior:'smooth',block:'start'});}
  function heading(txt,meta){
    const d=node('div','sn-grammar-group-heading');
    d.append(node('h2','',txt),node('span','',meta));return d;
  }
  function renderNavigation(q){
    nav.replaceChildren();crumbs.replaceChildren();nav.hidden=!!q;
    if(q){crumbs.textContent='Search results';return;}
    nav.append(button('All topics','sn-grammar-chip'+(!path.length?' is-active':''),()=>go([])));
    roots.forEach(t=>nav.append(button(t.name,'sn-grammar-chip'+(path[0]===t.id?' is-active':''),()=>go([t.id]))));
    if(!path.length){crumbs.textContent='Browse by category';return;}
    crumbs.append(button('Grammar','',()=>go([])));
    path.forEach((id,i)=>{
      crumbs.append(node('span','sn-grammar-slash',' / '));
      const t=terms.get(id);
      if(i===path.length-1)crumbs.append(node('span','',t.name));
      else crumbs.append(button(t.name,'',()=>go(path.slice(0,i+1))));
    });
  }
  function topicRow(t,target,level=0){
    const link=button('','sn-grammar-index-row',()=>go(target));
    link.append(node('span','sn-grammar-index-name',t.name));
    link.append(node('span','sn-grammar-index-count',total(count(t.id),'lesson')));
    link.append(node('span','sn-grammar-index-arrow','→'));
    return link;
  }
  function section(term){
    const wrapper=node('section','sn-grammar-group');
    wrapper.append(heading(term.name,total(count(term.id),'lesson')));
    const list=node('div','sn-grammar-index-list');
    const sub=children(term.id);
    if(sub.length)sub.forEach(t=>list.append(topicRow(t,[term.id,t.id])));
    else list.append(button('Browse '+term.name+'  →','sn-grammar-index-row',()=>go([term.id])));
    wrapper.append(list);content.append(wrapper);
  }
  function lessonRow(l){
    const a=node('a','sn-grammar-list-lesson');a.href=l.url;
    const title=node('strong','',l.title), desc=node('p','',l.description);
    a.append(node('div','sn-grammar-list-lesson-body'),node('span','sn-grammar-index-arrow','→'));
    a.firstChild.append(title,desc);return a;
  }
  function listing(entries,label){
    const wrapper=node('section','sn-grammar-group');
    wrapper.append(heading(label,total(entries.length,'lesson')));
    const list=node('div','sn-grammar-lesson-list');
    entries.slice(page*pageSize,(page+1)*pageSize).forEach(l=>list.append(lessonRow(l)));
    wrapper.append(list);
    if(entries.length>pageSize){
      const pages=node('nav','sn-grammar-pager');pages.setAttribute('aria-label','Lesson pages');
      const prev=button('← Previous','',()=>{page--;render()});
      const next=button('Next →','',()=>{page++;render()});
      prev.disabled=page===0;next.disabled=(page+1)*pageSize>=entries.length;
      pages.append(prev,node('span','',(page+1)+' / '+Math.ceil(entries.length/pageSize)),next);wrapper.append(pages);
    }
    content.append(wrapper);
  }
  function listFor(id){return lessons.filter(l=>under.get(id)?.has(l.id));}
  function indexView(){
    if(!path.length){
      roots.forEach(section);
      if(missing.length){
        const wrap=node('section','sn-grammar-group sn-grammar-unclassified');
        wrap.append(heading('Lessons without a grammar category',total(missing.length,'lesson')));
        wrap.append(button('View uncategorized lessons →','sn-grammar-index-row',()=>{path=[-1];page=0;render()}));
        content.append(wrap);
      }
      return;
    }
    if(path[0]===-1){listing(missing,'Lessons without a grammar category');return;}
    const id=path[path.length-1], t=terms.get(id);
    if(!t){go([]);return;}
    const sub=children(id);
    if(sub.length){
      const wrap=node('section','sn-grammar-group');
      wrap.append(heading(t.name,total(count(id),'lesson')));
      const list=node('div','sn-grammar-index-list');
      sub.forEach(child=>list.append(topicRow(child,path.concat(child.id))));
      wrap.append(list);content.append(wrap);
      const ids=direct.get(id)||new Set();
      if(ids.size){
        const directLessons=lessons.filter(l=>ids.has(l.id));
        const directWrap=node('div','sn-grammar-direct');
        directWrap.append(node('h3','','Lessons tagged '+t.name));
        directLessons.slice(0,8).forEach(l=>directWrap.append(lessonRow(l)));
        if(directLessons.length>8){
          const more=button('View all '+total(directLessons.length,'lesson')+' →','sn-grammar-more',()=>{path=path.concat(-2);page=0;render()});
          directWrap.append(more);
        }
        content.append(directWrap);
      }
    }else listing(listFor(id),t.name);
  }
  const searchIndex=lessons.map(l=>{
    const categories=[...new Set((l.terms||[]).flatMap(id=>ancestorPath(id).map(tid=>terms.get(tid)?.name||'')))];
    return {lesson:l,category:categories.join(' · '),
      title:tokenize(l.title),terms:tokenize(categories.join(' ')),
      other:tokenize(l.description+' '+l.keywords)};
  });
  function relevance(entry,words){
    let points=0;
    for(const word of words){
      if(entry.title.some(t=>t.startsWith(word)))points+=10;
      else if(entry.terms.some(t=>t.startsWith(word)))points+=5;
      else if(entry.other.some(t=>t.startsWith(word)))points+=2;
      else return -1;
    }
    return points;
  }
  function searchView(query){
    const searchWords=tokenize(query);
    const matches=searchIndex.map(e=>({...e,score:relevance(e,searchWords)}))
      .filter(e=>e.score>=0)
      .sort((a,b)=>b.score-a.score||a.lesson.title.localeCompare(b.lesson.title));
    content.append(heading('Results',total(matches.length,'match')));
    if(!matches.length){content.append(node('p','sn-grammar-no-results','No matches. Try another keyword.'));return;}
    const list=node('div','sn-grammar-lesson-list');
    matches.slice(page*pageSize,(page+1)*pageSize).forEach(e=>{
      const a=lessonRow(e.lesson);
      a.firstChild.insertBefore(node('span','sn-grammar-result-path',e.category||'Uncategorized'),a.firstChild.firstChild);
      list.append(a);
    });content.append(list);
    if(matches.length>pageSize){
      const pages=node('nav','sn-grammar-pager');
      const prev=button('← Previous','',()=>{page--;render()});
      const next=button('Next →','',()=>{page++;render()});
      prev.disabled=page===0;next.disabled=(page+1)*pageSize>=matches.length;
      pages.append(prev,node('span','',(page+1)+' / '+Math.ceil(matches.length/pageSize)),next);
      content.append(pages);
    }
  }
  function render(){
    const q=input.value.trim();clear.hidden=!q;
    renderNavigation(q);content.replaceChildren();
    if(q){searchView(q);return;}
    if(path[path.length-1]===-2){
      const id=path[path.length-2],rows=lessons.filter(l=>direct.get(id)?.has(l.id));
      listing(rows,'Lessons tagged '+(terms.get(id)?.name||''));return;
    }
    indexView();
  }
  input.addEventListener('input',()=>{page=0;render()});
  clear.addEventListener('click',()=>{input.value='';page=0;input.focus();render()});
  render();
})();
