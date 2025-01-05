//Endpoint: http://IP Address:port/iot_aircon_alarm_system_backend/public/?req=device -> GET: READ
//Endpoint: http://IP Address:port/iot_aircon_alarm_system_backend/public/alarmParams.php -> POST: CREATE, UPDATE
class Api {
  // Localhost connection

  //final String baseUrl = 'http://192.168.1.3'; // Change the IP address here.
  final String baseUrl = '';
  final String port = '8000';
  final String directory = 'iot_aircon_alarm_system_backend/public';

  /*String endPoint() {
    return '$baseUrl:$port/$directory';
  }*/

  String endPoint() {
    return ':$port/$directory';
  }

  //Remote connection or with Web Hosting
  /*
  //final String baseUrl = 'http://ateneodenagaiotproject.transmediaelements.com';
  final String baseUrl = '';
  final String port = '';
  final String directory = 'public';

  String endPoint() {
    return '$baseUrl/$directory';
  }
 */
}


