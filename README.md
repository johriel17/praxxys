
#Install the zip file

#now from the terminal go to the root directory of the project and run these commands

#you should have PHP, composer and NodeJs installed

note: if you're using xampp as your php for the composer, edit php.ini and enable the extension=zip

composer install

npm install --save-dev create-vite

#open the project and create a . env file then copy the .env.example

#run this command

php artisan key:generate

#then you should have mysql server running I used xampp

#create a database with the name 'laravel'

#and run these commands on the terminal

php artisan migrate

php artisan storage:link


#you can now run the application using the url will show there just copy it and paste to the browser

php artisan serve

#then open another terminal and run

npm run dev

#now you can use the application just register and login


#run this extra command to populate the products table 

php artisan db:seed --class=ProductSeeder
