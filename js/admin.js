var what = null;

function admin()
{
    var sels = document.getElementsByTagName('select');

    if(document.location.hash != '') // zajebista funkcja ustawiajaca formularz na ostatnio uzywane pola
    {
        temp = document.location.hash.substr(1).split('_');

        function konkat(temp, j)
        {
            var ret = temp[0];

            for(var k=1; k<=j; k++)
            {
                ret+= '_'+temp[k];
            }

            return ret;
        }

        for(var j=0; j<temp.length-1; j++)
        {
            for(var i=0; i<sels[j].options.length; i++)
            {
                if(sels[j].options[i].value == konkat(temp, j))
                {
                    sels[j].options[i].selected = 'selected';
                    sels[j].onchange();
                    break;
                }
            }
        }
    }

    // buttony DODAJ EDYTUJ KASUJ ZAPISZ
    // od obszaru
    var butscat = document.getElementById('butscat');
    var inpscat = document.getElementById('inpscat');

    document.getElementById('addscat').onclick = function()
    {
        if(sels[0].options[sels[0].selectedIndex].text != '')
        {
            butscat.parentNode.parentNode.style.display = 'table-row';
            inpscat.name = 'addscat';
            butscat.value = sels[0].options[sels[0].selectedIndex].value;
        }
    }
    document.getElementById('editscat').onclick = function()
    {
        if(sels[1].options[sels[1].selectedIndex].text != '')
        {
            butscat.parentNode.parentNode.style.display = 'table-row';
            inpscat.name = 'editscat';
            inpscat.value = sels[1].options[sels[1].selectedIndex].text;
            butscat.value = sels[1].options[sels[1].selectedIndex].value;
        }
    }
    document.getElementById('delscat').onclick = function()
    {
        if(sels[1].options[sels[1].selectedIndex].text != '')
        {
            inpscat.name = 'delscat';
            inpscat.value = sels[1].options[sels[1].selectedIndex].text+'#'+sels[1].options[sels[1].selectedIndex].value;

            if(confirm('Na pewno skasować?'))
            {
                inpscat.parentNode.submit();
            }
        }
    }

    // buttony DODAJ EDYTUJ KASUJ ZAPISZ
    // od tematyki
    var butsscat = document.getElementById('butsscat');
    var inpsscat = document.getElementById('inpsscat');

    document.getElementById('addsscat').onclick = function()
    {
        if(sels[1].options[sels[1].selectedIndex].text != '')
        {
            butsscat.parentNode.parentNode.style.display = 'table-row';
            inpsscat.name = 'addsscat';
            butsscat.value = sels[1].options[sels[1].selectedIndex].value;
        }
    }
    document.getElementById('editsscat').onclick = function()
    {
        if(sels[2].options[sels[2].selectedIndex].text != '')
        {
            butsscat.parentNode.parentNode.style.display = 'table-row';
            inpsscat.name = 'editsscat';
            inpsscat.value = sels[2].options[sels[2].selectedIndex].text;
            butsscat.value = sels[2].options[sels[2].selectedIndex].value;
        }
    }
    document.getElementById('delsscat').onclick = function()
    {
        if(sels[2].options[sels[2].selectedIndex].text != '')
        {
            inpsscat.name = 'delsscat';
            inpsscat.value = sels[2].options[sels[2].selectedIndex].text+'#'+sels[2].options[sels[2].selectedIndex].value;

            if(confirm('Na pewno skasować?'))
            {
                inpsscat.parentNode.submit();
            }
        }
    }

    // buttony DODAJ EDYTUJ KASUJ ZAPISZ
    // od modulow
    var butmoduls = document.getElementById('butmoduls');
    var inpmoduls = document.getElementById('inpmoduls');

    document.getElementById('addmoduls').onclick = function()
    {
        if(sels[2].options[sels[2].selectedIndex].text != '')
        {
            butmoduls.parentNode.parentNode.style.display = 'table-row';
            inpmoduls.name = 'addmoduls';
            butmoduls.value = sels[2].options[sels[2].selectedIndex].value;
        }
    }
    document.getElementById('editmoduls').onclick = function()
    {
        if(sels[3].options[sels[3].selectedIndex].text != '')
        {
            butmoduls.parentNode.parentNode.style.display = 'table-row';
            inpmoduls.name = 'editmoduls';
            inpmoduls.value = sels[3].options[sels[3].selectedIndex].text;
            butmoduls.value = sels[3].options[sels[3].selectedIndex].value;
        }
    }
    document.getElementById('delmoduls').onclick = function()
    {
        if(sels[3].options[sels[3].selectedIndex].text != '')
        {
            inpmoduls.name = 'delmoduls';
            inpmoduls.value = sels[3].options[sels[3].selectedIndex].text+'#'+sels[3].options[sels[3].selectedIndex].value;

            if(confirm('Na pewno skasować?'))
            {
                inpmoduls.parentNode.submit();
            }
        }
    }
}

addLoadEvent(admin);