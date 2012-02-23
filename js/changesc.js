function changeSubcat()
{
    var scats = new Array();
    scats[1] = new Array();
    scats[1].n = new Array("zarządzanie ludźmi", "zarządzanie strategiczne", "sprzedaż i marketing");
    scats[1].ids = new Array("1_1", "1_2", "1_3");
    scats[2] = new Array();
    scats[2].n = new Array("szybkie czytanie", "techniki pamięciowe", "coś tam");
    scats[2].ids = Array("2_1", "2_2", "2_3");
    scats[4] = new Array();
    scats[4].n = new Array();
    scats[4].ids = Array();

    document.getElementById('search_subcat').options.length = 0;
    document.getElementById('search_subsubcat').options.length = 0;
    if(document.getElementById('search_moduls') != null) document.getElementById('search_moduls').options.length = 0;

    var opt = this.options[this.selectedIndex].value;

    if(opt != 0 && scats[opt].n.length != 0)
    {
        for(var i=0; i<scats[opt].n.length; i++)
        {
            document.getElementById('search_subcat').options[i+1] = new Option(scats[opt].n[i]);
            document.getElementById('search_subcat').options[i+1].value = scats[opt].ids[i];
        }
    }
}