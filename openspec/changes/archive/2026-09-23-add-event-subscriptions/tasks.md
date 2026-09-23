## 1. DTOs

- [x] 1.1 Create `SubscriptionModel` (mapping to Nfield response) in `src/Data/Events/` and verify formatting with Pint.
- [x] 1.2 Create `CreateSubscriptionModel` (mapping to Nfield request) in `src/Data/Events/` and verify formatting with Pint.
- [x] 1.3 Create `UpdateSubscriptionModel` (mapping to Nfield request) in `src/Data/Events/` and verify formatting with Pint.

## 2. Contracts & Endpoints

- [x] 2.1 Create `SubscriptionCollectionEndpointInterface` and `SubscriptionEndpointInterface` in `src/Contracts/Endpoints/`.
- [x] 2.2 Create `SubscriptionCollectionEndpoint` in `src/Endpoints/v2/Events/` extending `BaseEndpoint` and using `basePath()` of `v2/events/subscriptions`. Verify with ArchTest.
- [x] 2.3 Create `SubscriptionEndpoint` in `src/Endpoints/v2/Events/` extending `BaseEndpoint` and using `resourcePath()`. Verify with ArchTest.
- [x] 2.4 Register endpoints in `NfieldAdminServiceProvider`.

## 3. Services & Resources

- [x] 3.1 Create `EventSubscriptionResource` implementing get, update, delete methods in `src/Resources/` and verify formatting.
- [x] 3.2 Create `EventSubscriptionService` implementing list, create, and `forSubscription` fluent entry point in `src/Services/`.
- [x] 3.3 Add `eventSubscriptions()` method to `NfieldManagerService` and its contract to expose the service.

## 4. Tests and Final Verification

- [x] 4.1 Write Pest tests for the `EventSubscriptionService` in `tests/Services/` verifying normal functionality.
- [x] 4.2 Run `composer run format` to ensure code styling compliance.
- [x] 4.3 Run `composer run analyse` to verify strict types and PHPStan compliance.
- [x] 4.4 Run `composer test` to ensure all tests pass.
