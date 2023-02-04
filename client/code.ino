/** RDT002 - Alexandros Paliampelos */

#include <TinyGPS++.h>
#include <SoftwareSerial.h>
#include <UnixTime.h>
#include <CAN.h>

#define NO_ERROR 0
#define UNKNOWN_PID -1
#define READ_ERROR -2

//GPS
TinyGPSPlus gps;
SoftwareSerial ss0(4, 3);

//GSM
int onModulePin = 9;
SoftwareSerial ss1(7, 8);

//vars
int8_t answer;
String packet;
UnixTime stamp2(0);

void setup() {
  //Serial.begin(115200);
  Serial.println(F("---------------SETUP------------------"));

  //Begin serial with gsm
  pinMode(onModulePin, OUTPUT);
  ss1.begin(9600);
  if (powerOnSIM900()) {
    delay(3000);
    if (connectedToNetwork()) {
      if (connectCAN()) {
        //Begin serial with gps
        ss0.begin(9600);
      }
    }
  }
}

void loop() {
  Serial.println(F("----------------LOOP------------------"));
  packet = "SNarduino0001;";
  //GPS (NEO-7M) listen
  ss0.listen();   
  //getting gps data is required
  if (getGPS()) {
    //getting can data is optional
    getCAN();
    packet += DECtoHEX(calculateCRC());  //Ouput with sn + gps + acc + can + crc
    packet += "\r\n";                    //end  the packet
    // transform packet (string) -> char array for other functions
    char data[packet.length() + 1];
    strcpy(data, packet.c_str());

    //Listen software serial of SIM900 module
    ss1.listen();                                
    if (getConn()) {                             //Get connection with network
      if (openTCP("iamle.ddns.net", "20222")) {  //Open TCP with server
        delay(20);
        sendPacket(data, sizeof(data));  //Send packet at server
        delay(20);
        closeTCP();  //Close connection
      }
    }
  }

  delay(100);
}

/** SIM 900 MODULE */
boolean powerOnSIM900() {
  Serial.print(F("Powering SIM900 module........."));
  // checks if the module is started
  uint8_t answer = sendATcommand2("AT", "OK", "ERROR", 2000);
  if (answer == 0 || answer == 2) {
    // power on pulse
    digitalWrite(onModulePin, HIGH);
    delay(3000);
    digitalWrite(onModulePin, LOW);

    // waits for an answer from the module
    while (answer == 0 || millis() > 6000) {  // Send AT every two seconds and wait for the answer
      answer = sendATcommand2("AT", "OK", "ERROR", 2000);
    }
  } else if (answer == 1) {
    Serial.println(F("DONE"));
    return true;
  }
  Serial.println(F("ERROR"));
  return false;
}

boolean connectedToNetwork() {
  Serial.print(F("Connecting to the network......"));
  uint8_t answer = sendATcommand2("AT+CREG?", "+CREG: 0,1", "+CREG: 0,5", 1000);
  if (answer == 0) {
    while (answer == 0 || millis() > 2000) {  // Send AT every two seconds and wait for the answer
      answer = sendATcommand2("AT+CREG?", "+CREG: 0,1", "+CREG: 0,5", 1000);
    }
  } else if (answer == 1 || answer == 2) {
    Serial.println(F("DONE"));
    return true;
  }
  Serial.println(F("ERROR"));
  return false;
}

boolean connectCAN() {
  Serial.print(F("Starting CAN bus at 500kbps...."));
  if (CAN.begin(500E3)) {
    Serial.println(F("DONE"));
    CAN.filter(0x7e8);
    return true;
  }
  Serial.println(F("ERROR"));
  return false;
}

/** GPS Module */
boolean getGPS() {
  Serial.print(F("Getting Data from GPRS........"));
  boolean gpsData = false;
  while (millis() < 5000) {
    if (ss0.available() > 0) {
      if (gps.encode(ss0.read())) {
        packet += getGPSInfo(8);
        Serial.println(F("DONE"));
        return true;
      }
    }
  }
  Serial.println(F("no data"));
  return false;
}

