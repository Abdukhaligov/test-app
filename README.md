###  Installation

```bash
git clone https://github.com/Abdukhaligov/test-app
cd test-app
```

```bash
composer install
```

```bash
php artisan migrate --seed
```

```bash
docker compose up
```

###  Features

* UUID-based identification for Customers and Orders


* Product caching with automatic cache invalidation on updates


* Event-driven architecture using Laravel Events and Listeners


* Database transactions ensure data integrity on order creation


### API Endpoints
| Method | Endpoint         | Description            |
| ------ | ---------------- | ---------------------- |
| GET    | `/orders/{uuid}` | Retrieve order by UUID |
| POST   | `/orders`        | Create a new order     |
