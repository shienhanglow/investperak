(function(){
'use strict';
/* Illustrations used for gallery items without an uploaded photo */
  function peak(x,top,w,base,fill){return `<path d="M${x-w} ${base} C${x-w*.55} ${base} ${x-w*.62} ${top} ${x} ${top} C${x+w*.62} ${top} ${x+w*.55} ${base} ${x+w} ${base}Z" fill="${fill}"/>`}
  function range(base,pk,fill){return pk.map(p=>peak(p[0],p[1],p[2],base+2,fill)).join('')+`<rect x="0" y="${base}" width="400" height="${260-base}" fill="${fill}"/>`}
  function sky(id,a,b){return `<defs><linearGradient id="${id}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="${a}"/><stop offset="1" stop-color="${b}"/></linearGradient></defs><rect width="400" height="260" fill="url(#${id})"/>`}
  function palm(x,y,h){let s=`<path d="M${x} ${y} Q${x+6} ${y-h/2} ${x+2} ${y-h}" stroke="#5B4632" stroke-width="4" fill="none"/>`;[[-30,8],[30,6],[-22,-10],[24,-12],[0,-18]].forEach(([dx,dy])=>{s+=`<path d="M${x+2} ${y-h} Q${x+2+dx/2} ${y-h+dy-10} ${x+2+dx} ${y-h+dy+8}" stroke="#2F6B3A" stroke-width="5" fill="none" stroke-linecap="round"/>`});return s}
  let uid=0;
  const S={
    karst:g=>sky(g,'#F7D58B','#E9A866')+'<circle cx="300" cy="78" r="26" fill="#FFF1C2"/>'+range(150,[[60,70,55],[150,95,45],[250,60,60],[350,90,50]],'#7E9C8A')+range(185,[[30,120,40],[120,105,50],[220,125,45],[320,110,55]],'#3E6655')+range(220,[[80,175,50],[200,165,60],[340,180,50]],'#1F4436')+'<rect x="0" y="236" width="400" height="24" fill="#16302A"/><path d="M0 238 H400" stroke="#E9C77B" stroke-width="1" opacity=".4"/>',
    oldtown:g=>sky(g,'#BFDDE8','#EAF3F2')+range(140,[[90,70,60],[260,55,70]],'#93AFA0')+(()=>{let s='';const c=['#E8C7A0','#CFE0D6','#F0DDA0','#E6B9A8','#D7D6EC','#F2E3C6'];for(let i=0;i<6;i++){const x=i*68-6;s+=`<rect x="${x}" y="120" width="66" height="120" fill="${c[i]}" stroke="#8A6F55" stroke-width="1"/><path d="M${x} 120 h66 v-8 h-66z" fill="#8A6F55"/>`;for(let j=0;j<3;j++){s+=`<path d="M${x+8+j*19} 160 v-18 a7 7 0 0 1 14 0 v18z" fill="#4E5F5A"/>`}s+=`<rect x="${x+6}" y="196" width="54" height="44" fill="#3C3A34" opacity=".55"/>`}return s})()+'<rect x="0" y="238" width="400" height="22" fill="#9A9A92"/>',
    pangkor:g=>sky(g,'#7EC6DF','#D9F0F2')+'<path d="M220 150 C250 110 300 105 340 150Z" fill="#3E7B5C"/><rect x="0" y="148" width="400" height="60" fill="#2E8FAE"/><path d="M0 170 Q100 165 200 172 T400 168" stroke="#BFE7F0" stroke-width="2" fill="none" opacity=".7"/><path d="M0 205 Q120 190 400 200 L400 260 L0 260Z" fill="#EAD7A8"/>'+palm(60,230,90)+palm(110,236,70)+'<path d="M260 180 h50 l-8 10 h-36z" fill="#B5432F"/><path d="M282 180 v-22 l14 18z" fill="#F4F1E6"/>',
    belum:g=>sky(g,'#C9D9D2','#E9EFEA')+range(120,[[70,80,70],[230,70,80],[360,85,60]],'#8FAE9E')+range(150,[[20,115,50],[150,105,70],[300,112,70]],'#5F8C77')+'<rect x="0" y="168" width="400" height="92" fill="#6E9AA2"/><ellipse cx="120" cy="150" rx="140" ry="10" fill="#fff" opacity=".45"/><ellipse cx="300" cy="135" rx="120" ry="8" fill="#fff" opacity=".4"/><path d="M0 168 Q200 175 400 168" stroke="#A9C7CB" stroke-width="2" fill="none"/><path d="M40 210 h60" stroke="#C6DDE0" stroke-width="1.5"/><path d="M230 230 h90" stroke="#C6DDE0" stroke-width="1.5"/><path d="M180 60 q10 -8 20 0 q10 -8 20 0" stroke="#2C3B35" stroke-width="2.5" fill="none"/>',
    port:g=>sky(g,'#A9C7DC','#E7EEF2')+'<rect x="0" y="190" width="400" height="70" fill="#3F6E86"/><rect x="0" y="176" width="400" height="16" fill="#8E9590"/>'+(()=>{let s='';const c=['#B5432F','#2F6A57','#D9A400','#3E5C7A','#A65E2E'];for(let r=0;r<4;r++)for(let i=0;i<9;i++){s+=`<rect x="${150+i*26}" y="${164-r*12}" width="24" height="11" fill="${c[(i+r*2)%5]}"/>`}return s})()+'<g stroke="#D3483A" stroke-width="4" fill="none"><path d="M40 176 v-110 h90"/><path d="M60 176 v-110"/><path d="M40 90 l20 -24"/></g><path d="M110 66 v40" stroke="#333" stroke-width="1.5"/><path d="M20 200 h90 l-12 18 h-70z" fill="#E7EEF2"/><rect x="40" y="188" width="40" height="12" fill="#C9D3D8"/>',
    kerian:g=>sky(g,'#CFE3EE','#F3F6F2')+range(150,[[320,110,60]],'#A8BFB4')+'<rect x="0" y="150" width="400" height="110" fill="#B9C8A6"/>'+(()=>{let s='';for(let i=0;i<5;i++){s+=`<path d="M${30+i*36} 150 l18 -16 v16 l18 -16 v16" fill="#DADFDC" stroke="#7C8782"/>`}s+='<rect x="30" y="150" width="180" height="40" fill="#E6E9E7" stroke="#7C8782"/><rect x="40" y="162" width="160" height="6" fill="#2F6A57"/><rect x="230" y="128" width="16" height="62" fill="#9AA39F"/><rect x="260" y="140" width="110" height="50" fill="#EEF0EE" stroke="#7C8782"/>';for(let r=0;r<3;r++)for(let i=0;i<7;i++){s+=`<path d="M${20+i*54+r*8} ${210+r*16} l40 0 l-8 12 l-40 0z" fill="#2B4A6B" stroke="#A9C4DD" stroke-width=".8"/>`}return s})()+'<rect x="0" y="194" width="400" height="8" fill="#8C8F86"/>',
    taiping:g=>sky(g,'#D7E6E0','#EFF3EC')+range(110,[[80,40,90],[300,55,110]],'#6F8F86')+'<rect x="0" y="170" width="400" height="90" fill="#7FA6A4"/>'+(()=>{let s='';[[70,150,90],[230,140,110],[370,155,70]].forEach(([x,y,w])=>{s+=`<path d="M${x} ${y} v40" stroke="#4A3A2C" stroke-width="5"/><ellipse cx="${x}" cy="${y}" rx="${w}" ry="${w*.32}" fill="#3C6B4C"/><ellipse cx="${x-10}" cy="${y-8}" rx="${w*.7}" ry="${w*.2}" fill="#4F8160"/><path d="M${x-w*.8} ${y+6} q-6 30 -4 50 M${x+w*.8} ${y+6} q6 30 4 50" stroke="#3C6B4C" stroke-width="3" fill="none"/>`});return s})()+'<path d="M0 200 Q200 206 400 200" stroke="#B5D0CE" stroke-width="2" fill="none"/>',
    dredge:g=>sky(g,'#E7D9B8','#F2ECDC')+range(130,[[60,60,60],[200,45,70],[340,70,60]],'#8FA394')+'<rect x="0" y="190" width="400" height="70" fill="#7D8C73"/><path d="M0 192 h400" stroke="#A8B494"/><rect x="120" y="130" width="140" height="60" fill="#8A6A4A"/><rect x="140" y="104" width="90" height="28" fill="#9C7A55"/><rect x="170" y="86" width="40" height="20" fill="#A88760"/><path d="M110 190 h160 l-10 12 h-140z" fill="#5E4A36"/><path d="M260 150 L340 196" stroke="#4E3E2E" stroke-width="7"/>'+(()=>{let s='';for(let i=0;i<7;i++){s+=`<rect x="${264+i*11}" y="${148+i*6.5}" width="7" height="6" fill="#3A2E22"/>`}return s})()+'<path d="M150 120 h20 M190 96 h12" stroke="#5B4632" stroke-width="2"/>'
  };

  const svgOf=k=>S[k]?`<svg viewBox="0 0 400 260" preserveAspectRatio="xMidYMid slice" aria-hidden="true">${S[k]('sk'+(uid++))}</svg>`:'';
  const LS={get:k=>{try{return localStorage.getItem(k)}catch(e){return null}},set:(k,v)=>{try{localStorage.setItem(k,v)}catch(e){}}};

  /* ---------- gallery ---------- */
  document.querySelectorAll('.g-item').forEach(b=>{if(!b.dataset.img&&b.dataset.scene){b.querySelector('.ph').innerHTML=svgOf(b.dataset.scene)}});
  let lang='en',shown=[],cur=null;
  document.querySelectorAll('.filters').forEach(fs=>{
    const gal=fs.parentElement.querySelector('.gallery');
    fs.querySelectorAll('.chip').forEach(ch=>ch.addEventListener('click',()=>{
      fs.querySelectorAll('.chip').forEach(c=>c.setAttribute('aria-pressed','false'));ch.setAttribute('aria-pressed','true');
      const f=ch.dataset.f;gal.querySelectorAll('.g-item').forEach(b=>{b.hidden=!(f==='all'||b.dataset.c===f)});
    }));
  });
  const lb=document.getElementById('lb');let lastFocus=null;
  function openLb(btn){cur=btn;const ph=document.getElementById('lbPh');
    if(btn.dataset.img){ph.innerHTML='';const im=new Image();im.src=btn.dataset.img;im.alt=btn.dataset.t;ph.appendChild(im)}else ph.innerHTML=svgOf(btn.dataset.scene);
    document.getElementById('lbT').textContent=btn.dataset.t+(btn.dataset.p?' · '+btn.dataset.p:'');document.getElementById('lbD').textContent=btn.dataset.d||'';
    lb.hidden=false;document.getElementById('lbClose').focus()}
  function closeLb(){lb.hidden=true;if(lastFocus)lastFocus.focus()}
  function step(n){const gal=cur.closest('.gallery');shown=[...gal.querySelectorAll('.g-item:not([hidden])')];const i=shown.indexOf(cur);openLb(shown[(i+n+shown.length)%shown.length])}
  document.querySelectorAll('.gallery').forEach(gal=>gal.addEventListener('click',e=>{const b=e.target.closest('.g-item');if(b){lastFocus=b;openLb(b)}}));
  document.getElementById('lbClose').onclick=closeLb;document.getElementById('lbPrev').onclick=()=>step(-1);document.getElementById('lbNext').onclick=()=>step(1);
  lb.addEventListener('click',e=>{if(e.target===lb)closeLb()});
  document.addEventListener('keydown',e=>{if(lb.hidden)return;if(e.key==='Escape')closeLb();if(e.key==='ArrowRight')step(1);if(e.key==='ArrowLeft')step(-1)});

  /* ---------- routing ---------- */
  const pages=['home','why','guide','gallery','about','partners','faq','contact'];
  const nav=document.getElementById('mainNav'),menuBtn=document.getElementById('menuBtn');
  let curPage='home';
  function setTitle(){const site=document.body.dataset['site'+(lang==='zh'?'Zh':'En')]||'';const a=nav.querySelector(`a[href="#${curPage}"]`);document.title=curPage==='home'||!a?site:site+' · '+a.textContent}
  function route(){
    let h=(location.hash||'#home').slice(1);let anchor=null;
    if(!pages.includes(h)){const el=document.getElementById(h);if(el&&el.closest('.page')){anchor=el;h=el.closest('.page').dataset.page}else h='home'}
    curPage=h;
    document.querySelectorAll('.page').forEach(p=>p.classList.toggle('on',p.dataset.page===h));
    nav.querySelectorAll('a').forEach(a=>{if(a.getAttribute('href')==='#'+h)a.setAttribute('aria-current','page');else a.removeAttribute('aria-current')});
    nav.classList.remove('open');menuBtn.setAttribute('aria-expanded','false');
    if(anchor)anchor.scrollIntoView();else window.scrollTo(0,0);
    setTitle();
  }
  window.addEventListener('hashchange',route);
  menuBtn.addEventListener('click',()=>{const o=nav.classList.toggle('open');menuBtn.setAttribute('aria-expanded',o)});
  document.querySelectorAll('a[data-scroll]').forEach(a=>a.addEventListener('click',e=>{e.preventDefault();const t=document.getElementById(a.getAttribute('href').slice(1));if(t)t.scrollIntoView()}));

  /* ---------- language ---------- */
  function setLang(l,save){
    lang=l;document.documentElement.lang=l==='zh'?'zh-Hans':'en';
    document.getElementById('m-en').hidden=l!=='en';document.getElementById('m-zh').hidden=l!=='zh';
    document.querySelectorAll('[data-foot]').forEach(f=>f.hidden=f.dataset.foot!==l);
    document.querySelectorAll('[data-zh][data-en]').forEach(el=>{el.textContent=el.dataset[l]});
    document.querySelectorAll('[data-setlang]').forEach(b=>b.setAttribute('aria-pressed',String(b.dataset.setlang===l)));
    setTitle();if(save)LS.set('ip-lang',l);
  }
  document.querySelectorAll('[data-setlang]').forEach(b=>b.addEventListener('click',()=>setLang(b.dataset.setlang,true)));
  let start=LS.get('ip-lang');
  if(start!=='en'&&start!=='zh'){const q=new URLSearchParams(location.search).get('lang');start=(q==='zh'||(!q&&(navigator.language||'').toLowerCase().startsWith('zh')))?'zh':'en'}
  route();setLang(start,false);

  /* ---------- copy + enquiry form ---------- */
  document.querySelectorAll('.copy-mail').forEach(cb=>cb.addEventListener('click',()=>{
    const done=()=>{cb.textContent=cb.dataset.done;setTimeout(()=>cb.textContent=cb.dataset.label,2000)};
    if(navigator.clipboard){navigator.clipboard.writeText(cb.dataset.v).then(done,()=>{})}
  }));
  document.querySelectorAll('form.enq').forEach(form=>form.addEventListener('submit',async e=>{
    e.preventDefault();
    const err=form.querySelector('.form-err'),ok=form.querySelector('.form-ok'),btn=form.querySelector('button[type=submit]');
    const n=form.elements.name.value.trim(),m=form.elements.email.value.trim();
    ok.hidden=true;
    if(!n||!/^\S+@\S+\.\S+$/.test(m)){err.hidden=false;err.textContent=!n?form.dataset.errName:form.dataset.errEmail;return}
    err.hidden=true;btn.disabled=true;
    try{
      const r=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{'Accept':'application/json'}});
      const j=await r.json().catch(()=>({}));
      if(r.ok&&j.ok){ok.hidden=false;ok.textContent=form.dataset.ok;form.reset()}
      else{err.hidden=false;err.textContent=j.error||form.dataset.fail}
    }catch(x){err.hidden=false;err.textContent=form.dataset.fail}
    btn.disabled=false;
  }));
})();
