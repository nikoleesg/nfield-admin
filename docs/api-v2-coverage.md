# NField API v2 — Endpoint Coverage

Generated 2026-09-22 from the `nfield-api-spec` OpenAPI document (`openapi://paths`), verified against `src/` on branch `dev` (HEAD `3c5d8aa`).

**Overall: 79 of 282 operations implemented (28%), across 182 paths.**

Counting is per *operation* (method + path), not per path. "Implemented" means a class in `src/Endpoints/v2/` (or `HttpClient`) issues that exact request.

## Summary by section

| Section | Implemented | Total | Coverage |
|---|---:|---:|---:|
| CAPI Interviewers | 11 | 11 | 100% |
| Surveys — Quota | 7 | 7 | 100% |
| Surveys — Fieldwork | 4 | 4 | 100% |
| Background Activities | 1 | 1 | 100% |
| Survey Blueprints | 1 | 1 | 100% |
| Surveys — Sampling Points | 22 | 25 | 88% |
| Surveys — Sample | 10 | 12 | 83% |
| Surveys — Core | 9 | 14 | 64% |
| Surveys — Settings & Content | 6 | 23 | 26% |
| Surveys — Publishing & Script | 3 | 13 | 23% |
| Surveys — Interviews & Data | 3 | 16 | 18% |
| Access & Authentication | 4 | 20 | 20% |
| Data Delivery | 0 | 38 | 0% |
| Surveys — Invitations & Distribution | 0 | 16 | 0% |
| Parent Surveys & Waves | 0 | 14 | 0% |
| Survey Groups | 0 | 12 | 0% |
| Screeners | 0 | 10 | 0% |
| Surveys — Interviewers & Assignments | 0 | 7 | 0% |
| CATI Interviewers | 0 | 5 | 0% |
| Event Subscriptions | 0 | 5 | 0% |
| Offices | 0 | 5 | 0% |
| Language Translations (tenant) | 0 | 4 | 0% |
| Response Codes (tenant) | 0 | 4 | 0% |
| Themes | 0 | 3 | 0% |
| Blacklist | 0 | 2 | 0% |
| Default Texts | 0 | 2 | 0% |
| Email Settings (tenant) | 0 | 2 | 0% |
| Search Fields Setting | 0 | 2 | 0% |
| Interviewers Worklog | 0 | 1 | 0% |
| Manual Tests (tenant) | 0 | 1 | 0% |
| Survey Resources | 0 | 1 | 0% |
| Templates | 0 | 1 | 0% |
| **Total** | **81** | **282** | **29%** |

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
| ✅ | GET | `/v2/surveys/{surveyId}/quotaTargets` | `SurveyQuotaTargetsEndpoint::getQuotaTargets` |
| ✅ | GET | `/v2/surveys/{surveyId}/quotaTargets/{eTag}` | `SurveyQuotaTargetsEndpoint::getQuotaTargetsByETag` |
| ✅ | GET | `/v2/surveys/{surveyId}/quotaVersions` | `SurveyQuotaVersionsEndpoint::getQuotaVersions` |
| ✅ | GET | `/v2/surveys/{surveyId}/quotaVersions/{eTag}` | `SurveyQuotaVersionsEndpoint::getQuotaVersionsByETag` |
| ✅ | GET | `/v2/surveys/{surveyId}/surveyQuotaFrame` | `SurveyQuotaFrameEndpoint::getQuotaFrame` |
| ✅ | PUT | `/v2/surveys/{surveyId}/surveyQuotaFrame` | `SurveyQuotaFrameEndpoint::setQuotaFrame` |
| ✅ | PUT | `/v2/surveys/{surveyId}/surveyQuotaFrame/{eTag}` | `SurveyQuotaFrameEndpoint::setQuotaLevelsTargets` |

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
| ✅ | DELETE | `/v2/surveys/{surveyId}/sample` | `SurveySampleCollectionEndpoint::destroy` |
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

