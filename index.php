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
?>
<!doctype html>
<html lang="<?= e($locale) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(tr($t, 'meta.title')) ?></title>
    <meta name="description" content="<?= e(tr($t, 'meta.description')) ?>">
    <meta name="theme-color" content="#1D1D1B">

    <link rel="icon" type="image/png" href="/assets/img/forcento-logo.png">
    <link rel="stylesheet" href="/assets/css/app.css">
    <script defer src="/assets/js/app.js"></script>
</head>
<body>
<header class="site-header" data-header>
    <div class="shell header-inner">
        <a class="brand" href="<?= e(locale_url($locale)) ?>" aria-label="FORCENTO ReNUTS">
            <img src="/assets/img/forcento-logo.png" alt="FORCENTO">
            <span class="brand-project">ReNUTS</span>
        </a>

        <nav class="desktop-nav" aria-label="Navigation">
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

            <button class="menu-button" type="button" aria-label="Menu" aria-expanded="false" data-menu-button>
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
            <a href="<?= e($contactHref) ?>"><?= e(tr($t, 'nav.contact')) ?></a>
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
                <div class="material-card material-card-main">
                    <div class="material-mark">
                        <span></span><span></span><span></span>
                    </div>
                    <p>PRESS CAKE</p>
                    <strong>→ ReNUTS</strong>
                </div>

                <div class="material-card material-card-small">
                    <span>01</span>
                    <p>RESOURCE</p>
                </div>

                <div class="material-card material-card-small material-card-bottom">
                    <span>02</span>
                    <p>NEW VALUE</p>
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
        </div>
    </section>

    <section class="section section-green">
        <div class="shell demo-grid">
            <div class="demo-visual reveal">
                <div class="demo-badge"><?= e(tr($t, 'demo.badge')) ?></div>
                <div class="bar-shape"></div>
                <div class="bar-dots"></div>
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
                <span class="fruit fruit-one">Q</span>
                <span class="fruit fruit-two">P</span>
                <span class="fruit fruit-three">A</span>
                <div class="local-arrow">→</div>
                <strong>LOCAL<br>VALUE</strong>
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
                <?php foreach (tr($t, 'status.items', []) as $index => $item): ?>
                    <li>
                        <span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <p><?= e($item) ?></p>
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

            <div class="partner-tags reveal">
                <?php foreach (tr($t, 'partners.types', []) as $partner): ?>
                    <span><?= e($partner) ?></span>
                <?php endforeach; ?>
            </div>

            <a class="button button-dark reveal" href="<?= e($contactHref) ?>">
                <?= e(tr($t, 'partners.cta')) ?>
            </a>
        </div>
    </section>

    <section class="section about-section" id="about">
        <div class="shell about-grid">
            <div class="reveal">
                <img class="about-logo" src="/assets/img/forcento-logo.png" alt="FORCENTO">
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
</body>
</html>
