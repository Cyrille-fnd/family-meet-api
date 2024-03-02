#!/bin/bash

# If you want to have more than one application, and in just one of them to run the supervisor, uncomment the lines below, 
# and add the env variable IS_WORKER as true in the EBS application you want the supervisor

#if [ "${IS_WORKER}" != "true" ]; then
#    echo "Not a worker. Set variable IS_WORKER=true to run supervisor on this instance"
#    exit 0
#fi

echo "Supervisor - starting setup"
. /opt/elasticbeanstalk/deployment/env

if [ ! -f /usr/bin/supervisord ]; then
    echo "installing supervisor"
    python3 -m ensurepip
    pip3 install supervisor
else
    echo "supervisor already installed"
fi

if [ ! -d /etc/supervisor ]; then
    mkdir /etc/supervisor
    echo "create supervisor directory"
fi

if [ ! -d /etc/supervisor/conf.d ]; then
    mkdir /etc/supervisor/conf.d
    echo "create supervisor configs directory"
fi

. /opt/elasticbeanstalk/deployment/env && cat .ebextensions/supervisor/supervisord.conf > /etc/supervisor/supervisord.conf
. /opt/elasticbeanstalk/deployment/env && cat .ebextensions/supervisor/supervisord.conf > /etc/supervisord.conf
. /opt/elasticbeanstalk/deployment/env && cat .ebextensions/supervisor/consumer.conf > /etc/supervisor/conf.d/consumer.conf

if ps aux | grep "[/]usr/local/bin/supervisord"; then
    echo "supervisor is running"
else
    echo "starting supervisor"
    /usr/local/bin/supervisord
fi

/usr/local/bin/supervisorctl reread
/usr/local/bin/supervisorctl update

echo "Supervisor Running!"
