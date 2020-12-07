#!/usr/bin/python -u

import serial
import sys
import os
import urllib2
import json
import datetime
import socket

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
    #print urllib2.urlopen("http://fbi.gruener-campus-malchow.de/dev/weather.php?string=%s" % line).read()
    # create dictionary from line with timestamp
    time = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    print time		
    dictionary = {
      'timestamp': time,
      'sensordata': line,
      'sensorID': socket.gethostname(),
    }
    # write line to json-file
    with open('/home/pi/wetterdaten/record_' + time + '.json', 'w') as outfile:
	json.dump(dictionary, outfile)

if __name__ == '__main__':
  main()
