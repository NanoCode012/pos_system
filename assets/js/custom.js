function search() {
    // Declare variables
    var input, filter, table, tr, td, i, j, isMatch, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) 
    {
        td = tr[i].getElementsByTagName("td");
        isMatch = false;
        if (td) 
        {
            for (j = 0; j < td.length; j++) 
            {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) 
                {
                    isMatch = true;
                    break;
                }
            }
        }
        if (! isMatch) tr[i].style.display = "none";
        else tr[i].style.display = "";
    }
}