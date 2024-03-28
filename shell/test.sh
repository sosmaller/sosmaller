#!/bin/bash
path=`dirname $0`
cd $path
cd ../

queue=default
count=2
logger=1
interval=1000

if [ $1 ]
then
./shell/base.sh $1 $queue $count $logger $interval
else
echo  "give a params usage: $0 {start|stop|restart|status}"
fi