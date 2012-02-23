function changeSubsubcat()
{
    var sscats = new Array();
    sscats["1_1"] = new Array();
    sscats["1_1"].n = new Array("motywowanie", "komunikacja przełożony - podwładny");
    sscats["1_1"].ids = new Array("1_1_1", "1_1_2");
    sscats["1_2"] = new Array();
    sscats["1_2"].n = new Array("tworzenie misji i wizji firmy");
    sscats["1_2"].ids = Array("1_2_1");
    sscats["2_1"] = new Array();
    sscats["2_1"].n = new Array();
    sscats["2_1"].ids = Array();
    sscats["2_2"] = new Array();
    sscats["2_2"].n = new Array();
    sscats["2_2"].ids = Array();

    document.getElementById('search_subsubcat').options.length = 0;
    if(document.getElementById('search_moduls') != null) document.getElementById('search_moduls').options.length = 0;

    var opt = this.options[this.selectedIndex].value;

    if(opt != 0 && sscats[opt].n.length != 0)
    {
        for(var i=0; i<sscats[opt].n.length; i++)
        {
            document.getElementById('search_subsubcat').options[i+1] = new Option(sscats[opt].n[i]);
            document.getElementById('search_subsubcat').options[i+1].value = sscats[opt].ids[i];
        }
    }
}