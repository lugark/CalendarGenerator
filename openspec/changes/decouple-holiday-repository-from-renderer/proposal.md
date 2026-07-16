## Why

`HolidaysRepository` currently converts stored holiday data straight into renderer-library events. That couples persistence to the PDF renderer API and makes the repository harder to reuse if the calendar generation pipeline changes.

## What Changes

- Move renderer-specific event construction out of the repository.
- Let the repository return app-owned holiday data or DTOs instead of renderer objects.
- Introduce a dedicated mapper or factory for converting holiday data into renderer events.
- Keep storage concerns separate from rendering concerns.

## Capabilities

### New Capabilities
- `holiday-to-renderer-mapping`: Converts stored holiday records into renderer-library events outside the repository layer.

### Modified Capabilities
- None

## Impact

- `src/Repository/HolidaysRepository.php` stops depending on renderer event classes.
- Mapping code becomes independently testable and reusable.
- Storage formats remain unchanged while the rendering adapter becomes replaceable.
- Tests should focus on repository output and mapping behavior as separate concerns.

