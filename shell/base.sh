#!/bin/bash
path=`dirname $0`
cd $path
cd ../
queue=$2  count=$3 logger=$4 interval=$5
start() {
/usr/bin/php ./artisan queue queue=$queue  count=$count logger=$logger interval=$interval run=1 &
}

stop() {
/usr/bin/php ./artisan queue queue=$queue run=0 &
}

killed() {
ps -ef | grep queue=$queue | grep -v grep | awk '{print $2}' | xargs kill -9
}

status() {
process=$(ps -ef | grep artisan | grep queue=$queue | grep -v grep | wc -l)
if [ $process -gt 0 ]
then
echo "queue is running. ($process workers)"
else
echo "queue is not running."
fi
}

case "$1" in
start)
process=$(ps -ef | grep queue=$queue | grep -v grep | wc -l)
if [ $process -ge $count ]
then
echo "queue is running. ($process workers)"
echo "You may stop them before you start."
else
start
fi
;;

stop)
stop
;;

kill)
killed
;;

restart)
stop
sleep 3
start
;;

status)
status
;;
*)
echo "Usage: $0 give a params {start|stop|restart|status}"
esac