String getGPSInfo(int type) {
  switch (type) {
    case 0:
      if (gps.time.isValid()) {
        uint32_t unix = stamp2.getUnix();
        stamp2.setDateTime(gps.date.year(), gps.date.month(), gps.date.day(), gps.time.hour(), gps.time.minute(), gps.time.second());
        return "0000" + String(unix) + ";";
      }
      return "00000000000000;";
    case 1:
      if (gps.date.isValid()) {
        return getYDigitString(gps.date.day(), 2) + "/" + getYDigitString(gps.date.month(), 2) + "/" + gps.date.year() + ";";
      }
      return "00/00/0000;";
    case 2:
      if (gps.time.isValid()) {
        return getYDigitString(gps.time.hour(), 2) + ":" + getYDigitString(gps.time.minute(), 2) + ":" + getYDigitString(gps.time.second(), 2);
        +";";
      }
      return "00:00:00;";
    case 3:
      if (gps.location.isValid()) {
        return String(gps.location.lat(), 6) + ";" + String(gps.location.lng(), 6) + ";";
      }
      return "0;0;";
    case 4:
      if (gps.altitude.isValid()) {
        return getYDigitString(gps.altitude.meters(), 3) + ";";
      }
      return "000;";
    case 5:
      if (gps.speed.isValid()) {
        return getYDigitString(gps.speed.kmph(), 3) + ";";
      }
      return "000;";

    case 6:
      if (gps.course.isValid()) {
        return getYDigitString(gps.course.deg(), 3) + ";";
      }
      return "000;";
    case 7:
      if (gps.satellites.isValid()) {
        return getYDigitString(gps.satellites.value(), 2) + ";";
      }
      return "00;";
    case 8:
      String result = "";
      for (int i = 0; i < 8; i++) {
        result += getGPSInfo(i);
      }
      return result;
  }
}

/** CAN DATA */
void getCAN() {
  Serial.print(F("Getting Data from CAN........."));

  String packet = "0x143A8000;";
  int pidList[] = { 0x04, 0x06, 0x0b, 0x0c, 0x0d, 0x0f, 0x11 };
  int pidCount = sizeof(pidList) / sizeof(*pidList);

  for (int i = 0; i < pidCount; i++) {
    float data = getCANData(pidList[i]);
    if (data == UNKNOWN_PID) {
      Serial.println(F("Error: Unknown PID"));
      return;
    } else if (data == READ_ERROR) {
      Serial.println(F("Error: Failed to read data"));
      return;
    } else {
      packet += String(data);
      packet += ";";
    }
  }

  packet += "NO DTCs;";
  Serial.println(F("done"));
}

float getCANData(int pID) {
  float val;
  CAN.beginPacket(0x7df, 8);
  CAN.write(0x02);  // number of additional bytes
  CAN.write(0x01);  // show current data
  CAN.write(pID);
  CAN.endPacket();

  if (CAN.parsePacket() == 0) return READ_ERROR;
  if (CAN.read() < 3 || CAN.read() != 0x41 || CAN.read() != pID) return READ_ERROR;

  switch (pID) {
    case 0x04:  // Calculated engine load
      val = (CAN.read() * 100) / 255.0;
      break;
    case 0x06:  // Short term fuel trim—Bank 1
      val = ((CAN.read() * 100) / 128.0) - 100.0;
      break;
    case 0x0b:  // Intake manifold absolute pressure
      val = CAN.read();
      break;
    case 0x0c:  // Engine speed
      val = ((CAN.read() * 256.0) + CAN.read()) / 4.0;
      break;
    case 0x0d:  // Vehicle speed
      val = CAN.read();
      break;
    case 0x0f:  // Intake air temperature
      val = (CAN.read() - 40);
      break;
    case 0x11:  // Throttle position
      val = ((CAN.read() * 100.0) / 255.0);
      break;
    default:
      return UNKNOWN_PID;
  }

  return val;
}

/** TCP */
boolean getConn() {
  //Close the GPRS PDP context
  if (sendATcommand2("AT+CIPSHUT", "OK", "ERROR", 5000) == 1) {

    // Selects Single-connection mode
    if (sendATcommand2("AT+CIPMUX=0", "OK", "ERROR", 1000) == 1) {
      // Waits for status IP INITIAL
      while (sendATcommand2("AT+CIPSTATUS", "INITIAL", "", 500) == 0)
        ;

      // Sets the APN, user name and password
      if (sendATcommand2("AT+CSTT=\"truphone.com\",\"\",\"\"", "OK", "ERROR", 20000) == 1) {
        // Waits for status IP START
        while (sendATcommand2("AT+CIPSTATUS", "START", "", 500) == 0)
          ;

        // Brings Up Wireless Connection
        if (sendATcommand2("AT+CIICR", "OK", "ERROR", 20000) == 1) {
          // Waits for status IP GPRSACT
          while (sendATcommand2("AT+CIPSTATUS", "GPRSACT", "", 500) == 0)
            ;

          // Gets Local IP Address
          if (sendATcommand2("AT+CIFSR", ".", "ERROR", 10000) == 1) {
            // Waits for status IP STATUS
            while (sendATcommand2("AT+CIPSTATUS", "IP STATUS", "", 500) == 0)
              ;
            Serial.println(F("Ready to open TCP"));
            return true;
          } else {
            Serial.println(F("Error getting the local IP address"));
          }
        } else {
          Serial.println(F("Error bring up wireless connection"));
        }
      } else {
        Serial.println(F("Error setting the APN"));
      }
    } else {
      Serial.println(F("Error setting the single connection"));
    }
  } else {
    Serial.println(F("Error closing GPRS PDP context"));
  }
  return false;
}

