SELECT 
					m1.timecode AS Zeit, 
					REPLACE(m1.wert,".",",") AS Temperatur, 
					m2.wert AS Luftfeuchtigkeit
				FROM messwerte AS m1
				LEFT JOIN messwerte AS m2 ON m1.timecode = m2.timecode AND m1.sensoren_sensor_pk=1 AND m2.sensoren_sensor_pk=2
				WHERE m1.sensoren_sensor_pk=1
				ORDER BY m1.timecode DESC LIMIT 10000