*9/14 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveys` | `SurveyCollectionEndpoint::list / find` |
| ✅ | POST | `/v2/surveys` | `SurveyCollectionEndpoint::create` |
| ✅ | POST | `/v2/surveys/createSurveyFromBlueprint` | `SurveyCollectionEndpoint::createFromBlueprint` |
| ✅ | GET | `/v2/surveys/search` | `SurveyCollectionEndpoint::search` |
| ✅ | DELETE | `/v2/surveys/{surveyId}` | `SurveyEndpoint::destroy` |
| ✅ | GET | `/v2/surveys/{surveyId}` | `SurveyEndpoint::get` |
| ✅ | PATCH | `/v2/surveys/{surveyId}` | `SurveyEndpoint::updatePartial` |
| ✅ | GET | `/v2/surveys/{surveyId}/counts` | `SurveyEndpoint::counts` |
| ✅ | GET | `/v2/surveys/{surveyId}/customColumns` | `SurveyEndpoint::getCustomColumns` |
| ❌ | GET | `/v2/surveys/{surveyId}/dataRetentionSettings` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/dataRetentionSettings` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/respondentDataEncrypt` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/surveyGroup` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/versions` | — |

### Surveys — Settings & Content

*6/23 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ✅ | GET | `/v2/surveys/{surveyId}/generalSettings` | `SurveyGeneralSettingsEndpoint::getGeneralSettings` |
| ✅ | PATCH | `/v2/surveys/{surveyId}/generalSettings` | `SurveyGeneralSettingsEndpoint::updateGeneralSettings` |
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
| ❌ | GET | `/v2/surveys/{surveyId}/responseCodes` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/responseCodes` | — |
| ❌ | DELETE | `/v2/surveys/{surveyId}/responseCodes/{responseCode}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/responseCodes/{responseCode}` | — |
| ❌ | PATCH | `/v2/surveys/{surveyId}/responseCodes/{responseCode}` | — |
| ✅ | GET | `/v2/surveys/{surveyId}/settings` | `SurveySettingsEndpoint::listSettings` |
| ✅ | POST | `/v2/surveys/{surveyId}/settings` | `SurveySettingsEndpoint::addOrUpdateSetting` |

### Surveys — Publishing & Script

