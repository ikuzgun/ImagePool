## About ImagePool

Imagepool, in its simplest definition, is an upload tool that allows your images to be saved to the system with the help of drag and drop or click

 Abilities:

- Multiple Image upload
- Image Delete
- Image Sort (new sorted records is saved to database)
- an API endpoint to output all images in json format




## Installation

- Clone Project to your local repository
- run command composer install (for the create all packages)
- create a new database with the name you want (for me this name is 'imagepool' u can see in .env file)
- run command 'php artisan migrate' to create the required table
- and start the project via link 'http://localhost/repositoryname/public'

## API

- The project has a single api link that can access all image records
- endpoint /api/v1/images/{token} that provides access to image images (for example http://localhost/repositoryname/public/api/v1/images/{token})
- In this project, we pass the token that provides access to the images as a parameter to the url.
- sample token for access is available in .env file






## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
