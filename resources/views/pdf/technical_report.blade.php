<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bharat Manufacturing Gap Finder - Technical Report</title>
    <style>
        @page { margin: 38px 44px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 9.5pt; line-height: 1.48; }
        h1 { color: #0f172a; font-size: 24pt; line-height: 1.15; margin: 0 0 10px; }
        h2 { color: #0f766e; font-size: 15pt; border-bottom: 1px solid #94a3b8; padding-bottom: 5px; margin: 22px 0 9px; page-break-after: avoid; }
        h3 { color: #0f172a; font-size: 11.5pt; margin: 14px 0 5px; page-break-after: avoid; }
        h4 { color: #334155; font-size: 10pt; margin: 10px 0 4px; }
        p { margin: 5px 0 8px; }
        ul, ol { margin: 5px 0 9px; padding-left: 20px; }
        li { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 8px 0 13px; page-break-inside: avoid; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 6px; vertical-align: top; }
        th { background: #e2e8f0; color: #0f172a; text-align: left; }
        .cover { border-bottom: 4px solid #059669; padding: 28px 0 20px; margin-bottom: 20px; }
        .eyebrow { color: #059669; font-weight: bold; font-size: 9pt; letter-spacing: 1.2px; text-transform: uppercase; }
        .subtitle { color: #475569; font-size: 12pt; }
        .meta { color: #64748b; font-size: 8.5pt; margin-top: 22px; }
        .callout { background: #ecfdf5; border-left: 4px solid #059669; padding: 10px 13px; margin: 10px 0; }
        .warning { background: #fff7ed; border-left: 4px solid #ea580c; padding: 10px 13px; margin: 10px 0; }
        .box { background: #f8fafc; border: 1px solid #cbd5e1; padding: 9px 12px; margin: 8px 0; }
        .code { font-family: DejaVu Sans Mono, monospace; font-size: 7.5pt; color: #0f172a; background: #f1f5f9; border: 1px solid #cbd5e1; padding: 8px; white-space: pre-wrap; margin: 7px 0 10px; }
        .small { font-size: 8pt; color: #64748b; }
        .page-break { page-break-before: always; }
        .footer { border-top: 1px solid #cbd5e1; color: #64748b; font-size: 8pt; margin-top: 22px; padding-top: 6px; }
        .good { color: #047857; font-weight: bold; }
        .limit { color: #b45309; font-weight: bold; }
    </style>
</head>
<body>
    <div class="cover">
        <div class="eyebrow">Detailed technical project report</div>
        <h1>Bharat Manufacturing Gap Finder</h1>
        <div class="subtitle">Complete architecture, implementation, security, deployment, and engineering evaluation</div>
        <div class="meta">Prepared for technical review, team selection, project presentation, and future production planning.</div>
    </div>

    <div class="callout">
        <strong>Executive technical summary:</strong> Bharat Manufacturing Gap Finder is a server-rendered Laravel web application that connects local manufacturing demand with local MSME capacity. It stores structured business, district, product, demand, and production information; calculates supply gaps; ranks opportunities; protects access with authentication and invitations; and provides map, directory, comparison, and PDF workflows.
    </div>

    <h2>1. Project identity and problem definition</h2>
    <h3>Project name</h3>
    <p><strong>Bharat Manufacturing Gap Finder</strong>, abbreviated as BMGF.</p>
    <h3>Problem being solved</h3>
    <p>Many regions purchase products from distant manufacturing centres even when a local enterprise could produce them. At the same time, entrepreneurs may start businesses without reliable knowledge of local demand. This creates avoidable freight cost, delayed supply, weak local employment, and investment in already saturated categories.</p>
    <h3>Core solution</h3>
    <p>The application creates a structured information loop:</p>
    <ol>
        <li>Record products and quantities needed in a district or region.</li>
        <li>Record existing local production capacity.</li>
        <li>Calculate the difference between demand and available production.</li>
        <li>Score the opportunity so people can focus on the most useful gaps.</li>
        <li>Register and verify MSMEs that may fulfil those needs.</li>
        <li>Allow authorised users to discover businesses and opportunities.</li>
    </ol>

    <h2>2. Why this is a web application</h2>
    <p>The project is designed as a browser-based application rather than a Windows or macOS-only program.</p>
    <table>
        <tr><th>Reason</th><th>Benefit</th></tr>
        <tr><td>Works in a browser</td><td>Businesses, buyers, administrators, and institutions can use the same system from laptops, desktops, tablets, and supported mobile browsers.</td></tr>
        <tr><td>Central data</td><td>All approved users work with the same directory and opportunity information instead of maintaining separate desktop copies.</td></tr>
        <tr><td>Easy updates</td><td>New business rules and security fixes are deployed once on the server.</td></tr>
        <tr><td>Location features</td><td>Browser permissions allow map selection and device location without a separate desktop installation.</td></tr>
        <tr><td>Team access</td><td>Private invitations allow controlled access for selected people or teams.</td></tr>
    </table>

    <h2>3. Technology stack and why each part is used</h2>
    <table>
        <tr><th>Technology</th><th>Role in this project</th><th>Why it is suitable</th></tr>
        <tr><td>PHP 8.2+</td><td>Server-side programming language.</td><td>Mature, widely supported, practical for web hosting, and compatible with the Laravel ecosystem.</td></tr>
        <tr><td>Laravel 12</td><td>Application framework, routing, validation, sessions, security, database access, and project structure.</td><td>Provides dependable conventions so business logic is organised instead of being scattered across files.</td></tr>
        <tr><td>Blade</td><td>Server-rendered page and PDF templates.</td><td>Works naturally with Laravel data, keeps views readable, and avoids unnecessary client-side complexity.</td></tr>
        <tr><td>PostgreSQL / Supabase</td><td>Stores user accounts, businesses, districts, products, demand, supply, gaps, and invitations.</td><td>Reliable relational storage with strong constraints, indexing, backups, and managed hosting options.</td></tr>
        <tr><td>Bootstrap</td><td>Responsive layout, forms, buttons, navigation, cards, tables, and mobile behaviour.</td><td>Allows a consistent interface to be delivered quickly across screen sizes.</td></tr>
        <tr><td>Leaflet and OpenStreetMap</td><td>Interactive district and enterprise location maps.</td><td>Useful for selecting, viewing, and sharing precise business locations.</td></tr>
        <tr><td>Chart.js</td><td>Opportunity factor charts on detailed opportunity pages.</td><td>Turns scoring values into visual information that is easier to compare.</td></tr>
        <tr><td>Three.js</td><td>Subtle ambient visual effect in the shared interface.</td><td>Used only as visual enhancement; the core business workflows do not depend on it.</td></tr>
        <tr><td>DomPDF</td><td>Generates opportunity briefs and project reports.</td><td>Allows the application to produce shareable documents directly from trusted server data.</td></tr>
        <tr><td>Composer</td><td>Manages PHP dependencies and autoloading.</td><td>Provides reproducible PHP installation and class loading.</td></tr>
        <tr><td>npm and Vite</td><td>Manages and builds browser assets.</td><td>Creates an optimised asset bundle for deployment.</td></tr>
        <tr><td>Docker</td><td>Packages PHP, Apache, dependencies, and built assets for deployment.</td><td>Makes the production environment more consistent between local development and Render.</td></tr>
        <tr><td>Render</td><td>Hosts the Laravel web service using the Docker image.</td><td>Provides a public HTTPS URL without requiring a privately owned domain.</td></tr>
    </table>

    <h2>4. High-level system architecture</h2>
    <div class="code">Browser
  |
  | HTTPS request
  v
Laravel routes and middleware
  |
  +-- Authentication and invitation checks
  +-- Controller validation and workflow decisions
  +-- Service classes for gap calculation and scoring
  +-- Eloquent models and relationships
  v
PostgreSQL / Supabase
  |
  +-- Users, invitations, businesses
  +-- Districts, products, demand, production
  +-- Manufacturing gaps and scores
  v
Blade pages, maps, charts, and PDF reports</div>
    <p>The architecture follows a request-response model. A browser requests a route, middleware decides whether the request is allowed, the controller validates and coordinates the action, services perform domain calculations, models read or write the database, and a Blade view returns the response.</p>

    <h2>5. Project folder structure</h2>
    <table>
        <tr><th>Folder</th><th>Responsibility</th></tr>
        <tr><td><code>app/Http/Controllers</code></td><td>Receives web requests and coordinates application workflows.</td></tr>
        <tr><td><code>app/Http/Middleware</code></td><td>Applies access rules such as administrator-only verification.</td></tr>
        <tr><td><code>app/Models</code></td><td>Represents database records and relationships.</td></tr>
        <tr><td><code>app/Services</code></td><td>Contains reusable business calculations that should not live inside views.</td></tr>
        <tr><td><code>database/migrations</code></td><td>Describes how the database evolves in a repeatable way.</td></tr>
        <tr><td><code>database/seeders</code></td><td>Provides initial example districts, products, businesses, demand, and production.</td></tr>
        <tr><td><code>resources/views</code></td><td>Contains user-facing Blade pages, admin pages, authentication pages, and PDF templates.</td></tr>
        <tr><td><code>routes</code></td><td>Defines the URLs and their middleware protection.</td></tr>
        <tr><td><code>public</code></td><td>Contains the web entry point and public assets.</td></tr>
        <tr><td><code>Dockerfile</code></td><td>Defines the production application image for Render.</td></tr>
        <tr><td><code>render.yaml</code></td><td>Documents the Render service and required environment variables.</td></tr>
        <tr><td><code>tests</code></td><td>Contains automated checks for application behaviour.</td></tr>
    </table>

    <div class="page-break"></div>
    <h2>6. Database design</h2>
    <p>The database uses relational tables because the project contains connected information. A business belongs to a user and may optionally be linked to a structured district. Demand and production belong to a district and product. A manufacturing gap is calculated from those records.</p>
    <table>
        <tr><th>Table</th><th>Main purpose</th><th>Important information</th></tr>
        <tr><td>users</td><td>Account identity and access.</td><td>Name, email, password hash, role.</td></tr>
        <tr><td>invitations</td><td>Private onboarding.</td><td>Email, secure token, expiry, accepted timestamp.</td></tr>
        <tr><td>businesses</td><td>MSME directory.</td><td>Name, state, district, locality, industry, contact, coordinates, verification state, owner.</td></tr>
        <tr><td>districts</td><td>Structured geographic and industrial areas.</td><td>Name, state, latitude, longitude.</td></tr>
        <tr><td>products</td><td>Product taxonomy.</td><td>Name, category, unit, optional HSN code.</td></tr>
        <tr><td>market_demand</td><td>Demand signals.</td><td>District, product, period, quantity, source, verification state.</td></tr>
        <tr><td>production_capacity</td><td>Local production.</td><td>District, product, business, installed capacity, actual production, quantity, period.</td></tr>
        <tr><td>manufacturing_gaps</td><td>Calculated opportunity.</td><td>District, product, estimated gap, score, breakdown, barriers, status.</td></tr>
    </table>
    <h3>Why migrations matter</h3>
    <p>Each schema change is represented by a migration. This means a new environment can recreate the expected structure in the correct order, instead of relying on manual database editing. It also gives the team a documented history of how the product evolved.</p>
    <h3>Data integrity</h3>
    <ul>
        <li>Foreign keys connect related records.</li>
        <li>Validation prevents invalid UUIDs, emails, coordinates, quantities, and phone values.</li>
        <li>Indexes support common district, status, location, and score lookups.</li>
        <li>Verification states separate submitted information from trusted public information.</li>
        <li>Nullable legacy district links allow pan-India user-entered locations without breaking the pilot dataset.</li>
    </ul>

    <h2>7. Main business workflow</h2>
    <h3>7.1 Private onboarding</h3>
    <ol>
        <li>An administrator enters a person’s email address.</li>
        <li>The system creates a random invitation token with a seven-day expiry.</li>
        <li>The administrator sends the generated private link.</li>
        <li>The invited person must use the matching email address.</li>
        <li>The token is marked as accepted after successful registration.</li>
        <li>The new user receives member access and can submit an MSME profile.</li>
    </ol>
    <h3>7.2 MSME registration</h3>
    <p>The user enters the enterprise name, state, district, locality, industry, registration number, contact information, description, and coordinates. The map can use device location, a clicked point, or a dragged marker. Reverse location lookup fills the address fields, but the user can correct them.</p>
    <h3>7.3 Verification</h3>
    <p>A new business starts as pending. Administrators review the information and either approve or reject it. Only approved records appear in the verified business directory. This protects the directory from spam and inaccurate public claims.</p>
    <h3>7.4 Demand and supply workflow</h3>
    <p>An authorised user enters demand for a product and period. Production records capture installed capacity and actual production. The calculation service compares the quantities and creates or updates a gap when demand is greater than supply.</p>

    <h2>8. Important calculation logic</h2>
    <div class="code">Demand = total reported demand for a product, district, and period
Supply = total recorded local production for the same product, district, and period
Estimated gap = maximum of zero and (Demand - Supply)

If Estimated gap is greater than zero:
    save the opportunity gap
    mark it publishable for the opportunity workflow
    calculate the opportunity score</div>
    <p>The calculation is intentionally understandable. It avoids showing a negative shortage when production is greater than demand. The score uses multiple business factors so a large demand number is not the only consideration.</p>
    <h3>Efficiency of the calculation</h3>
    <ul>
        <li>Database aggregation is used for demand and production totals.</li>
        <li>Gap creation uses update-or-create behaviour to avoid duplicate gap rows for the same district and product.</li>
        <li>Dashboard data is cached for a short period to reduce repeated database work.</li>
        <li>Relationships are eager-loaded where pages need related districts, products, or owners.</li>
    </ul>

    <h2>9. Authentication and security design</h2>
    <table>
        <tr><th>Control</th><th>Implementation</th><th>Purpose</th></tr>
        <tr><td>Private application</td><td>Main routes use authentication middleware.</td><td>Unknown visitors cannot access project data.</td></tr>
        <tr><td>Invite-only registration</td><td>Registration requires a matching, unexpired, unused token.</td><td>Only allowed people can create accounts.</td></tr>
        <tr><td>Password hashing</td><td>Laravel Hash is used before storing passwords.</td><td>Plain passwords are not stored in the database.</td></tr>
        <tr><td>Session protection</td><td>Session regeneration after login and secure Render cookies.</td><td>Reduces session fixation and insecure transport risk.</td></tr>
        <tr><td>Admin middleware</td><td>Admin actions require the admin role.</td><td>Members cannot approve businesses or create invitations.</td></tr>
        <tr><td>Login throttling</td><td>Five login attempts per minute.</td><td>Reduces simple password-guessing attacks.</td></tr>
        <tr><td>CSRF protection</td><td>Forms include Laravel CSRF tokens.</td><td>Protects state-changing form submissions.</td></tr>
        <tr><td>Secret separation</td><td>Environment values stay outside Git.</td><td>Database passwords and app keys are not published.</td></tr>
    </table>
    <div class="warning"><strong>Production reminder:</strong> use a private administrator email, a unique long password, a new production APP_KEY, a rotated database password, HTTPS, backups, and restricted database access. Never paste secrets into source files, PDFs, chat, or public repositories.</div>

    <h2>10. Privacy and verification model</h2>
    <p>The project distinguishes between submitted information and verified information. A business may be stored for review, but it is not treated as publicly trusted until an administrator approves it.</p>
    <ul>
        <li>Owner information is connected to the authenticated account.</li>
        <li>Pending profiles are limited to their owner and administrators.</li>
        <li>Public directory results use verified records only.</li>
        <li>Precise map locations should be collected only with the business owner’s consent.</li>
        <li>Contact data should be used for genuine business communication, not unsolicited marketing.</li>
    </ul>

    <div class="page-break"></div>
    <h2>11. Page and user experience structure</h2>
    <table>
        <tr><th>Page area</th><th>Purpose</th><th>Who uses it</th></tr>
        <tr><td>Login</td><td>Secure entry for invited members and administrators.</td><td>All authorised users.</td></tr>
        <tr><td>Dashboard</td><td>Summary counts, map, and opportunity pipeline.</td><td>Members, analysts, administrators.</td></tr>
        <tr><td>MSME directory</td><td>Search verified enterprises.</td><td>Authenticated buyers and partners.</td></tr>
        <tr><td>MSME profile</td><td>View capability, contact, and exact location.</td><td>Authenticated users.</td></tr>
        <tr><td>Registration form</td><td>Submit a new enterprise for review.</td><td>Invited members.</td></tr>
        <tr><td>Demand form</td><td>Record a local need.</td><td>Authorised data contributors.</td></tr>
        <tr><td>Supply form</td><td>Record capacity and actual production.</td><td>Authorised data contributors.</td></tr>
        <tr><td>Comparison</td><td>Compare selected districts.</td><td>Analysts and planning teams.</td></tr>
        <tr><td>Admin review</td><td>Approve or reject business profiles.</td><td>Administrators only.</td></tr>
        <tr><td>Admin invitations</td><td>Control who joins the private platform.</td><td>Administrators only.</td></tr>
    </table>

    <h2>12. Start-to-finish local setup</h2>
    <h3>Prerequisites</h3>
    <ul>
        <li>PHP 8.2 or later.</li>
        <li>Composer.</li>
        <li>Node.js and npm.</li>
        <li>PostgreSQL or Supabase.</li>
        <li>Git.</li>
    </ul>
    <h3>Commands</h3>
    <div class="code">git clone https://github.com/MishraJi-Devloper/bharat-gap-finder.git
cd bharat-gap-finder
composer install
copy .env.example .env
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan serve</div>
    <p>After the server starts, open the local URL shown by Laravel. The private application requires an administrator account before invitations can be created.</p>
    <h3>First administrator</h3>
    <div class="code">php artisan tinker

App\Models\User::create([
    'name' => 'Private Administrator',
    'email' => 'your-private-email@example.com',
    'password' => Illuminate\Support\Facades\Hash::make('use-a-long-private-password'),
    'role' => 'admin',
]);</div>
    <p>The actual email and password must be selected privately and must never be committed.</p>

    <h2>13. Deployment process</h2>
    <p>The current deployment approach packages the application as a Docker image and hosts it on Render. Render supplies the public HTTPS URL and runs the container. Supabase supplies the managed PostgreSQL database.</p>
    <h3>Deployment stages</h3>
    <ol>
        <li>Push source code to GitHub.</li>
        <li>Connect the repository to a Render Blueprint.</li>
        <li>Render reads <code>render.yaml</code> and builds the Docker image.</li>
        <li>Composer installs PHP dependencies.</li>
        <li>npm builds browser assets.</li>
        <li>Apache serves Laravel’s public directory.</li>
        <li>The container runs migrations and caches configuration, routes, and views.</li>
        <li>Render checks the health route and provides an HTTPS URL.</li>
    </ol>
    <h3>Required production values</h3>
    <table>
        <tr><th>Value</th><th>Where it belongs</th><th>Why it matters</th></tr>
        <tr><td>APP_KEY</td><td>Render environment settings.</td><td>Encrypts application data and sessions.</td></tr>
        <tr><td>APP_URL</td><td>Render environment settings, using HTTPS.</td><td>Controls secure generated links.</td></tr>
        <tr><td>Database credentials</td><td>Render secret environment settings.</td><td>Connects the app to Supabase without publishing credentials.</td></tr>
        <tr><td>APP_DEBUG=false</td><td>Render environment settings.</td><td>Prevents detailed exception data from being shown publicly.</td></tr>
        <tr><td>SESSION_SECURE_COOKIE=true</td><td>Render environment settings.</td><td>Restricts session cookies to HTTPS.</td></tr>
    </table>

    <h2>14. Why the code is efficient</h2>
    <p>The code is efficient in several practical ways:</p>
    <ul>
        <li>Dashboard data is cached for ten minutes, reducing repeated database queries.</li>
        <li>District counts and business counts are loaded through database relationships instead of repeated per-row queries.</li>
        <li>Opportunity candidates are filtered by district and industry rather than loading unrelated businesses.</li>
        <li>Paginated MSME directory results prevent every record from being loaded at once.</li>
        <li>Search is performed by the database rather than by downloading the complete directory into the browser.</li>
        <li>Indexes exist for common foreign keys, verification status, locality, state, district, and score lookups.</li>
        <li>Vite creates a production asset bundle instead of serving development assets.</li>
        <li>Docker makes the runtime repeatable for deployment.</li>
    </ul>
    <h3>Comparison with a basic implementation</h3>
    <table>
        <tr><th>Basic approach</th><th>This project’s approach</th><th>Result</th></tr>
        <tr><td>One large form with no account control.</td><td>Invited accounts, ownership, roles, and verification states.</td><td>Better trust and accountability.</td></tr>
        <tr><td>All businesses loaded on one page.</td><td>Database search and pagination.</td><td>Better performance as records grow.</td></tr>
        <tr><td>Calculations inside templates.</td><td>Dedicated calculation and scoring services.</td><td>Easier testing and maintenance.</td></tr>
        <tr><td>Manual database changes.</td><td>Ordered migrations and seed data.</td><td>Repeatable deployments.</td></tr>
        <tr><td>Plain password storage.</td><td>Laravel hashing and session controls.</td><td>Much safer credential handling.</td></tr>
        <tr><td>Uncontrolled public submissions.</td><td>Private invitations and administrator review.</td><td>Lower spam and data-quality risk.</td></tr>
    </table>
    <div class="warning"><strong>Important evaluation limit:</strong> efficiency claims should not be presented as measured benchmark results until the project has realistic load tests, database query profiling, and production traffic measurements. The current design is efficient by structure and intent, but exact speed depends on database size, hosting resources, network conditions, and map-provider response time.</div>

    <h2>15. Testing and quality process</h2>
    <p>The project currently verifies that the application boots and that private route behaviour is compatible with the test database. A stronger production quality process should add:</p>
    <ul>
        <li>Authentication tests for valid and invalid login.</li>
        <li>Invitation expiry, email matching, reuse, and role tests.</li>
        <li>Admin-only approval and invitation tests.</li>
        <li>MSME validation tests for invalid phones, coordinates, and registration data.</li>
        <li>Demand and supply calculation tests with known expected gaps.</li>
        <li>PDF generation tests.</li>
        <li>Browser tests for responsive registration, map selection, and directory search.</li>
        <li>Load tests for dashboard queries and directory search.</li>
    </ul>

    <h2>16. Current strengths</h2>
    <ul>
        <li>Clear business purpose connected to a real social and economic problem.</li>
        <li>Relational data design that supports demand, supply, businesses, and opportunities together.</li>
        <li>Pan-India business registration instead of being limited to seeded districts.</li>
        <li>Precise map location with automatic address assistance.</li>
        <li>Private-by-default access and administrator-controlled invitations.</li>
        <li>Verification before business profiles are publicly trusted.</li>
        <li>Separate services for the central calculations.</li>
        <li>Production Docker and Render deployment configuration.</li>
        <li>Shareable project and opportunity PDF outputs.</li>
    </ul>

    <h2>17. Current limitations and honest next improvements</h2>
    <ul>
        <li>The initial test suite is small and should grow with the security and data workflows.</li>
        <li>Email delivery is not yet configured; administrators currently copy invitation links manually.</li>
        <li>Business verification is administrative review, not government verification.</li>
        <li>The first dashboard dataset is a pilot dataset and should be expanded with reliable national sources.</li>
        <li>Demand quality depends on the accuracy and responsibility of contributors.</li>
        <li>Map and geocoding services have usage policies and network dependencies.</li>
        <li>Render’s free service may sleep after inactivity and is best for demonstration or early pilots.</li>
        <li>For larger scale, queues, object storage, monitoring, backups, and stronger moderation workflows should be added.</li>
    </ul>

    <h2>18. Production roadmap</h2>
    <table>
        <tr><th>Stage</th><th>Focus</th><th>Expected result</th></tr>
        <tr><td>Stage 1</td><td>Private pilot with invited MSMEs and buyers.</td><td>Validate the workflow and data quality.</td></tr>
        <tr><td>Stage 2</td><td>Admin moderation, reports, email invitations, and stronger tests.</td><td>Operate with a small trusted network.</td></tr>
        <tr><td>Stage 3</td><td>State-wise partnerships and reliable demand sources.</td><td>Improve coverage and trust.</td></tr>
        <tr><td>Stage 4</td><td>Paid reports, buyer services, and institutional subscriptions.</td><td>Create sustainable income.</td></tr>
        <tr><td>Stage 5</td><td>National scale, monitoring, queues, backups, and dedicated support.</td><td>Operate as a dependable public platform.</td></tr>
    </table>

    <h2>19. Final assessment</h2>
    <p>Bharat Manufacturing Gap Finder has a strong foundation for a serious product because it connects a clear problem to a practical workflow. It is more than a static directory: it combines local demand, production capacity, opportunity calculation, verified business profiles, precise locations, and controlled access.</p>
    <p>The most important next step is not adding decorative features. It is improving the quality of data, onboarding trusted contributors, measuring real usage, and building institutional relationships. If those parts are developed carefully, the platform can help local people make better manufacturing decisions and help regions retain more economic activity.</p>

    <div class="footer">Bharat Manufacturing Gap Finder - Detailed Technical Project Report. Prepared for technical review and team selection.</div>
</body>
</html>
