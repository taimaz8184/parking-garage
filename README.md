Scenario:

We are building an application to manage a parking garage customers. The app is accessed from a web browser and runs as a single page application. Each customer is able to request entering the parking garage, and receive a ticket. The customer is then charged for parking based on the length of their stay. The customer is not allowed to leave the parking before a successful payment.
This API has the following capabilities:

1.	Each customer is able to request entry to the parking garage at the time of arrival. If there is space available, the app issues a ticket to the customer.
2.	The customer is able to pay their ticket based on the length of their stay (options: 1hr ($3), 3hr ($4.5), 6hr ($6.75), or ALL DAY ($10.12)
3.	When the customer is on their way out of the garage, the app checks that if the ticket is paid. If not, denies their exit.
4.	If the parking garage is full, the app denies entry.
5.	App allows each customer to register in case the lot is full and maintain a queue of customers, sends a message (email or otherwise) to notify if there is a free spot when one becomes available.

To start the project:

Laravel:
1.	Create a database in phpmyadmin with name "cyp-database"
2.	In the laravel folder and in command prompt type: "composer install" to install the packages
3.	type: "php artisan migrate" to created the database
4.	type: "php artisan db:seed --class=CreateParkPlaces" to create default parking lot spots
5.	type: "php artisan serve" to run backend in localhost:8000

Vue:
1.	In the vue folder and in command prompt type: "npm i" to install the packages
2.	type: "npm run dev" to run frontend in localhost:3000

