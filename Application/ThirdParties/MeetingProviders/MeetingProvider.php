<?php

namespace Application\ThirdParties\MeetingProviders;

use Application\Dtos\Meeting as MeetingDto;

interface MeetingProvider
{
    public function setUp();

    public function setUpJoin(MeetingDto $meeting);

    public function getMeetingId(): string;

    public function getAdvisorMeetingUrl(): string;

    public function getAttendeMeetingUrl(): string;
}