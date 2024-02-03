#!/bin/bash

echo "php amqp - starting setup"

if php -m | grep -q "amqp"; then
    echo "L'extension PHP amqp est déja installée."
    exit
else
    echo "L'extension PHP amqp n'est pas installée."
fi

# cmake
if [ ! -d /cmake ]; then
    echo "installing cmake"
    
    if [ ! -f /cmake-3.28.0-linux-x86_64.sh ]; then
        echo "downloading cmake"

        wget https://cmake.org/files/LatestRelease/cmake-3.28.0-linux-x86_64.sh
    else
        echo "cmake already downloaded"
    fi

    /bin/sh cmake-3.28.0-linux-x86_64.sh < <(printf '%s\n' y y)
    mv cmake-3.28.0-linux-x86_64 cmake
    cmake/bin/cmake --version
    rm -f cmake-3.28.0-linux-x86_64.sh
else
    echo "cmake already installed"
fi

# rabbitmq-c
if [ ! -d /alanxz-rabbitmq-c* ]; then
    echo "downloading rabbitmq-c"

    wget https://cmake.org/files/LatestRelease/cmake-3.28.0-linux-x86_64.sh -O rabbitmq.tar.gz
    tar xvfz rabbitmq.tar.gz
else
    echo "rabbitmq-c already downloaded"
fi

cd /alanxz-rabbitmq-c*

if [ ! -d /build ]; then
    echo "installing rabbitmq-c"

    mkdir build
fi

cd build

args=("-DCMAKE_INSTALL_PREFIX=/usr/local" "..")
../../cmake/bin/cmake "${args[@]}"
../../cmake/bin/cmake --build . --target install
pecl upgrade amqp