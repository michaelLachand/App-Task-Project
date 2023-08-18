<?php

namespace App\Service;

use Symfony\Component\Routing\RouterInterface;

class SearchService
{
    public function __construct(
        private readonly IssueService $issueService,
        private readonly UserService $userService,
        private readonly RouterInterface $router
    ) {
    }

    public function search(string $query): array
    {
        $issueOptions = [];
        $userOptions = [];

        $issues = $this->issueService->findByQuery($query);
        $users = $this->userService->findByQuery($query);

        foreach ($issues as $issue) {
            $issueOptions[] = [
                'label' => "[{$issue['id']}] {$issue['summary']}",
                'value' => $this->router->generate('issue_show', [
                    'id' => $issue['id']
                ]),
            ];
        }

        foreach ($users as $user) {
            $userOptions[] = [
                'label' => "{$user['firstName']} {$user['lastName']}",
                'value' => $user['id'],
            ];
        }

        return [
            [
                'label' => 'Issues',
                'options' => $issueOptions,
            ],
            [
                'label' => 'Users',
                'options' => $userOptions,
            ]
        ];
    }
}