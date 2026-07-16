## Why

The current generation flow selects the layout by passing a renderer class directly into the request. That couples the command layer to concrete renderer types and makes adding more layouts more brittle than it needs to be.

## What Changes

- Introduce a layout registry or mapping layer in CalendarGenerator.
- Select layouts by stable aliases or configuration keys instead of renderer class names at the command boundary.
- Keep the actual renderer classes inside `lugark/calendar-pdf-renderer`.
- Allow new layouts to be added without changing command parsing logic.

## Capabilities

### New Capabilities
- `layout-registry`: Resolves layout aliases or configuration keys to renderer-library classes and their setup metadata.

### Modified Capabilities
- None

## Impact

- `src/Command/CalendarGenerateCommand.php` no longer needs to know concrete layout classes.
- A registry or factory layer will map user input to renderer library request types.
- New layouts can be added without touching command code.
- Tests should cover alias resolution and layout selection separately from rendering.

