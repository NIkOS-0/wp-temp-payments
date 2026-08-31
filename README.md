# Personal Payment Links on WordPress

A system of **personal offers**: a manager builds a proposal in seconds, the client receives a short link with a price and a time-to-live, and payment is processed through Robokassa. The public site and the payment circuit live on **separate domains** within a single Bedrock project.

<p align="center">
  <img src="docs/dashboard.png" alt="Offer manager dashboard: KPIs, 30-day chart, link creation" width="920">
</p>

<p align="center"><em>A custom dashboard instead of the standard wp-admin: conversion, activity, and link creation on a single screen.</em></p>

---

## Why this, and not "just another WooCommerce"

An ordinary store is a poor fit for the "the manager agreed on a price in chat and now has to send payment right away" scenario. Here a product is a **template** (`product_offer`), and a sale is a **one-time personal link** (`personal_offer`):

- its own price and quantity;
- TTL with minute-level precision;
- optional binding to the client's browser;
- `paid` status after a gateway callback, not "on good faith";
- on the payment domain, only offers are exposed to the outside — everything else returns a clean 404.

The manager never sees the WordPress core: the `offer_manager` role, a trimmed-down menu, a redirect to the dashboard, and a 14-day session.

---

## How the circuit is built

```
                    ORDER_DOMAIN                         main domain
                 (payments + admin)                    (content / storefront)
                          │                                      │
  Manager ──login──► Dashboard ──creates──► personal_offer       │
                          │                      │               │
                          │                      ▼               │
                          │              /p/{random-token}       │
                          │                      │               │
  Client ─────────────────┴──opens the link─────┘               │
                          │                                      │
                          ├── cookie-bind (optional)             │
                          ├── POST /api/payment/robokassa/checkout
                          ├── Robokassa (Password1)
                          └── ResultURL + Password2 ──► paid
```

The host is checked against `ORDER_DOMAIN` already at the config stage: `WP_HOME` / `WP_SITEURL` switch to the payment domain. The order MU-plugins are loaded **only** there (`order-domain-loader.php`). On the main site, this code isn't even loaded.

---

## Manager dashboard

The screenshot above is not a mockup but a working `wp-admin` built on Tailwind (isolated with `preflight: false` so it doesn't break the core) and Chart.js.

| Block | What it computes |
| --- | --- |
| KPI | Total offers / paid / conversion — SQL aggregation `GROUP BY post_status`, without `get_posts(-1)` |
| 30-day chart | Created / paid / expired by date; expiration is based on `_expiry_timestamp` |
| Offer creation | Title, product, price, TTL (hours + minutes), "bind to browser" |
| Recent links | `PAID` / active status, URL copying |

The AJAX link creation: nonce `manager_dashboard_nonce` + capability `manage_offers`.

---

## Data model

| CPT / status | Role |
| --- | --- |
| `product_offer` | Product catalog for the manager. `publicly_queryable = false` — there is no storefront |
| `personal_offer` | A deal instance, slug `/p/{16 characters}` |
| `offer_transaction` | Payment draft: amount, provider, link to the offer |
| `paid` status | A custom `register_post_status` on the offer after ResultURL |

The lifetime is written to `_expiry_timestamp` (unix), not as "N hours from the moment it's opened." An expired link and a repeat payment are cut off both on the page and at checkout.

---

## Robokassa payment

1. The client clicks pay → `POST /payment/v1/robokassa/checkout`.
2. Checks: the offer exists, is not `paid`, is not expired, and the price is numeric.
3. An `offer_transaction` is created in `pending`; the `InvId` for the gateway is the transaction ID, not the offer ID.
4. Redirect to Robokassa: signature `md5(MerchantLogin:OutSum:InvId:Password1)`.
5. ResultURL verifies `md5(OutSum:InvId:Password2)` (case-insensitive). Idempotency: a repeat callback on an already-`success` transaction responds `OK{InvId}` without a double write.
6. Success/Fail — a redirect to the offer permalink with `?payment=success|fail`. A successful view happens once: the frontend strips the query via `replaceState`; a repeat without the parameter returns 404 (`already_paid`).

The clean URL `/api/payment/robokassa/{checkout|result|success|fail}` is mapped to REST via a rewrite rule. The gateway login and passwords come only from env (`ROBOKASSA_*`); they are not in the code.

---

## Link protection

`OfferService::validateAccess()`:

- **TTL** — comparison against `_expiry_timestamp`.
- **Already paid** — access only with a one-time `payment=success`.
- **Bind to browser** — on the first visit to an offer, the `md5` of a long-lived `persistent_client_id` (a cookie set for a year) is written. A different browser gets `security_violation` → 404. The ID itself is not stored in the DB, only its hash.

On `ORDER_DOMAIN`, any URL other than `personal_offer`, `wp-login`, and service ones (`robots`, feed) is cut off by an early `template_redirect` with a static 404 — without leaking the site structure.

---

## Stack

| Layer | Solution |
| --- | --- |
| Application | [Bedrock](https://roots.io/bedrock/) · WordPress 6.9 · PHP ≥ 8.3 |
| Config | `.env` / `.env.local` · `vlucas/phpdotenv` · `oscarotero/env` |
| Theme | [Sage](https://roots.io/sage/) · Blade · Vite 7 · Tailwind 4 |
| Payment domain | MU-plugins in `web/app/mu-plugins/order/` |
| Quality | Laravel Pint · Pest |

Secrets and DB dumps are not kept in git (`.env`, `backup.sql`, `.agents` are in ignore).

---

## Quick start

```bash
composer install
cp .env.example .env   # DB_*, WP_HOME, WP_SITEURL, ORDER_DOMAIN, salts, ROBOKASSA_*
cd web/app/themes/ediet-theme && npm install && npm run build
```

Critical variables:

| Variable | Purpose |
| --- | --- |
| `ORDER_DOMAIN` | The host on which the dashboard, the 404 lockdown, and the payment API are enabled |
| `ROBOKASSA_MERCHANT_LOGIN` | Store login |
| `ROBOKASSA_PASSWORD_1` | Checkout signature |
| `ROBOKASSA_PASSWORD_2` | ResultURL verification |
| `ROBOKASSA_IS_TEST` | `1` — Robokassa test payment |

In the Robokassa dashboard, ResultURL must point to `https://{ORDER_DOMAIN}/api/payment/robokassa/result`.

The **Offer Manager** role is registered at `init` (`app/roles.php`) and receives the `manage_offers` capability plus rights to both CPTs.

---

## Code map

```
config/application.php              # switching WP_HOME by ORDER_DOMAIN
web/app/mu-plugins/order-domain-loader.php
web/app/mu-plugins/order/
  offer_manager_admin.php           # dashboard, KPIs, Chart.js, trimmed admin
  offers_404.php                    # lockdown of public pages
  api_offers_payment/               # REST + RobokassaGateway
web/app/themes/ediet-theme/app/
  roles.php  post-types.php  ajax-offers.php
  Services/OfferService.php         # link creation, TTL, cookie-bind
```