*3/13 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/surveys/{surveyId}/package` | — |
| ✅ | GET | `/v2/surveys/{surveyId}/publish` | `SurveyPublishEndpoint::getPublishState` |
| ✅ | PUT | `/v2/surveys/{surveyId}/publish` | `SurveyPublishEndpoint::publish` |
| ✅ | POST | `/v2/surveys/{surveyId}/publish/start` | `SurveyPublishEndpoint::startPublish` |
| ❌ | GET | `/v2/surveys/{surveyId}/script` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/script` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/script/{eTag}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/scriptFragments` | — |
| ❌ | DELETE | `/v2/surveys/{surveyId}/scriptFragments/{fragmentName}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/scriptFragments/{fragmentName}` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/scriptFragments/{fragmentName}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/varFile` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/varFile/{eTag}` | — |

### Surveys — Interviews & Data

*3/16 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/surveys/interviewSimulations` | — |
| ✅ | POST | `/v2/surveys/{surveyId}/dataDownload` | `SurveyDataEndpoint::downloadData` |
| ✅ | POST | `/v2/surveys/{surveyId}/dataDownload/{interviewId}` | `SurveyDataEndpoint::downloadInterviewData` |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewInteractionsSettings` | — |
| ❌ | PATCH | `/v2/surveys/{surveyId}/interviewInteractionsSettings` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewQuality` | — |
| ❌ | PUT | `/v2/surveys/{surveyId}/interviewQuality` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewQuality/{interviewId}` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewSimulation` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/interviewSimulations/downloadHints` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/interviewSimulations/startInterviewSimulations` | — |
| ✅ | DELETE | `/v2/surveys/{surveyId}/interviews/{interviewId}` | `SurveyInterviewEndpoint::deleteInterviewData` |
| ❌ | GET | `/v2/surveys/{surveyId}/manualTests` | — |
| ❌ | POST | `/v2/surveys/{surveyId}/manualTests` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/performance/metrics/live` | — |
| ❌ | GET | `/v2/surveys/{surveyId}/performance/metrics/test` | — |

### Access & Authentication

*2/20 implemented.*

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
| ❌ | GET | `/v2/requests` | — |
| ❌ | POST | `/v2/requests` | — |
| ❌ | PUT | `/v2/requests` | — |
| ❌ | DELETE | `/v2/requests/{requestId}` | — |
| ❌ | GET | `/v2/requests/{requestId}` | — |
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
| ❌ | GET | `/v2/parentSurveys` | — |
| ❌ | POST | `/v2/parentSurveys` | — |
| ❌ | GET | `/v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart` | — |
| ❌ | PUT | `/v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart` | — |
| ❌ | GET | `/v2/parentSurveys/{parentSurveyId}/waves` | — |
| ❌ | POST | `/v2/parentSurveys/{parentSurveyId}/waves` | — |
| ❌ | POST | `/v2/parentSurveys/{parentSurveyId}/waves/{waveId}` | — |
| ❌ | DELETE | `/v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` | — |
| ❌ | GET | `/v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` | — |
| ❌ | PUT | `/v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart` | — |
| ❌ | GET | `/v2/surveyWaves/{waveId}/startDate` | — |
| ❌ | PUT | `/v2/surveyWaves/{waveId}/startDate` | — |
| ❌ | GET | `/v2/surveyWaves/{waveId}/stopDate` | — |
| ❌ | PUT | `/v2/surveyWaves/{waveId}/stopDate` | — |

### Survey Groups

*0/12 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/surveyGroups` | — |
| ❌ | POST | `/v2/surveyGroups` | — |
| ❌ | DELETE | `/v2/surveyGroups/{surveyGroupId}` | — |
| ❌ | GET | `/v2/surveyGroups/{surveyGroupId}` | — |
| ❌ | PATCH | `/v2/surveyGroups/{surveyGroupId}` | — |
| ❌ | PUT | `/v2/surveyGroups/{surveyGroupId}/assignDirectory` | — |
| ❌ | PUT | `/v2/surveyGroups/{surveyGroupId}/assignLocal` | — |
| ❌ | GET | `/v2/surveyGroups/{surveyGroupId}/directoryAssignments` | — |
| ❌ | GET | `/v2/surveyGroups/{surveyGroupId}/localAssignments` | — |
| ❌ | GET | `/v2/surveyGroups/{surveyGroupId}/surveys` | — |
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

*0/5 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/events/subscriptions` | — |
| ❌ | POST | `/v2/events/subscriptions` | — |
| ❌ | DELETE | `/v2/events/subscriptions/{name}` | — |
| ❌ | GET | `/v2/events/subscriptions/{name}` | — |
| ❌ | PATCH | `/v2/events/subscriptions/{name}` | — |

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

*0/4 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/responseCodes` | — |
| ❌ | POST | `/v2/responseCodes` | — |
| ❌ | DELETE | `/v2/responseCodes/{responseCodeId}` | — |
| ❌ | PATCH | `/v2/responseCodes/{responseCodeId}` | — |

### Themes

*0/3 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/themes` | — |
| ❌ | PUT | `/v2/themes` | — |
| ❌ | DELETE | `/v2/themes/{themeId}` | — |

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

### Interviewers Worklog

*0/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | POST | `/v2/interviewersWorklog` | — |

### Manual Tests (tenant)

*0/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/manualTests` | — |

### Survey Resources

