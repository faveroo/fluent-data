<?php

use Gabriel\FluentData\Container\Container;

require 'vendor/autoload.php';

echo PHP_EOL;
echo "==============================" . PHP_EOL;
echo "CONTAINER TESTS" . PHP_EOL;
echo "==============================" . PHP_EOL;

$app = new Container();

class Logger 
{
    public function log(string $message): void
    {
        echo "[LOG]: {$message}" . PHP_EOL;
    }

}

echo PHP_EOL;
$logger = $app->make(Logger::class);
$logger->log("Container resolved Logger");

// =====================================================
// DEPENDENCY INJECTION
// =====================================================

class UserService
{
    public function __construct(protected Logger $logger) {}

    public function createUser(): void
    {
        $this->logger->log(
            'User created Successfully'
        );
    }
}

echo PHP_EOL;
echo "2. DEP INJEC" . PHP_EOL;

$userService = $app->make(UserService::class);

$userService->createUser();

// =====================================================
// RECURSIVE DEPENDENCY RESOLUTION
// =====================================================


class Database
{
    public function connect(): void
    {
        echo "Database connected" . PHP_EOL;
    }
}

class Repository
{
    public function __construct(
        protected Database $database
    ) {
    }

    public function all(): void
    {
        $this->database->connect();

        echo "Fetching data..." . PHP_EOL;
    }
}

class ProductService
{
    public function __construct(
        protected Repository $repository,
        protected Logger $logger
    ) {
    }

    public function listProducts(): void
    {
        $this->logger->log(
            'Listing products'
        );

        $this->repository->all();
    }
}

echo PHP_EOL;
echo "3. RECURSIVE RESOLUTION" . PHP_EOL;

$productService = $app->make(
    ProductService::class
);

$productService->listProducts();


// =====================================================
// INTERFACE BINDING
// =====================================================

interface PaymentGateway
{
    public function pay(float $amount): void;
}

class StripeGateway implements PaymentGateway
{
    public function pay(float $amount): void
    {
        echo "Paid {$amount} with Stripe" . PHP_EOL;
    }
}

class CheckoutService
{
    public function __construct(
        protected PaymentGateway $gateway
    ) {
    }

    public function checkout(): void
    {
        $this->gateway->pay(199.99);
    }
}

echo PHP_EOL;
echo "4. INTERFACE BINDING" . PHP_EOL;

$app->bind(
    PaymentGateway::class,
    StripeGateway::class
);

$checkout = $app->make(
    CheckoutService::class
);

$checkout->checkout();

// =====================================================
// BIND (NEW INSTANCE)
// =====================================================

echo PHP_EOL;
echo "6. BIND" . PHP_EOL;

$app->bind(
    Counter::class,
    Counter::class
);

$x = $app->make(Counter::class);
$y = $app->make(Counter::class);

$x->count++;

echo "X count: {$x->count}" . PHP_EOL;
echo "Y count: {$y->count}" . PHP_EOL;

echo "Same instance? ";

var_dump($x === $y);

// =====================================================
// SINGLETON
// =====================================================

class Counter
{
    public int $count = 0;
}

echo PHP_EOL;
echo "5. SINGLETON" . PHP_EOL;

$app->singleton(
    Counter::class,
    Counter::class
);

$a = $app->make(Counter::class);
$b = $app->make(Counter::class);

$a->count++;

echo "A count: {$a->count}" . PHP_EOL;
echo "B count: {$b->count}" . PHP_EOL;

echo "Same instance? ";

var_dump($a === $b);



// =====================================================
// CLOSURE BINDING
// =====================================================

class Config
{
    public function __construct(
        public array $items
    ) {
    }
}

echo PHP_EOL;
echo "7. CLOSURE BINDING" . PHP_EOL;

$app->bind(Config::class, function () {
    return new Config([
        'app_name' => 'FluentData',
        'version' => '1.0'
    ]);
});

$config = $app->make(Config::class);

print_r($config->items);


// =====================================================
// DEFAULT VALUES
// =====================================================

class MailService
{
    public function __construct(
        protected string $driver = 'smtp'
    ) {
    }

    public function driver(): void
    {
        echo "Driver: {$this->driver}" . PHP_EOL;
    }
}

echo PHP_EOL;
echo "8. DEFAULT VALUES" . PHP_EOL;

$mail = $app->make(MailService::class);

$mail->driver();


echo PHP_EOL;
echo "==============================" . PHP_EOL;
echo "ALL TESTS FINISHED" . PHP_EOL;
echo "==============================" . PHP_EOL;