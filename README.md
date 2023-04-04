# PHP binary template

A template project to bootstrap your PHP async scripts.

## Usage

### Installation

Install the dependencies via [Composer](https://getcomposer.org):

```shell
composer install --no-dev -o
```

Override the environment variables by creating a `.env.local` file.

```
LOG_LEVEL=DEBUG

DATABASE_MYSQL_PASSWORD=...
```

### UV extension

Installing the extension from PECL is not up to date with PHP 8.1. You should
install it [from source](https://github.com/amphp/ext-uv).

### Usage

Launch the script from the command line:
```shell
./bin/script
```

Or link the systemd unit files (with super admin rights):
```shell
systemctl link systemd/script.{service,timer}

# Activate the script's service
systemctl daemon-reload
systemctl enable script.{service,timer}
systemctl start script.{service,timer}

# Check if it is running
systemctl list-timers
systemctl status script.service
```
