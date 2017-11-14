Drupal reproduce module list
=====

[![CircleCI](https://circleci.com/gh/dcycle/docker-drupal-reproduce-modulelist.svg?style=svg)](https://circleci.com/gh/dcycle/docker-drupal-reproduce-modulelist)

Reproduce the list of modules and their projects for an existing Drupal site.

Usage
-----

    mkdir ./module-list
    echo "select filename,info from system where status=1;"|drush sqlc > "$(pwd)"/module-list/my-modules.txt
    docker run -v "$(pwd)":/app \
      -e DIRECTORY=/var php:7 /bin/bash -c 'php /app/run.php'

This will output something like:

    drush dl views-7.x-3.14
    drush dl devel-7.x-1.5
    drush en -y views_ui
    drush en -y views
    drush en -y devel
