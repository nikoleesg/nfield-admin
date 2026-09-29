<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupDirectoryAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupLocalAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupSurveysEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyGroupScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroupModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroups\SurveyGroupDirectoryAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroups\SurveyGroupLocalAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurveyGroup;

/**
 * One survey group, reached through
 * `NfieldManager::surveyGroups()->forSurveyGroup($id)`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyGroupEndpoint
 * (and its directoryAssignments, localAssignments and surveys sub-resources),
 * and {@see SurveyGroupCollectionService} with SurveyGroupCollectionEndpoint.
 */
class SurveyGroupService implements SurveyGroupScopedInterface
{
    use ScopedToSurveyGroup;

    public function __construct(
        protected SurveyGroupEndpointInterface $surveyGroupEndpoint,
        protected SurveyGroupDirectoryAssignmentsEndpointInterface $surveyGroupDirectoryAssignmentsEndpoint,
        protected SurveyGroupLocalAssignmentsEndpointInterface $surveyGroupLocalAssignmentsEndpoint,
        protected SurveyGroupSurveysEndpointInterface $surveyGroupSurveysEndpoint,
    ) {}

    public function get(): SurveyGroupModel
    {
        return SurveyGroupModel::from($this->surveyGroupEndpoint->get($this->getSurveyGroupId()));
    }

    /**
     * The directory (Entra ID) objects the group is assigned to, optionally
     * filtered with OData query options.
     *
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SurveyGroupDirectoryAssignmentModel>
     */
    public function directoryAssignments(array $filter = []): Collection
    {
        return SurveyGroupDirectoryAssignmentModel::collect(
            $this->surveyGroupDirectoryAssignmentsEndpoint->list($this->getSurveyGroupId(), $filter),
            Collection::class
        );
    }

    /**
     * The local (Nfield-native) identities the group is assigned to.
     *
     * @return Collection<int, SurveyGroupLocalAssignmentModel>
     */
    public function localAssignments(): Collection
    {
        return SurveyGroupLocalAssignmentModel::collect(
            $this->surveyGroupLocalAssignmentsEndpoint->list($this->getSurveyGroupId()),
            Collection::class
        );
    }

    /**
     * The surveys in the group, optionally filtered with OData query options.
     *
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SurveyModel>
     */
    public function surveys(array $filter = []): Collection
    {
        return SurveyModel::collect(
            $this->surveyGroupSurveysEndpoint->list($this->getSurveyGroupId(), $filter),
            Collection::class
        );
    }
}
