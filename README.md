# git-backup

USE AT OWN RISK!

simple project to commit to git on a regular basis

## running the command

    php src/run.php git:send

## configuring the command

Create a src/config/parameters.yml (or copy the included .dist file) and add include directories to scan for repos.

You may want to exclude subdirs you want to be ignored if you have git repos in those include directories you don't want to autocommit.

## todo's

* pull (with merge, not rebase) before commit/push
* resolve conflicts when merging