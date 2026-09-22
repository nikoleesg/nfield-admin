<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel;

it('serializes to camelCase', function () {
    $model = new SurveyCreateModel(
        surveyName: 'Test Survey',
        clientName: 'Test Client',
        surveyType: 'Capi',
        description: 'Test description',
        interviewerInstruction: 'Instruction',
        surveyGroupId: 123,
        isBlueprint: true,
        enableRespondentsGateway: false
    );

    $array = $model->toArray();

    expect($array)->toHaveKeys([
        'surveyName',
        'clientName',
        'surveyType',
        'description',
        'interviewerInstruction',
        'surveyGroupId',
        'isBlueprint',
        'enableRespondentsGateway',
    ]);

    expect($array['surveyName'])->toBe('Test Survey')
        ->and($array['clientName'])->toBe('Test Client');
});
