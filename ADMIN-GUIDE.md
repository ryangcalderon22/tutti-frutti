# Tutti Frutti Café — Admin Guide (v1.5.0)

Manage the site from WordPress admin: brands, product categories, jobs, FAQs, forms, homepage toggles, and images.

---

## Setup (first time)

1. Activate **Tutti Frutti Cafe** theme + **Tutti Frutti Slider** plugin  
2. **Settings → Permalinks → Save Changes** (required for `/brand/slug/` URLs)  
3. Visit the site once — demo brands, products, and page sections import automatically  

---

## Customize → Homepage Settings

| Setting | Effect |
|---------|--------|
| Hero minimum height | Default `30vh` (shorter homepage hero) |
| Slider section title | Default **Grab & Go** (replaces Featured Treats) |
| TF Slides group slug | Default `grab-and-go` — slides must use this group in **TF Slides** |
| Show Late Night & High Tea promos | Off by default |
| Show footer badges strip | Off by default |
| Hide icon/value cards on About | On by default (Premium Ingredients row hidden) |
| Extra custom CSS | Appended in `<head>` after theme variables |

---

## Customize → Page Images

| Setting | Effect |
|---------|--------|
| Rewards / Careers banner | Hero background for those pages (Customizer takes priority over section featured image when set) |

---

## Customize → Email Settings

| Setting | Effect |
|---------|--------|
| Contact admin email(s) | Comma-separated recipients |
| Contact — send customer confirmation | On by default |
| Careers admin email(s) | Comma-separated; empty = same as contact |
| Careers — send applicant confirmation | On by default |
| ChowNow / default order URL | Used for **Order Now** on brand products unless overridden per product |

---

## Brands & products

**Brands** — detail page at `/brand/slug/`

**Product Categories** — assign to a brand; products group under category headings on the brand page.

**Brand Products** — assign brand + category; optional **Order URL** (ChowNow); empty = global URL from Customize.

**Order Now** — hover on product card opens the order link in a new tab.

---

## Page Sections

List shows **Page**, **Layout**, **Order**. Filter by page in admin.

**Careers hero** and **Rewards** images: use **Customize → Page Images** or section **Featured Image**.

To remove About value/icon rows permanently: delete **Page Sections** filtered to About with layout Icon card, or keep **Hide icon/value cards on About** enabled.

---

## FAQs

**FAQs** CPT — title = question, editor = answer, menu order = sort order.

**Pages → FAQs** uses the FAQs template (auto-created on theme activation).

---

## Careers

**Jobs** — title, description, location, **Active** checkbox, menu order.

**Job Applications** — created when someone submits the form (name, email, job, resume path).

**Careers page** — lists active jobs with **Apply** (scrolls to form). **General Application** always available at the bottom; if no jobs are published, only the general form is emphasized.

---

## Contact

**Contact Messages** — stored inquiries from the contact form.

Form emails go to **Customize → Email Settings** contact recipients.

---

## Quick links (Dashboard widget)

Brands, Brand Products, Product Categories, Page Sections, FAQs, Jobs, Job Applications, Contact Messages, TF Slides, Customize.

---

## Theme version

**1.5.0** — `assets/css/custom.css` uses stable classes (`.page-order`, `.page-rewards`, `.page-careers`, `.page-brand-detail`) instead of `page-id-*`.
