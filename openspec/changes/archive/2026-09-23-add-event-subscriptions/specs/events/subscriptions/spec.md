## Purpose

Enables management of event grid domain topic subscriptions, allowing clients to register, retrieve, update, and delete webhooks for Nfield events.

## ADDED Requirements

### Requirement: List Subscriptions
The system SHALL return all current event subscriptions.

#### Scenario: Successfully listing subscriptions
- **WHEN** the `list` method is called on the event subscriptions entry point
- **THEN** the system returns a collection of `SubscriptionModel` objects

### Requirement: Create Subscription
The system SHALL create a new event subscription with the provided configuration.

#### Scenario: Successfully creating a subscription
- **WHEN** the `create` method is called with valid configuration (subscription name, endpoint URL, event types)
- **THEN** the system registers the webhook and returns the created `SubscriptionModel`

### Requirement: Get Subscription
The system SHALL retrieve the details of a specific event subscription by its name.

#### Scenario: Successfully retrieving a subscription
- **WHEN** the `get` method is called on a specific event subscription resource
- **THEN** the system returns the corresponding `SubscriptionModel`

### Requirement: Update Subscription
The system SHALL update an existing event subscription's configuration.

#### Scenario: Successfully updating a subscription
- **WHEN** the `update` method is called on a specific event subscription resource with new configuration (endpoint URL, event types)
- **THEN** the system updates the webhook without returning content (void)

### Requirement: Delete Subscription
The system SHALL delete an existing event subscription by its name.

#### Scenario: Successfully deleting a subscription
- **WHEN** the `delete` method is called on a specific event subscription resource
- **THEN** the system deletes the webhook without returning content (void)
