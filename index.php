<?php

$config = require __DIR__.'/config/site.php';

$uriPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

$segments = $uriPath === '' ? [] : explode('/', $uriPath);

$requestedLocale = $segments[0] ?? '';

if (in_array($requestedLocale, $config['locales'], true)) {

    $locale = $requestedLocale;

} else {

    $browser = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2));

    $locale = in_array($browser, $config['locales'], true)

        ? $browser

        : $config['default_locale'];

    if ($uriPath === '') {

        header('Location: /'.$locale.'/', true, 302);

        exit;

    }

}

$t = require __DIR__.'/lang/'.$locale.'.php';

function e(?string $value): string

{

    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');

}

function tr(array $data, string $path, mixed $fallback = ''): mixed

{

    $value = $data;

    foreach (explode('.', $path) as $part) {

        if (! is_array($value) || ! array_key_exists($part, $value)) {

            return $fallback;

        }

        $value = $value[$part];

    }

    return $value;

}

function locale_url(string $locale, string $anchor = ''): string

{

    return '/'.$locale.'/'.($anchor !== '' ? '#'.$anchor : '');

}

$contactEmail = trim((string) ($config['contact_email'] ?? ''));

$contactHref = $contactEmail !== ''

    ? 'mailto:'.$contactEmail.'?subject='.rawurlencode('FORCENTO ReNUTS')

    : '#contact';

$assetVersion = static function (string $path): string
{
    $fullPath = __DIR__.'/'.ltrim($path, '/');

    return is_file($fullPath)
        ? (string) filemtime($fullPath)
        : '1';
};


?>

<!doctype html>

<html lang="<?= e($locale) ?>">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e(tr($t, 'meta.title')) ?></title>

    <meta name="description" content="<?= e(tr($t, 'meta.description')) ?>">

    <meta name="theme-color" content="#1D1D1B">

    <link rel="icon" type="image/png" href="/assets/img/favicon.png?v=<?= e($assetVersion('assets/img/favicon.png')) ?>">

    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png?v=<?= e($assetVersion('assets/img/favicon.png')) ?>">

    <link rel="apple-touch-icon" href="/assets/img/favicon.png?v=<?= e($assetVersion('assets/img/favicon.png')) ?>">

    <link rel="stylesheet" href="/assets/css/app.css?v=<?= e($assetVersion('assets/css/app.css')) ?>">

    <script defer src="/assets/js/app.js?v=<?= e($assetVersion('assets/js/app.js')) ?>"></script>

    <style>
        /*
         * Critical header logo rules.
         * These live inline on purpose so production cannot show both logos
         * even if a stale external stylesheet is served from cache.
         */
        .brand-logo-stack {
            width: 88px;
            height: 48px;
            position: relative;
            flex: 0 0 88px;
            display: block;
        }

        .brand-logo-stack .brand-logo {
            width: 88px;
            height: 48px;
            position: absolute;
            inset: 0;
            display: block;
            object-fit: contain;
            transition: opacity 180ms ease;
        }

        .brand-logo-dark-bg {
            opacity: 1 !important;
        }

        .brand-logo-light-bg {
            opacity: 0 !important;
        }

        .site-header.scrolled .brand-logo-dark-bg {
            opacity: 0 !important;
        }

        .site-header.scrolled .brand-logo-light-bg {
            opacity: 1 !important;
        }

        @media (max-width: 680px) {
            .brand-logo-stack {
                width: 72px;
                height: 38px;
                flex-basis: 72px;
            }

            .brand-logo-stack .brand-logo {
                width: 72px;
                height: 38px;
            }
        }
    </style>

    </head>

<body>

