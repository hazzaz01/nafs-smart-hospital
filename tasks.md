# UI/UX Reskin Tasks

Goal: restyle the current CodeIgniter admin (Bootstrap/AdminLTE-style, server-rendered)
to visually match the reference design at https://eye-care-pi.vercel.app/ (teal/white,
card-based, sidebar + topbar shell). Backend/architecture stays CodeIgniter — this is a
CSS/template reskin, not a framework rewrite.

Reference: reskin scope decided 2026-08-20 — see conversation for full comparison
(576 admin views, 824 total views, 197 controllers, no existing build pipeline).

## Phase 1 - Design system setup (4-6 days)
- [ ] Define color palette (teal/white primary, accent colors) as CSS variables
- [ ] Define typography scale (headings, body, labels)
- [ ] Build reusable component styles: cards, buttons, inputs, badges, tables
- [ ] Build new sidebar shell (icons + labels, active state)
- [ ] Build new topbar shell (logo, notifications, language switcher, profile menu)
- [ ] Decide on build tooling (plain CSS vs. introducing Tailwind + npm build step)

## Phase 2 - Core layout conversion (2-3 days)
- [ ] Replace shared header/footer/sidebar includes with new shell
- [ ] Verify shell renders correctly across at least one page per major role (admin, doctor, staff)

## Phase 3 - High-traffic pages (4-5 days)
- [ ] Login page
- [ ] Main dashboard
- [ ] Patient list
- [ ] Appointments
- [ ] Sign-off checkpoint with stakeholder before continuing to phase 4

## Phase 4 - Remaining admin views (10-15 days)
- [ ] Convert tables/forms/cards across remaining ~570 admin views to new component classes
- [ ] Pay special attention to custom modules (eye exam, glaucoma, DR screening,
      eye surgery, ocular imaging) - newer and likely faster to restyle

## Phase 5 - QA pass (3-4 days)
- [ ] Confirm forms submit correctly across converted pages
- [ ] Confirm modals still open/close
- [ ] Confirm JS widgets still work (datatables, chart.js, select2, datepickers)
- [ ] Confirm responsive behavior (mobile/tablet) matches reference site's feel
- [ ] Cross-role smoke test (admin, doctor, staff logins)

## Risks / watch-outs
- No existing Composer/npm build pipeline - introducing Tailwind adds a build step
- Old jQuery plugins baked into views need markup/class updates, not just color swaps
- 576 admin views is a lot of repetitive but individually-reviewable work
