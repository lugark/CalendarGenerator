# CalendarGenerator Agent Guide

## Project Snapshot
- PHP/Symfony console project for generating printable PDF calendars with German public holidays and school holidays.
- Runtime target is PHP 7.4 with Symfony 5.4 components. Keep syntax and dependencies compatible with that target.
- Main code lives in `src/`; tests live in `tests/`; holiday fixture/data files live in `data/`.
- Calendar dates are represented mostly with `aeon-php/calendar` types inside the domain and native `\DateTime` at command/API boundaries.

## Useful Commands
- Install dependencies: `composer install`
- Run all tests: `bin/phpunit`
- Run one test file: `bin/phpunit tests/Calendar/EventTest.php`
- Run a console command: `bin/console <command>`
- Generate a calendar: `bin/console calendar:generate 2020-01`
- Generate with public holidays: `bin/console calendar:generate --publicholidays BY 2020-01`
- Fetch public holidays: `bin/console calendar:fetch:holidays --year 2021 public`
- Fetch school holidays: `bin/console calendar:fetch:holidays --year 2021 school`

## Architecture Notes
- `src/Calendar` contains event models and collections.
- `src/ApiDataLoader` fetches and transforms external holiday/vacation API data.
- `src/Repository` reads holiday data for rendering.
- `src/Renderer` turns render requests and events into PDF output.
- `src/Service/Storage` serializes/deserializes cached holiday data with MessagePack.
- `src/Command` contains Symfony Console entry points and should stay thin.

## Coding Guidelines
- Preserve the existing code style unless touching a broader area for a clear reason.
- Keep code compatible with PHP 7.4; do not use PHP 8-only syntax.
- Prefer explicit domain objects and existing repository/renderer/service boundaries over adding logic to commands.
- Avoid network calls in tests; use existing fixtures under `tests/**/fixtures` or add focused fixtures when needed.
- When changing data loading, storage, render requests, or event date logic, add or update focused PHPUnit coverage.
- Do not overwrite generated data files in `data/` unless the task explicitly asks for refreshed holiday data.

## Working With This Repo
- Check `git status --short` before editing. The worktree may contain user changes; never revert unrelated changes.
- If a file is already modified, inspect it before editing and keep user changes intact.
- Use `rg`/`rg --files` for code search.
- Use `apply_patch` for manual edits.
- Run the narrowest relevant tests after a change, then broaden to `bin/phpunit` when the touched area is shared or risky.

## Review Checklist
- Date range behavior is inclusive/exclusive exactly as intended.
- Federal-state holiday codes such as `BY` stay uppercase and validated where appropriate.
- External API changes are isolated behind loaders/transformers.
- Render changes do not require live API data.
- Tests cover edge cases around missing end dates, empty event lists, serialization, and invalid input.
