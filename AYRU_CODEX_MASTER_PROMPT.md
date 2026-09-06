# AYRU --- MASTER BUILD SPEC / CODEX ULTIMATE PROMPT

Version: 1.0 --- Source of truth for implementation

## 0. ROLE AND OPERATING MODE

You are the senior product engineer, senior WordPress/WooCommerce
developer, frontend engineer, UX engineer, CRO specialist, SEO engineer,
accessibility specialist and QA engineer responsible for implementing
AYRU.

This folder contains: - this specification; - AYRU brand assets; -
approved UI references/mockups; - NEB mascot assets and poses; -
decorative assets, backgrounds and hero visuals; - product/category
imagery when available.

Treat the supplied files and approved mockups as the visual source of
truth. Inspect the entire asset folder before coding. Build an asset
inventory and map each file to its intended role. Do not replace a
supplied visual with a generated substitute.

### Absolute rule: do NOT redesign AYRU

Do not make a generic AI-generated ecommerce website. Do not "improve"
the art direction by inventing gradients, glassmorphism, excessive
cards, random blobs, generic SaaS layouts, arbitrary shadows, new
mascots, stock illustrations, fake reviews, fake metrics, fake urgency,
fake discounts, fake delivery claims, fake trust badges or decorative
elements that are not supported by this specification/assets.

If an exact visual asset exists, use it. If a required asset is missing,
create a clearly named placeholder/component slot and report it; do NOT
invent a final illustration.

The goal is not "a website inspired by the mockups." The goal is to
reproduce the approved AYRU design system and UX faithfully, while
making it responsive, accessible, performant, SEO-ready and
maintainable.

------------------------------------------------------------------------

# 1. PRODUCT / BRAND CONTEXT

AYRU is an ecommerce universe primarily aimed at girls, young women,
future mothers and young mothers. The commercial core is
baby/child/chambre/cadeaux products, with a separate "Tendances" layer
for cute/viral products and collectibles.

The experience must feel: - soft; - cute but not childish; - warm; -
reassuring; - modern; - premium enough to inspire trust; -
social-first/mobile-first; - editorial rather than "dropshipping
template".

AYRU must never visually look like AliExpress, a generic Shopify theme,
a cheap dropshipping store or a generic AI landing page.

Primary navigation: - Bébé - Chambre & Déco - Peluches - Cadeaux -
Tendances - Guides & Conseils

Brand idea already used in approved UI: "L'univers de votre bébé
commence ici."

Other approved editorial direction: "Plus qu'une boutique, un monde pour
grandir."

Do not hardcode unverified commercial claims.

------------------------------------------------------------------------

# 2. VISUAL IDENTITY --- NON-NEGOTIABLE

## Palette

Core purple: #7C5CFC. Supporting family may include: - lavender #A78BFA
/ #C4B5FD; - pale lavender #EDE9FE; - warm cream/off-white #FFF7E6 /
#F7F5F2; - dark ink #18143D / #1E1E1E; - muted gray #6B7280.

Use the supplied assets/mockups to resolve exact visual balance. Do not
flood every surface with purple. Most ecommerce/product surfaces remain
light/cream/white so products can breathe.

## Signature

AYRU uses a four-point sparkle ✦ as a recurring signature. Never
substitute a five-point star when the AYRU four-point sparkle is
intended.

## Mascot: NEB

NEB is AYRU's original cream cloud-like mascot: - rounded organic cloud
body; - cream/off-white; - small rounded limbs; - recognizable purple
four-point sparkle; - cute simple face; - in approved site
hero/editorial illustrations, prefer the supplied 2D drawn NEB assets; -
do not transform NEB into an animal; - do not invent a different
mascot; - preserve the exact supplied NEB design.

NEB is an accent/guide, not wallpaper. Use it strategically in
hero/editorial/empty states/support/list moments. Product merchandising
must remain the focus.

## Typography

Use the typography indicated by supplied references/assets. Previous
brand direction uses rounded display typography with clean modern body
typography. Preserve the approved visual hierarchy. Do not introduce
arbitrary typefaces.

------------------------------------------------------------------------

# 3. RESPONSIVE PHILOSOPHY

This is a responsive website, NOT a native mobile app.

Mobile is strategically important because much traffic can arrive from
TikTok/Instagram. Desktop is not merely an enlarged mobile page.

Breakpoints must be deliberate. Components should adapt structurally: -
mobile: thumb-friendly, concise, stacked, swipeable where appropriate; -
desktop: horizontal composition, richer whitespace, product rows/grids,
persistent navigation; - tablet: intentional intermediate behavior.