*0/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/surveyResources` | — |

### Templates

*0/1 implemented.*

| ✓ | Method | Path | Implementation |
|---|---|---|---|
| ❌ | GET | `/v2/templates` | — |


---

## Notes & observations

- **The package is survey-centric.** 76 of the 79 implemented operations live under `/v2/surveys`, `/v2/capiInterviewers`, `/v2/surveyBlueprints` and `/v2/BackgroundActivities`; the remaining 3 are the token endpoints. Whole top-level areas (Data Delivery, Survey Groups, Offices, Screeners, Parent Surveys/Waves, Themes, Templates) are untouched.
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

None. All 79 implemented operations resolve to a live method+path pair in the v2 spec. The v1 stack was fully removed (`930bf06`, finished in `3c5d8aa`) — `src/Endpoints/v1/` and `src/Services/v1/` no longer exist and nothing in `src/` references them.

### Stale leftovers in the code

These are not wrong API calls, but residue from the removed v1 stack and from a DTO layer that was never wired up.

**1. Duplicate DTO, dead copy** — `src/Data/NewCapiInterviewerRequestModel.php` (root `Data` namespace, `StudlyCaseMapper`, snake_case properties) is referenced by nothing. The live one is `src/Data/CapiInterviewers/NewCapiInterviewerRequestModel.php`. Delete the root copy.

**2. v1-era DTOs, unreferenced** — all use the v1 `#[MapInputName(StudlyCaseMapper::class)]` + snake_case convention and serve endpoints this package does not implement (response codes, survey-level interviewers):

- `src/Data/InterviewerDTO.php`, `InterviewerDetailsData.php`, `InterviewerAssignmentResponseData.php`, `InterviewerSamplingPointAssignmentData.php`
- `src/Data/ResponseCodeDTO.php`, `SurveyResponseCodeDTO.php`, `SurveyResponseCodeForPatch.php`, `DomainResponseCodeForPatch.php`
- `src/Data/SamplingPointData.php`, `SamplingPointQuotaTargetData.php`, `SamplingPointInterviewerAssignmentsData.php`
- `src/Data/SurveyPublishStateData.php`, `SurveyDataRequestDTO.php`, `SurveyFieldworkCountsDTO.php`, `BackgroundTaskDTO.php`

**3. `*DTO` vs `*Model` duplication** — several of the above duplicate newer v2-style models that are also unused, e.g. `Data/SurveyFieldworkCountsDTO` vs `Data/Surveys/SurveyFieldwork/SurveyFieldworkCountsResponseModel`. Pick one naming convention.

**4. Unused v2 request/response models** — written but never referenced, because the endpoints take raw `array` instead:

- `Data/Surveys/SamplingPoints/SamplingPointCreateRequestModel`, `SamplingPointCustomDataModel`
- `Data/Surveys/Sample/SurveyCreateSampleColumnModel`, `ClearSurveySampleModel`, `SampleFilterModel`
- `Data/Surveys/SurveyQuota/SurveysQuotaTargetsResponseModel`, `SurveysQuotaTargetsEtagResponseModel`
- `Data/SurveyQuotaFrame/SurveyQuotaFrameData`, `SurveyQuotaFrameResponseData`, `Data/Quota/QuotaAttribute`

`Data/Surveys/Sample/SampleFilterModel` is an empty stub (`class SampleFilterModel {}`) — either implement it or drop it.

**5. Unused enums where the typed value is available** — `Enums/SurveyFieldworkStatusEnum` and `Enums/InterviewingRestrictionTypeEnum` are unreferenced, yet `SurveyFieldworkService::status()` returns a bare `int` and `stop()` takes a raw `array` whose only field is `interviewingRestrictionType`. Wiring these up is the cheapest type-safety win in the package.

### Minor drift in implemented calls

| Where | Detail | Impact |
|---|---|---|
| `BackgroundActivitiesEndpoint::buildPath()` | Requests `/v2/backgroundActivities`; the spec path is `/v2/BackgroundActivities`. | None in practice (ASP.NET routing is case-insensitive), but it will not match a spec-driven test or mock. |
| `SurveyCollectionEndpoint::search()` | Sends query param `Value`; the spec declares `value`. | Same — case-insensitive binding, cosmetic only. |
| `SurveySettingsEndpoint::buildPath()` | Hardcodes `'/v2/surveys'` instead of `"/{$this->version}/surveys"`. | Inconsistent with the other 21 endpoints. |

