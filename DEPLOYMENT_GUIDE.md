# Online Food Blog deployment

Deployment order: **Supabase → Render → edit Render URL and push → Vercel**. GitHub `main` is the production branch. The PHP app lives on Render; Vercel serves only the startup page and proxies the other paths.

## 1. Local run

Requires PHP 8.3+ with `pdo_pgsql`, `curl` and `fileinfo`, plus a PostgreSQL database. Run `database.pgsql.sql` in that database, then optionally `database.seed.pgsql.sql`. Set environment variables from `.env.example` in your shell (the app does not load `.env` automatically). For example, in PowerShell:

```powershell
$env:DATABASE_URL = 'postgresql://USER:URL_ENCODED_PASSWORD@HOST:5432/postgres?sslmode=require'
$env:BASE_URL = ''
$env:REMEMBER_SECRET = 'your-unique-long-random-secret'
php -S localhost:8000 -t .
```

Open `http://localhost:8000/index.php`. Local uploads go to `public/uploads/` unless Supabase Storage variables are set. If using XAMPP under `/project3`, set `BASE_URL=/project3` instead.

## 2. Supabase

Create a Supabase project. In **SQL Editor**, execute `database.pgsql.sql`; optionally execute `database.seed.pgsql.sql` once for sample restaurants and dishes. Do not import the old MySQL `database.sql`. In **Storage**, create a **public** bucket named `food-blog` with a 2 MB file limit and JPEG/PNG allowed. The PHP server uploads with the server-only service role key; never put that key in Vercel or browser code.

Copy the database connection URI from Supabase **Connect** (direct or session pooler, port 5432). Use `DATABASE_URL` with URL-encoded special characters in its password and `sslmode=require`. Alternatively set `DB_HOST`, `DB_PORT=5432`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_SSLMODE=require`. `DATABASE_URL` takes precedence. For Storage also copy `SUPABASE_URL` and the service role key from Supabase project settings.

## 3. Render

Create a **Web Service** from this GitHub repository, branch `main`, runtime **Docker**, Dockerfile `Dockerfile`, and automatic deploy on commit. You may use `render.yaml` as a Blueprint instead. Set the health check path to `/health.php`. Set these **Render-only** environment variables:

| Variable | Value |
| --- | --- |
| `DATABASE_URL` | Supabase URI with `sslmode=require` (or the six `DB_*` variables above) |
| `REMEMBER_SECRET` | Unique long random string |
| `SUPABASE_URL` | `https://YOUR_PROJECT.supabase.co` |
| `SUPABASE_SERVICE_ROLE_KEY` | Supabase server-only service role key |
| `SUPABASE_STORAGE_BUCKET` | `food-blog` |
| `BASE_URL` | Empty string, if you explicitly set it |

Deploy and confirm `https://YOUR_RENDER_HOST.onrender.com/health.php` returns `{"status":"ok"}` with HTTP 200, and `/index.php` opens. Render supplies `PORT`; `docker/start.sh` binds Apache to it. `BASE_URL` defaults to empty on Render. Register your own user at `/index.php?route=%2Fregister`, then in Supabase SQL Editor promote only that account:

```sql
UPDATE users SET role = 'admin' WHERE email = 'YOUR_EMAIL@example.com' AND role = 'member';
```

Sign out and sign in again to refresh the session role. Public registration cannot grant admin access.

## 4. Vercel

After Render has a real URL, replace `https://REPLACE_WITH_RENDER_HOST.onrender.com` in `vercel-proxy/vercel.json` with the exact HTTPS Render origin, **without a trailing slash**. Commit and push this one-line change to GitHub `main`. In Vercel, import the same GitHub repository as a static/Other project and set **Root Directory** to `vercel-proxy`; leave build command empty. Enable GitHub automatic deployments from `main` and deploy. Vercel `/` shows `vercel-proxy/index.html`; every non-root path proxies to Render. The page checks `/health.php` and `/index.php`, and redirects to `/index.php` after both return HTTP 200 twice consecutively.

## Troubleshooting

| Symptom | Check |
| --- | --- |
| 502/startup page loops | Open Render `/health.php` and `/index.php` directly; inspect Render logs; verify Vercel `vercel.json` origin and redeploy. Startup retries every 5 seconds. |
| Health 503/database error | Check Supabase project status, URI/`DB_*`, URL-encoded password, port 5432, `sslmode=require`, and schema import. |
| Render port error | Keep `PORT` supplied by Render; `docker/start.sh` must bind Apache to it. |
| Login/session resets | Use the Vercel URL consistently; verify HTTPS cookies, `REMEMBER_SECRET`, and that the Render instance has not restarted. File sessions reset on restart/sleep. |
| CSS/JS/images missing | Verify `BASE_URL` is empty on Render, `/public/css/` and `/public/js/` load, Storage bucket is public, and Storage variables are set. Linux filenames are case-sensitive. |
| Upload fails | Verify service role key is only in Render, bucket name and 2 MB/JPEG/PNG settings, and Render logs. |

[Render Free services sleep after inactivity](https://render.com/docs/free). The startup page handles wake-up 502 responses, but a paid Render service is required for true always-on availability.