Do not distort or crop key supplied visuals. Use
object-fit/object-position deliberately.

------------------------------------------------------------------------

# 4. APPROVED HOME PAGE DIRECTION

The supplied approved desktop and mobile Home mockups are the visual
source of truth.

## Header / announcement

Desktop: - thin configurable announcement bar; - wide rounded white
navigation container; - AYRU logo + four-point sparkle at left; -
category navigation; - long rounded search field; -
account/favorites/cart icons; - cart quantity badge when non-zero.

Mobile: - compact announcement; - menu; - centered/clear AYRU
branding; - search and cart; - no desktop nav squeezed into mobile.

Announcement text must be editable in WordPress, not hardcoded.

## Hero

Desktop: - large horizontal editorial hero; - copy on left; - approved
soft baby-bedroom/couette visual with 2D NEB on right; - retain the
approved "right-side" visual effect/composition; - primary CTA
"Découvrir la boutique"; - secondary CTA "Explorer nos univers"; -
supporting trust/value items only if claims are verified.

Mobile: - same brand story but recomposed vertically; - copy remains
immediately readable; - NEB/couette visual becomes a deliberate
lower/right composition, not a random crop.

No fake "+10 000 parents", fake shipping promises or fake claims.

## Category shortcuts

Icon/visual shortcuts for: Bébé / Chambre & Déco / Peluches / Cadeaux /
Tendances / Guides & Conseils. They must be clickable, accessible and
data-driven.

## Product sections

Home includes merchandising blocks such as: - Nouveautés; - Tendances du
moment; - Idées cadeaux; - category/universe discovery.

Product cards must be WooCommerce-driven, never static duplicated
markup.

Card requirements: - product image; - title; - price; - sale price when
genuinely configured; - favorite control; - badges only from real
product state (nouveau, promo, rupture, etc.); - variation/status
handling; - accessible focus states; - skeleton/loading state.

## "Nos univers"

Editorial/category tiles with actual imagery. Do not make every tile
look identical.

## "Ma Liste AYRU / Préparez l'arrivée de bébé"

Important feature block: - editorial illustration/checklist; - CTA
"Créer ma liste"; - explanation link; - warm, useful tone; - not a
generic wishlist banner.

## Reassurance

Only verifiable facts: - payment security; - delivery/tracking; -
returns; - support. Copy must come from actual configured policies. No
invented guarantees.

## Newsletter/community

Email capture with consent-aware implementation. No fake community size.

## Footer

Include useful navigation: Boutique / Guides & Conseils / Aide / À
propos / Légal / social links / country-currency if truly supported.

------------------------------------------------------------------------

# 5. COMPLETE INFORMATION ARCHITECTURE

Implement the following experiences even when there is no dedicated
mockup. Derive them from the approved AYRU design system rather than
inventing a new visual language.

Core: - Home - Shop / Collections - Category pages - Search
overlay/page - Search results - Product detail - Favorites - Mini-cart -
Cart - Checkout - Payment error/retry - Order confirmation - Account -
Login/register/password reset - Orders - Order detail - Order tracking -
Ma Liste AYRU - Shared list - Guides & Conseils hub - SEO/editorial
guides - FAQ - Contact - Shipping & returns - About / Notre histoire -
commitments if real - legal notices - CGV - privacy policy - cookies -
recruitment/press only if real content exists - 404

States: - empty cart; - empty favorites; - empty orders; - empty birth
list; - no search result; - unavailable product; - unavailable
variation; - out of stock; - loading/skeleton; - network/error; -
payment failed; - form validation; - success; - coupon valid/invalid; -
account unauthenticated; - shared-list unavailable/private if
applicable.

Never leave default unstyled WooCommerce screens.

------------------------------------------------------------------------

# 6. CATEGORY / COLLECTION UX

Category pages must combine commerce + SEO without becoming text walls.

Structure: 1. breadcrumb; 2. compact category hero using supplied
category asset where applicable; 3. H1 and concise useful intro; 4.
filters/sort; 5. product count; 6. product grid; 7. pagination or
SEO-safe load-more strategy; 8. useful editorial content below products;
9. internal links to related categories/guides; 10. FAQ when genuinely
relevant.

Filters should support relevant WooCommerce attributes. Mobile filters
use a drawer/bottom-sheet style interaction; desktop may use sidebar/top
controls depending approved reference.

Never index useless combinations of filter parameters.

