{{--
    ╔══════════════════════════════════════════════════╗
    ║  STYLES COMMUNS — à coller dans <head> de        ║
    ║  resources/views/layouts/app.blade.php           ║
    ╚══════════════════════════════════════════════════╝
    Ou créer resources/views/components/ui-styles.blade.php
    et appeler @include('components.ui-styles') dans app.blade.php
--}}
<style>
/* ── Tokens ── */
:root {
    --white:   #ffffff;
    --bg:      #f3f5fb;
    --border:  #e5e8f0;
    --accent:  #2563eb;
    --accent2: #1d4ed8;
    --acc-bg:  #eff4ff;
    --acc-txt: #1d4ed8;
    --t1:      #0f172a;
    --t2:      #475569;
    --t3:      #94a3b8;
    --ok:      #059669;  --ok-bg: #ecfdf5;  --ok-border: #a7f3d0;
    --warn:    #d97706;  --warn-bg:#fffbeb; --warn-border:#fcd34d;
    --err:     #dc2626;  --err-bg: #fef2f2; --err-border: #fecaca;
    --sh-sm:   0 1px 3px rgba(0,0,0,.06);
    --sh:      0 4px 16px rgba(0,0,0,.07);
    --r:       10px;
}

/* ── Base ── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Card ── */
.ucard {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--sh-sm);
}
.ucard-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: .9rem 1.25rem;
    border-bottom: 1px solid var(--border);
}
.ucard-title {
    font-size: .9rem; font-weight: 700; color: var(--t1);
    display: flex; align-items: center; gap: .5rem;
}
.ucard-title svg { width: 16px; height: 16px; color: var(--accent); }
.ucard-body { padding: 1.25rem; }

/* ── Page wrapper ── */
.upg { max-width: 900px; margin: 0 auto; padding: 1.75rem 1.25rem; display: flex; flex-direction: column; gap: 1.25rem; }
.upg-wide { max-width: 1100px; }

