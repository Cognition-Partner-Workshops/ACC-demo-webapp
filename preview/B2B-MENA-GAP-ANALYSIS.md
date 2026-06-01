# B2B MENA Gap Analysis — Nestlé Storefront

## Executive Summary

This document identifies the key missing screens and features required for the
Example_Storefront Magento 2 module to operate as a B2B platform in the Middle
East & North Africa (MENA) region. The analysis compares the current B2C-oriented
storefront against standard B2B commerce requirements and MENA-specific needs.

---

## Current State

The existing module provides:
- Hero Banner carousel (entity, repository, ViewModel, theme, JS)
- Basic homepage with product grid
- Nestlé MENA-inspired design system (brand colors, responsive layout)

**What's missing:** All B2B-specific commerce functionality, account management,
and MENA regional features.

---

## Missing Screens — Prioritized

### Priority 1: Core B2B Commerce (Must-Have)

| # | Screen | Description | Status |
|---|--------|-------------|--------|
| 1 | **Product Catalog (B2B)** | Filterable catalog with SKU, MOQ (Minimum Order Quantity), trade pricing, case/pallet quantities, and "Add to Order" instead of "Add to Cart" | Preview built |
| 2 | **Quick Order / Order Pad** | SKU-based rapid entry form with quantity inputs, CSV upload, and reorder from history. Core B2B workflow for repeat buyers | Preview built |
| 3 | **Request for Quote (RFQ)** | Form to request custom bulk pricing with product list, quantities, delivery frequency, region, and expected delivery date | Preview built |
| 4 | **Account Dashboard** | B2B-specific KPIs: total orders, YTD spend, credit limit, available credit, pending orders, open quotes. Quick links to key actions | Preview built |
| 5 | **Order History & Tracking** | Complete order history with status filters (Pending, Processing, Shipped, Delivered), reorder capability, invoice downloads, shipment tracking | Preview built |
| 6 | **Contact / B2B Support** | Regional office directory, B2B sales contact, inquiry type routing (new account, pricing, partnerships), country-specific forms | Preview built |

### Priority 2: B2B Account Management (High Priority)

| # | Screen | Description |
|---|--------|-------------|
| 7 | **Company Account Registration** | Multi-step registration: company details, trade license upload, tax registration (VAT/TRN), delivery addresses, payment terms selection |
| 8 | **User Roles & Permissions** | Company admin manages sub-users (purchasers, approvers, viewers) with role-based access and spending limits |
| 9 | **Approval Workflows** | Configurable purchase approval chains: orders above threshold require manager sign-off before submission |
| 10 | **Credit Management** | View credit limit, available credit, payment terms (Net 30/60/90), outstanding invoices, payment history |
| 11 | **Invoice Management** | List of invoices with status (Paid, Overdue, Pending), download PDF, pay online, dispute/query capability |
| 12 | **Shared / Requisition Lists** | Save frequently ordered product lists per department or team for one-click reorder |

### Priority 3: MENA-Specific Features (Regional Requirements)

| # | Screen / Feature | Description |
|---|-----------------|-------------|
| 13 | **Arabic (RTL) Layout** | Full right-to-left layout with Arabic translations for all screens, EN/عربي language toggle |
| 14 | **Multi-Currency Pricing** | Display prices in AED, SAR, KWD, QAR, BHD, OMR, EGP, JOD, LBP with automatic conversion |
| 15 | **VAT/Tax Configuration** | Region-specific VAT display (5% UAE/KSA/Bahrain/Oman, 0% Kuwait/Qatar), tax-inclusive and tax-exclusive views |
| 16 | **Regional Delivery Zones** | Delivery zone selection by country and city, zone-based shipping rates, estimated delivery windows per region |
| 17 | **Halal Certification Display** | Product-level halal certification badges and filtering, certificate document downloads |
| 18 | **Islamic Calendar Integration** | Ramadan/Eid promotional banners, seasonal product recommendations, delivery schedule adjustments |

