## 1. DTOs and Enums

- [x] 1.1 Create `InterviewQualityEnum` in `src/Enums` (cases: NotChecked=0, Approved=1, Unverified=2, Rejected=3, MarkedToReject=4, ToBeChecked=5) and verify by ensuring `composer run analyse` reports no errors.
- [x] 1.2 Create `InterviewDetailsData` in `src/Data/Surveys/` (with 5 properties: id, interviewQuality, interviewerId, samplingPointId, officeId) and verify structure manually and with PHPStan.
- [x] 1.3 Create `ManagerInterviewDetailsData` in `src/Data/Surveys/` (with 17 properties matching PUT response) and verify structure manually and with PHPStan.
- [x] 1.4 Create `QualityNewStateChangeData` in `src/Data/Surveys/` (with properties: interviewId, newState) and verify structure manually and with PHPStan.

## 2. Contracts and Endpoints

- [x] 2.1 Create `SurveyInterviewQualityCollectionEndpointInterface` and `SurveyInterviewQualityEndpointInterface` in `src/Contracts/Endpoints/` and verify by ensuring they extend correct contracts.
- [x] 2.2 Implement `SurveyInterviewQualityCollectionEndpoint` in `src/Endpoints/v2/` with GET and PUT methods using `subResourcePath($surveyId, 'interviewQuality')` and verify by ensuring `tests/ArchTest.php` passes.
- [x] 2.3 Implement `SurveyInterviewQualityEndpoint` in `src/Endpoints/v2/` with GET method using `subResourceItemPath($surveyId, 'interviewQuality', $interviewId)` and verify by ensuring `tests/ArchTest.php` passes.
- [x] 2.4 Register the two new endpoint contracts to their implementations in `NfieldAdminServiceProvider::registeringPackage()` and verify `composer test` doesn't break.

## 3. Services and Resources

- [x] 3.1 Create `SurveyInterviewQualityService` in `src/Services/` that implements `SurveyScopedInterface` and maps endpoint calls to DTOs, and verify by ensuring no un-typed returns or arrays exist (`ArchTest.php`).
- [x] 3.2 Add `interviewQuality()` method to `src/Resources/SurveyResource.php` that resolves `SurveyInterviewQualityService`, and verify the SDK exposes the new API correctly using a dummy Pest test.
