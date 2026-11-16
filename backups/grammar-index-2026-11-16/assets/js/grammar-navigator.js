/* SpanishNova Grammar: taxonomy explorer and live token search. */
(function () {
  'use strict';
  const root = document.getElementById('sn-grammar-navigator');
  if (!root) return;
  const dataElement = root.querySelector('#sn-grammar-data');
  if (!dataElement) return;
  let payload;
  try { payload = JSON.parse(dataElement.textContent); } catch (error) { return; }
  const input = root.querySelector('#sn-grammar-search');
  const clearButton = root.querySelector('#sn-grammar-clear');
  const nav = root.querySelector('#sn-grammar-sections');
  const breadcrumbs = root.querySelector('#sn-grammar-breadcrumbs');
  const content = root.querySelector('#sn-grammar-content');
  const terms = new Map(payload.terms.map(t => [t.id, t]));
  const lessons = payload.lessons || [];
  const preferred = ['tenses', 'verbs', 'moods', 'parts-of-speech'];
  const alias = { 'objeto':'object', 'indirecto':'indirect', 'directo':'direct', 'presente':'present', 'pasado':'past', 'futuro':'future', 'pronombres':'pronouns', 'verbos':'verbs', 'subjuntivo':'subjunctive' };
  const normalize = value => String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();
  const tokens = value => normalize(value).split(/\s+/).filter(Boolean).map(t => alias[t] || t);
  const rootId = id => {
    const seen = new Set();
    while (terms.has(id) && terms.get(id).parent && !seen.has(id)) {
      seen.add(id); id = terms.get(id).parent;
    }
    return id;
  };
  const pathOf = id => {
    const path = [], seen = new Set();
    while (id && terms.has(id) && !seen.has(id)) {
      path.unshift(id); seen.add(id); id = terms.get(id).parent;
    }
    return path;
  };
  const assigned = new Map();
  const counts = new Map();
  lessons.forEach(lesson => {
    assigned.set(lesson.id, pathOf(lesson.term));
    pathOf(lesson.term).forEach(id => counts.set(id, (counts.get(id) || 0) + 1));
  });
  const sorted = (a,b) => {
    const ai = preferred.indexOf(a.slug), bi = preferred.indexOf(b.slug);
    if (ai !== -1 || bi !== -1) {
      if (ai === -1) return 1;
      if (bi === -1) return -1;
      return ai - bi;
    }
    return a.name.localeCompare(b.name);
  };
  const roots = [...terms.values()].filter(t => !t.parent && counts.has(t.id)).sort(sorted);
  // Unclassified lessons remain available without inventing a taxonomy.
  const other = lessons.filter(l => !assigned.get(l.id).length);
  const otherRoot = { id: 0, name: 'Other grammar', slug: 'other', parent: -1 };
  if (other.length) roots.push(otherRoot);

  let selected = [], page = 0;
  const perPage = 12;
  const node = (tag, cls, value) => {
    const element = document.createElement(tag);
    if (cls) element.className = cls;
    if (value != null) element.textContent = value;
    return element;
  };
  const button = (label, cls, fn) => {
    const el = node('button', cls, label);
    el.type = 'button'; el.addEventListener('click', fn);
    return el;
  };
  const sectionLessons = path => lessons.filter(l => {
    const own = assigned.get(l.id);
    if (path.length === 1 && path[0] === 0) return !own.length;
    return path.every((id, index) => own[index] === id);
  });
  const directLessons = path => lessons.filter(l => {
    const own = assigned.get(l.id);
    return own.length === path.length && path.every((id,index) => own[index] === id);
  });
  function go(path) {
    selected = path; page = 0;
    input.value = '';
    render();
    root.scrollIntoView({block:'start', behavior:'smooth'});
  }
  function renderNav(query) {
    nav.replaceChildren();
    if (query) { nav.hidden = true; return; }
    nav.hidden = false;
    nav.append(button('All topics', 'sn-grammar-chip' + (!selected.length ? ' is-active' : ''), () => go([])));
    roots.forEach(t => {
      const active = selected[0] === t.id;
      nav.append(button(t.name, 'sn-grammar-chip' + (active ? ' is-active' : ''), () => go([t.id])));
    });
  }
  function renderCrumbs(query) {
    breadcrumbs.replaceChildren();
    if (query) { breadcrumbs.textContent = 'Search results'; return; }
    if (!selected.length) { breadcrumbs.textContent = 'Browse all grammar topics'; return; }
    breadcrumbs.append(button('Grammar', '', () => go([])));
    selected.forEach((id,index) => {
      breadcrumbs.append(node('span', 'sn-grammar-slash', ' / '));
      const t = terms.get(id) || otherRoot;
      if (index === selected.length - 1) breadcrumbs.append(node('span','',t.name));
      else breadcrumbs.append(button(t.name, '', () => go(selected.slice(0,index+1))));
    });
  }
  function heading(title, count) {
    const div = node('div','sn-grammar-group-heading');
    div.append(node('h2','',title), node('span','',count));
    return div;
  }
  function topicCard(term, path, count) {
    const card = button('', 'sn-grammar-topic-card', () => go(path));
    card.append(node('strong','',term.name));
    card.append(node('span','sn-grammar-topic-count',count + (count === 1 ? ' lesson →' : ' lessons →')));
    return card;
  }
  function renderTopics(path, isRootIndex) {
    const branches = isRootIndex ? roots : [...terms.values()]
      .filter(t => t.parent === path[path.length - 1] && counts.has(t.id)).sort(sorted);
    if (isRootIndex) {
      branches.forEach(section => {
        const sectionPath = [section.id];
        const childTerms = [...terms.values()].filter(t => t.parent === section.id && counts.has(t.id)).sort(sorted);
        const sectionNode = node('section','sn-grammar-group');
        sectionNode.append(heading(section.name, section.id === 0 ? other.length + ' lessons' : counts.get(section.id) + ' lessons'));
        const cards = node('div','sn-grammar-topic-grid');
        if (childTerms.length) {
          childTerms.forEach(child => cards.append(topicCard(child,[section.id,child.id],counts.get(child.id))));
          const direct = directLessons(sectionPath);
          if (direct.length) cards.append(topicCard({name:'General '+section.name}, sectionPath.concat(-1),direct.length));
        } else {
          cards.append(topicCard({name:'Browse '+section.name}, sectionPath,section.id === 0 ? other.length : counts.get(section.id)));
        }
        sectionNode.append(cards);
        content.append(sectionNode);
      });
      return;
    }
    const current = terms.get(path[path.length - 1]) || otherRoot;
    const section = node('section','sn-grammar-group');
    const children = branches;
    if (path[path.length - 1] === -1 || children.length === 0) { renderLessons(path); return; }
    section.append(heading(current.name, children.length + ' topics'));
    const cards = node('div','sn-grammar-topic-grid');
    children.forEach(t => cards.append(topicCard(t, path.concat(t.id), counts.get(t.id))));
    const direct = directLessons(path);
    if (direct.length) cards.append(topicCard({name:'General '+current.name},path.concat(-1),direct.length));
    section.append(cards);
    content.append(section);
  }
  function lessonCard(lesson) {
    const card = node('a','sn-grammar-lesson-card');
    card.href = lesson.url;
    card.append(node('strong','',lesson.title), node('p','',lesson.description), node('span','sn-grammar-lesson-link','Read lesson →'));
    return card;
  }
  function renderLessons(path) {
    const directOnly = path[path.length-1] === -1;
    const scope = directOnly ? path.slice(0,-1) : path;
    const matches = directOnly ? directLessons(scope) : sectionLessons(scope);
    const name = directOnly ? 'General '+(terms.get(scope[scope.length-1])?.name || 'Grammar') : (terms.get(scope[scope.length-1])?.name || otherRoot.name);
    const wrap = node('section','sn-grammar-group');
    wrap.append(heading(name, matches.length + ' lessons'));
    const grid = node('div','sn-grammar-lesson-grid');
    matches.slice(page*perPage, (page+1)*perPage).forEach(l=>grid.append(lessonCard(l)));
    wrap.append(grid);
    if (matches.length > perPage) {
      const pager = node('nav','sn-grammar-pager');
      pager.setAttribute('aria-label','Lesson pages');
      const prev = button('← Previous','',()=>{page--;render()});
      const next = button('Next →','',()=>{page++;render()});
      prev.disabled = page === 0;
      next.disabled = (page+1)*perPage >= matches.length;
      pager.append(prev, node('span','',(page+1)+' / '+Math.ceil(matches.length/perPage)), next);
      wrap.append(pager);
    }
    content.append(wrap);
  }
  const searchIndex = lessons.map(lesson => {
    const path = assigned.get(lesson.id);
    const label = path.map(id=>terms.get(id)?.name || '').join(' → ') || otherRoot.name;
    const titleTokens = tokens(lesson.title);
    const topicTokens = tokens(label);
    const bodyTokens = tokens(lesson.description + ' ' + lesson.keywords);
    return { lesson, label, titleTokens, topicTokens, bodyTokens };
  });
  function score(record, words) {
    let total = 0;
    for (const word of words) {
      // Every fragment must match a word boundary; tokens can be in any order.
      if (record.titleTokens.some(token=>token.startsWith(word))) total += 10;
      else if (record.topicTokens.some(token=>token.startsWith(word))) total += 5;
      else if (record.bodyTokens.some(token=>token.startsWith(word))) total += 2;
      else return -1;
    }
    return total;
  }
  function renderSearch(query) {
    const words = tokens(query);
    const matches = searchIndex.map(record=>({...record, relevance:score(record,words)}))
      .filter(record=>record.relevance >= 0)
      .sort((a,b)=>b.relevance-a.relevance || a.lesson.title.localeCompare(b.lesson.title));
    content.append(heading('Results', matches.length+' matches'));
    if (!matches.length) { content.append(node('p','sn-grammar-no-results','No matches. Try another keyword or a shorter word.'));return; }
    const list = node('div','sn-grammar-results');
    matches.slice(0,50).forEach(match=>{
      const row = node('a','sn-grammar-result');
      row.href=match.lesson.url;
      const info=node('div','');
      info.append(node('span','sn-grammar-result-path',match.label),node('strong','',match.lesson.title),node('p','',match.lesson.description));
      row.append(info,node('span','sn-grammar-result-arrow','→'));
      list.append(row);
    });
    content.append(list);
    if(matches.length>50)content.append(node('p','sn-grammar-more-results','Showing 50 results. Add another keyword to refine your search.'));
  }
  function render() {
    const q = input.value.trim();
    clearButton.hidden = !q;
    renderNav(q);
    renderCrumbs(q);
    content.replaceChildren();
    if (q) { renderSearch(q);return; }
    if (!selected.length) { renderTopics([],true);return; }
    renderTopics(selected,false);
  }
  // Hide initially empty taxonomy sections. Search reflects new published lessons on reload.
  input.addEventListener('input', render);
  clearButton.addEventListener('click',()=>{input.value='';input.focus();render()});
  render();
})();
