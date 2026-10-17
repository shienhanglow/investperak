<?php
require __DIR__ . '/inc/bootstrap.php';

/** Escape, keep line breaks, and allow **bold** */
function fmt(string $s): string { return preg_replace('/\*\*(.+?)\*\*/u', '<b>$1</b>', nl(trim($s))); }
function L(string $key, string $lang, int $i): string { $l = lines(t($key, $lang)); return $l[$i] ?? ''; }
function wa_digits(string $s): string { return preg_replace('/\D+/', '', $s); }
function img_url(string $f): string { return UPLOAD_URL . '/' . rawurlencode(basename($f)); }

$PAGES = ['home', 'why', 'guide', 'gallery', 'about', 'partners', 'faq', 'contact'];
$MAP = [
  'en' => ['strait' => 'STRAIT OF MALACCA', 'road' => 'E1 North–South Expwy', 'perak' => 'PERAK', 'penang' => 'Penang', 'kerian' => 'Kerian parks', 'ipoh' => 'Ipoh', 'lumut' => 'Lumut Port', 'kl' => 'Kuala Lumpur', 'title' => "Perak's position on Malaysia's west-coast corridor between Penang and Kuala Lumpur"],
  'zh' => ['strait' => '马六甲海峡', 'road' => 'E1 南北大道', 'perak' => '霹雳州', 'penang' => '槟城', 'kerian' => '吉辇工业园', 'ipoh' => '怡保', 'lumut' => '红土坎港', 'kl' => '吉隆坡', 'title' => '霹雳州位于槟城与吉隆坡之间的西海岸走廊'],
];
$HERO_ART = '<svg class="hero-art" viewBox="0 0 1200 200" preserveAspectRatio="none" aria-hidden="true"><circle cx="960" cy="70" r="38" fill="var(--gold)" opacity=".9"/><path d="M0 200 L0 150 C60 150 80 90 120 90 C160 90 170 140 210 140 C250 140 270 60 320 60 C370 60 380 150 430 150 C480 150 500 110 540 110 C580 110 600 160 660 160 C720 160 740 80 790 80 C840 80 850 150 900 150 C950 150 980 100 1030 100 C1080 100 1100 150 1200 150 L1200 200Z" fill="var(--karst-3)"/><path d="M0 200 L0 175 C80 175 100 120 150 120 C200 120 210 175 280 175 C350 175 370 135 410 135 C450 135 470 180 560 180 C640 180 660 115 720 115 C780 115 790 178 860 178 C930 178 960 140 1010 140 C1060 140 1080 180 1200 180 L1200 200Z" fill="var(--karst-2)"/><rect x="0" y="192" width="1200" height="8" fill="var(--karst)"/></svg>';

// load lists once
$LIST = [];
foreach (array_keys($ITEM_TYPES) as $ty) $LIST[$ty] = items($ty);

