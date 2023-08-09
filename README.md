
# Laravel Creator by Programmeruz





Laravel Creator is a package designed to simplify the code generation process in Laravel projects. With an intuitive UI at the /creator endpoint, you can easily generate migrations, seeders, models, controllers, and API resources with built-in Swagger documentation support.
## Installation
### Install via Composer:
Begin by pulling in the package using Composer:

```
composer require programmeruz/laravel-creator
```

### Registering the Service Provider (For Laravel versions below 5.5):

After installation, if you are running a version of Laravel less than 5.5, you'll need to register the service provider. Open config/app.php and add the service provider to the providers array:

```
'providers' => [
    // ...
    Programmeruz\LaravelCreator\LaravelCreatorServiceProvider::class,
],

```
Note: Laravel 5.5 and above uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.


### Accessing the Creator UI:

Once the package is properly installed and the service provider is registered, navigate to:
```
your-laravel-app-url/creator
```
Here, you'll find the intuitive UI to guide you through the code generation process.

## Using Swagger Documentation
To utilize the Swagger feature, after setting up your routes and controllers with Laravel Creator, run the following command:
```
php artisan l5-swagger:generate
```
This will produce the necessary Swagger configuration and UI, which you can typically access at:
```
your-laravel-app-url/api/documentation
```

### Final Thoughts
Laravel Creator is crafted to enhance your development workflow, ensuring you spend less time on boilerplate code and more on building your application's unique features. Should you encounter any issues or require support, please raise an issue on our GitHub repository.

Happy coding!

This documentation provides a structured introduction to your package, guiding users through the installation and basic usage processes. You can further expand it by including sections on advanced features, contribution guidelines, or any other information you find relevant.






## 🔗 Links
[![portfolio](https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white)](https://instagram.com/bobobek_com)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/bobobek-t-870a9112a/)
[![twitter](https://img.shields.io/badge/twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white)](https://twitter.com/BobobekTurdiyev)

