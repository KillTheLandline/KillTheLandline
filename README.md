# Kill The Landline Front End

This is the front-end to [Kill The Landline](http://killthelandline.com), an honorable mention project at Startup Weekend Columbia 2012. 

## About
This PHP-based software is designed to run on Heroku using a ClearDB MySQL database. The full process for account creation is outlined below, but the limiting factor is transferring the phone number to Kill The Landline, which takes about a week and requires a copy of the customer's bill. Hence, we were truly running a "lean startup" because this system was only completed up to the wait process - it was intended that we would build in billing and a more complete front-end / back-end integration during the week it took for first customers to transefer their phone numbers. Therefore, key parts of a full software package are missing, including Stripe integration and full Tropo API integration. 

## How it works 
* A customer enters their email address
* The email address is sent a confirmation email with a unique link to a signup page
* The customer creates an account with login info, then specifies the details of the number they wish to transfer and where they wish for it to forward.
* The customer then must submit, via email or post, proof that they own the telephone line (i.e. a telephone bill).
* Upon receiving the bill, we forward it to Tropo to begin the transfer process to our number. 
* The customer completes the billing form (from Stripe)
* There is a 1-2 week wait while the number transfters
* The service goes live, and we alert the customer that the service is live
* Incoming calls are forwarded to the specified numbers in the database
* Billing is automated monthly through Stripe