1.	/*
2.	 * RDT001 - Alexandros Paliampelos
3.	 */
4.	 
5.	#include <TinyGPS++.h>
6.	#include <SoftwareSerial.h>
7.	#include <UnixTime.h>
8.	#include <CAN.h>
9.	 
10.	//GPS
11.	TinyGPSPlus gps;
12.	SoftwareSerial ss0(4, 3);
13.	UnixTime stamp2(0);
14.	 
15.	//GSM
16.	int onModulePin = 9;
17.	SoftwareSerial ss1(7, 8);
18.	 
19.	//vars
20.	int8_t answer;
21.	//static String SN = "SNarduino0001;";
22.	String packet;
23.	int counter = 0;
24.	 
25.	void setup() {
26.	 
27.	  pinMode(onModulePin, OUTPUT);
28.	  //Serial.begin(115200);
29.	  ss1.begin(9600);
30.	  Serial.print(F("Powering SIM900 module........."));
31.	  powerOnSIM900();
32.	  delay(3000);
33.	  Serial.println(F("done"));
34.	  Serial.print(F("Connecting to the network......"));
35.	  while ( sendATcommand2("AT+CREG?", "+CREG: 0,1", "+CREG: 0,5", 1000) == 0 );
36.	  Serial.println(F("done"));
37.	 
38.	  Serial.print(F("Starting CAN bus at 500kbps...."));  
39.	  if (!CAN.begin(500E3)) {
40.	    Serial.println(F("error"));
41.	    while (1);
42.	  } else {
43.	    Serial.println(F("done"));
44.	  }
45.	  CAN.filter(0x7e8);
46.	 
47.	  ss0.begin(9600);
48.	}
49.	 
50.	void loop() {
51.	  Serial.println(F("---------------------------------"));
52.	  Serial.print(F("counter: "));
53.	  Serial.println(counter);
54.	 
55.	  packet ="";                           //Packet init
56.	  ss0.listen();                         //Listen software serial of NEO-7M module
57.	  packet = "SNarduino0001;";
58.	  getGPS();                             //GET gps data
59.	  packet += getAccelerometerXYZ(3);     //GET accelerometer data
60.	  getCAN();                             //GET can data
61.	  packet +=DECtoHEX(calculateCRC());    //Ouput with sn + gps + acc + can + crc
62.	  packet += "\r\n";                     //end  the packet
63.	  // transform packet (string) -> char array for other functions
64.	  char data[packet.length()+1] ;
65.	  strcpy(data, packet.c_str());
66.	   
67.	  ss1.listen();                        //Listen software serial of SIM900 module
68.	  if (getConn()) {                     //Get connection with network
69.	     if (openTCP()) {                  //Open TCP with server  
70.	        delay(20);
71.	        sendPacket(data, sizeof(data));//Send packet at server
72.	        delay(20);
73.	        closeTCP();                    //Close connection
74.	     }
75.	  }
76.	  counter++;
77.	  delay(100);
78.	}
79.	 
80.	 
81.	/*
82.	 *  TCP 
83.	 */
84.	 
85.	boolean getConn() {
86.	  //Close the GPRS PDP context
87.	  if (sendATcommand2("AT+CIPSHUT", "OK", "ERROR", 5000) == 1) {
88.	     
89.	    // Selects Single-connection mode
90.	    if (sendATcommand2("AT+CIPMUX=0", "OK", "ERROR", 1000) == 1)
91.	    {
92.	      // Waits for status IP INITIAL
93.	      while (sendATcommand2("AT+CIPSTATUS", "INITIAL", "", 500)  == 0 );
94.	 
95.	      // Sets the APN, user name and password
96.	      if (sendATcommand2("AT+CSTT=\"truphone.com\",\"\",\"\"", "OK",  "ERROR", 20000) == 1)
97.	      {
98.	        // Waits for status IP START
99.	        while (sendATcommand2("AT+CIPSTATUS", "START", "", 500)  == 0 );
100.	       
101.	        // Brings Up Wireless Connection
102.	        if (sendATcommand2("AT+CIICR", "OK", "ERROR", 20000) == 1)
103.	        {
104.	          // Waits for status IP GPRSACT
105.	          while (sendATcommand2("AT+CIPSTATUS", "GPRSACT", "", 500)  == 0 );
106.	 
107.	          // Gets Local IP Address
108.	          if (sendATcommand2("AT+CIFSR", ".", "ERROR", 10000) == 1)
109.	          {
110.	            // Waits for status IP STATUS
111.	            while (sendATcommand2("AT+CIPSTATUS", "IP STATUS", "", 500)  == 0 );
112.	            Serial.println(F("Ready to open TCP"));
113.	            return true;
114.	          } else { Serial.println(F("Error getting the local IP address")); }
115.	        } else { Serial.println(F("Error bring up wireless connection")); }
116.	      } else { Serial.println(F("Error setting the APN")); }
117.	    } else { Serial.println(F("Error setting the single connection")); }
118.	  } else { Serial.println(F("Error closing GPRS PDP context")); }
119.	  return false;
120.	}
      
