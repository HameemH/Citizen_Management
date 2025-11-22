# Milestone 3 – Revenue Reconciliation & Compliance Dashboard

## Objectives
- Give admins a single place to monitor fiscal performance: issued vs. collected taxes, delinquency, and payment velocity.
- Provide reconciliation tooling to cross-check outstanding assessments, detect inconsistencies, and export supporting evidence.
- Surface actionable alerts so staff can intervene with overdue accounts or failed Stripe sessions.

## Scope & Deliverables
1. **Admin Revenue Dashboard**
   - KPI tiles (total issued YTD, collected, outstanding, collection rate, average days to pay).
   - Trend chart (monthly issued vs. collected) fed by `tax_assessments` and `tax_payments` aggregates.
   - Breakdown tables for top delinquent citizens/properties.
2. **Reconciliation Toolkit**
   - Filterable report view (status, fiscal year, city) with export to CSV.
   - Aging buckets (0-30, 31-60, 61-90, 90+) derived from `due_date` and `paid_at`.
   - Flag mismatches (e.g., assessments marked paid but lacking payments, duplicate payments).
3. **Alerts & Notifications**
   - Badge/indicator for overdue assessments count in admin nav (tie into existing layout).
   - Optional email digest stub (configurable) for delinquent accounts (logic only, queued mail optional).
4. **Citizen Receipts & History Enhancements**
   - Payment history section (Stripe + manual) on citizen taxes page.
   - Downloadable receipt (PDF or HTML) referencing assessment, payment reference, amount, timestamps.

## Technical Approach
- Build reporting queries using Eloquent aggregates and cached snapshots (consider nightly job later).
- Introduce dedicated `AdminRevenueController` with Livewire or standard Blade tables; reuse Tailwind UI components.
- Add query scopes/helpers on `TaxAssessment` & `TaxPayment` (e.g., `scopeDelinquent`, `scopeInRange`).
- Utilize Laravel's `LazyCollection` or chunking for CSV exports to avoid memory spikes.
- Store generated receipt metadata in `tax_payments` (e.g., JSON column for receipt_number) if needed.

## Data/Config Needs
- Seed data updates for diverse statuses to demo dashboard.
- Config toggles in `config/tax.php` for delinquency thresholds and email recipients.

## Testing Plan
- Feature tests covering dashboard KPIs, reconciliation filters, CSV export authorization, and receipt download access control.
- Unit tests for new scopes/aging calculations (e.g., confirms correct bucket placement).
- Browser test stub (optional) to ensure citizen receipt link renders for paid assessments.

## Open Questions
- Should receipts be PDF (requires dompdf) or HTML print view? (default to HTML unless specified).
- Are email notifications required this milestone or just indicators? (currently scoped as optional stub).
- Any BI integrations (PowerBI export) needed later?
