<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PDAI') }} — Déclaration d'Activité Industrielle</title>

    <link rel="icon" href="{{ asset('images/logo_favicon.jpg') }}" type="image/jpg" sizes="32x32">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange:     #F47920;
            --orange-dk:  #D4610A;
            --orange-lt:  #FFF6EE;
            --orange-mid: #FDEBD8;
            --green:      #2E9E5B;
            --green-lt:   #EAF7EF;
            --green-mid:  #C8EDD8;
            --black:      #1A1A1A;
            --gray-dk:    #4B5563;
            --gray:       #6B7280;
            --border:     #E8DDD4;
            --white:      #FFFFFF;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--white);
            color: var(--black);
            overflow-x: hidden;
        }

        /* ═══ NAV ═══ */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; height: 80px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: box-shadow .3s;
        }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a {
            font-size: .9rem; font-weight: 500; color: var(--gray);
            text-decoration: none; transition: color .2s;
        }
        .nav-links a:hover { color: var(--orange); }
        .nav-cta { display: flex; gap: 10px; align-items: center; }

        /* ═══ BOUTONS ═══ */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 10px;
            font-family: 'Poppins', sans-serif; font-size: .875rem; font-weight: 600;
            cursor: pointer; text-decoration: none; border: none; transition: all .2s;
        }
        .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--black); }
        .btn-outline:hover { border-color: var(--orange); color: var(--orange); }
        .btn-primary { background: var(--orange); color: var(--white); }
        .btn-primary:hover { background: var(--orange-dk); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(244,121,32,.35); }
        .btn-white { background: var(--white); color: var(--orange); font-weight: 700; }
        .btn-white:hover { background: var(--orange-lt); }
        .btn-ghost { background: rgba(255,255,255,.15); color: #fff; border: 1px solid rgba(255,255,255,.2); }
        .btn-ghost:hover { background: rgba(255,255,255,.25); }
        .btn-lg { padding: 14px 28px; font-size: 1rem; border-radius: 12px; }

        /* ═══ HERO ═══ */
        .hero {
            min-height: 100vh; padding: 120px 5% 80px;
            display: flex; align-items: center;
            position: relative; background: var(--orange-lt); overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--orange) 33.33%, var(--white) 33.33% 66.66%, var(--green) 66.66%);
        }
        .hero::after {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(ellipse 65% 55% at 72% 42%, rgba(244,121,32,.08) 0%, transparent 70%);
        }
        .hero-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 60px; align-items: center;
            max-width: 1200px; margin: 0 auto; width: 100%; position: relative; z-index: 2;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 14px; border-radius: 100px;
            background: rgba(244,121,32,.12); color: var(--orange);
            font-size: .74rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
            margin-bottom: 20px; border: 1px solid rgba(244,121,32,.2);
        }
        .hero-badge span { width: 6px; height: 6px; border-radius: 50%; background: var(--orange); animation: blink 1.5s ease infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }

        h1 {
            font-family: 'Poppins', sans-serif; font-weight: 800;
            font-size: clamp(2.2rem, 4vw, 3.4rem); line-height: 1.1;
            letter-spacing: -.02em; color: var(--black); margin-bottom: 20px;
        }
        h1 em { font-style: normal; color: var(--orange); }
        .hero-desc { font-size: 1rem; color: var(--gray-dk); line-height: 1.75; max-width: 480px; margin-bottom: 36px; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        .hero-stats { display: flex; gap: 28px; margin-top: 48px; flex-wrap: wrap; }
        .stat { border-left: 3px solid var(--orange); padding-left: 14px; }
        .stat:nth-child(2) { border-color: var(--green); }
        .stat:nth-child(3) { border-color: var(--border); }
        .stat-num { font-family: 'Poppins', sans-serif; font-size: 1.9rem; font-weight: 800; color: var(--black); line-height: 1; }
        .stat-label { font-size: .78rem; color: var(--gray); margin-top: 4px; }

        /* ─ Mini dashboard hero ─ */
        .hero-visual { position: relative; display: flex; align-items: center; justify-content: center; }
        .hero-card-stack { position: relative; width: 100%; max-width: 440px; margin: 0 auto; }
        .dash-card {
            background: var(--white); border-radius: 20px;
            box-shadow: 0 20px 60px rgba(26,26,26,.10), 0 0 0 1px var(--border);
            padding: 24px;
        }
        .dash-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .dash-card-title { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: .95rem; color: var(--black); }
        .dash-card-count { background: var(--orange); color: #fff; font-size: .72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
        .dash-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 0; border-bottom: 1px solid #F3EDE8; font-size: .82rem;
        }
        .dash-row:last-child { border-bottom: none; }
        .dash-ref { font-weight: 700; color: var(--black); font-size: .78rem; }
        .dash-company { color: var(--gray); font-size: .78rem; max-width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 600; }
        .badge::before { content:''; width:5px; height:5px; border-radius:50%; }
        .badge-brouillon { background:#F3F4F6; color:#6B7280; }
        .badge-brouillon::before { background:#6B7280; }
        .badge-soumis { background:var(--orange-lt); color:var(--orange); }
        .badge-soumis::before { background:var(--orange); }
        .badge-valide { background:var(--green-lt); color:var(--green); }
        .badge-valide::before { background:var(--green); }
        .badge-paiement { background:#FEF9EC; color:#D97706; }
        .badge-paiement::before { background:#D97706; }

        .floating-card {
            position: absolute; background: var(--white); border-radius: 14px; padding: 14px 18px;
            box-shadow: 0 12px 36px rgba(26,26,26,.12), 0 0 0 1px var(--border); font-size: .82rem;
        }
        .fc-top    { top: -32px; right: -20px; min-width: 170px; }
        .fc-bottom { bottom: -28px; left: -20px; min-width: 170px; }
        .fc-label  { color: var(--gray); font-size: .7rem; margin-bottom: 4px; font-weight: 500; }
        .fc-val    { font-family: 'Poppins', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--black); }
        .fc-sub    { font-size: .7rem; font-weight: 600; color: var(--green); margin-top: 2px; }

        /* ═══ STRIP CHIFFRES ═══ */
        .strip {
            background: var(--black);
            border-top: 3px solid transparent;
            border-image: linear-gradient(90deg, var(--orange) 33.33%, var(--white) 33.33% 66.66%, var(--green) 66.66%) 1;
        }
        .strip-inner {
            display: grid; grid-template-columns: repeat(4, 1fr);
            max-width: 1200px; margin: 0 auto;
        }
        .strip-item {
            padding: 36px 28px; text-align: center;
            border-right: 1px solid rgba(255,255,255,.07);
        }
        .strip-item:last-child { border-right: none; }
        .strip-val { font-family: 'Poppins', sans-serif; font-size: 2.1rem; font-weight: 800; color: var(--orange); line-height: 1; }
        .strip-lbl { font-size: .8rem; color: rgba(255,255,255,.5); margin-top: 6px; }

        /* ═══ WRAPPERS ═══ */
        section { padding: 100px 5%; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-label {
            display: inline-block; font-size: .72rem; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase; color: var(--orange); margin-bottom: 12px;
        }
        h2 {
            font-family: 'Poppins', sans-serif; font-weight: 800;
            font-size: clamp(1.7rem, 2.8vw, 2.5rem); line-height: 1.15;
            letter-spacing: -.02em; margin-bottom: 14px; color: var(--black);
        }
        .section-desc { font-size: .95rem; color: var(--gray-dk); max-width: 520px; line-height: 1.75; }

        /* ═══ FEATURES ═══ */
        .features-section { background: var(--white); }
        .features-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .feat-card {
            border: 1.5px solid var(--border); border-radius: 18px; padding: 26px 22px;
            transition: all .25s; background: var(--white);
        }
        .feat-card:hover { border-color: rgba(244,121,32,.35); transform: translateY(-4px); box-shadow: 0 16px 40px rgba(244,121,32,.08); }
        .feat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .feat-card h3 { font-family: 'Poppins', sans-serif; font-size: .9rem; font-weight: 700; margin-bottom: 8px; color: var(--black); }
        .feat-card p { font-size: .83rem; color: var(--gray); line-height: 1.65; }

        /* ═══ DOCUMENTS ═══ */
        .docs-section { background: var(--orange-lt); }
        .docs-intro { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 56px; flex-wrap: wrap; gap: 24px; }
        .docs-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }

        .doc-card {
            border: 1.5px solid rgba(244,121,32,.15); border-radius: 18px; padding: 26px;
            transition: all .25s; position: relative; overflow: hidden; background: var(--white);
        }
        .doc-card::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--orange), var(--green));
            transform: scaleX(0); transition: transform .3s; transform-origin: left;
        }
        .doc-card:hover { border-color: rgba(244,121,32,.4); transform: translateY(-4px); box-shadow: 0 16px 40px rgba(244,121,32,.10); }
        .doc-card:hover::after { transform: scaleX(1); }

        .doc-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .doc-card h3 { font-family: 'Poppins', sans-serif; font-size: .95rem; font-weight: 700; margin-bottom: 8px; color: var(--black); }
        .doc-card p { font-size: .85rem; color: var(--gray); line-height: 1.65; }
        .doc-required {
            display: inline-block; margin-top: 14px; font-size: .7rem; font-weight: 700;
            color: var(--orange); background: rgba(244,121,32,.10); padding: 3px 10px;
            border-radius: 20px; border: 1px solid rgba(244,121,32,.15);
        }
        .doc-optional {
            display: inline-block; margin-top: 14px; font-size: .7rem; font-weight: 700;
            color: var(--green); background: var(--green-lt); padding: 3px 10px;
            border-radius: 20px; border: 1px solid var(--green-mid);
        }

        /* ═══ ÉTAPES ═══ */
        .steps-section { background: var(--black); color: var(--white); position: relative; overflow: hidden; }
        .steps-section::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--orange) 33.33%, var(--white) 33.33% 66.66%, var(--green) 66.66%);
        }
        .steps-section h2 { color: var(--white); }
        .steps-section .section-label { color: var(--orange); }
        .steps-section .section-desc { color: rgba(255,255,255,.5); }
        .steps-header { margin-bottom: 60px; }
        .steps-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start; }

        .steps-timeline { position: relative; }
        .steps-timeline::before {
            content: ''; position: absolute; left: 27px; top: 8px; bottom: 8px; width: 2px;
            background: linear-gradient(180deg, var(--orange) 0%, var(--green) 100%); opacity: .25;
        }
        .step-item { display: grid; grid-template-columns: 58px 1fr; gap: 24px; margin-bottom: 44px; }
        .step-item:last-child { margin-bottom: 0; }
        .step-num {
            width: 56px; height: 56px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1rem;
            flex-shrink: 0; position: relative; z-index: 2; color: #fff;
        }
        .sn-1 { background: var(--orange); }
        .sn-2 { background: rgba(244,121,32,.65); }
        .sn-3 { background: rgba(46,158,91,.65); }
        .sn-4 { background: var(--green); }

        .step-content { padding-top: 10px; }
        .step-phase { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: var(--orange); margin-bottom: 5px; }
        .step-phase.gp { color: var(--green); }
        .step-content h3 { font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .step-content p { font-size: .875rem; color: rgba(255,255,255,.5); line-height: 1.65; }
        .step-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .step-tag { font-size: .7rem; padding: 4px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,.12); color: rgba(255,255,255,.4); }

        .status-panel { padding: 28px; border-radius: 20px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); }
        .status-panel-title { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: .95rem; margin-bottom: 22px; color: #fff; }
        .status-item { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px; }
        .status-item:last-child { margin-bottom: 0; }
        .status-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
        .status-item-content h4 { font-size: .875rem; font-weight: 600; color: #fff; margin-bottom: 3px; }
        .status-item-content p  { font-size: .78rem; color: rgba(255,255,255,.4); line-height: 1.5; }

        .help-box { margin-top: 22px; padding: 22px; border-radius: 16px; border: 1px solid rgba(255,255,255,.07); background: rgba(255,255,255,.03); }
        .help-box-label { font-size: .68rem; text-transform: uppercase; letter-spacing: .12em; color: rgba(255,255,255,.35); margin-bottom: 8px; }
        .help-box p { font-size: .85rem; color: rgba(255,255,255,.5); line-height: 1.65; margin-bottom: 16px; }

        /* ═══ FAQ ═══ */
        .faq-section { background: var(--white); }
        .faq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start; }
        .faq-list { display: flex; flex-direction: column; gap: 14px; }
        .faq-item { border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; background: var(--white); transition: border-color .2s; }
        .faq-item.open { border-color: var(--orange); }
        .faq-q {
            padding: 16px 20px; font-weight: 600; font-size: .875rem;
            display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; user-select: none; gap: 16px; color: var(--black);
            font-family: 'Poppins', sans-serif;
        }
        .faq-q:hover { color: var(--orange); }
        .faq-q svg { flex-shrink: 0; transition: transform .25s; color: var(--gray); }
        .faq-item.open .faq-q svg { transform: rotate(45deg); color: var(--orange); }
        .faq-a { max-height: 0; overflow: hidden; font-size: .85rem; color: var(--gray-dk); line-height: 1.75; transition: max-height .3s ease, padding .3s; padding: 0 20px; }
        .faq-item.open .faq-a { max-height: 200px; padding: 0 20px 18px; }

        .faq-cta-box {
            background: var(--black);
            border-radius: 20px; padding: 40px 32px;
            color: #fff; display: flex; flex-direction: column; justify-content: space-between;
            min-height: 360px; position: relative; overflow: hidden;
        }
        .faq-cta-box::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--orange) 33.33%, var(--white) 33.33% 66.66%, var(--green) 66.66%);
        }
        .faq-cta-box::after {
            content: ''; position: absolute; bottom: -60px; right: -60px;
            width: 240px; height: 240px; border-radius: 50%; background: rgba(244,121,32,.06);
        }
        .faq-cta-box h3 { font-family: 'Poppins', sans-serif; font-size: 1.55rem; font-weight: 800; line-height: 1.2; margin-bottom: 14px; }
        .faq-cta-box p { font-size: .875rem; color: rgba(255,255,255,.55); line-height: 1.7; margin-bottom: 28px; }
        .faq-cta-flag { display: flex; gap: 3px; margin-bottom: 24px; }
        .faq-cta-flag span { width: 18px; height: 10px; display: inline-block; }

        /* ═══ FOOTER 4 colonnes ═══ */
        footer { background: var(--black); }

        .footer-main {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.4fr;
            gap: 48px;
            padding: 60px 5% 48px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Colonne marque */
        .footer-brand-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .footer-bar { width: 4px; height: 28px; border-radius: 4px; background: linear-gradient(180deg, var(--orange) 0%, var(--green) 100%); flex-shrink: 0; }
        .footer-brand-name { font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.15rem; color: #fff; line-height: 1.1; }
        .footer-brand-name small { display: block; font-size: .6rem; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); margin-top: 2px; }
        .footer-tagline { font-size: .82rem; color: rgba(255,255,255,.45); line-height: 1.7; max-width: 270px; margin-bottom: 22px; }

        .footer-socials { display: flex; gap: 9px; }
        .footer-social-btn {
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            display: flex; align-items: center; justify-content: center;
            transition: background .2s, border-color .2s; text-decoration: none; cursor: pointer;
        }
        .footer-social-btn:hover { background: rgba(244,121,32,.2); border-color: rgba(244,121,32,.35); }
        .footer-social-btn svg { width: 14px; height: 14px; fill: rgba(255,255,255,.55); }

        /* Colonnes nav / infos */
        .footer-col-title {
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .1em; color: rgba(255,255,255,.3); margin-bottom: 18px;
        }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a {
            font-size: .83rem; color: rgba(255,255,255,.55); text-decoration: none; transition: color .2s;
        }
        .footer-col ul li a:hover { color: var(--orange); }

        /* Colonne contact */
        .footer-contact-item { display: flex; align-items: flex-start; gap: 11px; margin-bottom: 14px; }
        .footer-contact-icon {
            width: 30px; height: 30px; border-radius: 7px; flex-shrink: 0;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; justify-content: center; margin-top: 1px;
        }
        .footer-contact-icon svg { width: 13px; height: 13px; fill: none; stroke: rgba(255,255,255,.5); stroke-width: 1.8; stroke-linecap: round; }
        .footer-contact-label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.3); margin-bottom: 2px; }
        .footer-contact-val { font-size: .83rem; color: rgba(255,255,255,.65); line-height: 1.45; }
        .footer-contact-val a { color: rgba(255,255,255,.65); text-decoration: none; transition: color .2s; }
        .footer-contact-val a:hover { color: var(--orange); }

        .footer-ticket {
            display: inline-flex; align-items: center; gap: 7px; margin-top: 18px;
            padding: 9px 16px; border-radius: 10px;
            background: rgba(244,121,32,.1); border: 1px solid rgba(244,121,32,.22);
            color: var(--orange); font-size: .8rem; font-weight: 600;
            font-family: 'Poppins', sans-serif; text-decoration: none; transition: background .2s;
        }
        .footer-ticket:hover { background: rgba(244,121,32,.2); }

        /* Barre basse */
        .footer-divider { border: none; border-top: 1px solid rgba(255,255,255,.07); max-width: 1200px; margin: 0 auto; }
        .footer-bottom {
            max-width: 1200px; margin: 0 auto; padding: 18px 5%;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .footer-bottom-left { font-size: .77rem; color: rgba(255,255,255,.3); }
        .footer-bottom-left span { color: var(--orange); }
        .footer-bottom-right { display: flex; align-items: center; gap: 14px; }
        .ci-flag { display: flex; height: 13px; border-radius: 3px; overflow: hidden; }
        .ci-band { width: 18px; }
        .footer-sep { width: 3px; height: 3px; border-radius: 50%; background: rgba(255,255,255,.18); }
        .footer-bottom-right a { font-size: .74rem; color: rgba(255,255,255,.3); text-decoration: none; transition: color .2s; }
        .footer-bottom-right a:hover { color: rgba(255,255,255,.65); }

        /* ═══ ANIMATIONS ═══ */
        @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:none; } }
        .anim { opacity: 0; }
        .anim.visible { animation: fadeUp .55s ease forwards; }
        .anim-d1 { animation-delay: .08s; }
        .anim-d2 { animation-delay: .16s; }
        .anim-d3 { animation-delay: .24s; }
        .anim-d4 { animation-delay: .32s; }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 1060px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 960px) {
            .hero-grid, .steps-two-col, .faq-grid { grid-template-columns: 1fr; }
            .docs-grid { grid-template-columns: 1fr 1fr; }
            .hero-visual { display: none; }
            .nav-links { display: none; }
            .steps-two-col > div:last-child { padding-top: 0 !important; }
            .strip-inner { grid-template-columns: repeat(2, 1fr); }
            .footer-main { grid-template-columns: 1fr 1fr; gap: 36px; }
        }
        @media (max-width: 600px) {
            .docs-grid, .features-grid { grid-template-columns: 1fr; }
            .hero-stats { flex-direction: column; gap: 16px; }
            .strip-inner { grid-template-columns: 1fr 1fr; }
            .strip-item { padding: 24px 16px; }
            .footer-main { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

{{-- ═══ NAVIGATION ═══ --}}
<nav>
    <a href="/" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
        <img src="{{ asset('images/logo.png') }}" alt="Logo PDAI" style="height:60px;">
    </a>
    <div class="nav-links">
        <a href="#features">Pourquoi PDAI ?</a>
        <a href="#documents">Documents</a>
        <a href="#etapes">Processus</a>
        <a href="#faq">FAQ</a>
    </div>
    <div class="nav-cta">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Mon espace →</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Connexion</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Commencer →</a>
                @endif
            @endauth
        @endif
    </div>
</nav>

{{-- ═══ HERO ═══ --}}
<section class="hero">
    <div class="hero-grid">
        <div>
            <div class="hero-badge"><span></span> Plateforme officielle — Côte d'Ivoire</div>
            <h1>Vos déclarations<br><em>industrielles</em><br>100&nbsp;% en ligne</h1>
            <p class="hero-desc">
                Déposez, suivez et validez vos Déclarations d'Activité Industrielle depuis n'importe où en Côte d'Ivoire.
                Un processus entièrement numérisé, simple et rapide.
            </p>
            <div class="hero-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Accéder à mon espace →</a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Créer mon dossier →</a>
                    @endif
                    <a href="#etapes" class="btn btn-outline btn-lg">Voir le processus</a>
                @endauth
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-num">72h</div>
                    <div class="stat-label">Délai moyen de traitement</div>
                </div>
                <div class="stat">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Dématérialisé</div>
                </div>
                <div class="stat">
                    <div class="stat-num">4</div>
                    <div class="stat-label">Phases claires et guidées</div>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-card-stack">
                <div class="floating-card fc-top">
                    <div class="fc-label">Déclarations ce mois</div>
                    <div class="fc-val">127</div>
                    <div class="fc-sub">↑ 18% vs mois dernier</div>
                </div>
                <div class="dash-card">
                    <div class="dash-card-header">
                        <span class="dash-card-title">Mes déclarations</span>
                        <span class="dash-card-count">12 dossiers</span>
                    </div>
                    <div class="dash-row">
                        <div><div class="dash-ref">DECL-2604-0061</div><div class="dash-company">Société Industrielle CI</div></div>
                        <span class="badge badge-valide">Validée</span>
                    </div>
                    <div class="dash-row">
                        <div><div class="dash-ref">DECL-2604-0059</div><div class="dash-company">Groupe Agro-Abidjan</div></div>
                        <span class="badge badge-paiement">Paiement</span>
                    </div>
                    <div class="dash-row">
                        <div><div class="dash-ref">DECL-2604-0057</div><div class="dash-company">Trans-Côte Logistics</div></div>
                        <span class="badge badge-soumis">Soumise</span>
                    </div>
                    <div class="dash-row">
                        <div><div class="dash-ref">DECL-2604-0055</div><div class="dash-company">ABI Construction SARL</div></div>
                        <span class="badge badge-brouillon">Brouillon</span>
                    </div>
                    <div class="dash-row">
                        <div><div class="dash-ref">DECL-2604-0052</div><div class="dash-company">Côte d'Or Industries</div></div>
                        <span class="badge badge-valide">Validée</span>
                    </div>
                </div>
                <div class="floating-card fc-bottom">
                    <div class="fc-label">Dernière validation</div>
                    <div class="fc-val">18h</div>
                    <div class="fc-sub">✓ Attestation disponible</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ STRIP CHIFFRES ═══ --}}
