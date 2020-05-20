# Laraquiz

Laraquiz is the Normal Quiz application build with the Laravel Framework & Web socket. In that, one can managing quiz and quiz questions from Backend. In front, Player will play a quiz and winner will be diclared. This will be useful for the organization who are arranging MeetUp or Workshops.

## Getting Started

* Clone this repo to your local machine using https://github.com/vcian/laraquiz.git

### Prerequisites

You need to install the following:
* [Install npm](https://www.npmjs.com/get-npm).

### Installing

Follow the below steps to install Laraquiz into your system:

```
composer install
```
```
npm install
```
```
// Run all Mix tasks...
npm run dev
```
Copy ```.env.example``` sample file to ```.env``` file and configure accordingly.

Run ```php artisan key:generate``` 
command to set the application key into your .env file. **If the application key is not set, your user sessions and other encrypted data will not be secure!**

Start websocket with one simple command:

```
php artisan websockets:serve
``` 
Follow the [Documentation](https://docs.beyondco.de/laravel-websockets/1.0/getting-started/introduction.html) for more details.

## Built With

* [Laravel 5.7](https://laravel.com/docs/5.7) - The web framework used
* [VueJs](https://vuejs.org/) - Frontend Js Framework
* [WebSocket](https://docs.beyondco.de/laravel-websockets/) - Used for real-time data

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
