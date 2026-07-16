# Renderer Boundary

## Befund

CalendarGenerator is now mostly an orchestration layer. The actual PDF rendering logic lives in `lugark/calendar-pdf-renderer`, while this repository should focus on:

- loading and storing holiday data
- mapping stored holiday records into renderer events
- selecting layout, date range, and output settings
- invoking the renderer library

The current integration already reflects that split, but the command layer still wires renderer classes together directly. That is acceptable for one layout, but it becomes a scaling limit once more layouts are added.

## What Belongs Where

### CalendarGenerator

- command parsing and validation
- holiday fetch orchestration
- storage access
- event mapping for renderer input
- layout selection by alias or config key
- render request setup

### `lugark/calendar-pdf-renderer`

- PDF generation
- layout-specific rendering logic
- month/day iteration
- weekend styling
- event positioning
- renderer-specific dimensions and output

## Current Risks

- Layout classes are still referenced directly from the command layer.
- `HolidaysRepository` currently returns renderer-library event objects instead of app-owned DTOs.
- The renderer boundary is clear, but the orchestration boundary is not yet modelled as a separate service.
- Adding a new layout will likely require touching the command unless a registry or factory is introduced.

## Recommended Direction

1. Introduce an application-level generation service between the command and the renderer.
2. Replace direct layout class references with layout aliases or a registry.
3. Keep renderer-specific code inside the renderer library.
4. Move repository output toward app-owned DTOs if the storage layer should stay independent from the renderer package.

## Resulting Shape

```text
Command
  -> Generation Service
    -> Layout Registry / Request Factory
      -> calendar-pdf-renderer
```

