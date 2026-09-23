## Purpose

Allows users of the NfieldAdmin SDK to view and update the quality state of interviews within a specific survey.

## ADDED Requirements

### Requirement: Retrieve interview quality details for a survey
The system MUST allow users to retrieve a list of interview quality details for a given survey.

#### Scenario: Successful retrieval of interview quality collection
- **WHEN** a user requests the interview quality collection for a valid survey ID
- **THEN** the system returns a collection of interview details including their quality state

### Requirement: Update quality state of an interview
The system MUST allow users to update the quality state of a specific interview within a survey.

#### Scenario: Successful update of interview quality
- **WHEN** a user provides a valid survey ID, interview ID, and a new valid quality state
- **THEN** the system updates the interview quality and returns the detailed updated interview record

### Requirement: Retrieve quality state of a single interview
The system MUST allow users to retrieve the quality details of a single, specific interview.

#### Scenario: Successful retrieval of single interview quality
- **WHEN** a user requests the interview quality for a valid survey ID and interview ID
- **THEN** the system returns the interview details including its quality state
