<?php

namespace App\Twig\Component;

use App\Service\IssueService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class Backlog
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public array $issues;


    #[LiveAction]
    public function setSelectedIssue(#[LiveArg] string $id): void
    {
        $this->emit('setSelectedIssue', [
            'issueId' => $id
        ]);
    }
}