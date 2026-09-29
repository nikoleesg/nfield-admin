# NField API v2 — Endpoint Coverage

Generated 2026-09-22 from the `nfield-api-spec` OpenAPI document (`openapi://paths`). Re-verified 2026-09-23 against `src/` on branch `dev` (HEAD `d9c8a84`): every ✅ row names a class and method that exists.

**Overall: 125 of 282 operations implemented (44%), across 182 paths.**

**Planned for development: 14 operations across 1 section**, each tracked by a GitHub issue (see [Planned for development](#planned-for-development)).

Status legend: ✅ implemented · 🗓️ planned for development (tracking issue linked) · ❌ pending, not yet planned.

Counting is per *operation* (method + path), not per path. "Implemented" means a class in `src/Endpoints/v2/` (or `HttpClient`) issues that exact request.

## Summary by section

| Section | Implemented | Planned | Total | Coverage |
|---|---:|---:|---:|---:|
| CAPI Interviewers | 11 | — | 11 | 100% |
| Surveys — Quota | 7 | — | 7 | 100% |
| Surveys — Fieldwork | 4 | — | 4 | 100% |
| Background Activities | 1 | — | 1 | 100% |
| Survey Blueprints | 1 | — | 1 | 100% |
| Event Subscriptions | 5 | — | 5 | 100% |
| Interviewers Worklog | 1 | — | 1 | 100% |
| Survey Resources | 1 | — | 1 | 100% |
| Response Codes (tenant) | 4 | — | 4 | 100% |
| Themes | 3 | — | 3 | 100% |
| Surveys — Sampling Points | 22 | — | 25 | 88% |
| Surveys — Sample | 10 | — | 12 | 83% |
| Surveys — Core | 13 | — | 14 | 93% |
| Surveys — Settings & Content | 11 | — | 23 | 48% |
| Surveys — Publishing & Script | 9 | — | 13 | 69% |
| Surveys — Interviews & Data | 8 | — | 16 | 50% |
| Access & Authentication | 9 | — | 20 | 45% |
| Data Delivery | 0 | — | 38 | 0% |
| Surveys — Invitations & Distribution | 0 | — | 16 | 0% |
| Parent Surveys & Waves | 0 | 14 | 14 | 0% |
| Survey Groups | 5 | — | 12 | 42% |
| Screeners | 0 | — | 10 | 0% |
| Surveys — Interviewers & Assignments | 0 | — | 7 | 0% |
| CATI Interviewers | 0 | — | 5 | 0% |
| Offices | 0 | — | 5 | 0% |
| Language Translations (tenant) | 0 | — | 4 | 0% |
| Blacklist | 0 | — | 2 | 0% |
| Default Texts | 0 | — | 2 | 0% |
| Email Settings (tenant) | 0 | — | 2 | 0% |
| Search Fields Setting | 0 | — | 2 | 0% |
| Manual Tests (tenant) | 0 | — | 1 | 0% |
| Templates | 0 | — | 1 | 0% |
| **Total** | **125** | **14** | **282** | **44%** |

---

## Planned for development

Planned for the next release (2026-09-23). Work proceeds section by section; each issue carries a checkbox per operation and is closed once all of them are implemented. When an operation lands, change its row in the detail tables below from 🗓️ to ✅, name the implementing class, and update the counts.

| Section | Operations | Tracking issue |
|---|---:|---|
| Parent Surveys & Waves | 14 | [#67](https://github.com/nikoleesg/nfield-admin/issues/67) |
| **Total** | **14** | |

Survey Groups was only partly planned (#60, now implemented): the write operations (`POST /v2/surveyGroups`, `PATCH`/`DELETE /v2/surveyGroups/{surveyGroupId}`, and `assignDirectory` / `assignLocal` / `unassignDirectory` / `unassignLocal`) remain ❌ pending.

---

## Detail by section

### CAPI Interviewers

*11/11 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/capiInterviewers` | `CapiInterviewersCollectionEndpoint::list / find` |
| ✅ | POST | `/v2/capiInterviewers` | `CapiInterviewersCollectionEndpoint::create` |
| ✅ | GET | `/v2/capiInterviewers/getByClientId/{clientInterviewerId}` | `CapiInterviewersCollectionEndpoint::getByClientId` |
| ✅ | DELETE | `/v2/capiInterviewers/{interviewerId}` | `CapiInterviewersEndpoint::delete` |
| ✅ | GET | `/v2/capiInterviewers/{interviewerId}` | `CapiInterviewersEndpoint::get` |
| ✅ | PATCH | `/v2/capiInterviewers/{interviewerId}` | `CapiInterviewersEndpoint::update` |
| ✅ | PUT | `/v2/capiInterviewers/{interviewerId}` | `CapiInterviewersEndpoint::resetPassword` |
| ✅ | GET | `/v2/capiInterviewers/{interviewerId}/assignments` | `CapiInterviewersAssignmentsEndpoint::list` |
| ✅ | GET | `/v2/capiInterviewers/{interviewerId}/offices` | `CapiInterviewersOfficesEndpoint::list` |
| ✅ | DELETE | `/v2/capiInterviewers/{interviewerId}/offices/{officeId}` | `CapiInterviewersOfficesEndpoint::delete` |
| ✅ | PATCH | `/v2/capiInterviewers/{interviewerId}/offices/{officeId}` | `CapiInterviewersOfficesEndpoint::update` |

### Surveys — Quota

*7/7 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveys/{surveyId}/quotaTargets` | `SurveyQuotaTargetsEndpoint::get` |
| ✅ | GET | `/v2/surveys/{surveyId}/quotaTargets/{eTag}` | `SurveyQuotaTargetsEndpoint::getVersion` |
| ✅ | GET | `/v2/surveys/{surveyId}/quotaVersions` | `SurveyQuotaVersionsEndpoint::list` |
| ✅ | GET | `/v2/surveys/{surveyId}/quotaVersions/{eTag}` | `SurveyQuotaVersionsEndpoint::get` |
| ✅ | GET | `/v2/surveys/{surveyId}/surveyQuotaFrame` | `SurveyQuotaFrameEndpoint::get` |
| ✅ | PUT | `/v2/surveys/{surveyId}/surveyQuotaFrame` | `SurveyQuotaFrameEndpoint::update` |
| ✅ | PUT | `/v2/surveys/{surveyId}/surveyQuotaFrame/{eTag}` | `SurveyQuotaFrameEndpoint::updateVersion` |

### Surveys — Fieldwork

*4/4 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveys/{surveyId}/fieldwork/counts` | `SurveyFieldworkEndpoint::counts` |
| ✅ | PUT | `/v2/surveys/{surveyId}/fieldwork/start` | `SurveyFieldworkEndpoint::start` |
| ✅ | GET | `/v2/surveys/{surveyId}/fieldwork/status` | `SurveyFieldworkEndpoint::status` |
| ✅ | PUT | `/v2/surveys/{surveyId}/fieldwork/stop` | `SurveyFieldworkEndpoint::stop` |

### Background Activities

*1/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/BackgroundActivities/{activityId}` | `BackgroundActivitiesEndpoint::get` |

### Survey Blueprints

*1/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | PUT | `/v2/surveyBlueprints/{blueprintId}/update` | `SurveyBlueprintsEndpoint::update` |

### Surveys — Sampling Points

*22/25 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | POST | `/v2/surveys/{surveyId}/activateSamplingpoints` | `SurveyEndpoint::batchActivateSamplingPoints` |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingMethod` | `SurveySamplingMethodEndpoint::get` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/samplingMethod` | `SurveySamplingMethodEndpoint::update` |
| ❌ | DELETE | `/v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image/{fileName}` | — |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingPoints` | `SamplingPointCollectionEndpoint::list / find` |
| ✅ | POST | `/v2/surveys/{surveyId}/samplingPoints` | `SamplingPointCollectionEndpoint::create` |
| ✅ | DELETE | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}` | `SamplingPointEndpoint::delete` |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}` | `SamplingPointEndpoint::get` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}` | `SamplingPointEndpoint::update` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/activate` | `SamplingPointEndpoint::activate` |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/addresses` | `SamplingPointAddressCollectionEndpoint::list / find` |
| ✅ | POST | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/addresses` | `SamplingPointAddressCollectionEndpoint::create` |
| ✅ | DELETE | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/addresses/{addressId}` | `SamplingPointAddressEndpoint::delete` |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/addresses/{addressId}` | `SamplingPointAddressEndpoint::get` |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/assignments` | `SamplingPointAssignmentEndpoint::list` |
| ✅ | DELETE | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/assignments/{interviewerId}` | `SamplingPointAssignmentEndpoint::unassign` |
| ✅ | POST | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/assignments/{interviewerId}` | `SamplingPointAssignmentEndpoint::assign` |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/quotaTargets` | `SamplingPointQuotaTargetsEndpoint::list` |
| ✅ | GET | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/quotaTargets/{quotaLevelId}` | `SamplingPointQuotaTargetsEndpoint::get` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/quotaTargets/{quotaLevelId}` | `SamplingPointQuotaTargetsEndpoint::update` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/replace` | `SamplingPointEndpoint::replace` |
| ✅ | DELETE | `/v2/surveys/{surveyId}/samplingPointsAssignments` | `SurveySamplingPointsAssignmentsEndpoint::massUnassign` |
| ✅ | POST | `/v2/surveys/{surveyId}/samplingPointsAssignments` | `SurveySamplingPointsAssignmentsEndpoint::massAssign` |

### Surveys — Sample

*10/12 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | DELETE | `/v2/surveys/{surveyId}/sample` | `SurveySampleCollectionEndpoint::delete` |
| ✅ | GET | `/v2/surveys/{surveyId}/sample` | `SurveySampleCollectionEndpoint::download` |
| ✅ | POST | `/v2/surveys/{surveyId}/sample` | `SurveySampleCollectionEndpoint::upload` |
| ✅ | PUT | `/v2/surveys/{surveyId}/sample/block` | `SurveySampleCollectionEndpoint::block` |
| ✅ | PUT | `/v2/surveys/{surveyId}/sample/clear` | `SurveySampleCollectionEndpoint::clear` |
| ✅ | POST | `/v2/surveys/{surveyId}/sample/create` | `SurveySampleCollectionEndpoint::create` |
| ✅ | PUT | `/v2/surveys/{surveyId}/sample/reset` | `SurveySampleCollectionEndpoint::reset` |
| ✅ | PUT | `/v2/surveys/{surveyId}/sample/update` | `SurveySampleCollectionEndpoint::update` |
| ✅ | GET | `/v2/surveys/{surveyId}/sample/{interviewId}` | `SurveySampleEndpoint::get` |
| ✅ | POST | `/v2/surveys/{surveyId}/sampleDataDownload/{fileName}` | `SurveySampleDataDownloadEndpoint::requestDownload` |
| ❌ | GET | `/v2/surveys/{surveyId}/sampleMask` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/sampleMask` | — |

### Surveys — Core

*13/14 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveys` | `SurveyCollectionEndpoint::list / find` |
| ✅ | POST | `/v2/surveys` | `SurveyCollectionEndpoint::create` |
| ✅ | POST | `/v2/surveys/createSurveyFromBlueprint` | `SurveyCollectionEndpoint::createFromBlueprint` |
| ✅ | GET | `/v2/surveys/search` | `SurveyCollectionEndpoint::search` |
| ✅ | DELETE | `/v2/surveys/{surveyId}` | `SurveyEndpoint::delete` |
| ✅ | GET | `/v2/surveys/{surveyId}` | `SurveyEndpoint::get` |
| ✅ | PATCH | `/v2/surveys/{surveyId}` | `SurveyEndpoint::update` |
| ✅ | GET | `/v2/surveys/{surveyId}/counts` | `SurveyEndpoint::counts` |
| ✅ | GET | `/v2/surveys/{surveyId}/customColumns` | `SurveyEndpoint::customColumns` |
| ✅ | GET | `/v2/surveys/{surveyId}/dataRetentionSettings` | `SurveyDataRetentionSettingsEndpoint::get` |
| ✅ | PUT | `/v2/surveys/{surveyId}/dataRetentionSettings` | `SurveyDataRetentionSettingsEndpoint::update` |
| ❌ | POST | `/v2/surveys/{surveyId}/respondentDataEncrypt` | — |
| ✅ | PUT | `/v2/surveys/{surveyId}/surveyGroup` | `SurveyMoveEndpoint::update` |
| ✅ | GET | `/v2/surveys/{surveyId}/versions` | `SurveyVersionsEndpoint::list` |

### Surveys — Settings & Content

*11/23 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveys/{surveyId}/generalSettings` | `SurveyGeneralSettingsEndpoint::get` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/generalSettings` | `SurveyGeneralSettingsEndpoint::update` |
| ❌ | DELETE | `/v2/surveys/{surveyId}/interviewerInstructions` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewerInstructions` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/interviewerInstructions/{fileName}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/languageTranslations` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/languageTranslations` | — |
| ❌ | DELETE | `/v2/surveys/{surveyId}/languageTranslations/{languageId}` | — |
| ❌ | PATCH | `/v2/surveys/{surveyId}/languageTranslations/{languageId}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/mediaFiles` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/mediaFiles/count` | — |
| ❌ | DELETE | `/v2/surveys/{surveyId}/mediaFiles/{fileName}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/mediaFiles/{fileName}` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/mediaFiles/{fileName}` | — |
| ✅ | GET | `/v2/surveys/{surveyId}/publicIds` | `SurveyPublicIdsEndpoint::list` |
| ✅ | PUT | `/v2/surveys/{surveyId}/publicIds` | `SurveyPublicIdsEndpoint::update` |
| ✅ | GET | `/v2/surveys/{surveyId}/responseCodes` | `SurveyResponseCodeCollectionEndpoint::list` / `find` |
| ✅ | POST | `/v2/surveys/{surveyId}/responseCodes` | `SurveyResponseCodeCollectionEndpoint::create` |
| ✅ | DELETE | `/v2/surveys/{surveyId}/responseCodes/{responseCode}` | `SurveyResponseCodeEndpoint::delete` |
| ✅ | GET | `/v2/surveys/{surveyId}/responseCodes/{responseCode}` | `SurveyResponseCodeEndpoint::get` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/responseCodes/{responseCode}` | `SurveyResponseCodeEndpoint::update` |
| ✅ | GET | `/v2/surveys/{surveyId}/settings` | `SurveySettingsEndpoint::list` |
| ✅ | POST | `/v2/surveys/{surveyId}/settings` | `SurveySettingsEndpoint::set` |

### Surveys — Publishing & Script

*9/13 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveys/{surveyId}/package` | `SurveyPackageEndpoint::get` |
| ✅ | GET | `/v2/surveys/{surveyId}/publish` | `SurveyPublishEndpoint::get` |
| ✅ | PUT | `/v2/surveys/{surveyId}/publish` | `SurveyPublishEndpoint::publish` |
| ✅ | POST | `/v2/surveys/{surveyId}/publish/start` | `SurveyPublishEndpoint::start` |
| ✅ | GET | `/v2/surveys/{surveyId}/script` | `SurveyScriptEndpoint::get` |
| ✅ | POST | `/v2/surveys/{surveyId}/script` | `SurveyScriptEndpoint::update` |
| ✅ | GET | `/v2/surveys/{surveyId}/script/{eTag}` | `SurveyScriptEndpoint::getVersion` |
| ❌ | GET | `/v2/surveys/{surveyId}/scriptFragments` | — |
| ❌ | DELETE | `/v2/surveys/{surveyId}/scriptFragments/{fragmentName}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/scriptFragments/{fragmentName}` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/scriptFragments/{fragmentName}` | — |
| ✅ | GET | `/v2/surveys/{surveyId}/varFile` | `SurveyVarFileEndpoint::get` |
| ✅ | GET | `/v2/surveys/{surveyId}/varFile/{eTag}` | `SurveyVarFileEndpoint::getVersion` |

### Surveys — Interviews & Data

*8/16 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/surveys/interviewSimulations` | — |
| ✅ | POST | `/v2/surveys/{surveyId}/dataDownload` | `SurveyDataEndpoint::download` |
| ✅ | POST | `/v2/surveys/{surveyId}/dataDownload/{interviewId}` | `SurveyDataEndpoint::downloadInterview` |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewInteractionsSettings` | — |
| ❌ | PATCH | `/v2/surveys/{surveyId}/interviewInteractionsSettings` | — |
| ✅ | GET | `/v2/surveys/{surveyId}/interviewQuality` | `SurveyInterviewQualityCollectionEndpoint::list` |
| ✅ | PUT | `/v2/surveys/{surveyId}/interviewQuality` | `SurveyInterviewQualityCollectionEndpoint::update` |
| ✅ | GET | `/v2/surveys/{surveyId}/interviewQuality/{interviewId}` | `SurveyInterviewQualityEndpoint::get` |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewSimulation` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewSimulations/downloadHints` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/interviewSimulations/startInterviewSimulations` | — |
| ✅ | DELETE | `/v2/surveys/{surveyId}/interviews/{interviewId}` | `SurveyInterviewEndpoint::delete` |
| ❌ | GET | `/v2/surveys/{surveyId}/manualTests` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/manualTests` | — |
| ✅ | GET | `/v2/surveys/{surveyId}/performance/metrics/live` | `SurveyPerformanceEndpoint::live` |
| ✅ | GET | `/v2/surveys/{surveyId}/performance/metrics/test` | `SurveyPerformanceEndpoint::test` |

### Access & Authentication

*9/20 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | DELETE | `/v2/domainAssignments` | — |
| ❌ | POST | `/v2/domainAssignments` | — |
| ❌ | GET | `/v2/localUsers` | — |
| ❌ | POST | `/v2/localUsers` | — |
| ❌ | DELETE | `/v2/localUsers/{identityId}` | — |
| ❌ | GET | `/v2/localUsers/{identityId}` | — |
| ❌ | PATCH | `/v2/localUsers/{identityId}` | — |
| ❌ | PATCH | `/v2/localUsers/{identityId}/password` | — |
| ❌ | POST | `/v2/localUsersLogs` | — |
| ✅ | GET | `/v2/me/role` | `UserRoleEndpoint::get` |
| ❌ | GET | `/v2/passwordSettings` | — |
| ❌ | PATCH | `/v2/passwordSettings` | — |
| ✅ | GET | `/v2/requests` | `RequestConfigurationCollectionEndpoint::list` |
| ✅ | POST | `/v2/requests` | `RequestConfigurationCollectionEndpoint::create` |
| ✅ | PUT | `/v2/requests` | `RequestConfigurationCollectionEndpoint::update` |
| ✅ | DELETE | `/v2/requests/{requestId}` | `RequestConfigurationEndpoint::delete` |
| ✅ | GET | `/v2/requests/{requestId}` | `RequestConfigurationEndpoint::get` |
| ✅ | GET | `/v2/roles` | `RolesEndpoint::list` |
| ✅ | POST | `/v2/token` | `Services\Http\HttpClient::getAccessToken` |
| ✅ | POST | `/v2/token/refresh` | `Services\Http\HttpClient::getAccessToken (refresh flow)` |

### Data Delivery

*0/38 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/delivery/fabricDataShares` | — |
| ❌ | POST | `/v2/delivery/fabricDataShares` | — |
| ❌ | DELETE | `/v2/delivery/fabricDataShares/{fabricDataShareId}` | — |
| ❌ | GET | `/v2/delivery/fabricDataShares/{fabricDataShareId}` | — |
| ❌ | GET | `/v2/delivery/fabricDataShares/{fabricDataShareId}/surveys` | — |
| ❌ | POST | `/v2/delivery/fabricDataShares/{fabricDataShareId}/surveys` | — |
| ❌ | DELETE | `/v2/delivery/fabricDataShares/{fabricDataShareId}/surveys/{nfieldSurveyId}` | — |
| ❌ | GET | `/v2/delivery/repositories` | — |
| ❌ | POST | `/v2/delivery/repositories` | — |
| ❌ | DELETE | `/v2/delivery/repositories/{repositoryId}` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/Credentials` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/Logs/Activities` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/Logs/Subscriptions` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/Metrics/{Interval}` | — |
| ❌ | POST | `/v2/delivery/repositories/{repositoryId}/Subscriptions` | — |
| ❌ | POST | `/v2/delivery/repositories/{repositoryId}/Sync` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/firewallRules` | — |
| ❌ | POST | `/v2/delivery/repositories/{repositoryId}/firewallRules` | — |
| ❌ | DELETE | `/v2/delivery/repositories/{repositoryId}/firewallRules/{firewallRuleId}` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/firewallRules/{firewallRuleId}` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/surveys` | — |
| ❌ | POST | `/v2/delivery/repositories/{repositoryId}/surveys` | — |
| ❌ | DELETE | `/v2/delivery/repositories/{repositoryId}/surveys/{surveyId}` | — |
| ❌ | PUT | `/v2/delivery/repositories/{repositoryId}/surveys/{surveyId}/reinitiate` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/users` | — |
| ❌ | POST | `/v2/delivery/repositories/{repositoryId}/users` | — |
| ❌ | DELETE | `/v2/delivery/repositories/{repositoryId}/users/{userId}` | — |
| ❌ | GET | `/v2/delivery/repositories/{repositoryId}/users/{userId}` | — |
| ❌ | POST | `/v2/delivery/repositories/{repositoryId}/users/{userId}/reset` | — |
| ❌ | GET | `/v2/delivery/settings/plans` | — |
| ❌ | GET | `/v2/delivery/settings/repositoryStatuses` | — |
| ❌ | GET | `/v2/delivery/surveys` | — |
| ❌ | GET | `/v2/delivery/surveys/{surveyId}/properties` | — |
| ❌ | POST | `/v2/delivery/surveys/{surveyId}/properties` | — |
| ❌ | DELETE | `/v2/delivery/surveys/{surveyId}/properties/{propertyId}` | — |
| ❌ | GET | `/v2/delivery/surveys/{surveyId}/properties/{propertyId}` | — |
| ❌ | PUT | `/v2/delivery/surveys/{surveyId}/properties/{propertyId}` | — |

### Surveys — Invitations & Distribution

*0/16 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/surveys/inviteRespondents/surveysInvitationStatus` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/dialMode` | — |
| ❌ | PATCH | `/v2/surveys/{surveyId}/dialMode` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/distribute` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/emailSettings` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/emailSettings` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/invitationImages/{fileName}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/invitationTemplates` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/invitationTemplates` | — |
| ❌ | DELETE | `/v2/surveys/{surveyId}/invitationTemplates/{templateId}` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/invitationTemplates/{templateId}` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/inviteRespondents` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/inviteRespondents/invitationStatus/{batchName}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/inviteRespondents/surveyBatchesStatus` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/landingPage` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/landingPage` | — |

### Parent Surveys & Waves

*0/14 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| 🗓️ | GET | `/v2/parentSurveys` | Planned for Development (#67) |
| 🗓️ | POST | `/v2/parentSurveys` | Planned for Development (#67) |
| 🗓️ | GET | `/v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart` | Planned for Development (#67) |
| 🗓️ | PUT | `/v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart` | Planned for Development (#67) |
| 🗓️ | GET | `/v2/parentSurveys/{parentSurveyId}/waves` | Planned for Development (#67) |
| 🗓️ | POST | `/v2/parentSurveys/{parentSurveyId}/waves` | Planned for Development (#67) |
| 🗓️ | POST | `/v2/parentSurveys/{parentSurveyId}/waves/{waveId}` | Planned for Development (#67) |
| 🗓️ | DELETE | `/v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` | Planned for Development (#67) |
| 🗓️ | GET | `/v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` | Planned for Development (#67) |
| 🗓️ | PUT | `/v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` | Planned for Development (#67) |
| 🗓️ | GET | `/v2/surveyWaves/{waveId}/startDate` | Planned for Development (#67) |
| 🗓️ | PUT | `/v2/surveyWaves/{waveId}/startDate` | Planned for Development (#67) |
| 🗓️ | GET | `/v2/surveyWaves/{waveId}/stopDate` | Planned for Development (#67) |
| 🗓️ | PUT | `/v2/surveyWaves/{waveId}/stopDate` | Planned for Development (#67) |

### Survey Groups

*5/12 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveyGroups` | `SurveyGroupCollectionEndpoint::list` |
| ❌ | POST | `/v2/surveyGroups` | — |
| ❌ | DELETE | `/v2/surveyGroups/{surveyGroupId}` | — |
| ✅ | GET | `/v2/surveyGroups/{surveyGroupId}` | `SurveyGroupEndpoint::get` |
| ❌ | PATCH | `/v2/surveyGroups/{surveyGroupId}` | — |
| ❌ | PUT | `/v2/surveyGroups/{surveyGroupId}/assignDirectory` | — |
| ❌ | PUT | `/v2/surveyGroups/{surveyGroupId}/assignLocal` | — |
| ✅ | GET | `/v2/surveyGroups/{surveyGroupId}/directoryAssignments` | `SurveyGroupDirectoryAssignmentsEndpoint::list` |
| ✅ | GET | `/v2/surveyGroups/{surveyGroupId}/localAssignments` | `SurveyGroupLocalAssignmentsEndpoint::list` |
| ✅ | GET | `/v2/surveyGroups/{surveyGroupId}/surveys` | `SurveyGroupSurveysEndpoint::list` |
| ❌ | PUT | `/v2/surveyGroups/{surveyGroupId}/unassignDirectory` | — |
| ❌ | PUT | `/v2/surveyGroups/{surveyGroupId}/unassignLocal` | — |

### Screeners

*0/10 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/screeners` | — |
| ❌ | POST | `/v2/screeners` | — |
| ❌ | GET | `/v2/screeners/{screenerId}/children` | — |
| ❌ | POST | `/v2/screeners/{screenerId}/children` | — |
| ❌ | GET | `/v2/screeners/{screenerId}/children/conditions` | — |
| ❌ | PUT | `/v2/screeners/{screenerId}/children/conditions` | — |
| ❌ | DELETE | `/v2/screeners/{screenerId}/children/conditions/{conditionId}` | — |
| ❌ | GET | `/v2/screeners/{screenerId}/children/routing-settings` | — |
| ❌ | PUT | `/v2/screeners/{screenerId}/children/routing-settings` | — |
| ❌ | POST | `/v2/screeners/{screenerId}/children/{childId}/conditions` | — |

### Surveys — Interviewers & Assignments

*0/7 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/surveys/{surveyId}/interviewers` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/interviewers` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/interviewers/distributeWorkpackageTarget` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/interviewers/{interviewerId}/assign` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewers/{interviewerId}/quotaLevelTargets` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/interviewers/{interviewerId}/quotaLevelTargets` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/interviewers/{interviewerId}/unassign` | — |

### CATI Interviewers

*0/5 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/catiInterviewers` | — |
| ❌ | POST | `/v2/catiInterviewers` | — |
| ❌ | DELETE | `/v2/catiInterviewers/{interviewerId}` | — |
| ❌ | GET | `/v2/catiInterviewers/{interviewerId}` | — |
| ❌ | PUT | `/v2/catiInterviewers/{interviewerId}` | — |

### Event Subscriptions

*5/5 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/events/subscriptions` | `SubscriptionCollectionEndpoint::list` |
| ✅ | POST | `/v2/events/subscriptions` | `SubscriptionCollectionEndpoint::create` |
| ✅ | DELETE | `/v2/events/subscriptions/{name}` | `SubscriptionEndpoint::delete` |
| ✅ | GET | `/v2/events/subscriptions/{name}` | `SubscriptionEndpoint::get` |
| ✅ | PATCH | `/v2/events/subscriptions/{name}` | `SubscriptionEndpoint::update` |

### Interviewers Worklog

*1/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | POST | `/v2/interviewersWorklog` | `InterviewersWorklogEndpoint::download` |

### Survey Resources

*1/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveyResources` | `SurveyResourceUsageEndpoint::list` / `find` |

### Offices

*0/5 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/offices` | — |
| ❌ | POST | `/v2/offices` | — |
| ❌ | DELETE | `/v2/offices/{officeId}` | — |
| ❌ | GET | `/v2/offices/{officeId}` | — |
| ❌ | PATCH | `/v2/offices/{officeId}` | — |

### Language Translations (tenant)

*0/4 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/languageTranslations` | — |
| ❌ | POST | `/v2/languageTranslations` | — |
| ❌ | DELETE | `/v2/languageTranslations/{languageId}` | — |
| ❌ | PATCH | `/v2/languageTranslations/{languageId}` | — |

### Response Codes (tenant)

*4/4 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/responseCodes` | `ResponseCodeCollectionEndpoint::list` |
| ✅ | POST | `/v2/responseCodes` | `ResponseCodeCollectionEndpoint::create` |
| ✅ | DELETE | `/v2/responseCodes/{responseCodeId}` | `ResponseCodeEndpoint::delete` |
| ✅ | PATCH | `/v2/responseCodes/{responseCodeId}` | `ResponseCodeEndpoint::update` |

### Themes

*3/3 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/themes` | `ThemeCollectionEndpoint::downloadUrl` |
| ✅ | PUT | `/v2/themes` | `ThemeCollectionEndpoint::upload` (multipart) |
| ✅ | DELETE | `/v2/themes/{themeId}` | `ThemeEndpoint::delete` |

### Blacklist

*0/2 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/blackList` | — |
| ❌ | POST | `/v2/blackList` | — |

### Default Texts

*0/2 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/defaultTexts` | — |
| ❌ | GET | `/v2/defaultTexts/{translationKey}` | — |

### Email Settings (tenant)

*0/2 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/emailSettings` | — |
| ❌ | PUT | `/v2/emailSettings` | — |

### Search Fields Setting

*0/2 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/searchFieldsSetting` | — |
| ❌ | PUT | `/v2/searchFieldsSetting` | — |

### Manual Tests (tenant)

*0/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/manualTests` | — |

### Templates

*0/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/templates` | — |


---

## Notes & observations

- **The package is survey-centric.** 80 of the 89 implemented operations live under `/v2/surveys`, `/v2/capiInterviewers`, `/v2/surveyBlueprints` and `/v2/BackgroundActivities`; the remaining 9 are the two token endpoints, `GET /v2/me/role`, `GET /v2/roles` and the five `/v2/events/subscriptions` operations. Whole top-level areas (Data Delivery, Survey Groups, Offices, Screeners, Parent Surveys/Waves, Themes, Templates) are untouched; Survey Groups, Parent Surveys/Waves and Themes are now planned.
- **Largest single gap: Data Delivery** (38 operations, 0 implemented) — repositories, subscriptions, users, firewall rules, Fabric data shares and delivery survey properties.
- **CAPI is complete, CATI is absent.** `/v2/capiInterviewers` is fully covered (11/11); `/v2/catiInterviewers` (5 operations) has no counterpart.
- **Survey-level interviewer management is missing** (7 operations): assign/unassign an interviewer to a survey, per-interviewer quota level targets and workpackage target distribution. Note this is distinct from *sampling-point* assignments, which are implemented.
- **Sampling points are near-complete** (22/25); only the sampling-point image endpoints (GET/DELETE/POST `.../samplingPoint/{samplingPointId}/image`) are pending.
- **Sample is near-complete** (10/12); the two pending ones are `GET`/`PUT /v2/surveys/{surveyId}/sampleMask`.
- **Script & questionnaire content is a notable hole** for a publishing workflow: `script`, `scriptFragments`, `varFile`, `package` and `versions` are all unimplemented, even though `publish` itself is covered.
- **Token endpoints** are implemented inside `src/Services/Http/HttpClient.php` rather than as an endpoint class, which is why they have no `Endpoints/v2` counterpart.
- ~~`SurveySettingsEndpoint::buildPath()` hardcodes `'/v2/surveys'`~~ — fixed in #35; `$version` now lives only on `BaseEndpoint` and `tests/ArchTest.php` fails on a hardcoded version prefix.

---

## Stale & deprecated check

### Deprecated in the spec (2 operations, neither implemented)

The OpenAPI document marks exactly two operations `deprecated: true`:

| Method | Path | Spec note | Implemented here? |
|---|---|---|---|
| POST | `/v2/surveys/{surveyId}/interviewers` | "DEPRECATED, USE Assign to Add+Assign directly." | No |
| POST | `/v2/surveys/{surveyId}/distribute` | "DEPRECATED, USE DistributeWorkpackageTarget" | No |

**No implemented endpoint is deprecated.** If the survey-interviewer area is picked up later, use `PUT .../interviewers/{interviewerId}/assign` and `POST .../interviewers/distributeWorkpackageTarget` rather than the two above.

### Stale calls (endpoints that no longer exist)

None. All 89 implemented operations resolve to a live method+path pair in the v2 spec. The v1 stack was fully removed (`930bf06`, finished in `3c5d8aa`) — `src/Endpoints/v1/` and `src/Services/v1/` no longer exist and nothing in `src/` references them.

### Stale leftovers in the code

**All resolved** (re-checked 2026-09-23). The audit on 2026-09-22 listed residue from the removed v1 stack: a duplicate root-namespace `NewCapiInterviewerRequestModel`, unreferenced v1-era `*DTO` / `*Data` classes in `src/Data/`, unused v2 request/response models, an empty `SampleFilterModel` stub, and unreferenced `SurveyFieldworkStatusEnum` / `InterviewingRestrictionTypeEnum`. Today:

- `src/Data/` has no root-level classes, and `Data/SurveyQuotaFrame/` and `Data/Quota/QuotaAttribute` are gone.
- `*DTO` / `*Data` suffixes are retired and `tests/ArchTest.php` rejects them (#28).
- The request models are wired into services (`array|RequestModel` input), and `SampleFilterModel` is a real model used by `SurveySampleService`.
- Both enums are referenced (`SurveyFieldworkService`, `SurveysFieldworkStopRequestModel`).

### Minor drift in implemented calls

| Where | Detail | Impact |
|---|---|---|
| `BackgroundActivitiesEndpoint::buildPath()` | Requests `/v2/backgroundActivities`; the spec path is `/v2/BackgroundActivities`. | None in practice (ASP.NET routing is case-insensitive), but it will not match a spec-driven test or mock. |
| `SurveyCollectionEndpoint::search()` | Sends query param `Value`; the spec declares `value`. | Same — case-insensitive binding, cosmetic only. |
| `SurveySettingsEndpoint::buildPath()` | Hardcodes `'/v2/surveys'` instead of `"/{$this->version}/surveys"`. | Inconsistent with the other 21 endpoints. |

**All three were fixed in #35** (`f97a8dc`). The endpoint-class layout was then settled in #40 + #43: `batchActivate` moved to `SurveyEndpoint`, the quota / settings / sample / interviews / CAPI-office paths were split onto their own classes, and `tests/ArchTest.php` now fails if a class spans two spec path prefixes. The class names in the tables above reflect that layout.

### Stale documentation

**Resolved.** `CLAUDE.md` and `AGENTS.md` no longer describe `src/Endpoints/v1/` as a reference; both now state that the v1 stack is removed and must not be reintroduced.

---

## Pending endpoints (flat list)

Everything not yet implemented (157 operations), grouped by section. Items marked "Planned for Development" have a tracking issue; the rest are not yet planned.

**Access & Authentication** (11 pending)

- `DELETE /v2/domainAssignments`
- `POST /v2/domainAssignments`
- `GET /v2/localUsers`
- `POST /v2/localUsers`
- `DELETE /v2/localUsers/{identityId}`
- `GET /v2/localUsers/{identityId}`
- `PATCH /v2/localUsers/{identityId}`
- `PATCH /v2/localUsers/{identityId}/password`
- `POST /v2/localUsersLogs`
- `GET /v2/passwordSettings`
- `PATCH /v2/passwordSettings`

**Blacklist** (2 pending)

- `GET /v2/blackList`
- `POST /v2/blackList`

**CATI Interviewers** (5 pending)

- `GET /v2/catiInterviewers`
- `POST /v2/catiInterviewers`
- `DELETE /v2/catiInterviewers/{interviewerId}`
- `GET /v2/catiInterviewers/{interviewerId}`
- `PUT /v2/catiInterviewers/{interviewerId}`

**Data Delivery** (38 pending)

- `GET /v2/delivery/fabricDataShares`
- `POST /v2/delivery/fabricDataShares`
- `DELETE /v2/delivery/fabricDataShares/{fabricDataShareId}`
- `GET /v2/delivery/fabricDataShares/{fabricDataShareId}`
- `GET /v2/delivery/fabricDataShares/{fabricDataShareId}/surveys`
- `POST /v2/delivery/fabricDataShares/{fabricDataShareId}/surveys`
- `DELETE /v2/delivery/fabricDataShares/{fabricDataShareId}/surveys/{nfieldSurveyId}`
- `GET /v2/delivery/repositories`
- `POST /v2/delivery/repositories`
- `DELETE /v2/delivery/repositories/{repositoryId}`
- `GET /v2/delivery/repositories/{repositoryId}`
- `GET /v2/delivery/repositories/{repositoryId}/Credentials`
- `GET /v2/delivery/repositories/{repositoryId}/Logs/Activities`
- `GET /v2/delivery/repositories/{repositoryId}/Logs/Subscriptions`
- `GET /v2/delivery/repositories/{repositoryId}/Metrics/{Interval}`
- `POST /v2/delivery/repositories/{repositoryId}/Subscriptions`
- `POST /v2/delivery/repositories/{repositoryId}/Sync`
- `GET /v2/delivery/repositories/{repositoryId}/firewallRules`
- `POST /v2/delivery/repositories/{repositoryId}/firewallRules`
- `DELETE /v2/delivery/repositories/{repositoryId}/firewallRules/{firewallRuleId}`
- `GET /v2/delivery/repositories/{repositoryId}/firewallRules/{firewallRuleId}`
- `GET /v2/delivery/repositories/{repositoryId}/surveys`
- `POST /v2/delivery/repositories/{repositoryId}/surveys`
- `DELETE /v2/delivery/repositories/{repositoryId}/surveys/{surveyId}`
- `PUT /v2/delivery/repositories/{repositoryId}/surveys/{surveyId}/reinitiate`
- `GET /v2/delivery/repositories/{repositoryId}/users`
- `POST /v2/delivery/repositories/{repositoryId}/users`
- `DELETE /v2/delivery/repositories/{repositoryId}/users/{userId}`
- `GET /v2/delivery/repositories/{repositoryId}/users/{userId}`
- `POST /v2/delivery/repositories/{repositoryId}/users/{userId}/reset`
- `GET /v2/delivery/settings/plans`
- `GET /v2/delivery/settings/repositoryStatuses`
- `GET /v2/delivery/surveys`
- `GET /v2/delivery/surveys/{surveyId}/properties`
- `POST /v2/delivery/surveys/{surveyId}/properties`
- `DELETE /v2/delivery/surveys/{surveyId}/properties/{propertyId}`
- `GET /v2/delivery/surveys/{surveyId}/properties/{propertyId}`
- `PUT /v2/delivery/surveys/{surveyId}/properties/{propertyId}`

**Default Texts** (2 pending)

- `GET /v2/defaultTexts`
- `GET /v2/defaultTexts/{translationKey}`

**Email Settings (tenant)** (2 pending)

- `GET /v2/emailSettings`
- `PUT /v2/emailSettings`

**Language Translations (tenant)** (4 pending)

- `GET /v2/languageTranslations`
- `POST /v2/languageTranslations`
- `DELETE /v2/languageTranslations/{languageId}`
- `PATCH /v2/languageTranslations/{languageId}`

**Manual Tests (tenant)** (1 pending)

- `GET /v2/manualTests`

**Offices** (5 pending)

- `GET /v2/offices`
- `POST /v2/offices`
- `DELETE /v2/offices/{officeId}`
- `GET /v2/offices/{officeId}`
- `PATCH /v2/offices/{officeId}`

**Parent Surveys & Waves** (14 pending)

- `GET /v2/parentSurveys` — Planned for Development (#67)
- `POST /v2/parentSurveys` — Planned for Development (#67)
- `GET /v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart` — Planned for Development (#67)
- `PUT /v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart` — Planned for Development (#67)
- `GET /v2/parentSurveys/{parentSurveyId}/waves` — Planned for Development (#67)
- `POST /v2/parentSurveys/{parentSurveyId}/waves` — Planned for Development (#67)
- `POST /v2/parentSurveys/{parentSurveyId}/waves/{waveId}` — Planned for Development (#67)
- `DELETE /v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` — Planned for Development (#67)
- `GET /v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` — Planned for Development (#67)
- `PUT /v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` — Planned for Development (#67)
- `GET /v2/surveyWaves/{waveId}/startDate` — Planned for Development (#67)
- `PUT /v2/surveyWaves/{waveId}/startDate` — Planned for Development (#67)
- `GET /v2/surveyWaves/{waveId}/stopDate` — Planned for Development (#67)
- `PUT /v2/surveyWaves/{waveId}/stopDate` — Planned for Development (#67)

**Screeners** (10 pending)

- `GET /v2/screeners`
- `POST /v2/screeners`
- `GET /v2/screeners/{screenerId}/children`
- `POST /v2/screeners/{screenerId}/children`
- `GET /v2/screeners/{screenerId}/children/conditions`
- `PUT /v2/screeners/{screenerId}/children/conditions`
- `DELETE /v2/screeners/{screenerId}/children/conditions/{conditionId}`
- `GET /v2/screeners/{screenerId}/children/routing-settings`
- `PUT /v2/screeners/{screenerId}/children/routing-settings`
- `POST /v2/screeners/{screenerId}/children/{childId}/conditions`

**Search Fields Setting** (2 pending)

- `GET /v2/searchFieldsSetting`
- `PUT /v2/searchFieldsSetting`

**Survey Groups** (7 pending)

- `POST /v2/surveyGroups`
- `DELETE /v2/surveyGroups/{surveyGroupId}`
- `PATCH /v2/surveyGroups/{surveyGroupId}`
- `PUT /v2/surveyGroups/{surveyGroupId}/assignDirectory`
- `PUT /v2/surveyGroups/{surveyGroupId}/assignLocal`
- `PUT /v2/surveyGroups/{surveyGroupId}/unassignDirectory`
- `PUT /v2/surveyGroups/{surveyGroupId}/unassignLocal`

**Surveys — Core** (1 pending)

- `POST /v2/surveys/{surveyId}/respondentDataEncrypt`

**Surveys — Interviewers & Assignments** (7 pending)

- `GET /v2/surveys/{surveyId}/interviewers`
- `POST /v2/surveys/{surveyId}/interviewers`
- `POST /v2/surveys/{surveyId}/interviewers/distributeWorkpackageTarget`
- `PUT /v2/surveys/{surveyId}/interviewers/{interviewerId}/assign`
- `GET /v2/surveys/{surveyId}/interviewers/{interviewerId}/quotaLevelTargets`
- `PUT /v2/surveys/{surveyId}/interviewers/{interviewerId}/quotaLevelTargets`
- `PUT /v2/surveys/{surveyId}/interviewers/{interviewerId}/unassign`

**Surveys — Interviews & Data** (8 pending)

- `GET /v2/surveys/interviewSimulations`
- `GET /v2/surveys/{surveyId}/interviewInteractionsSettings`
- `PATCH /v2/surveys/{surveyId}/interviewInteractionsSettings`
- `GET /v2/surveys/{surveyId}/interviewSimulation`
- `GET /v2/surveys/{surveyId}/interviewSimulations/downloadHints`
- `POST /v2/surveys/{surveyId}/interviewSimulations/startInterviewSimulations`
- `GET /v2/surveys/{surveyId}/manualTests`
- `POST /v2/surveys/{surveyId}/manualTests`

**Surveys — Invitations & Distribution** (16 pending)

- `GET /v2/surveys/inviteRespondents/surveysInvitationStatus`
- `GET /v2/surveys/{surveyId}/dialMode`
- `PATCH /v2/surveys/{surveyId}/dialMode`
- `POST /v2/surveys/{surveyId}/distribute`
- `GET /v2/surveys/{surveyId}/emailSettings`
- `PUT /v2/surveys/{surveyId}/emailSettings`
- `POST /v2/surveys/{surveyId}/invitationImages/{fileName}`
- `GET /v2/surveys/{surveyId}/invitationTemplates`
- `POST /v2/surveys/{surveyId}/invitationTemplates`
- `DELETE /v2/surveys/{surveyId}/invitationTemplates/{templateId}`
- `PUT /v2/surveys/{surveyId}/invitationTemplates/{templateId}`
- `POST /v2/surveys/{surveyId}/inviteRespondents`
- `GET /v2/surveys/{surveyId}/inviteRespondents/invitationStatus/{batchName}`
- `GET /v2/surveys/{surveyId}/inviteRespondents/surveyBatchesStatus`
- `GET /v2/surveys/{surveyId}/landingPage`
- `POST /v2/surveys/{surveyId}/landingPage`

**Surveys — Publishing & Script** (4 pending)

- `GET /v2/surveys/{surveyId}/scriptFragments`
- `DELETE /v2/surveys/{surveyId}/scriptFragments/{fragmentName}`
- `GET /v2/surveys/{surveyId}/scriptFragments/{fragmentName}`
- `POST /v2/surveys/{surveyId}/scriptFragments/{fragmentName}`

**Surveys — Sample** (2 pending)

- `GET /v2/surveys/{surveyId}/sampleMask`
- `PUT /v2/surveys/{surveyId}/sampleMask`

**Surveys — Sampling Points** (3 pending)

- `DELETE /v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image`
- `GET /v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image`
- `POST /v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image/{fileName}`

**Surveys — Settings & Content** (12 pending)

- `DELETE /v2/surveys/{surveyId}/interviewerInstructions`
- `GET /v2/surveys/{surveyId}/interviewerInstructions`
- `POST /v2/surveys/{surveyId}/interviewerInstructions/{fileName}`
- `GET /v2/surveys/{surveyId}/languageTranslations`
- `POST /v2/surveys/{surveyId}/languageTranslations`
- `DELETE /v2/surveys/{surveyId}/languageTranslations/{languageId}`
- `PATCH /v2/surveys/{surveyId}/languageTranslations/{languageId}`
- `GET /v2/surveys/{surveyId}/mediaFiles`
- `GET /v2/surveys/{surveyId}/mediaFiles/count`
- `DELETE /v2/surveys/{surveyId}/mediaFiles/{fileName}`
- `GET /v2/surveys/{surveyId}/mediaFiles/{fileName}`
- `POST /v2/surveys/{surveyId}/mediaFiles/{fileName}`

**Templates** (1 pending)

- `GET /v2/templates`
