function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

function nie_dziala()
{
    alert('Ta funkcja zostanie niedługo uruchomiona! Prosimy o cierpliwość.');
}