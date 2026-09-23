## Context

The Nfield v2 Admin API provides endpoints to manage webhook subscriptions for events (`/v2/events/subscriptions`). We are integrating these endpoints into the SDK. As established in the proposal, we need DTOs, Endpoints, a Service, and a fluent Resource.

The Nfield API schema names inputs and outputs slightly differently (e.g. `endpoint` in requests vs `webHookUri` in responses, and `eventSubscriptionName` in requests vs `name` in responses).

## Goals / Non-Goals

**Goals:**
- Provide a clean, typed PHP interface over the Event Subscriptions API.
- Abstract the API's naming inconsistencies (`endpoint` vs `webHookUri`) into consistent Spatie Laravel Data DTOs without breaking the SDK's core architecture rules (no casing mappers allowed in DTOs).
- Provide a scalable fluent API entry point grouped under `$manager->eventSubscriptions()`.

**Non-Goals:**
- Handling incoming webhooks or signature verification. The SDK only manages the subscriptions themselves, not the receiving of events.

## Decisions

### Decision 1: DTO Field Naming and Normalization
**Choice**: Use the exact names from the API response for the outbound model, and exact names from the API request for the inbound models, but strictly typed in camelCase.
**Rationale**: `AGENTS.md` explicitly states: "DTOs carry no casing mapper... Wire casing is normalised once at the HTTP boundary." We will let the `HttpClient` handle PascalCase to camelCase translation. Our DTOs (`SubscriptionModel`, `CreateSubscriptionModel`, `UpdateSubscriptionModel`) will map 1:1 to the OpenAPI specs.

### Decision 2: Grouped Fluent Interface
**Choice**: Create an `EventSubscriptionService` exposed as `$manager->eventSubscriptions()`. 
**Rationale**: Instead of flattening operations onto `NfieldManagerService` (like `listEventSubscriptions()`), grouping them under a domain entry point keeps the manager clean as the SDK grows.

### Decision 3: Fluent Resource Pattern for Scoped Operations
**Choice**: The `forEventSubscription(string $name)` method on the service will return an `EventSubscriptionResource`.
**Rationale**: This follows the repository's established fluent pattern (e.g., `forSurvey($id) -> SurveyResource`). The resource will implement `get()`, `update()`, and `delete()`.

## Risks / Trade-offs

- **Risk**: API Spec Naming Inconsistencies.
  **Mitigation**: We mapped the exact request and response shapes using the `nfield-api-spec` MCP server, so we are confident in the field names required.

- **Trade-off**: Adding a new nested method on `NfieldManager` breaks slightly from the strict flattened pattern of `Survey` and `CapiInterviewer`. However, it establishes a better pattern for future domain expansions.
