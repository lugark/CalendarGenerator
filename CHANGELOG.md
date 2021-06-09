## 0.3.0 (2021-06-09)
### Added
- [#4](https://github.com/lugark/CalendarGenerator/pull/4) - fetch german school vacations from [mehr-schulferien.de](https://www.mehr-schulferien.de/)

### Changed
- [#4](https://github.com/lugark/CalendarGenerator/pull/4) - refactored data loading for holidays & vacations    
    - APILoader uses tagged services to load & transform data
    - fetching events splitted into loaders and transformers

## 0.2.0 (2021-05-27)
### Added
- [#5](https://github.com/lugark/CalendarGenerator/pull/5) - Use [aeon-php/calendar](https://github.com/aeon-php/calendar) instead of own objects

### Changed
- [#5](https://github.com/lugark/CalendarGenerator/pull/5) - Changed renderer to not calculate the calender but use aeon-php/calendar to iterate
- [#5](https://github.com/lugark/CalendarGenerator/pull/5) - Refactored Render-Workflow
    - **Renderer\RenderRequest** to pass the request to other services
    - **Renderer\RenderInformation** seperated to support differnt types to render (landscape, portrait etc.)
    