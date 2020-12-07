SELECT 
					m1.timecode AS Zeit, 
					REPLACE(m1.wert,".",",") AS Temperatur, 
					m2.wert AS Luftfeuchte, 
					REPLACE(m3.wert,".",",") AS Windgeschwindigkeit,
					m4.wert AS Niederschlagsmenge,
					m5.wert AS Regen 
				FROM messwerte AS m1
				LEFT JOIN messwerte AS m2 ON m1.timecode = m2.timecode AND m1.sensoren_sensor_pk=5 AND m2.sensoren_sensor_pk=6
				LEFT JOIN messwerte AS m3 ON m1.timecode = m3.timecode AND m3.sensoren_sensor_pk=7
				LEFT JOIN messwerte AS m4 ON m1.timecode = m4.timecode AND m4.sensoren_sensor_pk=8
				LEFT JOIN messwerte AS m5 ON m1.timecode = m5.timecode AND m5.sensoren_sensor_pk=9
				WHERE m1.sensoren_sensor_pk=5	
				ORDER BY m1.timecode DESC LIMIT 1000
