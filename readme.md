# What is this?
This repository is WebDriver testing example files.

# Target
- PHP Conference Hokkaido 2019
- PHP Conference Okinawa 2019

# Setup

```
composer setup
```

# Run

```
docker-compose up --build
```

# Get started testing

| Service | Command |
| ------- | ------- |
| Original | `docker exec -it php ./composer test:original` |
| CakePHP3 | `docker exec -it php ./composer test:cakephp3` |
| Laravel6 | `docker exec -it php ./composer test:laravel6` |

# Paths

| Service | URL |
| ------- | ------- |
| phpinfo | http://localhost:12000/ |
| Original | http://localhost:12001/ |
| CakePHP3 | http://localhost:12100/ |
| Laravel6 | http://localhost:12200/ |

# License
MIT
