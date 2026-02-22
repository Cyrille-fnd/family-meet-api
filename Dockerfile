FROM dunglas/frankenphp:1-builder-php8.4-alpine AS builder

COPY --from=caddy:builder /usr/bin/xcaddy /usr/bin/xcaddy

RUN CGO_ENABLED=1 \
    XCADDY_SETCAP=1 \
XCADDY_GO_BUILD_FLAGS=$'-ldflags "-w -s -extldflags \'-Wl,-z,stack-size=0x80000\'"' \
    CGO_CFLAGS=$(php-config --includes) \
    CGO_LDFLAGS="$(php-config --ldflags) $(php-config --libs)" \
xcaddy build v2.10.2 \
        --output /usr/local/bin/frankenphp \
        --with github.com/dunglas/frankenphp=./ \
        --with github.com/dunglas/frankenphp/caddy=./caddy/ \
        --with github.com/dunglas/caddy-cbrotli \
        --with github.com/dunglas/mercure/caddy \
        --with github.com/dunglas/vulcain/caddy



FROM dunglas/frankenphp:1-builder-php8.4-alpine AS runner_dev

COPY --from=builder /usr/local/bin/frankenphp /usr/local/bin/frankenphp

RUN chmod +x /usr/local/bin/frankenphp; \
    install-php-extensions mysqli mysqlnd pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV SERVER_NAME="localhost"

COPY . /app
WORKDIR /app

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]



FROM dunglas/frankenphp:1-builder-php8.4-alpine AS runner_prod

COPY --from=builder /usr/local/bin/frankenphp /usr/local/bin/frankenphp

RUN chmod +x /usr/local/bin/frankenphp; \
    install-php-extensions mysqli mysqlnd pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod
ENV SERVER_NAME="api-family-meet.org"

COPY . /app
WORKDIR /app
RUN composer install --no-dev --optimize-autoloader