function render_main(string $L): void {
    global $LIST, $MAP, $HERO_ART;
    $p = $L === 'zh' ? 'zh-' : '';
    $m = $MAP[$L];
    $email = t('contact.email'); $phone = t('contact.phone'); $wa = t('contact.whatsapp'); $hours = t('contact.hours', $L);
    ?>
<main id="m-<?= $L ?>" lang="<?= $L === 'zh' ? 'zh-Hans' : 'en' ?>"<?= $L === 'zh' ? ' hidden' : '' ?>>
<!-- HOME -->
<div class="page" data-page="home">
  <section class="hero">
    <div class="wrap">
      <p class="eyebrow"><?= e(t('home.eyebrow', $L)) ?></p>
      <h1><?= e(t('home.title', $L)) ?> <em><?= e(t('home.title_hl', $L)) ?></em></h1>
      <p class="lede"><?= fmt(t('home.lede', $L)) ?></p>
      <div class="cta-row"><a class="btn primary" href="#contact"><?= e(t('home.cta1', $L)) ?></a><a class="btn ghost" href="#guide"><?= e(t('home.cta2', $L)) ?></a></div>
      <?php if (t('home.coord', $L) !== ''): ?><p class="coord"><?= e(t('home.coord', $L)) ?></p><?php endif; ?>
    </div>
    <?= $HERO_ART ?>
  </section>
  <?php if ($LIST['stat']): ?>
  <section class="band"><div class="wrap grid" style="gap:28px">
    <div class="sec-head"><p class="eyebrow"><?= e(t('home.stats_eyebrow', $L)) ?></p><h2><?= e(t('home.stats_title', $L)) ?></h2></div>
    <div class="stats" style="--cols:<?= min(4, max(1, count($LIST['stat']))) ?>">
      <?php foreach ($LIST['stat'] as $it): ?><div class="stat"><span class="v num"><?= e(iv($it, 'value', $L)) ?></span><span class="l"><?= e(iv($it, 'label', $L)) ?></span><span class="s"><?= e(iv($it, 'note', $L)) ?></span></div><?php endforeach; ?>
    </div>
  </div></section>
  <?php endif; ?>
  <section class="band alt"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('home.sectors_eyebrow', $L)) ?></p><h2><?= e(t('home.sectors_title', $L)) ?></h2><p class="lede"><?= fmt(t('home.sectors_lede', $L)) ?></p></div>
    <div class="grid g3">
      <?php foreach ($LIST['sector'] as $it): ?><div class="tile"><?php if (iv($it, 'tag', $L) !== ''): ?><span class="tag<?= iv($it, 'priority') ? ' gold' : '' ?>"><?= e(iv($it, 'tag', $L)) ?></span><?php endif; ?><h3><?= e(iv($it, 'title', $L)) ?></h3><p><?= fmt(iv($it, 'text', $L)) ?></p></div><?php endforeach; ?>
    </div>
  </div></section>
  <section class="band"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('home.steps_eyebrow', $L)) ?></p><h2><?= e(t('home.steps_title', $L)) ?></h2></div>
    <div class="steps" style="--cols:<?= min(6, max(1, count($LIST['step']))) ?>">
      <?php foreach ($LIST['step'] as $it): ?><div class="step"><h3><?= e(iv($it, 'title', $L)) ?></h3><p><?= fmt(iv($it, 'text', $L)) ?></p></div><?php endforeach; ?>
    </div>
    <div class="cta-row" style="margin-top:28px"><a class="btn primary" href="#contact"><?= e(t('home.steps_cta', $L)) ?></a></div>
  </div></section>
</div>

