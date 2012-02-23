/*
 * hints do formularza dodawania uslugi
 */

function prepareInputsForHints() {
  var inputs = document.getElementsByTagName("input");
  for (var i=0; i<inputs.length; i++){
    if (inputs[i].parentNode.getElementsByTagName("span")[0]) {
      inputs[i].onfocus = function () {
        this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
      }
      inputs[i].onblur = function () {
        this.parentNode.getElementsByTagName("span")[0].style.display = "none";
      }
    }
  }
  var selects = document.getElementsByTagName("select");
  for (var k=0; k<selects.length; k++){
    if (selects[k].parentNode.getElementsByTagName("span")[0]) {
      selects[k].onfocus = function () {
        this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
      }
      selects[k].onblur = function () {
        this.parentNode.getElementsByTagName("span")[0].style.display = "none";
      }
    }
  }
  var textareas = document.getElementsByTagName("textarea");
  for (var m=0; m<textareas.length; m++){
    textareas[m].onfocus = function () {
      this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
    }
    textareas[m].onblur = function () {
      this.parentNode.getElementsByTagName("span")[0].style.display = "none";
    }
  }
  var button = document.getElementById("addParticipant");
    button.onfocus = function () {
      this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
    }
    button.onblur = function () {
      this.parentNode.getElementsByTagName("span")[0].style.display = "none";
    }

  var calA = document.getElementById("drugi_termin_cb");
  var calB = document.getElementById("exampleXIa");
  var calC = document.getElementById("exampleXIb");
  var calD = document.getElementById("exampleXIc");
  var calE = document.getElementById("exampleXId");
  var cals = new Array(calA, calB, calC, calD, calE);

  for(var n=0; n<cals.length; n++)
  {
    cals[n].onfocus = function () {
      document.getElementById("cal_hint").style.display = "inline";
    }
    cals[n].onblur = function () {
      document.getElementById("cal_hint").style.display = "none";
    }
  }

  for(var o=0; o<=7; o++)
  {
    x = document.getElementById('day'+o);

    x.onfocus = function () {
      document.getElementById("days_hint").style.display = "inline";
    }
    x.onblur = function () {
      document.getElementById("days_hint").style.display = "none";
    }
  }


}

function addDrugiTermin()
{
    var cb = document.getElementById("drugi_termin_cb");

    if(!cb.checked)
    {
        var div = document.getElementById("drugi_termin_div");
        div.style.display = "none";
    }

    cb.onclick = function() {
        var div = document.getElementById("drugi_termin_div");
        if(div.style.display == "none") div.style.display = "block";
        else div.style.display = "none";
    }
}

part = 2;

function participantsList()
{
    for(var i=2; i<=16; i++)
    {
        div = document.getElementById('uczestnik'+i);
        inp = div.getElementsByTagName('input');
        if(inp[0].value != '')
        {
            div.style.display = 'block';
            part++;
        }
    }
}

function addParticipant()
{
    if(part <= 16)
    {
        document.getElementById('uczestnik'+part).style.display = 'block';
        part++;
    }
}

addLoadEvent(prepareInputsForHints);
addLoadEvent(addDrugiTermin);
addLoadEvent(participantsList);