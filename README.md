# NField Admin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nikoleesg/nfield-admin.svg?style=flat-square)](https://packagist.org/packages/nikoleesg/nfield-admin)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nikoleesg/nfield-admin/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nikoleesg/nfield-admin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nikoleesg/nfield-admin/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nikoleesg/nfield-admin/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nikoleesg/nfield-admin.svg?style=flat-square)](https://packagist.org/packages/nikoleesg/nfield-admin)

A Laravel SDK for the [NField](https://www.nipo.com/) v2 Admin API.

It wraps surveys, fieldwork, sample, sampling points, addresses, quota, publishing, settings and CAPI interviewers behind one fluent entry point. Authentication and token caching are handled for you, every response is a typed [Spatie Laravel Data](https://spatie.be/docs/laravel-data) object, and a list is always an `Illuminate\Support\Collection` — never a raw array you have to know the wire format of.

```php
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;

$survey = NfieldManager::withSurvey('survey-id');

$survey->getSurvey()->surveyName;        // SurveyModel
$survey->fieldwork()->start();
$survey->fieldwork()->counts()->successful;
$survey->samples()->downloadSampleData(); // Collection of sample rows
```

## Requirements

- PHP 8.2+
- Laravel 10, 11 or 12

## Installation

```bash
composer require nikoleesg/nfield-admin
```

Publish the config file:

```bash
php artisan vendor:publish --tag="nfield-admin-config"
```

Then set your NField credentials in `.env`:

```dotenv
NFIELD_DOMAIN=your-domain
NFIELD_USERNAME=your-username
NFIELD_PASSWORD=your-password
NFIELD_BASE_URL=https://apiap.nfieldmr.com
```

The published config also controls token caching:

```php
return [
    'domain' => env('NFIELD_DOMAIN', 'Nfield'),
    'username' => env('NFIELD_USERNAME', 'username'),
    'password' => env('NFIELD_PASSWORD', 'password'),
    'base_url' => env('NFIELD_BASE_URL', 'https://apiap.nfieldmr.com'),

    'cache' => [
        // Set to false to re-authenticate on every request.
        'enabled' => env('NFIELD_CACHE_ENABLED', true),

        // null uses the application's default cache store.
        'store' => env('NFIELD_CACHE_STORE'),

        'prefix' => env('NFIELD_CACHE_KEY_PREFIX', 'nfield_'),

        // Upper bound on the token TTL, in seconds. The API's own `expiresIn`
        // caps it further.
        'ttl' => 60 * 10,
    ],
];
```

## Usage

`NfieldManager` is the entry point. Everything else is reached by narrowing the
scope — a survey, then a sampling point, then an address.

### Surveys

```php
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;

NfieldManager::listSurveys();                            // Collection<SurveyModel>
NfieldManager::findSurveys(['surveyName' => 'Wave 1']);  // Collection<SurveyModel>
NfieldManager::searchRespondent('ada@example.com');      // Collection<SurveyBaseModel>

NfieldManager::createSurvey([
    'surveyName' => 'Wave 1',
    'clientName' => 'Acme',
    'surveyType' => 'Capi',
]);

NfieldManager::createSurveyFromBlueprint([
    'surveyName' => 'Wave 2',
    'blueprintSurveyId' => 'blueprint-id',
]);
```

Every method that takes a body accepts either an array or the matching request
model, so the model is documentation you can type-hint against rather than
something you are forced to build:

```php
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel;

NfieldManager::createSurvey(new SurveyCreateModel(
    surveyName: 'Wave 1',
    clientName: 'Acme',
    surveyType: 'Capi',
));
```

### One survey

```php
$survey = NfieldManager::withSurvey('survey-id');

$survey->getSurvey();                                  // SurveyModel
$survey->updateSurvey(['surveyName' => 'Wave 1 (rev)']);
$survey->deleteSurvey();
$survey->getSurveyCounts();                            // SurveyCountsModel
$survey->getCustomColumns();                           // Collection<string>
$survey->requestDataDownload(['fileName' => 'wave-1.zip']);
```

### Fieldwork

```php
$fieldwork = $survey->fieldwork();

$fieldwork->start();
$fieldwork->stop();                                    // blocks everything by default
$fieldwork->stop(InterviewingRestrictionTypeEnum::AllowOnlyActives);

$fieldwork->status();                                  // ?SurveyFieldworkStatusEnum
$fieldwork->statusCode();                              // the raw integer
$fieldwork->counts();                                  // SurveyFieldworkCountsResponseModel
```

### Sample

```php
$samples = $survey->samples();

$samples->downloadSampleData();                        // Collection of parsed rows
$samples->uploadSampleData($tsv, 'wave-1.csv');        // SampleUploadStatus
$samples->createSampleData([['columnName' => 'Phone', 'value' => '555']]);
$samples->blockSampleData([['name' => 'Status', 'op' => 'eq', 'value' => 'Open']]);
$samples->resetSampleData([['name' => 'Status', 'op' => 'eq', 'value' => 'Open']]);
$samples->clearSampleDataColumns(['columns' => ['Phone']]);
$samples->requestSampleDownload();                     // BackgroundActivityStatus

$record = $samples->forInterview(7);

$record->getSampleRecord();                            // ?Collection of one row
$record->updateSampleRecord(['sampleRecordId' => 7, 'columnUpdates' => [...]]);
$record->deleteSampleData([['name' => 'Status', 'op' => 'eq', 'value' => 'Open']]);
```

A sample record has no fixed shape — its columns are defined per survey — so it
comes back as a `Collection` of the parsed row rather than as a DTO.

### Sampling points and addresses

```php
$samplingPoints = $survey->samplingPoints();

$samplingPoints->listSamplingPoints();                 // Collection<SamplingPointResponseModel>
$samplingPoints->findSamplingPoints(['kind' => 1]);
$samplingPoints->createSamplingPoint(['name' => 'SP 1']);
$samplingPoints->activateSamplingPoints(['sp-1', 'sp-2']);

$samplingPoint = $samplingPoints->forSamplingPoint('sp-1');
// or straight from the manager:
$samplingPoint = NfieldManager::withSurveySamplingPoint('survey-id', 'sp-1');

$samplingPoint->getSamplingPoint();
$samplingPoint->updateSamplingPoint(['name' => 'SP 1 (rev)']);
$samplingPoint->activateSamplingPoint(['target' => 5]);
$samplingPoint->replaceSamplingPoint(['spareSamplingPointId' => 'sp-9']);
$samplingPoint->deleteSamplingPoint();

$samplingPoint->assignments()->listAssignments();
$samplingPoint->assignments()->assignInterviewer('interviewer-id');
$samplingPoint->assignments()->unassignInterviewer('interviewer-id');

$samplingPoint->quotaTargets()->listQuotaTargets();
$samplingPoint->quotaTargets()->setQuotaTargets('level-id', ['target' => 20]);

$addresses = $samplingPoint->addresses();

$addresses->listAddresses();                           // Collection<AddressModel>
$addresses->createAddress(['details' => '1 Example Street']);
$addresses->forAddress('address-id')->getAddress();
$addresses->forAddress('address-id')->deleteAddress();
```

Interviewers can also be assigned across many sampling points at once:

```php
$survey->assignments()->assignInterviewers(['sp-1', 'sp-2'], ['interviewer-id']);
$survey->assignments()->unassignInterviewers(['sp-1', 'sp-2'], ['interviewer-id']);
```

### Quota

```php
$quota = $survey->quota();

$quota->getQuotaFrame();                               // SurveysQuotaFrameResponseModel
$quota->setQuotaFrame(['target' => 1000, 'variableDefinitions' => [], 'frameVariables' => []]);
$quota->setQuotaLevelsTargets($eTag, ['levels' => [...]]);

$quota->getQuotaTargets();
$quota->getQuotaTargetsByETag(7);
$quota->getQuotaVersions();                            // Collection<QuotaFrameVersionModel>
$quota->getQuotaVersionsByETag(7);
```

### Publishing, settings and public ids

```php
$survey->publish()->getState();                        // SurveyPublishStateModel
$survey->publish()->publishLive();
$survey->publish()->forcePublishLive();
$survey->publish()->publishTest();
$survey->publish()->startPublishLive();                // BackgroundActivityStatus

$survey->settings()->list();                           // Collection<SurveySettingModel>
$survey->settings()->set(SurveySettingNameEnum::HideQuotaPage, 'true');
$survey->settings()->getGeneral();                     // SurveyGeneralSettingsModel
$survey->settings()->updateGeneral(['description' => 'Wave 1']);

$survey->samplingMethod()->getSamplingMethod();
$survey->samplingMethod()->setSamplingMethod(['samplingMethod' => 'Random']);

$survey->publicIds()->list();                          // Collection<SurveyPublicIdModel>
$survey->publicIds()->update($models);
```

### Data delivery

Downloads are asynchronous: the API returns a background activity you poll.

```php
$activity = $survey->data()->downloadData([
    'fileName' => 'wave-1.zip',
    'startDate' => '2026-01-01',
    'includeSuccessful' => true,
]);

NfieldManager::getBackgroundActivity($activity->activityId)->status;  // ActivityStatusEnum

$survey->data()->downloadInterviewData('interview-id', 'one.zip');
$survey->data()->deleteInterviewData('interview-id');
```

### CAPI interviewers

```php
NfieldManager::capiInterviewers()->list();                 // Collection<CapiInterviewerModel>
NfieldManager::capiInterviewers()->find(['officeId' => 'office-id']);
NfieldManager::capiInterviewers()->getByClientId('client-interviewer-id');

NfieldManager::capiInterviewers()->create([
    'userName' => 'ada',
    'password' => '...',
    'emailAddress' => 'ada@example.com',
]);

$interviewer = NfieldManager::capiInterviewers()->forInterviewer('interviewer-id');

$interviewer->get();                                   // CapiInterviewerModel
$interviewer->update(['firstName' => 'Ada']);
$interviewer->resetPassword(['password' => '...']);
$interviewer->delete();

$interviewer->getAssignments();                        // Collection<CapiInterviewerAssignmentModel>
$interviewer->getOffices();                            // Collection<string>
$interviewer->updateOffice('office-id');
$interviewer->deleteOffice('office-id');
```

### Blueprint surveys

```php
NfieldManager::withBlueprintSurvey('blueprint-id')->update([
    'surveyId' => 'survey-id',
    'includedConfiguration' => BlueprintConfigurationEnum::All,
]);
```

## Error handling

A failed request throws a typed exception carrying the status and the response
body. A 401 is retried once with a fresh token before it surfaces.

```php
use Nikoleesg\NfieldAdmin\Exceptions\ApiRequestException;
use Nikoleesg\NfieldAdmin\Exceptions\AuthenticationException;
use Nikoleesg\NfieldAdmin\Exceptions\NotFoundException;
use Nikoleesg\NfieldAdmin\Exceptions\ValidationException;

try {
    NfieldManager::withSurvey('does-not-exist')->getSurvey();
} catch (NotFoundException $e) {
    $e->getCode();  // 404
    $e->body();     // the raw response body
}
```

`AuthenticationException` (401 after the retry), `NotFoundException` (404) and
`ValidationException` (422) each extend `ApiRequestException`, which is what
every other failing status throws.

Calling a scoped service before its scope is set throws
`MissingScopeException` rather than sending a request to a malformed URL.

## Conventions

- **Responses are PascalCase on the wire**, and normalised to camelCase once, in
  `HttpClient`. Nothing below that boundary — endpoints, services, DTOs — ever
  sees PascalCase, and no DTO carries a casing mapper.
- **Input is `array|RequestModel`**, normalised through the request model, so one
  class owns each wire format.
- **Output is always a DTO** or an `Illuminate\Support\Collection` of DTOs.
- **Models are named `*Model`.** The `*DTO` and `*Data` suffixes are retired.

## Testing

```bash
composer test          # Pest
composer run analyse   # PHPStan level 4
composer run format    # Pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for what has changed recently.

## Credits

- [Niko Lee](https://github.com/nikoleesg)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