<!-- WHY -->
<div class="page" data-page="why">
  <section class="band"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('why.eyebrow', $L)) ?></p><h1><?= e(t('why.title', $L)) ?></h1><p class="lede"><?= fmt(t('why.lede', $L)) ?></p></div>
    <div class="corridor">
      <svg viewBox="0 0 420 300" role="img" aria-labelledby="<?= $p ?>mapT"><title id="<?= $p ?>mapT"><?= e($m['title']) ?></title>
        <rect x="0" y="0" width="420" height="300" fill="var(--sunk)" rx="6"/>
        <path d="M40 0 C70 60 60 120 100 170 C130 210 150 250 190 300" fill="none" stroke="var(--karst-3)" stroke-width="28" stroke-linecap="round" opacity=".5"/>
        <text x="22" y="140" font-size="10" fill="var(--muted)" transform="rotate(-70 22 140)"><?= e($m['strait']) ?></text>
        <path d="M150 20 L170 260" stroke="var(--muted)" stroke-width="2" stroke-dasharray="6 5"/>
        <text x="178" y="250" font-size="9" fill="var(--muted)"><?= e($m['road']) ?></text>
        <path d="M120 40 C200 60 270 90 300 150 C320 200 260 240 190 230 C140 222 110 180 100 120 C95 80 100 50 120 40Z" fill="var(--karst-2)" opacity=".22" stroke="var(--karst-2)" stroke-width="1.5"/>
        <text x="210" y="105" font-size="22" font-family="var(--f-display)" fill="var(--karst-2)"><?= e($m['perak']) ?></text>
        <g font-size="12" fill="var(--ink)">
          <circle cx="146" cy="20" r="6" fill="var(--ink)"/><text x="158" y="24"><?= e($m['penang']) ?></text>
          <circle cx="160" cy="62" r="5" fill="var(--karst-2)"/><text x="172" y="66"><?= e($m['kerian']) ?></text>
          <circle cx="160" cy="142" r="8" fill="var(--gold)" stroke="var(--ink)" stroke-width="1.5"/><text x="174" y="146" font-weight="700"><?= e($m['ipoh']) ?></text>
          <circle cx="118" cy="172" r="5" fill="var(--karst-2)"/><text x="40" y="190"><?= e($m['lumut']) ?></text>
          <circle cx="172" cy="282" r="6" fill="var(--ink)"/><text x="184" y="286"><?= e($m['kl']) ?></text>
        </g>
      </svg>
      <div class="grid" style="gap:18px">
        <h2><?= e(t('why.corridor_title', $L)) ?></h2>
        <p class="lede"><?= fmt(t('why.corridor_lede', $L)) ?></p>
        <div class="dist"><?php foreach ($LIST['distance'] as $it): ?><div><span><?= e(iv($it, 'route', $L)) ?></span><span><?= e(iv($it, 'value', $L)) ?></span></div><?php endforeach; ?></div>
        <p class="ref"><?= e(t('why.dist_note', $L)) ?></p>
      </div>
    </div>
  </div></section>
  <section class="band alt"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('why.reasons_eyebrow', $L)) ?></p><h2><?= e(t('why.reasons_title', $L)) ?></h2></div>
    <div class="grid g2" style="gap:32px 48px"><?php foreach ($LIST['reason'] as $it): ?><div class="item"><h3><?= e(iv($it, 'title', $L)) ?></h3><p><?= fmt(iv($it, 'text', $L)) ?></p></div><?php endforeach; ?></div>
  </div></section>
  <?php if ($LIST['location']): $h = lines(t('why.loc_headers', $L)); ?>
  <section class="band"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('why.loc_eyebrow', $L)) ?></p><h2><?= e(t('why.loc_title', $L)) ?></h2></div>
    <div class="tbl-wrap"><table>
      <thead><tr><?php for ($i = 0; $i < 4; $i++): ?><th><?= e($h[$i] ?? '') ?></th><?php endfor; ?></tr></thead>
      <tbody><?php foreach ($LIST['location'] as $it): ?><tr><td><b><?= e(iv($it, 'name', $L)) ?></b></td><td><?= e(iv($it, 'district', $L)) ?></td><td><?= e(iv($it, 'focus', $L)) ?></td><td><?= e(iv($it, 'why', $L)) ?></td></tr><?php endforeach; ?></tbody>
    </table></div>
    <p class="ref" style="margin-top:12px"><?= e(t('why.loc_note', $L)) ?></p>
  </div></section>
  <?php endif; ?>
</div>

<!-- GUIDE -->
<div class="page" data-page="guide">
  <section class="band"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('guide.eyebrow', $L)) ?></p><h1><?= e(t('guide.title', $L)) ?></h1><p class="lede"><?= fmt(t('guide.lede', $L)) ?></p></div>
    <?php if (t('guide.callout', $L) !== ''): ?><div class="callout"><?= fmt(t('guide.callout', $L)) ?></div><?php endif; ?>
    <div class="reg-layout" style="margin-top:40px">
      <nav class="toc" aria-label="<?= e(t('nav.guide', $L)) ?>">
        <?php foreach ($LIST['rule'] as $it): ?><a href="#<?= $p ?>r-<?= (int)$it['id'] ?>" data-scroll><?= e(iv($it, 'short', $L) ?: iv($it, 'title', $L)) ?></a><?php endforeach; ?>
      </nav>
      <div>
        <?php foreach ($LIST['rule'] as $it): ?>
        <article class="rule" id="<?= $p ?>r-<?= (int)$it['id'] ?>">
          <h2><?= e(iv($it, 'title', $L)) ?></h2>
          <?php if (iv($it, 'intro', $L) !== ''): ?><p style="max-width:68ch;color:var(--muted)"><?= fmt(iv($it, 'intro', $L)) ?></p><?php endif; ?>
          <?php if (iv($it, 'mm2h') && $LIST['mm2h']): $mh = lines(t('guide.mm2h_headers', $L)); ?>
          <div class="tbl-wrap"><table>
            <thead><tr><?php for ($i = 0; $i < 4; $i++): ?><th><?= e($mh[$i] ?? '') ?></th><?php endfor; ?></tr></thead>
            <tbody><?php foreach ($LIST['mm2h'] as $r): ?><tr><td><b><?= e(iv($r, 'tier', $L)) ?></b></td><td class="n"><?= e(iv($r, 'deposit')) ?></td><td><?= e(iv($r, 'visa', $L)) ?></td><td class="n"><?= e(iv($r, 'property')) ?></td></tr><?php endforeach; ?></tbody>
          </table></div>
          <?php endif; ?>
          <?php $bl = lines(iv($it, 'bullets', $L)); if ($bl): ?><ul><?php foreach ($bl as $b): ?><li><?= fmt($b) ?></li><?php endforeach; ?></ul><?php endif; ?>
          <?php if (iv($it, 'ref', $L) !== ''): ?><p class="ref"><?= e(iv($it, 'ref', $L)) ?></p><?php endif; ?>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div></section>
