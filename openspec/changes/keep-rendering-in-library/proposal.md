## Why

PDF generation already lives in `lugark/calendar-pdf-renderer`, but the application still documents and wires the renderer boundary in ways that could drift back into local rendering logic. As layouts expand, that boundary needs to stay explicit.

## What Changes

- Keep all PDF rendering, layout calculations, and event drawing inside `lugark/calendar-pdf-renderer`.
- Prevent CalendarGenerator from growing layout-specific rendering code.
- Restrict the application to orchestration, setup, and configuration of render requests.
- Treat renderer classes as implementation details of the library, not of the application.

## Capabilities

### New Capabilities
- `renderer-boundary`: Defines and enforces the contract between CalendarGenerator orchestration code and the renderer library.

### Modified Capabilities
- None

## Impact

- Application code remains focused on data preparation and request setup.
- Renderer-specific behavior stays isolated in the external library.
- Future layouts are added in the renderer library without spreading rendering code into CalendarGenerator.
- Documentation and tests should validate the boundary rather than duplicate renderer behavior.