------------------------------------------------------------------------

# 7. PRODUCT DETAIL PAGE

Product page is conversion-critical.

Above the fold: - breadcrumb; - gallery; - title; - rating only if real
reviews exist; - price / genuine sale state; - variations; - stock
state; - quantity; - add-to-cart; - favorite; - concise delivery/return
information based on configured policy.

Then: - description; - product details; - dimensions/materials/care when
available; - safety/age/warnings where relevant; -
manufacturer/responsible-person/compliance information where required
and available; - delivery; - returns; - FAQ; - related/recommended
products; - recently viewed if implemented.

Mobile: - gallery optimized for touch; - clear variation selectors; -
sticky add-to-cart may be used if it does not obstruct content; - no
aggressive popups.

Never fabricate CE, GPSR, safety, reviews, origin or delivery data.

------------------------------------------------------------------------

# 8. MINI-CART / CART

Mini-cart: - opens quickly without a full navigation break; - product
thumbnail, name, variant, qty, price; - remove/edit; - subtotal; -
checkout CTA; - continue shopping; - empty state with supplied
NEB/cart/cloud asset if available.

Cart page: - editable quantities; - remove; - coupon; - totals; -
delivery calculation/info; - checkout CTA; - useful cross-sells,
restrained; - empty cart state designed in AYRU style.

No fake countdowns or "X people are viewing this".

------------------------------------------------------------------------

# 9. CHECKOUT

Checkout must prioritize trust and completion, not decoration.

Use WooCommerce checkout/payment architecture rather than building an
unsafe homemade card-processing system.

Visual rules: - simplified AYRU header; - progress/context without
unnecessary steps; - clear contact, delivery, billing and payment
sections; - order summary; - coupon access without dominating; - errors
inline and human-readable; - mobile keyboard/input optimization; -
preserve entered data after validation errors; - no NEB overload; one
subtle reassurance use at most.

Payment provider integration (Stripe or another selected gateway) must
use official WooCommerce-compatible integration and tokenized/hosted
payment fields where appropriate. Never store raw card details.

------------------------------------------------------------------------

# 10. CONFIRMATION / ORDERS / TRACKING

Confirmation: - clear success state; - order number; - summary; - next
steps; - email expectation; - tracking explanation; - CTA to
account/orders or continue shopping; - NEB "order success" asset where
supplied.

Orders/account: - dashboard is clean and useful, not default
WordPress-looking; - order list; - statuses; - order detail; -
addresses/profile/password controls; - logout.

Tracking: - timeline/status representation based on real order/tracking
data; - no fabricated location; - use NEB tracking/parcel asset if
supplied.

------------------------------------------------------------------------

# 11. SEARCH

Search is product-oriented and editorial-aware.

Desktop: - search field in header. Mobile: - dedicated overlay/page.

Search should be able to surface: - products; - categories; - useful
guides.

Provide: - suggestions; - recent/popular suggestions only if real
logic/data exists; - no-results state with supplied magnifier/AYRU
visual; - typo tolerance only if supported by chosen search
implementation.

Search pages that provide no unique value should not pollute SEO
indexing.

------------------------------------------------------------------------

# 12. "MA LISTE AYRU" --- BIRTH LIST FEATURE

This is NOT the cart. It is a persistent/shareable birth list / wishlist
experience.

Purpose: A future parent creates a list, saves desired products, and can
share the list with relatives/friends.

Core flow: Guide/SEO page → "Créer ma liste" → authenticate/create
account if necessary → create/nickname list → add products → manage
quantities/preferences → share → visitor opens shared list → sees
available items → purchases through normal WooCommerce checkout.

Design: - use approved checklist/NEB visual; - warm editorial intro; -
list completion/progress only if based on meaningful data; - clear
add/remove; - clear share action; - privacy setting; - avoid confusing
"reserved" with "purchased".

IMPORTANT: do not mark an item permanently reserved merely because
somebody added it to a cart. Reservation/purchase status must have
explicit business logic to avoid blocking items because of abandoned
carts. Implement the safest simple behavior first: purchase completion
updates the list. If temporary reservation is later implemented, make
expiry explicit.

Provide empty state, shared list state, owner state, unavailable-product
state.

------------------------------------------------------------------------

# 13. SEO / CONTENT STRATEGY

SEO is part of architecture, not a plugin installed at the end.

Primary brand goal: AYRU should have a technically clean branded
presence for searches of its own name once indexed and authoritative
enough.

