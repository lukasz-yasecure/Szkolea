function add_serv()
{
    var inp = document.getElementById('doUzgodnienia');

    if(inp != null && !inp.checked)
    {
        document.getElementById('doUzgodnienia').onclick = function()
        {
            if(document.getElementById('exampleXIa').style.backgroundColor != 'gray')
            {
                document.getElementById('exampleXIa').disabled = "disabled";
                document.getElementById('exampleXIa').style.backgroundColor = "gray";
                document.getElementById('exampleXIb').disabled = "disabled";
                document.getElementById('exampleXIb').style.backgroundColor = "gray";
            }
            else
            {
                document.getElementById('exampleXIa').disabled = "";
                document.getElementById('exampleXIa').style.backgroundColor = "";
                document.getElementById('exampleXIb').disabled = "";
                document.getElementById('exampleXIb').style.backgroundColor = "";
            }
        }
    }
    else if(inp.checked)
    {
        document.getElementById('exampleXIa').disabled = "disabled";
        document.getElementById('exampleXIa').style.backgroundColor = "gray";
        document.getElementById('exampleXIb').disabled = "disabled";
        document.getElementById('exampleXIb').style.backgroundColor = "gray";

        document.getElementById('doUzgodnienia').onclick = function()
        {
            if(document.getElementById('exampleXIa').style.backgroundColor != 'gray')
            {
                document.getElementById('exampleXIa').disabled = "disabled";
                document.getElementById('exampleXIa').style.backgroundColor = "gray";
                document.getElementById('exampleXIb').disabled = "disabled";
                document.getElementById('exampleXIb').style.backgroundColor = "gray";
            }
            else
            {
                document.getElementById('exampleXIa').disabled = "";
                document.getElementById('exampleXIa').style.backgroundColor = "";
                document.getElementById('exampleXIb').disabled = "";
                document.getElementById('exampleXIb').style.backgroundColor = "";
            }
        }
    }
}

addLoadEvent(add_serv);