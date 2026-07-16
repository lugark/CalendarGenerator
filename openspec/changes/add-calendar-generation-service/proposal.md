## Why

CalendarGenerator currently wires fetching, event mapping, layout selection, and renderer invocation directly inside the console command. That makes the flow hard to extend once more layouts or setup variants are added.

## What Changes

- Introduce an application-level service that owns calendar generation orchestration.
- Move request assembly, event loading, and renderer invocation out of the console command.
- Keep the command thin so it only parses input and delegates work.
- Preserve the existing renderer library as the place where PDF output is produced.

## Capabilities

### New Capabilities
- `calendar-generation-orchestration`: Coordinates data loading, layout selection, render request setup, and output generation behind a reusable application service.

### Modified Capabilities
- None

## Impact

- `src/Command/CalendarGenerateCommand.php` becomes a thin entry point.
- A new application service layer will own generation flow and dependency wiring.
- Layout and renderer setup become reusable outside the console command.
- Tests should move toward service-level orchestration coverage instead of command-only coverage.

