#!/usr/bin/python -u

import serial
import sys
import os
import urllib2

# serial port of USB-WDE1
port = '/dev/ttyUSB0'

# MAIN
def main():
  # open serial line
  ser = serial.Serial(port, 9600)
  if not ser.isOpen():
    print "Unable to open serial port %s" % port
    sys.exit(1)

  while(1==1):
    # read line from WDE1
    line = ser.readline()
    line = line.strip()
    print line
    print urllib2.urlopen("http://fbi.gruener-campus-malchow.de/dev/weather.php?string=%s" % line).read()
    
if __name__ == '__main__':
  main()
