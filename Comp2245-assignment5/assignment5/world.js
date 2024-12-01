function remove_tags(str) {
    if ((str === null) || (str === '')) return false;
    else str = str.toString();
    return str.replace(/(<([^>]+)>)/ig, '');
}

let httpRequest;

window.onload = (evt) => {
    httpRequest = new XMLHttpRequest();

    // Add error handling for XMLHttpRequest
    if (!httpRequest) {
        alert('Cannot create an XMLHTTP instance');
        return false;
    }

    httpRequest.onreadystatechange = (e) => {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status === 200) {
                document.getElementById('result').innerHTML = httpRequest.responseText;
            } else {
                console.error('There was a problem with the request:', httpRequest.status);
            }
        }
    };

    // Fix the query selector for the toggle switch
    document.getElementById("lookup").onclick = (e) => {
        const qt = document.querySelector('.switch input[type="checkbox"]').checked;
        const search_request = remove_tags(document.getElementById('search').value);
        
        console.log("Log message")

        // Build the query parameters
        const queryParams = {
            "table": (qt ? 'cities' : 'countries'),
            "search": search_request || ''  // Handle empty search case
        };

        const request_url = `world.php?table=${queryParams.table}&search=${queryParams.search}`;
        httpRequest.open('GET', request_url, true);
        httpRequest.send();
    };
}