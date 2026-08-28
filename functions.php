<?php

declare(strict_types=1);
function printEvents(array $events)
{
    foreach ($events as $event)
    {
        printEvent($event);
    }
}
function printEvent(array $event): void
{
    $eventType = $event['type'] ?? null;
    if(!$eventType) {
        throw new \Exception("Event type is invalid");
    }

    // Run related function by type...
    $functionName = "print{$eventType}";
    if(!function_exists($functionName)) {
        return;
    }
    echo "--------{$event['created_at']}----------\n";
    $result = call_user_func_array(mb_strtolower($functionName, "utf8"), [$event]);
    if($result === false) {
        throw new \Exception("Function run failed! : {$functionName}");
    }
    echo "\n\n";
}

function printcommitcommentevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $comment = $event['payload']['comment'];

    echo "{$action} a commit comment in {$repo['name']} repository.\nCommit id: {$comment['commit_id']}\nComment: {$comment['body']}";
}

function printcreateevent(array $event)
{
    $repo = $event['repo'];
    $refType = $event['payload']['ref_type'];
    $ref = $event['payload']['ref'];

    echo "created {$ref} {$refType} in {$repo['name']} repository";
}

function printdeleteevent(array $event)
{
    $repo = $event['repo'];
    $refType = $event['payload']['ref_type'];
    $ref = $event['payload']['ref'];

    echo "deleted a {$refType} in {$repo['name']} repository. Name: {$ref}";
}

function printdiscussionevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $discussion = $event['payload']['discussion'];

    echo "{$action} a discussion in {$repo['name']} repository.\nDiscussion id: {$discussion['id']}\nTitle: {$discussion['title']}\nBody:{$discussion['body']}}";
}

function printforkevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $forkee = $event['payload']['forkee'];

    echo "{$action} {$repo['name']} repository.\nForkee id: {$forkee['id']}\nName: {$forkee['name']}\nDescription:{$forkee['description']}}";
}

function printissuecommentevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $comment = $event['payload']['comment'];
    $issue = $event['payload']['issue'];

    echo "{$action} an issue comment in {$repo['name']} repository.\nIssue id: {$issue['id']}\nTitle: {$issue['title']}\nComment:{$comment['body']}}";
}

function printissuesevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $issue = $event['payload']['issue'];

    $text = "{$action} an issue in {$repo['name']} repository.\nIssue id: {$issue['id']}\nTitle: {$issue['title']}}";

    if(isset($event['payload']['assignee'])) {
        $assigned = $event['payload']['assignee'];
        $text .= "\nAssigned user: {$assigned['login']}\n";
    }

    if(isset($event['payload']['label'])) {
        $label = $event['payload']['label'];
        $text .= "\nLabel: {$label['name']}";
    }

    $assignees = $event['payload']['assignees'];
    $countAssignees = count($assignees);
    $text .= "\nAssigned user count: {$countAssignees}";

    $labels = $event['payload']['labels'];
    $countLabels = count($labels);
    $text .= "\nLabel count: {$countLabels}";

    echo $text;
}

function printmemberevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $member = $event['payload']['member'];

    echo "{$action} a user in {$repo['name']} repository.\nMember: {$member['login']}";
}

function printpullrequestevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $pullRequest = $event['payload']['pull_request'];

    echo "{$action} a pull request in {$repo['name']}->{$pullRequest['head']['ref']}";
}

function printpullrequestreviewevent(array $event): void
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $pullRequest = $event['payload']['pull_request'];
    $review = $event['payload']['review'];

    echo "{$action} a pull request review in {$repo['name']}->{$pullRequest['head']['ref']}";
}

function printpullrequestreviewcommentevent(array $event)
{
    $repo = $event['repo'];
    $action = $event['payload']['action'];
    $pullRequest = $event['payload']['pull_request'];
    $comment = $event['payload']['comment'];

    echo "{$action} a pull request comment in {$repo['name']}->{$pullRequest['head']['ref']}.\nComment: {$comment['body']}";
}

function printpushevent(array $event)
{
    $repo = $event['repo'];
    echo "pushed in {$repo['name']}->{$event['payload']['ref']}";
}







