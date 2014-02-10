#!/bin/bash
BINDIR="/usr/local/bin/"
GRAPHDIR="/home/htdocs/moosy.net/www/graphs/"
$BINDIR/ems-gen-graphs.py $GRAPHDIR day > /dev/null 2>&1
$BINDIR/ems-gen-graphs.py $GRAPHDIR halfweek > /dev/null 2>&1
$BINDIR/ems-gen-graphs.py $GRAPHDIR week > /dev/null 2>&1
$BINDIR/ems-gen-graphs.py $GRAPHDIR month > /dev/null 2>&1
$BINDIR/calcheizkurve.sh

 