</div>

<!-- GALLERY -->
<div class="page" data-page="gallery">
  <section class="band"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('gallery.eyebrow', $L)) ?></p><h1><?= e(t('gallery.title', $L)) ?></h1><p class="lede"><?= fmt(t('gallery.lede', $L)) ?></p></div>
    <div class="filters" role="group">
      <?php foreach (['all', 'industry', 'heritage', 'nature'] as $i => $f): ?><button class="chip" aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>" data-f="<?= $f ?>"><?= e(L('gallery.filters', $L, $i)) ?></button><?php endforeach; ?>
    </div>
    <div class="gallery">
      <?php foreach ($LIST['gallery'] as $n => $it): $img = iv($it, 'image'); ?>
      <button class="g-item" data-i="<?= $n ?>" data-c="<?= e(iv($it, 'category')) ?>" data-img="<?= $img ? e(img_url($img)) : '' ?>" data-scene="<?= e(iv($it, 'scene')) ?>" data-t="<?= e(iv($it, 'title', $L)) ?>" data-p="<?= e(iv($it, 'place', $L)) ?>" data-d="<?= e(iv($it, 'caption', $L)) ?>">
        <div class="ph"><?php if ($img): ?><img src="<?= e(img_url($img)) ?>" alt="<?= e(iv($it, 'title', $L)) ?>" loading="lazy"><?php endif; ?></div>
        <div class="cap"><b><?= e(iv($it, 'title', $L)) ?></b><span><?= e(iv($it, 'place', $L)) ?></span></div>
      </button>
      <?php endforeach; ?>
    </div>
  </div></section>
</div>

<!-- ABOUT -->
<div class="page" data-page="about">
  <section class="band"><div class="wrap about-grid">
    <div class="grid" style="gap:20px">
      <p class="eyebrow"><?= e(t('about.eyebrow', $L)) ?></p>
      <h1><?= e(t('about.title', $L)) ?></h1>
      <div class="prose">
        <p class="lede"><?= fmt(t('about.lede', $L)) ?></p>
        <?php foreach (preg_split('/\R\s*\R/u', trim(t('about.body', $L))) as $para): if (trim($para) === '') continue; ?><p><?= fmt($para) ?></p><?php endforeach; ?>
      </div>
      <?php if ($LIST['service']): ?>
      <h2 style="margin-top:16px"><?= e(t('about.services_title', $L)) ?></h2>
      <div class="grid g2"><?php foreach ($LIST['service'] as $it): ?><div class="tile"><h3><?= e(iv($it, 'title', $L)) ?></h3><p><?= fmt(iv($it, 'text', $L)) ?></p></div><?php endforeach; ?></div>
      <?php endif; ?>
    </div>
    <div class="grid" style="gap:20px">
      <dl class="facts"><?php foreach ($LIST['fact'] as $it): ?><div><dt><?= e(iv($it, 'label', $L)) ?></dt><dd><?= fmt(iv($it, 'value', $L)) ?></dd></div><?php endforeach; ?></dl>
      <?php if (t('about.commit_text', $L) !== ''): ?><div class="tile"><h3><?= e(t('about.commit_title', $L)) ?></h3><p><?= fmt(t('about.commit_text', $L)) ?></p></div><?php endif; ?>
    </div>
  </div></section>
</div>

