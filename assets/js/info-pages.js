/* Progressive enhancement of WordPress page content: retain every node and link. */
(function(){
 'use strict';
 const main=document.getElementById('sn-info-page');
 const content=document.getElementById('sn-info-content');
 if(!main||!content)return;
 const children=[...content.children];
 if(!children.length)return;
 // Existing section wrappers already express content boundaries.
 const hasSections=children.some(el=>el.tagName==='SECTION'||el.classList.contains('wp-block-group'));
 const make=(className)=>{const e=document.createElement('section');e.className=className;return e;};
 const grid=document.createElement('div');
 grid.className='sn-info-grid';
 if(hasSections){
   let pending=null;
   children.forEach(el=>{
     if(el.tagName==='SECTION'||el.classList.contains('wp-block-group')){
       if(pending && pending.children.length)grid.append(pending);
       pending=null;
       el.classList.add('sn-info-card');
       grid.append(el);
     }else{
       if(!pending)pending=make('sn-info-card sn-info-card--lead');
       pending.append(el);
     }
   });
   if(pending && pending.children.length)grid.append(pending);
 }else{
   // Split only at top-level headings. Nested Gutenberg structures stay intact.
   const headingLevel=children.some(el=>el.tagName==='H2')?'H2':
     children.some(el=>el.tagName==='H3')?'H3':null;
   if(!headingLevel)return;
   let section=null;
   children.forEach(el=>{
     if(el.tagName===headingLevel){
       if(section&&section.children.length)grid.append(section);
       section=make('sn-info-card');
     }
     if(!section)section=make('sn-info-card sn-info-card--lead');
     section.append(el);
   });
   if(section&&section.children.length)grid.append(section);
 }
 if(!grid.children.length)return;
 content.append(grid);
 content.classList.add('sn-info-enhanced');
})();
