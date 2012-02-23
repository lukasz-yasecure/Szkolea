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

  var calA = document.getElementById("doUzgodnienia");
  var calB = document.getElementById("exampleXIa");
  var calC = document.getElementById("exampleXIb");
  var cals = new Array(calA, calB, calC);

  for(var n=0; n<cals.length; n++)
  {
    cals[n].onfocus = function () {
      document.getElementById("cal_hint").style.display = "inline";
    }
    cals[n].onblur = function () {
      document.getElementById("cal_hint").style.display = "none";
    }
  }
}
addLoadEvent(prepareInputsForHints);