<div class="strip">
    <div class="strip-inner">
        <div class="strip-item">
            <div class="strip-val">2 400+</div>
            <div class="strip-lbl">Déclarations traitées</div>
        </div>
        <div class="strip-item">
            <div class="strip-val">98%</div>
            <div class="strip-lbl">Taux de satisfaction</div>
        </div>
        <div class="strip-item">
            <div class="strip-val">72h</div>
            <div class="strip-lbl">Délai moyen de traitement</div>
        </div>
        <div class="strip-item">
            <div class="strip-val">24/7</div>
            <div class="strip-lbl">Accès à votre espace</div>
        </div>
    </div>
</div>

{{-- ═══ FEATURES ═══ --}}
<section class="features-section" id="features">
    <div class="container">
        <div style="text-align:center; max-width:580px; margin:0 auto 52px;">
            <span class="section-label anim anim-d1">Pourquoi PDAI ?</span>
            <h2 class="anim anim-d2">Une plateforme conçue pour<br>les industriels ivoiriens</h2>
            <p class="section-desc anim anim-d3" style="margin:0 auto;">Fini les déplacements et les files d'attente. Gérez tout en ligne depuis votre bureau ou votre téléphone.</p>
        </div>
        <div class="features-grid">
            <div class="feat-card anim anim-d1">
                <div class="feat-icon" style="background:var(--orange-mid);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                </div>
                <h3>Dépôt 100&nbsp;% numérique</h3>
                <p>Soumettez vos dossiers et pièces justificatives entièrement en ligne, sans vous déplacer.</p>
            </div>
            <div class="feat-card anim anim-d2">
                <div class="feat-icon" style="background:var(--green-lt);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <h3>Suivi en temps réel</h3>
                <p>Consultez le statut de votre déclaration à chaque étape, avec des notifications automatiques.</p>
            </div>
            <div class="feat-card anim anim-d3">
                <div class="feat-icon" style="background:var(--orange-mid);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <h3>Données sécurisées</h3>
                <p>Vos documents et informations sont protégés. Chaque action est tracée et archivée.</p>
            </div>
            <div class="feat-card anim anim-d4">
                <div class="feat-icon" style="background:var(--green-lt);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M9 15l2 2 4-4"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <h3>Attestation PDF officielle</h3>
                <p>Téléchargez votre attestation officielle dès validation, disponible à tout moment depuis votre espace.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ DOCUMENTS ═══ --}}