<!-- PARTNERS -->
<div class="page" data-page="partners">
  <section class="band"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('partners.eyebrow', $L)) ?></p><h1><?= e(t('partners.title', $L)) ?></h1><p class="lede"><?= fmt(t('partners.lede', $L)) ?></p></div>
    <div class="grid g2">
      <?php foreach ($LIST['partner'] as $it): $logo = iv($it, 'logo'); $web = iv($it, 'website'); ?>
      <div class="partner">
        <div class="logo" aria-hidden="true"<?= $logo ? ' style="background:#fff;overflow:hidden"' : '' ?>><?php if ($logo): ?><img src="<?= e(img_url($logo)) ?>" alt="" style="width:100%;height:100%;object-fit:contain"><?php else: ?><?= e(iv($it, 'initial') ?: mb_substr(iv($it, 'name'), 0, 1)) ?><?php endif; ?></div>
        <?php if (iv($it, 'role', $L) !== ''): ?><span class="tag gold"><?= e(iv($it, 'role', $L)) ?></span><?php endif; ?>
        <h2 style="font-size:var(--s2)"><?= e(iv($it, 'name')) ?></h2>
        <?php if (iv($it, 'subtitle', $L) !== ''): ?><p class="ref"><?= e(iv($it, 'subtitle', $L)) ?></p><?php endif; ?>
        <?php if (iv($it, 'address') !== ''): ?><p class="ref"><?= nl(iv($it, 'address')) ?></p><?php endif; ?>
        <?php if ($web): $href = preg_match('~^https?://~i', $web) ? $web : 'https://' . $web; ?><p><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e(preg_replace('~^https?://~i', '', $web)) ?></a></p><?php endif; ?>
        <p style="color:var(--muted)"><?= fmt(iv($it, 'text', $L)) ?></p>
        <?php $bl = lines(iv($it, 'bullets', $L)); if ($bl): ?><ul><?php foreach ($bl as $b): ?><li><?= fmt($b) ?></li><?php endforeach; ?></ul><?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="tile" style="margin-top:20px"><h3><?= e(t('partners.cta_title', $L)) ?></h3><p><?= fmt(t('partners.cta_text', $L)) ?> <a href="#contact"><?= e(t('partners.cta_link', $L)) ?></a>.</p></div>
  </div></section>
</div>

<!-- FAQ -->
<div class="page" data-page="faq">
  <section class="band"><div class="wrap">
    <div class="sec-head"><p class="eyebrow"><?= e(t('faq.eyebrow', $L)) ?></p><h1><?= e(t('faq.title', $L)) ?></h1></div>
    <div style="max-width:820px"><?php foreach ($LIST['faq'] as $n => $it): ?><details<?= $n === 0 ? ' open' : '' ?>><summary><?= e(iv($it, 'q', $L)) ?></summary><p><?= fmt(iv($it, 'a', $L)) ?></p></details><?php endforeach; ?></div>
  </div></section>
</div>

