function observe()
{
    // do left menu - obserwowanie
    var leftMenu = document.getElementById("main_menu");
    if(leftMenu != null)
    {
        var imgs = leftMenu.getElementsByTagName("img");

        l = imgs.length;
        for(k=0; k<l; k++)
        {
            imgs[k].style["cursor"] = "pointer";
            imgs[k].onclick = function()
            {
                jQuery.getJSON("observe.php", {"id": this.name, "what": document.getElementById("main_menu").getAttribute('class')}, function(json)
                {
                    if(json.result == 1)
                    {
                        alert('Dodano do obserwowanych!');
                    }
                    else alert('Błąd! Nie jesteś zalogowany albo nie aktywowałeś swojego konta.');
                });
            }
        }
    }
    // left menu koniec
}

addLoadEvent(observe);