/* ── Page header ── */
.upg-hd { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.upg-title { font-size: 1.2rem; font-weight: 800; color: var(--t1); letter-spacing: -.02em; }
.upg-sub   { font-size: .78rem; color: var(--t3); margin-top: .15rem; }

/* ── Alerts ── */
.ua-ok  { display:flex; align-items:center; gap:.6rem; padding:.7rem 1rem; background:var(--ok-bg);   border:1px solid var(--ok-border);   border-radius:9px; color:var(--ok);   font-size:.8rem; font-weight:600; }
.ua-err { display:flex; align-items:center; gap:.6rem; padding:.7rem 1rem; background:var(--err-bg);  border:1px solid var(--err-border);  border-radius:9px; color:var(--err);  font-size:.8rem; font-weight:600; }
.ua-ok svg, .ua-err svg { width:15px; height:15px; flex-shrink:0; }

/* ── Form fields ── */
.ufield { display: flex; flex-direction: column; gap: .35rem; }
.ufield label { font-size: .75rem; font-weight: 700; color: var(--t2); letter-spacing: .04em; }
.ufield input, .ufield select, .ufield textarea {
    width: 100%; padding: .55rem .8rem;
    border: 1px solid var(--border); border-radius: 8px;
    font-size: .84rem; font-family: inherit; color: var(--t1);
    background: var(--white); outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.ufield input:focus, .ufield select:focus, .ufield textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}
.ufield input[type="file"] { padding: .45rem .75rem; background: var(--bg); cursor: pointer; }
.ufield textarea { resize: vertical; min-height: 80px; }
.ufield-hint { font-size: .71rem; color: var(--t3); }

/* ── Form grid ── */
.ugrid { display: grid; gap: 1rem; }
.ugrid-2 { grid-template-columns: 1fr 1fr; }
@media(max-width:640px){ .ugrid-2 { grid-template-columns: 1fr; } }

/* ── Buttons ── */
.ubtn { display:inline-flex; align-items:center; gap:.4rem; font-size:.8rem; font-weight:700; padding:.5rem 1rem; border-radius:8px; border:none; cursor:pointer; transition:all .15s; text-decoration:none; white-space:nowrap; }
.ubtn svg { width:14px; height:14px; }
.ubtn-primary { background:var(--accent); color:#fff; }
.ubtn-primary:hover { background:var(--accent2); }
.ubtn-secondary { background:var(--bg); color:var(--t2); border:1px solid var(--border); }
.ubtn-secondary:hover { background:#e8ecf7; color:var(--t1); }
.ubtn-warn { background:#fffbeb; color:var(--warn); border:1px solid #fcd34d; }
.ubtn-warn:hover { background:#fef3c7; }
.ubtn-danger { background:var(--err-bg); color:var(--err); border:1px solid var(--err-border); }
.ubtn-danger:hover { background:#fee2e2; }
.ubtn-ok { background:var(--ok-bg); color:var(--ok); border:1px solid var(--ok-border); }
.ubtn-ok:hover { background:#d1fae5; }
.ubtn-sm { font-size:.74rem; padding:.36rem .7rem; }

/* ── Action bar ── */
.uactions { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }

/* ── Table ── */
.utbl-wrap { overflow-x: auto; }
.utbl { width:100%; border-collapse:collapse; font-size:.8rem; }
.utbl th { text-align:left; padding:.55rem 1rem; font-size:.63rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--t3); background:#f8f9fd; border-bottom:1px solid var(--border); white-space:nowrap; }
.utbl td { padding:.72rem 1rem; color:var(--t2); border-bottom:1px solid var(--border); vertical-align:middle; }
.utbl tr:last-child td { border-bottom:none; }
.utbl tbody tr:hover td { background:#f8f9fd; }
.utbl-nm { font-weight:600; color:var(--t1); }
.utbl-mono { font-family:monospace; font-size:.78rem; font-weight:700; color:var(--t1); }

/* ── Badges ── */
.ubadge { display:inline-flex; align-items:center; gap:.28rem; font-size:.67rem; font-weight:700; padding:2px 8px; border-radius:20px; }
.ubadge::before { content:''; width:5px; height:5px; border-radius:50%; background:currentColor; }
.ub-blue   { background:#dbeafe; color:#1d4ed8; }
.ub-green  { background:#d1fae5; color:#065f46; }
.ub-yellow { background:#fef9c3; color:#92400e; }
.ub-red    { background:#fee2e2; color:#991b1b; }
.ub-gray   { background:#f1f5f9; color:#475569; }
.ub-purple { background:#ede9fe; color:#5b21b6; }

/* ── Icon button ── */
.uib { width:30px; height:30px; border-radius:7px; display:flex; align-items:center; justify-content:center; border:1px solid var(--border); background:#f8f9fd; color:var(--t2); cursor:pointer; transition:all .15s; text-decoration:none; flex-shrink:0; }
.uib:hover { background:#eaedfa; }
.uib svg { width:13px; height:13px; }
.uib-eye    { color:#2563eb; border-color:#bfdbfe; background:#eff6ff; } .uib-eye:hover  { background:#dbeafe; }
.uib-edit   { color:#d97706; border-color:#fcd34d; background:#fffbeb; } .uib-edit:hover { background:#fef3c7; }
.uib-del    { color:var(--err); border-color:#fecaca; background:var(--err-bg); } .uib-del:hover { background:#fee2e2; }
.uib-doc    { color:#7c3aed; border-color:#ddd6fe; background:#f5f3ff; } .uib-doc:hover  { background:#ede9fe; }
.uib-ok     { color:var(--ok); border-color:var(--ok-border); background:var(--ok-bg); } .uib-ok:hover { background:#d1fae5; }

/* ── Info grid (detail view) ── */
.uinfo-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.75rem; }
.uinfo-field { display:flex; flex-direction:column; gap:.1rem; }
.uinfo-label { font-size:.65rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--t3); }
.uinfo-value { font-size:.84rem; font-weight:500; color:var(--t1); }

/* ── Section icon ── */
.usec-ico { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; }
.usec-ico svg { width:14px; height:14px; }

/* ── File link ── */
.ufile-link { display:inline-flex; align-items:center; gap:.35rem; font-size:.78rem; font-weight:600; color:var(--accent); text-decoration:none; transition:opacity .15s; }
.ufile-link:hover { opacity:.7; }
.ufile-link svg { width:13px; height:13px; }

/* ── File upload zone ── */
.ufile-zone { border:2px dashed var(--border); border-radius:9px; padding:.9rem 1rem; background:var(--bg); display:flex; align-items:center; gap:.75rem; font-size:.8rem; color:var(--t3); }
.ufile-zone input[type="file"] { flex:1; border:none; background:transparent; font-size:.8rem; color:var(--t2); padding:0; outline:none; cursor:pointer; }

/* ── Empty state ── */
.uempty { text-align:center; padding:2.5rem 1rem; color:var(--t3); font-size:.82rem; }
.uempty svg { width:36px; height:36px; opacity:.2; margin:0 auto .65rem; display:block; }

/* Filters */
.filters { display:flex; align-items:center; gap:.45rem; flex-wrap:wrap; }
.flt { font-size:.74rem; font-weight:600; padding:.32rem .8rem; border-radius:20px; border:1px solid var(--border); background:var(--white); color:var(--t2); cursor:pointer; text-decoration:none; transition:all .15s; white-space:nowrap; }
.flt:hover { background:#eaedfa; color:var(--accent); border-color:#c7d0f5; }
.flt.on { background:var(--accent); color:#fff; border-color:var(--accent); }

/* Bouton Mettre en Traitement inline (avec label) */
.bv {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .71rem; font-weight: 700;
    padding: .28rem .65rem; border-radius: 6px;
    border: 1px solid #a7f3d0; background: #ecfdf5; color: #059669;
    cursor: pointer; transition: all .15s;
}
.bv:hover   { background: #d1fae5; }
.bv svg     { width: 12px; height: 12px; }

/* ── Dropdown Actions ── */
.act-wrap { position:relative; display:inline-block; }

.act-btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.32rem .75rem; border-radius:7px;
    border:1px solid var(--border); background:#f8f9fb;
    font-size:.75rem; font-weight:600; color:var(--t2);
    cursor:pointer; transition:all .15s; white-space:nowrap;
    box-shadow:0 1px 2px rgba(0,0,0,.04);
}
.act-btn:hover { background:#eef0f6; border-color:#c8cdd8; color:var(--t1); }
.act-btn svg { width:13px; height:13px; }
.act-btn .chev { width:10px; height:10px; opacity:.45; transition:transform .18s; }
.act-wrap.open .act-btn { background:#eef0f6; border-color:#c8cdd8; }
.act-wrap.open .act-btn .chev { transform:rotate(180deg); }

.act-menu {
    position:absolute; right:0; top:calc(100% + 6px);
    min-width:195px; background:#fff;
    border:1px solid #e2e6f0; border-radius:11px;
    box-shadow:0 10px 36px rgba(0,0,0,.13), 0 2px 8px rgba(0,0,0,.06);
    padding:.35rem; z-index:200;
    display:none; flex-direction:column; gap:2px;
    animation:dropIn .12s ease;
}
@keyframes dropIn {
    from { opacity:0; transform:translateY(-5px); }
    to   { opacity:1; transform:translateY(0); }
}
.act-wrap.open .act-menu { display:flex; }

.act-item {
    display:flex; align-items:center; gap:.65rem;
    padding:.44rem .7rem; border-radius:7px;
    font-size:.78rem; font-weight:500;
    text-decoration:none; cursor:pointer;
    border:none; width:100%; text-align:left;
    transition:background .12s, color .12s;
    color:var(--t2); background:none;
}
.act-item .act-ico {
    width:27px; height:27px; border-radius:7px;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; transition:transform .12s;
}
.act-item:hover .act-ico { transform:scale(1.1); }
.act-item svg { width:13px; height:13px; }
.act-sep { height:1px; background:#f0f2f7; margin:.2rem 0; }

/* ── Couleurs ── */
/* Voir — bleu */
.act-item.c-view       { color:#1d4ed8; }
.act-item.c-view:hover { background:#eff6ff; }
.act-item.c-view .act-ico { background:#dbeafe; color:#1d4ed8; }

/* Documents — violet */
.act-item.c-doc       { color:#6d28d9; }
.act-item.c-doc:hover { background:#f5f3ff; }
.act-item.c-doc .act-ico { background:#ede9fe; color:#6d28d9; }

/* Modifier — ambre */
.act-item.c-edit       { color:#b45309; }
.act-item.c-edit:hover { background:#fffbeb; }
.act-item.c-edit .act-ico { background:#fef3c7; color:#d97706; }

/* Soumettre — vert */
.act-item.c-submit       { color:#065f46; }
.act-item.c-submit:hover { background:#ecfdf5; }
.act-item.c-submit .act-ico { background:#d1fae5; color:#059669; }

/* Payer — émeraude foncé */
.act-item.c-pay       { color:#047857; }
.act-item.c-pay:hover { background:#ecfdf5; }
.act-item.c-pay .act-ico { background:#a7f3d0; color:#059669; }

/* Télécharger — cyan */
.act-item.c-dl       { color:#0e7490; }
.act-item.c-dl:hover { background:#ecfeff; }
.act-item.c-dl .act-ico { background:#cffafe; color:#0891b2; }

/* Supprimer — rouge */
.act-item.c-del       { color:#b91c1c; }
.act-item.c-del:hover { background:#fef2f2; }
.act-item.c-del .act-ico { background:#fee2e2; color:#dc2626; }

</style>