<section class="docs-section" id="documents">
    <div class="container">
        <div class="docs-intro">
            <div>
                <span class="section-label anim anim-d1">Documents requis</span>
                <h2 class="anim anim-d2">Ce qu'il vous faut<br>pour déclarer</h2>
                <p class="section-desc anim anim-d3">Préparez ces pièces en amont pour soumettre votre déclaration sans délai.</p>
            </div>
        </div>
        <div class="docs-grid">
            <div class="doc-card anim anim-d1">
                <div class="doc-icon" style="background:var(--orange-mid);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h3>Informations du gérant</h3>
                <p>Nom complet, numéro de pièce d'identité, adresse, contact et nationalité du représentant légal.</p>
                <span class="doc-required">Obligatoire</span>
            </div>
            <div class="doc-card anim anim-d2">
                <div class="doc-icon" style="background:var(--green-lt);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <h3>Informations de l'entreprise</h3>
                <p>Raison sociale, forme juridique, secteur d'activité, nature de l'activité et siège social.</p>
                <span class="doc-required">Obligatoire</span>
            </div>
            <div class="doc-card anim anim-d3">
                <div class="doc-icon" style="background:var(--orange-mid);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <h3>Pièce d'identité</h3>
                <p>CNI, passeport ou titre de séjour en cours de validité du gérant ou représentant légal.</p>
                <span class="doc-required">Obligatoire</span>
            </div>
            <div class="doc-card anim anim-d1">
                <div class="doc-icon" style="background:var(--green-lt);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <h3>Registre de commerce (RCCM)</h3>
                <p>Extrait du registre du commerce et du crédit mobilier de votre entreprise si disponible.</p>
                <span class="doc-optional">Si disponible</span>
            </div>
            <div class="doc-card anim anim-d2">
                <div class="doc-icon" style="background:var(--orange-mid);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Numéro fiscal (NIF / DGI)</h3>
                <p>Numéro d'identification fiscale attribué par la Direction Générale des Impôts.</p>
                <span class="doc-optional">Si disponible</span>
            </div>
            <div class="doc-card anim anim-d3">
                <div class="doc-icon" style="background:var(--green-lt);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <h3>Justificatif de paiement</h3>
                <p>Reçu ou preuve de virement pour les droits de déclaration, selon le tarif en vigueur.</p>
                <span class="doc-required">Phase paiement</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══ ÉTAPES ═══ --}}
