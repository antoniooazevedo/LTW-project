let hashtags = [];
let selectedHashtags = [];
let details = document.getElementById("selectedHashtags") == null;
let first_time = true;

function autocompleteMatch(input, search_terms) {
    if (input == '') {
        return search_terms;
    }
    let reg = new RegExp(input, 'i');
    return search_terms.filter(function(term) {
        if (term.match(reg)) {
            return term;
        }
    });
}

function showResults(value_to_search) {
    document.getElementById("result").style.display = "block";
    let val = value_to_search[0] == '#' ? value_to_search.substring(1) : value_to_search;
    let res = document.getElementById("result");
    res.innerHTML = '';
    let list = '';
    let terms = autocompleteMatch(val, hashtags);

    for (let i=0; i< Math.min(terms.length, 9); i++) {
        if (terms[i] === '')continue;
        list += '<li>#' + terms[i] + '</li>';
        res.style.backgroundColor = "#f5f5f5";
    }

    if (list === '') {
        list += '<li id="noResults">No results found</li>';
        res.style.backgroundColor = "red";
    }

    res.innerHTML = '<ul id="list" style="list-style-type: none; padding: 0; margin-bottom: 0.3em; font-size: 0.7em">' + list + '</ul>';
    let list1 = document.getElementById("list");

    let selectedHashtagsHTML = document.getElementById(details ? "hashtagInput" : "selectedHashtags");

    if (list1){
        list1.addEventListener('click', function(e) {
            if (e.target && e.target.matches('li') && (details || (!selectedHashtags.includes(e.target.innerHTML) && e.target.innerHTML !== 'No results found'))) {
                selectedHashtags.push(e.target.innerHTML);
                if(details) selectedHashtagsHTML.value = e.target.innerHTML;
                else selectedHashtagsHTML.innerHTML += '<li>' + e.target.innerHTML + '</li>';
            }
        });
    }

    selectedHashtagsHTML.addEventListener('click', function(e) {
        if (e.target && e.target.matches('li')) {
            let index = selectedHashtags.indexOf(e.target.innerHTML);
            selectedHashtags.splice(index, 1);
            e.target.remove();
        }
    }
    );
}

function setHashtags(arr){
    if (first_time){
        hashtags = arr;
        first_time = false;
    }
}

document.getElementById("submit").addEventListener("click", function(){
    let hashtagString = "";
    for (let i=0; i<selectedHashtags.length; i++){
        hashtagString += selectedHashtags[i].substring(1) + " ";
    }
    document.getElementById(details ? "hashtagInput" : "hashtags").value = hashtagString;
});
