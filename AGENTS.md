# CalendarGenerator Agent Guide

## Project Snapshot
- PHP/Symfony console project for generating printable PDF calendars with German public holidays and school holidays.
- Runtime target is PHP 8.2 with Symfony 7.1 components. PHP 8.2 syntax and readonly/promoted properties are acceptable when consistent with touched code.
- Main code lives in `src/`; tests live in `tests/`; holiday fixture/data files live in `data/`.
- PDF calendar rendering is delegated to `lugark/calendar-pdf-renderer`; this application should not contain renderer layout logic.
- CalendarGenerator uses native PHP dates at command/API boundaries and maps stored holiday arrays into `Calendar\Pdf\Renderer\Event\Event` objects for the renderer library.
- The renderer library uses Carbon (`CarbonInterface`/`CarbonPeriod`) internally for event ranges and render periods.

## Useful Commands
- Install dependencies: `composer install`
- Run all checks: `composer test`
- Run PHPUnit with coverage/JUnit reports: `composer phpunit`
- Run static analysis: `composer phpstan`
- Run coding standards: `composer check-cs`
- Fix coding standards: `composer fix-cs`
- Run a console command: `bin/console <command>`
- Generate a calendar: `bin/console calendar:generate 2020-01`
- Generate with public holidays: `bin/console calendar:generate --publicholidays BY 2020-01`
- Fetch public holidays: `bin/console calendar:fetch:holidays --year 2021 public`
- Fetch school holidays: `bin/console calendar:fetch:holidays --year 2021 school`

## Architecture Notes
- `src/ApiDataLoader` fetches and transforms external holiday/vacation API data.
- `src/Repository` reads stored holiday data and maps it to renderer library events.
- `src/Service/Storage` serializes/deserializes cached holiday data with MessagePack.
- `src/Command` contains Symfony Console entry points and should stay thin.
- `lugark/calendar-pdf-renderer` owns PDF renderer types such as `CalendarRenderer`, `PdfRenderer`, `RenderRequest`, `LandscapeYear`, `Events`, and `Event`.
- `vendor/` should contain `lugark/calendar-pdf-renderer` and `nesbot/carbon`; `aeon-php/calendar` should not be installed for this project.
- See [docs/architecture/renderer-boundary.md](/Users/mathias.kuehn/priv-sources/CalendarGenerator/docs/architecture/renderer-boundary.md) for the current boundary analysis and layout expansion guidance.

## Coding Guidelines
- Preserve the existing code style unless touching a broader area for a clear reason.
- Keep code compatible with PHP 8.2 and Symfony 7.1.
- Prefer existing repository/service boundaries over adding logic to commands.
- Do not reintroduce `aeon-php/calendar` into CalendarGenerator; date and layout behavior needed for PDF rendering belongs behind `lugark/calendar-pdf-renderer`.
- Avoid adding direct Carbon dependencies to application code unless the renderer library API requires them; keep Carbon usage isolated to renderer objects where possible.
- Avoid network calls in tests; use existing fixtures under `tests/**/fixtures` or add focused fixtures when needed.
- When changing data loading, storage, renderer event mapping, or command integration, add or update focused PHPUnit coverage.
- Do not overwrite generated data files in `data/` unless the task explicitly asks for refreshed holiday data.

## Working With This Repo
- Check `git status --short` before editing. The worktree may contain user changes; never revert unrelated changes.
- If a file is already modified, inspect it before editing and keep user changes intact.
- Use `rg`/`rg --files` for code search.
- Use `apply_patch` for manual edits.
- Run the narrowest relevant checks after a change, then broaden to `composer test` when the touched area is shared or risky.

## Review Checklist
- Date range behavior is inclusive/exclusive exactly as intended.
- Federal-state holiday codes such as `BY` stay uppercase and validated where appropriate.
- External API changes are isolated behind loaders/transformers.
- Render changes stay at the `lugark/calendar-pdf-renderer` boundary and do not require live API data.
- Tests cover edge cases around missing end dates, empty event lists, serialization, and invalid input.
