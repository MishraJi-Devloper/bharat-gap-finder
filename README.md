<div align="center">

# 🏭 Bharat Manufacturing Gap Finder (BMGF)
### District-Level Industrial Deficit & Economic Intelligence Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Supabase-336791?style=for-the-badge&logo=postgresql)](https://supabase.com)
[![Status](https://img.shields.io/badge/Status-Production_Ready-10b981?style=for-the-badge)](#)

<p align="center">
  <strong>An empirical, data-driven platform designed to eliminate regional supply shortages across India by connecting unmet consumption demand with local MSME manufacturing capacity.</strong>
</p>

[Key Features](#-key-features) •
[The Core Formula](#-quantitative-model) •
[Architecture](#-system-architecture) •
[Comparison Matrix](#-competitive-differentiation) •
[Quickstart](#-local-installation--setup) •
[API & Routes](#-application-routes)

---

</div>

## 📌 Executive Overview

Across India, districts routinely import essential commodities (e.g., corrugated packaging, fasteners, castings, plastic components) from distant hubs or international markets, incurring excessive freight overhead and supply delays. Concurrently, local entrepreneurs and Micro, Small, and Medium Enterprises (MSMEs) struggle with business failure due to blind market entry—duplicating existing businesses in saturated markets while viable supply chains remain unserved.

**Bharat Manufacturing Gap Finder (BMGF)** addresses this market friction:
1. **Aggregates Regional Consumption Demands** from public procurement, infrastructure tenders, and commercial consumption.
2. **Audits Local Registered MSME Production Capacities** to compute localized supply ceilings.
3. **Calculates Net Industrial Deficits** in real time.
4. **Calculates a Weighted 0–100 Opportunity Score** to prioritize which factories should be constructed next for guaranteed local buyers.

---

## 📐 Quantitative Model

The platform operates on a closed-loop empirical balance formula evaluated per product ($p$), per district ($d$), across period ($t$):

$$\text{Manufacturing Gap } (G_{p,d,t}) = \text{Aggregate Demand } (D_{p,d,t}) - \sum \text{Local MSME Output } (S_{p,d,t})$$

### The 4-Pillar Opportunity Scoring Engine ($0 - 100$)
When $G > 0$ (a net shortage exists), the gap is evaluated using a weighted multi-factor feasibility index:

$$\text{Score} = (0.35 \times \text{Deficit}) + (0.25 \times \text{CapEx}) + (0.20 \times \text{Raw Materials}) + (0.20 \times \text{Employment})$$

| Pillar | Weight | Description |
| :--- | :---: | :--- |
| **Deficit Volume** | **35%** | Absolute volume and commercial monetary value of unmet regional demand. |
| **CapEx Feasibility** | **25%** | Machine setup cost, power requirements, and accessibility of standard MSME loans (₹25L–₹2Cr). |
| **Raw Material Proximity**| **20%** | Proximity to raw materials to protect profit margins against long-distance freight. |
| **Employment Potential** | **20%** | Potential for creating local blue-collar and skilled manufacturing employment. |

---

## ✨ Key Features

- **🗺️ Interactive Industrial Map (`/`)**: Geospatial district visualization powered by Leaflet.js with low-latency in-memory data caching.
- **📥 Dual Ingestion Pipelines**:
  - `POST /demand/store` &bull; Intake consumption requirements from government procurement and market estimates.
  - `POST /supply/store` &bull; Log installed capacity versus actual factory output.
- **⚖️ Side-by-Side District Benchmark (`/compare`)**: Contrast two industrial districts (e.g., Howrah vs. Purba Medinipur) to spot supply-chain dependencies.
- **📄 Opportunity Briefs (PDF)**: Server-side PDF briefs (`/opportunity/{id}/pdf`) tailored for District Industries Centres (DIC) and commercial banks.

---

## 📊 Competitive Differentiation

| Dimension | B2B Directories (IndiaMART, TradeIndia) | Govt Tenders (GeM, e-Procure) | BMGF (This Platform) |
| :--- | :--- | :--- | :--- |
| **Core Goal** | Listing existing sellers for lead generation | Purchasing goods for public projects | **Prescribing what new factories should be built** |
| **Data Scope** | Flat catalog of existing suppliers | Isolated contract bids | **Macro supply vs. demand mismatch** |
| **Spatial Insights** | None (nationwide listings) | Departmental only | **District-level geospatial deficit visualization** |
| **Opportunity Scoring** | ❌ None | ❌ None (Lowest bid wins) | **✅ Algorithmic 0–100 Feasibility Score** |
| **Target User** | Buyers & wholesale brokers | Procurement officers | **Entrepreneurs, MSMEs, Banks, DIC Officers** |
| **Economic Impact** | Long-distance trade & trading margins | Public capital deployment | **Import substitution & industrial cluster creation** |

---

## 🛠️ System Architecture & Stack

```
[ Ingestion Pipelines ] ──► [ PostgreSQL / Supabase ] ──► [ GapCalculationService ]
                                                                   │
                                                                   ▼
[ Leaflet Map & Blade UI ] ◄── [ Blade Cache Layer ] ◄── [ OpportunityScoringService ]
```

- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Database Engine**: PostgreSQL on Supabase with UUID primary keys and B-Tree performance indexing.
- **Caching & Session**: In-memory local file caching ensuring **<200ms** Time To First Byte (TTFB).
- **Frontend Visualization**: Leaflet.js for interactive district mapping and Chart.js for opportunity analytics.
- **Document Generation**: DomPDF rendering structured HTML/CSS templates directly into PDF files.

---

## 🚀 Local Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer 2.x
- PostgreSQL database (or Supabase account)

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/bharat-manufacturing-gap-finder.git
cd bharat-manufacturing-gap-finder
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
Copy the `.env.example` file and configure your credentials:
```bash
cp .env.example .env
```
Ensure your database parameters and cache drivers are configured:
```env
APP_NAME="Bharat Manufacturing Gap Finder"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=your-supabase-db-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password

CACHE_STORE=file
SESSION_DRIVER=file
```

### 4. Run Migrations & Seeders
```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Launch the Local Development Server
```bash
php artisan serve
```
Visit **`http://127.0.0.1:8000`** in your browser.

---

## 🗺️ Application Routes

| Endpoint | Method | Purpose |
| :--- | :---: | :--- |
| `/` | `GET` | Main Dashboard with Leaflet geospatial deficit map. |
| `/demand/create` | `GET/POST` | Ingestion form and processor for district consumption demand. |
| `/supply/create` | `GET/POST` | Registry form and processor for MSME installed vs. actual capacity. |
| `/compare` | `GET` | Side-by-side district economic comparison tool. |
| `/opportunity/{id}` | `GET` | Deep dive opportunity view with local MSME candidate matching. |
| `/opportunity/{id}/pdf` | `GET` | Downloads the PDF brief for a manufacturing opportunity. |

---

## 👥 Target Stakeholders

- **Young Industrial Entrepreneurs**: Discover low-risk, verified manufacturing opportunities backed by local purchase demand.
- **Commercial Lending Banks & SIDBI**: Validate loan repayment feasibility using district supply-demand gap data before capital disbursement.
- **District Industries Centres (DIC)**: Formulate targeted industrial cluster policies, allocate land parcels, and roll out focused MSME capital subsidies.

---

## 📜 License
Distributed under the **MIT License**. See `LICENSE` for more information.