Commercial/topic clusters include: - produits bébé; - panier bébé /
préparation bébé where semantically relevant; - liste de naissance
bébé; - quoi acheter avant naissance bébé; - préparer l'arrivée de
bébé; - chambre bébé; - peluches; - cadeaux bébé; - produits/tendances
TikTok where appropriate.

Do NOT keyword-stuff.

## Guides & Conseils

Create a content hub that can target informational searches. These pages
do not need to be prominent in the main navigation, but must NOT be
deceptive "hidden SEO pages". They are legitimate indexable editorial
pages accessible through Guides, footer, internal links and sitemap
where appropriate.

Example pillar: "Quoi acheter avant la naissance de bébé ?"

Supporting content: - liste de naissance bébé; - essentiels pour
l'arrivée de bébé; - préparer la chambre; - idées cadeaux; - age/stage
guides where useful.

Each guide: - unique search intent; - one clear H1; - useful original
content; - table of contents for long pages; - semantic H2/H3; -
internal links; - contextual product modules; - CTA toward Ma Liste AYRU
where natural; - FAQ only when useful; - author/update information if
editorial process supports it.

AdSense: Architect content areas so ads could later be inserted without
destroying UX/Core Web Vitals, but DO NOT add ads or empty ad clutter
unless the user configures AdSense. Ads must not masquerade as
content/buttons.

## Technical SEO

Implement: - clean permalinks; - canonical URLs; - XML sitemap; - robots
controls; - metadata; - Open Graph; - Twitter/social metadata; -
breadcrumb structured data; - Product structured data from real
WooCommerce data; - Organization/WebSite data where valid; - Article
data for guides where valid; - no fake AggregateRating; - sensible
index/noindex; - redirects when slugs change; - image alt strategy; -
optimized filenames; - pagination/indexing decisions; - internal
linking; - 404; - performance/Core Web Vitals.

Do not create schema fields with invented information.

------------------------------------------------------------------------

# 14. 404 / EMPTY / ERROR DESIGN LANGUAGE

These screens must feel deliberately AYRU.

Use supplied NEB states: - confused/lost → 404; - magnifier → no search
results; - cart pose → empty cart; - heart pose → empty favorites; -
parcel/order visual → empty orders/tracking where appropriate; -
checklist → empty/new birth list; - support/question pose → FAQ/contact
help.

Keep copy concise, useful and action-oriented. Always provide a recovery
CTA.

------------------------------------------------------------------------

# 15. ASSET CONTRACT

Before implementation: 1. recursively inspect `/assets` (or the supplied
equivalent); 2. list every image/SVG/WebP/PNG; 3. infer role from
filename and visual content; 4. produce `docs/ASSET_MAP.md`; 5. never
silently ignore a supplied approved asset.

Recommended logical structure if the supplied ZIP can be reorganized
without losing original files:

assets/ brand/ neb/ 2d/ 3d/ states/ hero/ home/ categories/ backgrounds/
decorative/ icons/ ui/ products/ editorial/

Known/expected asset concepts include: - official AYRU logo variants; -
AYRU sparkle/four-point star; - NEB poses; - Home NEB in lilac/cream
duvet --- mobile + desktop; - category Bébé hero --- mobile + desktop; -
abstract duvet/cloud cream/lilac background --- mobile + desktop; - NEB
discreetly integrated into baby-room scene --- mobile + desktop; - NEB +
birth-list checklist; - NEB in purple shopping cart; - sleeping NEB; -
peeking NEB; - NEB with magnifier; - order success NEB + parcel; -
tracking NEB + parcel; - lost/confused NEB; - NEB + question; -
welcoming/waving NEB; - NEB + heart; - NEB + gift; - NEB + open book; -
organic cream/lilac shape; - soft cream/lilac/cloud background; -
four-point sparkle cluster; - heart/doodle decorations; - motion
arrows/graphic accents; - standalone AYRU checklist; - empty-order
parcel illustration \[may still be pending\]; - search-empty magnifier +
AYRU elements \[may still be pending\].

The user may rename files before supplying the folder. Therefore DO NOT
depend on the filenames in this document alone. Inspect actual files and
create a mapping.

If duplicate visual concepts exist, do not randomly choose. Compare
against approved mockups and document which file is selected.

Use SVG for logos/icons when supplied. Prefer modern responsive formats
for raster delivery while retaining originals.

------------------------------------------------------------------------

# 16. WORDPRESS / WOOCOMMERCE TECHNICAL ARCHITECTURE

The implementation must remain portable from local/development hosting
to the user's future server.

