# togg-jira-sync
Sync toggl time entries with jira time log

Setup
```
git clone
composer install
```
Make file .env.local:
```
TOGGL_API_KEY=
TOGGL_PROJECT_ID=
TOGGL_WORKSPACE_ID=

JIRA_USER=
JIRA_PASS=

```

Usage:
```
./sync.php 2018-11-01 2018-12-01
```
