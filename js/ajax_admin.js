// przechowuje obiekt XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();

// tworzy obiekt XMLHttpRequest
function createXmlHttpRequestObject()
{
  // przechowa odwołanie do obiektu XMLHttpRequest
  var xmlHttp;
  // powinno działać dla wszystkich przeglądarek z wyjątkiem IE6 i starszych
  try
  {
    // próbuje utworzyć obiekt XMLHttpRequest
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    // w przypadku, gdy przeglądarką jest IE6 lub starsz
    var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
      "MSXML2.XMLHTTP.5.0",
      "MSXML2.XMLHTTP.4.0",
      "MSXML2.XMLHTTP.3.0",
      "MSXML2.XMLHTTP",
      "Microsoft.XMLHTTP");
    // sprawdza każdy identyfikator programu, aż jeden zadziała
    for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
    {
      try
      {
        // próbuje utworzyć obiekt XMLHttpRequest
        xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
      }
      catch (e) {}
    }
  }
  // zwraca utworzony obiekt lub wyświetla komunikat o błędzie
  if (!xmlHttp)
    alert("Błąd podczas tworzenia obiektu XMLHttpRequest.");
  else
    return xmlHttp;
}

// wywołana do odczytania pliku z serwera
function process(par)
{
  // kontynuuje jedynie jeśli obiekt xmlHttp nie jest zajęty
  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  {
    xmlHttp.open("GET", "admin.php?get="+par, false);
    xmlHttp.onreadystatechange = handleServerResponse;
    xmlHttp.send(null);
  }
 else
    // jeśli połączenie jest zajęte, ponawia próbę po 1 sekundzie
    setTimeout('process('+par+')', 1000);
}

// funkcja obsługująca odpowiedź http
function handleServerResponse()
{
  // kontynuuje jedynie jeśli transakcja została zakończona
  if (xmlHttp.readyState == 4)
  {
    // status 200 oznacza pomyślne ukończenie transakcji
    if (xmlHttp.status == 200)
    {
      // wyodrębnia wiadomość XML wysłaną z serwera
      var json_data = xmlHttp.responseText;
      var the_object = eval("("+json_data+")");
      updateSelect(the_object);
    }
    // dla statusu protokołu HTTP innego niż 200 zgłasza błąd
    else
    {
      alert("Wystąpił błąd podczas uzyskiwania dostępu do serwera: " + xmlHttp.statusText);
    }
  }
}