# Repository Guidelines

## Project Structure & Module Organization
The workspace separates concerns into two top-level folders. `backend/` hosts the Laravel 12 API, with HTTP controllers under `app/Http`, database migrations and seeders inside `database/`, and API routes defined in `routes/api.php`. Single-feature tests live in `backend/tests/Feature` and unit isolates in `backend/tests/Unit`. `frontend/` is a Vite-powered React + TypeScript client: shared components sit in `src/components`, API helpers in `src/services`, and route guards in `src/guards`. Static assets resolve from `frontend/public`. Use `.env.example` (backend) and `frontend/.env` templates before running services.

## Build, Test, and Development Commands
- `cd backend && composer install && npm install` prepares PHP and Vite dependencies; `php artisan migrate --seed` hydrates the MySQL schema.
- `cd backend && composer run dev` launches Sail services, the Laravel server, queue listener, logs, and Vite dashboard together. Prefer this during full-stack work.
- `cd backend && php artisan test` runs the PHPUnit suite after clearing cached config; use `php artisan test --filter=ProfileTest` for targeted runs.
- `cd frontend && npm install` then `npm run dev` serves the SPA at Vite defaults; `npm run build` emits production assets; `npm run preview` verifies the build.

## Coding Style & Naming Conventions
Backend PHP follows PSR-12 via Laravel Pint; run `cd backend && ./vendor/bin/pint` before opening a PR. Controllers, jobs, and requests use Laravel’s StudlyCase naming (`UserProfileController`). React source uses TypeScript modules with PascalCase components under `src/components`, camelCase utilities (`useAuthGuard.ts`), and Tailwind for styling. ESLint (`npm run lint`) enforces import order, hooks rules, and TypeScript strictness on the frontend.

## Testing Guidelines
Aim to cover new Laravel endpoints with Feature tests named `*Test.php` inside domain folders (for example, `tests/Feature/Api/AccountTest.php`) and isolate pure logic in Unit tests. Seed fake data with factories inside tests rather than relying on migrations. Frontend automated tests are not yet configured—document manual QA steps in the PR template and consider adding Playwright or Vitest cases when introducing critical flows.

## Commit & Pull Request Guidelines
Commits in history use short Conventional Commit–style prefixes, often in Spanish (`feat(perfil): descripción breve`). Keep the first line under 72 characters and describe user-facing impact. For pull requests, include: (1) a summary of the change and affected modules, (2) linked issue or Trello card, (3) screenshots or GIFs for UI updates, and (4) a checklist of backend/frontend tests executed. Request at least one review and wait for CI or manual test confirmation before merging into `develop`.
