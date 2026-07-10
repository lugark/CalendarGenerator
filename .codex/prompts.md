# Codex Prompts for CalendarGenerator

Use these prompts as starting points when asking Codex to work on this repository.

## Feature Work
```
Implement <feature> in this Symfony/PHP 7.4 project.
Before editing, inspect the relevant classes and tests.
Keep console commands thin, put domain logic in services/repositories/renderers, and add focused PHPUnit tests.
Run the relevant tests and summarize changed files, behavior, and verification.
```

## Bug Fix
```
Find and fix the bug where <symptom>.
Start by reproducing or locating the failing behavior with the narrowest relevant test.
Keep the fix scoped, preserve existing public behavior, and add a regression test.
Run the affected PHPUnit test file and any nearby tests.
```

## Code Review
```
Review the current changes as a senior PHP/Symfony reviewer.
Prioritize bugs, regressions, missing tests, PHP 7.4 compatibility, date range edge cases, and unexpected changes to generated data.
List findings first with file and line references.
```

## Test Expansion
```
Add focused tests for <area>.
Use existing test style and fixtures.
Avoid live network calls.
Cover edge cases around empty data, missing end dates, invalid input, and federal-state handling where relevant.
```

## Refactor
```
Refactor <area> without changing behavior.
First identify current responsibilities and test coverage.
Keep public interfaces stable unless a change is explicitly needed.
Run tests before and after where practical, and call out any behavior that was intentionally preserved.
```
