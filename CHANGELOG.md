## Not Released

### Added
- [#5](https://github.com/lugark/CalendarGenerator/pull/5) - Use [aeon-php/calendar](https://github.com/aeon-php/calendar) instead of own objects

### Changed
- [#5](https://github.com/lugark/CalendarGenerator/pull/5) - Changed renderer to not calculate the calender but use aeon-php/calendar to iterate
- [#5](https://github.com/lugark/CalendarGenerator/pull/5) - Refactored Render-Workflow
    - **Renderer\RenderRequest** to pass the request to other services
    - **Renderer\RenderInformation** seperated to support differnt types to render (landscape, portrait etc.)
    