function dynamic_selects()
{
    // ONCHANGE na 3 SELECTY
    // CZYSZCZENIE SELECTOW NIZEJ + update ajaxem
    var sels = document.getElementsByTagName('select');
    sels[0].onchange = function()
    {
        what = 'scat';
        document.getElementById('scat').options.length = 0;
        document.getElementById('sscat').options.length = 0;
        document.getElementById('moduls').options.length = 0;
        process('scat&id='+this.options[this.selectedIndex].value);
    }
    sels[1].onchange = function()
    {
        what = 'sscat';
        document.getElementById('sscat').options.length = 0;
        document.getElementById('moduls').options.length = 0;
        process('sscat&id='+this.options[this.selectedIndex].value);
    }
    sels[2].onchange = function()
    {
        what = 'moduls';
        document.getElementById('moduls').options.length = 0;
        process('moduls&id='+this.options[this.selectedIndex].value);
    }
}

function updateSelect(json)
{
    if(json['error'] != null && json['error'] == '1') return false;

    if(what == 'scat')
    {
        select = document.getElementById('scat');
        select.options[0] = new Option('', '', false, false);

        for(i=0; i<json.length; i++)
        {
            select.options[i+1] = new Option(json[i]['subcat'], json[i]['id'], false, false);
        }
    }
    else if(what == 'sscat')
    {
        select = document.getElementById('sscat');
        select.options[0] = new Option('', '', false, false);

        for(i=0; i<json.length; i++)
        {
            select.options[i+1] = new Option(json[i]['subsubcat'], json[i]['id'], false, false);
        }
    }
    else if(what == 'moduls')
    {
        select = document.getElementById('moduls');
        select.options[0] = new Option('', '', false, false);

        for(i=0; i<json.length; i++)
        {
            select.options[i] = new Option(json[i]['modul'], json[i]['id'], false, false);
        }
    }

    return true;
}

addLoadEvent(dynamic_selects);