<!-- CONTACT -->
<div class="page" data-page="contact">
  <section class="band"><div class="wrap contact-grid">
    <div class="grid" style="gap:20px">
      <p class="eyebrow"><?= e(t('contact.eyebrow', $L)) ?></p>
      <h1><?= e(t('contact.title', $L)) ?></h1>
      <p class="lede"><?= fmt(t('contact.lede', $L)) ?></p>
      <div class="grid" style="gap:14px">
        <?php if ($email): ?><div><p class="eyebrow"><?= e(L('contact.labels', $L, 0)) ?></p><div class="copy"><a class="mail" href="mailto:<?= e($email) ?>"><code><?= e($email) ?></code></a><button type="button" class="copy-mail" data-v="<?= e($email) ?>" data-label="<?= e(L('contact.copy', $L, 0)) ?>" data-done="<?= e(L('contact.copy', $L, 1)) ?>"><?= e(L('contact.copy', $L, 0)) ?></button></div></div><?php endif; ?>
        <?php if ($phone): ?><div><p class="eyebrow"><?= e(L('contact.labels', $L, 1)) ?></p><p class="num"><a href="tel:<?= e(preg_replace('/[^\d+]/', '', $phone)) ?>"><?= e($phone) ?></a></p></div><?php endif; ?>
        <?php if ($wa): ?><div><p class="eyebrow"><?= e(L('contact.labels', $L, 2)) ?></p><p><a href="https://wa.me/<?= e(wa_digits($wa)) ?>" target="_blank" rel="noopener"><?= e($wa) ?></a></p></div><?php endif; ?>
        <div><p class="eyebrow"><?= e(L('contact.labels', $L, 3)) ?></p><p><?= e(t('contact.company')) ?> <span class="num"><?= e(t('contact.regno')) ?></span><br><?= nl(t('contact.address')) ?></p></div>
        <?php if ($hours !== ''): ?><div><p class="eyebrow"><?= e(L('contact.labels', $L, 4)) ?></p><p><?= e($hours) ?></p></div><?php endif; ?>
      </div>
    </div>
    <?php $fl = lines(t('form.labels', $L)); ?>
    <form class="enq" data-lang="<?= $L ?>" action="enquiry.php" method="post" novalidate
          data-err-name="<?= e(t('form.err_name', $L)) ?>" data-err-email="<?= e(t('form.err_email', $L)) ?>" data-ok="<?= e(t('form.ok', $L)) ?>" data-fail="<?= e(t('form.fail', $L)) ?>">
      <input type="hidden" name="lang" value="<?= $L ?>"><input type="hidden" name="ts" value="<?= time() ?>">
      <div class="hp" aria-hidden="true"><label for="<?= $p ?>f-website">Website<input id="<?= $p ?>f-website" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="row2">
        <label for="<?= $p ?>f-name"><?= e($fl[0] ?? 'Name') ?><input id="<?= $p ?>f-name" name="name" required maxlength="200" autocomplete="name"></label>
        <label for="<?= $p ?>f-email"><?= e($fl[1] ?? 'Email') ?><input id="<?= $p ?>f-email" name="email" type="email" required maxlength="200" autocomplete="email"></label>
      </div>
      <div class="row2">
        <label for="<?= $p ?>f-country"><?= e($fl[2] ?? 'Country') ?><input id="<?= $p ?>f-country" name="country" maxlength="120" autocomplete="country-name"></label>
        <label for="<?= $p ?>f-company"><?= e($fl[3] ?? 'Company') ?><input id="<?= $p ?>f-company" name="company" maxlength="200" autocomplete="organization"></label>
      </div>
      <label for="<?= $p ?>f-phone"><?= e($fl[4] ?? 'Phone') ?><input id="<?= $p ?>f-phone" name="phone" type="tel" maxlength="60" autocomplete="tel"></label>
      <label for="<?= $p ?>f-interest"><?= e($fl[5] ?? 'Interest') ?><select id="<?= $p ?>f-interest" name="interest"><?php foreach (lines(t('form.interests', $L)) as $o): ?><option><?= e($o) ?></option><?php endforeach; ?></select></label>
      <label for="<?= $p ?>f-budget"><?= e($fl[6] ?? 'Budget') ?><select id="<?= $p ?>f-budget" name="budget"><?php foreach (lines(t('form.budgets', $L)) as $o): ?><option><?= e($o) ?></option><?php endforeach; ?></select></label>
      <label for="<?= $p ?>f-msg"><?= e($fl[7] ?? 'Message') ?><textarea id="<?= $p ?>f-msg" name="message" maxlength="5000" placeholder="<?= e(t('form.placeholder', $L)) ?>"></textarea></label>
      <p class="ref form-err" hidden style="color:var(--ink)"></p>
      <button class="btn primary" type="submit" style="justify-self:start"><?= e(t('form.submit', $L)) ?></button>
      <div class="form-msg form-ok" hidden></div>
    </form>
  </div></section>
</div>
</main>
<?php
}

