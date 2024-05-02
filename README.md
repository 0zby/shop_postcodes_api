# Technical Task

## Brief

The task is left fairly vague as we like to see how each person approaches the problem unguided, though feel free to ask questions.

The task is as follows, create a stand-alone PHP app that has:

* Console command to download and import UK postcodes (e.g. http://parlvid.mysociety.org/os/ ) into some kind of database
* A controller action to add a new store/shop to the database with:
  * Name
  * Geo coordinates
  * open/closed status
  * store type (takeaway, shop, restaurant)
  * a max delivery distance.
* Controller action to return stores near to a postcode (latitude, longitude) as a JSON API response, that would be suitable for use by a mobile app
* Controller action to return stores can deliver to a certain postcode as a JSON API response, that would be suitable for use by a mobile app

We're using Laravel framework but feel free to use whatever modern tools you're comfortable with.

The output of task will be reviewed on different things, such as:
* Simplicity
* Maintainability
* Correctness
* Understanding and use of suitable technology
* Performance
* Documentation
* Unit tests
* Security

The task should be submitted as source files (via BitBucket, GitHub).

Please don't spend too long on the task. We're not expecting it to be perfect, so let us know what improvements you might make if you had more time.

## Setup

### Prerequisties

* Docker
* PHP
* Composer

Clone the repository using git.

Change directory into the newly created repository folder.

Install using composer `composer install`.

Copy the example environment file `cp .env.example .env`.

Launch the containers `./vendor/bin/sail up -d`.

Run the migrations `./vendor/bin/sail artisan migrate`.


## Running tests

### Automated tests

Tests are run simply with `./vendor/bin/sail artisan test`.

## Notes

### General notes about additions

* I would like to document the endpoints and their usage.
* I would like to revisit the import process to add chunking to speed up the process.
* I would like to add custom request objects to clean up validation steps from controller
* I would like to add unit tests
* I would like to set up an enum for the store type for ease of use, validation in requests and so on
* I would like to add some authentication using some middleware so that the API is restricted
* I would like to add some query scope for open shops since presumably only open shops will deliver!
* I would like to add some response objects to solidify how the returns look exactly e.g. precision on long/lat
* I would like to double check that the presion on the postcode table matches the import data, however there's an argument that greater precision may be futureproofing
* I would like to make some factories and seeders so that we have some test data close to hand.


### Notes about coordinates and postcodes

There's a lot of complexity dealing with coordinates that might not be immediately obvious. Such as the coordinates provided are approximations which may lead to edge cases where the centre of the point is inside the delivery radius but the actual address is outside the radius.

Also I have assumed that we will calculate this as the crow flies. Actual delivery distace to something estimated to be 20 meters away, might be 500 meters away when accounting for one way systems, traffic flow etc. If doing this we could investigate forming a route and taking the length of the route at least but it's slower than treating it like geometry and calculating distance between points.

I have only imported the postcode and the coordinates, however if the application is going to be build up, we may want further data.

I did read in rare instances two streets can have the same postcode, so some thought needed around this if they got imported.
