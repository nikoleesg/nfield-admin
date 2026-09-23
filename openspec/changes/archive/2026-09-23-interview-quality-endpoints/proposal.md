## Why

The SDK currently lacks support for managing interview quality states. Exposing the `/v2/surveys/{surveyId}/interviewQuality` endpoints will allow users of the SDK to retrieve quality details for interviews and update their quality states programmatically.

## What Changes

- Add new endpoints `SurveyInterviewQualityCollectionEndpoint` (for GET list and PUT update) and `SurveyInterviewQualityEndpoint` (for GET single item) under `src/Endpoints/v2/`.
- Introduce DTOs mapping to the NField API schemas (`InterviewDetailsData`, `ManagerInterviewDetailsData`, `QualityNewStateChangeData`, and an `InterviewQualityEnum`).
- Add a new `SurveyInterviewQualityService` to handle the business logic and DTO conversions.
- Wire the new service into the fluent `SurveyResource` via a new `interviewQuality()` method.

## Capabilities

### New Capabilities
- `nfield-api/interview-quality`: Exposes interview quality management endpoints in the SDK.

### Modified Capabilities
None.

## Impact

- Adds new DTOs in `src/Data/Surveys/` and Enums in `src/Enums/`.
- Extends the `SurveyResource` which is part of the public API.
- Fully backward compatible; no breaking changes.
