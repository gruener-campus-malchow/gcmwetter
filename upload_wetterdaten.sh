#!/bin/sh

# iterate over all records

for file in *.json
do
	content=$(cat "$file")
	#echo $content
	
	#curl -XPOST -i -H "Content-Type: application/json; charset=UTF-8" -d '{\"k\":\"val\"}' http://fbi.gruener-campus-malchow.de/cis/api/wetterstation
	curl -XPOST http://fbi.gruener-campus-malchow.de/cis/api/wetterstation -F comment="$content"
	mv "$file" /home/pi/wetterdaten/transmitted
done

find /home/pi/wetterdaten/transmitted -iname "*" -mtime +60 -delete

echo $(date)'   end upload script' >> upload_wetterdaten.log
