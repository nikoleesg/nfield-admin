## Why

The SDK currently lacks support for the Nfield v2 Events Subscriptions API. Adding this vertical slice will allow users to manage webhook subscriptions for events directly through the NfieldManager facade.

## What Changes

- Introduce DTOs for subscriptions (`SubscriptionModel`, `CreateSubscriptionModel`, `UpdateSubscriptionModel`).
- Introduce endpoints (`SubscriptionCollectionEndpoint` and `SubscriptionEndpoint`) to interact with the API (`/v2/events/subscriptions` and `/v2/events/subscriptions/{name}`).
- Introduce a service (`EventSubscriptionService`) to handle operations.
- Integrate into the `NfieldManager` facade with a grouped fluent entry point: `$manager->eventSubscriptions()`.

## Capabilities

### New Capabilities
- `events/subscriptions`: The capability to manage event grid domain topic subscriptions, allowing clients to register, retrieve, update, and delete webhooks for Nfield events.

### Modified Capabilities
None.

## Impact

- Adds new DTOs in `src/Data/Events/`.
- Adds new endpoints in `src/Endpoints/v2/Events/`.
- Adds a new service `EventSubscriptionService` in `src/Services/`.
- Updates `NfieldManagerService` and `NfieldAdminServiceProvider` to expose the new service.