function render_footer(string $L): void { ?>
  <div class="wrap" lang="<?= $L === 'zh' ? 'zh-Hans' : 'en' ?>" data-foot="<?= $L ?>"<?= $L === 'zh' ? ' hidden' : '' ?>>
    <div class="f-cols">
      <div class="grid" style="gap:10px"><b style="font-family:var(--f-display);font-weight:400;font-size:1.5rem"><?= e(t('site.name', $L)) ?></b><p style="opacity:.85;max-width:44ch"><?= fmt(t('footer.about', $L)) ?></p></div>
      <div class="links"><p class="eyebrow"><?= e(t('footer.explore', $L)) ?></p><?php foreach (['why', 'guide', 'gallery', 'faq'] as $pg): ?><a href="#<?= $pg ?>"><?= e(t('nav.' . $pg, $L)) ?></a><?php endforeach; ?></div>
      <div class="links"><p class="eyebrow"><?= e(t('footer.company', $L)) ?></p><?php foreach (['about', 'partners', 'contact'] as $pg): ?><a href="#<?= $pg ?>"><?= e(t('nav.' . $pg, $L)) ?></a><?php endforeach; ?></div>
    </div>
    <div class="fine">
      <?php foreach (lines(t('footer.disclaimer', $L)) as $d): ?><p><?= fmt($d) ?></p><?php endforeach; ?>
      <p><?= e(t('contact.company')) ?> (<?= e(t('contact.regno')) ?>) · <?= e(str_replace(["\r\n", "\n"], ', ', t('contact.address'))) ?><?= t('contact.phone') ? ' · ' . e(t('contact.phone')) : '' ?></p>
      <p><?= e(t('footer.copyright', $L)) ?></p>
    </div>
  </div>
<?php }

$navAttr = function (string $k): string { return ' data-en="' . e(t($k, 'en')) . '" data-zh="' . e(t($k, 'zh')) . '"'; };
header('Content-Type: text/html; charset=utf-8');
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e(t('site.name')) ?></title>
<meta name="description" content="<?= e(t('site.meta')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Gloock&family=Figtree:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&family=Noto+Sans+SC:wght@400;500;700&family=Noto+Serif+SC:wght@600&display=swap">
<link rel="stylesheet" href="assets/style.css?v=<?= @filemtime(ROOT . '/assets/style.css') ?>">
</head>
<body data-site-en="<?= e(t('site.name', 'en')) ?>" data-site-zh="<?= e(t('site.name', 'zh')) ?>">
<header class="site">
  <div class="wrap bar">
    <a class="brand" href="#home">
      <svg viewBox="0 0 34 22" aria-hidden="true"><path d="M0 22 L5 22 C6 10 9 6 11 6 C13 6 14 12 15 22 L16 22 C17 4 20 0 23 0 C26 0 28 8 29 22 L34 22Z" fill="var(--karst-2)"/><circle cx="28" cy="5" r="3.2" fill="var(--gold)"/></svg>
      <span><b data-en="<?= e(t('site.name', 'en')) ?>" data-zh="<?= e(t('site.name', 'zh')) ?>"><?= e(t('site.name')) ?></b><small<?= $navAttr('site.brand_small') ?>><?= e(t('site.brand_small')) ?></small></span>
    </a>
    <div class="lang-sw" role="group" aria-label="Language / 语言"><button type="button" data-setlang="en" aria-pressed="true">EN</button><button type="button" data-setlang="zh" aria-pressed="false">中文</button></div>
    <button class="menu-btn" id="menuBtn" aria-expanded="false" aria-controls="mainNav"<?= $navAttr('nav.menu') ?>><?= e(t('nav.menu')) ?></button>
    <nav class="main" id="mainNav" aria-label="Main">
      <?php foreach ($PAGES as $pg): ?><a href="#<?= $pg ?>"<?= $navAttr('nav.' . $pg) ?>><?= e(t('nav.' . $pg)) ?></a><?php endforeach; ?>
    </nav>
  </div>
</header>

<?php render_main('en'); render_main('zh'); ?>

<footer><?php render_footer('en'); render_footer('zh'); ?></footer>

<div class="lightbox" id="lb" hidden role="dialog" aria-modal="true" aria-label="Photo">
  <figure>
    <div class="lb-bar">
      <?php foreach (['lbPrev', 'lbNext', 'lbClose'] as $i => $id): ?><button type="button" id="<?= $id ?>" data-en="<?= e(L('gallery.lb', 'en', $i)) ?>" data-zh="<?= e(L('gallery.lb', 'zh', $i)) ?>"><?= e(L('gallery.lb', 'en', $i)) ?></button><?php endforeach; ?>
    </div>
    <div class="ph" id="lbPh"></div>
    <figcaption><b id="lbT"></b><span id="lbD"></span></figcaption>
  </figure>
</div>
<script src="assets/site.js?v=<?= @filemtime(ROOT . '/assets/site.js') ?>"></script>
</body>
</html>
