<?php
/*
 * Content schema: every editable text and list on the site, with the starting content.
 * Field types: text (one line), textarea (paragraph), lines (one entry per line)
 * "bi" = bilingual (English + 中文). Use **double asterisks** for bold in longer texts.
 */

function F(string $label, string $en, string $zh, string $type = 'text', string $help = ''): array {
    return ['label' => $label, 'type' => $type, 'bi' => true, 'en' => $en, 'zh' => $zh, 'help' => $help];
}
function S(string $label, string $val, string $type = 'text', string $help = ''): array {
    return ['label' => $label, 'type' => $type, 'bi' => false, 'en' => $val, 'zh' => '', 'help' => $help];
}

$ADDR = "No. 27, Jalan Selamat Indah 1/1, Taman Selamat Indah 1, 32000 Sitiawan, Perak, Malaysia";

$TEXT_GROUPS = [
'general' => ['label' => 'Site & contact details', 'fields' => [
    'site.name'        => F('Site name', 'Invest Perak', '投资霹雳 Invest Perak'),
    'site.brand_small' => F('Small line under logo', 'investperak.com · by Alphront', 'investperak.com · Alphront 运营'),
    'site.meta'        => F('Search engine description', 'Investment information and facilitation for foreign investors in Perak, Malaysia, by Alphront Sdn. Bhd.', '霹雳州外国投资资讯与协助服务，由 Alphront Sdn. Bhd. 提供。', 'textarea'),
    'contact.company'  => S('Company name', 'Alphront Sdn. Bhd.'),
    'contact.regno'    => S('Company registration no.', '1370348-K'),
    'contact.address'  => S('Office address', $ADDR, 'textarea'),
    'contact.phone'    => S('Phone', '+60 18-228 0090'),
    'contact.whatsapp' => S('WhatsApp number', '+60 11-1166 9789', 'text', 'Include country code. Leave empty to hide.'),
    'contact.email'    => S('Email', 'low.alphront@gmail.com'),
    'contact.hours'    => F('Office hours', '', '', 'text', 'Leave empty to hide.'),
]],
'nav' => ['label' => 'Menu labels', 'fields' => [
    'nav.home'     => F('Home', 'Home', '首页'),
    'nav.why'      => F('Why Perak', 'Why Perak', '为何投资霹雳'),
    'nav.guide'    => F('Investor Guide', 'Investor Guide', '投资指南'),
    'nav.gallery'  => F('Gallery', 'Gallery', '图片集'),
    'nav.about'    => F('About Us', 'About Us', '关于我们'),
    'nav.partners' => F('Partners', 'Partners', '合作伙伴'),
    'nav.faq'      => F('FAQ', 'FAQ', '常见问题'),
    'nav.contact'  => F('Contact', 'Contact', '联系我们'),
    'nav.menu'     => F('Mobile menu button', 'Menu', '菜单'),
]],
'home' => ['label' => 'Home page', 'fields' => [
    'home.eyebrow'    => F('Hero small heading', 'Perak Darul Ridzuan · Malaysia', '霹雳州 · 马来西亚'),
    'home.title'      => F('Hero title', 'Invest in Perak,', '投资霹雳，'),
    'home.title_hl'   => F('Hero title (highlighted part)', 'between Penang and Kuala Lumpur.', '坐拥槟城与吉隆坡之间的黄金位置。'),
    'home.lede'       => F('Hero introduction', 'Perak has land, labour and a central location on Malaysia\'s west coast. Alphront Sdn. Bhd. helps foreign investors understand the rules, find the right site and get set up, with our agency and legal partners at every step.', '霹雳州拥有充足的土地、人力资源，并位于马来西亚西海岸的中心地带。Alphront Sdn. Bhd. 协助海外投资者了解法规、选择合适地点并完成落地，我们的中介与法律合作伙伴全程参与。', 'textarea'),
    'home.cta1'       => F('Main button', 'Book a consultation', '预约咨询'),
    'home.cta2'       => F('Second button', 'Read the investor guide', '阅读投资指南'),
    'home.coord'      => F('Small line under buttons', '4.5975° N, 101.0901° E · Ipoh, state capital', '北纬 4.5975° · 东经 101.0901° · 州首府怡保'),
    'home.stats_eyebrow' => F('Figures – small heading', 'Perak at a glance', '霹雳概览'),
    'home.stats_title'   => F('Figures – title', 'A track record investors can check', '有据可查的投资成绩'),
    'home.sectors_eyebrow' => F('Sectors – small heading', 'Opportunity sectors', '重点行业'),
    'home.sectors_title'   => F('Sectors – title', 'Where the demand is in Perak', '霹雳州的投资机会'),
    'home.sectors_lede'    => F('Sectors – introduction', 'Perak\'s economy runs from semiconductor supply chains in the north to port logistics on the coast and heritage tourism in Ipoh.', '霹雳州的经济版图从北部的半导体供应链，延伸到沿海的港口物流，再到怡保的文化旅游。', 'textarea'),
    'home.steps_eyebrow' => F('Process – small heading', 'How we work with you', '服务流程'),
    'home.steps_title'   => F('Process – title', 'From first enquiry to operating in Perak', '从首次咨询到在霹雳运营'),
    'home.steps_cta'     => F('Process – button', 'Start an enquiry', '开始咨询'),
]],
'why' => ['label' => 'Why Perak page', 'fields' => [
    'why.eyebrow' => F('Small heading', 'Why Perak', '为何投资霹雳'),
    'why.title'   => F('Title', 'Reasons to invest in Perak', '投资霹雳州的理由'),
    'why.lede'    => F('Introduction', 'Perak is Malaysia\'s fourth-largest state by area. It grew rich on tin: the Kinta Valley around Ipoh was once the richest tin field in the world. Today that space, infrastructure and workforce serve new industries.', '霹雳州面积位居马来西亚第四。它因锡矿而兴盛：怡保周边的近打谷曾是全球最富饶的锡矿区。如今，这里的土地、基础设施和人力正服务于新兴产业。', 'textarea'),
    'why.corridor_title' => F('Location – title', 'In the middle of the west-coast corridor', '位于西海岸经济走廊中心'),
    'why.corridor_lede'  => F('Location – text', 'Most of Malaysia\'s industry sits along the strip from Penang to Kuala Lumpur and Port Klang. Perak lies between them, on the North–South Expressway and the electrified west-coast rail line.', '马来西亚的工业大多集中在槟城至吉隆坡及巴生港一带。霹雳州正处其中，南北大道和西海岸电气化铁路贯穿全州。', 'textarea'),
    'why.dist_note'      => F('Location – small note', 'Distances and times are approximate road and rail figures.', '距离与时间均为公路及铁路的大约数字。'),
    'why.reasons_eyebrow' => F('Reasons – small heading', 'Eight reasons', '八大理由'),
    'why.reasons_title'   => F('Reasons – title', 'What Perak offers investors', '霹雳州能为投资者提供什么'),
    'why.loc_eyebrow'  => F('Locations – small heading', 'Industrial locations', '工业地点'),
    'why.loc_title'    => F('Locations – title', 'Key investment areas', '重点投资区域'),
    'why.loc_headers'  => F('Locations – table headings', "Location\nDistrict\nFocus\nWhy it matters", "地点\n县\n重点\n优势", 'lines', 'Four lines, one per column.'),
    'why.loc_note'     => F('Locations – source note', 'Sources: MIDA, MITI and InvestPerak public statements, 2024–2026. Ask us for the latest site availability and pricing.', '资料来源：MIDA、MITI 及霹雳州投资机构公开信息（2024–2026年）。如需最新地块供应和价格，请与我们联系。', 'textarea'),
]],
'guide' => ['label' => 'Investor Guide page', 'fields' => [
    'guide.eyebrow' => F('Small heading', 'Investor guide', '投资指南'),
    'guide.title'   => F('Title', 'Regulations for foreign investors', '外国投资者相关法规'),
    'guide.lede'    => F('Introduction', 'A summary of the main rules a foreign company or individual meets when investing in Perak. Federal law sets most of them; the Perak State Government controls land and property.', '外国公司或个人在霹雳州投资时会涉及的主要规定摘要。多数规定由联邦法律制定；土地和房地产则由霹雳州政府管辖。', 'textarea'),
    'guide.callout' => F('Notice box', '**Please note:** this page is general information, last reviewed October 2026. Rules change. Our legal partner can confirm what applies to your project before you commit.', '**请注意：**本页仅为一般资料，最后审阅于2026年10月。法规可能变更，在您作出承诺前，我们的法律合作伙伴可确认适用于您项目的规定。', 'textarea'),
    'guide.mm2h_headers' => F('MM2H table headings', "Tier\nFixed deposit\nVisa\nMinimum property purchase", "级别\n定期存款\n签证\n最低购房金额", 'lines', 'Four lines, one per column.'),
]],
'gallery' => ['label' => 'Gallery page', 'fields' => [
    'gallery.eyebrow' => F('Small heading', 'Photo gallery', '图片集'),
    'gallery.title'   => F('Title', 'Perak in pictures', '图说霹雳'),
    'gallery.lede'    => F('Introduction', 'Industry, heritage and nature across the state. Select a picture to enlarge it.', '全州的产业、文化遗产和自然风光。点击图片可放大查看。', 'textarea'),
    'gallery.filters' => F('Filter buttons', "All\nIndustry\nHeritage\nNature", "全部\n产业\n文化遗产\n自然", 'lines', 'Four lines: All, Industry, Heritage, Nature.'),
    'gallery.lb'      => F('Photo viewer buttons', "← Prev\nNext →\nClose", "← 上一张\n下一张 →\n关闭", 'lines', 'Three lines: previous, next, close.'),
]],
'about' => ['label' => 'About Us page', 'fields' => [
    'about.eyebrow' => F('Small heading', 'About us', '关于我们'),
    'about.title'   => F('Title', 'Alphront Sdn. Bhd.', 'Alphront Sdn. Bhd.'),
    'about.lede'    => F('Introduction', 'Alphront Sdn. Bhd. runs investperak.com to help overseas businesses and individuals invest in Perak with confidence. We have worked in investment and property services for more than 15 years, with a focus on Ipoh and Manjung.', 'Alphront Sdn. Bhd. 运营 investperak.com，帮助海外企业和个人放心投资霹雳州。我们在投资与房地产服务领域拥有逾15年经验，重点服务怡保和曼绒。', 'textarea'),
    'about.body'    => F('Main text', "Investing in a new country means unfamiliar laws, many agencies and a lot of paperwork. We act as your single point of contact. We explain the rules, introduce you to sites and people, and work with our agency and legal partners so your project moves forward without delay.\n\nWe work with manufacturers looking for a plant, service companies opening a regional office, and individuals buying property or relocating under MM2H.", "到新国家投资意味着陌生的法律、众多的政府机构和大量文书工作。我们是您的单一联络窗口：解释法规、介绍地点与人脉，并与中介及法律合作伙伴协作，让您的项目顺利推进。\n\n我们的客户包括寻找厂房的制造商、设立区域办事处的服务企业，以及购买房产或通过 MM2H 移居的个人。", 'textarea', 'Leave a blank line between paragraphs.'),
    'about.services_title' => F('Services – title', 'What we do', '我们的服务'),
    'about.commit_title'   => F('Commitments – title', 'Our commitments', '我们的承诺'),
    'about.commit_text'    => F('Commitments – text', 'Clear, accurate information. Fees agreed in writing before work starts. Legal questions handled by qualified lawyers. Your project details kept confidential.', '提供清晰准确的信息；开工前以书面确认费用；法律问题由合格律师处理；严格保密您的项目资料。', 'textarea'),
]],
'partners' => ['label' => 'Partners page', 'fields' => [
    'partners.eyebrow' => F('Small heading', 'Our partners', '合作伙伴'),
    'partners.title'   => F('Title', 'One team, three specialists', '一个团队，三方专业'),
    'partners.lede'    => F('Introduction', 'Alphront coordinates your project. Our partners bring local agency and legal expertise, so you deal with one team.', 'Alphront 负责统筹您的项目，合作伙伴提供本地中介和法律专业服务，您只需对接一个团队。', 'textarea'),
    'partners.cta_title' => F('Bottom box – title', 'Interested in partnering with us?', '有意与我们合作？'),
    'partners.cta_text'  => F('Bottom box – text', 'We work with consultants, banks, accountants and developers who serve foreign investors.', '我们与服务外国投资者的顾问、银行、会计师和发展商合作。', 'textarea'),
    'partners.cta_link'  => F('Bottom box – link text', 'Get in touch', '欢迎联系'),
]],
'faq' => ['label' => 'FAQ page', 'fields' => [
    'faq.eyebrow' => F('Small heading', 'FAQ', '常见问题'),
    'faq.title'   => F('Title', 'Common questions', '常见问题解答'),
]],
'contact' => ['label' => 'Contact page & form', 'fields' => [
    'contact.eyebrow' => F('Small heading', 'Contact', '联系我们'),
    'contact.title'   => F('Title', 'Talk to our team', '与我们的团队洽谈'),
    'contact.lede'    => F('Introduction', 'Tell us about your plans. We reply within two working days.', '请告诉我们您的计划，我们将在两个工作日内回复。', 'textarea'),
    'contact.labels'  => F('Contact detail labels', "Email\nPhone\nWhatsApp\nOffice\nHours", "电邮\n电话\nWhatsApp\n办公室\n办公时间", 'lines', 'Five lines: email, phone, WhatsApp, office, hours.'),
    'contact.copy'    => F('Copy button', "Copy\nCopied\nSelected", "复制\n已复制\n已选取", 'lines', 'Three lines: Copy, Copied, Selected.'),
    'form.labels'     => F('Form field labels', "Full name\nEmail\nCountry\nCompany (optional)\nPhone / WhatsApp\nI'm interested in\nEstimated investment\nYour project", "姓名\n电邮\n国家／地区\n公司（选填）\n电话／WhatsApp\n我感兴趣的是\n预计投资额\n您的项目", 'lines', 'Eight lines in this order: name, email, country, company, phone, interest, investment, project.'),
    'form.placeholder' => F('Project box hint', 'Sector, timeline, land or space needed…', '行业、时间表、所需土地或空间……'),
    'form.interests'  => F('"Interested in" options', "Setting up a factory or plant\nOpening an office or services business\nBuying property\nMM2H residence\nPartnering with Alphront\nSomething else", "设立工厂\n开设办事处或服务业务\n购买房产\nMM2H 第二家园\n与 Alphront 合作\n其他", 'lines'),
    'form.budgets'    => F('Investment options', "Below RM1 million\nRM1–10 million\nRM10–50 million\nAbove RM50 million\nNot sure yet", "RM100万以下\nRM100万–1,000万\nRM1,000万–5,000万\nRM5,000万以上\n尚未确定", 'lines'),
    'form.submit'     => F('Send button', 'Send enquiry', '提交咨询'),
    'form.err_name'   => F('Error: missing name', 'Please enter your name.', '请输入您的姓名。'),
    'form.err_email'  => F('Error: bad email', 'Please enter a valid email address, e.g. name@company.com.', '请输入有效的电邮地址，例如 name@company.com。'),
    'form.ok'         => F('Success message', 'Thank you. Your enquiry has been sent and our team will reply within two working days.', '谢谢您！您的咨询已发送，我们的团队将在两个工作日内回复。', 'textarea'),
    'form.fail'       => F('Error: could not send', 'Sorry, your enquiry could not be sent. Please email or WhatsApp us instead.', '抱歉，咨询未能发送。请改用电邮或 WhatsApp 联系我们。', 'textarea'),
]],
'footer' => ['label' => 'Footer', 'fields' => [
    'footer.about'   => F('Short description', 'Investment information and facilitation for Perak, Malaysia. Operated by Alphront Sdn. Bhd. with our agency and legal partners.', '霹雳州投资资讯与协助服务。由 Alphront Sdn. Bhd. 联同中介及法律合作伙伴运营。', 'textarea'),
    'footer.explore' => F('Column heading 1', 'Explore', '浏览'),
    'footer.company' => F('Column heading 2', 'Company', '公司'),
    'footer.disclaimer' => F('Disclaimer', "investperak.com is a private website operated by Alphront Sdn. Bhd. It is not the official website of the Perak State Government or its investment agency.\nInformation on this site is general guidance, not legal or financial advice. Figures from MIDA, MITI and public sources; please confirm before making decisions.", "investperak.com 是由 Alphront Sdn. Bhd. 运营的私人网站，并非霹雳州政府或其投资机构的官方网站。\n本网站资料仅供一般参考，不构成法律或财务建议。数据来自 MIDA、MITI 及公开来源，作出决定前请予以核实。", 'lines', 'One paragraph per line.'),
    'footer.copyright' => F('Copyright', '© 2026 Alphront Sdn. Bhd. All rights reserved.', '© 2026 Alphront Sdn. Bhd. 版权所有。'),
]],
];

