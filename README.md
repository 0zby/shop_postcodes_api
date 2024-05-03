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

If posting to the endpoints, ensure to set a header with Accept: application/json.

The postcode data is acquired from https://parlvid.mysociety.org/os/ONSPD/2022-11.zip

After downloading it and unzipping you can run the command `./vendor/bin/sail artisan app:etlpostcodes {filePath}` to import the data.

The main file in the download is `ONSPD_NOV_2022_UK.csv`, though if you want to test it out with a smaller dataset you could choose one of the suffixed files such as `ONSPD_NOV_2022_UK_DL.csv`.

## Running tests

### Automated tests

Tests are run simply with `./vendor/bin/sail artisan test`.

## Notes

### General notes about additions

Given more time I would like to:

* document the endpoints and their usage, perhaps with swagger
* revisit the import process to add chunking to speed up the process and add some checks on the data coming in
* add some code to download and extract the postcode data before the import process
* add custom request objects to clean up validation steps from controller
* add further unit tests
* improve the messaging for the enum validation to include the valid options - default is not helpful "The selected store type is invalid."
* add some authentication using some middleware so that the API is restricted
* add some response objects to solidify how the returns look exactly e.g. precision on long/lat
* double check that the presion on the postcode table matches the import data, however there's an argument that greater precision may be futureproofing
* make some factories and seeders so that we have some test data close to hand
* extract code from the command for ease of testing, and write some tests for it

### Notes about coordinates and postcodes

There's a lot of complexity dealing with coordinates that might not be immediately obvious. Such as the coordinates provided are approximations which may lead to edge cases where the centre of the point is inside the delivery radius but the actual address is outside the radius.

Also I have assumed that we will calculate this as the crow flies. Actual delivery distace to something estimated to be 20 meters away, might be 500 meters away when accounting for one way systems, traffic flow etc. If doing this we could investigate forming a route and taking the length of the route at least but it's slower than treating it like geometry and calculating distance between points.

I have only imported the postcode and the coordinates, however if the application is going to be build up, we may want further data.

I did read in rare instances two streets can have the same postcode, so some thought needed around this if they got imported.
