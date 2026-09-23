## Context

See `proposal.md` for the overarching motivation. The NField v2 API provides three endpoints for Interview Quality under a survey. We need to implement these endpoints following the established patterns in the SDK (Spatie Laravel Data for DTOs, interfaces, and fluent resources).

## Goals / Non-Goals

**Goals:**
- Implement `SurveyInterviewQualityCollectionEndpoint` and `SurveyInterviewQualityEndpoint`.
- Define accurate DTOs based directly on the OpenAPI schema for the endpoints.
- Wire these endpoints to the `SurveyResource`.

**Non-Goals:**
- Modifying other unrelated survey endpoints.
- Building custom Normalization mappers for keys (we will adhere to `#36` which strips out `MapInputName`).

## Decisions

### 1. Separation of Endpoints
**Decision:** Split the endpoints into a collection endpoint and an item endpoint.
**Rationale:** Follows the established architectural convention enforced by `ArchTest.php` in the package where `{id}` identifies a distinct child resource. `SurveyInterviewQualityCollectionEndpoint` will use `subResourcePath` for the collection GET/PUT, and `SurveyInterviewQualityEndpoint` will use `subResourceItemPath` for the item GET.

### 2. DTO and Enum Design matching API Spec
**Decision:** Create DTOs that precisely mirror the NField OpenAPI specification for these endpoints.
**Rationale:** The OpenAPI spec provides the following schemas:
- `InterviewQualityEnum`: Integer-backed enum covering 0 (NotChecked) to 5 (ToBeChecked).
- `InterviewDetailsData`: Maps to `NfieldPublicApi.Models.Surveys.InterviewDetailsModel`. Returned by the GET endpoints. Contains 5 properties (`id`, `interviewQuality`, `interviewerId`, `samplingPointId`, `officeId`).
- `ManagerInterviewDetailsData`: Maps to `Nfield.Manager.Surveys.Models.InterviewDetails`. Returned by the PUT endpoint. Contains 17 detailed properties (e.g., `interviewId`, `clientInterviewerId`, `interviewDuration`, etc.).
- `QualityNewStateChangeData`: Maps to `NfieldPublicApi.Models.Surveys.QualityNewStateChangeModel`. Request payload for the PUT endpoint (properties: `interviewId`, `newState`).

*Alternative Considered:* Merging `InterviewDetailsData` and `ManagerInterviewDetailsData`.
*Why Rejected:* The two models have different namespaces in the API spec and different sets of required/optional fields (e.g., `id` vs `interviewId`). Merging them might cause unhydration errors or schema mismatches in Spatie Laravel Data.

### 3. Service Layer Scope
**Decision:** Create a `SurveyInterviewQualityService` that implements `SurveyScopedInterface` and uses `ScopedToSurvey`.
**Rationale:** Consistent with how subresources are managed in this package (e.g., `SurveySampleService`). The service will accept arrays or request models, perform the API call, and return Data objects or Collections of Data objects.

## Risks / Trade-offs

- **Risk:** The OpenAPI spec states the DTO properties are camelCase, but the actual NField API responses are often PascalCase.
  - **Mitigation:** Rely on the `HttpClient`'s `ResponseKeyNormalizer` (which was introduced to handle this globally). We will define our DTO properties in standard PHP camelCase and strictly avoid adding `MapInputName` or `MapOutputName` attributes.
