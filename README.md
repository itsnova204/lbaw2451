```markdown
# lbaw2451

## Project Overview

This project is developed as part of the LBAW course. It is a web application built using the Laravel framework, designed to manage and track tasks efficiently. The application includes features such as user authentication, task creation, task assignment, and progress tracking.

## Getting Started

### Prerequisites

To prepare your computer for development, you need to install:

* [PHP](https://www.php.net/) version 8.3 or higher
* [Composer](https://getcomposer.org/) version 2.2 or higher
* [Node.js](https://nodejs.org/) version 14 or higher

### Installation

1. Clone the repository:

```bash
git clone https://gitlab.up.pt/lbaw/lbaw2425/lbaw2451.git
cd lbaw2451
```

2. Install PHP dependencies:

```bash
composer install
```

4. Copy the example environment file and modify the environment variables as needed:

```bash
cp .env.example .env
```

5. Generate an application key:

```bash
php artisan key:generate
```

6. Run database migrations and seed the database:

```bash
php artisan migrate --seed
```

### Running the Application

Start the development server:

```bash
php artisan serve
```

Access the application at `http://localhost:8000`.

### Running Tests

To run the test suite, use the following command:

```bash
php artisan test
```

## Contributing

We welcome contributions to this project. To contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature-name`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add some feature'`).
5. Push to the branch (`git push origin feature/your-feature-name`).
6. Create a new Pull Request.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Authors

- [Alexandre Silva](https://github.com/asilva1604)
- [Eduardo Baltazar](https://github.com/blahte4)
- [Tiago Aleixo](https://github.com/itsnova204)
- [Tiago Lourenço](https://github.com/Tiagocl)

## Acknowledgments

- Thanks to the LBAW course instructors and TAs for their support and guidance.
```