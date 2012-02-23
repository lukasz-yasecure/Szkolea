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
}

addLoadEvent(prepareInputsForHints);