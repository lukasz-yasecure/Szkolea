function init()
{
    // zmiana action przy formie od search comms / servs
    var nnn = document.getElementsByName('what');
    if(nnn.length == 2)
    {
        var form = document.getElementById('search_what');

        nnn[0].onclick = function()
        {
            form.name = 'comms';
        }

        nnn[1].onclick = function()
        {
            form.name = 'servs';
        }

        /*nnn[0].onclick = function()
        {
            form[0].action = 'index.php?comms';
        }
        
        nnn[1].onclick = function()
        {
            form[0].action = 'index.php?servs';
        }*/
    }

    if(document.getElementById('search_submit') != null)
    {
        // gdy index.php
        document.getElementById('search_submit').style.cursor = 'pointer';
        document.getElementById('search_submit').onclick = search;
    }

    // jesli jeszcze niezalogowany
    if(document.getElementById('log_submit') != null)
    {
        document.getElementById('log_submit').style.cursor = 'pointer';
        document.getElementById('log_submit').onclick = log;
    }

    // rejestracja
    if(document.getElementById('reg_submit') != null)
    {
        document.getElementById('reg_submit').style.cursor = 'pointer';
        document.getElementById('reg_submit').onclick = reg;
    }

    if(document.getElementById('comm_submit') != null)
    {
        document.getElementById('comm_submit').style.cursor = 'pointer';
        document.getElementById('comm_submit').onclick = function() {document.commForm.submit();}
    }

    if(document.getElementById('serv_submit') != null)
    {
        document.getElementById('serv_submit').style.cursor = 'pointer';
        document.getElementById('serv_submit').onclick = function() {document.servForm.submit();}
    }

    if(document.getElementById('addParticipant') != null)
    {
        document.getElementById('addParticipant').style.cursor = 'pointer';
        document.getElementById('addParticipant').onclick = null;
        document.getElementById('addParticipant').onclick = addParticipant;
    }
}

function search()
{
    document.searchForm.submit();
}

function log()
{
    document.logForm.submit();
}

function reg()
{
    document.regForm.submit();
}

function showProgram(id)
{
    div = document.getElementById('comm'+id);
    
    if(div.style.display == 'none')
    {
        div.style.display = 'table-row';
    }
    else
    {
        div.style.display = 'none';
    }
}

addLoadEvent(init);