<section class="steps-section" id="etapes">
    <div class="container">
        <div class="steps-two-col">
            <div>
                <div class="steps-header">
                    <span class="section-label">Processus</span>
                    <h2>4 phases pour valider<br>votre déclaration</h2>
                    <p class="section-desc">Un circuit transparent, du dépôt à l'attestation officielle.</p>
                </div>
                <div class="steps-timeline">
                    <div class="step-item">
                        <div class="step-num sn-1">1</div>
                        <div class="step-content">
                            <div class="step-phase">Phase 1 — Création</div>
                            <h3>Constitution du dossier</h3>
                            <p>Renseignez les informations du gérant, de l'entreprise et joignez les pièces justificatives requises.</p>
                            <div class="step-tags">
                                <span class="step-tag">Infos gérant</span>
                                <span class="step-tag">Infos entreprise</span>
                                <span class="step-tag">Documents</span>
                            </div>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num sn-2">2</div>
                        <div class="step-content">
                            <div class="step-phase">Phase 2 — Soumission</div>
                            <h3>Envoi de la déclaration</h3>
                            <p>Vérifiez votre dossier et soumettez-le. Il passe en statut <strong style="color:#fff;">« Soumise »</strong> et est transmis à l'équipe de traitement.</p>
                            <div class="step-tags">
                                <span class="step-tag">Vérification</span>
                                <span class="step-tag">Confirmation</span>
                            </div>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num sn-3">3</div>
                        <div class="step-content">
                            <div class="step-phase gp">Phase 3 — Paiement</div>
                            <h3>Règlement des droits</h3>
                            <p>Une fois votre dossier approuvé par l'agent, procédez au paiement des droits de déclaration selon le tarif applicable.</p>
                            <div class="step-tags">
                                <span class="step-tag">Paiement en ligne</span>
                                <span class="step-tag">Reçu</span>
                            </div>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num sn-4">4</div>
                        <div class="step-content">
                            <div class="step-phase gp">Phase 4 — Validation</div>
                            <h3>Délivrance de l'attestation</h3>
                            <p>Après confirmation du paiement, votre déclaration est validée et l'attestation officielle est disponible en téléchargement.</p>
                            <div class="step-tags">
                                <span class="step-tag">Attestation PDF</span>
                                <span class="step-tag">Archivage</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding-top: 110px;">
                <div class="status-panel">
                    <div class="status-panel-title">Référentiel des statuts</div>
                    <div class="status-item">
                        <div class="status-dot" style="background:#6B7280;"></div>
                        <div class="status-item-content">
                            <h4>Brouillon</h4>
                            <p>Déclaration en cours de constitution, non encore soumise. Modifiable à tout moment.</p>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-dot" style="background:var(--orange);"></div>
                        <div class="status-item-content">
                            <h4>Soumise</h4>
                            <p>Dossier envoyé et en attente d'examen par un agent traitant.</p>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-dot" style="background:#D97706;"></div>
                        <div class="status-item-content">
                            <h4>En attente de paiement</h4>
                            <p>Dossier approuvé. Le règlement des droits est attendu pour finaliser.</p>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-dot" style="background:var(--green);"></div>
                        <div class="status-item-content">
                            <h4>Validée</h4>
                            <p>Déclaration traitée et attestation disponible au téléchargement.</p>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-dot" style="background:#EF4444;"></div>
                        <div class="status-item-content">
                            <h4>Rejetée</h4>
                            <p>Dossier refusé. Un motif est fourni pour permettre la correction et le renvoi.</p>
                        </div>
                    </div>
                </div>
                <div class="help-box">
                    <div class="help-box-label">Besoin d'aide ?</div>
                    <p>Notre équipe est disponible du lundi au vendredi de 07h30 à 16h30 pour vous accompagner à chaque étape.</p>
                    <a href="mailto:pdai@commerce.gouv.ci"
                       class="btn btn-outline"
                       style="border-color:rgba(255,255,255,.15); color:#fff; font-size:.82rem; padding:9px 16px;">
                        Contacter le support →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FAQ ═══ --}}