### Priority 4: Advanced B2B Features (Nice-to-Have)

| # | Screen / Feature | Description |
|---|-----------------|-------------|
| 19 | **Negotiated Price Lists** | Customer-specific or customer-group pricing tiers with volume discounts and contract pricing |
| 20 | **Sales Rep Portal** | Dedicated interface for field sales reps to place orders on behalf of customers, view territory accounts |
| 21 | **Recurring / Scheduled Orders** | Set up automatic repeat orders on a schedule (weekly, bi-weekly, monthly) with quantity and product presets |
| 22 | **Analytics / Reporting Dashboard** | Spending analytics, product performance, order trends, budget vs. actual, exportable reports |
| 23 | **Document Center** | Centralized repository for product specs, MSDS, certifications, marketing materials, planograms |
| 24 | **Training & Onboarding Portal** | Product training modules, merchandising guides, brand guidelines for retailers/distributors |
| 25 | **Promotions / Trade Deals** | B2B-specific promotions: volume discounts, bundle deals, seasonal promotions with auto-apply at checkout |

---

## Technical Implementation Recommendations

### Magento 2 B2B Modules Required

| Module | Purpose |
|--------|---------|
| `Magento_Company` | Company accounts, hierarchies, user roles |
| `Magento_SharedCatalog` | Customer-group-specific product visibility and pricing |
| `Magento_NegotiableQuote` | RFQ workflow with price negotiation |
| `Magento_RequisitionList` | Saved product lists for quick reorder |
| `Magento_PurchaseOrder` | Approval workflows for purchase orders |
| `Magento_QuickOrder` | SKU-based rapid order entry |
| `Magento_OrderHistorySearch` | Advanced order search and filtering |
| `Magento_CompanyCredit` | B2B credit management and payment on account |

### MENA-Specific Technical Requirements

1. **i18n / L10n**: Arabic locale pack, RTL CSS framework, bidirectional text handling
2. **Payment Gateways**: Integration with regional processors (PayFort/Amazon Payment Services, Checkout.com MENA, HyperPay, Telr)
3. **Shipping**: Integration with regional carriers (Aramex, SMSA Express, Fetchr, Quiqup)
4. **Tax Engine**: GCC VAT compliance module with country-specific rates
5. **CDN / Performance**: Regional CDN nodes (UAE, KSA, Egypt) for low-latency delivery

---

## Implementation Roadmap

### Phase 1 (Weeks 1–4): Core B2B Commerce
- Product Catalog with B2B pricing (items 1–2)
- Quick Order pad with CSV upload (item 2)
- Basic Account Dashboard (item 4)
- Order History & Tracking (item 5)

### Phase 2 (Weeks 5–8): Quoting & Account Management
- Request for Quote workflow (item 3)
- Company Account Registration (item 7)
- User Roles & Permissions (item 8)
- Credit Management & Invoicing (items 10–11)

### Phase 3 (Weeks 9–12): MENA Localization
- Arabic RTL support (item 13)
- Multi-currency pricing (item 14)
- VAT configuration per country (item 15)
- Regional delivery zones (item 16)

### Phase 4 (Weeks 13–16): Advanced Features
- Approval workflows (item 9)
- Requisition lists (item 12)
- Negotiated pricing (item 19)
- Recurring orders (item 21)

---

## Preview Status

Six key B2B screens have been built as standalone HTML previews to validate the
UX before Magento implementation:

| Screen | File | Status |
|--------|------|--------|
| Homepage (redesigned) | `index.html` | Complete |
| Product Catalog | `catalog.html` | Complete |
| Quick Order | `quick-order.html` | Complete |
| Account Dashboard | `account.html` | Complete |
| Request for Quote | `quote.html` | Complete |
| Order History | `orders.html` | Complete |
| Contact / Support | `contact.html` | Complete |

All previews use the Nestlé MENA brand design system (#003DA5, #E3002B, #7AB648)
and are responsive with a 768px mobile breakpoint.
