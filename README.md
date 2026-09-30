# NField Admin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nikoleesg/nfield-admin.svg?style=flat-square)](https://packagist.org/packages/nikoleesg/nfield-admin)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nikoleesg/nfield-admin/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nikoleesg/nfield-admin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nikoleesg/nfield-admin/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nikoleesg/nfield-admin/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nikoleesg/nfield-admin.svg?style=flat-square)](https://packagist.org/packages/nikoleesg/nfield-admin)

A Laravel SDK for the [NField](https://www.nipo.com/) v2 Admin API.

It wraps surveys, fieldwork, sample, sampling points, addresses, quota, publishing, settings and CAPI interviewers behind one fluent entry point. Authentication and token caching are handled for you, every response is a typed [Spatie Laravel Data](https://spatie.be/docs/laravel-data) object, and a list is always an `Illuminate\Support\Collection` — never a raw array you have to know the wire format of.

```php
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;

$survey = NfieldManager::surveys()->forSurvey('survey-id');

$survey->get()->surveyName;              // SurveyModel
$survey->fieldwork()->start();
$survey->fieldwork()->counts()->successful;
$survey->samples()->download();          // Collection of sample rows
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

Then set your NField credentials in `.env` (`NFIELD_DOMAIN`, `NFIELD_USERNAME`, and `NFIELD_PASSWORD` are required):

```dotenv
NFIELD_DOMAIN=your-domain
NFIELD_USERNAME=your-username
NFIELD_PASSWORD=your-password
NFIELD_BASE_URL=https://apiap.nfieldmr.com
```

The published config defaults credentials to `null` so missing configuration fails fast at first use with an `InvalidConfigurationException`, and controls token caching:

```php
return [
    'domain' => env('NFIELD_DOMAIN'),
    'username' => env('NFIELD_USERNAME'),
    'password' => env('NFIELD_PASSWORD'),
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

$surveys = NfieldManager::surveys();

$surveys->list();                                  // Collection<SurveyModel>
$surveys->find(['surveyName' => 'Wave 1']);       // Collection<SurveyModel>
$surveys->searchRespondent('ada@example.com');    // Collection<SurveyBaseModel>

$surveys->create([
    'surveyName' => 'Wave 1',
    'clientName' => 'Acme',
    'surveyType' => 'Capi',
]);

$surveys->createFromBlueprint([
    'surveyName' => 'Wave 2',
    'blueprintSurveyId' => 'blueprint-id',
]);
```

Every method that takes a body accepts either an array or the matching request
model, so the model is documentation you can type-hint against rather than
something you are forced to build:

```php
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel;

NfieldManager::surveys()->create(new SurveyCreateModel(
    surveyName: 'Wave 1',
    clientName: 'Acme',
    surveyType: 'Capi',
));
```

### One survey

```php
$survey = NfieldManager::surveys()->forSurvey('survey-id');

$survey->get();                                        // SurveyModel
$survey->update(['surveyName' => 'Wave 1 (rev)']);
$survey->delete();
$survey->counts();                                     // SurveyCountsModel
$survey->customColumns();                              // Collection<string>
$survey->requestDownload(['fileName' => 'wave-1.zip']); // same as data()->download()
$survey->moveToGroup(7);                               // SurveyMoveModel
$survey->versions()->list();                           // Collection<SurveyVersionModel>
$survey->dataRetentionSettings()->get();               // retentionPeriod + possibleValues, in days
$survey->dataRetentionSettings()->update(365);
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

$samples->download();                                  // Collection of parsed rows
$samples->upload($tsv, 'wave-1.csv');                  // SampleUploadStatus
$samples->createColumns([['columnName' => 'Phone', 'value' => '555']]);
$samples->block([['name' => 'Status', 'op' => 'eq', 'value' => 'Open']]);
$samples->reset([['name' => 'Status', 'op' => 'eq', 'value' => 'Open']]);
$samples->delete([['name' => 'Status', 'op' => 'eq', 'value' => 'Open']]);
$samples->clearColumns(['columns' => ['Phone']]);
$samples->requestDownload();                           // BackgroundActivityStatus

$record = $samples->forInterview(7);

$record->get();                                        // ?Collection of one row
$record->update([['columnName' => 'Phone', 'value' => '555']]); // record ID is 7
```

A sample record has no fixed shape — its columns are defined per survey — so it
comes back as a `Collection` of the parsed row rather than as a DTO.

### Sampling points and addresses

```php
$samplingPoints = $survey->samplingPoints();

$samplingPoints->list();                       // Collection<SamplingPointResponseModel>
$samplingPoints->find(['kind' => 1]);
$samplingPoints->create(['name' => 'SP 1']);
$samplingPoints->activate(['sp-1', 'sp-2']);

$samplingPoint = $samplingPoints->forSamplingPoint('sp-1');
// or straight from the manager:
$samplingPoint = NfieldManager::surveys()->forSurvey('survey-id')->samplingPoints()->forSamplingPoint('sp-1');

$samplingPoint->get();
$samplingPoint->update(['name' => 'SP 1 (rev)']);
$samplingPoint->activate(['target' => 5]);
$samplingPoint->replace(['spareSamplingPointId' => 'sp-9']);
$samplingPoint->delete();

$samplingPoint->assignments()->list();
$samplingPoint->assignments()->assign('interviewer-id');
$samplingPoint->assignments()->unassign('interviewer-id');

$samplingPoint->quotaTargets()->list();
$samplingPoint->quotaTargets()->forQuotaLevel('level-id')->update(['target' => 20]);

$addresses = $samplingPoint->addresses();

$addresses->list();                           // Collection<AddressModel>
$addresses->create(['details' => '1 Example Street']);
$addresses->forAddress('address-id')->get();
$addresses->forAddress('address-id')->delete();
```

Interviewers can also be assigned across many sampling points at once:

```php
$survey->assignments()->assignInterviewers(['sp-1', 'sp-2'], ['interviewer-id']);
$survey->assignments()->unassignInterviewers(['sp-1', 'sp-2'], ['interviewer-id']);
```

### Quota

```php
$quota = $survey->quota();

$quota->frame()->get();                                // SurveysQuotaFrameResponseModel
$quota->frame()->update(['target' => 1000, 'variableDefinitions' => [], 'frameVariables' => []]);

$quota->targets()->get();                              // targets of the current frame
$quota->targets()->forVersion($eTag)->get();           // targets of one version
$quota->targets()->forVersion($eTag)->update(['levels' => [...]]);

$quota->versions()->list();                            // Collection<QuotaFrameVersionModel>
$quota->versions()->forVersion($eTag)->get();          // QuotaFrameModel
```

### Publishing, settings and public ids

```php
$survey->publish()->state();                           // SurveyPublishStateModel
$survey->publish()->publish(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::NoUpgrade);
$survey->publish()->start(SurveyPackageTypeEnum::Test, SurveyPublishForceUpgradeEnum::ForceUpgrade);  // BackgroundActivityStatus

$survey->settings()->list();                           // Collection<SurveySettingModel>
$survey->settings()->set(SurveySettingNameEnum::HideQuotaPage, 'true');
$survey->generalSettings()->get();                     // SurveyGeneralSettingsModel
$survey->generalSettings()->update(['description' => 'Wave 1']);

$survey->samplingMethod()->get();
$survey->samplingMethod()->update(['samplingMethod' => 'Random']);

$survey->publicIds()->list();                          // Collection<SurveyPublicIdModel>
$survey->publicIds()->update($models);

$survey->responseCodes()->list();                      // Collection<SurveyResponseCodeModel>
$survey->responseCodes()->create(['responseCode' => 210, 'description' => 'Callback']);
$survey->responseCodes()->forResponseCode(210)->update(['description' => 'Call back later']);
$survey->responseCodes()->forResponseCode(210)->delete();

$survey->script()->get();                              // SurveyGetScriptModel: ODIN script + file name
$survey->script()->update($odin);                      // string, array or SurveySetScriptModel; returns parse warnings
$survey->varFile()->get();                             // SurveyVarFileModel
$survey->package()->get(SurveyPackageTypeEnum::Test);  // SurveyPackageV1Model; live by default

$version = $survey->versions()->forVersion($eTag);     // an eTag from versions()->list()
$version->script();
$version->varFile();

$survey->performance()->live();                        // SurveyMetricsModel: warn/block counts per metric
$survey->performance()->test();
```

### Data delivery

Downloads are asynchronous: the API returns a background activity you poll.

```php
$activity = $survey->data()->download([
    'fileName' => 'wave-1.zip',
    'startDate' => '2026-01-01',
    'includeSuccessful' => true,
]);

NfieldManager::backgroundActivities()->forActivity($activity->activityId)->get()->status;  // ActivityStatusEnum

$interview = $survey->data()->forInterview(42);

$interview->download('one.zip');   // file name is optional
$interview->delete();
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

$interviewer->assignments();                           // Collection<CapiInterviewerAssignmentModel>
$interviewer->offices();                               // Collection<string>
$interviewer->assignOffice('office-id');
$interviewer->unassignOffice('office-id');
```

### Interviewers worklog

```php
$activity = NfieldManager::interviewersWorklog()->download([
    'from' => '2026-09-01',
    'to' => '2026-09-30 23:59:59',
]);                                                    // BackgroundActivityStatus; dates are sent in UTC

NfieldManager::backgroundActivities()->forActivity($activity->activityId)->get()->status;
```

### Parent surveys and waves

A parent survey groups waves; each wave is a survey in its own right, managed
through `NfieldManager::surveys()->forSurvey($waveId)`.

```php
$parents = NfieldManager::parentSurveys();

$parents->list();                                      // Collection<SurveyModel>
$parent = $parents->create(['surveyName' => 'Brand tracker']);

$tracker = $parents->forParentSurvey('parent-id');
$tracker->updateCheckMinSuccessfulsBeforeAutoStart(true);

$tracker->waves()->list();                             // Collection<SurveyModel>
$tracker->waves()->create(['surveyName' => 'Wave 1']);
$tracker->waves()->forWave('wave-id')->copy('Wave 2'); // copy needs the parent

// Wave settings, by the wave id alone:
$wave = NfieldManager::surveyWaves()->forWave('wave-id');
$wave->updateMinSuccessfulsBeforeAutoStart(100);
$wave->updateStartDate('2026-10-01');
$wave->updateStopDate(null);                           // null clears the date
```

### Survey groups

```php
$groups = NfieldManager::surveyGroups();

$groups->list();                                       // Collection<SurveyGroupModel>

$group = $groups->forSurveyGroup(7);

$group->get();                                         // SurveyGroupModel
$group->surveys(['$top' => 10]);                       // Collection<SurveyModel>, OData query optional
$group->directoryAssignments();                        // Collection<SurveyGroupDirectoryAssignmentModel>
$group->localAssignments();                            // Collection<SurveyGroupLocalAssignmentModel>
```

### Survey resource usage

```php
// GET /v2/surveyResources: size and retention dates per survey
NfieldManager::surveyResourceUsage()->list();          // Collection<SurveyResourceUsageModel>
NfieldManager::surveyResourceUsage()->find(['$filter' => 'State eq 1']);
```

### Blueprint surveys

```php
NfieldManager::surveys()->forBlueprintSurvey('blueprint-id')->update([
    'surveyId' => 'survey-id',
    'includedConfiguration' => BlueprintConfigurationEnum::All,
]);
```

### Event subscriptions

```php
$subscriptions = NfieldManager::eventSubscriptions();

$subscriptions->list();                                // Collection<SubscriptionModel>
$subscriptions->create([
    'eventSubscriptionName' => 'fieldwork-events',
    'endpoint' => 'https://example.com/webhook',
    'eventTypes' => ['...'],
]);

$subscription = $subscriptions->forSubscription('fieldwork-events');

$subscription->get();                                  // SubscriptionModel
$subscription->update(['eventTypes' => ['...']]);      // only the fields you pass are sent
$subscription->delete();
```

### Request configurations

Configurations for the scripting `*REQUEST` command (`/v2/requests` in the API).

```php
$configs = NfieldManager::requestConfigurations();

$configs->list();                                      // Collection<RequestConfigurationModel>
$configs->findByName('crm');                           // ?RequestConfigurationModel
$configs->create([
    'name' => 'crm',
    'uri' => 'https://crm.example.com/hook',
    'requestHttpMethod' => RequestHttpMethodEnum::Post,
]);                                                    // the API returns no body

$config = $configs->forRequestConfiguration(12);

$config->get();
$config->update([...]);                                // full replace; the id comes from the scope
$config->delete();
```

### Response codes (domain)

```php
$codes = NfieldManager::responseCodes();

$codes->list();                                        // Collection<DomainResponseCodeResponseModel>
$codes->create(['id' => 210, 'description' => 'Discarded', 'isDefinite' => true]);

$codes->forResponseCode(210)->update(['url' => 'https://example.com']);  // only the fields you pass are sent
$codes->forResponseCode(210)->delete();
```

### Themes

```php
NfieldManager::themes()->upload('template-id', 'Brand A', $zipContents, 'brand-a.zip');  // BackgroundActivityResponseModel

NfieldManager::themes()->forTheme('theme-id')->downloadUrl()->url;
NfieldManager::themes()->forTheme('theme-id')->delete();
```

### Roles

```php
NfieldManager::roles()->current();                     // UserRoleModel for the current user
NfieldManager::roles()->list();                        // role name => Collection<PermissionModel>
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
    NfieldManager::surveys()->forSurvey('does-not-exist')->get();
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
Missing credentials (`domain`, `username`, or `password`) throw
`InvalidConfigurationException` before any network request is attempted.

## Conventions

- **Responses are PascalCase on the wire**, and normalised to camelCase once, in
  `HttpClient`. Nothing below that boundary — endpoints, services, DTOs — ever
  sees PascalCase, and no DTO carries a casing mapper.
- **Input is `array|RequestModel`**, normalised through the request model, so one
  class owns each wire format.
- **Output is always a DTO** or an `Illuminate\Support\Collection` of DTOs.
- **Models are named `*Model`.** The `*DTO` and `*Data` suffixes are retired.
- **Services are named after their endpoints.** `SurveyCollectionService` covers
  the collection (`list`, `find`, `create`, `forSurvey()`); `SurveyService` covers
  one survey. A `forX($id)` selector returns a service scoped to that item, so
  its methods take no ID: `forInterviewer($id)->get()`, not `get($id)`.
- **Resources only navigate.** `SurveyResource`, `SamplingPointResource` and
  `SurveyQuotaResource` lead to the services below them; they never call the API
  themselves.

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