<section class="faq-section" id="faq">
    <div class="container">
        <div class="faq-grid">
            <div>
                <span class="section-label">FAQ</span>
                <h2 style="margin-bottom:32px;">Questions fréquentes</h2>
                <div class="faq-list">
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">
                            Combien de temps prend le traitement d'une déclaration ?
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="faq-a">Le délai moyen de traitement est de 24 à 72 heures ouvrées après soumission complète du dossier. Les dossiers incomplets peuvent entraîner des délais supplémentaires.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">
                            Puis-je modifier ma déclaration après soumission ?
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="faq-a">Une déclaration en statut « Brouillon » est entièrement modifiable. Une fois soumise, vous devrez contacter le support pour toute correction importante.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">
                            Comment récupérer mon attestation officielle ?
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="faq-a">Une fois votre déclaration validée, l'attestation est disponible directement dans votre espace « Mes Déclarations » au format PDF, téléchargeable à tout moment.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">
                            Puis-je gérer plusieurs entreprises avec un seul compte ?
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="faq-a">Oui, votre compte vous permet de gérer plusieurs déclarations pour différentes entreprises. Chaque déclaration dispose de sa propre référence et d'un suivi indépendant.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">
                            Que faire si ma déclaration est rejetée ?
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="faq-a">En cas de rejet, un motif détaillé vous est communiqué. Vous pouvez corriger les éléments indiqués et soumettre une nouvelle déclaration sans frais supplémentaires.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">
                            Comment se déroule le paiement des droits de déclaration ?
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="faq-a">Une fois votre dossier approuvé par l'agent traitant, vous recevez une notification vous invitant à procéder au paiement en ligne. Un reçu vous est délivré automatiquement après confirmation.</div>
                    </div>
                </div>
            </div>
            <div>
                <div class="faq-cta-box">
                    <div style="position:relative;z-index:1;">
                        <div class="faq-cta-flag">
                            <span style="background:var(--orange); border-radius:2px 0 0 2px;"></span>
                            <span style="background:#fff;"></span>
                            <span style="background:var(--green); border-radius:0 2px 2px 0;"></span>
                        </div>
                        <h3>Prêt à démarrer votre déclaration ?</h3>
                        <p>Créez votre compte en quelques secondes et commencez immédiatement. Le processus est entièrement guidé, étape par étape.</p>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:10px; position:relative; z-index:1;">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Créer un compte gratuitement →</a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-ghost btn-lg">J'ai déjà un compte</a>
                        @endif
                    </div>
                </div>
                <div style="margin-top:20px; background:#fff; border:1.5px solid var(--border); border-radius:16px; padding:24px;">
                    <div style="font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--gray); margin-bottom:10px;">Support & assistance</div>
                    <p style="font-size:.83rem; color:var(--gray-dk); line-height:1.65; margin-bottom:16px;">Notre équipe est disponible du lundi au vendredi de 07h30 à 16h30.</p>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <a href="mailto:pdai@commerce.gouv.ci" class="btn btn-outline" style="font-size:.8rem; padding:8px 14px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            Envoyer un e-mail
                        </a>
                        <a href="tel:+2252000000000" class="btn btn-outline" style="font-size:.8rem; padding:8px 14px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            +225 20 00 00 00 00
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FOOTER 4 COLONNES ═══ --}}
<footer>
    <div class="footer-main">

        {{-- Colonne 1 — Marque --}}
        <div>
            <div class="footer-brand-wrap">
                <div class="footer-bar"></div>
                <div class="footer-brand-name">
                    {{ config('app.name', 'PDAI') }}
                    <small>Côte d'Ivoire</small>
                </div>
            </div>
            <p class="footer-tagline">Plateforme officielle de gestion des Déclarations d'Activité Industrielle. Simple, rapide, 100&nbsp;% en ligne.</p>
            <div class="footer-socials">
                <a class="footer-social-btn" href="#" title="Facebook">
                    <svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a class="footer-social-btn" href="#" title="LinkedIn">
                    <svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7H10V9h4v2h.1A4.9 4.9 0 0 1 16 8z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                <a class="footer-social-btn" href="#" title="Twitter / X">
                    <svg viewBox="0 0 24 24"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                </a>
            </div>
        </div>

        {{-- Colonne 2 — Navigation --}}
        <div class="footer-col">
            <div class="footer-col-title">Navigation</div>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#features">Pourquoi PDAI&nbsp;?</a></li>
                <li><a href="#documents">Documents requis</a></li>
                <li><a href="#etapes">Processus & étapes</a></li>
                <li><a href="#faq">FAQ</a></li>
                @auth
                <li><a href="{{ url('/dashboard') }}">Mon tableau de bord</a></li>
                <li><a href="{{ route('declarations.index') }}">Mes déclarations</a></li>
                <li><a href="{{ route('attestations.index') }}">Attestations</a></li>
                @endauth
            </ul>
        </div>

        {{-- Colonne 3 — Informations utiles --}}
        <div class="footer-col">
            <div class="footer-col-title">Informations utiles</div>
            <ul>
                <li><a href="#">Textes réglementaires</a></li>
                <li><a href="#">Guide utilisateur (PDF)</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">Conditions d'utilisation</a></li>
                <li><a href="#">Mentions légales</a></li>
            </ul>
        </div>

        {{-- Colonne 4 — Contact --}}
        <div class="footer-col">
            <div class="footer-col-title">Contact & support</div>

            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div>
                    <div class="footer-contact-label">Téléphone</div>
                    <div class="footer-contact-val"><a href="tel:+22520000000">+225 20 00 00 00</a></div>
                </div>
            </div>

            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div>
                    <div class="footer-contact-label">E-mail</div>
                    <div class="footer-contact-val">
                        <a href="mailto:pdai@commerce.gouv.ci">
                            pdai@commerce.gouv.ci
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <div class="footer-contact-label">Adresse</div>
                    <div class="footer-contact-val">Abidjan, Plateau<br>Côte d'Ivoire</div>
                </div>
            </div>

            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="footer-contact-label">Horaires</div>
                    <div class="footer-contact-val">Lun – Ven : 07h30 – 16h30</div>
                </div>
            </div>
        </div>

    </div>

    <hr class="footer-divider">

    <div class="footer-bottom">
        <div class="footer-bottom-left">
            © {{ date('Y') }} — <span>{{ config('app.name', 'PDAI') }}</span>. Déclaration d'Activité Industrielle. Tous droits réservés.
        </div>
        <div class="footer-bottom-right">
            <div class="ci-flag">
                <div class="ci-band" style="background:#F47920; border-radius:2px 0 0 2px;"></div>
                <div class="ci-band" style="background:#FFFFFF;"></div>
                <div class="ci-band" style="background:#2E9E5B; border-radius:0 2px 2px 0;"></div>
            </div>
            <div class="footer-sep"></div>
            <a href="#">Confidentialité</a>
            <div class="footer-sep"></div>
            <a href="#">CGU</a>
            <div class="footer-sep"></div>
            <a href="#">Mentions légales</a>
        </div>
    </div>
</footer>


<script>
    function toggleFaq(el) {
        const item = el.closest('.faq-item');
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
        if (!isOpen) item.classList.add('open');
    }

    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.12 });
    document.querySelectorAll('.anim').forEach(el => obs.observe(el));

    window.addEventListener('scroll', () => {
        document.querySelector('nav').style.boxShadow =
            window.scrollY > 20 ? '0 4px 24px rgba(26,26,26,.08)' : 'none';
    });
</script>
</body>
</html>