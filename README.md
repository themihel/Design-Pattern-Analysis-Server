# Design Pattern Analysis - Server
Corresponding app: https://github.com/themihel/Design-Pattern-Analysis-App

# Usage

## Installing dependencies
`composer install && npm install`

## Starting development server
`docker-compose up`

## Running build process
The build process minifies the javascript and CSS files and moves them to the corresponding directory in the public folder to be accessible through the internet.

`gulp build`

# Actions
`GET /` - Method to check if the server is up and running

`POST /trackaction` - Method to receive data from the app (configured already)

`GET /statistics/generate` - Method to generate session data

# Relevant folders
The following part explains the content and usage of relevant folders to adapt.

## ressources/modifications
Each folder is meant for a specific group.

For example you can configure your app that the trial group uses `modification/1` and the control group `modification/2`, other groups can be added on your choice.
Each folder should at least include the styles.css and script.js file as the app references them by name.

The first part of the javascript indicates the version which will be send to the app. For the proof of concept study a following concept was used:
```
Version 1 = Group 1 Week 1
Version 2 = Group 1 Week 2
Version 3 = Group 2 Week 1
// ...
```
But you can adapt this to your needs and only need to keep track of the association.

## Migrations
This folder includes a SQL file for creating a initial database for tracking action which are received in the trackaction.

## Config
The included json file needs to be adapted to your database configuration.

# Session
A session is defined by the time period between opening and closing the app, which were separate tracked event themselves. All events within this period belong to the corresponding user session. Within the session, the number of visits to certain pages and the cumulative duration of the stay on each page is stored. Although this results in a loss of information content, for example how long individual stays of certain pages were, this form of data is still sufficient to draw conclusions.

# Any question?
Feel free to contact me.