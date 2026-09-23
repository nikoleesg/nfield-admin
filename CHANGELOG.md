# Changelog

All notable changes to `nfield-admin` will be documented in this file.

## [Unreleased]

The v2 rewrite, plus the foundation work that standardised it. The package is a
pure SDK now: no migrations, no views, no commands, no v1 stack.

### Added

- **CAPI interviewers** — list, find, create, read, update, reset password,
  delete, assignments and fieldwork offices, with a fluent
  `withCapiInterviewer()` resource.
- **Blueprint surveys** — `createSurveyFromBlueprint()` and
  `withBlueprintSurvey()->update()`.
- **Publishing and public ids** — publish state, live/test/force publish,
  `startPublish` as a background activity, and the public id list.
- **Survey settings** — the settings list, `set()` by name or
  `SurveySettingNameEnum`, and the general settings read/update.
- **Quota** — the quota frame, quota targets (current and by eTag) and quota
  versions, across three endpoint classes behind one `quota()` service.
- **Sampling points** — create, read, update, delete, activate, replace, batch
  activation of spares, addresses, per-point quota targets, and per-survey mass
  assignment of interviewers.
- **Sample** — download and parse, multipart upload, create columns, block,
  reset, clear, per-interview read and update, and asynchronous download
  requests.
- **Typed exceptions** — `AuthenticationException`, `NotFoundException`,
  `ValidationException`, all extending `ApiRequestException`, which carries the
  status and the response body.
- **Scoping contracts** — `SurveyScopedInterface` / `SamplingPointScopedInterface`
  with `MissingScopeException` when a scoped call is made before its scope is
  set.
- A baseline test suite: 359 tests covering `HttpClient`, every v2 endpoint
  method, every service and resource, the request models, the enums and the
  container bindings.

### Changed

- **Responses are normalised once, at the HTTP boundary.** The API returns
  PascalCase; `ResponseKeyNormalizer` lower-cases the first letter of every key
  recursively, so endpoints, services and DTOs only ever see camelCase. No DTO
  carries a casing mapper any more.
- **Every service method takes `array|RequestModel`** and normalises through the
  request model, so one class owns each wire format.
- **Every service method returns a DTO** or an `Illuminate\Support\Collection`
  of DTOs. `DataCollection` and raw-array returns are gone.
- **Scope is declared, not injected by parameter name.** Survey- and
  sampling-point-scoped services dropped their `$surveyId` constructor argument
  for the scoping contracts' setters.
- **One endpoint class per spec path prefix**, split into Collection/Item only
  where the `{id}` identifies a distinct child resource. 22 endpoint classes
  became 29.
- **Fluent setters return `static`**, and a method returning a scoped resource
  is named for it: `->samplingPoints()->for('sp-id')` is now
  `->forSamplingPoint('sp-id')`, alongside `forSurvey()`, `forInterviewer()`,
  `forAddress()` and `forInterview()`.
- **Models are named `*Model`.** The six CAPI interviewer `*Data` classes were
  renamed (`CapiInterviewerData` → `CapiInterviewerModel`, and so on); the
  `*DTO` and `*Data` suffixes are retired.
- Token caching uses the configured cache store — `NFIELD_CACHE_STORE`, or the
  application's default — instead of requiring redis, and honours the API's
  `expiresIn` up to the configured `ttl`.
- A 401 is retried once with a fresh token before it surfaces.
- `composer.json` no longer hard-codes `version`; the git tag is the source of
  truth. `minimum-stability` is `stable`.

### Removed

- The entire v1 stack: `Endpoints/v1`, `Services/v1`, and the 19 v1-era DTOs
  left behind by it.
- `Helper.php` and its global constants and function.
- The skeleton leftovers: the unregistered `NfieldAdminCommand`, the
  commented-out `ModelFactory`, the empty `resources/views` and its
  `->hasViews()` registration, and the unused `table_prefix` config key.
- 12 dead legacy DTOs (`AddressDTO`, `BackgroundActivityDTO`, `SurveyData`,
  `QuotaFrameVersionData`, the four `SurveyQuotaFrame/*Data` classes and the
  rest) and `ChannelEnum`, which only `SurveyData` used.
- The two operations the OpenAPI document marks deprecated are not implemented:
  `POST /v2/surveys/{surveyId}/interviewers` and
  `POST /v2/surveys/{surveyId}/distribute`.

### Fixed

- `HttpClient` built one shared `PendingRequest`, so a body or header from one
  call leaked into the next; each call now builds its own.
- Token caching was silently disabled unless the application's default cache
  store was redis, which re-authenticated on every single request.
- `EndpointPath::join()` was typed `string ...$segments` under
  `strict_types`, so every path built from an integer id threw a `TypeError` on
  the first call — the sample read by interview id and both quota reads by
  eTag.
- The `NfieldManager` facade resolved nothing, because `nfield-manager` was
  never bound.
- `BackgroundActivities` path casing, the `search` query parameter name and a
  hardcoded version prefix in `SurveySettingsEndpoint`, all against the
  OpenAPI document.
- `massUnassign` returned null where an array was declared.
- CSV parsing of UTF-16 and BOM-prefixed sample downloads.

## v1.0.2 - 2023-11-21

### What's Changed

- Dev by @nikoleesg in https://github.com/nikoleesg/nfield-admin/pull/1

### New Contributors

- @nikoleesg made their first contribution in https://github.com/nikoleesg/nfield-admin/pull/1

**Full Changelog**: https://github.com/nikoleesg/nfield-admin/compare/v1.0.1...v1.0.2

## v1.0.1 - 2023-11-07

- add laravel-data dependencies

## v1.0.0 - 2023-11-06

**Full Changelog**: https://github.com/nikoleesg/nfield-admin/commits/v1.0.0

- Add SurveyService