**All three were fixed in #35** (`f97a8dc`). The endpoint-class layout was then settled in #40 + #43: `batchActivate` moved to `SurveyEndpoint`, the quota / settings / sample / interviews / CAPI-office paths were split onto their own classes, and `tests/ArchTest.php` now fails if a class spans two spec path prefixes. The class names in the tables above reflect that layout.

### Stale documentation

`CLAUDE.md` (lines 31, 49, 136) and `AGENTS.md` (lines 74, 128) all still state that v1 endpoints live in `src/Endpoints/v1/` "for reference". Those directories were deleted; the guidance is misleading for anyone (or any agent) reading the repo now.

---

## Pending endpoints (flat list)

Everything not yet implemented, grouped by section.

**Access & Authentication** (18 pending)

- `DELETE /v2/domainAssignments`
- `POST /v2/domainAssignments`
- `GET /v2/localUsers`
- `POST /v2/localUsers`
- `DELETE /v2/localUsers/{identityId}`
- `GET /v2/localUsers/{identityId}`
- `PATCH /v2/localUsers/{identityId}`
- `PATCH /v2/localUsers/{identityId}/password`
- `POST /v2/localUsersLogs`
- `GET /v2/me/role`
- `GET /v2/passwordSettings`
- `PATCH /v2/passwordSettings`
- `GET /v2/requests`
- `POST /v2/requests`
- `PUT /v2/requests`
- `DELETE /v2/requests/{requestId}`
- `GET /v2/requests/{requestId}`
- `GET /v2/roles`

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

**Event Subscriptions** (5 pending)

- `GET /v2/events/subscriptions`
- `POST /v2/events/subscriptions`
- `DELETE /v2/events/subscriptions/{name}`
- `GET /v2/events/subscriptions/{name}`
- `PATCH /v2/events/subscriptions/{name}`

**Interviewers Worklog** (1 pending)

- `POST /v2/interviewersWorklog`

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

- `GET /v2/parentSurveys`
- `POST /v2/parentSurveys`
- `GET /v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart`
- `PUT /v2/parentSurveys/{parentSurveyId}/checkMinSuccessfulsBeforeAutoStart`
- `GET /v2/parentSurveys/{parentSurveyId}/waves`
- `POST /v2/parentSurveys/{parentSurveyId}/waves`
- `POST /v2/parentSurveys/{parentSurveyId}/waves/{waveId}`
- `DELETE /v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart`
- `GET /v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart`
- `PUT /v2/surveyWaves/{waveId}/minSuccessfulsBeforeAutoStart`
- `GET /v2/surveyWaves/{waveId}/startDate`
- `PUT /v2/surveyWaves/{waveId}/startDate`
- `GET /v2/surveyWaves/{waveId}/stopDate`
- `PUT /v2/surveyWaves/{waveId}/stopDate`

**Response Codes (tenant)** (4 pending)

- `GET /v2/responseCodes`
- `POST /v2/responseCodes`
- `DELETE /v2/responseCodes/{responseCodeId}`
- `PATCH /v2/responseCodes/{responseCodeId}`

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

**Survey Groups** (12 pending)

- `GET /v2/surveyGroups`
- `POST /v2/surveyGroups`
- `DELETE /v2/surveyGroups/{surveyGroupId}`
- `GET /v2/surveyGroups/{surveyGroupId}`
- `PATCH /v2/surveyGroups/{surveyGroupId}`
- `PUT /v2/surveyGroups/{surveyGroupId}/assignDirectory`
- `PUT /v2/surveyGroups/{surveyGroupId}/assignLocal`
- `GET /v2/surveyGroups/{surveyGroupId}/directoryAssignments`
- `GET /v2/surveyGroups/{surveyGroupId}/localAssignments`
- `GET /v2/surveyGroups/{surveyGroupId}/surveys`
- `PUT /v2/surveyGroups/{surveyGroupId}/unassignDirectory`
- `PUT /v2/surveyGroups/{surveyGroupId}/unassignLocal`