121.	 //Replace XXX.XXX.XXX.XXX. with your IP and YYYYY with your port! 
122.	boolean openTCP() {
123.	 Serial.print(F("Openning TCP with server XXX.XXX.XXX.XXX at port YYYYY...."));
125.	  if (sendATcommand2("AT+CIPSTART=\"TCP\",\"XXX.XX.XX.XX\",\"YYYYY\"",
126.	                   "CONNECT OK", "CONNECT FAIL", 10000) == 1)
127.	  {
128.	    Serial.println(F("success!"));
129.	    delay(30);
130.	    return true;
131.	  } else { 
132.	    Serial.println(F("error"));
133.	    return false;
134.	  } 
135.	}
136.	 
137.	boolean closeTCP() {
138.	   if (sendATcommand2("AT+CIPCLOSE", "CLOSE OK", "ERROR", 10000))
139.	   {
140.	      Serial.println(F("TCP Closed"));
141.	      return true;
142.	   } else {
143.	      Serial.println(F("Error closing TCP"));
144.	      return false; 
145.	   }
146.	}
147.	 
148.	boolean sendPacket(char* pck, int bufferSize) {
149.	  char aux_str[sizeof(pck)+10];
150.	  Serial.println(F("Sending: "));
151.	  Serial.print(pck);
152.	   
153.	  // Sends some data to the TCP socket
154.	  sprintf(aux_str, "AT+CIPSEND=%d", strlen(pck));
155.	  if (sendATcommand2(aux_str, ">", "ERROR", 10000) == 1)
156.	  {
157.	    sendATcommand2(pck, "SEND OK", "ERROR", 10000);
158.	    Serial.println(F("PACKET SEND"));
159.	    return true;
160.	  } else {
161.	    Serial.println(F("PACKET DIDNT SEND"));
162.	    return false;
163.	  }
164.	  return false;
165.	}
166.	 
167.	int8_t sendATcommand2(char const* ATcommand, char const* expected_answer1,
168.	                      char const* expected_answer2, unsigned int timeout) {
169.	 
170.	  uint8_t x = 0,  answer = 0;
171.	  char response[150];
172.	  unsigned long previous;
173.	 
174.	  memset(response, '\0', 100);    // Initialize the string
175.	 
176.	  delay(100);
177.	 
178.	  while ( ss1.available() > 0) ss1.read();   // Clean the input buffer
179.	 
180.	  ss1.println(ATcommand);    // Send the AT command
181.	 
182.	  x = 0;
183.	  previous = millis();
184.	 
185.	  // this loop waits for the answer
186.	  do {
187.	    // if there are data in the UART input buffer, reads it and checks for the asnwer
188.	    if (ss1.available() != 0) {
189.	      response[x] = ss1.read();
190.	      x++;
191.	      // check if the desired answer 1  is in the response of the module
192.	      if (strstr(response, expected_answer1) != NULL)
193.	      {
194.	        answer = 1;
195.	      }
196.	      // check if the desired answer 2 is in the response of the module
197.	      else if (strstr(response, expected_answer2) != NULL)
198.	      {
199.	        answer = 2;
200.	      }
201.	    }
202.	  }
203.	  // Waits for the asnwer with time out
204.	  while ((answer == 0) && ((millis() - previous) < timeout));
205.	 
206.	  return answer;
207.	}
208.	 
209.	 
210.	/*
211.	 * CAN DATA
212.	 */
213.	 
214.	void getCAN() {
215.	 
216.	  Serial.print(F("Getting Data from CAN........."));
217.	  /*
220.	                        //04 --> egine load
221.	                         //05--> coolant temp
222.	         
223.	  */
224.	    /* pids:
225.	   * 0x04 0x06 0x0b 0x0c 0x0d 0x0f 0x11
226.	   */
227.	  // service 01 pid 00 for pids above: 143A8000
228.	   
229.	  packet += "0x143A8000;";
230.	  float result; 
231.	  int i = 0x04;
232.	  boolean flag = true;
233.	  while (flag) {
234.	    packet += getCANData(i);  
235.	    packet += ";";
236.	    if      ( i == 0x04 ) { i = 0x06; }
237.	    else if ( i == 0x06 ) { i = 0x0b; }
238.	    else if ( i == 0x0b ) { i = 0x0c; }
239.	    else if ( i == 0x0c ) { i = 0x0d; }
240.	    else if ( i == 0x0d ) { i = 0x0f; }
241.	    else if ( i == 0x0f ) { i = 0x11; }
242.	    else if ( i == 0x11 ) {flag=false;}
243.	  }
244.	   
245.	  packet += "NO DTCs;";
246.	  Serial.println(F("done"));
247.	}
248.	 
249.	float getCANData(int pID){
250.	   
251.	  float val;
252.	  CAN.beginPacket(0x7df, 8);
253.	  CAN.write(0x02);            // number of additional bytes
254.	  CAN.write(0x01);            // show current data
255.	   
256.	  //cant pass hex number we need to transform dec to hex 
257.	  if      ( pID == 0x01 ) {               // not implemented
258.	    CAN.write(0x01); CAN.endPacket();   
259.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x01);}
260.	  else if ( pID == 0x03 ) {             // not implemented
261.	    CAN.write(0x03); CAN.endPacket(); 
262.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x03);}
263.	  else if ( pID == 0x04 ) {             // Calculated engine load
264.	    CAN.write(0x04); CAN.endPacket(); 
265.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x04);
266.	     val = (CAN.read() * 100) / 255.0;}
267.	  else if ( pID == 0x05 ) {             // not implemented
268.	    CAN.write(0x05); CAN.endPacket(); 
269.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x05);}
270.	  else if ( pID == 0x06 ) {             // Short term fuel trim—Bank 1
271.	    CAN.write(0x06); CAN.endPacket(); 
272.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x06);
273.	     val = ((CAN.read() * 100) / 128.0) - 100.0;}
274.	  else if ( pID == 0x0b ) {             // Intake manifold absolute pressure
275.	    CAN.write(0x0b); CAN.endPacket(); 
276.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x0b);
277.	    val = CAN.read();}
278.	  else if ( pID == 0x0c ) {             // Engine speed
279.	    CAN.write(0x0c); CAN.endPacket(); 
280.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x0c);
281.	    val = ((CAN.read() * 256.0) + CAN.read()) / 4.0;}
282.	  else if ( pID == 0x0d ) {             // Vehicle speed
283.	    CAN.write(0x0d); CAN.endPacket(); 
284.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x0d);
285.	    val = CAN.read();}
286.	  else if ( pID == 0x0f ) {             // Intake air temperature
287.	    CAN.write(0x0f); CAN.endPacket(); 
288.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x0f);
289.	    val =  (CAN.read() - 40);}
290.	  else if ( pID == 0x11 ) {             // Throttle position
291.	    CAN.write(0x11); CAN.endPacket(); 
292.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x11);
293.	    val = (CAN.read() * 100) / 255.0;}
294.	  else if ( pID == 0x13 ) {             // not implemented
295.	    CAN.write(0x13); CAN.endPacket(); 
296.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x13);}
297.	  else if ( pID == 0x14 ) {             // not implemented
298.	    CAN.write(0x14); CAN.endPacket(); 
299.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x14);}
300.	  else if ( pID == 0x1c ) {             // not implemented
301.	    CAN.write(0x1c); CAN.endPacket(); 
302.	    while (CAN.parsePacket() == 0 || CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != 0x1c);}
303.	 
304.	  return val;
305.	}
306.	  
307.	/*
308.	 * GPS Module
309.	 */
310.	void getGPS() {
311.	 
312.	  Serial.print(F("Getting Data from GPRS........"));
313.	  boolean gpsData = false;
314.	  while (gpsData == false || millis() < 5000){
315.	    if (ss0.available() > 0) {
316.	      if (gps.encode(ss0.read())) {
317.	        //packet = SN + getGPSInfo(9);
318.	        gpsData = true;
319.	      }
320.	    } 
321.	  }
322.	   
323.	 if (gpsData == true ) {       //we have gps data
324.	    packet += getGPSInfo(9);        //now check if timestamp is ok
325.	    if ( packet.indexOf("00000000000000;") == -1  ) {
326.	        Serial.println(F("done"));
327.	        //return packet;
328.	    }
329.	  }
330.	  else {
331.	    Serial.println(F("no data"));
332.	    gpsData = false;  // get again gps data
333.	    packet += "0000000000000;00/00/0000;00:00:00;-00.000000;-00.000000;000;000;000;0;00;-0.00;-0.00;-0.00;";
334.	            //02450766850052;31/12/2020;20:41:32;+38.637977;+24.108908;299;000;084;1;06;-1.00;-0.08;-0.02;
335.	    //return packet;
336.	  }
337.	}
338.	 
339.	String getGPSInfo(int type) {
340.	   
341.	    if (type == 0) {               //get timestamp
342.	    String tims = "00000000000000;";
343.	      if (gps.time.isValid()){
344.	        //uint32_t unix1 = stamp2.getUnix();
345.	        uint32_t unix2 = stamp2.getUnix();
346.	        stamp2.setDateTime(gps.date.year(), gps.date.month(), gps.date.day(), gps.time.hour(), gps.time.minute(), gps.time.second());
347.	        tims = "0000" + String(unix2) +";";
348.	      }
349.	      return tims;
350.	  } 
351.	  if (type == 1){               //get date
352.	      String dat = "00/00/0000;";
353.	      if (gps.date.isValid()){
354.	          dat = getYDigitString(gps.date.day(),2);
355.	          dat += "/";
356.	          dat += getYDigitString(gps.date.month(),2);
357.	          dat += "/";
358.	          dat += gps.date.year();
359.	          dat += ";";
360.	      }  
361.	      return dat;
362.	  }  
363.	  if (type == 2) {               //get time
364.	    String tim = "00:00:00;";
365.	      if (gps.time.isValid()){
366.	          tim = getYDigitString(gps.time.hour(),2);
367.	          tim += ":";
368.	          tim += getYDigitString(gps.time.minute(),2);
369.	          tim += ":";
370.	          tim += getYDigitString(gps.time.second(),2);
371.	          tim += ";";
372.	      }
373.	      return tim;
374.	  } 
375.	  if (type == 3) {              //get location
376.	    String loc = "00.00000;00.00000;";
377.	      if (gps.location.isValid()) {
378.	        double a = gps.location.lat();
379.	        double b = gps.location.lng();
380.	        loc = String(a,6);
381.	        loc += ";";
382.	        loc += String(b,6);
383.	        loc += ";";
384.	      }
385.	     return loc;
386.	  } 
387.	  if (type == 4) {          // get altitude  
388.	     String alt = "000;";
389.	     if (gps.altitude.isValid()) {
390.	      alt = getYDigitString(gps.altitude.meters(),3);
391.	      alt += ";";
392.	     } 
393.	     return alt;
394.	  } 
395.	  if (type == 5) {          //get gps speed
396.	     String spe = "000;";
397.	     if (gps.speed.isValid()) {
398.	      spe = getYDigitString(gps.speed.kmph(),3);
399.	      spe += ";";
400.	     }
401.	     return spe;
402.	  } 
403.	  if (type == 6) {          //get direction
404.	     String dir = "000;";
405.	     if (gps.course.isValid()) {
406.	      dir = getYDigitString(gps.course.deg(),3);
407.	      dir += ";";
408.	     }
409.	     return dir;
410.	  } 
411.	  if (type == 7) {          //get gps valid
412.	    String val = "1;";
413.	    return val;
414.	  } 
415.	  if (type == 8) {          //get gps sattelites
416.	    String sat = "00;";    
417.	    if (gps.satellites.isValid()) {
418.	      sat = getYDigitString(gps.satellites.value(),2);
419.	      sat += ";";
420.	    }
421.	    return sat;
422.	  } 
423.	  if (type == 9 ) {          //get all data from gps
424.	    int i;
425.	    String s ="";
426.	    for (i=0; i < 9; i++) {
427.	      s += getGPSInfo(i);
428.	    }
429.	    return s;
430.	  }
431.	}
432.	 
433.	/*
434.	 * Accelometer Module (not implemented)  
435.	 */
436.	String getAccelerometerXYZ(int type) {
437.	  if (type == 0){               
438.	      String x = "-0.00;";
439.	      return x;
440.	  }
441.	  if (type == 1){            
442.	      String y = "-0.00;";
443.	      return y;
444.	  }    
445.	  if (type == 2){            
446.	      String z = "-0.00;";
447.	      return z;
448.	  }   
449.	  if (type == 3){            
450.	    int i;
451.	    String s ="";
452.	    for (i=0; i < 3; i++) {
453.	      s += getAccelerometerXYZ(i);
454.	    }
455.	    return s;
456.	  }   
457.	}
458.	 
459.	/*
460.	 * SIM 900 MODULE
461.	 */
462.	void powerOnSIM900() {
463.	  uint8_t answer = 0;
464.	  // checks if the module is started
465.	  answer = sendATcommand2("AT", "OK", "OK", 2000);
466.	  if (answer == 0)
467.	  {
468.	    // power on pulse
469.	    digitalWrite(onModulePin, HIGH);
470.	    delay(3000);
471.	    digitalWrite(onModulePin, LOW);
472.	 
473.	    // waits for an answer from the module
474.	    while (answer == 0) {   // Send AT every two seconds and wait for the answer
475.	      answer = sendATcommand2("AT", "OK", "OK", 2000);
476.	    }
477.	  }
478.	}
479.	 
480.	/*
481.	 *  TOOLS
482.	 */
483.	 
484.	int calculateCRC() {
485.	  int resultDEC = 0;
486.	  int i = 0;
487.	  if (packet.length() != 0) {
488.	    for (auto x : packet ) {
489.	      resultDEC += (int)x;
490.	      if (resultDEC >= 0xFFFF) {
491.	         resultDEC = 0xFFFF - resultDEC;
492.	      }
493.	    }
494.	  }
495.	  return resultDEC;
496.	}
497.	 
498.	String DECtoHEX(int inputDEC) {
499.	  String outputHEX = "*0000";
500.	  outputHEX = String(inputDEC, HEX);
501.	  outputHEX.toUpperCase();
502.	  if ( outputHEX.length() != 4 ) {
503.	    if ( outputHEX.length() == 1 ) {
504.	      outputHEX = "000" + outputHEX;
505.	    } else if ( outputHEX.length() == 2) {
506.	      outputHEX = "00" + outputHEX;
507.	    } else if ( outputHEX.length() == 3) {
508.	      outputHEX = "0" + outputHEX;
509.	    } else {
510.	      outputHEX = "0000";
511.	    }
512.	  }
513.	  return "*" + outputHEX;
514.	}
515.	 
516.	String getYDigitString(const int& x, int y) // 00-99
517.	{
518.	  if (y == 2) {
519.	    if (x==0)   { return "00"; }
520.	    if (x<10)   { return "0" + String(x); }
521.	  }
522.	  if (y == 3) {
523.	    if (x==0)   { return "000"; }
524.	    if (x<10)   { return "00" + String(x); }
525.	    if (x > 99) { return String(x); }
526.	  }
527.	  return String(x);
528.	}