boolean openTCP(char const* serverIP, char const* serverPort) {
  Serial.print(F("Openning TCP with server XXX.XXX.XXX.XXX at port 20222...."));
  char command[44];
  sprintf(command, "AT+CIPSTART=\"TCP\",\"%s\",\"%s\"", serverIP, serverPort);
  if (sendATcommand2(command, "CONNECT OK", "CONNECT FAIL", 10000) == 1) {
    Serial.println(F("success!"));
    delay(30);
    return true;
  } else {
    Serial.println(F("error"));
    return false;
  }
}

boolean closeTCP() {
  if (sendATcommand2("AT+CIPCLOSE", "CLOSE OK", "ERROR", 10000)) {
    Serial.println(F("TCP Closed"));
    return true;
  } else {
    Serial.println(F("Error closing TCP"));
    return false;
  }
}

boolean sendPacket(char* pck, int bufferSize) {
  char aux_str[sizeof(pck) + 10];
  Serial.println(F("Sending: "));
  Serial.print(pck);

  // Sends some data to the TCP socket
  sprintf(aux_str, "AT+CIPSEND=%d", strlen(pck));
  if (sendATcommand2(aux_str, ">", "ERROR", 10000) == 1) {
    sendATcommand2(pck, "SEND OK", "ERROR", 10000);
    Serial.println(F("PACKET SEND"));
    return true;
  } else {
    Serial.println(F("PACKET DIDNT SEND"));
    return false;
  }
  return false;
}

int8_t sendATcommand2(char const* ATcommand, char const* expected_answer1,
                      char const* expected_answer2, unsigned int timeout) {
  uint8_t x = 0, answer = 0;
  char response[150];
  unsigned long previous;

  memset(response, '\0', 100);  // Initialize the string

  delay(100);

  while (ss1.available() > 0) ss1.read();  // Clean the input buffer

  ss1.println(ATcommand);  // Send the AT command

  x = 0;
  previous = millis();

  // this loop waits for the answer
  do {
    // if there are data in the UART input buffer, reads it and checks for the asnwer
    if (ss1.available() != 0) {
      response[x] = ss1.read();
      x++;
      // check if the desired answer 1  is in the response of the module
      if (strstr(response, expected_answer1) != NULL) {
        answer = 1;
      }
      // check if the desired answer 2 is in the response of the module
      else if (strstr(response, expected_answer2) != NULL) {
        answer = 2;
      }
    }
  }
  // Waits for the asnwer with time out
  while ((answer == 0) && ((millis() - previous) < timeout));

  return answer;
}

/** Helpers */
int calculateCRC() {
  int resultDEC = 0;
  int i = 0;
  if (packet.length() != 0) {
    for (auto x : packet) {
      resultDEC += (int)x;
      if (resultDEC >= 0xFFFF) {
        resultDEC = 0xFFFF - resultDEC;
      }
    }
  }
  return resultDEC;
}

String DECtoHEX(int inputDEC) {
  String outputHEX = "*0000";
  outputHEX = String(inputDEC, HEX);
  outputHEX.toUpperCase();
  if (outputHEX.length() != 4) {
    if (outputHEX.length() == 1) {
      outputHEX = "000" + outputHEX;
    } else if (outputHEX.length() == 2) {
      outputHEX = "00" + outputHEX;
    } else if (outputHEX.length() == 3) {
      outputHEX = "0" + outputHEX;
    } else {
      outputHEX = "0000";
    }
  }
  return "*" + outputHEX;
}

String getYDigitString(const int& x, int y)  // 00-99
{
  if (y == 2) {
    if (x == 0) { return "00"; }
    if (x < 10) { return "0" + String(x); }
  }
  if (y == 3) {
    if (x == 0) { return "000"; }
    if (x < 10) { return "00" + String(x); }
    if (x > 99) { return String(x); }
  }
  return String(x);
}
