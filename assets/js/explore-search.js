/* Live search for Explore (Vocabulary, Readings, Conversations). */
(function () {
  'use strict';
  const root = document.getElementById('sn-explore');
  if (!root) return;
  const source = root.querySelector('#sn-explore-data');
  if (!source) return;
  let data;
  try { data = JSON.parse(source.textContent); } catch (error) { return; }

  const input = root.querySelector('#sn-explore-query');
  const clear = root.querySelector('#sn-explore-clear');
  const topicsNode = root.querySelector('#sn-explore-topics');
  const topicArea = root.querySelector('#sn-explore-topic-area');
  const resultsNode = root.querySelector('#sn-explore-results');
  const countNode = root.querySelector('#sn-explore-count');
  const resultsTitle = root.querySelector('#sn-explore-results-title');
  const pager = root.querySelector('#sn-explore-pagination');
  const items = data.items || [];
  const topics = data.topics || [];
  const aliases = { 'dormir':'sleep', 'sueno':'sleep', 'trabajo':'work', 'comida':'food', 'viaje':'travel', 'familia':'family' };
  const normalize = value => String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,' ').trim();
  const words = text => normalize(text).split(/\s+/).filter(Boolean).map(word => aliases[word] || word);
  const button = (label, className, action) => {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = className;
    b.textContent = label;
    b.addEventListener('click', action);
    return b;
  };
  const node = (tag, className, text) => {
    const element = document.createElement(tag);
    if (className) element.className = className;
    if (text != null) element.textContent = text;
    return element;
  };

  const initial = topics.find(topic => topic.slug === data.selectedTopic);
  let topic = initial ? initial.id : null;
  let page = 0;
  const perPage = 12;
  const indexed = items.map(item => ({
    item,
    title: words(item.title),
    topic: words(item.topicNames.join(' ') + ' ' + item.typeLabel),
    other: words(item.description + ' ' + item.keywords + ' ' + item.level),
  }));
  const relevance = (entry, query) => {
    let score = 0;
    for (const token of query) {
      if (entry.title.some(part => part.startsWith(token))) score += 10;
      else if (entry.topic.some(part => part.startsWith(token))) score += 6;
      else if (entry.other.some(part => part.startsWith(token))) score += 2;
      else return -1;
    }
    return score;
  };
  function changeTopic(id) {
    topic = id;
    page = 0;
    render();
  }
  function renderTopics() {
    topicsNode.replaceChildren();
    topicArea.hidden = topics.length === 0;
    if (!topics.length) return;
    topicsNode.append(button('All topics','sn-explore-topic' + (topic === null ? ' is-active' : ''), () => changeTopic(null)));
    topics.forEach(item => {
      topicsNode.append(button(item.name,'sn-explore-topic' + (topic === item.id ? ' is-active' : ''), () => changeTopic(item.id)));
    });
  }
  function renderCard(item) {
    const link = node('a', 'explore-result explore-result--' + item.type);
    link.href = item.url;
    const meta = node('div','explore-result-meta');
    meta.append(node('span','explore-result-type',item.typeLabel),node('span','explore-result-level',item.level));
    link.append(meta,node('h3','explore-result-title',item.title),node('p','explore-result-description',item.description));
    if (item.topicNames.length) link.append(node('p','explore-result-topics','Topics: ' + item.topicNames.join(', ')));
    link.append(node('span','explore-result-arrow','→'));
    return link;
  }
  function render() {
    const q = input.value.trim();
    const query = words(q);
    clear.hidden = !q;
    const matching = indexed.map(entry => ({ ...entry, score: query.length ? relevance(entry,query) : 0 }))
      .filter(entry => entry.score >= 0 && (topic === null || entry.item.topics.includes(topic)))
      .sort((a,b) => query.length ? b.score-a.score : 0);
    const count = matching.length;
    if (page * perPage >= count && page) page = 0;
    resultsTitle.textContent = q ? 'Search results' : (topic === null ? 'Lessons' : topics.find(t => t.id === topic)?.name || 'Lessons');
    countNode.textContent = count + (count === 1 ? ' result' : ' results');
    resultsNode.replaceChildren();
    if (!count) resultsNode.append(node('p','sn-explore-empty','No matches. Try another keyword or topic.'));
    matching.slice(page * perPage, (page + 1) * perPage).forEach(entry => resultsNode.append(renderCard(entry.item)));
    pager.replaceChildren();
    if (count > perPage) {
      const prev = button('← Previous','',()=>{page--;render()});
      const next = button('Next →','',()=>{page++;render()});
      prev.disabled = page === 0;
      next.disabled = (page+1)*perPage >= count;
      pager.append(prev,node('span','',(page+1)+' / '+Math.ceil(count/perPage)),next);
    }
  }
  input.addEventListener('input',()=>{page=0;render()});
  clear.addEventListener('click',()=>{input.value='';page=0;input.focus();render()});
  renderTopics();
  render();
})();