Use: - WordPress; - WooCommerce; - a custom AYRU theme or child/custom
theme architecture appropriate to the implementation; -
version-controlled custom code; - environment variables/config for
secrets; - no credentials committed; - minimal plugin dependence.

Do not solve every problem with a plugin. Every plugin adds
update/security/performance risk. Document why each required plugin
exists.

Keep: - presentation in theme/components; - business logic that must
survive a theme change in a dedicated plugin/module where appropriate; -
WooCommerce customizations through supported hooks/templates/APIs; -
avoid editing WooCommerce core; - avoid editing WordPress core.

Create a custom AYRU functionality plugin for durable custom features
such as Ma Liste AYRU if appropriate.

Use WordPress admin/configuration for editable: - announcement; -
editorial homepage copy where sensible; - selected
categories/collections; - guide content; - policies; - contact
details; - social links.

Do not turn every pixel into an admin option.

------------------------------------------------------------------------

# 17. DATA / PRODUCT MODEL

Use native WooCommerce concepts where possible: - products; - variable
products; - attributes; - categories; - tags only when useful; -
inventory; - sale pricing; - shipping classes; - tax settings; -
coupons; - orders; - customer accounts.

Do not hardcode sample products into templates.

Until sourcing is finalized, seed development with clearly marked DEMO
fixtures that can be removed. Never present demo claims/data as
production content.

Plan importer/supplier synchronization as a later integration boundary.
Do not couple core frontend to AliExpress/DSers/CJ/etc. prematurely.

------------------------------------------------------------------------

# 18. PAYMENTS / SECURITY

Do not implement a custom payment processor.

WooCommerce owns cart/order lifecycle. Payment gateway is integrated
through official/maintained WooCommerce gateway plugins/APIs.

Security requirements: - HTTPS in production; - secure cookies; -
WordPress/WooCommerce nonces; - server-side validation; - sanitize
inputs; - escape output; - prepared database operations; -
least-privilege roles; - no exposed secrets; - rate-limit/protect
sensitive endpoints where relevant; - spam protection on forms; -
backups/update strategy documented.

Never store card PAN/CVC.

------------------------------------------------------------------------

# 19. PERFORMANCE

Target a fast mobile experience.

Requirements: - responsive images/srcset; - WebP/AVIF where pipeline
permits; - dimensions to prevent layout shift; - lazy-load below fold; -
hero/LCP asset loaded intentionally, not lazily by accident; - minimize
JS; - no giant animation framework without need; - avoid autoplay heavy
media; - local/system font strategy where licensing allows; -
cache-friendly assets; - database/query hygiene; - WooCommerce
fragment/cart behavior reviewed for performance; - no decorative asset
larger than necessary.

Animations: subtle and functional. Respect `prefers-reduced-motion`. No
constant floating/bouncing elements that make AYRU feel like an AI demo.

------------------------------------------------------------------------

# 20. ACCESSIBILITY

Aim for WCAG 2.2 AA practices: - semantic HTML; - keyboard navigation; -
visible focus; - labels; - accessible menus/dialogs/drawers; - alt
text; - sufficient contrast; - errors tied to inputs; - ARIA only where
semantic HTML is insufficient; - no information conveyed only by
color; - touch targets appropriate on mobile; - reduced-motion support.

Decorative doodles/sparkles should have empty alt / presentation
semantics.

------------------------------------------------------------------------

# 21. CRO RULES

Optimize conversion by clarity, not manipulation.

Allowed: - strong hierarchy; - clear CTA; - concise reassurance; -
useful recommendations; - real availability; - real promotion; -
transparent delivery/returns; - good search/filter; - persistent cart
state; - friction reduction.

Forbidden: - fake scarcity; - fake social proof; - fake crossed-out
prices; - fake countdown; - forced urgency; - misleading preselection; -
hidden costs; - dark patterns.

------------------------------------------------------------------------

# 22. LEGAL / COMPLIANCE CONTENT

Build the templates/fields for legal and product-safety information but
do not invent legal facts.

Pages: - mentions légales; - CGV; - privacy; - cookies; -
shipping/returns.

For child/baby products, architecture must be able to display: -
manufacturer; - product reference; - age/warnings; - CE where genuinely
applicable and documented; - responsible person/importer where
applicable; - traceability/safety info.

Content will be populated only from verified supplier/business data.

------------------------------------------------------------------------

# 23. IMPLEMENTATION QUALITY

Code must be: - modular; - readable; - documented where non-obvious; -
reusable; - secure; - responsive; - accessible; - testable.