/* Repeatable lists. Field: [label, type, bilingual?, options?]  types: text, textarea, lines, select, check, image */
$ITEM_TYPES = [
'stat' => ['label' => 'Home – key figures', 'group' => 'home', 'title' => 'value', 'fields' => [
    'value' => ['Figure', 'text', true], 'label' => ['Label', 'text', true], 'note' => ['Small note', 'text', true]]],
'sector' => ['label' => 'Home – sectors', 'group' => 'home', 'title' => 'title', 'fields' => [
    'tag' => ['Tag', 'text', true], 'priority' => ['Highlight tag in gold', 'check', false],
    'title' => ['Title', 'text', true], 'text' => ['Text', 'textarea', true]]],
'step' => ['label' => 'Home – process steps', 'group' => 'home', 'title' => 'title', 'fields' => [
    'title' => ['Title', 'text', true], 'text' => ['Text', 'textarea', true]]],
'distance' => ['label' => 'Why Perak – distances', 'group' => 'why', 'title' => 'route', 'fields' => [
    'route' => ['Route', 'text', true], 'value' => ['Distance / time', 'text', true]]],
'reason' => ['label' => 'Why Perak – reasons', 'group' => 'why', 'title' => 'title', 'fields' => [
    'title' => ['Title', 'text', true], 'text' => ['Text', 'textarea', true]]],
'location' => ['label' => 'Why Perak – investment areas', 'group' => 'why', 'title' => 'name', 'fields' => [
    'name' => ['Location', 'text', true], 'district' => ['District', 'text', true], 'focus' => ['Focus', 'text', true], 'why' => ['Why it matters', 'text', true]]],
'rule' => ['label' => 'Investor Guide – sections', 'group' => 'guide', 'title' => 'title', 'fields' => [
    'title' => ['Title', 'text', true], 'short' => ['Short name for side menu', 'text', true],
    'intro' => ['Intro paragraph (optional)', 'textarea', true],
    'bullets' => ['Points (one per line, **bold** allowed)', 'lines', true],
    'ref' => ['Sources line', 'text', true], 'mm2h' => ['Show MM2H tier table in this section', 'check', false]]],
'mm2h' => ['label' => 'Investor Guide – MM2H tiers', 'group' => 'guide', 'title' => 'tier', 'fields' => [
    'tier' => ['Tier', 'text', true], 'deposit' => ['Fixed deposit', 'text', false], 'visa' => ['Visa', 'text', true], 'property' => ['Minimum property', 'text', false]]],
'gallery' => ['label' => 'Gallery photos', 'group' => 'gallery', 'title' => 'title', 'fields' => [
    'image' => ['Photo (JPG, PNG or WebP, max 6 MB)', 'image', false],
    'scene' => ['Illustration used when no photo is uploaded', 'select', false, ['' => '— none —', 'karst' => 'Limestone hills', 'kerian' => 'Industrial park', 'port' => 'Port', 'oldtown' => 'Old town', 'dredge' => 'Tin dredge', 'pangkor' => 'Island beach', 'belum' => 'Rainforest lake', 'taiping' => 'Lake gardens']],
    'category' => ['Category', 'select', false, ['industry' => 'Industry', 'heritage' => 'Heritage', 'nature' => 'Nature']],
    'title' => ['Title', 'text', true], 'caption' => ['Caption', 'textarea', true], 'place' => ['Place', 'text', true]]],
'fact' => ['label' => 'About Us – company facts', 'group' => 'about', 'title' => 'label', 'fields' => [
    'label' => ['Label', 'text', true], 'value' => ['Value', 'textarea', true]]],
'service' => ['label' => 'About Us – services', 'group' => 'about', 'title' => 'title', 'fields' => [
    'title' => ['Title', 'text', true], 'text' => ['Text', 'textarea', true]]],
'partner' => ['label' => 'Partners', 'group' => 'partners', 'title' => 'name', 'fields' => [
    'name' => ['Partner name', 'text', false], 'initial' => ['Logo letter', 'text', false],
    'logo' => ['Logo image (optional)', 'image', false],
    'role' => ['Role tag', 'text', true], 'subtitle' => ['Type of firm', 'text', true],
    'address' => ['Address', 'textarea', false], 'website' => ['Website (optional)', 'text', false],
    'text' => ['Description', 'textarea', true], 'bullets' => ['Services (one per line)', 'lines', true]]],
'faq' => ['label' => 'FAQ questions', 'group' => 'faq', 'title' => 'q', 'fields' => [
    'q' => ['Question', 'text', true], 'a' => ['Answer', 'textarea', true]]],
];

