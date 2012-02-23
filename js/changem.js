function changeModuls()
{
    var mods = new Array();
    mods["1_1_1"] = new Array();
    mods["1_1_1"].n = new Array('istota i znaczenie premii', 'wartościowanie pracy', 'wartościowanie kompetencji');
    mods["1_1_1"].ids = new Array('1_1_1_4', '1_1_1_5', '1_1_1_6');
    mods["1_2_1"] = new Array();
    mods["1_2_1"].n = new Array('miejsce misji i wizji w strategii firmy');
    mods["1_2_1"].ids = new Array('1_2_1_1');

    if(document.getElementById('search_moduls') != null)
    {
        document.getElementById('search_moduls').options.length = 1;

        var opt = this.options[this.selectedIndex].value;

        if(opt != 0 && mods[opt].n.length != 0)
        {
            for(var i=0; i<mods[opt].n.length; i++)
            {
                document.getElementById('search_moduls').options[i+1] = new Option(mods[opt].n[i]);
                document.getElementById('search_moduls').options[i+1].value = mods[opt].ids[i];
            }
        }
    }
}