No giant single template. No copy/paste product-card variants. No inline
style chaos. No magic numbers everywhere. Use design tokens/CSS custom
properties for spacing, radii, colors, typography and shadows.

Reproduce visual measurements from references systematically: -
establish container widths; - spacing scale; - card radii; - header
heights; - grid gaps; - typography scale; - image aspect ratios; -
button heights; - mobile gutters.

Do not eyeball each page independently.

------------------------------------------------------------------------

# 24. REQUIRED PROJECT DOCUMENTATION

Create: - `README.md` - `docs/ARCHITECTURE.md` - `docs/ASSET_MAP.md` -
`docs/DESIGN_SYSTEM.md` - `docs/ROUTES_PAGES.md` - `docs/SEO.md` -
`docs/WOOCOMMERCE.md` - `docs/MA_LISTE_AYRU.md` - `docs/DEPLOYMENT.md` -
`docs/QA_CHECKLIST.md` - `.env.example` if relevant - a record of
required plugins and why.

------------------------------------------------------------------------

# 25. IMPLEMENTATION ORDER --- DO NOT SKIP

### Stage A --- Audit only

Before coding: 1. inspect entire supplied folder; 2. inventory assets;
3. identify approved mockups; 4. map visuals; 5. report
missing/ambiguous assets; 6. propose project structure; 7. identify
which content is placeholder vs verified.

Do not redesign.

### Stage B --- Foundation

-   WordPress/WooCommerce environment;
-   AYRU theme;
-   design tokens;
-   header/footer;
-   responsive layout primitives;
-   reusable buttons/cards/forms/icons;
-   accessibility foundations.

### Stage C --- Commerce shell

-   product card;
-   category grid;
-   product page;
-   mini-cart/cart;
-   search;
-   favorites;
-   WooCommerce states.

### Stage D --- Home

Reproduce approved Home mobile + desktop using real components.

### Stage E --- Secondary pages

Collections/categories, account, orders, tracking, checkout,
confirmation, FAQ/contact/legal/404.

### Stage F --- Ma Liste AYRU

Implement persistent/shareable list with clear owner/shared flows.

### Stage G --- Guides + SEO

Templates, taxonomy/content architecture, structured metadata, internal
links.

### Stage H --- QA

Test: - mobile widths; - tablet; - desktop; - keyboard; - reduced
motion; - empty states; - errors; - variation products; - out of
stock; - coupons; - account; - checkout; - payment failure/success test
mode; - list sharing; - SEO metadata; - schema validity; -
sitemap/robots; - performance.

------------------------------------------------------------------------

# 26. VISUAL QA GATE

For every major page: 1. render/screenshot mobile; 2. render/screenshot
desktop; 3. compare against approved references; 4. list discrepancies;
5. correct them.

Do not declare a page complete merely because it "looks close".

Check: - spacing; - hierarchy; - visual density; - cropping; - NEB
scale; - four-point sparkle consistency; - border radii; - typography; -
card proportions; - button sizing; - alignment; - footer; -
hover/focus; - loading/empty/error states.

------------------------------------------------------------------------

# 27. HOW TO HANDLE UNCERTAINTY

If a requirement conflicts with a supplied approved visual, stop and
report the exact conflict.

If an image is missing: - use a neutral placeholder in development; -
identify expected filename/role; - continue implementation without
inventing a new final asset.

If business information is unknown: - expose a configurable field; - use
obvious development placeholder copy; - do not fabricate production
claims.

If a plugin/library is proposed: - explain what problem it solves; -
whether WordPress/WooCommerce already solves it; -
maintenance/security/performance impact; - only then add it.

------------------------------------------------------------------------

# 28. FIRST RESPONSE EXPECTED FROM CODEX

Do NOT immediately generate the entire website.

First: 1. inspect all files; 2. summarize the understood AYRU product
and visual system; 3. output the asset inventory and mapping; 4. output
the page/component architecture; 5. output the WordPress/WooCommerce
architecture; 6. identify missing/ambiguous assets or information; 7.
produce an implementation plan in small verifiable milestones.

Then begin Stage B only after the repository is understood.

At every milestone, report: - files created/modified; - what works; -
what remains; - assumptions; - visual discrepancies; - tests performed.

The specification above is binding. The supplied approved assets/mockups
outrank generic ecommerce conventions. When a conventional ecommerce
pattern conflicts with AYRU's approved visual direction, preserve AYRU
while maintaining usability/accessibility.
