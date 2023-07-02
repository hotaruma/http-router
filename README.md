# HTTP Router

[![Build and Test](https://github.com/hotaruma/http-router/actions/workflows/cicd.yml/badge.svg)](https://github.com/hotaruma/http-router/actions/workflows/cicd.yml)
[![Latest Version](https://img.shields.io/github/release/hotaruma/http-router.svg)](https://github.com/hotaruma/http-router/releases)
[![License](https://img.shields.io/github/license/hotaruma/http-router.svg)](https://github.com/hotaruma/http-router/blob/master/LICENSE)
[![Packagist Downloads](https://img.shields.io/packagist/dt/hotaruma/http-router.svg)](https://packagist.org/packages/hotaruma/http-router)

Simple HTTP router.

## Installation

You can install the library using Composer. Run the following command:

```bash
composer require hotaruma/http-router
```

## RouteMap

### Creating Routes

To create routes, you need to use the RouteMap class. Here's an example of creating a route:

```php
use Hotaruma\HttpRouter\RouteMap;

$routeMap = new RouteMap();

$routeMap->get('/hello', function () {
    echo 'Hello, world!';
});

$routeMap->post('/users', UserController::class);
```

### Route Parameters

You can also define route parameters using curly braces `{}` in the route path:

```php
$routeMap->get('/shop/{category}', CategoryController::class);
```

### Route Config

Routes can be configured by defining its defaults, rules, and more.

- **Defaults**: This sets the placeholder's value when generating a route by name, as long as there is no attribute with the same name in the route.
  In that case, the attribute's value takes precedence.
- **Rules**: These play a role in pattern validation within {} placeholders. They determine if a parameter matches a specific type based on regular
  expressions. Rules are also used to validate parameters during route generation.
- **Middlewares**: Middleware functions can be grouped and returned in their original form.
- **Name**: This is used to generate a route later on.
- **Methods**: Methods define whether a route matches the current request.

```php
$routeMap->add('/news/{id}', NewsController::class)->config(
    defaults: ['id' => '1'],
    rules: ['id' => '\d+'],
    middlewares: [LogMiddleware::class],
    name: 'newsPage',
    methods: [AdditionalMethod::ANY],
);
```

It is preferable to use named attributes for configuration.
By using named attributes, you can explicitly specify the purpose of each configuration option, improving the readability of your code.

### Grouping Routes

You can group routes with a common prefix and apply shared middleware or other configurations:

```php
$routeMap->group(
    rules: ['slug' => '\w+', 'id' => '\d+'],
    namePrefix: 'question',
    methods: [HttpMethod::GET],
    group: function (RouteMapInterface $routeMap) {
    
        $routeMap->add('/questions/{slug}', [QuestionController::class, 'view']);
        $routeMap->add('/users/{id}', [UserController::class, 'view']);
        
        $routeMap->group(
            namePrefix: 'admin',
            pathPrefix: 'admin',
            middlewares: [LogMiddleware::class, AccessMiddleware::class],
            methods: [HttpMethod::DELETE, HttpMethod::POST],
            group: function (RouteMapInterface $routeMap) {

                $routeMap->add('/questions/{id}', AdminQuestionController::class);
                $routeMap->add('/users/{id}', AdminUserController::class);
            }
        );
    }
);
```

When grouping routes and nesting one group within another, you have the ability to merge configurations. This means that each route inside a group
merges its configuration with the group's configuration, and each nested group merges its configuration with its parent group.

By organizing routes into groups, you can apply specific configurations to multiple routes at once. The configurations cascade down the nested groups,
allowing you to inherit and override settings as needed. This provides a powerful and flexible way to manage and organize your routes.

```php
$routeMap->group(
    pathPrefix: 'admin',
    methods: [HttpMethod::GET],
    middlewares: [ManagerAccessMiddleware::class],
    group: function (RouteMapInterface $routeMap) {

        $routeMap->add('/dashboard', [AdminController::class, 'dashboard']);

        $routeMap->changeGroupConfig(
            middlewares: [AdminAccessMiddleware::class],
            methods: [HttpMethod::DELETE, HttpMethod::POST],
        );
        
        $routeMap->add('/users/{id}', [AdminController::class, 'users']);
        $routeMap->add('/settings', [AdminController::class, 'settings']);
    }
);
```

## Route Dispatcher

Once the routes are defined, you can use the RouteDispatcher class to match the incoming request to the appropriate route and extract the associated
attributes.
You can configure the RouteDispatcher and match the routes:

```php
use Hotaruma\HttpRouter\{RouteMap,RouteDispatcher};

$routeDispatcher = new RouteDispatcher();
$routeMap = new RouteMap();

$routeMap->get('/home', HomeController::class);
$routeMap->post('/contacts', ContactsController::class);

$routeDispatcher->config(
    requestHttpMethod: HttpMethod::tryFrom($serverRequest->getMethod()),
    requestPath: $serverRequest->getUri()->getPath(),
    routes: $routeMap->getRoutes(),
);

try {
    $route = $routeDispatcher->match();
} catch (RouteDispatcherNotFoundException $exception) {
    // exception handling
}

$attributes = $route->getAttributes();
$action = $route->getAction();
```

You can customize the route dispatching process by providing your own implementation of the `RouteMatcherInterface` interface.
```php
$routeDispatcher->routeMatcher(new RouteMatcher());
```

## URL Generator

To use the RouteUrlGenerator, you need to create a RouteMap instance with defined routes.

```php
use Hotaruma\HttpRouter\{RouteMap,RouteUrlGenerator};

$routeUrlGenerator = new RouteUrlGenerator();
$routeMap = new RouteMap();

$routeMap->get('/profile/{category}/', stdClass::class)->config(
    rules: ['category' => '\w+'],
    defaults: ['category' => 'projects'],
    name: 'profile',
);

$routeUrlGenerator->config(
    routes: $routeMap->getRoutes(),
);
$route = $routeUrlGenerator->generateByName('profile');
$url = $route->getUrl(); //profile/projects/
```

You can customize the route url generation by providing your own implementation of the `RouteUrlBuilderInterface` interface.
```php
$routeUrlGenerator->routeUrlBuilder(new RouteUrlBuilder());
```
## Contributing

Contributions are welcome! If you find a bug or have an idea for a new feature, please open an issue or submit a pull
request.