<header class="site-header" data-header>

    <div class="shell header-inner">

        <a class="brand" href="<?= e(locale_url($locale)) ?>" aria-label="FORCENTO ReNUTS">
            <span class="brand-logo-stack" aria-hidden="true">
                <img class="brand-logo brand-logo-dark-bg" src="/assets/img/forcento-logo2.png?v=<?= e($assetVersion('assets/img/forcento-logo2.png')) ?>" alt="">
                <img class="brand-logo brand-logo-light-bg" src="/assets/img/forcento-logo.png?v=<?= e($assetVersion('assets/img/forcento-logo.png')) ?>" alt="">
            </span>
            <span class="brand-project">ReNUTS</span>
        </a>

        <nav class="desktop-nav" aria-label="<?= e(tr($t, 'nav.navigation')) ?>">

            <a href="#project"><?= e(tr($t, 'nav.project')) ?></a>

            <a href="#science"><?= e(tr($t, 'nav.science')) ?></a>

            <a href="#chain"><?= e(tr($t, 'nav.chain')) ?></a>

            <a href="#status"><?= e(tr($t, 'nav.status')) ?></a>

            <a href="#partners"><?= e(tr($t, 'nav.partners')) ?></a>

        </nav>

        <div class="header-actions">

            <div class="language-switcher">

                <?php foreach ($config['locales'] as $availableLocale): ?>

                    <a

                        href="<?= e(locale_url($availableLocale)) ?>"

                        class="<?= $availableLocale === $locale ? 'active' : '' ?>"

                        hreflang="<?= e($availableLocale) ?>"

                    ><?= strtoupper(e($availableLocale)) ?></a>

                <?php endforeach; ?>

            </div>

            <a class="button button-small button-dark desktop-cta" href="<?= e($contactHref) ?>">

                <?= e(tr($t, 'nav.contact')) ?>

            </a>

            <button class="menu-button" type="button" aria-label="<?= e(tr($t, 'nav.menu')) ?>" aria-expanded="false" data-menu-button>

                <span></span><span></span>

            </button>

        </div>

    </div>

    <div class="mobile-menu" data-mobile-menu>

        <div class="shell">

            <a href="#project"><?= e(tr($t, 'nav.project')) ?></a>

            <a href="#science"><?= e(tr($t, 'nav.science')) ?></a>

            <a href="#chain"><?= e(tr($t, 'nav.chain')) ?></a>

            <a href="#status"><?= e(tr($t, 'nav.status')) ?></a>

            <a href="#partners"><?= e(tr($t, 'nav.partners')) ?></a>

            <a class="mobile-contact-link" href="<?= e($contactHref) ?>"><?= e(tr($t, 'nav.contact')) ?></a>

            <div class="mobile-language-switcher" aria-label="<?= e(tr($t, 'nav.language')) ?>">
                <?php foreach ($config['locales'] as $availableLocale): ?>
                    <a
                        href="<?= e(locale_url($availableLocale)) ?>"
                        class="<?= $availableLocale === $locale ? 'active' : '' ?>"
                        hreflang="<?= e($availableLocale) ?>"
                    ><?= strtoupper(e($availableLocale)) ?></a>
                <?php endforeach; ?>
            </div>

        </div>

    </div>

</header>