function field_def(string $key): ?array {
    global $TEXT_GROUPS;
    foreach ($TEXT_GROUPS as $g) if (isset($g['fields'][$key])) return $g['fields'][$key];
    return null;
}

/* ---------- starting list content ---------- */
function B(string $en, string $zh): array { return ['en' => $en, 'zh' => $zh]; }

function seed_items(): array {
    $OPT = 'D-03-07, Se Ruang Square, Jalan Eco Santuari 8/3, 42500 Telok Panglima Garang, Selangor, Malaysia';
    $FOO = 'No. 39, 1st Floor, Persiaran PM 2/3, Pusat Bandar Seri Manjung, Seksyen II, 32040 Seri Manjung, Perak';
    return [
    'stat' => [
        ['value' => B('RM15.7b', 'RM157亿'), 'label' => B('Approved investment, 2025', '2025年批准投资额'), 'note' => B('Total approved for Perak, as reported by MITI', '据马来西亚投资贸易及工业部（MITI）公布的霹雳州批准总额')],
        ['value' => B('87%', '87%'), 'label' => B('Realisation rate', '落实率'), 'note' => B('Approved manufacturing projects 2021–2025 now implemented (MIDA data)', '2021–2025年获批制造业项目已落实比例（MIDA 数据）')],
        ['value' => B('RM61b', 'RM610亿'), 'label' => B('Realised since 1980', '1980年以来已落实投资'), 'note' => B('Electrical & electronics leads at RM13.1b', '电子电气领域居首，达 RM131亿')],
    ],
    'sector' => [
        ['tag' => B('Priority', '优先'), 'priority' => '1', 'title' => B('Electrical & electronics', '电子电气'), 'text' => B('Kerian sits next to the Penang and Kulim semiconductor clusters. New parks there target E&E, chips and supporting suppliers.', '吉辇（Kerian）紧邻槟城和居林半导体产业集群，当地新工业园专注电子电气、芯片及配套供应商。')],
        ['tag' => B('Priority', '优先'), 'priority' => '1', 'title' => B('Green & advanced manufacturing', '绿色与先进制造'), 'text' => B('Solar glass, renewable components and precision engineering, with large industrial land banks still available.', '太阳能玻璃、再生能源组件与精密工程，并拥有大量可供开发的工业用地。')],
        ['tag' => B('Growth', '增长'), 'priority' => '', 'title' => B('Logistics & maritime', '物流与海事'), 'text' => B('Lumut\'s port and shipyards serve the Strait of Malacca, linked by highway to the North–South Expressway.', '红土坎（Lumut）港口与造船厂面向马六甲海峡，经公路连接南北大道。')],
        ['tag' => B('Growth', '增长'), 'priority' => '', 'title' => B('Agriculture & food', '农业与食品'), 'text' => B('Fertile land along the Perak River supports agro-processing, aquaculture and modern farming.', '霹雳河沿岸土地肥沃，适合农产品加工、水产养殖和现代农业。')],
        ['tag' => B('Growth', '增长'), 'priority' => '', 'title' => B('Tourism & hospitality', '旅游与酒店'), 'text' => B('Ipoh\'s old town, Pangkor Island and the Royal Belum rainforest draw domestic and regional visitors.', '怡保旧街场、邦咯岛和皇家贝隆雨林吸引国内外游客。')],
        ['tag' => B('Growth', '增长'), 'priority' => '', 'title' => B('Property & second home', '房地产与第二家园'), 'text' => B('Ipoh and Manjung offer lower living costs than Kuala Lumpur or Penang for residents under the MM2H programme.', '与吉隆坡或槟城相比，怡保和曼绒生活成本更低，适合 MM2H 计划参与者定居。')],
    ],
    'step' => [
        ['title' => B('Enquiry', '咨询'), 'text' => B('Tell us your sector, budget and timeline. We reply within two working days.', '告诉我们您的行业、预算和时间表，我们将在两个工作日内回复。')],
        ['title' => B('Briefing', '简报'), 'text' => B('Sector briefing, site shortlist and a cost picture for your project.', '提供行业简报、候选地点和项目成本概况。')],
        ['title' => B('Structuring', '架构规划'), 'text' => B('Our legal partner advises on company structure, equity and licences.', '法律合作伙伴就公司架构、股权和执照提供意见。')],
        ['title' => B('Approvals', '审批'), 'text' => B('Incorporation, MIDA incentives, state consents and work passes.', '公司注册、MIDA 优惠、州政府批准及工作准证。')],
        ['title' => B('Aftercare', '后续支持'), 'text' => B('Hiring, local suppliers and help as you expand.', '招聘、本地供应商对接及扩展支持。')],
    ],
    'distance' => [
        ['route' => B('Ipoh → Kuala Lumpur', '怡保 → 吉隆坡'), 'value' => B('~205 km · ~2.5 h drive', '约205公里 · 车程约2.5小时')],
        ['route' => B('Ipoh → Penang', '怡保 → 槟城'), 'value' => B('~160 km · ~2 h drive', '约160公里 · 车程约2小时')],
        ['route' => B('Kerian → Kulim Hi-Tech Park', '吉辇 → 居林高科技园'), 'value' => B('<1 h drive', '车程不到1小时')],
        ['route' => B('Ipoh → KL by ETS train', '怡保 → 吉隆坡（ETS 电动火车）'), 'value' => B('~2.5 h', '约2.5小时')],
    ],
    'reason' => [
        ['title' => B('Next to the semiconductor belt', '毗邻半导体产业带'), 'text' => B('Kerian borders Penang and Kulim, so suppliers and plants there can serve the region\'s chip makers without paying Penang land prices.', '吉辇与槟城、居林接壤，当地的供应商和工厂可服务区域芯片制造商，却无需承担槟城的地价。')],
        ['title' => B('Industrial land at scale', '大面积工业用地'), 'text' => B('New parks such as Kerian Integrated Green Industrial Park (KIGIP), Kerian Industrial City and Silver Valley Technology Park offer large plots for long-term growth.', '吉辇综合绿色工业园（KIGIP）、吉辇工业城和银谷科技园等新园区提供大面积地段，满足长期发展。')],
        ['title' => B('Lower costs', '成本更低'), 'text' => B('Land, rent and wages are lower than in neighbouring industrial zones, and Ipoh has one of the lowest living costs among Malaysia\'s major cities.', '土地、租金和工资均低于邻近工业区，怡保也是马来西亚主要城市中生活成本最低的之一。')],
        ['title' => B('Commitments that get built', '承诺能够落实'), 'text' => B('About 87% of manufacturing investment approved for Perak from 2021 to 2025 has been implemented, according to MIDA data.', '根据 MIDA 数据，霹雳州2021至2025年获批的制造业投资约87%已经落实。')],
        ['title' => B('A trained workforce', '训练有素的劳动力'), 'text' => B('Perak has universities and technical institutes, including Universiti Teknologi PETRONAS and UPSI, plus skills centres serving industry.', '州内设有国油科技大学（UTP）、苏丹依德利斯教育大学（UPSI）等高等学府及技术学院，并有服务产业的技能中心。')],
        ['title' => B('Port, road and rail', '港口、公路与铁路'), 'text' => B('Lumut Port on the Strait of Malacca, the North–South Expressway, ETS rail and Sultan Azlan Shah Airport in Ipoh.', '马六甲海峡旁的红土坎港、南北大道、ETS 电动火车，以及怡保苏丹阿兹兰沙机场。')],
        ['title' => B('Open to foreign ownership', '开放外资持股'), 'text' => B('Foreign investors can hold 100% of manufacturing companies, and most services sectors have no general equity limit.', '外国投资者可100%持有制造业公司，大部分服务业也没有一般性股权限制。')],
        ['title' => B('A good place to live', '宜居之地'), 'text' => B('Heritage towns, food culture, islands and rainforest make Perak easy to relocate managers and families to.', '历史古镇、美食文化、海岛与雨林，让管理人员和家属更容易安家。')],
    ],
    'location' => [
        ['name' => B('Kerian Integrated Green Industrial Park (KIGIP)', '吉辇综合绿色工业园（KIGIP）'), 'district' => B('Kerian', '吉辇'), 'focus' => B('E&E, semiconductor, logistics, ICT', '电子电气、半导体、物流、资讯通信'), 'why' => B('Next to Penang and Kulim Hi-Tech Park', '紧邻槟城与居林高科技园')],
        ['name' => B('Kerian Industrial City', '吉辇工业城'), 'district' => B('Kerian', '吉辇'), 'focus' => B('Manufacturing, E&E supply chain', '制造业、电子电气供应链'), 'why' => B('Second industrial hub in the same corridor', '同一走廊中的第二个工业中心')],
        ['name' => B('Silver Valley Technology Park', '银谷科技园'), 'district' => B('Kinta / Ipoh region', '近打／怡保一带'), 'focus' => B('Green technology, solar glass', '绿色科技、太阳能玻璃'), 'why' => B('Hosts a solar glass project by China\'s Jinjing Silicon Technology', '中国晶景硅业太阳能玻璃项目所在地')],
        ['name' => B('Lumut maritime area', '红土坎海事区'), 'district' => B('Manjung', '曼绒'), 'focus' => B('Port, shipbuilding, logistics', '港口、造船、物流'), 'why' => B('Direct access to Strait of Malacca shipping lanes', '直通马六甲海峡航道')],
        ['name' => B('Ipoh city', '怡保市'), 'district' => B('Kinta', '近打'), 'focus' => B('Services, tourism, property', '服务业、旅游、房地产'), 'why' => B('State capital with airport and rail', '拥有机场和铁路的州首府')],
    ],
    'rule' => [
        ['title' => B('Setting up a company', '设立公司'), 'short' => B('Setting up a company', '设立公司'), 'intro' => B('', ''), 'mm2h' => '',
         'bullets' => B("Most foreign investors incorporate a private limited company (Sdn Bhd) with the Companies Commission of Malaysia (SSM) under the Companies Act 2016.\nA Sdn Bhd needs at least one shareholder and one director who ordinarily lives in Malaysia, plus a licensed company secretary.\nA foreign company can also register a branch or, for some activities, a representative or regional office approved by MIDA.\nRegister for tax with LHDN and, where relevant, with the local authority (business premises licence) and SOCSO/EPF for staff.",
                        "大多数外国投资者会根据《2016年公司法》，在马来西亚公司委员会（SSM）注册私人有限公司（Sdn Bhd）。\n私人有限公司至少需要一名股东、一名通常居住在马来西亚的董事，以及一名持牌公司秘书。\n外国公司也可注册分公司；部分业务可经 MIDA 批准设立代表处或区域办事处。\n需在内陆税收局（LHDN）办理税务登记，并视情况申请地方政府营业执照，以及为员工登记社险（SOCSO）和公积金（EPF）。"),
         'ref' => B('Companies Act 2016 · SSM · MITI', '《2016年公司法》 · SSM · MITI')],
        ['title' => B('Foreign ownership', '外资持股'), 'short' => B('Foreign ownership', '外资持股'), 'intro' => B('', ''), 'mm2h' => '',
         'bullets' => B("**Manufacturing:** since June 2003, foreign investors may hold 100% of new projects and expansions, with no export requirement and no excluded products.\n**Services:** the Companies Act sets no equity condition. Individual regulators may set limits for specific licences, for example in distributive trade, telecommunications, finance or professional services.\nJoint ventures with Malaysian partners are encouraged but generally not required.",
                        "**制造业：**自2003年6月起，外国投资者可100%持有新项目及扩建项目，无出口要求，也无排除产品。\n**服务业：**《公司法》未设股权条件，但个别监管机构可能对特定执照设限，例如分销贸易、电信、金融或专业服务。\n政府鼓励与马来西亚伙伴合资，但一般并非强制。"),
         'ref' => B('MIDA Equity Policy', 'MIDA 股权政策')],
        ['title' => B('Manufacturing licence', '制造业执照'), 'short' => B('Manufacturing licence', '制造业执照'), 'intro' => B('', ''), 'mm2h' => '',
         'bullets' => B("Under the Industrial Co-ordination Act 1975, a manufacturing company needs a licence from MITI (through MIDA) once its shareholders' funds reach RM2.5 million or it employs 75 or more full-time workers.\nSmaller companies are exempt, but must apply once they pass either threshold.\nFactories also need local authority, environmental (DOE) and fire safety (Bomba) approvals, which we coordinate with the relevant Perak agencies.",
                        "根据《1975年工业协调法》，制造业公司的股东资金达到 RM250万，或全职员工达到75人或以上时，须通过 MIDA 向 MITI 申请制造业执照。\n规模较小的公司可获豁免，但一旦超过任一门槛便须申请。\n工厂还需取得地方政府、环境局（DOE）和消防局（Bomba）的批准，我们会与霹雳州相关机构协调。"),
         'ref' => B('Industrial Co-ordination Act 1975 · MIDA', '《1975年工业协调法》 · MIDA')],
        ['title' => B('Tax incentives', '税务优惠'), 'short' => B('Tax incentives', '税务优惠'), 'intro' => B('', ''), 'mm2h' => '',
         'bullets' => B("Malaysia's standard corporate tax rate is 24%. Qualifying projects can apply to MIDA for incentives such as Pioneer Status (income tax exemption) or Investment Tax Allowance.\nIncentives are approved project by project and depend on sector, value added, technology and location.\nThe Perak State Government and its investment agency offer facilitation for approvals and, in some parks, land-related support.",
                        "马来西亚标准企业所得税率为24%。符合条件的项目可向 MIDA 申请优惠，例如先锋地位（所得税豁免）或投资税务津贴。\n优惠按项目逐一审批，视行业、附加值、技术和地点而定。\n霹雳州政府及其投资机构提供审批协助，部分园区还提供土地相关支持。"),
         'ref' => B('MIDA · Income Tax Act 1967 · Promotion of Investments Act 1986', 'MIDA · 《1967年所得税法》 · 《1986年投资促进法》')],
        ['title' => B('Buying property in Perak', '在霹雳州购买房产'), 'short' => B('Buying property', '购买房产'), 'intro' => B('', ''), 'mm2h' => '',
         'bullets' => B("A foreigner needs written consent from the Perak State Authority to buy land or property (National Land Code, section 433B).\nPerak sets its own rules under Circular No. 12/2022 of the Director of Land and Mines. These cover minimum prices by local authority area, property types allowed, quotas, levy rates and special rates for MM2H participants.\nCurrent guides list a minimum of about **RM1,000,000** for foreign buyers in Perak, for both landed and strata homes. Check the exact figure for your area before signing.\nForeigners cannot buy low-cost or low–medium-cost housing, Malay Reserve land, or units allocated to Bumiputera buyers.\nReal Property Gains Tax for foreign sellers is 30% on disposals within five years and 10% from the sixth year.",
                        "外国人购买土地或房产须获得霹雳州政府的书面批准（《国家土地法典》第433B条）。\n霹雳州依据土地与矿务局局长2022年第12号通令制定本州规定，涵盖各地方政府区域的最低价格、允许的房产类别、配额、征费率，以及 MM2H 参与者的特别价格。\n现有指南列出霹雳州外国买家的最低价格约为 **RM1,000,000**，适用于有地及分层住宅。签约前请确认您所在地区的确切数额。\n外国人不得购买低价或中低价房屋、马来保留地，或分配给土著买家的单位。\n外国卖家的产业盈利税（RPGT）：五年内出售为30%，第六年起为10%。"),
         'ref' => B('National Land Code 1965 · Perak Land and Mines Office · RPGT Act 1976', '《1965年国家土地法典》 · 霹雳州土地与矿务局 · 《1976年产业盈利税法》')],
        ['title' => B('Expatriate staff', '外籍员工'), 'short' => B('Expatriate staff', '外籍员工'), 'intro' => B('', ''), 'mm2h' => '',
         'bullets' => B("Foreign managers and specialists work under an Employment Pass, applied for by the company through the Expatriate Services Division (ESD).\nPass categories depend on monthly salary and contract length. Category I and II holders may bring dependants.\nCompanies are expected to train Malaysians and plan for succession for key roles.",
                        "外籍管理人员和专才须持就业准证（Employment Pass）工作，由公司通过外籍人士服务署（ESD）申请。\n准证类别取决于月薪和合约期限，第一类和第二类持有人可携带家属。\n公司应培训本地员工，并为关键职位做好接班安排。"),
         'ref' => B('Immigration Department of Malaysia · ESD (MYXpats)', '马来西亚移民局 · ESD（MYXpats）')],
        ['title' => B('Malaysia My Second Home (MM2H)', '马来西亚第二家园计划（MM2H）'), 'short' => B('MM2H residence', 'MM2H 第二家园'), 'mm2h' => '1',
         'intro' => B('MM2H is a long-term residence programme for people who want to live in Malaysia. Under the revised programme, applicants must be at least 30.', 'MM2H 是为希望在马来西亚长期居住人士而设的居留计划。根据修订后的计划，申请人须年满30岁。'),
         'bullets' => B("Participants must spend at least 60 days a year in Malaysia.\nProperty bought under MM2H must also meet Perak's own MM2H price rates and state consent.", "参与者每年须在马来西亚居住至少60天。\n通过 MM2H 购买的房产也须符合霹雳州的 MM2H 价格规定并取得州政府批准。"),
         'ref' => B('Ministry of Tourism, Arts and Culture (MOTAC) · MM2H guidelines', '旅游、艺术及文化部（MOTAC） · MM2H 指南')],
        ['title' => B('Moving money in and out', '资金进出'), 'short' => B('Moving money', '资金进出'), 'intro' => B('', ''), 'mm2h' => '',
         'bullets' => B("Malaysia has liberal foreign exchange rules. Non-residents can bring in capital and send home profits, dividends, interest and sale proceeds in foreign currency.\nMalaysia has investment guarantee agreements and tax treaties with many countries, which protect investments and avoid double taxation.",
                        "马来西亚外汇政策宽松。非居民可汇入资本，并以外币汇出利润、股息、利息和出售所得。\n马来西亚与多国签有投资保障协定和避免双重征税协定，保障投资并避免重复课税。"),
         'ref' => B('Bank Negara Malaysia Foreign Exchange Policy · MITI', '马来西亚国家银行外汇政策 · MITI')],
    ],
    'mm2h' => [
        ['tier' => B('Silver', '银级'), 'deposit' => 'RM500,000', 'visa' => B('5 years', '5年'), 'property' => 'RM750,000'],
        ['tier' => B('Gold', '金级'), 'deposit' => 'RM2,000,000', 'visa' => B('15 years', '15年'), 'property' => 'RM750,000'],
        ['tier' => B('Platinum', '白金级'), 'deposit' => 'RM5,000,000', 'visa' => B('20 years; path to PR', '20年；可申请永久居留'), 'property' => 'RM1,500,000'],
    ],
    'gallery' => [
        ['image' => '', 'scene' => 'karst', 'category' => 'nature', 'title' => B('Kinta Valley limestone hills', '近打谷石灰岩山'), 'caption' => B('The karst hills around Ipoh at dawn.', '黎明时分怡保周边的喀斯特山峦。'), 'place' => B('Ipoh, Kinta', '近打 · 怡保')],
        ['image' => '', 'scene' => 'kerian', 'category' => 'industry', 'title' => B('Kerian industrial corridor', '吉辇工业走廊'), 'caption' => B('Factories and solar arrays in Perak\'s northern industrial zone, next to Penang and Kulim.', '霹雳北部工业区的工厂与太阳能板，毗邻槟城和居林。'), 'place' => B('Kerian', '吉辇')],
        ['image' => '', 'scene' => 'port', 'category' => 'industry', 'title' => B('Lumut Port', '红土坎港'), 'caption' => B('Container handling on the Strait of Malacca.', '马六甲海峡旁的集装箱作业。'), 'place' => B('Manjung', '曼绒')],
        ['image' => '', 'scene' => 'oldtown', 'category' => 'heritage', 'title' => B('Ipoh Old Town', '怡保旧街场'), 'caption' => B('Restored shophouses from the tin-mining boom, now cafés and boutiques.', '锡矿繁荣时期的修复店屋，如今成为咖啡馆和精品店。'), 'place' => B('Ipoh', '怡保')],
        ['image' => '', 'scene' => 'dredge', 'category' => 'heritage', 'title' => B('Tin dredge heritage', '锡矿铁船遗产'), 'caption' => B('A preserved tin dredge, a reminder of the Kinta Valley\'s mining history.', '保存完好的锡矿铁船，见证近打谷的采矿历史。'), 'place' => B('Tanjung Tualang', '端洛')],
        ['image' => '', 'scene' => 'pangkor', 'category' => 'nature', 'title' => B('Pangkor Island', '邦咯岛'), 'caption' => B('Beaches and fishing villages off the Manjung coast.', '曼绒海岸外的海滩与渔村。'), 'place' => B('Pangkor', '邦咯')],
        ['image' => '', 'scene' => 'belum', 'category' => 'nature', 'title' => B('Royal Belum rainforest', '皇家贝隆雨林'), 'caption' => B('Lake and ancient rainforest in Perak\'s far north.', '霹雳最北部的湖泊与古老雨林。'), 'place' => B('Gerik, Hulu Perak', '上霹雳 · 宜力')],
        ['image' => '', 'scene' => 'taiping', 'category' => 'heritage', 'title' => B('Taiping Lake Gardens', '太平湖滨公园'), 'caption' => B('Century-old rain trees around former tin-mining lakes.', '环绕旧锡矿湖的百年雨树。'), 'place' => B('Taiping', '太平')],
    ],
    'fact' => [
        ['label' => B('Company', '公司'), 'value' => B('Alphront Sdn. Bhd.', 'Alphront Sdn. Bhd.')],
        ['label' => B('Registration', '注册号'), 'value' => B('1370348-K', '1370348-K')],
        ['label' => B('Website', '网站'), 'value' => B('investperak.com', 'investperak.com')],
        ['label' => B('Office', '办公室'), 'value' => B("No. 27, Jalan Selamat Indah 1/1, Taman Selamat Indah 1, 32000 Sitiawan, Perak, Malaysia", "No. 27, Jalan Selamat Indah 1/1, Taman Selamat Indah 1, 32000 Sitiawan, Perak, Malaysia")],
        ['label' => B('Experience', '经验'), 'value' => B('More than 15 years in investment and property services', '逾15年投资与房地产服务经验')],
        ['label' => B('Main cities', '重点城市'), 'value' => B('Ipoh and Manjung', '怡保与曼绒')],
        ['label' => B('Partners', '合作伙伴'), 'value' => B('Optima Properties and Foo Leong Tan & Chan', 'Optima Properties 与 Foo Leong Tan & Chan')],
    ],
    'service' => [
        ['title' => B('Market entry advice', '市场进入咨询'), 'text' => B('Sector briefings, cost comparisons and feasibility input.', '行业简报、成本比较和可行性建议。')],
        ['title' => B('Site selection', '选址服务'), 'text' => B('Shortlists and visits to industrial parks, offices and land.', '工业园、办公室和土地的候选清单及实地考察。')],
        ['title' => B('Approvals support', '审批协助'), 'text' => B('Incorporation, licences, incentives, state consent and work passes.', '公司注册、执照、优惠、州政府批准和工作准证。')],
        ['title' => B('Property & relocation', '房产与移居'), 'text' => B('Home search, MM2H applications and settling-in help.', '寻找住所、MM2H 申请及安家协助。')],
    ],
    'partner' => [
        ['name' => 'Optima Properties', 'initial' => 'O', 'logo' => '', 'website' => '', 'address' => $OPT,
         'role' => B('Agency partner', '中介合作伙伴'), 'subtitle' => B('Valuer, Estate Agency & Property Management', '估价、房地产中介与物业管理'),
         'text' => B('Optima Properties checks property documents and the properties themselves, and handles the practical side of moving into Perak.', 'Optima Properties 负责核实房产文件及房产本身，并处理进驻霹雳州的实际事务。'),
         'bullets' => B("Industrial land, factory and office search\nResidential property for investors and MM2H applicants\nSite visits, negotiations and handover\nIntroductions to local suppliers and service providers", "寻找工业用地、厂房和办公室\n为投资者和 MM2H 申请人提供住宅房产\n实地考察、议价和交接\n介绍本地供应商和服务商")],
        ['name' => 'Foo Leong Tan & Chan', 'initial' => 'F', 'logo' => '', 'website' => '', 'address' => $FOO,
         'role' => B('Legal partner', '法律合作伙伴'), 'subtitle' => B('Advocates & Solicitors', '律师事务所（Advocates & Solicitors）'),
         'text' => B('Foo Leong Tan & Chan, based in Seri Manjung, gives formal legal advice and handles the documents.', '位于实兆远曼绒的 Foo Leong Tan & Chan 提供正式法律意见并处理相关文件。'),
         'bullets' => B("Company incorporation and corporate structuring\nLicences, permits and regulatory compliance\nSale and purchase agreements and state consent applications\nEmployment, immigration and contract matters", "公司注册与企业架构\n执照、准证与合规\n买卖合约及州政府批准申请\n雇佣、移民及合约事务")],
    ],
    'faq' => [
        ['q' => B('Can a foreigner own 100% of a company in Perak?', '外国人能否100%持有霹雳州的公司？'), 'a' => B('Yes, in most cases. Foreign investors can own 100% of manufacturing companies, and most services companies have no general equity limit. Some regulated sectors set their own limits, so check your activity with us first.', '大多数情况下可以。外国投资者可100%持有制造业公司，大部分服务业公司也没有一般性股权限制。部分受监管行业另有限制，请先与我们确认您的业务。')],
        ['q' => B('Can foreigners buy property in Perak?', '外国人可以在霹雳州购买房产吗？'), 'a' => B('Yes, with consent from the Perak State Authority. Perak sets minimum prices by area (currently about RM1 million in most guides) and excludes low-cost housing, Malay Reserve land and Bumiputera units.', '可以，但须获得霹雳州政府批准。霹雳州按地区设定最低价格（目前多数指南列为约 RM100万），并且不允许购买低价房屋、马来保留地和土著单位。')],
        ['q' => B('How long does it take to set up a company?', '设立公司需要多长时间？'), 'a' => B('Incorporating a Sdn Bhd with SSM can take a few days once documents are ready. Bank accounts, licences and work passes add several weeks, depending on your sector.', '文件齐备后，在 SSM 注册私人有限公司只需数天。银行开户、执照和工作准证还需数周，视行业而定。')],
        ['q' => B('Do I need a local partner?', '需要本地合伙人吗？'), 'a' => B('Not usually. You do need at least one director who ordinarily lives in Malaysia and a licensed company secretary. We can help arrange both.', '通常不需要。但您需要至少一名通常居住在马来西亚的董事和一名持牌公司秘书，我们可以协助安排。')],
        ['q' => B('Are there tax incentives in Perak?', '霹雳州有税务优惠吗？'), 'a' => B('Yes. Qualifying projects can apply to MIDA for federal incentives such as Pioneer Status or Investment Tax Allowance. Approval depends on the project.', '有。符合条件的项目可向 MIDA 申请联邦优惠，例如先锋地位或投资税务津贴，是否获批视项目而定。')],
        ['q' => B('Is investperak.com a government website?', 'investperak.com 是政府网站吗？'), 'a' => B('No. investperak.com is run by Alphront Sdn. Bhd., a private company. We are not the Perak State Government or its official investment agency, but we work with government bodies on your behalf.', '不是。investperak.com 由私人公司 Alphront Sdn. Bhd. 运营。我们并非霹雳州政府或其官方投资机构，但会代表您与政府部门对接。')],
        ['q' => B('What does your service cost?', '服务费用如何计算？'), 'a' => B('The first consultation is free. After that we quote a fixed fee or a scope-based fee in writing before any work starts.', '首次咨询免费。之后我们会在开工前以书面形式报出固定费用或按工作范围计算的费用。')],
    ],
    ];
}

/** Flatten seed item (bilingual pairs -> name_en / name_zh) */
function flatten_item(array $raw): array {
    $out = [];
    foreach ($raw as $k => $v) {
        if (is_array($v)) { $out[$k . '_en'] = $v['en']; $out[$k . '_zh'] = $v['zh']; }
        else $out[$k] = $v;
    }
    return $out;
}
