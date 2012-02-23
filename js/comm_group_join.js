part = {%grupa%};

function init()
{
    if(document.getElementById('addParticipant') != null)
    {
        document.getElementById('addParticipant').style.cursor = 'pointer';
        document.getElementById('addParticipant').onclick = addParticipant;
    }
}

function addParticipant()
{
    if(part++ < 16)
    {
        document.getElementById('uczestnik'+part).style.display = 'block';
    }
}

addLoadEvent(init);