<main>

    <section class="hero" id="project">

        <div class="hero-orb hero-orb-one"></div>

        <div class="hero-orb hero-orb-two"></div>

        <div class="shell hero-grid">

            <div class="hero-copy reveal">

                <div class="eyebrow light"><?= e(tr($t, 'hero.eyebrow')) ?></div>

                <h1><?= e(tr($t, 'hero.title')) ?></h1>

                <p class="hero-lead"><?= e(tr($t, 'hero.text')) ?></p>

                <div class="hero-actions">

                    <a class="button button-green" href="#resource">

                        <?= e(tr($t, 'hero.primary')) ?>

                    </a>

                    <a class="button button-ghost-light" href="#partners">

                        <?= e(tr($t, 'hero.secondary')) ?>

                    </a>

                </div>

                <div class="hero-status">

                    <span class="status-dot"></span>

                    <?= e(tr($t, 'hero.status')) ?>

                </div>

            </div>

            <div class="hero-visual reveal" aria-hidden="true">

                <div class="renuts-process">

                    <div class="renuts-process-track"></div>

                    <div class="renuts-step">

                        <div class="renuts-icon">

                            <svg viewBox="0 0 120 120" aria-hidden="true">

                                <path d="M60 21C44 21 32 34 32 51c0 22 11 43 28 48 17-5 28-26 28-48 0-17-12-30-28-30Z"/>

                                <path d="M46 28c3 8 8 13 14 17"/>

                                <path d="M74 28c-3 8-8 13-14 17"/>

                                <path d="M60 44c-8 13-12 26-10 42"/>

                                <path d="M60 44c8 13 12 26 10 42"/>

                            </svg>

                        </div>

                        <div class="renuts-step-copy">

                            <span>01</span>

                            <strong><?= e(tr($t, 'hero_process.nut')) ?></strong>

                        </div>

                    </div>

                    <div class="renuts-arrow">

                        <svg viewBox="0 0 52 20" aria-hidden="true">

                            <path d="M2 10h43"/>

                            <path d="m37 3 8 7-8 7"/>

                        </svg>

                    </div>

                    <div class="renuts-step">

                        <div class="renuts-icon">

                            <svg viewBox="0 0 120 120" aria-hidden="true">

                                <rect x="34" y="24" width="52" height="15" rx="4"/>

                                <path d="M45 39v22h30V39"/>

                                <path d="M60 61v14"/>

                                <path d="M38 80c8-6 16-6 22 0 7-6 15-6 22 0"/>

                                <path d="M33 91c10-7 20-7 27 0 8-7 18-7 27 0"/>

                            </svg>

                        </div>

                        <div class="renuts-step-copy">

                            <span>02</span>

                            <strong><?= e(tr($t, 'hero_process.pressing')) ?></strong>

                        </div>

                    </div>

                    <div class="renuts-arrow">

                        <svg viewBox="0 0 52 20" aria-hidden="true">

                            <path d="M2 10h43"/>

                            <path d="m37 3 8 7-8 7"/>

                        </svg>

                    </div>

                    <div class="renuts-step">

                        <div class="renuts-icon">

                            <svg viewBox="0 0 120 120" aria-hidden="true">

                                <path d="M29 69h62c-2 18-13 29-31 29S31 87 29 69Z"/>

                                <path d="M38 68c5-14 13-21 22-21 10 0 18 7 23 21"/>

                                <circle cx="49" cy="57" r="3"/>

                                <circle cx="60" cy="51" r="3"/>

                                <circle cx="71" cy="58" r="3"/>

                                <circle cx="55" cy="63" r="2.4"/>

                            </svg>

                        </div>

                        <div class="renuts-step-copy">

                            <span>03</span>

                            <strong><?= e(tr($t, 'hero_process.powder')) ?></strong>

                        </div>

                    </div>

                    <div class="renuts-arrow">

                        <svg viewBox="0 0 52 20" aria-hidden="true">

                            <path d="M2 10h43"/>

                            <path d="m37 3 8 7-8 7"/>

                        </svg>

                    </div>

                    <div class="renuts-step">

                        <div class="renuts-icon renuts-icon-final">

                            <svg viewBox="0 0 120 120" aria-hidden="true">

                                <path d="M19 59h42c-1 18-9 29-21 29S20 77 19 59Z"/>

                                <path d="M25 57c4-10 9-15 15-15s12 5 15 15"/>

                                <circle cx="32" cy="50" r="2.5"/>

                                <circle cx="41" cy="47" r="2.5"/>

                                <circle cx="49" cy="52" r="2.5"/>

                                <rect x="68" y="41" width="32" height="47" rx="8" transform="rotate(8 84 65)"/>

                                <circle cx="79" cy="56" r="2.3"/>

                                <circle cx="88" cy="64" r="2.3"/>

                                <circle cx="79" cy="73" r="2.3"/>

                            </svg>

                        </div>

                        <div class="renuts-step-copy">

                            <span>04</span>

                            <strong><?= e(tr($t, 'hero_process.products')) ?></strong>

                        </div>

                    </div>

                </div>

                <div class="renuts-process-caption">

                    <span><?= e(tr($t, 'hero_process.resource')) ?></span>

                    <span class="renuts-caption-line"></span>

                    <span><?= e(tr($t, 'hero_process.value')) ?></span>

                </div>

            </div>

        </div>

    </section>

    <section class="section section-paper" id="resource">

        <div class="shell split">

            <div class="section-heading reveal">

                <div class="eyebrow"><?= e(tr($t, 'resource.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'resource.title')) ?></h2>

            </div>

            <div class="prose-large reveal">

                <p><?= e(tr($t, 'resource.text1')) ?></p>

                <p><?= e(tr($t, 'resource.text2')) ?></p>

                <div class="science-note">

                    <span>i</span>

                    <p><?= e(tr($t, 'resource.note')) ?></p>

                </div>

            </div>

        </div>

    </section>

    <section class="section section-white">

        <div class="shell opportunity-grid">

            <div class="opportunity-number reveal">01</div>

            <div class="reveal">

                <div class="eyebrow"><?= e(tr($t, 'opportunity.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'opportunity.title')) ?></h2>

                <p class="section-lead"><?= e(tr($t, 'opportunity.text')) ?></p>

            </div>

        </div>

    </section>

    <section class="section process-section">

        <div class="shell">

            <div class="section-heading narrow reveal">

                <div class="eyebrow light"><?= e(tr($t, 'process.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'process.title')) ?></h2>

            </div>

            <div class="process-grid">

                <?php foreach (tr($t, 'process.steps', []) as $index => $step): ?>

                    <article class="process-step reveal">

                        <div class="process-index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></div>

                        <div class="process-line"></div>

                        <h3><?= e($step[0]) ?></h3>

                        <p><?= e($step[1]) ?></p>

                    </article>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <section class="section section-paper" id="science">

        <div class="shell">

            <div class="section-heading science-heading reveal">

                <div>

                    <div class="eyebrow"><?= e(tr($t, 'science.eyebrow')) ?></div>

                    <h2><?= e(tr($t, 'science.title')) ?></h2>

                </div>

                <p><?= e(tr($t, 'science.intro')) ?></p>

            </div>

            <div class="science-grid">

                <?php foreach (tr($t, 'science.cards', []) as $index => $card): ?>

                    <article class="science-card reveal">

                        <span class="card-number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>

                        <h3><?= e($card[0]) ?></h3>

                        <p><?= e($card[1]) ?></p>

                    </article>

                <?php endforeach; ?>

            </div>

            <div class="science-note compact reveal">
                <span>i</span>
                <p><?= e(tr($t, 'science.note')) ?></p>
            </div>

        </div>

    </section>

    <section class="section section-green">

        <div class="shell demo-grid">

            <div class="demo-visual reveal">
                <div class="demo-badge"><?= e(tr($t, 'demo.badge')) ?></div>

                <div class="poc-header-style" aria-hidden="true">
                    <div class="poc-header-step">
                        <div class="renuts-icon poc-header-icon">
                            <svg viewBox="0 0 120 120" aria-hidden="true">
                                <rect x="24" y="40" width="72" height="40" rx="12"/>
                                <path d="M34 48h52"/>
                                <path d="M34 72h52"/>
                                <circle cx="42" cy="59" r="3"/>
                                <circle cx="55" cy="53" r="2.7"/>
                                <circle cx="67" cy="64" r="3"/>
                                <circle cx="80" cy="56" r="2.7"/>
                            </svg>
                        </div>
                    </div>

                    <div class="renuts-arrow poc-header-arrow">
                        <svg viewBox="0 0 52 20" aria-hidden="true">
                            <path d="M2 10h43"/>
                            <path d="m37 3 8 7-8 7"/>
                        </svg>
                    </div>

                    <div class="poc-header-step">
                        <div class="renuts-icon poc-header-icon">
                            <svg viewBox="0 0 120 120" aria-hidden="true">
                                <path d="M28 59h64c-2 22-14 35-32 35S30 81 28 59Z"/>
                                <path d="M36 58c5-13 13-20 24-20s19 7 24 20"/>
                                <circle cx="45" cy="50" r="3"/>
                                <circle cx="56" cy="45" r="3"/>
                                <circle cx="68" cy="49" r="3"/>
                                <circle cx="78" cy="54" r="2.7"/>
                                <path d="M42 39c3-7 8-11 14-13"/>
                                <path d="M70 37c4-5 8-7 13-8"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="reveal">

                <div class="eyebrow dark"><?= e(tr($t, 'demo.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'demo.title')) ?></h2>

                <p class="section-lead"><?= e(tr($t, 'demo.text')) ?></p>

                <p><?= e(tr($t, 'demo.more')) ?></p>

            </div>

        </div>

    </section>

    <section class="section section-white" id="chain">

        <div class="shell">

            <div class="section-heading wide reveal">

                <div class="eyebrow"><?= e(tr($t, 'chain.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'chain.title')) ?></h2>

                <p><?= e(tr($t, 'chain.intro')) ?></p>

            </div>

            <div class="actors">

                <?php foreach (tr($t, 'chain.actors', []) as $index => $actor): ?>

                    <article class="actor-row reveal">

                        <div class="actor-index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></div>

                        <h3><?= e($actor[0]) ?></h3>

                        <p><?= e($actor[1]) ?></p>

                    </article>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <section class="section local-section">

        <div class="shell local-grid">

            <div class="local-graphic reveal" aria-hidden="true">

                <span class="fruit fruit-one"></span>

                <span class="fruit fruit-two"></span>

                <span class="fruit fruit-three"></span>

                <div class="local-arrow">→</div>

                <strong><?= e(tr($t, 'local.graphic')) ?></strong>

            </div>

            <div class="reveal">

                <div class="eyebrow"><?= e(tr($t, 'local.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'local.title')) ?></h2>

                <p class="section-lead"><?= e(tr($t, 'local.text')) ?></p>

                <div class="science-note compact">

                    <span>i</span>

                    <p><?= e(tr($t, 'local.note')) ?></p>

                </div>

            </div>

        </div>

    </section>

    <section class="section status-section" id="status">

        <div class="shell status-grid">

            <div class="section-heading reveal">

                <div class="eyebrow light"><?= e(tr($t, 'status.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'status.title')) ?></h2>

            </div>

            <ol class="status-list reveal">

                <?php foreach (tr($t, 'status.phases', []) as $index => $phase): ?>

                    <li class="status-phase">

                        <span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>

                        <div class="status-phase-copy">
                            <div class="status-phase-label"><?= e($phase['label'] ?? '') ?></div>
                            <h3><?= e($phase['title'] ?? '') ?></h3>

                            <ul>
                                <?php foreach (($phase['items'] ?? []) as $item): ?>
                                    <li><?= e($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                    </li>

                <?php endforeach; ?>

            </ol>

        </div>

    </section>

    <section class="section partners-section" id="partners">

        <div class="shell">

            <div class="partners-copy reveal">

                <div class="eyebrow"><?= e(tr($t, 'partners.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'partners.title')) ?></h2>

                <p class="section-lead"><?= e(tr($t, 'partners.text')) ?></p>

            </div>

            <div class="partner-axes">

                <?php foreach (tr($t, 'partners.axes', []) as $index => $axis): ?>

                    <article class="partner-axis reveal">
                        <span class="partner-axis-number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <div class="partner-axis-kicker"><?= e($axis['kicker'] ?? '') ?></div>
                        <h3><?= e($axis['title'] ?? '') ?></h3>
                        <p><?= e($axis['text'] ?? '') ?></p>
                        <a href="<?= e($contactHref) ?>"><?= e($axis['cta'] ?? tr($t, 'partners.cta')) ?> <span aria-hidden="true">→</span></a>
                    </article>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <section class="section about-section" id="about">

        <div class="shell about-grid">

            <div class="reveal">

                <img class="about-logo" src="/assets/img/forcento-logo.png?v=<?= e($assetVersion('assets/img/forcento-logo.png')) ?>" alt="FORCENTO">

            </div>

            <div class="reveal">

                <div class="eyebrow"><?= e(tr($t, 'about.eyebrow')) ?></div>

                <h2><?= e(tr($t, 'about.title')) ?></h2>

                <p class="section-lead"><?= e(tr($t, 'about.text')) ?></p>

                <div class="founders">

                    <span><?= e(tr($t, 'about.founders')) ?></span>

                    <strong><?= e(implode(' · ', $config['founders'])) ?></strong>

                </div>

            </div>

        </div>

    </section>

    <section class="section faq-section" id="faq">

        <div class="shell faq-grid">

            <div class="section-heading reveal">
                <div class="eyebrow"><?= e(tr($t, 'faq.eyebrow')) ?></div>
                <h2><?= e(tr($t, 'faq.title')) ?></h2>
            </div>

            <div class="faq-list reveal">
                <?php foreach (tr($t, 'faq.items', []) as $item): ?>
                    <details class="faq-item">
                        <summary>
                            <span><?= e($item['question'] ?? '') ?></span>
                            <span class="faq-plus" aria-hidden="true">+</span>
                        </summary>
                        <div class="faq-answer">
                            <p><?= e($item['answer'] ?? '') ?></p>
                        </div>
                    </details>
                <?php endforeach; ?>
            </div>

        </div>

    </section>

    <section class="final-cta" id="contact">

        <div class="shell final-grid reveal">

            <h2><?= e(tr($t, 'final.title')) ?></h2>

            <div class="final-side">

                <a class="button button-green" href="<?= e($contactHref) ?>">

                    <?= e(tr($t, 'final.cta')) ?>

                </a>

                <p>

                    <?= e($config['company']) ?><br>

                    <?= e($config['address']) ?>

                    <?php if ($contactEmail !== ''): ?>

                        <br><a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>

                    <?php endif; ?>

                </p>

            </div>

        </div>

    </section>

</main>

<footer class="footer">

    <div class="shell footer-inner">

        <p>© <?= date('Y') ?> <?= e($config['company']) ?> · <?= e(tr($t, 'footer.rights')) ?></p>

        <p><?= e(tr($t, 'footer.legal')) ?></p>

    </div>

</footer>


<script>
(() => {
    const header = document.querySelector('[data-header]');

    if (!header) {
        return;
    }

    const syncHeaderState = () => {
        header.classList.toggle('scrolled', window.scrollY > 20);
    };

    syncHeaderState();
    window.addEventListener('scroll', syncHeaderState, { passive: true });
})();
</script>

</body>

</html>