**Survey Resources** (1 pending)

- `GET /v2/surveyResources`

**Surveys — Core** (5 pending)

- `GET /v2/surveys/{surveyId}/dataRetentionSettings`
- `PUT /v2/surveys/{surveyId}/dataRetentionSettings`
- `POST /v2/surveys/{surveyId}/respondentDataEncrypt`
- `PUT /v2/surveys/{surveyId}/surveyGroup`
- `GET /v2/surveys/{surveyId}/versions`

**Surveys — Interviewers & Assignments** (7 pending)

- `GET /v2/surveys/{surveyId}/interviewers`
- `POST /v2/surveys/{surveyId}/interviewers`
- `POST /v2/surveys/{surveyId}/interviewers/distributeWorkpackageTarget`
- `PUT /v2/surveys/{surveyId}/interviewers/{interviewerId}/assign`
- `GET /v2/surveys/{surveyId}/interviewers/{interviewerId}/quotaLevelTargets`
- `PUT /v2/surveys/{surveyId}/interviewers/{interviewerId}/quotaLevelTargets`
- `PUT /v2/surveys/{surveyId}/interviewers/{interviewerId}/unassign`

**Surveys — Interviews & Data** (13 pending)

- `GET /v2/surveys/interviewSimulations`
- `GET /v2/surveys/{surveyId}/interviewInteractionsSettings`
- `PATCH /v2/surveys/{surveyId}/interviewInteractionsSettings`
- `GET /v2/surveys/{surveyId}/interviewQuality`
- `PUT /v2/surveys/{surveyId}/interviewQuality`
- `GET /v2/surveys/{surveyId}/interviewQuality/{interviewId}`
- `GET /v2/surveys/{surveyId}/interviewSimulation`
- `GET /v2/surveys/{surveyId}/interviewSimulations/downloadHints`
- `POST /v2/surveys/{surveyId}/interviewSimulations/startInterviewSimulations`
- `GET /v2/surveys/{surveyId}/manualTests`
- `POST /v2/surveys/{surveyId}/manualTests`
- `GET /v2/surveys/{surveyId}/performance/metrics/live`
- `GET /v2/surveys/{surveyId}/performance/metrics/test`

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

**Surveys — Publishing & Script** (10 pending)

- `GET /v2/surveys/{surveyId}/package`
- `GET /v2/surveys/{surveyId}/script`
- `POST /v2/surveys/{surveyId}/script`
- `GET /v2/surveys/{surveyId}/script/{eTag}`
- `GET /v2/surveys/{surveyId}/scriptFragments`
- `DELETE /v2/surveys/{surveyId}/scriptFragments/{fragmentName}`
- `GET /v2/surveys/{surveyId}/scriptFragments/{fragmentName}`
- `POST /v2/surveys/{surveyId}/scriptFragments/{fragmentName}`
- `GET /v2/surveys/{surveyId}/varFile`
- `GET /v2/surveys/{surveyId}/varFile/{eTag}`

**Surveys — Sample** (2 pending)

- `GET /v2/surveys/{surveyId}/sampleMask`
- `PUT /v2/surveys/{surveyId}/sampleMask`

**Surveys — Sampling Points** (3 pending)

- `DELETE /v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image`
- `GET /v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image`
- `POST /v2/surveys/{surveyId}/samplingPoint/{samplingPointId}/image/{fileName}`

**Surveys — Settings & Content** (17 pending)

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
- `GET /v2/surveys/{surveyId}/responseCodes`
- `POST /v2/surveys/{surveyId}/responseCodes`
- `DELETE /v2/surveys/{surveyId}/responseCodes/{responseCode}`
- `GET /v2/surveys/{surveyId}/responseCodes/{responseCode}`
- `PATCH /v2/surveys/{surveyId}/responseCodes/{responseCode}`

**Templates** (1 pending)

- `GET /v2/templates`

**Themes** (3 pending)

- `GET /v2/themes`
- `PUT /v2/themes`
- `DELETE /v2/themes/{themeId}`
